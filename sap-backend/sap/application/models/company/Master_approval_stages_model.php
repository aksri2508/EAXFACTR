<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_approval_stages_model.php
* @Class  			 : Master_approval_stages_model
* Model Name         : Master_approval_stages_model
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
class Master_approval_stages_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'MASTER_APPROVAL_STAGES';
		$this->tableName 	= constant($this->tableNameStr);
    }
	
	
	/**
	* @METHOD NAME 	: saveApprovalStage()
	*
	* @DESC 		: TO SAVE THE APPROVAL STAGE
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveApprovalStage($getPostData)
    {	
		$rowData  	= bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);
		$finalCount = ($getPostData['noOfApprovals'] > $getPostData['noOfRejections'] ) ? $getPostData['noOfApprovals'] :  $getPostData['noOfRejections'];
		$authorizerCount = count(explode(",",$getPostData['authorizerId']));
	
		// ADDITIONAL CHECKING CONSTRAINTS 
		if($finalCount > $authorizerCount) {
			 $modelOutput['flag'] = 3;
			 return $modelOutput;
		}
		
		// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
		$whereExistsQry = array(
								  'LCASE(stage_name)' => strtolower($getPostData['stageName']),
							   );
							
		$chkRecord 		= $this->commonModel->isExists(MASTER_APPROVAL_STAGES,$whereExistsQry);
		
        if (0 == $chkRecord) {
			$insertId			 = $this->commonModel->insertQry($this->tableName, $rowData);
            $modelOutput['flag'] = 1;
        } else {
            $modelOutput['flag'] = 2;
        }
        return $modelOutput;
    }
    
    
	/**
	* @METHOD NAME 	: updateApprovalStage()
	*
	* @DESC 		: TO UPDATE THE APPROVAL STAGE 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateApprovalStage($getPostData)
    {
		$whereExistsQry = array(
								 'LCASE(stage_name)' => strtolower($getPostData['stageName']),
								 'id!='				 => $getPostData['id'],
								);	
								
		$totRows		= $this->commonModel->isExists(MASTER_APPROVAL_STAGES,$whereExistsQry);
		               
        if(0 == $totRows) {
			$whereQry 	= array('id'=>$getPostData['id']);
			$rowData 	= bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
			$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);
		    $modelOutput['flag'] = 1;
        } else {
            $modelOutput['flag'] = 2;
        }
        return $modelOutput;
    }
    
	
    /**
	* @METHOD NAME 	: editApprovalStage()
	*
	* @DESC 		: TO EDIT THE APPROVAL STAGE
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editApprovalStage($getPostData)
    {
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $getPostData['id']);
        $this->app_db->select($rowData);
        $this->app_db->from(MASTER_APPROVAL_STAGES);
        $this->app_db->where('id', $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        return $rs->result_array();
    }
    
	
    /**
	* @METHOD NAME 	: getApprovalStageList()
	*
	* @DESC 		: TO GET THE APPROVAL STAGE LIST INFORMATION
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getApprovalStageList($getPostData)
    {
        // SELECT 
        $this->app_db->select(array(
								MASTER_APPROVAL_STAGES.'.id',
								MASTER_APPROVAL_STAGES.'.stage_name',
								MASTER_APPROVAL_STAGES.'.stage_description',
                                MASTER_APPROVAL_STAGES.'.no_of_approvals',
                                MASTER_APPROVAL_STAGES.'.no_of_rejections',
								MASTER_APPROVAL_STAGES.'.status',
								MASTER_APPROVAL_STAGES.'.sap_id',
                                MASTER_APPROVAL_STAGES.'.sap_error',
                                MASTER_APPROVAL_STAGES.'.posting_status',
								MASTER_STATIC_DATA.'.name as statusName',
							));
        $this->app_db->from(MASTER_APPROVAL_STAGES);
		$this->app_db->join(MASTER_STATIC_DATA, MASTER_STATIC_DATA.'.master_id = '.MASTER_APPROVAL_STAGES.'.status', '');
        $this->app_db->where(MASTER_STATIC_DATA.'.type', 'COMMON_STATUS');
        $this->app_db->where(MASTER_APPROVAL_STAGES.'.is_deleted', '0');
		
        
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                if ($fieldValue!="") {
					if($fieldName=="stageName") {
						$this->app_db->like('LCASE(stage_name)', strtolower($fieldValue));
					} else if($fieldName=="stageDescription") {
						$this->app_db->like('LCASE(stage_description)', strtolower($fieldValue));
					} else if($fieldName=="noOfApprovals") {
                        $this->app_db->where(MASTER_APPROVAL_STAGES.'.no_of_approvals', $fieldValue);
					} else if($fieldName=="noOfRejections") {
                        $this->app_db->where(MASTER_APPROVAL_STAGES.'.no_of_rejections', $fieldValue);
					} else if($fieldName=="status"){
						$this->app_db->where(MASTER_APPROVAL_STAGES.'.status', $fieldValue);
					} else if ($fieldName == "sapId") {
						$this->app_db->where(MASTER_APPROVAL_STAGES.'.sap_id', $fieldValue);
					} else if ($fieldName == "postingStatus") {
						$this->app_db->where(MASTER_APPROVAL_STAGES.'.posting_status', $fieldValue);
					}
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('MASTER_APPROVAL_STAGES')['columns_list'],$fieldName);
				
			if(!empty($fieldName)){
				$this->app_db->order_by($fieldName, $sortOrder);
			}
			
        }else{
			$this->app_db->order_by(MASTER_APPROVAL_STAGES.'.updated_on', 'desc');
		}
        
        // CLONE DB QUERY TO GET THE TOTAL RESULT BEFORE PAGINATION
        $tempdb       = clone $this->app_db;
        $totalRecords = $tempdb->count_all_results();
        
        // PAGINATION
        if (isset($tableProperties['first'])) {
            $offset = $tableProperties['first'];
            $limit  = $tableProperties['rows'];
        } else {
            $offset = 0;
            $limit  = $tableProperties['rows'];
        }
        $this->app_db->limit($limit, $offset);
        
        // GET RESULTS 		
        $searchResultSet = $this->app_db->get();
        $searchResultSet = $searchResultSet->result_array();
        
		// MODEL DATA 
        $modelData['searchResults'] = $searchResultSet;
        $modelData['totalRecords']  = $totalRecords;
        return $modelData;
    }
	
}
?>