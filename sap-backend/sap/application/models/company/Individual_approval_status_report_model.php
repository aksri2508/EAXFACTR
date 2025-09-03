<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Individual_approval_status_report_model.php
* @Class  			 : Individual_approval_status_report_model
* Model Name         : Individual_approval_status_report_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : -
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Individual_approval_status_report_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'INDIVIDUAL_APPROVAL_STATUS_REPORT';
		$this->tableName 	= constant($this->tableNameStr);
    }
	
	
    /**
	* @METHOD NAME 	: getApprovalStatusHistory()
	*
	* @DESC 		: TO GET THE APPROVAL TEMPLATE LIST
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getApprovalStatusHistoryList($getPostData)
    {
        $approvalStatusReportId = $getPostData['id'];

        // SELECT 
        $this->app_db->select(array(
                                    INDIVIDUAL_APPROVAL_STATUS_REPORT.'.id',
                                    INDIVIDUAL_APPROVAL_STATUS_REPORT.'.approval_status_report_id',
                                    INDIVIDUAL_APPROVAL_STATUS_REPORT.'.approval_status',
                                    INDIVIDUAL_APPROVAL_STATUS_REPORT.'.approver_remarks as remarks',
                                    INDIVIDUAL_APPROVAL_STATUS_REPORT.'.approver_id',
                                    INDIVIDUAL_APPROVAL_STATUS_REPORT.'.prev_approval_flag',
                                    INDIVIDUAL_APPROVAL_STATUS_REPORT.'.created_on as created_date_time',
                                    INDIVIDUAL_APPROVAL_STATUS_REPORT.'.sap_id',
                                    INDIVIDUAL_APPROVAL_STATUS_REPORT.'.posting_status',
                                    INDIVIDUAL_APPROVAL_STATUS_REPORT.'.sap_error',
                                    MASTER_STATIC_DATA.'.name as approval_status_name',
                                    'CONCAT('.EMPLOYEE_PROFILE.'.first_name," ",'.EMPLOYEE_PROFILE.'.last_name) as employee_name'
                                ));
        $this->app_db->from(INDIVIDUAL_APPROVAL_STATUS_REPORT);
        $this->app_db->join(MASTER_STATIC_DATA, MASTER_STATIC_DATA.'.master_id = '.INDIVIDUAL_APPROVAL_STATUS_REPORT.'.approval_status', 'left');
        $this->app_db->join(EMPLOYEE_PROFILE, EMPLOYEE_PROFILE.'.id = '.INDIVIDUAL_APPROVAL_STATUS_REPORT.'.approver_id', 'left');
        $this->app_db->where(MASTER_STATIC_DATA.'.type', 'APPROVAL_STATUS');
        $this->app_db->where(INDIVIDUAL_APPROVAL_STATUS_REPORT.'.is_deleted', '0');
        $this->app_db->where(INDIVIDUAL_APPROVAL_STATUS_REPORT.'.approval_status_report_id', $approvalStatusReportId);
        $this->app_db->order_by(INDIVIDUAL_APPROVAL_STATUS_REPORT.'.updated_on', 'desc');
        $searchResultSet = $this->app_db->get();
        $searchResultSet = $searchResultSet->result_array();
        return $searchResultSet;
    }


    /**
	* @METHOD NAME 	: updateIndividualApprovalStatus()
	*
	* @DESC 		: TO UPDATE THE INDIVIDUAL APPROVAL STATUS 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateIndividualApprovalStatus($getPostData)
    {

        // $this->currentUserId = 203;

        // Default Error Flag Contact Admin
        $modelOutput['flag'] = 4;

        if(isset($getPostData['approvalStatusReportId'])){

            // Transaction Start
            $this->app_db->trans_start();

            $this->app_db->select(array('id','prev_approval_flag'));
            $this->app_db->from(INDIVIDUAL_APPROVAL_STATUS_REPORT);
            $this->app_db->where('approval_status_report_id', $getPostData['approvalStatusReportId']);
            $this->app_db->where('approver_id', $this->currentUserId);
            $this->app_db->where('is_deleted', '0');
            $this->app_db->where('prev_approval_flag', '0');
            $rs = $this->app_db->get();
            $totRows  = $rs->num_rows();
            $getIndividualApprovalResult  = $rs->result_array(); 
 
           if($totRows==0) { // Insert the record 

            // To get receiver Id, document_id, document_type_id.
            $this->app_db->select(array('id','document_created_by','document_id','document_type_id'));
            $this->app_db->from(APPROVAL_STATUS_REPORT);
            $this->app_db->where('id', $getPostData['approvalStatusReportId']);
            $rs = $this->app_db->get();
            $totRows  = $rs->num_rows();
            $getApprovalResult  = $rs->result_array(); 

                $rowData                = bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);
                $rowData['approver_id'] = $this->currentUserId;
                $insertId               = $this->commonModel->insertQry($this->tableName, $rowData);
                $modelOutput['flag'] = 1;

                // Adding notification payload informations.
                $modelOutput['receiver_id'] = $getApprovalResult[0]['document_created_by']; 
                $modelOutput['document_id'] = $getApprovalResult[0]['document_id']; 
                $modelOutput['document_type_id'] = $getApprovalResult[0]['document_type_id']; 

                $this->updateApprovalStatusReportTbl($getPostData);
                $this->app_db->trans_complete();
				
				// Check the transaction status
				if ($this->app_db->trans_status() === FALSE) {
					$modelOutput['flag'] = 4;
					return $modelOutput;
				} 
            } else{ // Error 
              $modelOutput['flag'] = 3; // you already updated the document 
            }
        }else{
              $modelOutput['flag'] = 2; // Request parameter missing
        }
        return $modelOutput;
    }

    
    /**
	* @METHOD NAME 	: updateApprovalStatusReportTbl()
	*
	* @DESC 		: TO UPDATE THE APPROVAL STATUS REPORT TABLE 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateApprovalStatusReportTbl($getPostData)
    {
        // GET THE APPROVAL STATUS REPORT RECORDS
        $this->app_db->select(array('id','no_of_approvals','no_of_rejections','total_approved','total_rejected','approvers_id','overall_approval_status','document_id','document_type_id'));
        $this->app_db->from(APPROVAL_STATUS_REPORT);
        $this->app_db->where('id', $getPostData['approvalStatusReportId']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        $getApprovalStatusReport = $rs->result_array();

        $noOfApprovals = $getApprovalStatusReport[0]['no_of_approvals'];
        $noOfRejections= $getApprovalStatusReport[0]['no_of_rejections'];
        $totalApproved = $getApprovalStatusReport[0]['total_approved'];
        $totalRejected = $getApprovalStatusReport[0]['total_rejected'];
        $overAllApprovalStatus = $getApprovalStatusReport[0]['overall_approval_status'];
        $indvStatus    = $getPostData['status'];
        $finalApprovalStatus = 1;
       
	   
        // INDIVIDUAL STATUS 
        if($indvStatus==2){ // Approved
            $totalApproved = $totalApproved + 1; 
            if($totalApproved == $noOfApprovals ){
                $finalApprovalStatus = 2;
            }
        }else if($indvStatus==3){ // Rejected 
            $totalRejected =  $totalRejected + 1; 
            if($totalRejected == $noOfRejections ){
                $finalApprovalStatus = 3;
            }
        }
        
        // Overall approvalstatus : If the document is already 
        if( ($overAllApprovalStatus == 2) || ($overAllApprovalStatus == 3) ) { 
            $finalApprovalStatus =  $overAllApprovalStatus;
        }

        // 
        $updateData['total_approved']   = $totalApproved;
        $updateData['total_rejected']   = $totalRejected;
        $updateData['overall_approval_status']   = $finalApprovalStatus;
        $updateData['last_remarks']              = $getPostData['remarks'];

        // UPDATE TO APPROVAL STATUS TABLE 
        $whereQry 	= array('id'=>$getPostData['approvalStatusReportId']);
        $this->commonModel->updateQry(APPROVAL_STATUS_REPORT, $updateData, $whereQry);

        /* 
		// CODE COMMENTED FOR APPROVAL PROCESS FLOW 
		// Convert the document from Draft to Approved or Rejected Status 
		if($finalApprovalStatus ==  2){ // APPROVED 
			 $this->convertDraftDocumentToApprovedDocument($getApprovalStatusReport,$finalApprovalStatus);
		}
		*/
		
		if($overAllApprovalStatus!=3) { // IF ITS IS ALREADY REJECTED, NO NEED TO PROCEED 
			if($finalApprovalStatus ==  3){ // REJECTED 
				$this->updateRejectedStatusToDocumentTable($getApprovalStatusReport,$finalApprovalStatus);
			}
		}

    }
	
	
	/**
	* @METHOD NAME 	: updateRejectedStatusToDocumentTable()
	*
	* @DESC 		: TO UPDATE THE REJECTED STATUS TO THE DOCUMENT TABLE [GRPO, PURCHASE REQUEST / ORDER ]
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function updateRejectedStatusToDocumentTable($getApprovalStatusReport,$finalApprovalStatus){
		$documentId         = $getApprovalStatusReport[0]['document_id'];
		$documentTypeId     = $getApprovalStatusReport[0]['document_type_id'];

        // GET SCREEN NAME 
        $findScreenNameArray = getScreenDetailsByDocumentType($documentTypeId);
        $tableName           = $findScreenNameArray['tableName'];
		
		$updateData['approval_status'] =  $finalApprovalStatus;
		$updateData['is_draft']        =  1;
		$whereQry                      = array('id'=>$documentId);
		$this->commonModel->updateQry($tableName, $updateData, $whereQry);
	}
	
	
    /**
	* @METHOD NAME 	: convertDraftDocumentToApprovedDocument()
	*
	* @DESC 		: TO UPDATE THE APPROVAL STATUS and DOCUMENT NUMBER IN THE DOCUMENT TABLE 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	/* 
	// OLD CODE MAINTAINED FOR APPROVAL ACTIVITY
    public function convertDraftDocumentToApprovedDocument($getApprovalStatusReport,$finalApprovalStatus){
        $documentId         = $getApprovalStatusReport[0]['document_id'];
        $documentTypeId     = $getApprovalStatusReport[0]['document_type_id'];

        // GET SCREEN NAME 
        $findScreenNameArray = getScreenDetailsByDocumentType($documentTypeId);
        $tableName           = $findScreenNameArray['tableName'];
  
        if($finalApprovalStatus==2) { // APPROVED 
            // GET THE DOCUMENT NUMBERING ID AND DOCUMENT NUMBERING TYPE 
            $trimCharacterTblName    = substr($tableName, 4);
            $configTblNameConvertion = strtoupper($trimCharacterTblName);

            $getDocumentNumberDetails 			    = getDocumentNumberTypeId($configTblNameConvertion,'PRIMARY');
            $passDocumentData['document_numbering_id'] 	    = $getDocumentNumberDetails[0]['id'];
            $passDocumentData['document_numbering_type'] 	= $getDocumentNumberDetails[0]['document_numbering_type'];

            // PROCESS THE DOCUMENT NUMBER TO NEXT INCREMENT 
            $DocNumInfo = processDocumentNumber($passDocumentData, $tableName);
            $passDocumentData['document_number'] = $DocNumInfo['documentNumber'];

            // To update next document number.
            updateNextNumber($passDocumentData, $passDocumentData['document_numbering_type']);

            // UPDATE TO APPROVAL STATUS COLUMN IN DOCUMENTS TABLE (PURCHASE REQUEST, GRPO ETC)
            // DOCUMENT NUMBER COLUMNS ALSO NEEDS TO BE UPDATED TOGETHER 
            $updateData['document_number']       =  $DocNumInfo['documentNumber'];
            $updateData['document_numbering_id'] =  $passDocumentData['document_numbering_id'];
            $updateData['approval_status'] =  $finalApprovalStatus;
            $updateData['is_draft']        =  0;
            $whereQry                      = array('id'=>$documentId);
            $this->commonModel->updateQry($tableName, $updateData, $whereQry);

		 // TO UPDATE THE DOCUMENT NUMBER TO THE DOCUMENT TABLE 
			$id 									   	 = $getApprovalStatusReport[0]['id'];
			$documentNumber 			  				 = $DocNumInfo['documentNumber'];
			$updateApprovalStatusData['document_number'] = $documentNumber;
			$whereQry                     				 = array('id' => $id);
			$this->commonModel->updateQry(APPROVAL_STATUS_REPORT, $updateApprovalStatusData, $whereQry);
        }
    }
	*/
}
?>