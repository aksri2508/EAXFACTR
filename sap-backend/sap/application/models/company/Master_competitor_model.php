<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_competitor_model.php
* @Class  			 : Master_competitor_model
* Model Name         : Master_competitor_model
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
class Master_competitor_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'MASTER_COMPETITOR';
		$this->tableName 	= constant($this->tableNameStr);
    }
	
	
	/**
	* @METHOD NAME 	: saveCompetitor()
	*
	* @DESC 		: TO SAVE THE COMPETITOR DETAILS
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveCompetitor($getPostData)
    {	
		// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
		$whereExistsQry = array(
							  'LCASE(competitor_name)' => strtolower($getPostData['competitorName']),
							);
		$chkRecord = $this->commonModel->isExists(MASTER_COMPETITOR,$whereExistsQry);
		
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
	* @METHOD NAME 	: updateCompetitor()
	*
	* @DESC 		: TO UPDATE THE COMPETITOR
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateCompetitor($getPostData)
    {
		$whereExistsQry = array(
								 'LCASE(competitor_name)' => strtolower($getPostData['competitorName']),
								 'id!='				  => $getPostData['id'],
								);	
		
		$totRows = $this->commonModel->isExists(MASTER_COMPETITOR,$whereExistsQry);
		               
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
	* @METHOD NAME 	: editCompetitor()
	*
	* @DESC 		: TO EDIT THE COMPETITOR
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editCompetitor($getPostData)
    {
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $getPostData['id']);
        //$this->app_db->select(array('id','competitor_name','threat_level_id','status'));
        $this->app_db->select($rowData);
        $this->app_db->from(MASTER_COMPETITOR);
        $this->app_db->where('id', $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        return $rs->result_array();
    }
    
   
    /**
	* @METHOD NAME 	: getCompetitorList()
	*
	* @DESC 		: TO GET THE COMPETITOR LIST
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getCompetitorList($getPostData)
    {
        $this->app_db->select(array(
								MASTER_COMPETITOR.'.id',
								MASTER_COMPETITOR.'.competitor_name',
								MASTER_COMPETITOR.'.threat_level_id',
								MASTER_COMPETITOR.'.status',
								MASTER_COMPETITOR.'.posting_status',
								MASTER_COMPETITOR.'.sap_id',
								MASTER_COMPETITOR.'.sap_error',							
								MASTER_STATIC_DATA.'.name as statusName',
							));
        $this->app_db->from(MASTER_COMPETITOR);
		$this->app_db->join(MASTER_STATIC_DATA, MASTER_STATIC_DATA.'.master_id = '.MASTER_COMPETITOR.'.status', '');
        $this->app_db->where(MASTER_STATIC_DATA.'.type', 'COMMON_STATUS');
        $this->app_db->where(MASTER_COMPETITOR.'.is_deleted', '0');
		
        
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                if ($fieldValue!="") {
					if($fieldName=="threatLevelId") {
						$this->app_db->where('threat_level_id', $fieldValue);
					}
					else if($fieldName=="competitorName"){
						$this->app_db->like('LCASE(competitor_name)', strtolower($fieldValue));
					}
					else if($fieldName=="status"){
						$this->app_db->where(MASTER_COMPETITOR.'.status', $fieldValue);
					}else if ($fieldName == "sapId") {
						$this->app_db->where(MASTER_COMPETITOR.'.sap_id', $fieldValue);
					} else if ($fieldName == "postingStatus") {
						$this->app_db->where(MASTER_COMPETITOR.'.posting_status', $fieldValue);
					}
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('MASTER_COMPETITOR')['columns_list'],$fieldName);
				
			if(!empty($fieldName)){
				$this->app_db->order_by($fieldName, $sortOrder);
			}
					
        }else{
			$this->app_db->order_by(MASTER_COMPETITOR.'.updated_on', 'desc');
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