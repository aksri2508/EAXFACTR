<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Common_services_model.php
* @Class  			 : Common_services_model
* Model Name         : Common_services_model
* Description        :
* Module             : common
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : -
* @LastModifiedDate  : -
* @LastModifiedBy    : 
* @LastModifiedDesc  : Adapting the coding standard
* Features           : db functions for master data: multilanguage, gender, business model, user category,
* functional type, levels
*/
class Common_services_model extends CI_Model
{
	
//************************************** START COMMON MODULE*******************************/
    /**
	* @METHOD NAME 	: insertQry()
	*
	* @DESC 		: INSERT VALUES INTO TABLE
	* @RETURN VALUE : $insertId int
	* @PARAMETER 	: $tableName String , $data array
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function insertQry($tableName,$data){

//		echo "Table name is ".$tableName;
//		printr($data);
//		exit;

		if(!isset($data['created_by'])){
			$this->app_db->set('created_by',$this->currentUserId);
		}
		//$this->app_db->set('created_on',$this->currentDateTime);
		
		$this->app_db->set('created_on','NOW()',false);
		$this->app_db->set('updated_on','NOW()',false);
		$this->app_db->set('referer_created','WEB'); // DIRECT UPDATE OF REFERER CREATION 
		$this->app_db->db_debug = false;
		if($this->app_db->insert($tableName,$data))
		{	
			$insertId = $this->app_db->insert_id();
			return $insertId;

		}else{
			$error = $this->app_db->error();
			print_r($error);exit;
			bindConfigDbErrorMsg($error);
		}
	}

	
	/**
	* @METHOD NAME 	: updateQry()
	*
	* @DESC 		: UPDATE VALUES INTO TABLE
	* @RETURN VALUE : $affectedRows int
	* @PARAMETER 	: $tableName String , $data array , $whereQry array
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function updateQry($tableName,$data,$whereQry){
		
		if ($this->app_db->field_exists('posting_status', $tableName))
		{
			$this->app_db->set('posting_status',0); // For update make 0 
		}
	
			
		// CHECK ARRAY MANIPULATION 
		if(!is_array($whereQry) || count($whereQry)==0 ){
			echo json_encode(array(
				'status' 		=> 'FAILURE',
				'message' 		=> 'Something went wrong.!',
				"responseCode" 	=> 200
			));
			exit();
		}
		
		// CHECK ID IS EMPTY 
		if(isset($whereQry['id']) && empty($whereQry['id'])){
			echo json_encode(array(
				'status' 		=> 'FAILURE',
				'message' 		=> 'Something went wrong in ID Value.!',
				"responseCode" 	=> 200
			));
			exit();
		}
		
		$this->app_db->set('referer_updated','WEB'); // DIRECT UPDATE OF REFERER UPDATION  
		$this->app_db->set('updated_on','NOW()',false);
		$this->app_db->set('updated_by',$this->currentUserId);
		$this->app_db->update($tableName,$data,$whereQry);
		$affectedRows = $this->app_db->affected_rows();
		return $affectedRows;
	}
	
	
	/**
	* @METHOD NAME 	: deleteQry()
	*
	* @DESC 		: DELETE VALUES FROM TABLE
	* @RETURN VALUE : $affectedRows int
	* @PARAMETER 	: $tableName String , $whereQry array
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function deleteQry($tableName,$whereQry){
		$data = array();
		$this->app_db->set('is_deleted',1);
		//$this->app_db->set('updated_on',$this->currentDateTime);
		$this->app_db->set('updated_on','NOW()',false);
		$this->app_db->set('updated_by',$this->currentUserId);
		$this->app_db->update($tableName,$data,$whereQry);
		$affectedRows = $this->app_db->affected_rows();
		return $affectedRows;
	}

    /**
	* @METHOD NAME 	: isExists()
	*
	* @DESC 		: CHECK WHETHER DATA IS EXIST OR NOT
	* @RETURN VALUE : $totRows int
	* @PARAMETER 	: $tableName String , $whereQry array
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function isExists($tableName,$whereQry) {
				
		// CHECK WHEREQRY EXISTS 
		if(!is_array($whereQry) || count($whereQry)==0 ){
			echo json_encode(array(
				'status' 		=> 'FAILURE',
				'message' 		=> 'Something went wrong.!',
				"responseCode" 	=> 200
			));
			exit();
		}
	
		// CHECK IF ID EXISTS AND IF ID IS NULL RETURN ERROR 
		if(isset($whereQry['id!=']) && empty($whereQry['id!='])){
			echo json_encode(array(
				'status' 		=> 'FAILURE',
				'message' 		=> 'Something went wrong in ID Value.!',
				"responseCode" 	=> 200
			));
			exit();
		}
		
		$this->app_db->select(array('id'));
		$this->app_db->from($tableName);
		$this->app_db->where('is_deleted',0);
		foreach($whereQry as $key => $value){
			$this->app_db->where($key,$value);
		}
		$rs			= $this->app_db->get();
		$totRows    = $rs->num_rows();
		return $totRows;
	}

/************************************** END OF COMMON SERVICE MODEL ********************************/
/************************************** AUTO SUGGESTION LIST ***************************************/
	/**
	* @METHOD NAME 	: getModuleScreenMappingDetails()
	*
	* @DESC 		: TO GET THE MODULE SCREEN MAPPING DETAILS 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getModuleScreenMappingDetails($whereFields)
	{
		$this->app_db->select(array(
									'id',
									'screen_name',
									'document_type_id',
									'enable_udf',
									'enable_document_numbering',
									'enable_approval_process',
									'enable_notification'
									)
							);

		$this->app_db->from(MASTER_MODULE_SCREEN_MAPPING);
		foreach($whereFields as $whereKey => $whereValue){
			if($whereKey == 'document_type_id' && $whereValue == '!=0' ){
				$this->app_db->where('document_type_id!=', '0');
			}else {
				$this->app_db->where($whereKey, $whereValue);
			}
		}
		$this->app_db->where('is_deleted', '0');
		$this->app_db->order_by('screen_name', 'asc');
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getPriorityAutoList()
	*
	* @DESC 		: TO GET THE MASTER PRIORITY LIST
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getPriorityAutoList($getServicesList)
	{
		$this->app_db->select(array('id','priority_name','posting_status','sap_id','sap_error'));
		$this->app_db->from(MASTER_PRIORITY);
		
		if($getServicesList['category']==1){	
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			$this->app_db->where('is_deleted', '0');
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}			
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getItemAutoList()
	*
	* @DESC 		: TO GET THE ITEM AUTO LIST
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getItemAutoList($getServicesList)
	{
		$this->app_db->select(array(
									MASTER_ITEM.'.id',
									MASTER_ITEM.'.item_code',
									MASTER_ITEM.'.item_name',
									//MASTER_ITEM.'.unit_price',
									MASTER_ITEM.'.item_weight',
									MASTER_ITEM.'.uom_id',
									MASTER_ITEM.'.foreign_name',
									MASTER_ITEM.'.hsn_id',
									MASTER_ITEM.'.manufacturer_id',
									MASTER_ITEM.'.item_transaction_type',
									MASTER_ITEM.'.last_sales_price',
									MASTER_ITEM.'.last_purchase_price',
									MASTER_ITEM.'.sales_open_count',
									MASTER_ITEM.'.purchase_open_count',
									MASTER_ITEM.'.stock',
									MASTER_ITEM.'.posting_status',
									MASTER_ITEM.'.sap_id',
									MASTER_ITEM.'.sap_error',
									MASTER_ITEM_PRICE_LIST.'.unit_price',
									'CONCAT('.MASTER_MANUFACTURER.'.manufacturer_code," ",
									'.MASTER_MANUFACTURER.'.manufacturer_name) AS manufacturer_name',
									MASTER_UOM.'.uom_name'));
		
		$this->app_db->from(MASTER_ITEM);
		$this->app_db->join(MASTER_UOM, MASTER_UOM.'.id ='.MASTER_ITEM.'.uom_id','left');
		$this->app_db->join(MASTER_MANUFACTURER, MASTER_MANUFACTURER.'.id ='.MASTER_ITEM.'.manufacturer_id','left');
		$this->app_db->join(MASTER_ITEM_PRICE_LIST, MASTER_ITEM_PRICE_LIST.'.id ='.MASTER_ITEM.'.last_price_list_id','left');
		
		if(isset($getServicesList['typeId']) && !empty($getServicesList['typeId'])){ 	// ISSET 
			
			$transactionType = $getServicesList['typeId'];
			
			$this->app_db->where(MASTER_ITEM.'.item_transaction_type REGEXP','LCASE(replace("'.$transactionType.'"," ","|"))',false);
		
		}
		
		if(isset($getServicesList['manufacturerId']) && !empty($getServicesList['manufacturerId'])){
			$this->app_db->where(MASTER_ITEM.'.manufacturer_id', $getServicesList['manufacturerId']);
			$this->app_db->where(MASTER_ITEM.'.is_deleted', '0');
			$this->app_db->where(MASTER_ITEM.'.status', '1'); 		// ACTIVE ITEMS ONLY 
		}else if($getServicesList['category']==1){	
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			if($flag==0){ 
				if($fieldValue==''){
					return [];
				}
				$this->app_db->like('LCASE(concat('.MASTER_ITEM.'.item_code,
											  '.MASTER_ITEM.'.item_name))',
								strtolower($fieldValue));
			}
			$this->app_db->where(MASTER_ITEM.'.is_deleted', '0');
			$this->app_db->where(MASTER_ITEM.'.status', '1'); 		// ACTIVE ITEMS ONLY 
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where(MASTER_ITEM.'.id', $id);
			if($delFlag==1){
				$this->app_db->where(MASTER_ITEM.'.is_deleted', '0');
			}			
		}
	    $rs = $this->app_db->get();
		$result =  $rs->result_array();
		
		// RESULT DETAILS 
		foreach($result as $resultKey => $resultValue){
			//$result[$resultKey]['itemWarehouseList'] = [];
			$result[$resultKey]['itemWarehouseList'] = $this->getItemWarehouseDetails($resultValue['id']);
		}	
		return $result;
	}
	
	
	/**
	* @METHOD NAME 	: getRentalItemAutoList()
	*
	* @DESC 		: TO GET THE RENTAL ITEM AUTO LIST
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getRentalItemAutoList($getServicesList)
	{
		$this->app_db->select(array(
									MASTER_RENTAL_ITEM.'.id',
									MASTER_RENTAL_ITEM.'.rental_item_code',
									MASTER_RENTAL_ITEM.'.rental_item_name',
									MASTER_RENTAL_ITEM.'.foreign_name',
									MASTER_RENTAL_ITEM.'.stock',
									MASTER_RENTAL_ITEM.'.uom_id',
									MASTER_RENTAL_ITEM.'.hsn_id',
									MASTER_RENTAL_ITEM.'.rental_item_image',
									MASTER_RENTAL_ITEM.'.status',
									MASTER_RENTAL_ITEM.'.posting_status',
									MASTER_RENTAL_ITEM.'.sap_id',
									MASTER_RENTAL_ITEM.'.sap_error',
									MASTER_UOM.'.uom_name'));
		
		$this->app_db->from(MASTER_RENTAL_ITEM);
		$this->app_db->join(MASTER_UOM, MASTER_UOM.'.id ='.MASTER_RENTAL_ITEM.'.uom_id','left');
		
		if($getServicesList['category']==1){	
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			if($flag==0){ 
				if($fieldValue==''){
					return [];
				}
				$this->app_db->like('LCASE(concat('.MASTER_RENTAL_ITEM.'.rental_item_code,
											  '.MASTER_RENTAL_ITEM.'.rental_item_name))',
								strtolower($fieldValue));
			}
			$this->app_db->where(MASTER_RENTAL_ITEM.'.is_deleted', '0');
			$this->app_db->where(MASTER_RENTAL_ITEM.'.status', '1'); 		// ACTIVE ITEMS ONLY 
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where(MASTER_RENTAL_ITEM.'.id', $id);
			if($delFlag==1){
				$this->app_db->where(MASTER_RENTAL_ITEM.'.is_deleted', '0');
			}			
		}
	    $rs = $this->app_db->get();
		$result =  $rs->result_array();

		// RESULT DETAILS FOR RENTAL MODULE 
		foreach($result as $resultKey => $resultValue){
			$passData['rentalItemId'] = $resultValue['id'];
			$result[$resultKey]['rentalEquipmentList'] = $this->getEquipmentDetailsByRentalItemId($passData);
		}	
		return $result;
	}
	
	
	/**
	* @METHOD NAME 	: getRentalEquipmentAutoList()
	*
	* @DESC 		: TO GET THE RENTAL equipment AUTO LIST
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getRentalEquipmentAutoList($getServicesList)
	{
		
		$this->app_db->select(array(
									MASTER_RENTAL_EQUIPMENT.'.id',
									MASTER_RENTAL_EQUIPMENT.'.rental_item_id',
									MASTER_RENTAL_EQUIPMENT.'.document_id',
									MASTER_RENTAL_EQUIPMENT.'.document_type_id',
									MASTER_RENTAL_EQUIPMENT.'.equipment_code',
									MASTER_RENTAL_EQUIPMENT.'.equipment_name',
									MASTER_RENTAL_EQUIPMENT.'.equipment_image',
									MASTER_RENTAL_EQUIPMENT.'.status',
									MASTER_RENTAL_EQUIPMENT.'.rental_status',
									MASTER_RENTAL_EQUIPMENT.'.posting_status',
									MASTER_RENTAL_EQUIPMENT.'.sap_id',
									MASTER_RENTAL_EQUIPMENT.'.sap_error'
									));
		
		$this->app_db->from(MASTER_RENTAL_EQUIPMENT);
		
		if($getServicesList['category']==1){	
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			if($flag==0){ 
				if($fieldValue==''){
					return [];
				}
				$this->app_db->like('LCASE(concat('.MASTER_RENTAL_EQUIPMENT.'.equipment_code,
											  '.MASTER_RENTAL_EQUIPMENT.'.equipment_name))',
								strtolower($fieldValue));
			}
			$this->app_db->where(MASTER_RENTAL_EQUIPMENT.'.is_deleted', '0');
			
			
			if(isset($getServicesList['rentalStatus'])){
				$rentalStatus = $getServicesList['rentalStatus'];
				$this->app_db->where_in(MASTER_RENTAL_EQUIPMENT.'.rental_status', $rentalStatus);
			}
			
			$this->app_db->where(MASTER_RENTAL_EQUIPMENT.'.status', '1'); 		// ACTIVE ITEMS ONLY 
			
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where(MASTER_RENTAL_EQUIPMENT.'.id', $id);
			if($delFlag==1){
				$this->app_db->where(MASTER_RENTAL_EQUIPMENT.'.is_deleted', '0');
			}			
		}
	    $rs = $this->app_db->get();
		$result =  $rs->result_array();
		
		return $result;
	}
	
	
	/**
	* @METHOD NAME 	: getItemWarehouseDetails()
	*
	* @DESC 		: TO GET THE ITEM WAREHOUSE BASED UPON THE ITEM ID 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getItemWarehouseDetails($itemId)
	{
		
		$this->app_db->select(array(
									ITEM_WAREHOUSE.'.id',
									ITEM_WAREHOUSE.'.item_id',
									ITEM_WAREHOUSE.'.warehouse_id',
									ITEM_WAREHOUSE.'.bin_id',
									ITEM_WAREHOUSE.'.status',
									));
		
		$this->app_db->from(ITEM_WAREHOUSE);
		$this->app_db->where(ITEM_WAREHOUSE.'.item_id', $itemId);
		$this->app_db->where(ITEM_WAREHOUSE.'.status', 1);
		$rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getPriceListAutoList()
	*
	* @DESC 		: TO GET THE PRICE LIST AUTO LIST 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getPriceListAutoList($getServicesList)
	{
		$this->app_db->select(array('id','price_list_name','posting_status','sap_id','sap_error'));
		$this->app_db->from(MASTER_PRICE_LIST);
		
		if($getServicesList['category']==1){	
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			$this->app_db->where('is_deleted', '0');
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}			
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getDocumentNumberingAutoList()
	*
	* @DESC 		: TO GET THE DOCUMENT NUMBERING LIST 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getDocumentNumberingAutoList($getServicesList)
	{
		$this->app_db->select(array('id','document_numbering_name','next_number','is_system_config',
		'document_numbering_type','posting_status','sap_id','sap_error'));
		$this->app_db->from(MASTER_DOCUMENT_NUMBERING);
		
		if($getServicesList['category']==1){	
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			$this->app_db->where('is_deleted', '0');
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}			
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getCurrencyAutoList()
	*
	* @DESC 		: TO GET THE CURRENCY AUTO LIST
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getCurrencyAutoList($getServicesList)
	{

		$this->app_db->select(array('id','currency_name','code','posting_status','sap_id','sap_error'));
		$this->app_db->from(MASTER_CURRENCY);
		
		if($getServicesList['category']==1){	
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			$this->app_db->where('is_deleted', '0');
			$this->app_db->where(MASTER_CURRENCY.'.status', '1'); 		// ACTIVE ITEMS ONLY 
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}			
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	/**
	* @METHOD NAME 	: getLevelofInterestAutoList()
	*
	* @DESC 		: TO GET THE MASTER LEVEL OF INTEREST LIST
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getLevelofInterestAutoList($getServicesList)
	{
		$this->app_db->select(array('id','interest_name','posting_status','sap_id','sap_error'));
		$this->app_db->from(MASTER_LEVEL_OF_INTEREST);		
		
		if($getServicesList['category']==1){	
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			$this->app_db->where('is_deleted', '0');
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}			
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	

	/**
	* @METHOD NAME 	: getMasterActivityAutoList()
	*
	* @DESC 		: TO GET THE MASTER ACTIVITY LIST
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getMasterActivityAutoList($getServicesList)
	{
		$this->app_db->select(array('id','activity_name','posting_status','sap_id','sap_error'));
		$this->app_db->from(MASTER_ACTIVITY);
		
		if($getServicesList['category']==1){			
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			$this->app_db->where('is_deleted', '0');
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}			
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getBusinessPartnerAutoList()
	*
	* @DESC 		: TO GET THE BUSINESS PARTNER AUTO SUGGESTION LIST
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getBusinessPartnerAutoList($getServicesList)
	{
		$this->app_db->select(array(
								BUSINESS_PARTNER.'.id',
								BUSINESS_PARTNER.'.partner_code',
								BUSINESS_PARTNER.'.currency_id',
								BUSINESS_PARTNER.'.credit_limit',
								BUSINESS_PARTNER.'.account_balance',
								BUSINESS_PARTNER.'.partner_name',
								BUSINESS_PARTNER.'.payment_terms_id',
								BUSINESS_PARTNER.'.payment_method_id',
								BUSINESS_PARTNER.'.price_list_id',
								BUSINESS_PARTNER.'.posting_status',
								BUSINESS_PARTNER.'.sap_id',
								BUSINESS_PARTNER.'.sap_error',
								MASTER_CURRENCY.'.currency_name'));

		$this->app_db->from(BUSINESS_PARTNER);
		$this->app_db->join(MASTER_CURRENCY, BUSINESS_PARTNER.'.currency_id ='.MASTER_CURRENCY.'.id');
		
		
		// CHECK TYPE ID 
		if(isset($getServicesList['typeId']) && is_array($getServicesList['typeId'])){
			$typeId	 = $getServicesList['typeId'];
			$this->app_db->where_in(BUSINESS_PARTNER.'.partner_type_id',$typeId); // 1 -> Customer,2 -> Vendor , 3 -> lead

			// BASED UPON EMPLOYEE TYPE ADD THE WHERE IN CONDTION FOR DEALER 
			if($this->currentEmployeeType== 'dealer'){
				$this->app_db->where_in(BUSINESS_PARTNER.'.id',explode(",",$this->dealerBusinessPartnerId));
			}
		}
		/*
		else if(isset($getServicesList['typeId']) && !empty($getServicesList['typeId'])){
			$typeId = $getServicesList['typeId'];
			$this->app_db->where(BUSINESS_PARTNER.'.partner_type_id',$typeId); // 1 -> Customer,2 -> Vendor , 3 -> lead
		}
		*/

		
		if($getServicesList['category']==1){
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			
			if($flag==0){
				$this->app_db->like('LCASE(concat('.BUSINESS_PARTNER.'.partner_code,
											  '.BUSINESS_PARTNER.'.partner_name))',
								strtolower($fieldValue));
								
			}
			$this->app_db->where(BUSINESS_PARTNER.'.is_deleted', '0');
			$this->app_db->where(BUSINESS_PARTNER.'.status', 1); // ACTIVE 
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where(BUSINESS_PARTNER.'.id', $id);
			if($delFlag==1){
				$this->app_db->where(BUSINESS_PARTNER.'.is_deleted', '0');
			}			
		}
		$rs = $this->app_db->get();	
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getBusinessPartnerContactsAutoList()
	*
	* @DESC 		: TO GET THE BUSINESS PARTNER CONTACTS AUTO SUGGESTION LIST
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getBusinessPartnerContactsAutoList($getServicesList)
	{
		$this->app_db->select(array(
								BP_CONTACTS.'.id',
								BP_CONTACTS.'.business_partner_id',
								BP_CONTACTS.'.contact_type_id',
								BP_CONTACTS.'.contact_email_id',
								BP_CONTACTS.'.contact_name',
								BP_CONTACTS.'.posting_status',
								BP_CONTACTS.'.sap_id',
								BP_CONTACTS.'.sap_error',
								'CONCAT('.BP_CONTACTS.'.primary_country_code," ",
								'.BP_CONTACTS.'.primary_contact_no) AS contact_number',
								));

		$this->app_db->from(BP_CONTACTS);
		
		if(isset($getServicesList['sapId']) && !empty($getServicesList['sapId'])){
			$this->app_db->where(BP_CONTACTS.'.sap_id', $getServicesList['sapId']);
		}else if($getServicesList['category']==1){ // - SEARCH MUST PASS BUSINESS PARTNER ID 			
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			
			$this->app_db->where(BP_CONTACTS.'.business_partner_id', 
							$getServicesList['businessPartnerId']);
							
			if($flag==0){
				$this->app_db->like('LCASE(concat('.BP_CONTACTS.'.contact_email_id,
											  '.BP_CONTACTS.'.contact_name))',
								strtolower($fieldValue));
			}
			$this->app_db->where(BP_CONTACTS.'.is_deleted', '0');
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where(BP_CONTACTS.'.id', $id);
			if($delFlag==1){
				$this->app_db->where(BP_CONTACTS.'.is_deleted', '0');
			}
		}
		$rs = $this->app_db->get();	
		
		$businessPartnerContactsList  = $rs->result_array();
		
		
		// CHECK COUNT RECORDS 
		if(count($businessPartnerContactsList)>0){
			$passType['type'] 	= 'BP_CONTACT_TYPE';
			$contactTypeList   	= $this->getMasterStaticDataAutoList($passType, 2);
			
			foreach($businessPartnerContactsList as $contactKey => $contactValue){
				$contactTypeId					= array_search($contactValue['contact_type_id'], array_column($contactTypeList, 'id'));						
				$contactTypeName 				= $contactTypeList[$contactTypeId]['name'];
				$businessPartnerContactsList[$contactKey]['contact_type_name'] = $contactTypeName;
			}
			
		}
		return $businessPartnerContactsList;    
	}
	
	
	/**
	* @METHOD NAME 	: getBusinessPartnerAddressAutoList()
	*
	* @DESC 		: TO GET THE BUSINESS PARTNER ADDRESS AUTO LIST 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getBusinessPartnerAddressAutoList($getServicesList)
	{
	    $this->app_db->select(array('id','business_partner_id','address_type_id','tax_code','address','state_id','city','zipcode','default_address','posting_status','sap_id','sap_error'));
		$this->app_db->from(BP_ADDRESS);
		
		if($getServicesList['category']==1){
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			
			$this->app_db->where(BP_ADDRESS.'.business_partner_id',$getServicesList['businessPartnerId']);
		
			if($flag==0){																				//$this->app_db->like('LCASE(CONCAT('.EMPLOYEE_PROFILE.'.first_name,'.EMPLOYEE_PROFILE.'.last_name))', strtolower($fieldValue));
			}
			$this->app_db->where('is_deleted', '0');			
			//$this->app_db->where('status', '1');
			
		}else if($getServicesList['category']==2){ // FOR EDIT AND OTHER OPERATIONS 
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}			
		}
	    $rs = $this->app_db->get();
				
		$addressDetails = $rs->result_array();
		
		if(count($addressDetails) > 0){ // ADDRESS DETAILS COUNT INFORMATION
			
			$passData['category'] = 2;
			$passData['delFlag']  = 0;
				
			foreach($addressDetails as $addressKey => $addressValue){
				$passData['id'] 	  = $addressValue['state_id'];
				$stateDetails 		  = $this->getStateAutoList($passData);
				$stateName 			  = '';
				$countryName		  = '';
												
				if(count($stateDetails)>0){
					$stateName	 = $stateDetails[0]['state_name'];
					$countryName = $stateDetails[0]['country_name'];
				}
				$addressDetails[$addressKey]['stateName'] = $stateName;
				$addressDetails[$addressKey]['countryName'] = $countryName;
			}
		}
	    return $addressDetails;
	}
	
	
	/**
	* @METHOD NAME 	: getRentalEquipmentStatusAutoList()
	*
	* @DESC 		: TO GET THE RENTAL EQUIPMENT STATUS 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getRentalEquipmentStatusAutoList($getServicesList)
	{
		$this->app_db->select(array('id','equipment_status_name','posting_status','sap_id','sap_error'));
		$this->app_db->from(MASTER_RENTAL_EQUIPMENT_STATUS);
		
		if($getServicesList['category']==1){	
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			$this->app_db->where('is_deleted', '0');
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}			
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getRentalEquipmentCategoryAutoList()
	*
	* @DESC 		: TO GET THE RENTAL EQUIPMENT CATEGORY LIST  
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getRentalEquipmentCategoryAutoList($getServicesList)
	{
		$this->app_db->select(array('id','category_name','posting_status','sap_id','sap_error'));
		$this->app_db->from(MASTER_RENTAL_EQUIPMENT_CATEGORY);
		
		if($getServicesList['category']==1){	
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			$this->app_db->where('is_deleted', '0');
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}			
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getEmployeeAutoList()
	*
	* @DESC 		: TO GET THE EMPLOYEE AUTO SUGGESTION LIST
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getEmployeeAutoList($getServicesList)
	{
	    $this->app_db->select(array('id','emp_code','employee_type_id','designation_id','territory_id',
									'email_id','posting_status','sap_id','sap_error',
									'CONCAT('.EMPLOYEE_PROFILE.'.first_name," ",'.EMPLOYEE_PROFILE.'.last_name) as employee_name')
							);
		$this->app_db->from(EMPLOYEE_PROFILE);	
		
		$this->app_db->where(EMPLOYEE_PROFILE.'.branch_id REGEXP','LCASE(replace("'.$this->currentbranchId.'"," ","|"))',false);
		
		
		if($getServicesList['category']==1){
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
		
			if($flag==0){		
				$this->app_db->like('LCASE(CONCAT('.EMPLOYEE_PROFILE.'.first_name,'.EMPLOYEE_PROFILE.'.last_name))', strtolower($fieldValue));
			}
			
			// TYPE BASED AUTOSUGGESTION
			if(isset($getServicesList['typeId'])){
				$type = $getServicesList['typeId'];
				if($type==1){
					$this->app_db->where(EMPLOYEE_PROFILE.'.is_user', '0');
				}else if($type==2){
					$this->app_db->where(EMPLOYEE_PROFILE.'.is_user', '1');
				}	
			}
			
			$this->app_db->where('is_deleted', '0');
			
			$this->app_db->where('status', '1'); // Show only active employees
			
		}else if($getServicesList['category']==2){ // FOR EDIT AND OTHER OPERATIONS 
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}			
		}
		
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getAccessControlNameAutoList()
	*
	* @DESC 		: Get the access control name list 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getAccessControlNameAutoList($getServicesList)
	{
		$this->app_db->select(array('id','access_control_name','status','is_system_config'));
		$this->app_db->from(ACCESS_CONTROL);
		
		if($getServicesList['category']==1){	
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			$this->app_db->where('is_deleted', '0');
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}			
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	

	/**
	* @METHOD NAME 	: getCreatedByDetails()
	*
	* @DESC 		: TO GET THE LOGGED IN USER INFO
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getCreatedByDetails($getServicesList)
	{
	    $this->app_db->select(array('id','posting_status','sap_id','sap_error',
								'CONCAT('.EMPLOYEE_PROFILE.'.first_name," ",'.EMPLOYEE_PROFILE.'.last_name) as employee_name')
							);
		$this->app_db->from(EMPLOYEE_PROFILE);	
		
		$this->app_db->where(EMPLOYEE_PROFILE.'.branch_id REGEXP','LCASE(replace("'.$this->currentbranchId.'"," ","|"))',false);
		
		
		if($getServicesList['category']==1){
			// No Operation needed.

		}else if($getServicesList['category']==2){ // FOR EDIT AND OTHER OPERATIONS 
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}			
		}
		
		
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	/**
	* @METHOD NAME 	: getReportingManagerIds()
	*
	* @DESC 		: TO GET THE REPORTING MANAGER IDS 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getReportingManagerIds(){
		// GET REPORTING MANAGER IDS 
		$this->app_db->distinct();
		$this->app_db->select('id');
		$this->app_db->from(EMPLOYEE_PROFILE);
		$this->app_db->where('is_deleted', 0);
		$rs = $this->app_db->get();
	    $result  = $rs->result_array();
		
		$reportingManagerId = array();
		if(count($result)>0){
			foreach($result as $key => $value){
				$reportingManagerId[]  = $value['id'];
			}
		}
		return $reportingManagerId;
	}
	
	
	/**
	* @METHOD NAME 	: checkReportingManager()
	*
	* @DESC 		: TO GET THE REPORTING MANAGER IDS 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function checkReportingManager($id){
		// GET REPORTING MANAGER IDS 
		$this->app_db->select('id');
		$this->app_db->from(EMPLOYEE_PROFILE);
		$this->app_db->where('reporting_manager_id', $id);
		$this->app_db->where('is_deleted', 0);
		$rs = $this->app_db->get();
	    if($rs->num_rows() > 0 ){
			return true; 
		}else{
			return false;
		}
	}
	
	
	
	/**
	* @METHOD NAME 	: getTeamListByHead()
	*
	* @DESC 		: TO 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getTeamListByHead($id){
		$this->app_db->select(SMT_TEAM.'.id',SMT_TEAM.'.team_name',SMT_TEAM.'.team_head_id');
		$this->app_db->from(SMT_TEAM);
		$this->app_db->where(SMT_TEAM.'.team_head_id', $id);
		$this->app_db->where(SMT_TEAM.'.is_deleted', 0);
		return $rs = $this->app_db->get();
	}
	
	
	/**
	* @METHOD NAME 	: getTeamMembersByTeamId()
	*
	* @DESC 		: TO 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getTeamMembersByTeamId($id){
		$this->app_db->select(array(SMT_TEAM_MEMBERS.'.id',
							  SMT_TEAM_MEMBERS.'.team_id',
							  SMT_TEAM_MEMBERS.'.emp_id'));
		$this->app_db->from(SMT_TEAM_MEMBERS);
		$this->app_db->where(SMT_TEAM_MEMBERS.'.team_id', $id);
		$this->app_db->where(SMT_TEAM_MEMBERS.'.is_deleted', 0);
		$this->app_db->where(SMT_TEAM_MEMBERS.'.branch_id', $this->currentbranchId);
		$rs = $this->app_db->get();
		return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getGroupUsersByRM()
	*
	* @DESC 		: FUNCTION TO GET THE GROUP USERS 
	* @RETURN VALUE : boolean
	* @PARAMETER 	: $getPostData array
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getGroupUsersByRM($id){
		
	
		$defaultCols = array(
							EMPLOYEE_PROFILE.'.id',
							EMPLOYEE_PROFILE.'.reporting_manager_id'
						);
		
		$this->app_db->select($defaultCols);
        $this->app_db->from(EMPLOYEE_PROFILE);
		$this->app_db->where(EMPLOYEE_PROFILE.'.reporting_manager_id',$id);
		$this->app_db->where(EMPLOYEE_PROFILE.'.is_deleted','0');
		//$this->app_db->where(EMPLOYEE_PROFILE.'.branch_id',$this->currentbranchId);
		
		$this->app_db->where(EMPLOYEE_PROFILE.'.branch_id REGEXP','LCASE(replace("'.$this->currentbranchId.'",
		",","|"))',false);
		
		
		$rs=$this->app_db->get();
		return $rs->result_array();
	}
	
	/**
	* @METHOD NAME 	: getTeamHeadAutoList()
	*
	* @DESC 		: TO GET THE TEAM HEAD AUTO LIST 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getTeamHeadAutoList($getServicesList)
	{
		$reportingManagerIds = $this->getReportingManagerIds();
		$chkReportingManager = $this->checkReportingManager($this->currentUserId);
		
	    $this->app_db->select(array('id','emp_code','posting_status','sap_id','sap_error',
							'CONCAT('.EMPLOYEE_PROFILE.'.first_name," ",'.EMPLOYEE_PROFILE.'.last_name) as employee_name'));
		
		$this->app_db->from(EMPLOYEE_PROFILE);	
				
		$this->app_db->where(EMPLOYEE_PROFILE.'.branch_id REGEXP','LCASE(replace("'.$this->currentbranchId.'"," ","|"))',false);
		
		// LIST ALL REPORTING MANAGERS 
		if($this->currentAccessControlId==1){
			$this->app_db->where_in(EMPLOYEE_PROFILE.'.id',$reportingManagerIds);			
		}else{
			if($chkReportingManager){ // CHECK CURRENT USER AS REPORTING MANAGER 
				$this->app_db->where('id', $this->currentUserId);
			}else{
				return "";
			}
		}

		if($getServicesList['category']==1){
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
		
			if($flag==0){		
				$this->app_db->like('LCASE(CONCAT('.EMPLOYEE_PROFILE.'.first_name,'.EMPLOYEE_PROFILE.'.last_name))', strtolower($fieldValue));
			}
			$this->app_db->where('is_deleted', '0');
			$this->app_db->where('status', '1'); // SHOW ONLY ACTIVE RECORDS
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}			
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getEmployeeTeamDetails()
	*
	* @DESC 		: TO GET THE EMPLOYEE TEAM DETAILS 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getEmployeeTeamDetails($empId){
		$this->app_db->select(array(SMT_TEAM_MEMBERS.'.id',
							  SMT_TEAM_MEMBERS.'.team_id',
							  SMT_TEAM_MEMBERS.'.emp_id'));
		$this->app_db->from(SMT_TEAM_MEMBERS);
		$this->app_db->where(SMT_TEAM_MEMBERS.'.emp_id', $empId);
		$this->app_db->where(SMT_TEAM_MEMBERS.'.is_deleted', 0);
		$this->app_db->where(SMT_TEAM_MEMBERS.'.status', 1);
		$this->app_db->where(SMT_TEAM_MEMBERS.'.branch_id', $this->currentbranchId);
		$rs = $this->app_db->get();
		return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getGateHistory()
	*
	* @DESC 		: TO GET THE GATE HISTORY.
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function getGateHistory($transportId)
    {
		$this->app_db->select('a.id,a.status,a.created_on as date_time,msd.name as status_name');
		$this->app_db->from(GATEPASS_HISTORY. " a");
		$this->app_db->join(MASTER_STATIC_DATA." msd", 'msd.master_id = a.status AND msd.type = "GATEPASS_STATUS"', 'left');
		$this->app_db->where('a.transport_id',$transportId);
		$this->app_db->where('a.is_deleted',0);

		$rs = $this->app_db->get();
		$resultData =  $rs->result_array();	
		return $resultData;
    }
	
	
	/**
	* @METHOD NAME 	: getTeamNameAutoList()
	*
	* @DESC 		: TO GET THE TEAM NAME LISTING 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getTeamNameAutoList($getServicesList)
	{
		$chkReportingManager = $this->checkReportingManager($this->currentUserId);
		$getTeamListRs		 = $this->getTeamListByHead($this->currentUserId);
		
	    $this->app_db->select(array('id','team_name','posting_status','sap_id','sap_error'));		
		$this->app_db->from(SMT_TEAM);
		$this->app_db->where(SMT_TEAM.'.branch_id',$this->currentbranchId);		
		
		
		if($this->currentAccessControlId!=1){
			if($chkReportingManager){ // CHECK CURRENT USER AS REPORTING MANAGER 
				if($getTeamListRs->num_rows()>0){
					$teamArray 	= $getTeamListRs->result_array();
					$teamIds  	=  array_column($teamArray, 'id');
					$this->app_db->where_in(SMT_TEAM.'.id',$teamIds);
				}else {
					return "";
				}
			}else{
				return "";
			}
		}
		
		if($getServicesList['category']==1){
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
		
			if($flag==0){		
				$this->app_db->like('LCASE('.SMT_TEAM.'.team_name)', strtolower($fieldValue));
			}
			$this->app_db->where('is_deleted', '0');
			$this->app_db->where('status', '1');
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}			
		}
		$rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getTerritoryAutoList()
	*
	* @DESC 		: 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getTerritoryAutoList($getServicesList)
	{
		$this->app_db->select(array('id','territory_name','mapping_id','status','posting_status','sap_id','sap_error'));
		$this->app_db->from(MASTER_TERRITORY);
			
		if($getServicesList['category']==1){
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
				
			if($flag==0){			
				$this->app_db->like('LCASE(territory_name)', strtolower($fieldValue));
			}		
			$this->app_db->where('is_deleted', '0');
			$this->app_db->where('status', '1');
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}			
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getManufacturerAutoList()
	*
	* @DESC 		: 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getManufacturerAutoList($getServicesList)
	{
		$this->app_db->select(array('id','manufacturer_code','manufacturer_name','status','posting_status','sap_id','sap_error'));
		$this->app_db->from(MASTER_MANUFACTURER);
			
		if($getServicesList['category']==1){
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
				
			if($flag==0){			
				$this->app_db->like('LCASE(concat('.MASTER_MANUFACTURER.'.manufacturer_code,
											  '.MASTER_MANUFACTURER.'.manufacturer_name))',
								strtolower($fieldValue));
			}
			$this->app_db->where('is_deleted', '0');
			$this->app_db->where('status', '1');
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}			
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getHsnAutoList()
	*
	* @DESC 		: 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getHsnAutoList($getServicesList)
	{
		$this->app_db->select(array('id','hsn_code','chapter','heading','sub_heading','status','posting_status','sap_id','sap_error'));
		$this->app_db->from(MASTER_HSN);
			
		if($getServicesList['category']==1){
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
				
			if($flag==0){			
				$this->app_db->like('LCASE(concat('.MASTER_HSN.'.hsn_code))',
								strtolower($fieldValue));
			}
			$this->app_db->where('is_deleted', '0');
			$this->app_db->where('status', '1');
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}			
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getTermsAndConditionAutoList()
	*
	* @DESC 		: 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getTermsAndConditionAutoList($getServicesList)
	{
		$this->app_db->select(array('id','heading','body_content','posting_status','sap_id','sap_error'));
		$this->app_db->from(MASTER_TERMS_AND_CONDITION);
			
		if($getServicesList['category']==1){
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
				
			if($flag==0){			
				$this->app_db->like('LCASE(heading)', strtolower($fieldValue));
			}		
			$this->app_db->where('is_deleted', '0');
			$this->app_db->where('status', '1');
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}			
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getRentalWorklogAutoList()
	*
	* @DESC 		: 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getRentalWorklogAutoList($getServicesList)
	{
		$this->app_db->select(array(
										RENTAL_WORKLOG.'.id',
										RENTAL_WORKLOG.'.document_number',
										RENTAL_WORKLOG.'.document_numbering_id',
										RENTAL_WORKLOG.'.total_billable_hours',
										RENTAL_WORKLOG.'.rental_equipment_id',
										RENTAL_WORKLOG.'.rental_item_id',
										RENTAL_WORKLOG.'.start_date',
										RENTAL_WORKLOG.'.end_date',
										RENTAL_WORKLOG.'.posting_status',
										RENTAL_WORKLOG.'.sap_id',
										RENTAL_WORKLOG.'.sap_error',
										MASTER_RENTAL_EQUIPMENT.'.equipment_code',
										MASTER_RENTAL_EQUIPMENT.'.equipment_name'
									));
		$this->app_db->from(RENTAL_WORKLOG);
		$this->app_db->join(MASTER_RENTAL_EQUIPMENT, MASTER_RENTAL_EQUIPMENT.'.id ='.RENTAL_WORKLOG.'.rental_equipment_id','');

		if(isset($getServicesList['businessPartnerId'])){
			$this->app_db->where(RENTAL_WORKLOG.'.customer_bp_id',$getServicesList['businessPartnerId']);
		}
		
		if(isset($getServicesList['rentalItemId'])){
			$this->app_db->where(RENTAL_WORKLOG.'.rental_item_id',$getServicesList['rentalItemId']);
		}
		
		if(isset($getServicesList['rentalEquipmentId'])){
			$this->app_db->where(RENTAL_WORKLOG.'.rental_equipment_id',$getServicesList['rental_equipment_id']);
		}
		
		if($getServicesList['category']==1){
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
				
			if($flag==0){			
				$this->app_db->like('LCASE('.RENTAL_WORKLOG.'.document_number)', strtolower($fieldValue));
			}		
			$this->app_db->where(RENTAL_WORKLOG.'.is_deleted', '0');
			$this->app_db->where(RENTAL_WORKLOG.'.status', '1');
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where(RENTAL_WORKLOG.'.id', $id);
			if($delFlag==1){
				$this->app_db->where(RENTAL_WORKLOG.'.is_deleted', '0');
			}			
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getInspectionTemplateAutoList()
	*
	* @DESC 		: 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getInspectionTemplateAutoList($getServicesList)
	{
		$this->app_db->select(array('id','template_name','template_details','posting_status','sap_id','sap_error'));
		$this->app_db->from(MASTER_INSPECTION_TEMPLATE);
			
		if($getServicesList['category']==1){
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
				
			if($flag==0){			
				$this->app_db->like('LCASE(template_name)', strtolower($fieldValue));
			}		
			$this->app_db->where('is_deleted', '0');
			//$this->app_db->where('status', '1');
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}			
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	

	/**
	* @METHOD NAME 	: getApprovalStagesAutoList()
	*
	* @DESC 		: 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getApprovalStagesAutoList($getServicesList)
	{
		$this->app_db->select(array('id','stage_name','stage_description','no_of_approvals','no_of_rejections','authorizer_id','status'));
		$this->app_db->from(MASTER_APPROVAL_STAGES);
			
		if($getServicesList['category']==1){
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
				
			if($flag==0){			
				$this->app_db->like('LCASE(stage_name)', strtolower($fieldValue));
			}		
			$this->app_db->where('is_deleted', '0');
			//$this->app_db->where('status', '1');
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}			
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getVehicleAutoList()
	*
	* @DESC 		: TO GET THE VEHICLE AUTO LISTING 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getVehicleAutoList($getServicesList)
	{
		$this->app_db->select(array('id','vehicle_code','description','vendor_bp_id'));
		$this->app_db->from(MASTER_VEHICLE);
			
		if($getServicesList['category']==1){
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
				
			if($flag==0){
				$this->app_db->like('LCASE(concat('.MASTER_VEHICLE.'.vehicle_code,'.MASTER_VEHICLE.'.description))',
									strtolower($fieldValue));
			}
			$this->app_db->where('is_deleted', '0');
			//$this->app_db->where('status', '1');
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}			
		}
	    $rs = $this->app_db->get();
	    $result =  $rs->result_array();
		
		// RESULT DETAILS 
		foreach($result as $resultKey => $resultValue){
			$passData = array('delFlag'=>0 , 'id' => $resultValue['vendor_bp_id'] , 'category' => 2);
			$result[$resultKey]['vendorBpInfo'] = $this->getBusinessPartnerAutoList($passData);
		}	
		return $result;
	}

	
	/**
	* @METHOD NAME 	: getIndustryAutoList()
	*
	* @DESC 		: 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getIndustryAutoList($getServicesList)
	{
		$this->app_db->select(array('id','industry_name','posting_status','sap_id','sap_error'));
		$this->app_db->from(MASTER_INDUSTRY);
			
		if($getServicesList['category']==1){
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
				
			if($flag==0){			
				$this->app_db->like('LCASE(industry_name)', strtolower($fieldValue));
			}		
			$this->app_db->where('is_deleted', '0');
			$this->app_db->where('status', '1');
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}			
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	

	/**
	* @METHOD NAME 	: getInformationSourceAutoList()
	*
	* @DESC 		: 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getInformationSourceAutoList($getServicesList)
	{
		$this->app_db->select(array('id','source_code','source_description','posting_status','sap_id','sap_error'));
		$this->app_db->from(MASTER_INFORMATION_SOURCE);	
			
		if($getServicesList['category']==1){
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];

			if($flag==0){				
					$this->app_db->like('LCASE(source_code)', strtolower($fieldValue));
			}		
			$this->app_db->where('is_deleted', '0');
			$this->app_db->where('status', '1');
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}			
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getPaymentMethodsAutoList()
	*
	* @DESC 		: 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getPaymentMethodsAutoList($getServicesList)
	{
		$this->app_db->select(array('id','payment_method_code','payment_method_name','posting_status','sap_id','sap_error'));
		$this->app_db->from(MASTER_PAYMENT_METHODS);	
			
		if($getServicesList['category']==1){
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];

			if($flag==0){				
					$this->app_db->like('LCASE(payment_method_code)', strtolower($fieldValue));
			}		
			$this->app_db->where('is_deleted', '0');
			//$this->app_db->where('status', '1');
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}			
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getPaymentTermsAutoList()
	*
	* @DESC 		: 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getPaymentTermsAutoList($getServicesList)
	{
		$this->app_db->select(array('id','payment_term_code','payment_term_name','payment_duration','posting_status','sap_id','sap_error'));
		$this->app_db->from(MASTER_PAYMENT_TERMS);	
			
		if($getServicesList['category']==1){
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];

			if($flag==0){				
					$this->app_db->like('LCASE(payment_term_code)', strtolower($fieldValue));
			}		
			$this->app_db->where('is_deleted', '0');
			//$this->app_db->where('status', '1');
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}			
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getStageAutoList()
	*
	* @DESC 		: 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getStageAutoList($getServicesList)
	{
	    $this->app_db->select(array('id','stage_name','stage_number','close_percentage','posting_status','sap_id','sap_error'));
		$this->app_db->from(MASTER_STAGE);		
		
		if($getServicesList['category']==1){
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];

			if($flag==0){	
				$this->app_db->like('LCASE(stage_name)', strtolower($fieldValue));
			}
			$this->app_db->where('status', '1');
			$this->app_db->where('is_deleted', '0');
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}			
		}
		 $this->app_db->order_by('stage_number', 'asc');
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getActivityAutoList()
	*
	* @DESC 		: 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getActivityAutoList($getServicesList)
	{
		$this->app_db->select(array('id','activity_no','posting_status','sap_id','sap_error'));
		$this->app_db->from(ACTIVITY);
		
		if($getServicesList['category']==1){
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
				
			if($flag==0){	
				$this->app_db->like('LCASE(activity_no)', strtolower($fieldValue));
			}
			$this->app_db->where('is_deleted', '0');
			$this->app_db->where('status', '1');
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}			
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	
	/**
	* @METHOD NAME 	: getCompetitorAutoList()
	*
	* @DESC 		: 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getCompetitorAutoList($getServicesList)
	{
		
	    $this->app_db->select(array('id','competitor_name','threat_level_id','posting_status','sap_id','sap_error'));
		$this->app_db->from(MASTER_COMPETITOR);	


		if($getServicesList['category']==1){
	
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			
			if($flag==0){	
				$this->app_db->like('LCASE(competitor_name)', strtolower($fieldValue));
			}
			$this->app_db->where('is_deleted', '0');
			$this->app_db->where('status', '1');
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}			
		}
		$rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getReasonAutoList()
	*
	* @DESC 		: 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getReasonAutoList($getServicesList)
	{
	
		$this->app_db->select(array('id','reason_description','posting_status','sap_id','sap_error'));
		$this->app_db->from(MASTER_REASON);	
		
		if($getServicesList['category']==1){
			
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			
			if($flag==0){	
				$this->app_db->like('LCASE(reason_description)', strtolower($fieldValue));
			}
			$this->app_db->where('is_deleted', '0');
			$this->app_db->where('status', '1');
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}			
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getCountryAutoList()
	*
	* @DESC 		: TO GET THE COUNTRY AUTO LISTING 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getCountryAutoList($getServicesList)
	{

		$this->app_db->select(array('id','country_name','country_code','iso_code','posting_status','sap_id','sap_error'));
		$this->app_db->from(MASTER_COUNTRY);	
		
		if($getServicesList['category']==1){
			
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			
			if($flag==0){	
				$this->app_db->like('LCASE(country_name)', strtolower($fieldValue));
			}
			
			if(isset($getServicesList['sapId'])){
				$this->app_db->where(MASTER_COUNTRY.'.sap_id', $getServicesList['sapId']);
			} else if (isset($getServicesList['postingStatus'])) {
				$this->app_db->where(MASTER_COUNTRY.'.posting_status', $getServicesList['postingStatus']);
			}

			$this->app_db->where('is_deleted', '0');
			$this->app_db->where('status', '1');
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}			
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getDimensionAutoList()
	*
	* @DESC 		: TO GET THE DIMENSION AUTO LISTING 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getDimensionAutoList($getServicesList)
	{
		$this->app_db->select(array('id','dimension_name','dimension_description','status','posting_status','sap_id','sap_error'));
		$this->app_db->from(MASTER_DIMENSION);	
		
		if($getServicesList['category']==1){
			
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			
			if($flag==0){	
				$this->app_db->like('LCASE(dimension_name)', strtolower($fieldValue));
			}
			$this->app_db->where('is_deleted', '0');
			$this->app_db->where('status', '1');
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}			
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getStateAutoList()
	*
	* @DESC 		: TO GET THE STATE AUTO LISTING 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getStateAutoList($getServicesList)
	{
		$this->app_db->select(array(
									MASTER_STATE.'.id',
									MASTER_STATE.'.state_name',
									MASTER_STATE.'.country_id',
									MASTER_STATE.'.posting_status',
									MASTER_STATE.'.sap_id',
									MASTER_STATE.'.sap_error',
									MASTER_COUNTRY.'.country_name'
									)
								);
		$this->app_db->from(MASTER_STATE);
		$this->app_db->join(MASTER_COUNTRY, MASTER_COUNTRY.'.id ='.MASTER_STATE.'.country_id','LEFT');
		
		if($getServicesList['category']==1){
			
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			
			if($flag==0){	
				$this->app_db->like('LCASE(state_name)', strtolower($fieldValue));
			}

			if(isset($getServicesList['sapId'])){
				$this->app_db->where(MASTER_STATE.'.sap_id', $getServicesList['sapId']);
			} else if (isset($getServicesList['postingStatus'])) {
				$this->app_db->where(MASTER_STATE.'.posting_status', $getServicesList['postingStatus']);
			}

			$this->app_db->where(MASTER_STATE.'.is_deleted', '0');
			$this->app_db->where(MASTER_STATE.'.status', '1');
		}else if($getServicesList['category']==2){
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where(MASTER_STATE.'.id', $id);
			if($delFlag==1){
				$this->app_db->where(MASTER_STATE.'.is_deleted', '0');
			}			
		}
		
		if(isset($getServicesList['countryId'])){
			$this->app_db->where('country_id', $getServicesList['countryId']);
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getDesignationAutoList()
	*
	* @DESC 		: 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getDesignationAutoList($getServicesList)
	{
		$this->app_db->select(array('id','designation_name'));
		$this->app_db->from(MASTER_DESIGNATION);	
		
		if($getServicesList['category']==1){
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
				    
			if($flag==0){	
				$this->app_db->like('LCASE(designaiton_name)', strtolower($fieldValue));
			}
			
			$this->app_db->where('status', '1');
		}
		else if($getServicesList['category']==2)
		{
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
					
		}
		$rs = $this->app_db->get();
	    return $rs->result_array();
	}
	

	/**
	* @METHOD NAME 	: getUomAutoList()
	*
	* @DESC 		: 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getUomAutoList($getServicesList)
	{
		$this->app_db->select(array('id','uom_name','posting_status','sap_id','sap_error'));
		$this->app_db->from(MASTER_UOM);	
		
		if($getServicesList['category']==1){
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
				    
			if($flag==0){	
				$this->app_db->like('LCASE(uom_name)', strtolower($fieldValue));
			}
			$this->app_db->where('is_deleted', '0');
			$this->app_db->where('status', '1');
		}
		else if($getServicesList['category']==2)
		{
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}		
		}
		$rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getItemGroupAutoList()
	*
	* @DESC 		: 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getItemGroupAutoList($getServicesList)
	{
	    $this->app_db->select(array('id','group_code','group_name','posting_status','sap_id','sap_error'));
		$this->app_db->from(MASTER_ITEM_GROUP);	
		
		if($getServicesList['category']==1)
		{
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			
			if($flag==0){	
				$this->app_db->like('LCASE(group_code)', strtolower($fieldValue));
			}
			$this->app_db->where('is_deleted', '0');
			$this->app_db->where('status', '1');
		}
		else if($getServicesList['category']==2)
		{
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}		
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	
	/**
	* @METHOD NAME 	: getMasterStaticDataAutoList()
	*
	* @DESC 		: 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getMasterStaticDataAutoList($getServicesList, $dataListType=1)
	{	
		/*
		printr($getServicesList);
		echo "----->";
		printr($dataListType);
		echo "<------------>";
		*/
	
		$type =  $getServicesList['type'];
		$this->app_db->select(array('master_id as id','name','type','posting_status','sap_id','sap_error'));
		$this->app_db->from(MASTER_STATIC_DATA);	
		$this->app_db->where('type', $type);

		if($dataListType==1)
		{
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 	  	=  $getServicesList['allData'];

			if($flag==0){	
				$this->app_db->like('LCASE(name)', strtolower($fieldValue));
			}			
			$this->app_db->where('is_deleted', '0');
		}
		else if($dataListType==2)
		{
			if(isset($getServicesList['id'])){
				$masterId = $getServicesList['id'];
				$this->app_db->where('master_id',$masterId);
			}
		}
		$rs = $this->app_db->get();		
		return $rs->result_array();		
	}
	
	
	/**
	* @METHOD NAME 	: getThreadAutoList()
	*
	* @DESC 		: The function especially designed for opportunity screen to handle competitor name (thread list together)
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getThreadAutoList()
	{	
		$type =  'THREAD_LEVEL';
		$this->app_db->select(array('master_id as id','name','type'));
		$this->app_db->from(MASTER_STATIC_DATA);	
		$this->app_db->where('type', $type);
		$this->app_db->where('is_deleted', '0');
		$rs = $this->app_db->get();		
		return $rs->result_array();		
	}
	
	
	/**
	* @METHOD NAME 	: getTaxAutoList()
	*
	* @DESC 		: 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getTaxAutoList($getServicesList)
	{
	    $this->app_db->select(array('id','tax_code','tax_description','attribute_id','posting_status','sap_id','sap_error'));
		$this->app_db->from(MASTER_TAX);	
		
		if($getServicesList['category']==1)
		{
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			
			
			if($flag==0){			
				$this->app_db->like('LCASE(tax_code)', strtolower($fieldValue));
			}		
			$this->app_db->where('is_deleted', '0');
			$this->app_db->where('status', '1');
		}
		else if($getServicesList['category']==2)
		{
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}		
		}
	    $rs 		= $this->app_db->get();
	    $taxResult  =  $rs->result_array();
		

		// GET ATTRIBUTE DETAILS 
		foreach($taxResult as $taxKey => $taxValue){
			
			$attributePercentage = 0;
			$attributeIds 		 = explode(",",$taxValue['attribute_id']);
			
			foreach($attributeIds as $attributeKey => $attributeValue){
				$this->app_db->select(array('id','attribute_code','attribute_description','attribute_percentage'));
				$this->app_db->from(MASTER_TAX_ATTRIBUTE);	
				$this->app_db->where('id', $attributeValue);
				$attributeResult = $this->app_db->get();
				$attributeData 	 = $attributeResult->result_array();
				if(count($attributeData)>0){
					$attributePercentage+= $attributeData[0]['attribute_percentage'];
				}
			}
			$taxResult[$taxKey]['attributePercentage'] = $attributePercentage;
		}
		return $taxResult;		
	}
	
	
	/**
	* @METHOD NAME 	: getTaxAttributeAutoList()
	*
	* @DESC 		: 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getTaxAttributeAutoList($getServicesList)
	{
	    $this->app_db->select(array('id','attribute_code','attribute_description','attribute_percentage','posting_status','sap_id','sap_error'));
		$this->app_db->from(MASTER_TAX_ATTRIBUTE);	
		
		if($getServicesList['category']==1)
		{
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			
			if($flag==0){			
				$this->app_db->like('LCASE(attribute_code)', strtolower($fieldValue));
			}
			$this->app_db->where('status', '1');
			$this->app_db->where('is_deleted', '0');
		}
		else if($getServicesList['category']==2)
		{
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}		
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getTeamAutoList()
	*
	* @DESC 		: 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getTeamAutoList($getServicesList)
	{
	    $this->app_db->select(array('id','team_name','team_head_id','remarks','posting_status','sap_id','sap_error'));
		$this->app_db->from(SMT_TEAM);
		
		if($getServicesList['category']==1)
		{
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			
			
			if($flag==0){			
				$this->app_db->like('LCASE(team_name)', strtolower($fieldValue));
			}	
			$this->app_db->where('status', '1');			
			$this->app_db->where('is_deleted', '0');
		}
		else if($getServicesList['category']==2)
		{
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}		
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getOpportunityTypeAutoList()
	*
	* @DESC 		: 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getOpportunityTypeAutoList($getServicesList)
	{
	    $this->app_db->select(array('id','type_description','posting_status','sap_id','sap_error'));
		$this->app_db->from(MASTER_OPPORTUNITY_TYPE);
		
		if($getServicesList['category']==1)
		{
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			
			
			if($flag==0){			
				$this->app_db->like('LCASE(type_description)', strtolower($fieldValue));
			}	
			$this->app_db->where('status', '1');			
			$this->app_db->where('is_deleted', '0');
		}
		else if($getServicesList['category']==2)
		{
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}		
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
		
	
	/**
	* @METHOD NAME 	: getdistributionRulesAutoList()
	*
	* @DESC 		: 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getdistributionRulesAutoList($getServicesList)
	{
		// EMP FLAG 
		if(isset($getServicesList['empFlag']) && $getServicesList['empFlag']==1){ // 
			$empDetails  			= $this->getProfileInformation($this->currentUserId);
			$empDistributionRulesId = $empDetails['profileInfo'][0]['distribution_rules_id'];
			
			if(empty($empDistributionRulesId)){
				return [];
			}			
		}
		
	    $this->app_db->select(array(
									MASTER_DISTRIBUTION_RULES.'.id',
									MASTER_DISTRIBUTION_RULES.'.distribution_code',
									MASTER_DISTRIBUTION_RULES.'.distribution_name',
									MASTER_DISTRIBUTION_RULES.'.posting_status',
									MASTER_DISTRIBUTION_RULES.'.sap_id',
									MASTER_DISTRIBUTION_RULES.'.sap_error',
									MASTER_DIMENSION.'.id as dimension_id',
									MASTER_DIMENSION.'.dimension_description'
									));
									
		$this->app_db->from(MASTER_DISTRIBUTION_RULES);
		
		$this->app_db->join(MASTER_DIMENSION, MASTER_DIMENSION.'.id ='.MASTER_DISTRIBUTION_RULES.'.dimension_id','');
		
		// EMPLOYEE FLAG 
		if(isset($getServicesList['empFlag']) && $getServicesList['empFlag']==1 && 
		($this->currentAccessControlId!=1)){
			$this->app_db->where_in(MASTER_DISTRIBUTION_RULES.'.id', $empDistributionRulesId,false);			
		}	
		
		if($getServicesList['category']==1)
		{
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			
			if($flag==0){			
				$this->app_db->like('LCASE('.MASTER_DISTRIBUTION_RULES.'.center_name)', strtolower($fieldValue));
			}	
			$this->app_db->where(MASTER_DISTRIBUTION_RULES.'.status', '1');			
			$this->app_db->where(MASTER_DISTRIBUTION_RULES.'.is_deleted', '0');
		}
		else if($getServicesList['category']==2)
		{
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where(MASTER_DISTRIBUTION_RULES.'.id', $id);
			if($delFlag==1){
				$this->app_db->where(MASTER_DISTRIBUTION_RULES.'.is_deleted', '0');
			}		
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
		
	
	/**
	* @METHOD NAME 	: getReportingManagerList()
	*
	* @DESC 		: 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getReportingManagerList($getServicesList)
	{
	   	// SELECT 
        $this->app_db->select(array(
								EMPLOYEE_PROFILE.'.id',
								EMPLOYEE_PROFILE.'.emp_code',
								EMPLOYEE_PROFILE.'.gender_id',
								EMPLOYEE_PROFILE.'.first_name',
								EMPLOYEE_PROFILE.'.last_name',
								EMPLOYEE_PROFILE.'.email_id',
								EMPLOYEE_PROFILE.'.primary_country_code',
								EMPLOYEE_PROFILE.'.primary_contact_no',
								EMPLOYEE_PROFILE.'.status',
								EMPLOYEE_PROFILE.'.branch_id',
								EMPLOYEE_PROFILE.'.profile_img',
								EMPLOYEE_PROFILE.'.posting_status',
								EMPLOYEE_PROFILE.'.sap_id',		
								EMPLOYEE_PROFILE.'.sap_error'
							));
							
        $this->app_db->from(EMPLOYEE_PROFILE);
		$this->app_db->where('is_deleted', '0');
		$this->app_db->where('reporting_manager_id!=', '0');
		
		if($getServicesList['category']==1)
		{
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			
			if($flag==0){			
				$this->app_db->like('LCASE(emp_code)', strtolower($fieldValue));
			}		
			$this->app_db->where('status', '1');
		}
		else if($getServicesList['category']==2)
		{
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}		
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getWarehouseAutoList()
	*
	* @DESC 		: TO GET THE WAREHOUSE AUTO LIST 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getWarehouseAutoList($getServicesList)
	{
	    $this->app_db->select(array(
									WAREHOUSE.'.id',
									WAREHOUSE.'.warehouse_code',
									WAREHOUSE.'.warehouse_name',
									WAREHOUSE.'.status',
									WAREHOUSE.'.branch_id',
									WAREHOUSE.'.bin_id',
									WAREHOUSE.'.posting_status',
									WAREHOUSE.'.sap_id',
									WAREHOUSE.'.sap_error',
									MASTER_BRANCHES.'.branch_code',
									MASTER_BRANCHES.'.branch_name'									
									)
							);
									
		$this->app_db->from(WAREHOUSE);	
		$this->app_db->join(MASTER_BRANCHES, MASTER_BRANCHES.'.id = '.WAREHOUSE.'.branch_id', 'left');
		//$this->app_db->where(WAREHOUSE.'.branch_id', $this->currentbranchId);	
		
		if($getServicesList['category']==1)
		{
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			
			if($flag==0){			
				$this->app_db->like('LCASE(warehouse_name)', strtolower($fieldValue));
			}
			$this->app_db->where(WAREHOUSE.'.status', 1);	// ONLY active warehouse 
			$this->app_db->where(WAREHOUSE.'.is_deleted', '0');
		}else if($getServicesList['category']==2)
		{
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where(WAREHOUSE.'.id', $id);
			if($delFlag==1){
				$this->app_db->where(WAREHOUSE.'.is_deleted', '0');
			}		
		}
		
	    $rs = $this->app_db->get();
	    $result =  $rs->result_array();
		
		//printr($result);		//exit;
		if(is_array($result) && count($result)>0){
			
			foreach($result as $warehouseKey => $warehouseValue){
					
					$binData = array();
					$binId = $warehouseValue['bin_id'];
						
					if(!empty($binId)){
						// BIN INFORMATION DETAILS 
							$this->app_db->select(array(
											MASTER_BIN.'.id',
											MASTER_BIN.'.bin_code',
											MASTER_BIN.'.bin_name'
											)
									);
											
							$this->app_db->from(MASTER_BIN);	
							$this->app_db->where_in(MASTER_BIN.'.id', explode(",",$binId));	// ONLY active warehouse 
							
							$rs = $this->app_db->get();
							$binData =  $rs->result_array();
					}					
					$result[$warehouseKey]['binList'] =  $binData;
			}
		}
		return $result;
	}
	
	
	/**
	* @METHOD NAME 	: getFromToWarehouseList()
	*
	* @DESC 		: - 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getFromToWarehouseList()
	{
	    $this->app_db->select(array(
									WAREHOUSE.'.id',
									WAREHOUSE.'.warehouse_code',
									WAREHOUSE.'.warehouse_name',
									WAREHOUSE.'.status',
									WAREHOUSE.'.branch_id',
									WAREHOUSE.'.bin_id',
									MASTER_BRANCHES.'.branch_code',
									MASTER_BRANCHES.'.branch_name'
									)
							);
									
		$this->app_db->from(WAREHOUSE);	
		$this->app_db->join(MASTER_BRANCHES, MASTER_BRANCHES.'.id = '.WAREHOUSE.'.branch_id', 'left');
		$this->app_db->where(WAREHOUSE.'.is_deleted', '0');
		
	    $rs = $this->app_db->get();
	    $result =  $rs->result_array();
		
		if(is_array($result) && count($result)>0){
			
			foreach($result as $warehouseKey => $warehouseValue){
					
					$binData = array();
					$binId = $warehouseValue['bin_id'];

					if(!empty($binId)){
						$binIdArray = explode(",",$binId);
						
						//printr($binIdArray);						exit;
						
						foreach($binIdArray as $binKey => $binValue ){
					
							// BIN INFORMATION DETAILS 
							$this->app_db->select(array(
											MASTER_BIN.'.id',
											MASTER_BIN.'.bin_code',
											MASTER_BIN.'.bin_name'
											)
									);
											
							$this->app_db->from(MASTER_BIN);	
							$this->app_db->where(MASTER_BIN.'.id', $binValue);	// ONLY active warehouse 
							
							$rs = $this->app_db->get();
							$binResult =  $rs->result_array();
							if(!empty($binResult)){
								$binData[$binKey] = $binResult[0];
							}
						}
					}					
					$result[$warehouseKey]['binList'] =  $binData;
			}
		}
		return $result;
	}
	
	
	/**
	* @METHOD NAME 	: getBinAutoList()
	*
	* @DESC 		: TO GET THE BIN AUTO LIST 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getBinAutoList($getServicesList)
	{
	    $this->app_db->select(array(
									MASTER_BIN.'.id',
									MASTER_BIN.'.bin_code',
									MASTER_BIN.'.bin_name',
									MASTER_BIN.'.status',
									MASTER_BIN.'.posting_status',
									MASTER_BIN.'.sap_id',
									MASTER_BIN.'.sap_error'
									));
									
		$this->app_db->from(MASTER_BIN);	
		
		if($getServicesList['category']==1)
		{
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			
			if($flag==0){			
				$this->app_db->like('LCASE(bin_code)', strtolower($fieldValue));
			}		
			$this->app_db->where(MASTER_BIN.'.is_deleted', '0');
		}
		else if($getServicesList['category']==2)
		{
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];

			// echo 'dd';
			// print_r($id);
			// exit;
			// if(is_array($id)){
			// 	$this->app_db->where_in($id);
			// }
			// else{
				$this->app_db->where('id', $id);
			// }
			if($delFlag==1){
				$this->app_db->where(MASTER_BIN.'.is_deleted', '0');
			}		
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getIssuingNoteAutoList()
	*
	* @DESC 		: 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getIssuingNoteAutoList($getServicesList)
	{
	    $this->app_db->select(array('id','note_name','posting_status','sap_id','sap_error'));
		$this->app_db->from(MASTER_ISSUING_NOTE);
		
		if($getServicesList['category']==1)
		{
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			
			
			if($flag==0){			
				$this->app_db->like('LCASE(note_name)', strtolower($fieldValue));
			}	
			$this->app_db->where('status', '1');			
			$this->app_db->where('is_deleted', '0');
		}
		else if($getServicesList['category']==2)
		{
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}		
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getLocationList()
	*
	* @DESC 		: TO GET THE LOCATION AUTO LIST 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getLocationAutoList($getServicesList)
	{
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
									MASTER_STATE.'.state_name',							
									MASTER_COUNTRY.'.country_name',
									));
									
		$this->app_db->from(MASTER_LOCATION);	
		$this->app_db->join(MASTER_STATE, MASTER_STATE.'.id = '.MASTER_LOCATION.'.state_id', 'left');
		$this->app_db->join(MASTER_COUNTRY, MASTER_COUNTRY.'.id = '.MASTER_STATE.'.country_id', 'left');
		
		if($getServicesList['category']==1)
		{
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			
			if($flag==0){			
				$this->app_db->like('LCASE(location_no)', strtolower($fieldValue));
			}		
			$this->app_db->where(MASTER_LOCATION.'.status', '1');
			$this->app_db->where(MASTER_LOCATION.'.is_deleted', '0');
		}
		else if($getServicesList['category']==2)
		{
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where(MASTER_LOCATION.'.is_deleted', '0');
			}		
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	
	/**
	* @METHOD NAME 	: getBranchAutoList()
	*
	* @DESC 		: TO GET THE BRANCH AUTO LIST 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getBranchAutoList($getServicesList)
	{
	    $this->app_db->select(array('id','branch_name','posting_status','sap_id','sap_error'));
		$this->app_db->from(MASTER_BRANCHES);	
		
		if($getServicesList['category']==1)
		{
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			
			if($flag==0){			
				$this->app_db->like('LCASE(attribute_code)', strtolower($fieldValue));
			}		
			$this->app_db->where('is_deleted', '0');
		}
		else if($getServicesList['category']==2)
		{
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}		
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}


	/**
	* @METHOD NAME 	: salesArDpInvoiceDetails()
	*
	* @DESC 		: TO GET THE Sales Ar DpInvoice Details 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function salesArDpInvoiceDetails($getServicesList)
	{
	    $this->app_db->select(array('id','document_number'));
		$this->app_db->from(SALES_AR_DP_INVOICE);	
		
		if($getServicesList['category']==1)
		{
			$fieldValue =  $getServicesList['fieldValue'];
			$flag 		=  $getServicesList['allData'];
			
			if($flag==0){			
				$this->app_db->like('LCASE(attribute_code)', strtolower($fieldValue));
			}		
			$this->app_db->where('is_deleted', '0');
		}
		else if($getServicesList['category']==2)
		{
			$delFlag = $getServicesList['delFlag'];
			$id 	 = $getServicesList['id'];
			$this->app_db->where('id', $id);
			if($delFlag==1){
				$this->app_db->where('is_deleted', '0');
			}		
		}
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getWarehouseListByBranchId()
	*
	* @DESC 		: TO GET THE WAREHOUSE LIST BY BRANCH ID 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getWarehouseListByBranchId($getServicesList)
	{
		$branchId  = $getServicesList['branchId'];
	    $this->app_db->select(array(
									WAREHOUSE.'.id',
									WAREHOUSE.'.warehouse_code',
									WAREHOUSE.'.warehouse_name',
									WAREHOUSE.'.status',
									WAREHOUSE.'.branch_id',
									MASTER_BRANCHES.'.branch_code',
									MASTER_BRANCHES.'.branch_name'									
									)
							);
		$this->app_db->from(WAREHOUSE);	
		$this->app_db->join(MASTER_BRANCHES, MASTER_BRANCHES.'.id = '.WAREHOUSE.'.branch_id', 'left');
		$this->app_db->where(WAREHOUSE.'.branch_id', $branchId);
	    $rs = $this->app_db->get();
	    return $rs->result_array();
	}
	

/******************* END OF AUTO SUGGESTION LIST ***************************************/
	/**
	* @METHOD NAME 	: changePassword()
	*
	* @DESC         : FUNCTION TO CHANGE PASSWORD
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function changePassword($getPostData){

		$oldPassword =  $getPostData['oldPassword'];
		$newPassword =  $getPostData['newPassword'];

		// Check the password
		$this->app_db->select(array('id','password'));
		$this->app_db->from(LOGIN);
		$this->app_db->where('profile_id',$this->currentUserId);
		$this->app_db->where('is_deleted','0');
		$rs=$this->app_db->get();
		
		
		if(1 == $rs->num_rows() &&
			($_row = $rs->row()) &&
			passwordVerify($oldPassword, $_row->password, $_row->id, false)
		)
		{
			$this->app_db->set('password', passwordHash($newPassword));
			$this->app_db->set('updated_on','NOW()');
			$this->app_db->set('updated_by',$this->currentUserId);
			$this->app_db->where('profile_id',$this->currentUserId);
			$this->app_db->update(LOGIN);
			$modelOutput['flag'] = 1;
		}else {
			$modelOutput['flag'] = 2; // old Password does not match.
		}
		return $modelOutput;
	}
	
	
	/**
	* @METHOD NAME 	: getProfileInformation()
	*
	* @DESC         : FUNCTION TO GET THE PROFILE INFORMATION
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getProfileInformation($id){
		// GET THE USERS INFORMATION
		$this->app_db->select(array(
			                    EMPLOYEE_PROFILE.'.id',			                   
								EMPLOYEE_PROFILE.'.emp_code',
								EMPLOYEE_PROFILE.'.gender_id',
								EMPLOYEE_PROFILE.'.first_name',
								EMPLOYEE_PROFILE.'.last_name',
								EMPLOYEE_PROFILE.'.email_id',
								EMPLOYEE_PROFILE.'.gender_id',
								EMPLOYEE_PROFILE.'.primary_country_code',
								EMPLOYEE_PROFILE.'.primary_contact_no',
								EMPLOYEE_PROFILE.'.profile_img',
								EMPLOYEE_PROFILE.'.status',
								EMPLOYEE_PROFILE.'.branch_id',
								EMPLOYEE_PROFILE.'.is_user',
								EMPLOYEE_PROFILE.'.distribution_rules_id',
								LOGIN.'.profile_id',
								MASTER_BRANCHES.'.branch_name'
			             ));
						 
		$this->app_db->from(EMPLOYEE_PROFILE);		
		$this->app_db->where(EMPLOYEE_PROFILE.'.id', $id);
		
		$this->app_db->join(LOGIN, EMPLOYEE_PROFILE.'.id ='.LOGIN.'.profile_id','LEFT');

		$this->app_db->join(MASTER_BRANCHES, EMPLOYEE_PROFILE.'.branch_id ='.MASTER_BRANCHES.'.id','LEFT');


		$this->app_db->where(EMPLOYEE_PROFILE.'.is_deleted',0);
		
		$rs = $this->app_db->get();
				

		if($rs->num_rows()==1){
            $userProfileData 			= $rs->result_array();
			$modelOutput['flag']		= 1;
			$fullUrl					= "";
			$profilePictureName   		= $userProfileData[0]['profile_img'];
            if(!empty($profilePictureName)) {
                $fullUrl =  getFullImgUrl('employee',$profilePictureName); 
            }			
			$userProfileData[0]['profileImgUrl'] = $fullUrl;			 
			$modelOutput['profileInfo'] 		 = $userProfileData;
		}else{
			$modelOutput['flag'] = 2;
		}
		return $modelOutput;
	}
	
	
	/**
	* @METHOD NAME 	: getCompanyInformation()
	*
	* @DESC         : FUNCTION TO GET THE COMPANY INFORMATION
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getCompanyInformation(){
		$this->app_db->get(COMPANY_DETAILS);
		$this->app_db->from(COMPANY_DETAILS);		
		$this->app_db->where(COMPANY_DETAILS.'.is_deleted',0);
		$rs = $this->app_db->get();
		return $rs->result_array();
	}
	
	
	
	/**
	* @METHOD NAME 	: updateprofile()
	*
	* @DESC         : FUNCTION TO UPDATE THE PROFILE INFORMATION
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function updateprofile($getPostData) {
				
        //check email address is already Exists or not
        $whereExistsQry = array('id !=' => $getPostData['id'],'email_id'  => $getPostData['emailId']);
        $emailExistRst  = $this->commonModel->isExists(EMPLOYEE_PROFILE,$whereExistsQry); //returns no.of rows
        if(0 == $emailExistRst) {
            $profileData = array(
									'emp_code'            	 => $getPostData['empCode'],
									'first_name'             => $getPostData['firstName'],
									'last_name'              => $getPostData['lastName'],
									'email_id'               => $getPostData['emailId'],               
									'primary_country_code'   => $getPostData['primaryCountryCode'],
									'primary_contact_no'     => $getPostData['primaryContactNo'],
									'profile_img'     		 => $getPostData['profileImg']
								);
								
            $whereUpdateCondn = array("id" => $getPostData['id']);
            $affectedRows = $this->updateQry(EMPLOYEE_PROFILE, $profileData, $whereUpdateCondn); // returns affected rows
            if($affectedRows == 0 ) {
                $modelOutput['flag'] = 2; // Unable to update the record. Please try again later.
            }
            else {
                $modelOutput['flag'] = 1; // Successfully Updated
            }
        }
        else {
			$modelOutput['flag'] = 3; // Email address already exists
		}
		return $modelOutput;
	}
	
	
	/**
	* @METHOD NAME 	: getARInvoiceDetails()
	*
	* @DESC 		: TO GET THE AR INVOICE DOCUMENT NUMBER
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function getARInvoiceDetails($getPostData)
    {
		$id = $getPostData['id'];
		$this->app_db->select(array('id','document_number','sales_ar_dp_invoice_used_amount','sales_ar_dp_invoice_used_remaining_amount'));
        $this->app_db->from(SALES_AR_INVOICE);
		$this->app_db->where('is_deleted', '0');
		if(isset($getPostData['delFlag'])){
			$this->app_db->where('is_deleted', '1');
		}      
		$this->app_db->where('id', $id);
        $rs = $this->app_db->get();
		return $resultData = $rs->result_array();
	}
		
		
	/**
	* @METHOD NAME 	: getDpInvoiceDocumentNumber()
	*
	* @DESC 		: TO GET THE DP INVOICE DOCUMENT NUMBER
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function getDpInvoiceDocumentNumber($getPostData)
    {
		$id  = '';
		if(isset($getPostData['id']) && !empty($getPostData['id'])){
			$id = $getPostData['id'];
		}
		$this->app_db->select(array('id','tax_percentage','document_number','total_amount','remaining_amount'));
		$this->app_db->from(SALES_AR_DP_INVOICE);
		if(!empty($id)){
			$this->app_db->where('id', $id);
		}else{
			$this->app_db->where('is_deleted', '0');
			$this->app_db->where('status', 2); // status -> CLOSED 
			$this->app_db->where('incoming_payment_flag', 1); 	// Incoming payment flag -> 1 
			$this->app_db->where('remaining_amount >', 0); 		// Remaining amount is greater than 0 
			if($this->currentAccessControlId!=1){	 // ADMIN CONDITION		
				$this->app_db->where_in(SALES_AR_DP_INVOICE.'.created_by', $this->currentgroupUsers);
			}
			if(isset($getPostData['businessPartnerId'])){
				$this->app_db->where('customer_bp_id', $getPostData['businessPartnerId']);
			}
		}
		$rs = $this->app_db->get();
		return $resultData = $rs->result_array();
	}
	
	/**
	* @METHOD NAME 	: getDpInvoiceDocumentNumberForArInvoice()
	*
	* @DESC 		: TO GET THE DP INVOICE DOCUMENT NUMBER 
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function getDpInvoiceDocumentNumberForArInvoice($getPostData)
    {
		$documentTypeId = $getPostData['documentTypeId'];
		$resultData 	= array();
		
		$id  = '';
		if(isset($getPostData['documentNoId']) && !empty($getPostData['documentNoId'])){
			$id = $getPostData['documentNoId'];
		}
		
	
		
		if($documentTypeId==2) $copyFromType  = "SALES_QUOTE";
		if($documentTypeId==3) $copyFromType  = "SALES_ORDER";
		//if($documentTypeId==10) $copyFromType = "SALES_DELIVERY";
		
		if(!empty($documentTypeId) && ($documentTypeId==2 || $documentTypeId==3)){
			
			// GET THE SCREEN DETAILS 
			$screenDetails		= $this->config->item('SCREEN_NAMES')[$copyFromType];
			$parentTableName 	= $screenDetails['tableName'];
			$childTableName		= $screenDetails['childTableName'];
			$fieldName			= $screenDetails['childRefId'];
			
			
			
			// GET CHILD TABLE PRIMARY KEY IDS BASED UPON PARENT ID 
			$this->app_db->select(array('id'));
			$this->app_db->from($childTableName);
			$this->app_db->where($fieldName, $id);
			$this->app_db->where('is_deleted', '0');
			$rs = $this->app_db->get();
			$childTableResultData = $rs->result_array();
			$tblChildIds  = array_column($childTableResultData,'id');

			if(count($tblChildIds)>0){
				// GET THE LIST OF DP INVOICE ITMES ID 
				$query = 'SELECT distinct(sales_ar_dp_invoice_id)
							FROM 
							tbl_sales_ar_dp_invoice_items 
							WHERE 
							copy_from_type="'.$copyFromType.'" 
							AND 
							is_deleted = 0 
							AND 
							copy_from_id IN  ('.implode(",",$tblChildIds).')';
				$rs 	= $this->app_db->query($query);
				$dpInvoiceItems = $rs->result_array();
			
				if(count($dpInvoiceItems)>0){
					$dpInvoiceId = $dpInvoiceItems[0]['sales_ar_dp_invoice_id'];
					
					$this->app_db->select(array('id','total_amount','remaining_amount'));
					$this->app_db->from('tbl_sales_ar_dp_invoice');
					$this->app_db->where_in('id', $dpInvoiceId);
					$this->app_db->where('is_deleted', '0');
					$this->app_db->where('status', 2); // status -> CLOSED 
					$this->app_db->where('incoming_payment_flag', 1); 	// Incoming payment flag -> 1 
					$this->app_db->where('remaining_amount >', 0);
					$rs = $this->app_db->get();
					return $resultData =  $rs->result_array();
				}
			}
		}
	}
	
	
	/**
	* @METHOD NAME 	: getAlternativeItemsByItemId()
	*
	* @DESC 		: TO GET THE ALTERNATIVE ITEMS BY ITEM ID 
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getAlternativeItemsByItemId($getPostData)
    {
		$itemId = $getPostData['itemId'];
        // SELECT 
        $this->app_db->select(array(
								MASTER_ALTERNATIVE_ITEMS.'.item_id',
								MASTER_ALTERNATIVE_ITEMS_LIST.'.id',
								MASTER_ALTERNATIVE_ITEMS_LIST.'.alt_item_id',
								MASTER_ALTERNATIVE_ITEMS_LIST.'.remarks',
								MASTER_ALTERNATIVE_ITEMS_LIST.'.match_factor'
							));
        $this->app_db->from(MASTER_ALTERNATIVE_ITEMS_LIST);
		$this->app_db->join(MASTER_ALTERNATIVE_ITEMS, MASTER_ALTERNATIVE_ITEMS_LIST.'.master_alternative_item_id = '.MASTER_ALTERNATIVE_ITEMS.'.id', '');
		$this->app_db->where(MASTER_ALTERNATIVE_ITEMS_LIST.'.is_deleted', '0');
		$this->app_db->where(MASTER_ALTERNATIVE_ITEMS.'.status', 1); //active
        $this->app_db->where(MASTER_ALTERNATIVE_ITEMS.'.item_id', $itemId);
		
		
        // GET RESULTS 		
        $searchResultSet = $this->app_db->get();
		$searchResultSet = $searchResultSet->result_array();
		
		foreach($searchResultSet as $key => $value){
			
			// FRAME ALL THE INFO DATA
			$statusInfoDetails	= array();
			
			// GET BUSINESS PARTNER CONTACTS LIST 
			$getInfoData		 = array(
										'getItemList' 	 => $value['alt_item_id'],
									);
									
			$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
			
			// SEARCH RESULTS DATA 
			$searchResultSet[$key]['alternative_item_info'] = $statusInfoDetails['itemInfo'];
		}

		// MODEL DATA 
        $modelData['searchResults'] = $searchResultSet;
        return $modelData;
    }
	
	
	/**
	* @METHOD NAME 	: getDocumentNumber()
	*
	* @DESC 		: TO GET THE DOCUMENT NUMBER
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getDocumentNumber($getPostData)
    {
		$documentTypeId = $getPostData['documentTypeId'];
		//$getPostData['businessPartnerId'] = 1;
		$resultData 	= array();
		
		$id  = '';
		if(isset($getPostData['documentNoId']) && !empty($getPostData['documentNoId'])){
			$id = $getPostData['documentNoId'];
		}
		
		if($documentTypeId==1){ // Opportunity
			$this->app_db->select(array('id','opportunity_no as document_number'));
			$this->app_db->from(OPPORTUNITY);
			
			if(!empty($id)){
				$this->app_db->where('id', $id);
			}else{
				$this->app_db->where('is_deleted', '0');
				$this->app_db->where('opportunity_status', 1);
				
				if(isset($getPostData['businessPartnerId'])){
					$this->app_db->where('business_partner_id', $getPostData['businessPartnerId']);
				}
				
				if($this->currentAccessControlId!=1){	 // ADMIN CONDITION		
					$this->app_db->where_in(OPPORTUNITY.'.created_by', $this->currentgroupUsers);
				}
			}
			$rs 		= $this->app_db->get();			
			$resultData = $rs->result_array();			
		}else if($documentTypeId==2){ // Sales Quote
			$this->app_db->select(array('id','document_number as document_number','is_draft'));
			$this->app_db->from(SALES_QUOTE);
			if(!empty($id)){
				$this->app_db->where('id', $id);
			}else{
				$this->app_db->where('is_deleted', '0');
				$this->app_db->where('status', 1);
				
				if($this->currentAccessControlId!=1){	 // ADMIN CONDITION		
					$this->app_db->where_in(SALES_QUOTE.'.created_by', $this->currentgroupUsers);
				}
				
				if(isset($getPostData['businessPartnerId'])){
					$this->app_db->where('customer_bp_id', $getPostData['businessPartnerId']);
				}
				
			}
			$rs = $this->app_db->get();
			$resultData = $rs->result_array();		
		}else if ($documentTypeId==3){ // Sales Order
			$this->app_db->select(array('id','document_number as document_number','is_draft'));
			$this->app_db->from(SALES_ORDER);
			
			if(!empty($id)){
				$this->app_db->where('id', $id);
			}else{
				$this->app_db->where('is_deleted', '0');
				$this->app_db->where('status', 1);
				if($this->currentAccessControlId!=1){	 // ADMIN CONDITION		
					$this->app_db->where_in(SALES_ORDER.'.created_by', $this->currentgroupUsers);
				}
				
				if(isset($getPostData['businessPartnerId'])){
					$this->app_db->where('customer_bp_id', $getPostData['businessPartnerId']);
				}
			}
			$rs = $this->app_db->get();
			$resultData = $rs->result_array();	
		}else if ($documentTypeId==4){ // Activity
			$this->app_db->select(array('id','activity_no as document_number'));
			$this->app_db->from(ACTIVITY);
			if(!empty($id)){
				$this->app_db->where('id', $id);
			}else{
				$this->app_db->where('is_deleted', '0');
				$this->app_db->where('status', 1);
				
				if($this->currentAccessControlId!=1){	 // ADMIN CONDITION		
					$this->app_db->where_in(ACTIVITY.'.created_by', $this->currentgroupUsers);
				}
				
				if(isset($getPostData['businessPartnerId'])){
					$this->app_db->where('business_partner_id', $getPostData['businessPartnerId']);
				}
			}
			$rs = $this->app_db->get();
			$resultData = $rs->result_array();
		}else if ($documentTypeId==5){ // purchase request 
			$this->app_db->select(array('id','document_number','is_draft'));
			$this->app_db->from(PURCHASE_REQUEST);
			if(!empty($id)){
				$this->app_db->where('id', $id);
			}else{
				$this->app_db->where('is_deleted', '0');
				$this->app_db->where('status', 1);
				
				if($this->currentAccessControlId!=1){	 // ADMIN CONDITION		
					$this->app_db->where_in(PURCHASE_REQUEST.'.created_by', $this->currentgroupUsers);
				}
			}
			$rs = $this->app_db->get();
			$resultData = $rs->result_array();
		}else if ($documentTypeId==6){ // purchase order 
			$this->app_db->select(array('id','document_number','is_draft'));
			$this->app_db->from(PURCHASE_ORDER);
			if(!empty($id)){
				$this->app_db->where('id', $id);
			}else{
				$this->app_db->where('is_deleted', '0');
				$this->app_db->where('status', 1);
				
				if($this->currentAccessControlId!=1){	 // ADMIN CONDITION		
					$this->app_db->where_in(PURCHASE_ORDER.'.created_by', $this->currentgroupUsers);
				}
				
				if(isset($getPostData['businessPartnerId'])){
					$this->app_db->where('vendor_bp_id', $getPostData['businessPartnerId']);
				}
			}
			$rs = $this->app_db->get();
			$resultData = $rs->result_array();
		}else if ($documentTypeId==7){ //GRPO
			$this->app_db->select(array('id','document_number','is_draft'));
			$this->app_db->from(GRPO);
			if(!empty($id)){
				$this->app_db->where('id', $id);
			}else{
				$this->app_db->where('is_deleted', '0');
				$this->app_db->where('status', 1);
				
				if($this->currentAccessControlId!=1){	 // ADMIN CONDITION		
					$this->app_db->where_in(GRPO.'.created_by', $this->currentgroupUsers);
				}
				
				if(isset($getPostData['businessPartnerId'])){
					$this->app_db->where('vendor_bp_id', $getPostData['businessPartnerId']);
				}
			}
			$rs = $this->app_db->get();
			$resultData = $rs->result_array();
		}else if ($documentTypeId==8){ //INVENTORY_TRANSFER_REQUEST
			
			// FROM WAREHOUSE DETAILS 
			$fromWarehouseIds			 	  = "";
			$passWareHouseData['branchId'] 	  = $this->currentbranchId;
			$getMyBranchWarehouseList   	  = $this->commonModel->getWarehouseListByBranchId($passWareHouseData);
			$fromWarehouseIds 				  = array_column($getMyBranchWarehouseList,'id');
			$fromWarehouseIds 				  = implode(",",$fromWarehouseIds);
		
		
			$this->app_db->select(array('id','document_number','is_draft'));
			$this->app_db->from(INVENTORY_TRANSFER_REQUEST);
			if(!empty($id)){
				$this->app_db->where('id', $id);
			}else{
				$this->app_db->where('is_deleted', '0');
				$this->app_db->where('status', 1);
				
				if($this->currentAccessControlId!=1){	 // ADMIN CONDITION		
					$this->app_db->where_in(INVENTORY_TRANSFER_REQUEST.'.created_by', $this->currentgroupUsers);
				}
				
				if(isset($getPostData['businessPartnerId'])){ // Business Partner Id
					$this->app_db->where('business_partner_id', $getPostData['businessPartnerId']);
				}
				
				$this->app_db->where_not_in(INVENTORY_TRANSFER_REQUEST.'.to_warehouse_id', $fromWarehouseIds, FALSE);
				$this->app_db->or_where(INVENTORY_TRANSFER_REQUEST.'.intra_branch_flag',1);
			}
			$rs = $this->app_db->get();
			$resultData = $rs->result_array();
		}else if ($documentTypeId==9){ //INVENTORY_TRANSFER
			$this->app_db->select(array('id','document_number','is_draft'));
			$this->app_db->from(INVENTORY_TRANSFER);
			if(!empty($id)){
				$this->app_db->where('id', $id);
			}else{
				$this->app_db->where('is_deleted', '0');
				$this->app_db->where('status', 1);
				
				if($this->currentAccessControlId!=1){	 // ADMIN CONDITION		
					$this->app_db->where_in(INVENTORY_TRANSFER.'.created_by', $this->currentgroupUsers);
				}
				
				if(isset($getPostData['businessPartnerId'])){
					$this->app_db->where('business_partner_id', $getPostData['businessPartnerId']);
				}
			}
			$rs = $this->app_db->get();
			$resultData = $rs->result_array();
		}else {
			// RENTAL MODULE GENERIC LOGIC 
			$screenDetails		= $this->config->item('SCREEN_NAMES');
			if( ($documentTypeId==10) || ($documentTypeId==11) || ($documentTypeId==12) ||
				($documentTypeId==13) || ($documentTypeId==14) || ($documentTypeId==18) || 
				($documentTypeId==19) || ($documentTypeId==20) || ($documentTypeId==21) || 
				($documentTypeId==22) || ($documentTypeId==23) || ($documentTypeId==23)){
				
				$findScreenNameArray = array();
				foreach($screenDetails as $screenKey => $screenValue){
					if($screenValue['id'] == $documentTypeId){
						$findScreenNameArray = $screenValue;
						break;
					}
				}
			
				if(count($findScreenNameArray) > 0) {
					$tableName = $findScreenNameArray['tableName'];
					$this->app_db->select(array('id','document_number','is_draft'));
					$this->app_db->from($tableName);
					if(!empty($id)){
						$this->app_db->where('id', $id);
					}else{
						$this->app_db->where('is_deleted', '0');
						$this->app_db->where('status', 1);
						
						if($this->currentAccessControlId!=1){	 // ADMIN CONDITION		
							$this->app_db->where_in($tableName.'.created_by', $this->currentgroupUsers);
						}
						
						if(isset($getPostData['businessPartnerId'])){
							$this->app_db->where('customer_bp_id', $getPostData['businessPartnerId']);
						}
					}
					$rs = $this->app_db->get();
					$resultData = $rs->result_array();
				}
			}
		}
		
		return $resultData;
    }
	
	
	
	
	/**
	* @METHOD NAME 	: getDocumentNumber()
	*
	* @DESC 		: TO GET THE DOCUMENT NUMBER
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getMasterDocumentNumbering($getPostData)
    {
		//$documentTypeId = isset($getPostData['documentTypeId']) ? $getPostData['documentTypeId'] : 1;
		
		$documentTypeId = $getPostData['documentTypeId'];
		
		$this->app_db->select(array(
									MASTER_DOCUMENT_NUMBERING.'.id',
									MASTER_DOCUMENT_NUMBERING.'.document_numbering_name',
									MASTER_DOCUMENT_NUMBERING.'.prefix',
									MASTER_DOCUMENT_NUMBERING.'.suffix',
									MASTER_DOCUMENT_NUMBERING.'.digits',
									MASTER_DOCUMENT_NUMBERING.'.next_number',
									MASTER_DOCUMENT_NUMBERING.'.remarks',
									MASTER_DOCUMENT_NUMBERING.'.is_system_config',
									MASTER_DOCUMENT_NUMBERING.'.document_numbering_type',
									));
		
		$this->app_db->from(MASTER_DOCUMENT_NUMBERING);
		$this->app_db->where(MASTER_DOCUMENT_NUMBERING.'.is_lock', 0);
		$this->app_db->where(MASTER_DOCUMENT_NUMBERING.'.continue_series', 1);
		$this->app_db->where(MASTER_DOCUMENT_NUMBERING.'.document_type_id', $documentTypeId);
		$this->app_db->where(MASTER_DOCUMENT_NUMBERING.'.branch_id', $this->currentbranchId);
		$this->app_db->where(MASTER_DOCUMENT_NUMBERING.'.is_deleted', 0);
		$rs = $this->app_db->get();
	    return $rs->result_array();
		

		//$getPostData['businessPartnerId'] = 1;
		/*$resultData[] 	= array(
			"documentNumeringName" => "Doc1",
			"nextNumber" =>5,
			"is_system_config" =>0
			);
			
		$resultData[] 	= array(
			"documentNumeringName" => "Doc2",
			"nextNumber" =>5,
			"is_system_config" =>0
			);*/
		
		//return $resultData;
    }

/////////////////////////////////// ITEM CONFIGURATION QUERIES ////////////////////////////////////////////////	
	/**
	* @METHOD NAME 	: getLineItemConfiguationForUser()
	*
	* @DESC 		: TO GET THE LINE ITEM CONFIGURATION FOR USER 
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	/*
	public function getLineItemConfiguationForUser($getPostData)
    {
		$modelOutput['flag']  = 1;
		$this->app_db->select(array('id','module','default_fields','disabled_fields'));
        $this->app_db->from(LINE_ITEM_CONFIGURATION);
		$this->app_db->where('module', $getPostData['screenName']);
        $rs = $this->app_db->get();
		$resultData =  $rs->result_array();
		
	
		if(!empty($resultData[0]['id'])){
				$modelOutput['moduleConfiguraiton']  =  $resultData;
				
				// GET DATA FROM USER LINE ITEM TABLE 
				$this->app_db->select(array('id','module','fields_selected'));
				$this->app_db->from(USER_LINE_ITEM_CONFIGURATION);
				$this->app_db->where('module', $getPostData['screenName']);
				$this->app_db->where('user_id', $this->currentUserId);
				$userRs = $this->app_db->get();
				$userResultData =  $userRs->result_array();
				$modelOutput['userConfiguraiton']  =  $userResultData;
				return $modelOutput;
		}else{
			$modelOutput['flag']  = 2;
		}
		return $modelOutput;
    }
	*/
	
	/**
	* @METHOD NAME 	: updateLineItemConfiguationForUser()
	*
	* @DESC 		: TO UPDATE THE LINE ITEM CONFIGURATION FOR THE USER 
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function updateLineItemConfiguationForUser($getPostData){
		$module  				  = $getPostData['screenName'];
		$userConfigurationDetails = $this->getUserLineItemConfiguration($getPostData);
		$modelOutput['flag']	  = 1;
		
		if(isset($userConfigurationDetails[0]['id']) ){ // UPDATE OPERATION 
				
			$data			  = array( 'fields_selected' => $getPostData['fieldsSelected']);
			$whereUpdateCondn = array(
									   'module'	 => $getPostData['screenName'],
									   'user_id' => $this->currentUserId
									 );
			$affectedRows	  = $this->updateQry(USER_LINE_ITEM_CONFIGURATION, $data, $whereUpdateCondn); // returns affected rows
		
		}else if(count($userConfigurationDetails) == 0 ){ // INSERT 
				$insertData = array(
									 'module'		 	=> $getPostData['screenName'],
									 'user_id'	 		=> $this->currentUserId,
									 'fields_selected' 	=> $getPostData['fieldsSelected'],
								);
								
				$this->commonModel->insertQry(USER_LINE_ITEM_CONFIGURATION,$insertData);
		}
		return $modelOutput;
	}
	
	
	/**
	* @METHOD NAME 	: getUserLineItemConfiguration()
	*
	* @DESC 		: TO UPDATE THE LINE ITEM CONFIGURATION FOR THE USER 
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function getUserLineItemConfiguration($getPostData){
		
		$module  = $getPostData['screenName'];
		
		// GET DATA FROM USER LINE ITEM TABLE 
		$this->app_db->select(array('id','module','fields_selected'));
		$this->app_db->from(USER_LINE_ITEM_CONFIGURATION);
		$this->app_db->where('module', $getPostData['screenName']);
		$this->app_db->where('user_id', $this->currentUserId);
		$userRs = $this->app_db->get();
		return $userResultData =  $userRs->result_array();
	
	}
	
	


/////////////////////////////////// END OF ITEM CONFIGURATION QUERIES ////////////////////////////////////////////////	
/////////////////////////////////// MANAGE ATTACHMENTS ////////////////////////////////////////////////	
	/**
	* @METHOD NAME 	: deleteAttachment()
	*
	* @DESC 		: TO DELETE THE ATTACHMENT
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function deleteAttachment($getPostData)
    {
		$this->app_db->trans_start();
		
			// DELETE IN  MASTER SYNDROME TABLE			
			$whereQry  = array('id' => $getPostData['id']);			
			$this->commonModel->deleteQry(ATTACHMENTS,$whereQry);
        
		$this->app_db->trans_complete();
		
        if ($this->app_db->trans_status() === FALSE) {
            $modelOutput['flag'] = 2; // Failure
        } else {
            $modelOutput['flag'] = 1; // Success
        }
        return $modelOutput;
    }
	
	
	/**
	* @METHOD NAME 	: saveAttachment()
	*
	* @DESC 		: TO SAVE THE ATTACHMENT
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveAttachment($getPostData)
    {	
		$this->app_db->trans_start();
		
			//printr($getPostData);
			 
			foreach($getPostData as $key => $value){
				
				$insertData = array(
									'file_name'		=> $value['fileName'],
									'screen_name' 	=> $value['screenName'],
									'reference_id'	=> $value['referenceId'],
								);
								
				$this->commonModel->insertQry(ATTACHMENTS,$insertData);
			}
			
		$this->app_db->trans_complete();
		
        if ($this->app_db->trans_status() === FALSE) {
            $modelOutput['flag'] = 2; // Failure
        } else {
            $modelOutput['flag'] = 1; // Success
        }
        return $modelOutput;
    }
	
	
	/**
	* @METHOD NAME 	: getAttachmentList()
	*
	* @DESC 		: TO GET THE ATTACHMENT LIST DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getAttachmentList($getPostData)
    {
		$this->app_db->select(array('id','reference_id','screen_name','file_name','date(created_on) as created_on'));
        $this->app_db->from(ATTACHMENTS);
        $this->app_db->where('screen_name', $getPostData['screenName']);
        $this->app_db->where('reference_id', $getPostData['referenceId']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
		
		$modelData['searchResults'] = $rs->result_array();
		return $modelData;
	}
	
	
	/**
	 * @METHOD NAME 	: getTransactionAnalyticsCount()
	 *
	 * @DESC 			: GET THE TRANSACTION ANALYTICS COUNT
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getTransactionAnalyticsCount($tableName, $statusId,$skipDistributionFlag='')
	{
		// GET THE EMPLOYEE DISTRIBUTION LIST 
		$empDetails  = $this->commonModel->getProfileInformation($this->currentUserId);
		$empDistributionRulesId = $empDetails['profileInfo'][0]['distribution_rules_id'];

		if($skipDistributionFlag==1){ // FOR INVENTORY TRANSFER |  REQUEST 
			$fromWarehouseIds			 	  = "";
			$passWareHouseData['branchId'] 	  = $this->currentbranchId;
			$getMyBranchWarehouseList   	  = $this->getWarehouseListByBranchId($passWareHouseData);
			$fromWarehouseIds 				  = array_column($getMyBranchWarehouseList,'id');
			$fromWarehouseIds 				  = implode(",",$fromWarehouseIds);
			
			$this->app_db->select(array('id'));
			$this->app_db->from($tableName);
			
			if (!empty($statusId)) {
				$this->app_db->where('status', $statusId);
			}
			$this->app_db->where('is_deleted', 0);
			
			$this->app_db->where_in($tableName . '.branch_id', $this->currentUserBranchIds);
			
			/* // Removed this condition not required
			$this->app_db->group_start();
				$this->app_db->where($tableName . '.branch_id', $this->currentbranchId);
				$this->app_db->or_where_in('from_warehouse_id', $fromWarehouseIds, FALSE);
			$this->app_db->group_end();
			*/
			
			$rs = $this->app_db->get();
			$searchResultData =  $rs->result_array();
		
		}else{ // FOR OTHER TRANSACTIONS 
			$this->app_db->select(array('id', 'distribution_rules_id'));
			$this->app_db->from($tableName);
			if (!empty($statusId)) {
				$this->app_db->where('status', $statusId);
			}
			$this->app_db->where('is_deleted', 0);
			$this->app_db->where_in($tableName . '.branch_id', $this->currentUserBranchIds);
			
			
			if (strtolower($this->currentEmployeeType) == 'customer') {
				$this->app_db->where('customer_bp_id', $this->customerBusinessPartnerId);
				$rs = $this->app_db->get();
				$searchResultData =  $rs->result_array();
			}else if ($this->currentEmployeeType== 'dealer') {
				$this->app_db->where_in('customer_bp_id', $this->dealerBusinessPartnerId);
				$rs = $this->app_db->get();
				$searchResultData =  $rs->result_array();
			}else {
				// ADMIN CONDITION 
				if (($this->hierarchyMode == 2) && ($this->currentAccessControlId != 1)) { // RM MODULE 
					$this->app_db->where_in($tableName . '.created_by', $this->currentgroupUsers, false);
				}

				$rs = $this->app_db->get();
				$searchResultData =  $rs->result_array();

				if ($this->hierarchyMode == 1) { // DISTRIBUTION RULES 
					// CHECK HIRARACHY MODE
					if ($this->currentAccessControlId != 1) {	// TO FIND THE DISTRIBUTION RULES RECORD
						$searchResultData  = processDistributionRulesData($searchResultData, $empDistributionRulesId);
					}
				}
			}
		}
		
		$totalRecords 		=  count($searchResultData);
		return $totalRecords;
	}
	
	
	/**
	 * @METHOD NAME 	: getItemStockList()
	 *
	 * @DESC 			: TO GET THE ITEM STOCK LIST 
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getItemStockList($getPostData)
    {
		$itemId = $getPostData['itemId'];
		
        $this->app_db->select(array(
									ITEM_STOCKS.'.id',
									ITEM_STOCKS.'.item_id',
									ITEM_STOCKS.'.availability',
									ITEM_STOCKS.'.bin_id',
									MASTER_BIN.'.bin_name',
									WAREHOUSE.'.id AS warehouse_id',
									WAREHOUSE.'.warehouse_name',
									WAREHOUSE.'.branch_id',
									WAREHOUSE.'.warehouse_code',
									MASTER_BRANCHES.'.branch_name'));
		
		$this->app_db->from(ITEM_STOCKS);
		$this->app_db->join(WAREHOUSE, WAREHOUSE.'.id ='.ITEM_STOCKS.'.warehouse_id
		AND '.ITEM_STOCKS.'.item_id='.$itemId.'','right');
		$this->app_db->join(MASTER_BIN, MASTER_BIN.'.id ='.ITEM_STOCKS.'.bin_id','left');
		$this->app_db->join(MASTER_BRANCHES, MASTER_BRANCHES.'.id = '.WAREHOUSE.'.branch_id', 'left');
	    $rs = $this->app_db->get();
	    return $rs->result_array();
    }
	
	
	/**
	 * @METHOD NAME 	: getItemStockDetailsByItemId()
	 *
	 * @DESC 			: TO GET THE ITEM STOCK DETAILS BY ITEM ID  
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getItemStockDetailsByItemId($getPostData)
    {
		$itemId = $getPostData['itemId'];
		
        $this->app_db->select(array(
									ITEM_STOCKS.'.id',
									ITEM_STOCKS.'.item_id',
									ITEM_STOCKS.'.availability',
									ITEM_STOCKS.'.bin_id',
									ITEM_STOCKS.'.warehouse_id',
									));
		$this->app_db->from(ITEM_STOCKS);
		$this->app_db->where('item_id',$itemId);
		$this->app_db->where('is_deleted', '0');
	    $rs = $this->app_db->get();
	    return $rs->result_array();
    }
	
	
	/**
	 * @METHOD NAME 	: getItemTransactionDetails()
	 *
	 * @DESC 			: TO GET THE ITEM TRANSACTION DETAILS WHICH IS USED IN LAST SALES PRICE AND LAST PURCHASE PRICE
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getItemTransactionDetails($getPostData,$screenDetails)
    {
		$itemId = $getPostData['itemId'];
		$type 	=  $getPostData['type'];
		$output = array();
		
		$parentTableName 	= $screenDetails['tableName'];
		$childTableName		= $screenDetails['childTableName'];
		$childRefId			= $screenDetails['childRefId'];
		
		if($type == 'PURCHASE'){
			$appendField = 'vendor_bp_id';
		}else if($type == 'SALES'){
			$appendField = 'customer_bp_id';
		}
		
		$query = 'SELECT
					  t1.created_on,
					  t1.document_number,
					  t1.id,
					  t1.created_by,
					  t1.branch_id,
					  t1.'.$appendField.',
					  t2.item_id,
					  t2.updated_on,
					  t2.'.$childRefId.',
					  t2.quantity,
					  t2.unit_price,
					  t2.total_item_amount
					FROM (SELECT
							item_id,
							quantity,
							unit_price,
							updated_on,
							total_item_amount,
							'.$childRefId.'
						  FROM '.$childTableName.'
							WHERE item_id='.$itemId.'
						 LIMIT 50) AS t2
					  INNER JOIN '.$parentTableName.' AS t1
						ON t1.id = t2.'.$childRefId.' ORDER BY t2.updated_on DESC';

		// SEARCH RESULT DATA 
		$rs				   = $this->app_db->query($query);
		$searchResultData  = $rs->result_array();
		return $searchResultData;
    }
	
	
	/**
	 * @METHOD NAME 	: insertAPITracker()
	 *
	 * @DESC 			: INSERT THE API TRACKER INTO THE TABLE 
	 * @RETURN VALUE 	: 
	 * @PARAMETER 		: 
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	**/
	public function insertAPITracker($responseParam){
		
		$serverUrl = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
		
		if($serverUrl == 'sapservice.castleready.com') {

				$skipMethodNames = array(
										'checkOrganizationExpiry',
										'getCompanyList',
										'getBranchList',
										'checkLogin',
										'checkCompanyLogin',
										'forgotPassword',
										'getApiTrackerList'
									);
									
				
				// DECLARE VARIABLES
				$moduleName = '';
				$screenName = '';
				$methodName = '';
				$apiUrl 	= $this->uri->uri_string();
				$methodType	= $this->input->method();
					
				// ASSIGN SEGMENTS 
				$segments = $this->uri->segment_array();
				if(isset($segments[1])){
					$moduleName = $segments[1];
				}	
				if(isset($segments[2])){
					$screenName = $segments[2];
				}		
				if(isset($segments[3])){
					$methodName = $segments[3];
				}
			
				if(!in_array($methodName,$skipMethodNames)){
					
					$requestParam = $this->currentRequestOrginalData;
					$decodeResponse = json_decode($responseParam);
					
					if(isset($decodeResponse->results->searchResults) && count($decodeResponse->results->searchResults) > 0){
						$getFirstRecord = $decodeResponse->results->searchResults[0];
						$decodeResponse->results->searchResults = $getFirstRecord;
					}
					
					$passData 	= array(
										'module_name' 	=> $moduleName,
										'screen_name' 	=> $screenName,
										'api_url'		=> $apiUrl,
										'method_name'	=> $methodName,
										'method_type'	=> $methodType,
										'request_parameter'	=> $requestParam,
										'response_parameter'	=> json_encode($decodeResponse)
									);
					
					// CHECK DATA ALREADY EXISTS 
					$whereQry = array(
										'module_name' 	=> $moduleName,
										'screen_name' 	=> $screenName,
										'api_url'		=> $apiUrl,
										'method_name'	=> $methodName,
									);
									
					$chkRecord = $this->commonModel->isExists(DEV_API_TRACKER,$whereQry);
					
					
					if($chkRecord==0){
						$insertId 	= $this->commonModel->insertQry(DEV_API_TRACKER,$passData);
					}else { // UPDATE THE RECORD 
						$affectedRows = $this->updateQry(DEV_API_TRACKER, $passData, $whereQry);
					}
				}
		}
	}

	/**
	* @METHOD NAME 	: getApiTrackerList()
	*
	* @DESC 		: TO GET THE API TRACKER LIST 
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getApiTrackerList($getPostData)
    {
        // SELECT 
        $this->app_db->select(array(
								DEV_API_TRACKER.'.id',
								DEV_API_TRACKER.'.module_name',
								DEV_API_TRACKER.'.screen_name',
								DEV_API_TRACKER.'.api_url',
								DEV_API_TRACKER.'.method_name',
								DEV_API_TRACKER.'.method_type',
								DEV_API_TRACKER.'.request_parameter',
								DEV_API_TRACKER.'.response_parameter'
							));
        $this->app_db->from(DEV_API_TRACKER);
        $this->app_db->where(DEV_API_TRACKER.'.is_deleted', '0');
		$this->app_db->order_by(DEV_API_TRACKER.'.updated_on', 'desc');
        
        // GET RESULTS 		
        $searchResultSet = $this->app_db->get();
        $searchResultSet = $searchResultSet->result_array();
		
		foreach($searchResultSet as $searchKey => $searchValue){
			$searchResultSet[$searchKey]['request_parameter'] = json_decode($searchValue['request_parameter']);
			$searchResultSet[$searchKey]['response_parameter'] = json_decode($searchValue['response_parameter']);
		}
		// MODEL DATA 
		$totalRecords = count($searchResultSet);
        $modelData['searchResults'] = $searchResultSet;
        $modelData['totalRecords']  = $totalRecords;
        return $modelData;
    }
	

	
	
/////////////////////////////////// END OF ATTACHMENTS ////////////////////////////////////////////////	
/////////////////////////////////// TRANSACTION COMMON QUERIES ////////////////////////////////////////////////	
	/**
	 * @METHOD NAME 	: checkOpenQuantityRecords()
	 *
	 * @DESC 			: TO CHECK THE OPEN QUANTITY RECORDS 
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function checkOpenQuantityRecords($tableName,$fieldName,$fieldValue)
    {
		$chkDataExists = 1;
        $this->app_db->select(array('id'));
        $this->app_db->from($tableName);
        $this->app_db->where($fieldName, $fieldValue);
        $this->app_db->where('open_quantity >',0,false);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        $resultData =  $rs->result_array();
		if(count($resultData)>0){
			$chkDataExists = 2;
		}
		return $chkDataExists;
    }
	
	/**
	 * @METHOD NAME 	: checkOrderedQuantityRecords()
	 *
	 * @DESC 			: TO CHECK THE ORDERED QUANTITY RECORDS 
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function checkOrderedQuantityRecords($tableName,$fieldName,$fieldValue)
    {
		$chkDataExists = 1;
        $this->app_db->select(array('id'));
        $this->app_db->from($tableName);
        $this->app_db->where($fieldName, $fieldValue);
        $this->app_db->where('ordered_quantity >',0,false);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        $resultData =  $rs->result_array();
		if(count($resultData)>0){
			$chkDataExists = 2;
		}
		return $chkDataExists;
    }
	
	
	/**
	 * @METHOD NAME 	: getTransTblParentId()
	 *
	 * @DESC 			: GET THE TRANSACTION ANALYTICS COUNT
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getTransTblParentId($tableName,$fieldName,$id)
    {
        $this->app_db->select(array($fieldName));
        $this->app_db->from($tableName);
        $this->app_db->where('id', $id);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        $resultData =  $rs->result_array();
		if(count($resultData)>0){
			return $resultData[0][$fieldName];
		}else{
			return "";
		}
    }
    
	
	/**
	 * @METHOD NAME 	: directlyCloseStatus()
	 *
	 * @DESC 			: DIRECTLY CLOSE THE STATUS OF THE RECORD  
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function directlyCloseStatus($tableName, $fieldName, $insertId)
    {
		$data 	  = array('status'=>2);
		$whereQry = array('id' => $insertId);
		$this->app_db->set('updated_on','NOW()',false);
		$this->app_db->set('updated_by',$this->currentUserId);
		$this->app_db->update(constant($tableName),$data,$whereQry);
		$affectedRows = $this->app_db->affected_rows();
    }
	
	
	/**
	 * @METHOD NAME 	: updateTransParentTableStatus()
	 *
	 * @DESC 			: TO UPDATE THE PARENT STATUS 
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function updateTransParentTableStatus($tableName,$updateArray,$id)
    {
		$whereQry = array('id' => $id);
		$this->app_db->set('updated_on','NOW()',false);
		$this->app_db->set('updated_by',$this->currentUserId);
		
		// LOOP EXECUTION 
		foreach($updateArray as $updateKey => $updateValue){
			$this->app_db->set($updateKey,$updateValue);
		}	

		$this->app_db->where('id', $id);
		$this->app_db->update($tableName,$whereQry);
		$affectedRows = $this->app_db->affected_rows();
		
		//$str = $this->app_db->last_query();
		
    }
	
	
	/**
	 * @METHOD NAME 	: transGetChildOrderItems()
	 *
	 * @DESC 			: TO 
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function transGetChildOrderItems($tableName,$primaryId)
    {
		$fieldName = array('id','quantity','open_quantity','ordered_quantity','item_id');
		$this->app_db->select($fieldName);
        $this->app_db->from($tableName);
        //$this->app_db->where_in('id', $primaryIds);
		$this->app_db->where('id', $primaryId);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
		//$str = $this->app_db->last_query();
		//printr($str);
        return $resultData =  $rs->result_array();
    }
    
	
	/**
	 * @METHOD NAME 	: transUpdateChildOrderItems()
	 *
	 * @DESC 			: TO UPDATE THE CHILD ORDERD ITEM LIST 
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function transUpdateChildOrderItems($itemListArray)
    {
		$data = array();
		if(count($itemListArray)>0){
			
			$copyFromType		= $itemListArray[0]['copyFromType'];
			$screenDetails		= $this->config->item('SCREEN_NAMES')[$copyFromType];
			$parentTableName 	= $screenDetails['tableName'];
			$childTableName		= $screenDetails['childTableName'];
			$fieldName			= $screenDetails['childRefId'];
			
			$this->app_db->set('updated_on','NOW()',false);
			$this->app_db->set('updated_by',$this->currentUserId);
			
			foreach($itemListArray as $itemKey => $itemValue){
				$whereQry = array('id'=>$itemValue['id']);
				$this->app_db->set('open_quantity',$itemValue['open_quantity'],false);
				$this->app_db->set('ordered_quantity',$itemValue['ordered_quantity'],false);
				$this->app_db->update($childTableName,$data,$whereQry);
				$affectedRows = $this->app_db->affected_rows();
			}
		}
    }
   
   
	/**
	 * @METHOD NAME 	: updateLastPriceInItemTable()
	 *
	 * @DESC 			: TO 
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function updateLastPriceInItemTable($itemData,$fieldFlag)
    {
		$data = array();
		$this->app_db->set('updated_on','NOW()',false);
		$this->app_db->set('updated_by',$this->currentUserId);
		
		if($fieldFlag==1){ // PURCHASE TABLE
			$data['last_purchase_price'] = $itemData['unitPrice'];
		}else if($fieldFlag==2){ // SALES TABLE
			$data['last_sales_price'] 	 = $itemData['unitPrice'];
		}
		$this->app_db->where('id', $itemData['itemId']);
		$this->app_db->update(MASTER_ITEM,$data);
		$affectedRows = $this->app_db->affected_rows();
    }
    
	
   /**
	 * @METHOD NAME 	: transUpdateItemStock()
	 *
	 * @DESC 			: TO UPDATE THE STOCK TABLE 
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function transUpdateItemStock($itemValue,$symbol)
    {
		//print_r($itemValue);
		
		$arrivedValue 		= $itemValue['newArrivedValue'];
		$arrivedValueCalc 	= 'availability' . $symbol . $arrivedValue;
		
		//echo "Arrived value calc ::".$arrivedValueCalc; //exit;
					
			$itemId  	 = $itemValue['itemId'];
			$warehouseId = $itemValue['warehouseId'];
			$binId 		 = $itemValue['binId'];
		
			// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
			$whereQry = array(
								  'item_id' 	 => $itemId,
								  'warehouse_id' => $warehouseId,
								  'bin_id' 		 => $binId,
								);
			$chkRecord = $this->commonModel->isExists(ITEM_STOCKS,$whereQry);
			
						
			if($chkRecord==0){
				$passData 	= array(
								'item_id' 		=> $itemId,
								'warehouse_id'  => $warehouseId,
								'bin_id'		=> $binId,
								'availability'	=> $arrivedValue
							);
				$insertId 		= $this->commonModel->insertQry(ITEM_STOCKS,$passData);
			}else{
				$updateData = array();
				$this->app_db->set('availability', $arrivedValueCalc, false);
				$this->app_db->set('updated_on','NOW()',false);
				$this->app_db->set('updated_by',$this->currentUserId);
				$this->app_db->update(ITEM_STOCKS,$updateData,$whereQry);
				$affectedRows = $this->app_db->affected_rows();
			}
		
			$this->updateTblMasterItemStock($itemValue);
		//exit;
    }
	
	
   /**
	 * @METHOD NAME 	: updateTblMasterItemStock()
	 *
	 * @DESC 			: TO UPDATE THE MASTER ITEM  STOCK DETAILS 
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function updateTblMasterItemStock($itemValue)
    {
		//if($inventoryFlag!=1){
			
			//echo "Going inside inventory :: ";
			//printr($itemValue);
		
			$itemId  = $itemValue['itemId'];
		
			// AVAILABLITY 
			$this->app_db->select_sum("availability");
			$this->app_db->from(ITEM_STOCKS);
			$this->app_db->where('item_id', $itemId);
			$this->app_db->where('is_deleted', '0');
			$rs = $this->app_db->get();
			$resultData =  $rs->result_array();
			$availabilityCnt = $resultData[0]['availability'];

			/*	
				printr($this->app_db->last_query()); echo "Availablity count is ::"; printr($availabilityCnt);exit;
			*/
			
			if(empty($availabilityCnt)){
				$availabilityCnt = 0;
			}	
			
			// UPDATE INTO ITEM TABLE 
			$updateData = array(
				'stock' => $availabilityCnt
			);
			$whereQry = array(
									  'id' 	 => $itemId,
							 );
			$this->app_db->set('updated_on','NOW()',false);
			$this->app_db->set('updated_by',$this->currentUserId);
			$this->app_db->update(MASTER_ITEM,$updateData,$whereQry);
		//}
	}
	
	
   /**
	 * @METHOD NAME 	: checkReqItemStockExists()
	 *
	 * @DESC 			: -
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	**/
	public function checkReqItemStockExists($itemValue,$symbol)
    {
		//echo "<pre>";print_r($itemValue);echo "</pre>";exit;
		
		$modelOutput['flag']  = 1; // SUCCESS
		$itemId  		 	  = $itemValue['itemId'];
		$warehouseId 	 	  = $itemValue['warehouseId'];
		$binId 			 	  = $itemValue['binId'];
		$newArrivedValue 	  = $itemValue['newArrivedValue'];
	
		// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
		$whereQry = array(
							  'item_id' 	 => $itemId,
							  'warehouse_id' => $warehouseId,
							  'bin_id' 		 => $binId,
							);
		$chkRecord = $this->commonModel->isExists(ITEM_STOCKS,$whereQry);
		
		if($chkRecord==0){
			$modelOutput['flag'] = 2; // record not exists 
			return $modelOutput;
		}				
		
		if($chkRecord!=0 && $symbol=="-"){
			$this->app_db->select(array("id","availability"));
			$this->app_db->from(ITEM_STOCKS);
			$this->app_db->where('warehouse_id', $warehouseId);
			$this->app_db->where('bin_id', $binId);
			$this->app_db->where('item_id', $itemId);
			$this->app_db->where('is_deleted', '0');
			$rs = $this->app_db->get();
			$resultData =  $rs->result_array();
			
			$availability = $resultData[0]['availability'];
			
			if($newArrivedValue>$availability){
				$modelOutput['flag'] = 3; // GIVEN QUANTITY IS HIGHER VALUE
				return $modelOutput;
			}	
		}
		return $modelOutput;
    }
	
	
	/**
	 * @METHOD NAME 	: getTransactionTrackingDetails()
	 *
	 * @DESC 			: TO GET THE TRANSACTION RECORD DETAILS 
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getTransactionTrackingDetails($fieldValue)
    {
        $this->app_db->select(array('id','transaction_ids','transaction_details'));
        $this->app_db->from(TRACKING_TRANSACTION);
		//$this->app_db->where(TRACKING_TRANSACTION.'.transaction_ids REGEXP','LCASE(replace("'.$fieldValue.'"," ","|"))',false);
		$formFieldValue = "'".$fieldValue."'";
		$where = "FIND_IN_SET(".$formFieldValue.','.TRACKING_TRANSACTION.".transaction_ids) >0 ";
		$this->app_db->where($where);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        $resultData =  $rs->result_array();
		return $resultData;
    }
	
	
	/**
	 * @METHOD NAME 	: saveTransactionRecord()
	 *
	 * @DESC 			: TO SAVE THE TRANSACTION RECORDS 
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function saveTransactionRecord($transactionIds,$transDetails)
    {
		$rowData['transaction_ids'] 	= $transactionIds;
		$rowData['transaction_details'] = json_encode($transDetails);
		$insertId = $this->commonModel->insertQry(TRACKING_TRANSACTION, $rowData);
		//echo "insert id is ".$insertId;
    }
	
	
	/**
	 * @METHOD NAME 	: updateTransactionRecord()
	 *
	 * @DESC 			: TO UPDATE THE TRANSACTION RECORDS 
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function updateTransactionRecord($rowId,$transactionIds,$transDetails)
    {
		$this->app_db->set('transaction_ids', $transactionIds);
		$this->app_db->set('transaction_details', json_encode($transDetails));
		$this->app_db->set('updated_on','NOW()');
		$this->app_db->set('updated_by',$this->currentUserId);
		$this->app_db->where('id',$rowId);
		$this->app_db->update(TRACKING_TRANSACTION);
    }
	
	
	/**
	 * @METHOD NAME 	: getTransactionRecord()
	 *
	 * @DESC 			: -
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getTransactionRecord($id,$tableName)
    {
		if( $tableName == 'tbl_rental_inspection_out' || $tableName == 'tbl_rental_inspection_in') 
		{
			$fieldName = array('id','document_number','status','date(created_on) as posting_date');
		}
		else if ($tableName == 'tbl_rental_worklog' ){
			//$fieldName = array('id','document_number','status','total_billable_hours as total_amount');
			$fieldName = array('id','document_number','status');
		}
		else {
			$fieldName = array('id','document_number','status','posting_date','document_date','total_amount','total_before_discount');
		}
		$this->app_db->select($fieldName);
        $this->app_db->from($tableName);
		$this->app_db->where('id', $id);
       // $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        return $resultData =  $rs->result_array();
    }
	
	
	/**
	 * @METHOD NAME 	: getItemOpenQuantityCount()
	 *
	 * @DESC 			: -
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getItemOpenQuantityCount($itemId,$tableName)
    {
		$this->app_db->select('SUM(open_quantity) AS open_quantity_cnt', FALSE);
        $this->app_db->from(constant($tableName));
		$this->app_db->where('item_id', $itemId);
		$this->app_db->where('status', 1); // open count only 
        $rs = $this->app_db->get();
		return $resultData =  $rs->result_array();
    }
	
	
	/**
	 * @METHOD NAME 	: updateOpenQuanityCountToItemTable()
	 *
	 * @DESC 			: -
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function updateOpenQuanityCountToItemTable($screenName,$openQuantityValue,$itemId)
    {
		if($screenName == 'PURCHASE_ORDER'){
			$updateData['purchase_open_count'] = $openQuantityValue;
		}else if($screenName == 'SALES_ORDER'){
			$updateData['sales_open_count'] = $openQuantityValue;
		}
		$whereQry  = array('id' => $itemId );
		$affectedRows = $this->updateQry(MASTER_ITEM, $updateData, $whereQry);
    }
	
	
	/**
	 * @METHOD NAME 	: getParentTableStatus()
	 *
	 * @DESC 			: TO GET THE PARENT TABLE STATUS 
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getParentTableStatus($id,$tableName)
    {
		$this->app_db->select(array('id','status'));
        $this->app_db->from($tableName);
		$this->app_db->where('id', $id);
        $rs = $this->app_db->get();
		return $resultData =  $rs->result_array();
    }
	
	
	/**
	 * @METHOD NAME 	: updateStatusToChildTable()
	 *
	 * @DESC 			: TO UPDATE THE STATUS TO CHILD TABLE 
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function updateStatusToChildTable($id,$status,$childTableName,$fieldName)
    {
		$data			  = array( 'status' => $status);
		$whereUpdateCondn = array($fieldName => $id);
		$affectedRows	  = $this->updateQry($childTableName, $data, $whereUpdateCondn); // returns affected rows
    }
	
		
	/**
	 * @METHOD NAME 	: updateSourceChildTableUtilizedStatus()
	 *
	 * @DESC 			: RENTAL_MODULE : TO UPDATE THE SOURCE CHILD TABLE UTILIZED STATUS  
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function updateSourceChildTableUtilizedStatus($tableName,$id,$isUtilized)
    {
		$data = array('is_utilized' => $isUtilized);
		$whereUpdateCondn = array('id' => $id);
		$affectedRows	  = $this->updateQry($tableName, $data, $whereUpdateCondn); // returns affected rows
    }
	
	
	/**
	 * @METHOD NAME 	: checkAvailableUtilizedRecordsExists()
	 *
	 * @DESC 			: RENTAL_MODULE : CHECK THE AVAILABLE UTILIZED RECORD EXISTS IN THE SOURCE TABLE
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function checkAvailableUtilizedRecordsExists($tableName,$fieldName,$fieldValue)
    {
		$chkDataExists = 1;
        $this->app_db->select(array('id'));
        $this->app_db->from($tableName);
        $this->app_db->where($fieldName, $fieldValue);
        $this->app_db->where('is_utilized',0);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        $resultData =  $rs->result_array();
		if(count($resultData)>0){
			$chkDataExists = 2;
		}
		return $chkDataExists;
    }

	
	/**
	 * @METHOD NAME 	: checkItemInUtilizedStatus()
	 *
	 * @DESC 			: RENTAL_MODULE : TO CHECK THE ITEM UTITLIZED IN ANOTHER TABLE 
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function checkItemInUtilizedStatus($tableName,$fieldName,$fieldValue)
    {
		$chkDataExists = 1;
        $this->app_db->select(array('id'));
        $this->app_db->from($tableName);
        $this->app_db->where($fieldName, $fieldValue);
        $this->app_db->where('is_utilized',1);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        $resultData =  $rs->result_array();
		if(count($resultData)>0){
			$chkDataExists = 2;
		}
		return $chkDataExists;
    }
	
	
	/**
	* @METHOD NAME 	: getAvailableStock()
	*
	* @DESC 		: FUNCTION WRITTEN BY JOEL(Sapteam) TO MANAGE THE AVAILABLETOPROMISE FUNCTIONLITY
	* @RETURN VALUE : 
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function getAvailableStock($requestData)
	{
		$itemId 		= $requestData['itemId'];
		$warehouseId 	= $requestData['warehouseId'];

		$val 	= 0;
		$query 	= 'SELECT availability FROM tbl_item_stocks WHERE item_id =  "'.$itemId.'" AND warehouse_id = "'.$warehouseId.'"';
		$rs 				= $this->app_db->query($query);
		$searchResultData 	=  $rs->result_array();
		if(count($searchResultData)> 0){
		   $val = $searchResultData[0]['availability'];
		}
		return $val;
	}


	/**
	* @METHOD NAME 	: getAvailableToPromise()
	*
	* @DESC 		: FUNCTION WRITTEN BY Joel(Sapteam) TO MANAGE THE AVAILABLETOPROMISE FUNCTIONLITY
	* @RETURN VALUE : 
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getAvailableToPromise($requestData)
	{
		$itemId 		= $requestData['itemId'];
		$warehouseId 	= $requestData['warehouseId'];
		$query 			= 'SELECT * FROM (SELECT "SO" AS document, a.document_number AS docnum,  c.partner_name, a.posting_date AS orderdate, a.delivery_date AS deliverydate, b.open_quantity AS committed, 0 AS ordered FROM tbl_sales_order AS a 
			LEFT JOIN tbl_sales_order_items AS b ON a.id = b.sales_order_id
			LEFT JOIN tbl_business_partner AS c ON a.customer_bp_id = c.id
			WHERE item_id = '.$itemId.' AND warehouse_id = '.$warehouseId.' AND a.status = 1 

			UNION ALL 

			SELECT "PO" AS document, a1.document_number AS docnum,  c1.partner_name, a1.posting_date AS orderdate, a1.delivery_date AS deliverydate,0 AS committed, b1.open_quantity AS ordered  FROM tbl_purchase_order AS a1 
			LEFT JOIN tbl_purchase_order_items AS b1 ON a1.id = b1.purchase_order_id
			LEFT JOIN tbl_business_partner AS c1 ON a1.vendor_bp_id = c1.id
			WHERE item_id = '.$itemId.' AND warehouse_id = '.$warehouseId.' AND a1.status = 1 
			) AS a12
			ORDER BY deliverydate ASC';

		// SEARCH RESULT DATA 
		$rs				   = $this->app_db->query($query);
		$searchResultData  = $rs->result_array();
		return $searchResultData;
	}

	
/////////////////////////////////////////////// PRICE LIST MANIPULATION ///////////////////////////////////////////////
	/**
	 * @METHOD NAME 	: getMasterItemPriceDetails()
	 *
	 * @DESC 			: TO GET THE MASTER ITEM PRICE DETAILS 
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getMasterItemPriceDetails($getPostData)
    {
		$this->app_db->select(array('id','item_id','price_list_id','unit_price'));
		$this->app_db->from(MASTER_ITEM_PRICE_LIST);
		$this->app_db->where('price_list_id', $getPostData['priceListId']);
		$this->app_db->where('item_id', $getPostData['itemId']);
		$rs = $this->app_db->get();
		return $resultData =  $rs->result_array();
    }
	
	
	/**
	 * @METHOD NAME 	: getSpBusinessPartnerDetails()
	 *
	 * @DESC 			: TO GET THE SP BUSINESS PARTHER DETAILS 
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getSpBusinessPartnerDetails($getPostData)
    {
		$this->app_db->select(array('id','item_id','price_list_id','unit_price','discount_percentage','price_after_discount'));
		$this->app_db->from(SP_BUSINESS_PARTNER);
		$this->app_db->where('price_list_id', $getPostData['priceListId']);
		$this->app_db->where('item_id', $getPostData['itemId']);
		$this->app_db->where('business_partner_id', $getPostData['businessPartnerId']);
		$rs = $this->app_db->get();
		return $resultData =  $rs->result_array();
    }
	
	
	/**
	 * @METHOD NAME 	: getLastPriceIdByItem()
	 *
	 * @DESC 			: TO GET THE LAST PRICE ID BY ITEM 
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getLastPriceIdByItem($getPostData)
    {
		$this->app_db->select(array('id','last_price_list_id'));
		$this->app_db->from(MASTER_ITEM);
		$this->app_db->where('id', $getPostData['itemId']);
		$rs = $this->app_db->get();
		return $resultData =  $rs->result_array();
    }
	
	
	/**
	 * @METHOD NAME 	: getBusinessPartnerPriceListInfo()
	 *
	 * @DESC 			: TO GET THE BUSINESS PARTHER PRICE LIST INFO
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getBusinessPartnerPriceListInfo($getPostData)
    {
		$this->app_db->select(array('id','price_list_id'));
		$this->app_db->from(BUSINESS_PARTNER);
		$this->app_db->where('id', $getPostData['businessPartnerId']);
		$rs = $this->app_db->get();
		return $resultData =  $rs->result_array();
    }
	
	
	/**
	 * @METHOD NAME 	: getMasterPriceListById()
	 *
	 * @DESC 			: TO GET THE MASTER PRICE LIST BY ID 
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	**/
	public function getMasterPriceListById($getPostData)
    {
		$rowData = array('id','price_list_name','default_price_list_id','default_factor','is_system_config','system_type');
		$this->app_db->select($rowData);
		$this->app_db->from(MASTER_PRICE_LIST);
		$this->app_db->where('id', $getPostData['id']);
		$this->app_db->where('is_deleted', '0');
		$rs 		= $this->app_db->get();
		return $resultData =   $rs->result_array();
	}
	
		
	/**
	 * @METHOD NAME 	: getDefaultPriceListFactor()
	 *
	 * @DESC 			: TO GET THE DEFAULT PRICE LIST FACTOR
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getDefaultPriceListInformation($getPostData,$defaultFactor)
    {
		$defaultPriceListId 		= $getPostData['default_price_list_id'];
		$passData['id'] 			= $defaultPriceListId;
		$getDefaultPriceListDetails = $this->getMasterPriceListById($passData);
		
		if(count($getDefaultPriceListDetails) > 0){
			$getDbDefaultPriceListId 	= $getDefaultPriceListDetails[0]['default_price_list_id'];
			
			$defaultFactor 				= $defaultFactor * $getDefaultPriceListDetails[0]['default_factor'];
			$getDefaultPriceListDetails[0]['calculated_default_factor'] = $defaultFactor;
			
			if($getDbDefaultPriceListId == $defaultPriceListId){
					return $getDefaultPriceListDetails;
			}else {
				$getDefaultPriceListDetails[0]['defaultPriceListId'] = $getDefaultPriceListDetails[0]['default_price_list_id']; 
				return $this->getDefaultPriceListInformation($getDefaultPriceListDetails[0],$defaultFactor);
			}
		}else{
			return $getDefaultPriceListDetails;
		}
    }
	
	
	
/////////////////////////////////////////////// END OF PRICE LIST MANIPULATION ///////////////////////////////////////////////
/////////////////////////////////// END OF  TRANSACTION COMMON QUERIES ////////////////////////////////////////	
//////////////////////////////////////////////////RENTAL MODULE COMMON FUNCTIONS ////////////////////////////////////
	/**
	* @METHOD NAME 	: getEquipmentDetailsByRentalItemId()
	*
	* @DESC 		: TO GET THE EQUIPMENT DETAILS BY ITEM ID 
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getEquipmentDetailsByRentalItemId($getPostData)
    {
		$rentalItemId = $getPostData['rentalItemId'];
		
		$query = 'select * from
					(select	
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
						a.document_id,
						a.document_type_id,
						a.status,
						a.rental_status,
						a.remarks,
						
						 /* Master STATIC DATA */
						msd.name as ownership,
					
						/* WAREHOUSE */
						wh.warehouse_code,	
						wh.warehouse_name,	
						
						 /* Master STATIC DATA -1  */
						msd1.name as document_type_name
									
					FROM '.MASTER_RENTAL_EQUIPMENT.' as a
					LEFT JOIN '.WAREHOUSE.' as wh 
						ON wh.id = a.warehouse_id
					LEFT JOIN (SELECT * FROM '.MASTER_STATIC_DATA.' WHERE type = "EQUIPMENT_OWNERSHIP") as msd
						ON msd.master_id = a.ownership_id
					LEFT JOIN (SELECT * FROM '.MASTER_STATIC_DATA.' WHERE type = "DOCUMENT_TYPE") as msd1
						ON msd1.master_id = a.document_type_id
					
					WHERE a.is_deleted = 0
					AND a.rental_item_id = '.$rentalItemId.'
					)  as a
					
				WHERE id != 0 ';
		$rs				    = $this->app_db->query($query);
		$resultData  		= $rs->result_array();
		return $resultData;
    }
	
	
	/**
	* @METHOD NAME 	: getcurrentEquipmentRentalStatus()
	*
	* @DESC 		: TO GET THE CURRENT EQUIPMENT RENTAL STATUS  
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getcurrentEquipmentRentalStatus($equipmentId)
    {
		$rowData = array('id','equipment_code','equipment_name','rental_status','document_id','document_type_id');
		$this->app_db->select($rowData);
		$this->app_db->from(MASTER_RENTAL_EQUIPMENT);
		$this->app_db->where('id', $equipmentId);
		$this->app_db->where('is_deleted', '0');
		$rs 		= $this->app_db->get();
		return $resultData =   $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getEquipmentRentalStatusByScreen()
	*
	* @DESC 		: TO CHECK THE RENTAL EQUIPMENT STATUS BY SCREEN NAME 
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getEquipmentRentalStatusByScreen($currentScreenTableName,$copyFromType,$rentalStatusName,$chkStatusFlag = 1)
    {
		$modelOutput = array();
		$flag 		 = 0;
		$rowData 	 = array('id','current_screen_name','copy_from_screen_name','from_status','to_status');
		$this->app_db->select($rowData);
		$this->app_db->from(RENTAL_EQUIPMENT_SCREEN_CONFIGURATION);
		$this->app_db->where('current_screen_name', $currentScreenTableName);
		$this->app_db->where('copy_from_screen_name', $copyFromType);
		if($chkStatusFlag == 1) {
			$this->app_db->where('from_status', $rentalStatusName);
		}else if ($chkStatusFlag == 2) {
			$this->app_db->where('to_status', $rentalStatusName);
		}
		$this->app_db->where('is_deleted', '0');
		$rs 		= $this->app_db->get();
		$resultData =   $rs->result_array();
		
		//printr($this->app_db->last_query());
		if(count($resultData) == 1) {
			$flag = 1;
		}else if(count($resultData) == 0){
			$flag = 0;
		}
		$modelOutput['results'] = $resultData;
		$modelOutput['recordExistsFlag'] = $flag;
		return $modelOutput;
	}
	
	
	/**
	* @METHOD NAME 	: updateTblRentalEquipmentRentalStatus()
	*
	* @DESC 		: -
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateTblRentalEquipmentRentalStatus($equipmentId,$rentalStatusId)
    {
		$data			  = array( 'rental_status' => $rentalStatusId);
		$whereUpdateCondn = array( 'id'=> $equipmentId);
		$affectedRows	  = $this->updateQry(MASTER_RENTAL_EQUIPMENT, $data, $whereUpdateCondn); // returns affected rows
	}
	
	
	/**
	* @METHOD NAME 	: updateTblRentalEquipmentDocumentStatus()
	*
	* @DESC 		: -
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateTblRentalEquipmentDocumentStatus($equipmentId,$documentTypeId,$documentId)
    {
		$data			  = array( 'document_id' => $documentId, 'document_type_id' => $documentTypeId );
		$whereUpdateCondn = array( 'id'=> $equipmentId);
		$affectedRows	  = $this->updateQry(MASTER_RENTAL_EQUIPMENT, $data, $whereUpdateCondn); // returns affected rows
	}
	
	
	/**
	* @METHOD NAME 	: updateWorkLogStatus()
	*
	* @DESC 		: TO UPDATE THE RENTAL WORKLOG STATUS 
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateWorkLogStatus($worklogId,$statusId)
    {
		$data			  = array( 'status' => $statusId);
		$whereUpdateCondn = array( 'id'=> $worklogId);
		$affectedRows	  = $this->updateQry(RENTAL_WORKLOG, $data, $whereUpdateCondn); // returns affected rows
	}
	
////////////////////////////////////////////////// END OF RENTAL MODULE ////////////////////////////////////////////
////////////////////////////////////////////////// START OF APPROVAL PROCESS ///////////////////////////////////////
	public function convertDraftDocumentToApprovedDocument($screenName,$documentId){
		
		$this->app_db->trans_start();
		
			$getOverAllApprovalStatus  = getOverAllApprovalStatusForDocument($screenName,$documentId);
			
			if(count($getOverAllApprovalStatus) == 0 ){
				$modelOutput['flag'] = 3; 	// RECORD NOT FOUND. Please contact admin 
				return $modelOutput;
			}else if (count($getOverAllApprovalStatus)>0){
				$overAllApprovalStatus = $getOverAllApprovalStatus[0]['overall_approval_status'];
				if($overAllApprovalStatus==1){ 		  // PENDING
					$modelOutput['flag'] = 4;
					return $modelOutput;
				}else if ($overAllApprovalStatus==3){ // REJECTED
					$modelOutput['flag'] = 5;
					return $modelOutput;
				}
			}
			
			if($overAllApprovalStatus == 2 ) { // Only approval document converted from draft to normal document 
			
				$documentTypeId  		  = $getOverAllApprovalStatus[0]['document_type_id'];

				// GET SCREEN NAME 
				$findScreenNameArray = getScreenDetailsByDocumentType($documentTypeId);
				$tableName           = $findScreenNameArray['tableName'];
	  
				// APPROVED 
				// GET THE DOCUMENT NUMBERING ID AND DOCUMENT NUMBERING TYPE 
				$trimCharacterTblName    = substr($tableName, 4);
				$configTblNameConvertion = strtoupper($trimCharacterTblName);

				$getDocumentNumberDetails 			    = getDocumentNumberTypeId($configTblNameConvertion,'PRIMARY');
				$passDocumentData['document_numbering_id'] 	    = $getDocumentNumberDetails[0]['id'];
				$passDocumentData['document_numbering_type'] 	= $getDocumentNumberDetails[0]['document_numbering_type'];

				// PROCESS THE DOCUMENT NUMBER TO NEXT INCREMENT 
				$DocNumInfo = processDocumentNumber($passDocumentData, $tableName);
				$passDocumentData['document_number'] = $DocNumInfo['documentNumber'];

				// To update next document number.
				updateNextNumber($passDocumentData, $passDocumentData['document_numbering_type']);

				// UPDATE TO APPROVAL STATUS COLUMN IN DOCUMENTS TABLE (PURCHASE REQUEST, GRPO ETC)
				// DOCUMENT NUMBER COLUMNS ALSO NEEDS TO BE UPDATED TOGETHER 
				$updateData['document_number']       =  $DocNumInfo['documentNumber'];
				$updateData['document_numbering_id'] =  $passDocumentData['document_numbering_id'];
				$updateData['approval_status'] =  2;
				$updateData['is_draft']        =  0;
				$whereQry                      = array('id'=>$documentId);
				$this->commonModel->updateQry($tableName, $updateData, $whereQry);

			 // TO UPDATE THE DOCUMENT NUMBER TO THE DOCUMENT TABLE 
				$id 									   	 = $getOverAllApprovalStatus[0]['id'];
				$documentNumber 			  				 = $DocNumInfo['documentNumber'];
				$updateApprovalStatusData['document_number'] = $documentNumber;
				$whereQry                     				 = array('id' => $id);
				$this->commonModel->updateQry(APPROVAL_STATUS_REPORT, $updateApprovalStatusData, $whereQry);
			}
		$this->app_db->trans_complete();
		
		 if ($this->app_db->trans_status() === FALSE) {
            $modelOutput['flag'] = 2; // Failure
        } else {
            $modelOutput['flag'] = 1; // Success
        }
        return $modelOutput;
    }
////////////////////////////////////////////////// END OF APPROVAL PROCESS ////////////////////////////////////////////
////////////////////////////////////////////////// START OF NOTIFICATION ////////////////////////////////////////////
	public function saveNotificationTbl($notificationDetails){

		$notificationType = $notificationDetails['notification_type']; 
		$proceedFlag 	  = 0;
		
		// CHECK THE FLAG IN THE CONSTANT FILE 
		if($notificationType == 1 && WEB_NOTIFICATION == 1) {
			$proceedFlag = 1;
		}else if($notificationType == 2 && SMS_NOTIFICATION == 1) {
			$proceedFlag = 1;
		}else if($notificationType == 3 && EMAIL_NOTIFICATION == 1) {
			$proceedFlag = 1;
		}

		// PROCEED FLAG 
		if($proceedFlag == 1){
			$this->app_db->trans_start();
				
				$insertID = $this->commonModel->insertQry(NOTIFICATIONS,$notificationDetails);
		
			$this->app_db->trans_complete();
			
			if ($this->app_db->trans_status() === FALSE) {
				$modelOutput['flag'] = 2; // Failure
			} else {
				$modelOutput['flag'] = 1; // Success
			}
			return $modelOutput;
		}
	}

////////////////////////////////////////////////// END OF NOTIFICATION ////////////////////////////////////////////


}
?>