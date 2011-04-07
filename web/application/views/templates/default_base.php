<?php
$this->load->view('templates/default_header');  

if (is_array($path))
{
	foreach($path as $value)
	{
		$this->load->view($path);
	}
}
else
{
	list($controller, $function, $page_view) = explode("/", substr($path, 0));
	$this->load->view($path);
}	

$this->load->view('templates/default_footer');
?>