<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Barometer
 *
 * The web's simplest feedback form
 * 
 * @package   Barometer
 * @author    Ollie Rattue, Too many tabs <orattue[at]toomanytabs.com>
 * @copyright Copyright (c) 2011, Ollie Rattue
 * @license   http://www.opensource.org/licenses/mit-license.php
 * @link      http://github.com/ollierattue/Barometer
 * @link	  http://getbarometer.org
 */

class System extends CI_Controller {

	function System()
	{
		parent::__construct();
		
		$this->load->model('barometer_model');
		$this->load->helper('page_helper');
		
		if (ENABLE_PROFILER == TRUE)
		{
			$this->output->enable_profiler(TRUE);
		}
	}
	
	// --------------------------------------------------------------------
		
	// called via AJAX from the feedback popup form
	function verify_barometer()
	{
		$sender_ip = $_SERVER["REMOTE_ADDR"];
		
		// simple spam protection
		// if 5 feedbacks are sent from the same ip in the space of 1 minute then we ban the ip for 30 minutes
		$spam = $this->barometer_model->check_ip_for_spam($sender_ip);
		
		if ($spam == TRUE)
		{
			$data = array("error" => "You have been blocked for sending too many feedback messages. Please try again later.");
			$this->json_response($data);
			exit;
		}
	
		$barometer_id = $_POST["barometer_id"];
		
		$recipient_email = $this->barometer_model->get_recipient_email($barometer_id);
		
		if ($recipient_email == FALSE)
		{
			$data = array("error" => "This feedback form is no longer active.");
			$this->json_response($data);
			exit;
		}
		
		// the barometer exists and the IP is not marked for spam
		
		// We add to the mail queue 
		
		$subject = $_POST["subject"];
		$message = $_POST["description"];
		$sender_email = $_POST["email"];

		if ($sender_email == NULL)
		{
			$reply_email = NO_REPLY_EMAIL;
			$from_email = FROM_EMAIL;
			$from_name = FROM_NAME;
		}
		else
		{
			$reply_email = $sender_email;
	       	$from_email = $sender_email;
			$from_name = $sender_email;	
			$message .= "

--------------------------------------------------------------------------

Note: The sender left an email address ({$reply_email}) so you can reply to their query";
		}
		
		$base_url = base_url();
		
		$message = <<<ENDINGTEXT
{$message}

--------------------------------------------------------------------------

Sent via Barometer ({$base_url})
ENDINGTEXT;
		
		$email_data = array(
							'email_from_email'		=> $from_email,
							'email_from_name' 		=> $from_name,
							'email_reply_email'		=> $reply_email,
							'email_subject' 		=> $subject,
							'email_message' 		=> $message,
							'sender_ip' 			=> $sender_ip,
							'barometer_id' 			=> $barometer_id
		);
		
		$this->barometer_model->add_email_to_queue($email_data);
	
		// Lets send the user a success message. IF the email does not send it is logged and 
		// the system will try again at a later point. The user however does not need to know
		// this
		$data = array("message" => "Success", "barometer_id" => $barometer_id);
		$this->json_response($data);
		exit;	
	}

	// --------------------------------------------------------------------
	
   	function feedback_form($barometer_id)
   	{
		if (isset($_SERVER['HTTP_REFERER']))
		{
			$this->barometer_model->record_refferal($barometer_id, parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST));
		}
		
		// ** TODO - IS THIS WORTH DOING ?? ***
		// check to see if barometer id exists in db
		// if it doesn't then I need to show a custom 404 page
		$this->data['barometer_id'] = $barometer_id;
   		
		$this->load->view('system/feedback_form/form', $this->data);
   	}
	
	// --------------------------------------------------------------------
	
	function send_feedback()
	{
		$barometer_id = $_POST["barometer_id"];
		$this->process_email_queue(PROCESS_EMAIL_QUEUE_KEY, $barometer_id);
		
		// not actually read by the Javascript but we should always return a response
		$data = array("message" => "Success", "barometer_id" => $barometer_id);
		$this->json_response($data);
		exit;
	}

	// --------------------------------------------------------------------
	
	// run via a cron every hour or so to retry failed email attempts
	// also used by send_email which is called via AJAX after we veryify the barometer is valid
	
	function process_email_queue($key = NULL, $barometer_id = NULL)
	{
		if ($key != PROCESS_EMAIL_QUEUE_KEY) // a small bit of protection
		{
			//echo 'Incorrect key';
			return FALSE;
		}
		
		$email_queue = $this->barometer_model->get_email_queue($barometer_id);
		
		if ($email_queue->num_rows() == 0) // no emails to process
		{
			//echo 'No emails to process';
			return TRUE;
		}
		
		$this->load_email();
		
		foreach($email_queue->result() as $email)
		{
			$this->email->clear();
			
			$this->email->from($email->email_from_email, $email->email_from_name);		
			$this->email->to($email->recipient_email);
			$this->email->reply_to($email->email_reply_email);
			$this->email->subject($email->email_subject);
			
			$message = str_replace("\n", "\r\n", $email->email_message);
			$message = wordwrap($message, 70);
			
		    $this->email->message($message);

		    if ($this->email->send()) // email sent succesffully
	     	{
				// save the email sent time for statistics
				if ($this->barometer_model->save_email_sent($email->barometer_id, long2ip($email->sender_ip)))
				{
					// delete email from email_queue table
					$this->barometer_model->delete_email_from_queue($email->email_queue_id);
				}	
	     	}
	     	else // email failed
	     	{	
				// update last attemt timestamp
				$this->barometer_model->save_failed_email_attempt($email->email_queue_id);
	     	}
		}
		
		//echo 'Mail queue cleared';
		return TRUE;
	}
	
	// --------------------------------------------------------------------
		
	private function json_response($data = NULL)
	{
		header('Content-type: application/x-json');
		//encode and return json data...
		echo json_encode($data);
	}
	
	// --------------------------------------------------------------------
		
	private function load_email()
	{
		$config = array();
		
		if (USE_SMTP == TRUE)
		{
			$config = array(
			    'protocol' 		=> MAIL_PROT,
			    'smtp_host' 	=> SMTP_HOST,
			    'smtp_port' 	=> SMTP_PORT,
			    'smtp_user' 	=> SMTP_USER,
			    'smtp_pass' 	=> SMTP_PASS,
			    'smtp_timeout' 	=> SMTP_TIMEOUT,
			    'mailtype'  	=> MAIL_TYPE,
				'charset'		=> MAIL_CHARSET
			);
		}
		
		$this->load->library('email', $config);
		$this->email->clear();
		$this->email->set_newline("\r\n");
	}
	
	// --------------------------------------------------------------------
	
	// This function is left so we can quickly test the SMTP setup.
	
	private function test_email()
	{
	 	$email_config = array();

		if (USE_SMTP == TRUE)
		{
			$email_config['protocol'] = MAIL_PROT;
			$email_config['smtp_host'] = SMTP_HOST;
			$email_config['smtp_user'] = SMTP_USER;
			$email_config['smtp_pass'] = SMTP_PASS;
			$email_config['smtp_port'] = SMTP_PORT;
			$email_config['smtp_timeout'] = SMTP_TIMEOUT;
			$emaiL_config['charset'] = MAIL_CHARSET;
		}

		$this->load->library('email', $email_config);
		$this->email->clear();
		$this->email->set_newline("\r\n");

		$this->email->from(FROM_EMAIL, FROM_NAME);

		$this->email->to(ADMIN_EMAIL);
		$this->email->reply_to(NO_REPLY_EMAIL);
		$this->email->subject('test_email() - smtp setup test');

		$message = "Alistair Darling unveiled a nakedly political pre-election Budget today, hoping to use one-off taxes on the rich to delay inevitable cuts in public services until well into what would be Labour's fourth term in office.

With barely six weeks to go before an election widely expected on May 6, the Chancellor was unable to conjure up a classic Budget giveaway for voters, his hands tied by record levels of public sector borrowing.

But he told the Commons that a tax on bankers' bonuses had already brought £2 billion into Treasury coffers – more than twice the amount expected – and the Government had earned £8 billion in fees and charges on its bank support programme.

Partly as a result of that – and because, he said, of the decisions taken to help the UK economy through the worst global recession in 60 years – borrowing this year would be £11 billion lower than previously forecast at £167 billion.";

		$message = str_replace("\n", "\r\n", $message);
		$message = wordwrap($message, 70);

		$this->email->message($message);

   		if ($this->email->send()) // email sent succesffully
    	{
			echo 'email sent';
		}
    	else // email failed
    	{
    		echo 'email failed';
			$this->email->print_debugger();
		}
	}
	
	// --------------------------------------------------------------------	
}

/* End of file system.php */
/* Location: ./application/controllers/system.php */