<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Warehouse_model.php
* @Class  			 : Warehouse_model
* Model Name         : Warehouse_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 26 ARR 2020
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Warehouse_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'WAREHOUSE';
		$this->tableName 	= constant($this->tableNameStr);
    }
	
	
	/**
	* @METHOD NAME 	: saveWarehouse()
	*
	* @DESC 		: TO SAVE THE WAREHOUSE DETAILS
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveWarehouse($getPostData)
    {
		$chkWareHouseCode = 0;
		$chkWareHouseName = 0;
		
		// CHECK WAREHOUSE CODE ALREADY EXISTS 
		$whereExistsQry = array(
                              'LCASE(warehouse_code)' => strtolower($getPostData['warehouseCode']),
							);
		$chkWareHouseCode = $this->commonModel->isExists(WAREHOUSE,$whereExistsQry);
        
        if($getPostData['defaultWarehouse'] == 1){
            $this->resetDefaultWarehouse($getPostData);
        }
        
		// CHECK WAREHOUSE NAME ALREADY EXISTS
		$whereExistsQry = array(
                              'LCASE(warehouse_name)' => strtolower($getPostData['warehouseName']),
							);
		$chkWareHouseName = $this->commonModel->isExists(WAREHOUSE,$whereExistsQry);
	
		// CHECK THE WAREHOUSE INFOMATION 
		if($chkWareHouseCode > 0) {
			$modelOutput['flag'] = 2;
			return $modelOutput;
		}else if ($chkWareHouseName > 0){
			$modelOutput['flag'] = 3;
			return $modelOutput;
        } else {
            $rowData  				= bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);
			$rowData['branch_id'] 	= $this->currentbranchId;
            $insertId 				= $this->commonModel->insertQry($this->tableName, $rowData);
            $modelOutput['flag'] = 1;
            $modelOutput['insert_id'] = $insertId;
        }
        return $modelOutput;
    }
    
    
	/**
	* @METHOD NAME 	: updateWarehouse()
	*
	* @DESC 		: TO UPDATE THE WAREHOUSE
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateWarehouse($getPostData)
    {
		
		if($getPostData['defaultWarehouse'] == 1){
            $this->resetDefaultWarehouse($getPostData);
        }
		
		// CHECK THE WAREHOUSE CODE 
		$whereExistsQry = array(
                                 'LCASE(warehouse_code)' => strtolower($getPostData['warehouseCode']),
								'id!=' 					 => $getPostData['id'],
								);
        $chkWareHouseCode = $this->commonModel->isExists(WAREHOUSE,$whereExistsQry);
		
		// CHECK THE WAREHOUSE NAME
		$whereExistsQry = array(
                                 'LCASE(warehouse_name)' => strtolower($getPostData['warehouseName']),
								'id!=' 					 => $getPostData['id'],
								);
        $chkWareHouseName = $this->commonModel->isExists(WAREHOUSE,$whereExistsQry);
		
		
		// CHECK THE WAREHOUSE INFOMATION 
		if($chkWareHouseCode >0) {
			$modelOutput['flag'] = 2;
			return $modelOutput;
		}else if ($chkWareHouseName > 0){
			$modelOutput['flag'] = 3;
			return $modelOutput;
        } else {
			$whereQry 	= array('id'=>$getPostData['id']);			
			$rowData 	= bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
			$rowData['branch_id'] 	= $this->currentbranchId;
			$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);
            $modelOutput['flag'] = 1;
        }
        return $modelOutput;
    }
	
    
    /**
	* @METHOD NAME 	: updateWarehouse()
	*
	* @DESC 		: TO UPDATE THE WAREHOUSE
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function resetDefaultWarehouse($getPostData)
    {
        $whereQry 	= array('branch_id'=> $this->currentbranchId);	
        $rowData['default_warehouse'] 	= 0;
        $this->commonModel->updateQry($this->tableName, $rowData, $whereQry);
    }
	
    /**
	* @METHOD NAME 	: editWarehouse()
	*
	* @DESC 		: TO EDIT THE WAREHOUSE
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editWarehouse($getPostData)
    {
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $getPostData['id']);
		/*$this->app_db->select(array('id','warehouse_code','warehouse_name','bin_id','location_id','address_1',
		'address_2','branch_id','status'));
		*/
		$this->app_db->select($rowData);
        $this->app_db->from(WAREHOUSE);
        $this->app_db->where('id', $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        return $rs->result_array();
    }
    
   
    /**
	* @METHOD NAME 	: getWarehouseList()
	*
	* @DESC 		: TO GET THE WAREHOUSE LIST INFORMATION
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getWarehouseList($getPostData)
    {
        
        // SELECT 
        $this->app_db->select(array(
                                WAREHOUSE.'.id',
                                WAREHOUSE.'.warehouse_code',
                                WAREHOUSE.'.warehouse_name',
                                WAREHOUSE.'.bin_id',
                                WAREHOUSE.'.location_id',
                                WAREHOUSE.'.address_1',
                                WAREHOUSE.'.address_2',
                                WAREHOUSE.'.branch_id',
								WAREHOUSE.'.status',
								WAREHOUSE.'.default_warehouse',
								WAREHOUSE.'.posting_status',
								WAREHOUSE.'.sap_id',
								WAREHOUSE.'.sap_error',
								MASTER_LOCATION.'.location_no',
								MASTER_LOCATION.'.location_name',
								MASTER_STATE.'.state_name',							
								MASTER_COUNTRY.'.country_name',								
								MASTER_STATIC_DATA.'.name as statusName',
							));
        $this->app_db->from(WAREHOUSE);
		$this->app_db->join(MASTER_STATIC_DATA, MASTER_STATIC_DATA.'.master_id = '.WAREHOUSE.'.status', '');
		$this->app_db->join(MASTER_LOCATION, MASTER_LOCATION.'.id = '.WAREHOUSE.'.location_id', 'left');
		$this->app_db->join(MASTER_STATE, MASTER_STATE.'.id = '.MASTER_LOCATION.'.state_id', 'left');
		$this->app_db->join(MASTER_COUNTRY, MASTER_COUNTRY.'.id = '.MASTER_STATE.'.country_id', 'left');
        $this->app_db->where(MASTER_STATIC_DATA.'.type', 'COMMON_STATUS');
		
        $this->app_db->where(WAREHOUSE.'.is_deleted', '0');
        $this->app_db->where(WAREHOUSE.'.branch_id', $this->currentbranchId);
		
        
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                if ($fieldValue!="") {
					 if($fieldName=="warehouseName"){
						
						 $this->app_db->like('LCASE(CONCAT_WS(warehouse_code," ",warehouse_name ))', strtolower($fieldValue));
                    }else if($fieldName=="locationName"){
                        $this->app_db->like('LCASE(location_name)', strtolower($fieldValue));
                    }else if($fieldName=="stateName"){
                        $this->app_db->like('LCASE(state_name)', strtolower($fieldValue));
                    }else if($fieldName=="countryName"){
                        $this->app_db->like('LCASE(country_name)', strtolower($fieldValue));
                    }
					else if($fieldName=="location_id"){
                        $this->app_db->like('LCASE(location_id)', strtolower($fieldValue));
                    }else if($fieldName=="address_1"){
                        $this->app_db->like('LCASE(address_1)', strtolower($fieldValue));
                    }else if($fieldName=="address_2"){
                        $this->app_db->like('LCASE(address_2)', strtolower($fieldValue));
                    }else if($fieldName=="branch_id"){
                        $this->app_db->like('LCASE(branch_id)', strtolower($fieldValue));
					}else if($fieldName=="status"){
						$this->app_db->where(WAREHOUSE.'.status', $fieldValue);
					}else if ($fieldName == "sapId") {
						$this->app_db->where(WAREHOUSE.'.sap_id', $fieldValue);
					} else if ($fieldName == "postingStatus") {
						$this->app_db->where(WAREHOUSE.'.posting_status', $fieldValue);
					}
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
            $fieldName	   = getListingParams($this->config->item('WAREHOUSE')['columns_list'],$fieldName);
				
			if(!empty($fieldName)){
				$this->app_db->order_by($fieldName, $sortOrder);
			}
			
        }else{
			$this->app_db->order_by(WAREHOUSE.'.updated_on', 'desc');
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