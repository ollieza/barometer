<?php
class MY_Session extends CI_Session {

	// --------------------------------------------------------------------
	# Modified to print out <div> with success or error css class
	
	/**
	 * Add or change flashdata, only available
	 * until the next request
	 *
	 * @access	public
	 * @param	mixed
	 * @param	string
	 * @return	void
	 */
	function set_flashdata($newdata = array(), $newval = '',$type = NULL)
	{
		if (is_string($newdata))
		{
			$newdata = array($newdata => $newval);
		}

		if (count($newdata) > 0)
		{
			foreach ($newdata as $key => $val)
			{
				$flashdata_key = $this->flashdata_key.':new:'.$key;

				if ($type == 'success')
				{
					$val = '<div class="flash_success">'.$val.'</div>';
				}

				if ($type == 'error')
				{
					$val = '<div class="flash_error">'.$val.'</div>';
				}

				$this->set_userdata($flashdata_key, $val);
			}
		}
	}
	
	// --------------------------------------------------------------------
}