<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// --------------------------------------------------------------------

## Returns a language file using sprintf to substitute variable where necessary

function language($language_key = NULL, $variable = NULL)
{
	$CI =& get_instance();
	$CI->lang->load('flash_messages','English');
	
	if (!empty($variable))
	{
		return sprintf($CI->lang->line($language_key),$variable);
	}
	
	return $CI->lang->line($language_key);
}

// --------------------------------------------------------------------

/* End of file general_helper.php */
/* Location: ./application/helpers/general_helper.php */