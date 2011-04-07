<script type="text/javascript">
	
$(document).ready(function() {
				
	// labelify plugin
	$(":text").labelify();
	
	// form validation plugin
	$("#create").validate({
	
		highlight: function(element, errorClass) {
		  },
	
		errorPlacement: function(error, element) {
		     error.insertBefore("#email_address");
		   },
		
	
		errorClass: "error",
		validClass: "success",
	
		rules: {
			email_address: {
				required: true,
				email: true
			}							
		},
		
		messages: {	
			email_address:  {
				required: 'Please enter a valid e-mail address.',
				email: 'Please enter a valid e-mail address.'
			}
		}
	});
	
	// Do some nice AJAX stuff when we have a validation form
	$('#submit').click(function() {

			if ($('#email_address').attr('class') == 'success')
			{
				var email_address = $('#email_address').val();

		        $.post("<?php echo site_url('welcome/create'); ?>", {email_address: email_address}, function() {

					$('#page').html	('<div id="create_barometer_loading"> Building your Barometer...</div>');
					setTimeout("$('#page').load('<?php echo site_url('welcome/create/ajax'); ?>')",1750);
					$('#email_address').val('');
		        });
			}
    });
});
</script>

<h2>The easiest way to add a stylish feedback form to your website.</h2>

<div class="left_column">
<ul class="advantages">
        <li>Hide your email address to avoid spam.</li>
        <li>In-page form tab increases user feedback rate.</li>
		<li>Simple fuss-free form without a full support system.</li>
		<li>Receive feedback into your normal inbox - no login required.</li>
		<li>The world's simplest signup. No data capturing here!</li>
		<li>Completely free. Forever.</li>
</ul>

<div class="signup">
<h3>The world's quickest signup!</h3>

<p>Enter your email address to create your barometer. You will get a few lines of code to add to your website to enable your feedback tab.</p>

<form id="create" name="create" action="javascript:function dummy() { return false; }">

<?php echo form_error('email_address'); ?>
<br />
<input id="email_address" type="text" name="email_address" maxlength="200" value="<?php echo set_value('email_address'); ?>" title="Your email address" />
<input type="submit" id="submit" name="submit" value="Get your Barometer now" />

<?php echo form_close(); ?>
</div>
</div>

<div class="right_column">
	<div class="screenshots">
		<img src="<?php echo base_url(); ?>assets/website/images/formigniter_barometer_closed-396-wide.png" style="border:1px #CCCCCC solid;padding-bottom: 11px;" />
	</div>
</div>