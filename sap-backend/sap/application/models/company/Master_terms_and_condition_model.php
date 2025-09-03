<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_terms_and_condition_model
* Model Name         : Master_terms_and_condition_model
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
class Master_terms_and_condition_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'MASTER_TERMS_AND_CONDITION';
		$this->tableName 	= constant($this->tableNameStr);
    }
	
	
	/**
	* @METHOD NAME 	: saveterms_and_condition()
	*
	* @DESC 		: TO SAVE THE terms_and_condition DETAILS
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveTermsandcondition($getPostData)
    {	
		// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
		$whereExistsQry = array(
							  'LCASE(heading)' => strtolower($getPostData['heading']),
							);
		$chkRecord = $this->commonModel->isExists(MASTER_TERMS_AND_CONDITION,$whereExistsQry);
		
        if (0 == $chkRecord) {	
        	
			$rowData  = bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);

			$insertId = $this->commonModel->insertQry($this->tableName, $rowData);
            $modelOutput['flag'] = 1;
        } else {
            $modelOutput['flag'] = 2;
        }
        return $modelOutput;
    }
    
    
	/**
	* @METHOD NAME 	: updateTermsandcondition()
	*
	* @DESC 		: TO UPDATE THE Termsandcondition
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateTermsandcondition($getPostData)
    {
		$whereExistsQry = array(
								 'LCASE(heading)' => strtolower($getPostData['heading']),
								'id!='				  => $getPostData['id'],
								);	
		
		$totRows = $this->commonModel->isExists(MASTER_TERMS_AND_CONDITION,$whereExistsQry);
		               
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
	* @METHOD NAME 	: editTermsandcondition()
	*
	* @DESC 		: TO EDIT THE Termsandcondition
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editTermsandcondition($getPostData)
    {
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $getPostData['id']);
        $this->app_db->select($rowData);
        $this->app_db->from(MASTER_TERMS_AND_CONDITION);
        $this->app_db->where('id', $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        return $rs->result_array();
    }
	
	
    /**
	* @METHOD NAME 	: getTermsandconditionList()
	*
	* @DESC 		: TO GET THE Termsandcondition LIST
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getTermsandconditionList($getPostData)
    {
        // SELECT 
        $this->app_db->select(array(
								MASTER_TERMS_AND_CONDITION.'.id',
								MASTER_TERMS_AND_CONDITION.'.heading',
								MASTER_TERMS_AND_CONDITION.'.body_content',
								MASTER_TERMS_AND_CONDITION.'.posting_status',
								MASTER_TERMS_AND_CONDITION.'.sap_id',
								MASTER_TERMS_AND_CONDITION.'.sap_error',
								MASTER_TERMS_AND_CONDITION.'.status',
								MASTER_STATIC_DATA.'.name as statusName',
							));
							
        $this->app_db->from(MASTER_TERMS_AND_CONDITION);
		$this->app_db->join(MASTER_STATIC_DATA, MASTER_STATIC_DATA.'.master_id = '.MASTER_TERMS_AND_CONDITION.'.status', 'LEFT');
        $this->app_db->where(MASTER_STATIC_DATA.'.type', 'COMMON_STATUS');
        $this->app_db->where(MASTER_TERMS_AND_CONDITION.'.is_deleted', '0');
		
        
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                if ($fieldValue!="") {
					if($fieldName=="heading") {
						$this->app_db->like('LCASE(heading)', strtolower($fieldValue));
					}else if($fieldName=="status") {
						$this->app_db->where(MASTER_TERMS_AND_CONDITION.'.status', $fieldValue);
					}else if ($fieldName == "sapId") {
						$this->app_db->where(MASTER_TERMS_AND_CONDITION.'.sap_id', $fieldValue);
					} else if ($fieldName == "postingStatus") {
						$this->app_db->where(MASTER_TERMS_AND_CONDITION.'.posting_status', $fieldValue);
					}
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('MASTER_TERMS_AND_CONDITION')['columns_list'], $fieldName);
				
			if(!empty($fieldName)){
				$this->app_db->order_by($fieldName, $sortOrder);
			}
			
			
        }else{
			$this->app_db->order_by(MASTER_TERMS_AND_CONDITION.'.updated_on', 'desc');
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
