<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_bin_model.php
* @Class  			 : Master_bin_model
* Model Name         : Master_bin_model
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
class Master_bin_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'MASTER_BIN';
		$this->tableName 	= constant($this->tableNameStr);
    }
	
	
	/**
	* @METHOD NAME 	: saveBin()
	*
	* @DESC 		: TO SAVE THE BIN DETAILS
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveBin($getPostData)
    {	
		// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
		$whereExistsQry = array(
							  'LCASE(bin_code)' => strtolower($getPostData['binCode']),
							);
		$chkRecord = $this->commonModel->isExists(MASTER_BIN,$whereExistsQry);
		
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
	* @METHOD NAME 	: updateBin()
	*
	* @DESC 		: TO UPDATE THE BIN DETAILS 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateBin($getPostData)
    {
		$whereExistsQry = array(
								'LCASE(bin_code)' => strtolower($getPostData['binCode']),
								'id!='			  => $getPostData['id'],
								);	
		
		$totRows = $this->commonModel->isExists(MASTER_BIN,$whereExistsQry);
		               
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
	* @METHOD NAME 	: editBin()
	*
	* @DESC 		: TO EDIT THE BIN DETAILS 
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editBin($getPostData)
    {
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $getPostData['id']);
        //$this->app_db->select(array('id','bin_code','bin_name','status'));
        $this->app_db->select($rowData);
        $this->app_db->from(MASTER_BIN);
        $this->app_db->where('id', $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        return $rs->result_array();
    }
    
   
    /**
	* @METHOD NAME 	: getBinList()
	*
	* @DESC 		: TO GET THE BIN LIST INFORMATION
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getBinList($getPostData)
    {
        // SELECT 
        $this->app_db->select(array(
								MASTER_BIN.'.id',
								MASTER_BIN.'.bin_code',
								MASTER_BIN.'.bin_name',
								MASTER_BIN.'.posting_status',
								MASTER_BIN.'.sap_id',
								MASTER_BIN.'.sap_error',
								MASTER_BIN.'.status',
								MASTER_STATIC_DATA.'.name as statusName',
							));
        $this->app_db->from(MASTER_BIN);
		$this->app_db->join(MASTER_STATIC_DATA, MASTER_STATIC_DATA.'.master_id = '.MASTER_BIN.'.status', '');
        $this->app_db->where(MASTER_STATIC_DATA.'.type', 'COMMON_STATUS');
		
        $this->app_db->where(MASTER_BIN.'.is_deleted', '0');
		
        
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                if ($fieldValue!="") {
					if($fieldName=="binCode") {
						$this->app_db->like('LCASE(bin_code)', strtolower($fieldValue));
					}else if($fieldName=="binName") {
						$this->app_db->like('LCASE(bin_name)', strtolower($fieldValue));
					}else if($fieldName=="status"){
						$this->app_db->where(MASTER_BIN.'.status', $fieldValue);
					}else if ($fieldName == "sapId") {
						$this->app_db->where(MASTER_BIN.'.sap_id', $fieldValue);
					} else if ($fieldName == "postingStatus") {
						$this->app_db->where(MASTER_BIN.'.posting_status', $fieldValue);
					}
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('MASTER_BIN')['columns_list'],$fieldName);
				
			if(!empty($fieldName)){
				$this->app_db->order_by($fieldName, $sortOrder);
			}
			
        }else{
			$this->app_db->order_by(MASTER_BIN.'.updated_on', 'desc');
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