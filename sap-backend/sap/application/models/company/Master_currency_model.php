<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_currency_model.php
* @Class  			 : Master_currency_model
* Model Name         : Master_currency_model
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
class Master_currency_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'MASTER_CURRENCY';
		$this->tableName 	= constant($this->tableNameStr);
    }
	
	
	/**
	* @METHOD NAME 	: saveCurrency()
	*
	* @DESC 		: TO SAVE THE CURRENCY DETAILS
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveCurrency($getPostData)
    {	
		// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
		$whereExistsQry = array(
							  'LCASE(currency_name)' => strtolower($getPostData['currencyName']),
							);
		$chkRecord = $this->commonModel->isExists(MASTER_CURRENCY,$whereExistsQry);
		
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
	* @METHOD NAME 	: updateCurrency()
	*
	* @DESC 		: TO UPDATE THE CURRENCY DETAILS
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateCurrency($getPostData)
    {
		$whereExistsQry = array(
								 'LCASE(currency_name)' => strtolower($getPostData['currencyName']),
								 'id!='				    => $getPostData['id'],
								);	
		
		$totRows = $this->commonModel->isExists(MASTER_CURRENCY,$whereExistsQry);
		               
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
	* @METHOD NAME 	: editCurrency()
	*
	* @DESC 		: TO EDIT THE CURRENCY
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editCurrency($getPostData)
    {
       $rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $getPostData['id']);//$this->app_db->select(array('id','currency_name','international_description','code','international_code','status'));
        $this->app_db->select($rowData);
        $this->app_db->from(MASTER_CURRENCY);
        $this->app_db->where('id', $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        return $rs->result_array();
    }
	    
	
    /**
	* @METHOD NAME 	: getCurrencyList()
	*
	* @DESC 		: TO GET THE CURRENCY LIST
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getCurrencyList($getPostData)
    {
        // SELECT 
        $this->app_db->select(array(
								MASTER_CURRENCY.'.id',
								MASTER_CURRENCY.'.currency_name',
								MASTER_CURRENCY.'.international_description',
								MASTER_CURRENCY.'.code',
								MASTER_CURRENCY.'.international_code',
								MASTER_CURRENCY.'.status',
								MASTER_CURRENCY.'.sap_id',
								MASTER_CURRENCY.'.posting_status',
								MASTER_CURRENCY.'.sap_error',
								MASTER_STATIC_DATA.'.name as statusName',
							));
        $this->app_db->from(MASTER_CURRENCY);
		$this->app_db->join(MASTER_STATIC_DATA, MASTER_STATIC_DATA.'.master_id = '.MASTER_CURRENCY.'.status', '');
        $this->app_db->where(MASTER_STATIC_DATA.'.type', 'COMMON_STATUS');
		
        $this->app_db->where(MASTER_CURRENCY.'.is_deleted', '0');
		
        
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
		
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                if ($fieldValue!="") {
					if($fieldName=="currencyName") {
						$this->app_db->like('LCASE(currency_name)', strtolower($fieldValue));
					}else if($fieldName=="internationalDescription"){
						$this->app_db->like('LCASE(international_description)', strtolower($fieldValue));
					}else if($fieldName=="code"){
						$this->app_db->like('LCASE(code)', strtolower($fieldValue));
					}else if($fieldName=="internationalCode"){
						$this->app_db->like('LCASE(international_code)', strtolower($fieldValue));
					}else if($fieldName=="status"){
						$this->app_db->where(MASTER_CURRENCY.'.status', $fieldValue);
					}else if ($fieldName == "sapId") {
						$this->app_db->where(MASTER_CURRENCY.'.sap_id', $fieldValue);
					} else if ($fieldName == "postingStatus") {
						$this->app_db->where(MASTER_CURRENCY.'.posting_status', $fieldValue);
					}
                }
            }
        }

        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('MASTER_CURRENCY')['columns_list'],$fieldName);
				
			if(!empty($fieldName)){
				$this->app_db->order_by($fieldName, $sortOrder);
			}
			
			
        }else{
			$this->app_db->order_by(MASTER_CURRENCY.'.updated_on', 'desc');
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
