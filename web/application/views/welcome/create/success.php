<h2>Your Barometer has been created!</h2>

<p>Copy the following code, then paste it onto every page you want to add a Barometer feedback tab to, immediately before the &lt;/body&gt; tag.</p>

<form id="successfully_created">
<textarea id="barometer_code" readonly >
<style type='text/css'>@import url('<?php echo BAROMETER_ASSETS_URL; ?>assets/barometer/css/barometer.css');</style>
<script src='<?php echo BAROMETER_ASSETS_URL; ?>assets/barometer/javascripts/barometer.js' type='text/javascript'></script>
<script type="text/javascript" charset="utf-8">
  BAROMETER.load('<?php echo $barometer_id; ?>');
</script></textarea>
</form>

<h3>A note about spam filters</h3>

<p>Please note the feedback emails are sent from <?php echo FROM_EMAIL; ?>. If you are having problems receving them please check your spam filter, and enable this address if required.</p>

<h3>And thats it!</h3>

<p>If you run into any problems, give me a shout using the Barometer on this website.</p>

<p>Thanks,
<br /><a href="http://twitter.com/ollierattue>Ollie Rattue</a></p>