<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Approval_status_report.php
* @Class  			 : Approval_status_report
* Model Name         : Approval_status_report
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 16 MAY 2019
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/
class Approval_status_report extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->config->load('table_config/tbl_approval_status_report.php');
        $this->load->model('company/approval_status_report_model', 'nativeModel');
    }
	
    
	/**
	* @METHOD NAME 	: getApprovalStatusReportList()
	*
	* @DESC 		: TO GET THE APPROVAL STATUS REPORT
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getApprovalStatusReportList()
    {
        $this->checkRequestMethod("put");
        $modelOutput           = $this->nativeModel->getApprovalStatusReportList($this->currentRequestData);
        
        $passSearchData['category'] = 2;
        $passSearchData['delFlag']  = 0;
        
        foreach($modelOutput['searchResults'] as $searchKey => $searchVaue){
                $documentNumber =  '';
                $approvalStatusName = '';
                $isDraft = '';
               
                // GET THE APPROVAL STATUS NAME
				$statusInfoDetails	= array();
				$getInfoData		= array(	
					'getApprovalStatusList' 			 => $searchVaue['approval_status'],
				);
				$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
                
                if(isset($statusInfoDetails['ApprovalStatuslInfo'][0])){
                    $approvalStatusName = $statusInfoDetails['ApprovalStatuslInfo'][0]['name'];
                }
                $modelOutput['searchResults'][$searchKey]['approvalStatusName']     = $approvalStatusName;
        }
        
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }
		
}
