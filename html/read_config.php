<?php
session_start();
include_once 'includes/functions.php';
?>

<?php
if (login_check() != true){
    echo ("<p><span class=error_message>You are not authorized to access this page. Please</span> <a href=index.php>login</a>.</p>");
    exit(0);
}
require 'templates/header.html';
?>
<h1>Configuration</h1>

<?php
require_once 'Config/Lite.php';
$config = new Config_Lite('user_config.ini');
$standalone = '';
$gmail = '';
                
if($config['main']['mailer_mode'] == 'standalone'){
    $standalone = 'selected';
}elseif($config['main']['mailer_mode'] == 'gmail'){
    $gmail = 'selected';
}elseif($config['main']['mailer_mode'] == 'cloud'){
    $cloud = 'selected';
}
?>

<script type='text/javascript'>
hideAll = function() {
	$('.standaloneTextBox').hide();
	$('.gmailTextBox').hide();
	$('.cloudTextBox').hide();
};

hideStuff = function() {
	<?php
	$current_method = $config['main']['mailer_mode'];
	if($current_method == "standalone"){
		echo '$(\'.standaloneTextBox\').show();';
		echo '$(\'.gmailTextBox\').hide();';
		echo '$(\'.cloudTextBox\').hide();';
	}elseif($current_method == "cloud"){
		echo '$(\'.standaloneTextBox\').hide();';
		echo '$(\'.gmailTextBox\').hide();';
		echo '$(\'.cloudTextBox\').show();';
	}elseif($current_method == "gmail"){
		echo '$(\'.standaloneTextBox\').hide();';
		echo '$(\'.gmailTextBox\').show();';
		echo '$(\'.cloudTextBox\').hide();';
	}else{
		echo 'hideAll();';
	}
	?>
};

$(document).ready(function (e) {
	hideStuff();
	
    $('#alertsDropDown').change(function () {
        if ($(this).val() == 'cloud') {
            $('.cloudTextBox').show();
			$('.gmailTextBox').hide();
			$('.standaloneTextBox').hide();
        } else if ($(this).val() == 'gmail') {
            $('.gmailTextBox').show();
			$('.cloudTextBox').hide();
			$('.standaloneTextBox').hide();	
        } else if ($(this).val() == 'standalone') {
            $('.standaloneTextBox').show();
			$('.gmailTextBox').hide();
			$('.cloudTextBox').hide();
        }
    });
});
</script>

<?php
    echo ('<form name="user_config" id="user_config" action="save_config.php" onsubmit="return ValidateInput();" method="post">
           <table width=95% halign=left>
           <tr align=left><td title="Your personal FalconGate Intel API key.">FalconGate Intel API key:</td><td><input type=text size=71 name="fg_intel_key" value='.$config['main']['fg_intel_key'].'></td></tr>
           <tr align=left><td title="Your personal VirusTotal API key.">VirusTotal API key:</td><td><input type=text size=71 name="vt_key" value='.$config['main']['vt_api_key'].'></td></tr>
           <tr align=left><td title="This is the list of recipients for the email alerts sent by FalconGate.">Alert recipients:</td><td><input type=text size=71 name="dst_emails" value='.$config['main']['dst_emails'].'></td></tr>
           <tr align=left><td title="This is the customized list of IP addresses you wish to block.">Blacklist:</td><td><textarea form="user_config" id="blacklist" name="blacklist" rows=5 cols=81>'.$config['main']['blacklist'].'</textarea></td></tr>
           <tr align=left><td title="This is the list of IP addresses to be whitelisted from blocking by FalconGuard.">Whitelist:</td><td><textarea form="user_config" id="whitelist" name="whitelist" rows=5 cols=81>'.$config['main']['whitelist'].'</textarea></td></tr>
           <tr align=left><td title="This is the list of email addresses to be monitored for potential compromise due to hacking breaches in third party online services.">Email watchlist:</td><td><textarea form="user_config" id="email_watchlist" name="email_watchlist" rows=5 cols=81>'.$config['main']['email_watchlist'].'</textarea></td></tr>
           </table>
           <br>');
echo ('<p class=notes>Note: Multiple recipient emails can be added to the "Alerts recipients" field using commas as separator.</p>');
    echo ("<p><i><b>Select below your preferred option for alerting (default = Standalone):</b></i></p>");
    echo ('<table>
           <td>
		   <select id="alertsDropDown" name="selector">
				<option value="empty" id="empty">Select delivery method</option>
				<option value="cloud" id="cloud" '.$cloud.'>Cloud Alerts</option>
				<option value="gmail" id="gmail" '.$gmail.'>Gmail</option>
				<option value="standalone" id="standalone" '.$standalone.'>Standalone</option>
			</select>
			<br><br>
           </td>
           </table>
           <table width=40% halign=left>
           <tr align=left>
			<td>
				<div class="cloudTextBox">
					Telegram Chat ID: <input type=text size=20 name="telegram_id" id="telegram_id" value='.$config['main']['telegram_id'].'><br>
				</div>
				<div class="gmailTextBox">
					Gmail Address: <input type=text size=20 name="mailer_address" id="mailer_address" value='.$config['main']['mailer_address'].'><br>
					Password: <input type="password" name="mailer_pwd" id="mailer_pwd" size="20" maxlength="500">
				</div>
				<div class="standaloneTextBox">Standalone</div>
			</td>
			</tr>
           </table>
		   <br>
           <input class="config_button" type="submit" value="Save" name="mailer_submit" id="mailer_submit">
           </form><br>');
if (isset($_GET['updated'])){
    if ($_GET['updated'] == 'True'){
        echo ('<p>Configuration saved!</p>
        <p>FalconGate process restarted...</p><br>');
    }
}
?>
 
<?php
require 'templates/footer.html';
?>