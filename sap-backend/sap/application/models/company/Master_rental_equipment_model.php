<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_rental_equipment_model.php
* @Class  			 : Master_rental_equipment_model
* Model Name         : Master_rental_equipment_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 15 MAY 2021
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Master_rental_equipment_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->tableNameStr 			= 'MASTER_RENTAL_EQUIPMENT';
		$this->tableName 				= constant($this->tableNameStr);
	}

	/**
	 * @METHOD NAME 	: saveRentalEquipment()
	 *
	 * @DESC 			: TO SAVE THE RENTAL EQUIPMENT DETAILS
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function saveRentalEquipment($getPostData)
	{
		$rowData = bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);

		// Adding Transaction Start
		$this->app_db->trans_start();

		$whereExistsQry = array(
			'LCASE(equipment_code)' => strtolower($getPostData['equipmentCode']),
		);
		$chkRecord = $this->commonModel->isExists(MASTER_RENTAL_EQUIPMENT, $whereExistsQry);

		if (0 == $chkRecord) {
			$insertId 				  = $this->commonModel->insertQry($this->tableName, $rowData);
			$this->app_db->trans_complete(); // TRANSACTION COMPLETE
			

			// Check the transaction status
			if ($this->app_db->trans_status() === FALSE) {
				$modelOutput['flag'] = 2; // Failure
			} else {
				$modelOutput['sId']	 = $insertId;
				$modelOutput['flag'] = 1; // Success
			}
			return $modelOutput;
		} else {
			$modelOutput['flag'] = 3;
		}
		return $modelOutput;
	}


	/**
	 * @METHOD NAME 	: updateRentaEquipment()
	 *
	 * @DESC 			: TO UPDATE THE RENTAL EQUIPMENT.
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function updateRentaEquipment($getPostData)
	{

		$rowData	 = bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);

		$whereExistsQry = array(
			'LCASE(equipment_code)' => strtolower($getPostData['equipmentCode']),
			'id!='				  => $getPostData['id'],
		);

		$totRows = $this->commonModel->isExists($this->tableName, $whereExistsQry);

		if (0 == $totRows) {

			// ADDING TRANSACTION START.
			$this->app_db->trans_start();

			$whereQry 	 = array('id' => $getPostData['id']);
			$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);

			$this->app_db->trans_complete(); // TRANSACTION COMPLETE.

			$modelOutput['flag'] = 1;
		} else {
			$modelOutput['flag'] = 3;
		}
		return $modelOutput;
	}


	/**
	 * @METHOD NAME 	: deleteRentalEquipment()
	 *
	 * @DESC 			: TO DELETE THE RENTAL EQUIPMENT.
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function deleteRentalEquipment($getPostData)
	{
		// DELETE IN RENTAL ITEM. 
		$whereQry  = array('id' => $getPostData['id']);
		$this->commonModel->deleteQry($this->tableName, $whereQry);

		$modelOutput['flag'] = 1; // Success
		return $modelOutput;
	}


	/**
	 * @METHOD NAME 	: editRentalEquipment()
	 *
	 * @DESC 			: TO EDIT THE RENTAL EQUIPMENT
	 * @RETURN VALUE 	: $rs array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function editRentalEquipment($getPostData)
	{
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $getPostData['id']);
		$this->app_db->select($rowData);
		$this->app_db->from($this->tableName);
		$this->app_db->where('id', $getPostData['id']);
		$this->app_db->where('is_deleted', '0');
		$rs = $this->app_db->get();
		return $rs->result_array();
	}


	/**
	 * @METHOD NAME 	: getRentlEquipmentList()
	 *
	 * @DESC 			: TO GET THE RENTAL EQUIPMENT LIST.
	 * @RETURN VALUE 	: $modelData array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getRentlEquipmentList($getPostData, $downloadFlag = '')
	{
		// SELECT 
		$query =	'SELECT * FROM
						(
							SELECT
							  a.id,
							  a.rental_item_id,
							  a.equipment_code,
							  a.equipment_name,
							  a.equipment_image,
							  a.unit_price,
							  a.mfr_serial_number,
							  a.serial_number,
							  a.ownership_id,
							  a.warehouse_id,

							  a.status,
							  a.rental_status,
							  a.remarks,
							  a.created_on,
							  a.created_by,
							  a.updated_on,
							  a.updated_by,
							  a.posting_status,
							  a.sap_id,
							  a.sap_error,
	
							/* Master STATIC DATA - EQUIPMENT STATUS */
							mes.equipment_status_name as status_name,

							/* Master STATIC DATA - RENTAL STATUS */
							mrst.name as rental_status_name,

							/* Master STATIC DATA */
							osn.name as ownership_name,

							/* Master STATIC DATA */
							wsn.warehouse_name  as warehouse_name,

							CONCAT(cep.first_name," ",cep.last_name) as created_by_name

							FROM ' . $this->tableName . ' as a 
				
							LEFT JOIN ' . MASTER_RENTAL_EQUIPMENT_STATUS . ' as mes
								ON mes.id = a.status

							LEFT JOIN ' . MASTER_STATIC_DATA . ' as mrst
								ON mrst.master_id = a.rental_status AND mrst.type = "RENTAL_STATUS"

							LEFT JOIN ' . MASTER_STATIC_DATA . ' as osn
								ON osn.master_id = a.ownership_id AND osn.type = "EQUIPMENT_OWNERSHIP"
							
							LEFT JOIN ' . WAREHOUSE . ' as wsn
								ON wsn.id = a.warehouse_id 	
							
							 LEFT JOIN ' . EMPLOYEE_PROFILE . ' as cep 
								ON cep.id = a.created_by
							
							AND a.is_deleted = 0
						)
					as a WHERE id!=0';


		// TABLE PROPERTIES AND SEARCH DATA MANUIPULATION.
		$tableProperties = $getPostData['tableProperties'];
		$filters         = $getPostData['search'];

		// SEARCH
		if (count($filters) > 0) {
			foreach ($filters as $key => $value) {
				$fieldName  = $key;
				$fieldValue = $value;
				if ($fieldValue!="") {
					if ($fieldName == "equipmentName") {
						$query .= ' AND LCASE(CONCAT(equipment_code," ",equipment_name)) REGEXP LCASE(replace("' . strtolower($fieldValue) . '"," ","|"))';
					} else if ($fieldName == "ownershipName") {
						$query .= ' AND LCASE(ownership_name) REGEXP LCASE(replace("' . strtolower($fieldValue) . '"," ","|"))';
					} else if ($fieldName == "warehouseName") {
						$query .= ' AND LCASE(warehouse_name) REGEXP LCASE(replace("' . strtolower($fieldValue) . '"," ","|"))';
					} else if ($fieldName == "equipmentStatusName") {
						$query .= ' AND LCASE(status_name) REGEXP LCASE(replace("' . strtolower($fieldValue) . '"," ","|"))';
					} else if ($fieldName == "rentalStatusName") {
						$query .= ' AND LCASE(rental_status_name) REGEXP LCASE(replace("' . strtolower($fieldValue) . '"," ","|"))';
					} else if ($fieldName == "status") {
						if ($fieldValue!="") {
							$query .= ' AND a.status = "' . $fieldValue . '"';
						}
					} else if ($fieldName == "sapId") {
						$query .= ' AND sap_id = "' . $fieldValue . '"';
					} else if ($fieldName == "postingStatus") {
						$query .= ' AND posting_status = "' . $fieldValue . '"';
					} else if ($fieldName == "createdByName") {
						$query .= ' AND LCASE(created_by_name) REGEXP LCASE(replace("' . strtolower($fieldValue) . '"," ","|"))';
					}
				}
			}
		}

		// $query .= '  group by id ';

		// ORDERING 
		if (isset($tableProperties['sortField'])) {

			$fieldName = $tableProperties['sortField'];
			$sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";

			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('MASTER_RENTAL_EQUIPMENT')['columns_list'], $fieldName);

			if (!empty($fieldName)) {
				$query .= ' ORDER BY ' . $fieldName . ' ' . $sortOrder;
			}
		} else {
			$query .= ' ORDER BY updated_on desc';
		}

		// CLONE DB QUERY TO GET THE TOTAL RESULT BEFORE PAGINATION.
		$rs = $this->app_db->query($query);
		$totalRecords = $rs->num_rows();


		// PAGINATION
		if (empty($downloadFlag)) {
			if (isset($tableProperties['first'])) {
				$offset = $tableProperties['first'];
				$limit  = $tableProperties['rows'];
			} else {
				$offset = 0;
				$limit  = $tableProperties['rows'];
			}
			$query .= ' LIMIT ' . $offset . ',' . $limit;
		}

		// GET RESULTS 		
		$searchResultSet = $this->app_db->query($query);
		$searchResultSet = $searchResultSet->result_array();

		// FRAME PHOTO URL 
		foreach ($searchResultSet as $key => $value) {
			$value['rentalEquipmentImgUrl'] 	= getFullImgUrl('rentalEquipmentPhoto', $value['equipment_image']);
			$searchResultSet[$key] 	= $value;
		}

		// MODEL DATA 
		$modelData['searchResults'] = $searchResultSet;
		$modelData['totalRecords']  = $totalRecords;
		return $modelData;
	}
}
