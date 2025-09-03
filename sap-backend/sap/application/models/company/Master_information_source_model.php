<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_information_source_model.php
* @Class  			 : Master_information_source_model
* Model Name         : Master_information_source_model
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
class Master_information_source_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'MASTER_INFORMATION_SOURCE';
		$this->tableName 	= constant($this->tableNameStr);
    }
	
	
	/**
	* @METHOD NAME 	: saveInformationSource()
	*
	* @DESC 		: TO SAVE THE INFORMATION SOURCE DETAILS
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveInformationSource($getPostData)
    {	
		// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
		$whereExistsQry = array(
							  'LCASE(source_code)' => strtolower($getPostData['sourceCode']),
							);
		$chkRecord = $this->commonModel->isExists(MASTER_INFORMATION_SOURCE,$whereExistsQry);
		
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
	* @METHOD NAME 	: updateInformationSource()
	*
	* @DESC 		: TO UPDATE THE INFORMATION SOURCE
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateInformationSource($getPostData)
    {
		$whereExistsQry = array(
								 'LCASE(source_code)' => strtolower($getPostData['sourceCode']),
								'id!='				  => $getPostData['id'],
								);	
		
		$totRows = $this->commonModel->isExists(MASTER_INFORMATION_SOURCE,$whereExistsQry);
		               
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
	* @METHOD NAME 	: editInformationSource()
	*
	* @DESC 		: TO EDIT THE INFORMATION SOURCE
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editInformationSource($getPostData)
    {
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $getPostData['id']);
        //$this->app_db->select(array('id','source_code','source_description','status'));
        $this->app_db->select($rowData);
        $this->app_db->from(MASTER_INFORMATION_SOURCE);
        $this->app_db->where('id', $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        return $rs->result_array();
    }
	
	
    /**
	* @METHOD NAME 	: getInformationSourceList()
	*
	* @DESC 		: TO GET THE INFORMATION SOURCE LIST
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getInformationSourceList($getPostData)
    {
        // SELECT 
        $this->app_db->select(array(
								MASTER_INFORMATION_SOURCE.'.id',
								MASTER_INFORMATION_SOURCE.'.source_code',
								MASTER_INFORMATION_SOURCE.'.source_description',
								MASTER_INFORMATION_SOURCE.'.status',
								MASTER_INFORMATION_SOURCE.'.posting_status',
								MASTER_INFORMATION_SOURCE.'.sap_id',
								MASTER_INFORMATION_SOURCE.'.sap_error',
								MASTER_STATIC_DATA.'.name as statusName',
							));
							
        $this->app_db->from(MASTER_INFORMATION_SOURCE);
		$this->app_db->join(MASTER_STATIC_DATA, MASTER_STATIC_DATA.'.master_id = '.MASTER_INFORMATION_SOURCE.'.status', '');
        $this->app_db->where(MASTER_STATIC_DATA.'.type', 'COMMON_STATUS');
        $this->app_db->where(MASTER_INFORMATION_SOURCE.'.is_deleted', '0');
		
        
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                if ($fieldValue!="") {
					if($fieldName=="sourceCode") {
						$this->app_db->like('LCASE(source_code)', strtolower($fieldValue));
					}else if($fieldName=="sourceDescription") {
						$this->app_db->like('LCASE(source_description)', strtolower($fieldValue));
					}else if($fieldName=="status") {
						$this->app_db->where(MASTER_INFORMATION_SOURCE.'.status', $fieldValue);
					}else if ($fieldName == "sapId") {
						$this->app_db->where(MASTER_INFORMATION_SOURCE.'.sap_id', $fieldValue);
					} else if ($fieldName == "postingStatus") {
						$this->app_db->where(MASTER_INFORMATION_SOURCE.'.posting_status', $fieldValue);
					}
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('MASTER_INFORMATION_SOURCE')['columns_list'],$fieldName);
				
			if(!empty($fieldName)){
				$this->app_db->order_by($fieldName, $sortOrder);
			}
			
			
        }else{
			$this->app_db->order_by(MASTER_INFORMATION_SOURCE.'.updated_on', 'desc');
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
