<?php

/**
 * Barometer
 *
 * The web's simplest feedback form
 * 
 * @package   Barometer
 * @license   http://www.opensource.org/licenses/mit-license.php
 */

class Barometer_model extends CI_Model 
{	
	// Protected or private properties
	protected $_table;
	
	function __construct()
    {
        parent::__construct();
    	$this->_table = $this->config->item('database_tables');
	}
	
	// --------------------------------------------------------------------
	
	function count_barometers()
	{
		return $this->db->count_all($this->_table['barometers']);
	}

	// --------------------------------------------------------------------

	function get_recipient_email($barometer_id = NULL)
	{
		$query = $this->db->query("SELECT recipient_email
									FROM {$this->_table['barometers']}
			                		WHERE barometer_id = ".$this->db->escape($barometer_id)."");
                
        if ($query->num_rows == 1)
		{
			$barometer = $query->row();
			return $barometer->recipient_email;
		}

		return FALSE;	
	}
	
	// --------------------------------------------------------------------
	
	function check_ip_for_spam($sender_ip)
	{
		return FALSE;
		
		// check spam and ban table
		// -> if ban return TRUE
		// -> if within 30 mins of spam return TRUE
		
		// if ip address has sent less than 5 total return FALSE;
		
		// if more than 5 total if time between last 5 is = or less than 90 seconds
		
		$query = $this->db->query("SELECT COUNT('email_id') as feedback_sent
									FROM {$this->_table['emails_sent']}
			                		WHERE sender_ip = INET_NTOA(".$this->db->escape($sender_ip).")
									AND UNIX_TIMESTAMP(`sent`) >= (UNIX_TIMESTAMP() - 90)");
	
		$ip_address = $query->row();
		
		if ($ip_address->feedback_sent >= 5) // ip has sent 5 or more emails in the last 90 seconds
		{
			return TRUE;
		}
		
		return FALSE;
	}	
	
	// --------------------------------------------------------------------
	
	function get_email_queue($barometer_id = NULL)
	{
		$where = NULL;
		
		if ($barometer_id)
		{
			$where = "WHERE email_queue.barometer_id = ".$this->db->escape($barometer_id)."";
		}
		
		$query = $this->db->query("SELECT email_queue.*, barometers.recipient_email
									FROM {$this->_table['email_queue']} email_queue
									JOIN {$this->_table['barometers']} barometers on email_queue.barometer_id = barometers.barometer_id
									$where");
	
		return $query;
	}
	
	// --------------------------------------------------------------------
	
	function create_barometer($email_address)
	{
		// the baromter_id needs to be unique as it is the primary key
		
		$unique = FALSE;
		$loop = 1;
	
		while($unique == FALSE)
		{
			// generate a reset code
			$barometer_id = $this->get_unique_string(20);
		
			// Run a query to make sure that this reset code is unique
			$query = $this->db->query("SELECT barometer_id
										FROM {$this->_table['barometers']} 
										WHERE barometer_id = ".$this->db->escape($barometer_id)."");
			
			if ($query->num_rows == 0)
			{
				$unique = TRUE;
			}
			$loop++;
		}
		
		$this->db->insert($this->_table['barometers'], array('barometer_id' => $barometer_id, 'recipient_email' => $email_address, 'created' => date("Y-m-d H:i:s"), 'active' => 1));

		if ($this->db->affected_rows() == '1')
		{
			return $barometer_id;
		}

		return FALSE;
	}
	
	// --------------------------------------------------------------------
	
	function save_email_sent($barometer_id = NULL, $sender_ip_address = NULL)
	{
		$datetime = date("Y-m-d H:i:s");
		
		$query = $this->db->query("INSERT INTO {$this->_table['emails_sent']} (barometer_id, sender_ip, sent) VALUES (".$this->db->escape($barometer_id).", INET_ATON(".$this->db->escape($sender_ip_address)."), ".$this->db->escape($datetime).")");

		if ($this->db->affected_rows() == '1')
		{
			return TRUE;
		}

		return FALSE;
	}
	
	// --------------------------------------------------------------------
	
	function add_email_to_queue($email_data = NULL)
	{
		$email_data['sender_ip'] = ip2long($email_data['sender_ip']);
		
		$this->db->insert($this->_table['email_queue'], $email_data);
		
		if ($this->db->affected_rows() == '1')
		{
			return TRUE;
		}

		return FALSE;
	}

	// --------------------------------------------------------------------
	
	function delete_email_from_queue($email_queue_id = NULL)
	{
		$this->db->where('email_queue_id', $email_queue_id);
		$this->db->delete($this->_table['email_queue']);
		
		if ($this->db->affected_rows() == '1')
		{
			return TRUE;
		}

		return FALSE;
	}

	// --------------------------------------------------------------------
	
	function save_failed_email_attempt($email_queue_id = NULL)
	{
		$this->db->where('email_queue_id', $email_queue_id);
		$this->db->update($this->_table['email_queue'], array('last_attempt' => date("Y-m-d H:i:s")));
		
		if ($this->db->affected_rows() == '1')
		{
			return TRUE;
		}

		return FALSE;
	}
	
	// --------------------------------------------------------------------
	
	function record_refferal($barometer_id, $reffering_domain)
	{
		$this->db->insert($this->_table['barometer_form_loads'], array('barometer_id' => $barometer_id, 'reffering_domain' => $reffering_domain));
				
		if ($this->db->affected_rows() == '1')
		{
			return TRUE;
		}

		return FALSE;
	}
	
	// --------------------------------------------------------------------
	
	private function get_unique_string($length = NULL)
	{	
		$base='ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz123456789';
		$max=strlen($base)-1;
		$activatecode='';
		mt_srand((double)microtime()*1000000);
		
		while (strlen($activatecode)<$length+1)
		  $activatecode.=$base{mt_rand(0,$max)};
		
		return $activatecode;
	}
	
	// --------------------------------------------------------------------	
}	
?>
