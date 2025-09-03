<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_territory_model.php
* @Class  			 : Master_territory_model
* Model Name         : Master_territory_model
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
class Master_territory_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'MASTER_TERRITORY';
		$this->tableName 	= constant($this->tableNameStr);
    }
	
	public $frameIds = array();
	
	
	/**
	* @METHOD NAME 	: saveTerritory()
	*
	* @DESC 		: TO SAVE THE TERRITORY DETAILS 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveTerritory($getPostData)
    {	
		// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
		$whereExistsQry = array(
							  'LCASE(territory_name)' => strtolower($getPostData['territoryName']),
							);
		$chkRecord = $this->commonModel->isExists(MASTER_TERRITORY,$whereExistsQry);
		
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
	* @METHOD NAME 	: updateTerritory()
	*
	* @DESC 		: TO UPDATE THE TERRITORY Type
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateTerritory($getPostData)
    {
		$whereExistsQry = array(
								 'LCASE(territory_name)' => strtolower($getPostData['territoryName']),
								 'id!='				 	 => $getPostData['id'],
								);	
		
		$totRows = $this->commonModel->isExists(MASTER_TERRITORY,$whereExistsQry);
		               
        if(0 == $totRows) {	
			
			// Adding Transaction Start
			$this->app_db->trans_start();
		
			$whereQry = array('id'=>$getPostData['id']);			
			$rowData = bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
			$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);
			
			if($getPostData['status'] ==2){ // InActive
				$this->updateChildRecordsToInactive($getPostData);
			}
			
			$this->app_db->trans_complete(); // TRANSACTION COMPLETE
			
            $modelOutput['flag'] = 1;
        } else {
            $modelOutput['flag'] = 2;
        }
        return $modelOutput;
    }
	
	
	/**
	* @METHOD NAME 	: getRecordsByMappingId()
	*
	* @DESC 		: FRAME THE SUB-CHILD RECORDS USING MAPPING ID 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function getRecordsByMappingId($id){
		// FRAME IDS 
		$this->frameIds[] = $id;		
		$this->app_db->select(array('id','mapping_id','status'));
        $this->app_db->from(MASTER_TERRITORY);
        $this->app_db->where('mapping_id', $id);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
		
		if($rs->num_rows()>0){
			 $resultData  =  $rs->result_array();
			 $childId  	  =  $resultData[0]['id'];
			 $this->getRecordsByMappingId($childId);
		}
	}
	
	
	/**
	* @METHOD NAME 	: updateChildRecordsToInactive()
	*
	* @DESC 		: UPDATE THE CHILD RECORDS TO IN-ACTIVE STATUS 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function updateChildRecordsToInactive($getPostData){
		$this->getRecordsByMappingId($getPostData['id']);
		
		if(count($this->frameIds) >0){
			$data['status'] = 2;
			foreach($this->frameIds as $frameKey => $frameValue){
				$whereQry		= array('id'=>$frameValue);
				$this->commonModel->updateQry(MASTER_TERRITORY,$data,$whereQry);
			}
		}	
	}
	
	
    /**
	* @METHOD NAME 	: editTerritory()
	*
	* @DESC 		: TO EDIT THE TERRITORY DETAILS 
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editTerritory($getPostData)
    {
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $getPostData['id']);
        //$this->app_db->select(array('id','territory_name','mapping_id','status'));
        $this->app_db->select($rowData);
        $this->app_db->from(MASTER_TERRITORY);
        $this->app_db->where('id', $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        return $rs->result_array();
    }
	
	/**
	* @METHOD NAME 	: getTerritoryList()
	*
	* @DESC 		: TO EDIT THE TERRITORY DETAILS 
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getTerritoryList($getPostData = null)
    {
        $this->app_db->select(array('id','territory_name','mapping_id','status','posting_status','sap_id','sap_error'));
        $this->app_db->from(MASTER_TERRITORY);
        $this->app_db->where('is_deleted', '0');
 
 if($getPostData != null){

        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                if (!empty($fieldValue) || $fieldValue == "0") {
					if($fieldName=="territoryName") {
						$this->app_db->like('LCASE(territory_name)', strtolower($fieldValue));
					}else if($fieldName=="status"){
						$this->app_db->where(MASTER_TERRITORY.'.status', $fieldValue);
					}else if ($fieldName == "sapId") {
						$this->app_db->where(MASTER_TERRITORY.'.sap_id', $fieldValue);
					} else if ($fieldName == "postingStatus") {
						$this->app_db->where(MASTER_TERRITORY.'.posting_status', $fieldValue);
					} else if ($fieldName == "sapError") {
						$this->app_db->where(MASTER_TERRITORY.'.sap_error', $fieldValue);
					} else if ($fieldName == "mappingId") {
						$this->app_db->where(MASTER_TERRITORY.'.mapping_id', $fieldValue);
					} else if ($fieldName == "id") {
						$this->app_db->where(MASTER_TERRITORY.'.id', $fieldValue);
					}
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('MASTER_TERRITORY')['columns_list'],$fieldName);
				
			if(!empty($fieldName)){
				$this->app_db->order_by($fieldName, $sortOrder);
			}
			
        }else{
			$this->app_db->order_by(MASTER_TERRITORY.'.updated_on', 'desc');
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
       // $modelData['test'] = $getPostData;
        return $modelData;

    }else{

    	$rs = $this->app_db->get();
        return $rs->result_array();

    }

      
    }
}
?>
