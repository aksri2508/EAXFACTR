<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Access_control.php
* @Class  			 : Access_control
* Model Name         : Access_control
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 09 JULY 2023
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/
class Access_control extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->config->load('table_config/tbl_master_module_screen_mapping.php');
		$this->config->load('table_config/tbl_access_control.php');
		$this->config->load('table_config/tbl_access_control_screen_list.php');
        $this->load->model('company/access_control_model', 'nativeModel');
    }
	
	
    /**
	* @METHOD NAME 	: saveAccessControl()
	*
	* @DESC 		: TO SAVE/UPDATE ACCESS CONTROL SCREEN
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function saveAccessControl()
    {
       // Params from http request
       $this->checkRequestMethod("post"); // Check the Request Method
       $outputData['status'] = "FAILURE";
       $getData 		     = $this->currentRequestData;
       $modelOutput 	  	 = $this->nativeModel->saveAccessControl($getData);
        
           if (1 == $modelOutput['flag']) {
               $outputData['sId']      	= $modelOutput['sId'];
               $outputData['status']       = "SUCCESS";
               $outputData['message']      = lang('MSG_355'); 	// Successfully Inserted
           } else if (3 == $modelOutput['flag']) {
               $outputData['message']      = lang('GLB_009');  // UNABLE TO SAVE THE RECORD
           } else if (4 == $modelOutput['flag']) {
            $outputData['message']      = lang('MSG_356');  // AccessControlName Record duplicate.
        }
       
       $this->output->sendResponse($outputData);
    }


	/**
	* @METHOD NAME 	: updateAccessControl()
	*
	* @DESC 		: TO SAVE/UPDATE ACCESS CONTROL SCREEN
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateAccessControl()
    {
       // Params from http request
       $this->checkRequestMethod("put"); // Check the Request Method
       $outputData['status']  = "FAILURE";
       $getData 		       = $this->currentRequestData;

           $modelOutput 	   = $this->nativeModel->updateAccessControl($getData);
       
           if (1 == $modelOutput['flag']) {
               $outputData['sId']      	= $modelOutput['sId'];
               $outputData['status']       = "SUCCESS";
               $outputData['message']      = lang('MSG_357'); 	// Successfully Updated
           } else if (3 == $modelOutput['flag']) {
               $outputData['message']      = lang('GLB_009');  // UNABLE TO SAVE THE RECORD
           } else if (4 == $modelOutput['flag']) {
            $outputData['message']      = lang('MSG_356');  // AccessControlName Record duplicate.
        }
       
       $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: getAccessControlList()
	*
	* @DESC 		: TO GET THE ACCESS CONTROL LIST
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getAccessControlList()
    {
        $this->checkRequestMethod("put");
        $modelOutput           = $this->nativeModel->getAccessControlList($this->currentRequestData);

		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }
	
	

	/**
	* @METHOD NAME 	: getModuleScreenList()
	*
	* @DESC 		: TO GET THE MODULE SCREEN LIST
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getModuleScreenList()
    {
        //$this->checkRequestMethod("get");
        $modelOutput           = $this->nativeModel->getModuleScreenList();
        
		$formEnableArray = array('enable_view','enable_add','enable_update','enable_download');
        
        $moduleData = array();
        $processData = array();

        // ReArranging data to Modulewise.
        foreach($modelOutput['searchResults'] as $sValue){
            $processData[$sValue['module_id']][] = $sValue;
        }

        // Process Data.
        foreach($processData as $mKey => $mValue){
			
            $screenDataList = array();
            foreach($mValue as $sValue){

                $assignData= array();
                $assignData = $sValue;
                unset($assignData['module_id']);
				    
                $screenData = array();
                $screenData['screen_id'] = $assignData['id'];
                $screenData['screen_name'] = $assignData['screen_name'];
                $screenData['screen_order'] = $assignData['screen_order'];
				
				
				$notAvailableColumns = array();				
				if(!empty($assignData['not_available_columns'])){
					$notAvailableColumns = explode(",",$assignData['not_available_columns']);
				}

				foreach($formEnableArray as $enableKey => $enableValue){					
						if(!in_array($enableValue,$notAvailableColumns)){
							$screenData[$enableValue] = "1";
						}else{
							$screenData[$enableValue] = "3";
						}
				}
                $screenDataList[] = $screenData;
            }
            $mData = array();
            $mData['module_id'] = $mValue[0]['module_id'];
            $mData['module_name'] = $mValue[0]['module_name'];
            $mData['module_order'] = $mValue[0]['module_order'];
            $mData['screenList'] = $screenDataList;
            $moduleData[] = $mData;
        }

		// FRAME OUTPUT
        $outputData['results']      = $moduleData;
        $outputData['status']       = "SUCCESS";
		
		$this->output->sendResponse($outputData);
    }
	
	
	
	/**
	* @METHOD NAME 	: checkAccessControlScreenListExists()
	*
	* @DESC 		: TO CHECK ACCESS CONTROL SCREEN LIST EXISTS 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function checkAccessControlScreenListExists($screenData,$editAcessControlScreenList){
		$findAccessControlData = [];
		foreach($editAcessControlScreenList as $editKey =>$editValue){
			if($editValue['master_module_screen_mapping_id'] == $screenData['screen_id'] ) {
					$findAccessControlData = $editValue;
					break;
			}			
		}
		return $findAccessControlData;
	}
	
	
	/**
	* @METHOD NAME 	: editAccessControlScreenList()
	*
	* @DESC 		: TO EDIT THE ACCESS CONTROL SCREEN LIST
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function editAccessControlScreenList()
    {
        $this->checkRequestMethod("put");
		$outputData['status']  = "FAILURE";

         // PARAMS FROM HTTP REQUEST
         if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) 
         {
            
		$editAcessControlScreenList  = $this->nativeModel->editAccessControlScreenList($this->currentRequestData['id']);
            
			
		$modelOutput  = $this->nativeModel->getModuleScreenList();
        
		$formEnableArray = array('enable_view','enable_add','enable_update','enable_download');
        
        $moduleData = array();
        $processData = array();

        // ReArranging data to Modulewise.
        foreach($modelOutput['searchResults'] as $sValue){
            $processData[$sValue['module_id']][] = $sValue;
        }

        // Process Data.
        foreach($processData as $mKey => $mValue){
			
            $screenDataList = array();
            foreach($mValue as $sValue){

                $assignData= array();
                $assignData = $sValue;
                unset($assignData['module_id']);
				    
                $screenData = array();
                $screenData['screen_id'] = $assignData['id'];
                $screenData['screen_name'] = $assignData['screen_name'];
                $screenData['screen_order'] = $assignData['screen_order'];
				
				
				$notAvailableColumns = array();				
				if(!empty($assignData['not_available_columns'])){
					$notAvailableColumns = explode(",",$assignData['not_available_columns']);
				}

				foreach($formEnableArray as $enableKey => $enableValue){					
						if(!in_array($enableValue,$notAvailableColumns)){
							$screenData[$enableValue] = "1";
						}else{
							$screenData[$enableValue] = "3";
						}
				}
				
				$getAccessControlEditRecord = $this->checkAccessControlScreenListExists($screenData,$editAcessControlScreenList);
				
				
				if(is_array($getAccessControlEditRecord) && count($getAccessControlEditRecord) ){
		
					if(isset($screenData['enable_view'])){
						$screenData['enable_view'] = $getAccessControlEditRecord['enable_view'];
					}

					if(isset($screenData['enable_add'])){
						$screenData['enable_add'] = $getAccessControlEditRecord['enable_add'];
					}
					
					if(isset($screenData['enable_update'])){
						$screenData['enable_update'] = $getAccessControlEditRecord['enable_update'];
					}
					
					if(isset($screenData['enable_download'])){
						$screenData['enable_download'] = $getAccessControlEditRecord['enable_download'];
					}
					$screenData['access_control_screen_list_id'] = $getAccessControlEditRecord['access_control_screen_list_id'];
				}
								
                $screenDataList[] = $screenData;
            }
            $mData = array();
            $mData['module_id'] = $mKey;
            $mData['module_name'] = $mValue[0]['module_name'];
            $mData['module_order'] = $mValue[0]['module_order'];
            $mData['screenList'] = $screenDataList;
            $moduleData[] = $mData;
        }
		
		$outputData['results']['id'] = $editAcessControlScreenList[0]['id'];
		$outputData['results']['status'] = $editAcessControlScreenList[0]['status'];
		$outputData['results']['accessControlName'] = $editAcessControlScreenList[0]['access_control_name'];
		$outputData['results']['isSystemConfig']	= $editAcessControlScreenList[0]['is_system_config'];
		$outputData['results']['accessControlData'] = $moduleData;
		
        $outputData['status']       = "SUCCESS";
		
		$this->output->sendResponse($outputData);
		}
	}
	
}
?>