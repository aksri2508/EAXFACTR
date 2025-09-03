<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_vehicle_model.php
* @Class  			 : Master_vehicle_model
* Model Name         : Master_vehicle_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 07 JUNE 2024
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Master_vehicle_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr		= 'MASTER_VEHICLE';
		$this->tableName 		= constant($this->tableNameStr);
    }
	

	/**
	* @METHOD NAME 	: saveAllVehicle()
	*
	* @DESC 		: TO SAVE THE VEHICLE.
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveAllVehicle($getPostData)
    {

		 // Transaction Start
		 $this->app_db->trans_start();
		 foreach($getPostData['vehicleListArray'] as $vehicleData){
			$vehicleData['vendorBpId'] = $getPostData['vendorBpId'];
			$insertId =  $this->saveSingleVehicle($vehicleData);
		}

		if($insertId>0){
			$this->app_db->trans_complete(); // Transaction Complete
			$modelOutput['sId']	 = $insertId;
			$modelOutput['flag'] = 1;
		}else{
			$modelOutput['flag'] = 3;
		}

		return $modelOutput;
    }



	/**
	* @METHOD NAME 	: saveSingleVehicle()
	*
	* @DESC 		: TO SAVE THE VEHICLE.
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveSingleVehicle($vehicleData)
    {
		$rowData = bindConfigTableValues($this->tableNameStr, 'CREATE', $vehicleData);
		return $this->commonModel->insertQry($this->tableName, $rowData);
    }


		/**
	* @METHOD NAME 	: updateAllVehicle()
	*
	* @DESC 		: TO UPDATE THE VEHICLE.
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function updateAllVehicle($getPostData)
    {

		// Transaction Start
		$this->app_db->trans_start();

		// Delete records.
		if(isset($getPostData['deletedVehicleChildIds']) && count($getPostData['deletedVehicleChildIds'])>0){
			foreach($getPostData['deletedVehicleChildIds'] as $del_Ids){
				$this->nativeModel->deleteItemVehicles($del_Ids);
			}
		}

		foreach($getPostData['vehicleListArray'] as $vehicleData){

			$vehicleData['vendorBpId'] = $getPostData['vendorBpId'];

			if(isset($vehicleData['id']) && $vehicleData['id'] != ""){
				// $modelOutput =  $this->nativeModel->updateVehicle($vehicleData, $vehicleData['id']);
				$whereQry = array('id='	=> $vehicleData['id']);			
				$rowData = bindConfigTableValues($this->tableNameStr, 'UPDATE', $vehicleData);
				
				$id = $this->commonModel->updateQry($this->tableName, $rowData, $whereQry);

			}
			else {
				$id =  $this->saveSingleVehicle($vehicleData);
			}

		}

		$this->app_db->trans_complete(); // Transaction Complete

		$modelOutput['flag'] = 1;
		$modelOutput['insertId'] = $id;
		return $modelOutput;
    }


	/**
	* @METHOD NAME 	: editVehicle()
	*
	* @DESC 		: TO EDIT THE VEHICLE.
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editVehicle($getPostData)
    {
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $getPostData['id']);
        $this->app_db->select($rowData);
        $this->app_db->from($this->tableName);
        $this->app_db->where('vendor_bp_id', $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        return $rs->result_array();
    }
    
	
    /**
	* @METHOD NAME 	: getVehicleList()
	*
	* @DESC 		: TO GET THE VEHICLE LIST
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function getVehicleList($getPostData,$downloadFlag='')
    {
		/*
		$this->app_db->select('vendor_bp_id as id,partner_name as vendor_bp_name, partner_code as vendor_bp_code, COUNT(*) as vehicle_count');
		$this->app_db->from(MASTER_VEHICLE);
		$this->app_db->join(BUSINESS_PARTNER, BUSINESS_PARTNER.'.id = vendor_bp_id', 'left');
		$this->app_db->where(MASTER_VEHICLE.'.is_deleted',0);
		$this->app_db->group_by('vendor_bp_id');
		*/		
		$query = 'SELECT * FROM (SELECT
	 								a.vendor_bp_id as id,
	 								a.created_on,
	 								-- a.created_by,
	 								a.updated_on,
	 								a.updated_by,
	 								CONCAT(b.first_name," ",b.last_name) as created_by_name,
	 								CONCAT(c.first_name," ",c.last_name) as updated_by_name,

	 								  /* BUSINESS PARTNER */
	 								bp.partner_name as vendor_bp_name,
	 								bp.partner_code	as vendor_bp_code,
									COUNT(*) as vehicle_count

	 							  FROM '.MASTER_VEHICLE.' as a
	 							  	LEFT JOIN ' . BUSINESS_PARTNER . ' as bp
	 							  ON bp.id = a.vendor_bp_id
	 								LEFT JOIN '.EMPLOYEE_PROFILE.' as b
	 								  ON b.id = a.created_by
	 								LEFT JOIN '.EMPLOYEE_PROFILE.' as c
	 								  ON c.id = a.updated_by 
	 							  WHERE a.is_deleted = 0
								GROUP BY a.vendor_bp_id
	 							  ) as a
	 						WHERE id != 0';
		 
		 //TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
         $tableProperties = $getPostData['tableProperties'];
         $filters         = $getPostData['search'];
        
          //SEARCH
         if (count($filters) > 0) {
             foreach ($filters as $key => $value) {
                 $fieldName  = $key;
                 $fieldValue = $value;
                  if ($fieldValue!="") {
	 				 if($fieldName=="vendorBpName"){						
						$query .= ' AND LCASE(concat(vendor_bp_name," ",vendor_bp_code)) REGEXP LCASE(replace("' . strtolower($fieldValue) . '"," ","|"))';
	 				}					
                 }
             }
         }
        
         // ORDERING 
         if (isset($tableProperties['sortField'])) {
             $fieldName = $tableProperties['sortField'];
             $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";

	 		//GET SORT KEY DETAILS 
	 		$fieldName	   = getListingParams($this->config->item('MASTER_VEHICLE')['columns_list'],$fieldName);
				
	 		if(!empty($fieldName)){
	 			$query.= ' ORDER BY '.$fieldName.' '.$sortOrder;
	 		}
         }else{
	 		$query.= ' ORDER BY updated_on desc';
		 }

		 
          // CLONE DB QUERY TO GET THE TOTAL RESULT BEFORE PAGINATION
	 	$rs = $this->app_db->query($query);

	 	$totalRecords =$rs->num_rows();
        
        // PAGINATION
	 	if(empty($downloadFlag)){
	 		if (isset($tableProperties['first'])) {
	 			$offset = $tableProperties['first'];
	 			$limit  = $tableProperties['rows'];
	 		} else {
	 			$offset = 0;
	 			$limit  = $tableProperties['rows'];
	 		}
	 		$query.=' LIMIT '.$offset.','.$limit;
         }
		
         // GET RESULTS 		
         $searchResultSet = $this->app_db->query($query);
         $searchResultSet = $searchResultSet->result_array();
		
	 	 //MODEL DATA 
         $modelData['searchResults'] = $searchResultSet;
         $modelData['totalRecords']  = $totalRecords;
         return $modelData;
    }


		/**
	 * @METHOD NAME 	: deleteItemVehicles()
	 *
	 * @DESC 			: -
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function deleteItemVehicles($id)
	{
		$whereQry  = array('id' => $id);
		$this->commonModel->deleteQry($this->tableName, $whereQry);

		$modelOutput['flag'] = 1; // Success
		return $modelOutput;
	}
	
	
    /**
	* @METHOD NAME 	: getVehicleList()
	*
	* @DESC 		: TO GET THE VEHICLE LIST
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    // public function getVehicleList($getPostData,$downloadFlag='')
    // {
	// 	$query = 'SELECT * FROM (SELECT
	// 								a.id,
	// 								a.vehicle_code,
	// 								a.description,
	// 								a.vendor_bp_id,
	// 								a.created_on,
	// 								a.created_by,
	// 								a.updated_on,
	// 								a.updated_by,
	// 								a.sap_id,
	// 								a.posting_status,
	// 								a.sap_error,
	// 								a.is_deleted,
	// 								CONCAT(b.first_name," ",b.last_name) as created_by_name,
	// 								CONCAT(c.first_name," ",c.last_name) as updated_by_name,

	// 								  /* BUSINESS PARTNER */
	// 								bp.partner_name,
	// 								bp.partner_code	

	// 							  FROM '.MASTER_VEHICLE.' as a
	// 							  	LEFT JOIN ' . BUSINESS_PARTNER . ' as bp
	// 							  ON bp.id = a.vendor_bp_id
	// 								LEFT JOIN '.EMPLOYEE_PROFILE.' as b
	// 								  ON b.id = a.created_by
	// 								LEFT JOIN '.EMPLOYEE_PROFILE.' as c
	// 								  ON c.id = a.updated_by
	// 							  WHERE a.is_deleted = 0
	// 							  ) as a
	// 						WHERE id != 0';
		
		
    //     // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
    //     $tableProperties = $getPostData['tableProperties'];
    //     $filters         = $getPostData['search'];
        
    //     // SEARCH
    //     if (count($filters) > 0) {
    //         foreach ($filters as $key => $value) {
    //             $fieldName  = $key;
    //             $fieldValue = $value;
    //              if ($fieldValue!="") {
	// 				if($fieldName=="partnerTypeId") {
	// 					$query.=' AND partner_type_id = "'.$fieldValue.'"';						
	// 				}else if($fieldName=="partnerName"){						
	// 					$query.=' AND LCASE(CONCAT(partner_code," ",partner_name)) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
						
	// 				}
	// 				else if($fieldName=="vehicleCode"){
	// 					$query.=' AND LCASE(vehicle_code) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
	// 				}					
	// 				else if ($fieldName == "sapId") {
	// 					$query .= ' AND sap_id = "' . $fieldValue . '"';
	// 				}
	// 				else if ($fieldName == "postingStatus") {
	// 					$query .= ' AND posting_status = "' . $fieldValue . '"';
	// 				} 
	// 				else if($fieldName=="createdOn"){
	// 					$query.=' AND DATE(created_on) = "'.$fieldValue.'"';
	// 				}
	// 				else if($fieldName=="createdByName"){
	// 					$query.=' AND LCASE(created_by_name) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
	// 				}
	// 				else if($fieldName=="updatedByName"){
	// 					$query.=' AND LCASE(updated_by_name) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
	// 				}
    //             }
    //         }
    //     }
        
    //     // ORDERING 
    //     if (isset($tableProperties['sortField'])) {
    //         $fieldName = $tableProperties['sortField'];
    //         $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";

	// 		// GET SORT KEY DETAILS 
	// 		$fieldName	   = getListingParams($this->config->item('BUSINESS_PARTNER')['columns_list'],$fieldName);
				
	// 		if(!empty($fieldName)){
	// 			$query.= ' ORDER BY '.$fieldName.' '.$sortOrder;
	// 		}
			
    //     }else{
	// 		$query.= ' ORDER BY updated_on desc';
	// 	}
        
    //      // CLONE DB QUERY TO GET THE TOTAL RESULT BEFORE PAGINATION
	// 	$rs = $this->app_db->query($query);
	// 	$totalRecords =$rs->num_rows();
        
    //     // PAGINATION
	// 	if(empty($downloadFlag)){	
	// 		if (isset($tableProperties['first'])) {
	// 			$offset = $tableProperties['first'];
	// 			$limit  = $tableProperties['rows'];
	// 		} else {
	// 			$offset = 0;
	// 			$limit  = $tableProperties['rows'];
	// 		}
	// 		$query.=' LIMIT '.$offset.','.$limit;
    //     }
		
    //     // GET RESULTS 		
    //     $searchResultSet = $this->app_db->query($query);
    //     $searchResultSet = $searchResultSet->result_array();
		
	// 	// MODEL DATA 
    //     $modelData['searchResults'] = $searchResultSet;
    //     $modelData['totalRecords']  = $totalRecords;
    //     return $modelData;
    // }


}
