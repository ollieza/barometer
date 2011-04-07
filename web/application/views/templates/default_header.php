<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php echo APP_NAME; ?> <?php if ($title == NULL) { echo ' - feedback tabs for all'; } else { echo " - $title"; }?></title>
<meta name="description" content="Get a free feedback tab for your website today."> 
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/assets/website/css/core.css" media="screen" />

<script type="text/javascript" src="<?php echo base_url(); ?>assets/website/javascripts/jquery.min.js"></script>

<!-- jQuery form validation -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/website/javascripts/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/website/javascripts/jquery.metadata.js"></script>

<!-- jQuery labelify -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/website/javascripts/jquery.labelify.js"></script>

</head>
<body id="<?php echo $body_id; ?>">

<div id="wrapper">
        <span class="count"><?php echo $barometer_count; ?> Barometers installed</span>

<h1 class="title"><a href="<?php echo base_url(); ?>"><?php echo APP_NAME; ?></a></h1>

<div id="page">