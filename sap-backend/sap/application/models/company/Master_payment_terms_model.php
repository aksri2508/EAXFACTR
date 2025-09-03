<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_payment_terms_model.php
* @Class  			 : Master_payment_terms_model
* Model Name         : Master_payment_terms_model
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
class Master_payment_terms_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'MASTER_PAYMENT_TERMS';
		$this->tableName 	= constant($this->tableNameStr);
    }
	
	
	/**
	* @METHOD NAME 	: savePaymentTerms()
	*
	* @DESC 		: -
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function savePaymentTerms($getPostData)
    {	
		// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
		$whereExistsQry = array(
                              'LCASE(payment_term_code)' => strtolower($getPostData['paymentTermCode']),
							);
		$chkRecord = $this->commonModel->isExists(MASTER_PAYMENT_TERMS,$whereExistsQry);
		
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
	* @METHOD NAME 	: updatePaymentTerms()
	*
	* @DESC 		: -
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updatePaymentTerms($getPostData)
    {
      
		$whereExistsQry = array(
                                 'LCASE(payment_term_code)' => strtolower($getPostData['paymentTermCode']),
								'id!=' => $getPostData['id'],
								);	
		
        $totRows = $this->commonModel->isExists(MASTER_PAYMENT_TERMS,$whereExistsQry);
                       
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
	* @METHOD NAME 	: editPaymentTerms()
	*
	* @DESC 		: -
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editPaymentTerms($getPostData)
    {
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $getPostData['id']);
       // $this->app_db->select(array('id','payment_term_code','payment_term_name','status'));
        $this->app_db->select($rowData);
        $this->app_db->from(MASTER_PAYMENT_TERMS);
        $this->app_db->where('id', $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        return $rs->result_array();
    }
    
   
    /**
	* @METHOD NAME 	: getPaymentTermsList()
	*
	* @DESC 		: -
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getPaymentTermsList($getPostData)
    {
        // SELECT 
        $this->app_db->select(array(
                                MASTER_PAYMENT_TERMS.'.id',
                                MASTER_PAYMENT_TERMS.'.payment_term_code',
                                MASTER_PAYMENT_TERMS.'.payment_term_name',
								MASTER_PAYMENT_TERMS.'.status',
								MASTER_PAYMENT_TERMS.'.posting_status',
								MASTER_PAYMENT_TERMS.'.sap_id',
								MASTER_PAYMENT_TERMS.'.sap_error',
								MASTER_STATIC_DATA.'.name as statusName',
							));
        $this->app_db->from(MASTER_PAYMENT_TERMS);
		$this->app_db->join(MASTER_STATIC_DATA, MASTER_STATIC_DATA.'.master_id = '.MASTER_PAYMENT_TERMS.'.status', '');
        $this->app_db->where(MASTER_STATIC_DATA.'.type', 'COMMON_STATUS');
		
        $this->app_db->where(MASTER_PAYMENT_TERMS.'.is_deleted', '0');
		
        
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                if ($fieldValue!="") {
					if($fieldName=="payment_term_code") {
						$this->app_db->like('LCASE(payment_term_code)', strtolower($fieldValue));
					}else if($fieldName=="payment_term_name"){
						$this->app_db->like('LCASE(payment_term_name)', strtolower($fieldValue));
					}else if($fieldName=="status"){
						$this->app_db->where(MASTER_PAYMENT_TERMS.'.status', $fieldValue);
					}else if ($fieldName == "sapId") {
						$this->app_db->where(MASTER_PAYMENT_TERMS.'.sap_id', $fieldValue);
					} else if ($fieldName == "postingStatus") {
						$this->app_db->where(MASTER_PAYMENT_TERMS.'.posting_status', $fieldValue);
					}
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('MASTER_PAYMENT_TERMS')['columns_list'],$fieldName);
				
			if(!empty($fieldName)){
				$this->app_db->order_by($fieldName, $sortOrder);
			}
			
        }else{
			$this->app_db->order_by(MASTER_PAYMENT_TERMS.'.updated_on', 'desc');
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