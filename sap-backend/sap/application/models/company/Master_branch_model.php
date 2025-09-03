<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_branch_model.php
* @Class  			 : Master_branch_model
* Model Name         : Master_branch_model
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
class Master_branch_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr				 = 'MASTER_BRANCHES';
		$this->itemTableNameStr			 = 'WAREHOUSE';
		$this->itemTableColumnRef 		 = 'branch_id';
		$this->itemTableColumnReqRef	 = 'branchId';
		$this->tableName				 = constant($this->tableNameStr);
		$this->itemTableName			 = constant($this->itemTableNameStr);
    }
	
	
	/**
	* @METHOD NAME 	: saveBranch()
	*
	* @DESC 		: TO SAVE THE BRANCH DETAILS
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveBranch($getPostData)
    {	
		$rowData = bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);
		
		// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
		$whereExistsQry = array(
							  'LCASE(branch_code)' => strtolower($getPostData['branchCode']),
							);
							
		$chkRecord 		= $this->commonModel->isExists(MASTER_BRANCHES,$whereExistsQry);
		
        if (0 == $chkRecord) {
			$insertId 		= $this->commonModel->insertQry($this->tableName, $rowData);
			$this->createWareHouse($insertId); // BRANCH ID DETAILS 
            $modelOutput['flag'] = 1;
        } else {
            $modelOutput['flag'] = 2;
        }
        return $modelOutput;
    }
    
    
	/**
	* @METHOD NAME 	: createWareHouse()
	*
	* @DESC 		: TO CREATE THE WAREHOUSE DETAILS 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function createWareHouse($branchId)
    {	
		$frameArray = array(
								'warehouse_code' => 'WC_CODE_'.$branchId,
								'warehouse_name' => 'WC_HOUSENAME_'.$branchId,
								'branch_id' 		=> $branchId,
								'bin_id' 			=> 1,
								'address_1' 		=> '',
								'address_2' 		=> '',
								//'locationId'	=> '',
							);
							
		//$rowData  = bindConfigTableValues($this->itemTableNameStr, 'CREATE', $frameArray);
		
		$insertId = $this->commonModel->insertQry($this->itemTableName, $frameArray);
    }
    
	
	/**
	* @METHOD NAME 	: updateBranch()
	*
	* @DESC 		: TO UPDATE THE BRANCH  
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateBranch($getPostData)
    {
		$whereExistsQry = array(
								'LCASE(branch_code)' => strtolower($getPostData['branchCode']),
								'id!='			  	 => $getPostData['id'],
								);	
		
		$totRows = $this->commonModel->isExists(MASTER_BRANCHES,$whereExistsQry);
		               
        if(0 == $totRows) {
			$whereQry = array('id'=>$getPostData['id']);	

			$rowData  = bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
			$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);
			
            $modelOutput['flag'] = 1;
        } else {
            $modelOutput['flag'] = 2;
        }
        return $modelOutput;
    }
    
	
    /**
	* @METHOD NAME 	: editBranch()
	*
	* @DESC 		: TO EDIT THE BRANCH DETAILS 
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editBranch($getPostData)
    {  
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $getPostData['id']);
		$this->app_db->select($rowData);	//$this->app_db->select(array('id','branch_code','branch_name','first_name','last_name','email_id','status'));
        $this->app_db->from(MASTER_BRANCHES);
        $this->app_db->where('id', $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        return $rs->result_array();
    }
    
   
    /**
	* @METHOD NAME 	: getBranchList()
	*
	* @DESC 		: TO GET THE BRANCH LIST
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getBranchList($getPostData)
    {
        // SELECT 
        $this->app_db->select(array(
								MASTER_BRANCHES.'.id',
								MASTER_BRANCHES.'.branch_code',
								MASTER_BRANCHES.'.branch_name',
								MASTER_BRANCHES.'.first_name',
								MASTER_BRANCHES.'.last_name',
								MASTER_BRANCHES.'.email_id',
								MASTER_BRANCHES.'.status',
								MASTER_BRANCHES.'.posting_status',
								MASTER_BRANCHES.'.sap_id',
								MASTER_BRANCHES.'.sap_error',
								MASTER_STATIC_DATA.'.name as statusName'
							));
        $this->app_db->from(MASTER_BRANCHES);
		$this->app_db->join(MASTER_STATIC_DATA, MASTER_STATIC_DATA.'.master_id = '.MASTER_BRANCHES.'.status', '');
        $this->app_db->where(MASTER_STATIC_DATA.'.type', 'COMMON_STATUS');
		
        $this->app_db->where(MASTER_BRANCHES.'.is_deleted', '0');
		
        
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                if ($fieldValue!="") {
					if($fieldName=="branchCode") {
						$this->app_db->like('LCASE(branch_code)', strtolower($fieldValue));
					}else if($fieldName=="branchName") {
						$this->app_db->like('LCASE(branch_name)', strtolower($fieldValue));
					}else if($fieldName=="warehouseId"){
						$this->app_db->where(MASTER_BRANCHES.'.warehouse_id', $fieldValue);
					}else if($fieldName=="branchAdminId"){
						$this->app_db->where(MASTER_BRANCHES.'.branch_admin_id', $fieldValue);
					}else if($fieldName=="status"){
						$this->app_db->where(MASTER_BRANCHES.'.status', $fieldValue);
					} else if ($fieldName == "sapId") {
						$this->app_db->where(MASTER_BRANCHES.'.sap_id', $fieldValue);
					} else if ($fieldName == "postingStatus") {
						$this->app_db->where(MASTER_BRANCHES.'.posting_status', $fieldValue);
					}
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('MASTER_BRANCHES')['columns_list'],$fieldName);
				
			if(!empty($fieldName)){
				$this->app_db->order_by($fieldName, $sortOrder);
			}
			
        }else{
			$this->app_db->order_by(MASTER_BRANCHES.'.updated_on', 'desc');
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