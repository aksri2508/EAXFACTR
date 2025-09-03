<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_location_model.php
* @Class  			 : Master_location_model
* Model Name         : Master_location_model
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
class Master_location_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'MASTER_LOCATION';
		$this->tableName 	= constant($this->tableNameStr);
    }
	
	
	/**
	* @METHOD NAME 	: saveLocation()
	*
	* @DESC 		: TO SAVE THE LOCATION DETAILS
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveLocation($getPostData)
    {	
		// Adding Transaction Start
		$this->app_db->trans_start();

		
		// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
		$whereExistsQry = array(
							  'LCASE(location_name)' => strtolower($getPostData['locationName']),
							);

		$chkRecord 		= $this->commonModel->isExists(MASTER_LOCATION,$whereExistsQry);
		
		if($chkRecord == 0 ){
			$rowData		= bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);
			$insertId 		= $this->commonModel->insertQry($this->tableName, $rowData);
			
			if ($insertId > 0) {
					$whereQry					= array('id'=>$insertId);	
					$updateData['location_no']  = $insertId;
					$this->commonModel->updateQry($this->tableName,$updateData,$whereQry);

				$this->app_db->trans_complete(); // TRANSACTION COMPLETE
			}
			
			if ($this->app_db->trans_status() === FALSE) {
				$modelOutput['flag'] = 2; // Failure
			} else {
				$modelOutput['flag'] = 1; // Success
			}
		}else{
				$modelOutput['flag'] = 3; // Already exists
		}
		return $modelOutput;
    }
    
    
	/**
	* @METHOD NAME 	: updateLocation()
	*
	* @DESC 		: TO UPDATE THE LOCATION DETAILS 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateLocation($getPostData)
    {
		// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
		$whereExistsQry = array(
							  'LCASE(location_name)' => strtolower($getPostData['locationName']),
							  'id!='			  	 => $getPostData['id'],
							);

		$chkRecord 		= $this->commonModel->isExists(MASTER_LOCATION,$whereExistsQry);

		               
        if(0 == $chkRecord) {
			$whereQry = array('id'=>$getPostData['id']);
			$rowData  = bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
			$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);
            $modelOutput['flag'] = 1;
        } else {
            $modelOutput['flag'] = 3;
        }
        return $modelOutput;
    }
	
	
    /**
	* @METHOD NAME 	: editLocation()
	*
	* @DESC 		: TO EDIT THE LOCATION DETAILS 
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editLocation($getPostData)
    {
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $getPostData['id']);
       // $this->app_db->select(array('id','location_no','location_name','ship_to_name','ship_to_address','street_no','block','building','state_id','city','zip_code','status'));
        $this->app_db->select($rowData);
        $this->app_db->from(MASTER_LOCATION);
        $this->app_db->where('id', $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        return $rs->result_array();
    }
   
   
    /**
	* @METHOD NAME 	: getLocationList()
	*
	* @DESC 		: TO GET THE LOCATION LIST
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getLocationList($getPostData)
    {
        // SELECT 	
        $this->app_db->select(array(
								MASTER_LOCATION.'.id',
								MASTER_LOCATION.'.location_no',
								MASTER_LOCATION.'.location_name',
								MASTER_LOCATION.'.ship_to_name',
								MASTER_LOCATION.'.ship_to_address',
								MASTER_LOCATION.'.street_no',
								MASTER_LOCATION.'.block',
								MASTER_LOCATION.'.building',
								MASTER_LOCATION.'.state_id',
								MASTER_LOCATION.'.city',
								MASTER_LOCATION.'.zip_code',
								MASTER_LOCATION.'.status',
								MASTER_LOCATION.'.posting_status',
								MASTER_LOCATION.'.sap_id',
								MASTER_LOCATION.'.sap_error',
								//MASTER_STATE.'.id',
								MASTER_STATE.'.state_name',							
								MASTER_COUNTRY.'.country_name',
								MASTER_STATIC_DATA.'.name as statusName',
							));
        $this->app_db->from(MASTER_LOCATION);
		$this->app_db->join(MASTER_STATIC_DATA, MASTER_STATIC_DATA.'.master_id = '.MASTER_LOCATION.'.status', '');
		$this->app_db->join(MASTER_STATE, MASTER_STATE.'.id = '.MASTER_LOCATION.'.state_id', 'left');
		$this->app_db->join(MASTER_COUNTRY, MASTER_COUNTRY.'.id = '.MASTER_STATE.'.country_id', 'left');
        $this->app_db->where(MASTER_STATIC_DATA.'.type', 'COMMON_STATUS');
        $this->app_db->where(MASTER_LOCATION.'.is_deleted', '0');
		
        
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                if ($fieldValue!="") {
					if($fieldName=="locationNo") {
						$this->app_db->like('LCASE(location_no)', strtolower($fieldValue));
					}if($fieldName=="locationName") {
						$this->app_db->like('LCASE(location_name)', strtolower($fieldValue));
					}else if($fieldName=="shipToName") {
						$this->app_db->like('LCASE(ship_to_name)', strtolower($fieldValue));
					}else if($fieldName=="shipToAddress") {
						$this->app_db->like('LCASE(ship_to_address)', strtolower($fieldValue));
					}else if($fieldName=="streetNo") {
						$this->app_db->like('LCASE(street_no)', strtolower($fieldValue));
					}else if($fieldName=="block") {
						$this->app_db->like('LCASE(block)', strtolower($fieldValue));
					}else if($fieldName=="building") {
						$this->app_db->like('LCASE(building)', strtolower($fieldValue));
					}
					else if($fieldName=="stateId") {
						$this->app_db->where('state_id', $fieldValue);
					}
					else if($fieldName=="city") {
						$this->app_db->like('LCASE(city)', strtolower($fieldValue));
					}
					else if($fieldName=="zipCode") {
						$this->app_db->like('LCASE(zip_code)', strtolower($fieldValue));
					}
					else if($fieldName=="status"){
						$this->app_db->where(MASTER_LOCATION.'.status', $fieldValue);
					}else if ($fieldName == "sapId") {
						$this->app_db->where(MASTER_LOCATION.'.sap_id', $fieldValue);
					} else if ($fieldName == "postingStatus") {
						$this->app_db->where(MASTER_LOCATION.'.posting_status', $fieldValue);
					}
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('MASTER_LOCATION')['columns_list'],$fieldName);
				
			if(!empty($fieldName)){
				$this->app_db->order_by($fieldName, $sortOrder);
			}
			
        }else{
			$this->app_db->order_by(MASTER_LOCATION.'.updated_on', 'desc');
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