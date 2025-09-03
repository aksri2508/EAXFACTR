<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_hsn_model.php
* @Class  			 : Master_hsn_model
* Model Name         : Master_hsn_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 05 JULY 2020
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Master_hsn_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'MASTER_HSN';
		$this->tableName 	= constant($this->tableNameStr);
    }
	
	
	/**
	* @METHOD NAME 	: saveHsn()
	*
	* @DESC 		: TO SAVE THE HSN DETAILS
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveHsn($getPostData)
    {	
		// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
		$whereExistsQry = array(
							  'LCASE(hsn_code)' => strtolower($getPostData['hsnCode']),
							);
		$chkRecord = $this->commonModel->isExists($this->tableName,$whereExistsQry);
		
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
	* @METHOD NAME 	: updateHsn()
	*
	* @DESC 		: TO UPDATE THE HSN DETAILS 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateHsn($getPostData)
    {
		$whereExistsQry = array(
								'LCASE(hsn_code)' => strtolower($getPostData['hsnCode']),
								'id!='			  => $getPostData['id'],
								);	
		
		$totRows = $this->commonModel->isExists($this->tableName,$whereExistsQry);
		               
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
	* @METHOD NAME 	: editHsn()
	*
	* @DESC 		: TO EDIT THE HSN DETAILS 
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editHsn($getPostData)
    {
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $getPostData['id']);
        $this->app_db->select($rowData);
        $this->app_db->from($this->tableName);
        $this->app_db->where('id', $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        return $rs->result_array();
    }
    
   
    /**
	* @METHOD NAME 	: getHsnList()
	*
	* @DESC 		: TO GET THE HSN LIST INFORMATION
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getHsnList($getPostData)
    {
        // SELECT 
        $this->app_db->select(array(
								$this->tableName.'.id',
								$this->tableName.'.hsn_code',
								$this->tableName.'.chapter',
								$this->tableName.'.heading',
								$this->tableName.'.sub_heading',
								$this->tableName.'.hsn_description',
								$this->tableName.'.status',
								$this->tableName.'.sap_id',
								$this->tableName.'.sap_error',
								$this->tableName.'.posting_status',
								MASTER_STATIC_DATA.'.name as statusName',
							));
        $this->app_db->from($this->tableName);
		$this->app_db->join(MASTER_STATIC_DATA, MASTER_STATIC_DATA.'.master_id = '.$this->tableName.'.status', '');
        $this->app_db->where(MASTER_STATIC_DATA.'.type', 'COMMON_STATUS');
		
        $this->app_db->where($this->tableName.'.is_deleted', '0');
		
        
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                if ($fieldValue!="") {
					if($fieldName=="hsnCode") {
						$this->app_db->like('LCASE(hsn_code)', strtolower($fieldValue));
					}else if($fieldName=="chapter") {
						$this->app_db->like('LCASE(chapter)', strtolower($fieldValue));
					}else if($fieldName=="heading") {
						$this->app_db->like('LCASE(heading)', strtolower($fieldValue));
					}else if($fieldName=="subHeading") {
						$this->app_db->like('LCASE(sub_heading)', strtolower($fieldValue));
					}else if($fieldName=="hsnDescription") {
						$this->app_db->like('LCASE(hsn_description)', strtolower($fieldValue));
					}else if($fieldName=="status"){
						$this->app_db->where($this->tableName.'.status', $fieldValue);
					}else if ($fieldName == "sapId") {
						$this->app_db->where($this->tableName.'.sap_id', $fieldValue);
					} else if ($fieldName == "postingStatus") {
						$this->app_db->where($this->tableName.'.posting_status', $fieldValue);
					}
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item($this->tableNameStr)['columns_list'],$fieldName);
				
			if(!empty($fieldName)){
				$this->app_db->order_by($fieldName, $sortOrder);
			}
			
        }else{
			$this->app_db->order_by($this->tableName.'.updated_on', 'desc');
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