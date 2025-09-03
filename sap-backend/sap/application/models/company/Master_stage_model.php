<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_stage_model.php
* @Class  			 : Master_stage_model
* Model Name         : Master_stage_model
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
class Master_stage_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'MASTER_STAGE';
		$this->tableName 	= constant($this->tableNameStr);
    }
	
	
	/**
	* @METHOD NAME 	: saveStage()
	*
	* @DESC 		: TO SAVE THE STAGE DETAILS
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveStage($getPostData)
    {	
		// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
		$whereExistsQry = array(
							  'LCASE(stage_number)' => strtolower($getPostData['stageNumber']),
							);
		$chkRecord = $this->commonModel->isExists(MASTER_STAGE,$whereExistsQry);
		
        if (0 == $chkRecord) {
			$rowData 						= bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);
			$rowData['close_percentage']	= round($getPostData['closePercentage'],2);
			$insertId 						= $this->commonModel->insertQry($this->tableName, $rowData);
            $modelOutput['flag'] = 1;
        } else {
            $modelOutput['flag'] = 2;
        }
        return $modelOutput;
    }
    
    
	/**
	* @METHOD NAME 	: updateStage()
	*
	* @DESC 		: TO UPDATE THE STAGE
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateStage($getPostData)
    {
		$whereExistsQry = array(
								 'LCASE(stage_number)' => strtolower($getPostData['stageNumber']),
								'id!='				  => $getPostData['id'],
								);	
		
		$totRows = $this->commonModel->isExists(MASTER_STAGE,$whereExistsQry);
		               
        if(0 == $totRows) {		
			$whereQry 						= array('id'=>$getPostData['id']);			
			$rowData 						= bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
			$rowData['close_percentage'] 	= round($getPostData['closePercentage'],2);
			$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);
            $modelOutput['flag'] = 1;
        } else {
            $modelOutput['flag'] = 2;
        }
        return $modelOutput;
    }
    
	
    /**
	* @METHOD NAME 	: editStage()
	*
	* @DESC 		: TO EDIT THE STAGE
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editStage($getPostData)
    {
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $getPostData['id']);
		$this->app_db->select($rowData);
        //$this->app_db->select(array('id','stage_name','stage_number','close_percentage','status'));
        $this->app_db->from(MASTER_STAGE);
        $this->app_db->where('id', $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        return $rs->result_array();
    }
    
   
    /**
	* @METHOD NAME 	: getStageList()
	*
	* @DESC 		: TO GET THE STAGE LIST
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getStageList($getPostData)
    {
        // SELECT 
        $this->app_db->select(array(
									MASTER_STAGE.'.id',
									MASTER_STAGE.'.stage_name',
									MASTER_STAGE.'.stage_number',
									MASTER_STAGE.'.close_percentage',
									MASTER_STAGE.'.status',
									MASTER_STAGE.'.posting_status',
									MASTER_STAGE.'.sap_id',
									MASTER_STAGE.'.sap_error',
									MASTER_STATIC_DATA.'.name as statusName',
								));
							
        $this->app_db->from(MASTER_STAGE);
		
		$this->app_db->join(MASTER_STATIC_DATA, MASTER_STATIC_DATA.'.master_id = '.MASTER_STAGE.'.status', '');
        $this->app_db->where(MASTER_STATIC_DATA.'.type', 'COMMON_STATUS');
		
        $this->app_db->where(MASTER_STAGE.'.is_deleted', '0');
		
        
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                if ($fieldValue!="") {
					if($fieldName=="stageName") {
						$this->app_db->like('LCASE(stage_name)', strtolower($fieldValue));
					}else if($fieldName=="stageNumber"){
						$this->app_db->like('LCASE(stage_number)', strtolower($fieldValue));
					}else if($fieldName=="closePercentage"){
						$this->app_db->like('LCASE(close_percentage)', strtolower($fieldValue));
					}else if($fieldName=="status"){
						$this->app_db->where(MASTER_STAGE.'.status', $fieldValue);
					}else if ($fieldName == "sapId") {
						$this->app_db->where(MASTER_STAGE.'.sap_id', $fieldValue);
					} else if ($fieldName == "postingStatus") {
						$this->app_db->where(MASTER_STAGE.'.posting_status', $fieldValue);
					}
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";

			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('MASTER_STAGE')['columns_list'],$fieldName);
				
			if(!empty($fieldName)){
				$this->app_db->order_by($fieldName, $sortOrder);
			}else{
				$this->app_db->order_by('stage_number', $sortOrder);
			}
			
			
        }else{
			$this->app_db->order_by(MASTER_STAGE.'.updated_on', 'desc');		
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