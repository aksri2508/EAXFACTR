<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * We are extending the core CI_Output to force to use the sendResponse instead of set_output/append_output
 * We have to force to use the proper HTTP Status code as per application specification
 *
*/
class MY_Output extends CI_Output {
    
    /*
     * sendResponse is single point of output for application
     * pass the response in Array format with HTTP status code
    */
    public function sendResponse(array $response = [])
    {
		
        //SET REPONSE CODE AND RESPONSE MESSAGE
        if((isset($response['message'])) && is_array($response['message']))
        {
			
			if(isset($response['message'][1])){
               $response['responseCode'] = $response['message'][1];
           }else{
               $response['responseCode'] = 200 ;
           }
		   
		   $response['message'] = $response['message'][0];
        }else{
            $response['responseCode'] = 200 ;
        }
		
		
        //For CLI mode, we do no need to set any HTTP Header and status code
        if (is_cli())
        {
            //set_output is blocked for deprecated.
            $this->final_output = implode("\n", $response);
        }
        else
        {
            $config = get_config();
            
            
            $responseCode = $response['responseCode'];
            //unset($response['responseCode']);
            
            // response code to HTTP Status code
            if (isset($config['HTTP_RESPONSE_CODES'][$responseCode])) {
                $httpStatus = $config['HTTP_RESPONSE_CODES'][$responseCode];
            }else {
                // currently useful on modification
                echo 'Invalid response code'; exit(__FILE__.'@'.__LINE__);
            }
            
            //set_output is blocked for deprecated.
            if (!empty($response)) toCamelCase($response);
            $this->final_output = json_encode($response);
			
			if(TRACK_API_FLAG == 1) {
				$this->trackApiEndPoints($this->final_output);
			}
            
            if (0 == ENABLE_HTTP_STATUS_CODE) {
                $httpStatus = 200;
            }
            
            //set HTTP Status code
            $this->set_status_header($httpStatus);
            
            //set HTTP content-type with charset
            $this->set_content_type('application/json', 'utf-8');
        }
    }
		
	
	/*
     * TRACAK ALL THE REQUEST AND RESPONSE ENDPOINTS IN THE SERVER
    */
    public function trackApiEndPoints($response) 
    {
		$ci = &get_instance();
		$ci->commonModel->insertAPITracker($response);
    }
	
	
    /*
     * for single point of output,
     * forceDownload is wrapper to force_download of download helper
    */
    public function forceDownload($filename = '', $data = '', $set_mime = FALSE)
    {
        $this->load->helper('download');
        force_download($filename, $data, $set_mime);
    }
    
    /*
     * override set_output to force to use the sendResponse
    */
    public function set_output($output)
    {
        log_message('error', __FUNCTION__.'@'.__FILE__.' is deprecated');
        return $this;
    }
    
    /*
     * override append_output to force to use the sendResponse
    */
    public function append_output($output)
    {
        log_message('error', __FUNCTION__.'@'.__FILE__.' is deprecated');
        return $this;
    }
}