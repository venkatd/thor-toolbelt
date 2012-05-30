<? 
	$page = $_GET['page'];
?>
	
<script type="text/javascript">
	$(function() {	
		//make button a jQuery button
		$("button").button();
	
		//define variables
		var name = $("#c_name"), email = $("#c_email"), phone = $("#c_phone"),  allFields = $([]).add(name).add(email).add(phone), tips = $(".validateTips");

		//function that updates the message
		function updateTips(t) {
			tips.text(t).addClass('ui-state-highlight');
			setTimeout(function() { tips.removeClass('ui-state-highlight', 1500);}, 500);
		}

		//function that checks the length of the field min and max
		function checkLength(o,n,min,max) {
			if ( o.val().length > max || o.val().length < min ) {
				o.addClass('ui-state-error');
				updateTips("Length of " + n + " must be between " + min + " and " + max + ".");
				return false;
			} else {
				return true;
			}
		}

		//function that checks the regular expression
		function checkRegexp(o,regexp,n) {
			if ( !( regexp.test( o.val() ) ) ) {
				o.addClass('ui-state-error');
				updateTips(n);
				return false;
			} else {
				return true;
			}
		}
		
		//validation form attached to the sumbitForm button
		$("#submitForm").click( function() {
		
			//by the default, validation is okay, ant there is no error
			var bValid = true;
			allFields.removeClass('ui-state-error');

			//check for required field length
			bValid = bValid && checkLength(name,"your name",3,50);
			bValid = bValid && checkLength(email,"your email address",6,80);
			bValid = bValid && checkLength(phone,"your phone",7,16);

			//check for regular expression in names - only a-z and 0-9 allowed - alphanumeric
			bValid = bValid && checkRegexp(name,/^[a-z]([0-9a-z_])+$/i,"Name should consist of letters and numbers only.");
			
			//check for regular expression in email - correct emails
			bValid = bValid && checkRegexp(email,/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i,"Your email address is not valid.  I.E.: unreal@gmail.com");
			
			//if no errors - submit form
			if (bValid) $("#contact").submit();
		});
		
		//validation form attached to the sumbitForm button
		$("#submitForm2").click( function() { $("#refferal").submit() });
		
		
	});
	
</script> 
  
  
<style>
	.contactTable td { padding-bottom:6px; }
</style>

<table width="100%" cellpadding="0" cellspacing="0" class="contactTable">
<tr>
  <td width="440" valign="top">
		<div style="background:url(images/contact.gif) no-repeat top left; padding-left:85px; font-size:20px; letter-spacing:-1px;">
			<div style="line-height:64px;">718.908.<span style="display:none;">SKYPE OFF</span>0800</div>
			<div style="line-height:64px;"><a href="ro.vcf">Download Info Card</a></div>
			<div style="line-height:64px;"><a href="mailto:<?=$adminEmail;?>"><?=$adminEmail;?></a></div>
			<div style="line-height:32px;">
				<input type="checkbox" id="smsOut" onchange="document.getElementById('smsIn').checked = this.checked" /> <span style="color:red;">Check here for emergency?</span><br />
				Fill in the contact form to send SMS
			</div>
		</div>
	</td>
  <td valign="top">
<?  
	if(isset($_POST['email'])){

		//send text message
		$from = stripslashes($_POST['name']);
		$email = stripslashes($_POST['email']);
		if(isset($_POST['message'])) $text = stripslashes($_POST['message']);
		if(isset($_POST['phone'])) $phone = stripslashes($_POST['phone']);
		$headers = "MIME-Version: 1.0\n" ;
		$headers .= "Content-Type: text/html; charset=\"iso-8859-1\"\n"; 
		$headers .= 'From:' . $from ."<br>";
		$message = 'Email: '. $email .'<br>';
		if(isset($_POST['phone'])) $message .= 'Phone: '. $phone ."<br>";
		if(isset($_POST['message'])) $message .= '<br>Message: '. $text;
		if(isset($_POST['smsIn'])) mail($phoneNumber, "Website Contact", $message, $headers);

		//send e-mail to support
		$to = $adminEmail;
		$subject = 'Website Contact';
		$random_hash = md5(date('r', time()));
		$headers  = "From: " . strip_tags($_POST['email']) . "\r\n";
		$headers .= "Reply-To: ". strip_tags($_POST['email']) . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		ob_start(); 
?>
    <h2>Website Contact</h2>
    <p>From <?=$_POST['name'];?>:</p>
    <? if(isset($_POST['phone'])) { ?><p>Phone: <?=$_POST['phone'];?></p><? } ?>
    <? if(isset($_POST['mobile'])) { ?><p>Mobile: <?=$_POST['mobile'];?></p><? } ?>
    <? if(isset($_POST['boro'])) { ?><p>Borough: <?=$_POST['boro'];?></p><? } ?>
    <p><?=$_POST['message'];?></p>
<?
		$message = ob_get_clean();
		$mail_sent = @mail( $to, $subject, $message, $headers );
		
		if($mail_sent) { ?><h3>Thank you for contacting <?=$companyName;?></h3>We will get back to you shortly.<? }
		else { ?><b>There has been a problem submitting this email.</b><br><br>Send your message to <a href="mailto:<?=$adminEmail;?>"><?=$adminEmail;?></a>.<? }
	
		//send confirmation e-mail
		$to = strip_tags($_POST['email']);
		$subject = 'Thank your for contacting New Style Driving School';
		$random_hash = md5(date('r', time()));
		$headers  = "From: " .$adminEmail. " \r\n";
		$headers .= "Reply-To: " .$adminEmail. " \r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		ob_start(); 
?>
		<table style="background:#f5f5f5; width:580px;" cellspacing="1" cellpadding="1">
    <tr>
    	<td style="padding:5px 25px 5px 5px;"><img src="http://<?=$siteName;?>/images/logo.png" /></td>
      <td><h2 style="margin:0; padding:5px 0;">Hello <?=$_POST['name'];?></h2></td>
    </tr>
    <tr>
    	<td colspan="2" style="padding:10px 10px 25px; background:white;">      	
        <p>Thank you for contacting <?=$companyName;?></p>
        <p>We have received your message and will get back to you shortly.</p>
        <p>Best Regards,</p>
        <p><?=$companyName;?></p>
        --------------<br />
        <a href="http://<?=$siteName;?>"><?=$companyName;?></a><br />
        <a href="mailto:<?=$adminEmail;?>">Email Us</a>
    	</td>
    </tr>
    </table>
<?
		$message = ob_get_clean();
		$mail_sent = @mail( $to, $subject, $message, $headers );
	}	
	
	//SHOW EMAIL FORM
	else {	
?>
  <div id="dialog-form" style="padding-bottom:10px;" title="Contact New Style Driving School">
  	<p><? include_once ('admin/content/contact.htm'); ?></p><br />
    <p class="validateTips" style="margin-bottom:10px;">Fields in <span style="color:red;">red</span> are required.</p>
  
    <form name="contact" id="contact" method="post">
    	<table width="100%" cellpadding="0" cellspacing="0">
      <tr>
      	<td style="padding-right:20px;"><label for="name" style="color:red;">Name</label></td>
        <td><input type="text" name="name" id="c_name" maxlength="50" tabindex="1" class="text ui-widget-content ui-corner-all" /></td>
        <td rowspan="5" style="vertical-align:top; padding-left:25px;">
					<input type="checkbox" id="smsIn" name="smsIn" onchange="document.getElementById('smsOut').checked = this.checked" /> <span style="color:red;">Emergency?</span> Check to send SMS. Read above.
					<div style="line-height:8px; font-size:8px;">&nbsp;</div>
          <label for="message">Message</label><br />
          <textarea name="message" id="c_message" tabindex="4" class="text ui-widget-content ui-corner-all" style="width:285px; height:68px;"></textarea>
        </td>
      </tr>
      <tr>
      	<td><label for="email" style="color:red;">Email</label></td>
        <td><input type="text" name="email" id="c_email" maxlength="80" tabindex="2" class="text ui-widget-content ui-corner-all" /></td>
      </tr>
      <tr>
      	<td><label for="phone" style="color:red;">Phone</label></td>
        <td><input type="text" name="phone" id="c_phone" maxlength="12" tabindex="3" class="text ui-widget-content ui-corner-all" /></td>
      </tr>
      <tr>
      	<td><label for="phone">Mobile</label></td>
        <td><input type="text" name="mobile" id="c_mobile" maxlength="12" tabindex="3" class="text ui-widget-content ui-corner-all" /></td>
      </tr>
      <tr>
      	<td><label for="phone">Boro</label></td>
        <td><input type="text" name="boro" id="c_boro" maxlength="12" tabindex="3" class="text ui-widget-content ui-corner-all" /></td>
      </tr>
      </table>
    </form>
  </div>
  <button id="submitForm">Contact Us Now</button>
<?
	}
?>


<?  
	if(isset($_POST['refferal'])){


		//send e-mail to support
		$to = $adminEmail;
		$subject = 'Website Refferal';
		$random_hash = md5(date('r', time()));
		$headers  = "From: " . strip_tags($_POST['email']) . "\r\n";
		$headers .= "Reply-To: ". strip_tags($_POST['email']) . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		ob_start(); 
?>
    <h2>Website Refferal</h2>
		<p><b>Referrer's Information:</b></p>
    <? if(isset($_POST['r1_name'])) { ?><p>Name: <?=$_POST['r1_name'];?></p><? } ?>
    <? if(isset($_POST['r1_phone'])) { ?><p>Phone: <?=$_POST['r1_phone'];?></p><? } ?>
    <? if(isset($_POST['r1_email'])) { ?><p>Email: <?=$_POST['r1_email'];?></p><? } ?>
		<br />
		<p><b>Friend's Information:</b></p>
    <? if(isset($_POST['r2_name'])) { ?><p>Name: <?=$_POST['r2_name'];?></p><? } ?>
    <? if(isset($_POST['r2_phone'])) { ?><p>Phone: <?=$_POST['r2_phone'];?></p><? } ?>
    <? if(isset($_POST['r2_email'])) { ?><p>Email: <?=$_POST['r2_email'];?></p><? } ?>
<?
		$message = ob_get_clean();
		$mail_sent = @mail( $to, $subject, $message, $headers );
		
		if($mail_sent) { ?><h3 style="margin-top:15px;">Thank you for submitting your refferal</h3>We have received the information you submitted.<? }
		else { ?><b>There has been a problem submitting this email.</b><br><br>Send your message to <a href="mailto:<?=$adminEmail;?>"><?=$adminEmail;?></a>.<? }
	
	}	
	
	//SHOW EMAIL FORM
	else {	
?>
  <div id="dialog-form2" style="padding :10px 0;" title="New Style Driving School Refferal">
  	<p>Here at New Style Driving School we aim for 100% client satisfaction rate.  A refferal is your best compliment.  Please tell us who reffered you or who would you like to reffer.</p><br />
  
    <form name="refferal" id="refferal" method="post">
			<input type="hidden" name="refferal" value="1" />
    	<table width="100%" cellpadding="0" cellspacing="0">
      <tr>
      	<td><label for="name">Your Name</label></td>
        <td><input type="text" name="r1_name" id="r1_name" maxlength="50" tabindex="4" class="text ui-widget-content ui-corner-all" /></td>
      	<td><label for="name">Friend's Name</label></td>
        <td><input type="text" name="r2_name" id="r2_name" maxlength="50" tabindex="7" class="text ui-widget-content ui-corner-all" /></td>
      </tr>
      <tr>
      	<td><label for="email">Your Email</label></td>
        <td><input type="text" name="r1_email" id="r1_email" maxlength="80" tabindex="5" class="text ui-widget-content ui-corner-all" /></td>
      	<td><label for="email">Friend's Email</label></td>
        <td><input type="text" name="r2_email" id="r2_email" maxlength="80" tabindex="8" class="text ui-widget-content ui-corner-all" /></td>
      </tr>
      <tr>
      	<td><label for="phone">Your Phone</label></td>
        <td><input type="text" name="r1_phone" id="r1_phone" maxlength="12" tabindex="6" class="text ui-widget-content ui-corner-all" /></td>
      	<td><label for="phone">Friend's Phone</label></td>
        <td><input type="text" name="r2_phone" id="r2_phone" maxlength="12" tabindex="9" class="text ui-widget-content ui-corner-all" /></td>
      </tr>
      </table>
    </form>
  </div>
  <button id="submitForm2">Submit Referral</button>
<?
	}
?>











  </td>
</tr>
</table>