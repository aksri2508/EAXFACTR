<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_tax_attribute_model.php
* @Class  			 : Master_tax_attribute_model
* Model Name         : Master_tax_attribute_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 30 MAY 2019
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Master_tax_attribute_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'MASTER_TAX_ATTRIBUTE';
		$this->tableName 	= constant($this->tableNameStr);
    }
	
	
	/**
	* @METHOD NAME 	: saveTaxAttribute()
	*
	* @DESC 		: TO SAVE THE TAX ATTRIBUTE DETAILS
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveTaxAttribute($getPostData)
    {	
		// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
		$whereExistsQry = array(
							  'LCASE(attribute_code)' => strtolower($getPostData['attributeCode']),
							);
		$chkRecord = $this->commonModel->isExists(MASTER_TAX_ATTRIBUTE,$whereExistsQry);
		
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
	* @METHOD NAME 	: updateTaxAttribute()
	*
	* @DESC 		: TO UPDATE THE TAX ATTRIBUTE
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateTaxAttribute($getPostData)
    {
		$whereExistsQry = array(
								 'LCASE(attribute_code)' => strtolower($getPostData['attributeCode']),
								 'id!='				     => $getPostData['id'],
								);	
		
		$totRows = $this->commonModel->isExists(MASTER_TAX_ATTRIBUTE,$whereExistsQry);
		               
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
	* @METHOD NAME 	: editTaxAttribute()
	*
	* @DESC 		: TO EDIT THE TAX
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editTaxAttribute($getPostData)
    {
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $getPostData['id']);
       // $this->app_db->select(array('id','attribute_code','attribute_description','attribute_percentage','status'));
        $this->app_db->select($rowData);
        $this->app_db->from(MASTER_TAX_ATTRIBUTE);
        $this->app_db->where('id', $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        return $rs->result_array();
    }
    
   
    /**
	* @METHOD NAME 	: getTaxAttributeList()
	*
	* @DESC 		: TO GET THE TAX ATTRIBUTE LIST
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getTaxAttributeList($getPostData)
    {
        // SELECT 
        $this->app_db->select(array(
								MASTER_TAX_ATTRIBUTE.'.id',
								MASTER_TAX_ATTRIBUTE.'.attribute_code',
								MASTER_TAX_ATTRIBUTE.'.attribute_description',
								MASTER_TAX_ATTRIBUTE.'.attribute_percentage',
								MASTER_TAX_ATTRIBUTE.'.posting_status',
								MASTER_TAX_ATTRIBUTE.'.sap_id',
								MASTER_TAX_ATTRIBUTE.'.sap_error',
								MASTER_TAX_ATTRIBUTE.'.status',
								MASTER_STATIC_DATA.'.name as statusName',
							));
        $this->app_db->from(MASTER_TAX_ATTRIBUTE);
		
		$this->app_db->join(MASTER_STATIC_DATA, MASTER_STATIC_DATA.'.master_id = '.MASTER_TAX_ATTRIBUTE.'.status', '');
		
        $this->app_db->where(MASTER_STATIC_DATA.'.type', 'COMMON_STATUS');
		
        $this->app_db->where(MASTER_TAX_ATTRIBUTE.'.is_deleted', '0');
		
        
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                if ($fieldValue!="") {
					if($fieldName=="attributeCode") {
						$this->app_db->like('LCASE(attribute_code)', strtolower($fieldValue));
					}else if($fieldName=="attributeDescription"){
						$this->app_db->like('LCASE(attribute_description)', strtolower($fieldValue));
					}else if($fieldName=="attributePercentage"){
						$this->app_db->like('LCASE(attribute_percentage)', strtolower($fieldValue));
					}else if($fieldName=="status"){
						$this->app_db->where(MASTER_TAX_ATTRIBUTE.'.status', $fieldValue);
					}else if ($fieldName == "sapId") {
						$this->app_db->where(MASTER_TAX_ATTRIBUTE.'.sap_id', $fieldValue);
					} else if ($fieldName == "postingStatus") {
						$this->app_db->where(MASTER_TAX_ATTRIBUTE.'.posting_status', $fieldValue);
					}
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName 		= $tableProperties['sortField'];
            $sortOrder 		= ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('MASTER_TAX_ATTRIBUTE')['columns_list'],$fieldName);
				
			if(!empty($fieldName)){
				$this->app_db->order_by($fieldName, $sortOrder);
			}
			
			
        }else{
			$this->app_db->order_by(MASTER_TAX_ATTRIBUTE.'.updated_on', 'desc');
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