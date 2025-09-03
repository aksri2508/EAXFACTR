<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

//Class is used to send the email to the users. Configured the gmail functionality
class Customemail  extends MY_Controller {
	
	 public function __construct()
    {
       // parent::__construct();
	} 
	
	// FUNCTION USED TO SEND THE EXTERNAL MAIL
	public function sendemail_external($toemailid, $subject, $message, array $sender, $bcc = '', $cc = '', $documentDetails = array())
	{
		
		if(0==MAILFLAG) {
			return '';
		}
		
		//SMTP CREDENTIALS
		$config  = array(
			'smtp_host'   => $sender['smtp_host'],
			'protocol'    => $sender['smtp_protocol'],
			'smtp_port'   => $sender['smtp_port'],
			'smtp_crypto' => $sender['smtp_secure'],
			'smtp_user'   => $sender['smtp_username'],
			'smtp_pass'   => $sender['smtp_password'],
			'mailtype' 	  => 'html',
//			'mailtype' 	  => $sender['mailtype'],
			'newline'     => "\r\n",
			'crlf'        => "\r\n",
		);
		
		$ci=& get_instance();
		$ci->load->library('email', $config);
		
		$ci->email->from($sender['user_emailid'], $sender['username']);
		$ci->email->reply_to($sender['user_emailid']);
		$ci->email->to($toemailid);


		if(!empty($cc)) {
			$ci->email->cc($cc);
		}
		
		if(!empty($bcc)) {
			$ci->email->bcc($bcc);
		}
		
		if(!empty($documentDetails)) {
			
			// $fullFilePath = getcwd().$filePath = $documentDetails['filePath'];
			$fullFilePath = $documentDetails['filePath'];
			$ci->email->attach($fullFilePath);

		}

		$ci->email->subject($subject);
		$ci->email->message($message);
		

		$ci->email->send();

		//print_r($ci->email->print_debugger());

	}
}
/* End of file Someclass.php */
?>
