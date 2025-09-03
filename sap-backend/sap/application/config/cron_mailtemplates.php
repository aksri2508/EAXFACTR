<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//Configuarion values for external & Internal email template

// ACTIVITY - Notfication Mail Body. 
$config['activity_mail_notification']['title'] 	 = 'Activity Notification Mail';
$config['activity_mail_notification']['subject']  = 'Activity Notification Mail';
$config['activity_mail_notification']['body'] 	 = 
'<tr>
	<td style="width:100%;text-align: center;padding:10px;font-size:20px;font-weight:bold">
		Hello <<NAME>>
	</td>
</tr>
<tr>
	<td style="width:100%;text-align: center;padding:10px;">
		<br><br>
		We are delighted to welcome you to x-Factr Application!. 
		<br>
		<br>
	</td>
</tr>
<tr>
	<td style="width:100%;text-align: center;padding:10px;">
		<strong>Notification Message (Reason) : <<REASON>></strong>
		<br>
		<br>
	</td>
</tr>
<tr>
	<td style="width:100%;text-align: center;padding:10px;">
		Please check your mail very offen to get recent updates from x-Factor Application. 
	</td>
</tr>';

?>