<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang='en-US' xml:lang='en-US' xmlns='http://www.w3.org/1999/xhtml'>
  <head>
    <title>Barometer submit</title>
    <meta content='text/html; charset=utf-8' http-equiv='Content-Type' />
    <script src="//ajax.googleapis.com/ajax/libs/prototype/1.6.0.3/prototype.js" type="text/javascript"></script>
    <link href="<?php echo BAROMETER_ASSETS_URL; ?>assets/barometer/css/barometer_iframe.css" media="all" rel="stylesheet" type="text/css" />
 
    <script type="text/javascript">
      
      function submitBarometer() {    
                    
        if ($F('description') == "") {
          alert("Please enter a description");
          return false;
        }
        
        if ($F('subject') == "") {
          alert("Please enter a subject");
          return false;
        }
        
        displayGoodMessage('<span id="submit"> Submitting...</span>');
        $('barometer-form').hide();
 
        submitForm();
      }
            
      function submitForm() {
        new Ajax.Request('/system/verify_barometer', {
          method: 'post',
          requestHeaders: { Accept: 'application/json' },
          parameters: $('barometer-form').serialize(true),

          onSuccess: function(transport) {
            var json = transport.responseText.evalJSON();

            if(json.message != null) {
              displayGoodMessage('<span id="success"> Message submitted. Thanks!</span><p><span class="success_option_return">Click the X to return to website or</span> <a href="/system/feedback_form/' + json.barometer_id + '" class="success_option_more">send some more feedback</a></p>');

				new Ajax.Request('/system/send_feedback', {
		          method: 'post',
		          parameters: $('barometer-form').serialize(true)
				});
            }
            else if(json.error != null) {
              displayBadMessage(json.error);
            }
            else {
              displayBadMessage("Invalid response format"); 
            }
          },
          onFailure: function(transport) {
            displayBadMessage("Failed to submit request, please try again");
          }

        });
      }      
      
      function displayGoodMessage(text) {
        $('status-message').update(text)
        $('status-message').show();
		$('overlay_preamble').update('');
      }
      
      function displayBadMessage(text) {
        $('status-message').update('<span id="error">' + text + '</span>');
        $('status-message').show();
        $('barometer-form').show();
      }
      
    </script>
    
  </head>
  
  <body style="overflow-y:visible;">
		<div id="barometer_page">
		
		<div id="overlay_preamble">
		<h2 id="barometer_title">Feedback and bugs, fixes, ideas..</h2>
		<p id="barometer_text">What are you thinking?</p>
		</div>
		
      <h2 id="status-message" style="display:none;" align="center"></h2>
      <form action="" id="barometer-form" method="post">   

        <p><textarea id="description" name="description" rows="6"></textarea>
           
        <h3 id="subject_header">Subject</h3>

        <p><input class="title" id="subject" name="subject" type="text"/></p>
          
      	<h3 id="email_header">Your email address (only if you want a reply)</h3>
        <p><input class="title" id="email" name="email" type="text"/></p>
        
         <input type="hidden" id="barometer_id" name="barometer_id" value="<?php echo $barometer_id; ?>"/>

        <input class="buttonsubmit" name="commit" value="Send feedback" type="submit" onClick = "submitBarometer(); return false;"/>      

        <br clear="all" />&nbsp; 
      </form>

    </div>
    
  </body>
  
</html>