<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

//Class is used to send the email to the users. Configured the gmail functionality
class Customsms  extends MY_Controller {
	
	public function __construct()
    {
       // parent::__construct();
	}
	
	// Function to send sms to users when assigning the leads etc. | company sms CREDENTIALS
	public function send_sms_external($mobilenumbers,$message,$userLang,$sms_credentials){
	
		global $config_sms_external_global;
		$output_array 		  = array();
		$output_array['flag'] = 1;
		$output_array['msg']  = '';

		// sms credentials 
		$httpParams = [
		    'UID' => $sms_credentials['username'],
		    'P'   => $sms_credentials['password'],
		    'S'   => $sms_credentials['sender_id'],
		    'G'   => $mobilenumbers,
		    'M'   => $message,
		    'L'   => ('ar' == $userLang ? 'A' : 'L'),
        ];
        
        $httpParams = http_build_query($httpParams);
		$url	    = $sms_credentials['sms_url'];
		
		// CURL PROCESS STARTS 
		$message = urlencode($message);
		$ch 	 = curl_init();
		if (!$ch){
			$content = "Couldn't initialize a cURL handle";
			$output_array['flag'] = 2; // error
			$output_array['msg'] = 'SMSERR3-Error in Application';
			// die("Couldn't initialize a cURL handle"); // -> commented by prem
		}
		$ret = curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);          
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $httpParams);
		
		$ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		// Process the curl response output
		$curlresponse = curl_exec($ch); // execute
		if(curl_errno($ch)){
			$curl_error = curl_error($ch);
			$content 	= $mobilenumbers."--".$curl_error;
			//outputsmslog($content);
			$output_array['flag'] = 2; // error
			$output_array['msg'] = 'SMSERR1-Error in Application';
		}

		if (empty($ret)) {
		// some kind of an error happened
			$curl_error = curl_error($ch);
			$content 	= $mobilenumbers."--".$curl_error;
		//	outputsmslog($content);
			$output_array['flag'] = 2; // error
			$output_array['msg']  = 'SMSERR2-Error in Application';
			// die(curl_error($ch));  // -> commented by prem
			curl_close($ch); // close cURL handler
		} else {
			$info = curl_getinfo($ch);
			curl_close($ch); // close cURL handler
			//echo "<br>";
			// echo $curlresponse;    //echo "Message Sent Succesfully" ; 
			$output_array['flag'] = 1;
			$output_array['msg']  = $curlresponse; // Get the job id for the specification.
		}
		return $output_array;
	}
}
/* End of file Someclass.php */
?>
