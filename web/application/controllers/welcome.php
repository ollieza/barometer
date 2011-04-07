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

class Welcome extends CI_Controller {

	function Welcome()
	{
		parent::__construct();
			
		$this->load->model('barometer_model');
		$this->load->helper('page_helper');
		$this->load->helper('template_helper');
		
		$this->data['barometer_count'] = $this->barometer_model->count_barometers();
		
		if (ENABLE_PROFILER == TRUE)
		{
			$this->output->enable_profiler(TRUE);
		}
	}
	
	// --------------------------------------------------------------------
		
	function index()
	{
		build_page('welcome/index/content', $this->data, NULL, 'homepage');
	}
	
	// --------------------------------------------------------------------
		
	function create($type = NULL)
	{
		if ($type == 'ajax')
		{
			$this->data['barometer_id'] = $this->session->userdata('barometer_id');
			
			if ($this->data['barometer_id'] == NULL)
			{
				// User has come direct to web page, possibly from a search engine,
				// instead of this page being loaded via AJAX
				redirect();
			}
			
			$this->load->view('welcome/create/success', $this->data );
		}
		else
		{
			$this->form_validation->set_rules('email_address','Email','required|trim|xss_clean|valid_email|max_length[200]');

			$this->form_validation->set_error_delimiters('<br /><span class="error">', '</span>');

			if ($this->form_validation->run() == FALSE) // validation hasn't been passed
			{
				// set a flash data error message
				$this->session->set_flashdata( 'flash', 'Please make sure you entered a valid email address', 'error');
				redirect();
			}
			else // passed validation proceed to post success logic
			{
				// check to see whether this is a page reload via session
				$barometer_id = $this->barometer_model->create_barometer(set_value('email_address'));

				if ($barometer_id == FALSE)
				{
					// set a flash data error message
					$this->session->set_flashdata( 'flash', 'There was a problem creating your Barometer. Our techies have been pinged, and will try and fix the problem ASAP. Please try again later today. Thanks.', 'error');
					log_message('error', "Creating a new Barometer with a valid email address - couldn't write to database");
					redirect();
				}
				else
				{
					if ($type == 'default')
					{
						$this->data['barometer_id'] = $barometer_id;
						$this->data['barometer_count'] =  $this->db->count_all('barometers');
						build_page('welcome/create/success', $this->data, 'Successfully created your Barometer', 'success');
					}
					else
					{
						$this->session->set_userdata('barometer_id', $barometer_id);
					}
				}
			}
		}
	}
	
	// --------------------------------------------------------------------
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */