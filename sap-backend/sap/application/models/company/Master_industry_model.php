<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_industry_model.php
* @Class  			 : Master_industry_model
* Model Name         : Master_industry_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 11 MAY 2018
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Master_industry_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'MASTER_INDUSTRY';
		$this->tableName 	= constant($this->tableNameStr);
    }
	
	
	/**
	* @METHOD NAME 	: saveIndustry()
	*
	* @DESC 		: TO SAVE THE INDUSTRY DETAILS
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveIndustry($getPostData)
    {	
		// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
		$whereExistsQry = array(
							  'LCASE(industry_name)' => strtolower($getPostData['industryName']),
							);
		$chkRecord = $this->commonModel->isExists(MASTER_INDUSTRY,$whereExistsQry);
		
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
	* @METHOD NAME 	: updateIndustry()
	*
	* @DESC 		: TO UPDATE THE INDUSTRY
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateIndustry($getPostData)
    {
		$whereExistsQry = array(
								 'LCASE(industry_name)' => strtolower($getPostData['industryName']),
								 'id!='				  => $getPostData['id'],
								);	
		
		$totRows = $this->commonModel->isExists(MASTER_INDUSTRY,$whereExistsQry);
		               
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
	* @METHOD NAME 	: editIndustry()
	*
	* @DESC 		: TO EDIT THE INDUSTRY
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editIndustry($getPostData)
    {
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $getPostData['id']);
        //$this->app_db->select(array('id','industry_name','industry_description','status'));
        $this->app_db->select($rowData);
        $this->app_db->from(MASTER_INDUSTRY);
        $this->app_db->where('id', $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        return $rs->result_array();
    }
    
   
    /**
	* @METHOD NAME 	: getIndustryList()
	*
	* @DESC 		: TO GET THE INDUSTRY LIST
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getIndustryList($getPostData)
    {
        // SELECT 
        $this->app_db->select(array(
										MASTER_INDUSTRY.'.id',
										MASTER_INDUSTRY.'.industry_name',
										MASTER_INDUSTRY.'.industry_description',
										MASTER_INDUSTRY.'.status',
										MASTER_INDUSTRY.'.posting_status',
										MASTER_INDUSTRY.'.sap_id',
										MASTER_INDUSTRY.'.sap_error',
										MASTER_STATIC_DATA.'.name as statusName'
							));
							
        $this->app_db->from(MASTER_INDUSTRY);
		
		$this->app_db->join(MASTER_STATIC_DATA, MASTER_STATIC_DATA.'.master_id = '.MASTER_INDUSTRY.'.status', '');
        $this->app_db->where(MASTER_STATIC_DATA.'.type', 'COMMON_STATUS');
        $this->app_db->where(MASTER_INDUSTRY.'.is_deleted', '0');
		
        
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                if ($fieldValue!="") {
					if($fieldName=="industryName") {
						$this->app_db->like('LCASE(industry_name)', strtolower($fieldValue));
					}else if($fieldName=="industryDescription"){
						$this->app_db->like('LCASE(industry_description)', strtolower($fieldValue));
					}else if($fieldName=="status"){
						$this->app_db->where(MASTER_INDUSTRY.'.status', $fieldValue);
					}else if ($fieldName == "sapId") {
						$this->app_db->where(MASTER_INDUSTRY.'.sap_id', $fieldValue);
					} else if ($fieldName == "postingStatus") {
						$this->app_db->where(MASTER_INDUSTRY.'.posting_status', $fieldValue);
					}
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('MASTER_INDUSTRY')['columns_list'],$fieldName);
				
			if(!empty($fieldName)){
				$this->app_db->order_by($fieldName, $sortOrder);
			}
			
			
        }else{
			$this->app_db->order_by(MASTER_INDUSTRY.'.updated_on', 'desc');
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
