<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Links_library
{
	// Protected or private properties
	protected $_table;
	
	// Constructor
	public function __construct()
	{
		if (!isset($this->CI))
		{
			$this->CI =& get_instance();
		}

		$this->CI->config->load('database_tables', TRUE);
		$this->_table = $this->CI->config->item('database_tables');
	}
}

/* End of file Links_library.php */
/* Location: ./application/libraries/Links_library.php */