<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// --------------------------------------------------------------------

/** 
* function build_page()
*
* a simple template system
* @access private
* @param $page - string
* @param $title - string
* @param $data - string
* @return string
*/

function build_page($path, $data = null, $title = null,$body_id = 'default')
{
	if ($title = NULL)
	{
		$title = APP_NAME;
	}
	
	list($controller, $function, $page_view) = explode("/", substr($path, 0));
    
	$data['path'] = $path;
	$data['title'] = $title; 	
	$data['body_id'] = $body_id;
	$data['controller'] = $controller;
	$data['function'] = $function;
	$data['page_view'] = $page_view;

	$CI =& get_instance();
   	$CI->load->vars($data); 
	$CI->load->view('templates/default_base');
}

// --------------------------------------------------------------------
/* End of file template_helper.php */
/* Location: ./application/helpers/template_helper.php */