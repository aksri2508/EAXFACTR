<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 ** Helper Name : NOTIFICATION HELPER FOR ENTIRE SYSTEM 
 ** Description : -
 ** Module   	: NA
 **	Actors 	  	: - 
 **	Features 	: - 
 */
/**
 * @METHOD NAME 	: sendNotification()
 *
 * @DESC 			: SEND NOTIFICATION BASED UPON THE MODULE 
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function sendNotification($notificationType, $moduleType, $recvID, $payload='')
{
	// GET THE INSTANCE 
	$CI		= &get_instance();

	if($notificationType == 'WEB'){
		// APPROVAL PROCESS NOTIFICATION 
		if($moduleType == 'APPROVAL_MODULE' || 
		$moduleType == 'APPROVAL_REQUEST_MODULE') {

			foreach($recvID as $recvKey => $recvValue) { 
				$notifcationArray['receiver_id']		 =  $recvValue;
				$notifcationArray['notification_type']	 =  1;
				// $notifcationArray['content'] 			 =  lang('NOTIFY_MSG_01')[0];
				$notifcationArray['content'] 			 =  $payload['content'];
				$notifcationArray['document_id'] 		 =  $payload['document_id'];
				$notifcationArray['document_type_id'] 	 =  $payload['document_type_id'];
				$CI->commonModel->saveNotificationTbl($notifcationArray);			
			}
		}
	}
	else if($notificationType == 'EMAIL'){
		// Send mail notifications.
	}
	else if($notificationType == 'SMS'){
		// Send sms notifications.
	}
	else {
		// Send other type notifications.
	}

}

/****************************************** END OF NOTIFICATIONS ********************/