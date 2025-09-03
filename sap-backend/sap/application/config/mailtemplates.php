<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//Configuarion values for external & Internal email template

// REGISTRATION FOR USER 
$config['user_registration']['title'] 	 = 'Registration Mail';
$config['user_registration']['subject']  = 'Thank you for registering with x-Factr';
$config['user_registration']['body'] 	 = 
'<tr>
	<td style="width:100%;text-align: center;padding:10px;font-size:20px;font-weight:bold">
		Hello <<NAME>>
	</td>
</tr>
<tr>
	<td style="width:100%;text-align: center;padding:10px;">
		We are delighted to welcome you to x-Factr Application. Thank you for joining!
		<br>
		<br>
	</td>
</tr>
<tr>
	<td style="width:100%;text-align: center;padding:10px;">
		Please login to x-Factr Application with the following:
		<br>
		<!--
		<strong>Login URL</strong> : <a href="<<LOGIN_URL>>" style="color:#ff5e3a;"> <<LOGIN_URL>> </a>
		-->
		<br>
		<div style="padding-left:100px;"><table>
		<tr><td><strong>Username</strong></td><td>: <<USERNAME>></td></tr>
		<tr><td><strong>Password</strong></td><td>: <<PASSWORD>></td></tr>
		<tr><td><strong>Company Name</strong></td><td>: <<COMPANY_NAME>></td></tr>
		<tr><td><strong>Branch Name</strong></td><td>: <<BRANCH_NAME>></td></tr>
	     </table></div>
		</td>
</tr>
<tr>
	<td style="width:100%;text-align: center;padding:10px;">
		We recommend that you change the password from its default setting, after your first login. 
	</td>
</tr>';


// FORGOT PASSWORD MAIL 
$config['forgotpassword_mail']['title'] 	= 'Forgot Password';
$config['forgotpassword_mail']['subject']   = 'Forgot Password - En';
$config['forgotpassword_mail']['body']		= 
'<tr>
	<td style="width:100%;text-align: center;padding:10px;font-size:20px;font-weight:bold">
		Hello <<NAME>>
	</td>
</tr>
<tr>
	<td style="width:100%;text-align: center;padding:10px;">
		 Your password has reseted. Please, use the new password to login.
		<br>
		<strong>Password</strong> : <<PASSWORD>>	
		<br>
		If you did not request to have your password reset, you can safely ignore this mail. We assure you that your account is safe.
	</td>
</tr>';

?>