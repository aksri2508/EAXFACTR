<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_reason_model.php
* @Class  			 : Master_reason_model
* Model Name         : Master_reason_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 16 MAY 2019
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Master_reason_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'MASTER_REASON';
		$this->tableName 	= constant($this->tableNameStr);
    }
	
	
	/**
	* @METHOD NAME 	: saveReason()
	*
	* @DESC 		: TO SAVE THE REASON DETAILS
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveReason($getPostData)
    {	
		$rowData  = bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);
		
		// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
		$whereExistsQry = array(
							  'LCASE(reason_description)' => strtolower($getPostData['reasonDescription']),
							);
		$chkRecord = $this->commonModel->isExists(MASTER_REASON,$whereExistsQry);
		
        if (0 == $chkRecord) {
			$insertId = $this->commonModel->insertQry($this->tableName, $rowData);
            $modelOutput['flag'] = 1;
        } else {
            $modelOutput['flag'] = 2;
        }
        return $modelOutput;
    }
    
    
	/**
	* @METHOD NAME 	: updateReason()
	*
	* @DESC 		: TO UPDATE THE REASON
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateReason($getPostData)
    {
		$whereExistsQry = array(
								 'LCASE(reason_description)' => strtolower($getPostData['reasonDescription']),
								 'id!='				 		 => $getPostData['id'],
								);	
		
		$totRows = $this->commonModel->isExists(MASTER_REASON,$whereExistsQry);
		               
        if(0 == $totRows) {
			$whereQry = array('id'=>$getPostData['id']);
			
			$rowData = bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
			$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);
		    $modelOutput['flag'] = 1;
        } else {
            $modelOutput['flag'] = 2;
        }
        return $modelOutput;
    }
    
	
    /**
	* @METHOD NAME 	: editReason()
	*
	* @DESC 		: TO EDIT THE REASON
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editReason($getPostData)
    {
        $this->app_db->select(array('id','reason_description','status'));
        $this->app_db->from(MASTER_REASON);
        $this->app_db->where('id', $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        return $rs->result_array();
    }
    
   
    /**
	* @METHOD NAME 	: getReasonList()
	*
	* @DESC 		: TO GET THE REASON LIST INFORMATION
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getReasonList($getPostData)
    {
        // SELECT 
        $this->app_db->select(array(
								MASTER_REASON.'.id',
								MASTER_REASON.'.reason_description',
								MASTER_REASON.'.status',
								MASTER_REASON.'.posting_status',
								MASTER_REASON.'.sap_id',
								MASTER_REASON.'.sap_error',
								MASTER_STATIC_DATA.'.name as statusName',
							));
        $this->app_db->from(MASTER_REASON);
		$this->app_db->join(MASTER_STATIC_DATA, MASTER_STATIC_DATA.'.master_id = '.MASTER_REASON.'.status', '');
        $this->app_db->where(MASTER_STATIC_DATA.'.type', 'COMMON_STATUS');
		
        $this->app_db->where(MASTER_REASON.'.is_deleted', '0');
		
        
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                if ($fieldValue!="") {
					if($fieldName=="reasonDescription") {
						$this->app_db->like('LCASE(reason_description)', strtolower($fieldValue));
					}else if($fieldName=="status"){
						$this->app_db->where(MASTER_REASON.'.status', $fieldValue);
					}else if ($fieldName == "sapId") {
						$this->app_db->where(MASTER_REASON.'.sap_id', $fieldValue);
					} else if ($fieldName == "postingStatus") {
						$this->app_db->where(MASTER_REASON.'.posting_status', $fieldValue);
					}
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('MASTER_REASON')['columns_list'],$fieldName);
				
			if(!empty($fieldName)){
				$this->app_db->order_by($fieldName, $sortOrder);
			}
			
        }else{
			$this->app_db->order_by(MASTER_REASON.'.updated_on', 'desc');
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