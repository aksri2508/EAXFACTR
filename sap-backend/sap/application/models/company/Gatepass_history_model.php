<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Gatepass_history_model.php
* @Class  			 : Gatepass_history_model
* Model Name         : Gatepass_history_model
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
class Gatepass_history_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr		= 'GATEPASS_HISTORY';
		$this->tableName 		= constant($this->tableNameStr);
    }
	
	
	/**
	 * @METHOD NAME 	: saveGatePassCheckInOut()
	 *
	 * @DESC 			: TO SAVE THE GATE PASS IN-OUT
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT : -
	 **/
	public function saveGatePassCheckInOut($getPostData)
	{
		$rowData = bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);

		$this->app_db->trans_start();
		$insertId 	= $this->commonModel->insertQry($this->tableName, $rowData);
	
		
		// CLOSE THE TRANSPORT BASED UPON THE STATUS 
		$gatePassStatus = $getPostData['status'];
		
		if($gatePassStatus==1){ // CLOSE THE TRANSPORT 
			$whereQry   = array('id' => $getPostData['id']);
			$rowData    = array('status'=>2); // CLOSE THE STATUS 
			$this->commonModel->updateQry(TRANSPORT, $rowData, $whereQry);
		}
	
		if($insertId>0){
			$this->app_db->trans_complete(); // Transaction Complete.
		}

		// Check the transaction status
		if ($this->app_db->trans_status() === FALSE) {
			$modelOutput['flag'] = 2; // Failure
		} else {
			$modelOutput['sId']	 = $insertId;
			$modelOutput['flag'] = 1; // Success

		}
		return $modelOutput;
	}
	
	
	
	/**
	* @METHOD NAME 	: getGatePassByBarCode()
	*
	* @DESC 		: TO GET THE GATE PASS BY BARCODE.
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function getGatePassByBarCode($bcode)
    {
		$this->app_db->select('
								a.id,
								a.barcode_number,
								a.vehicle_id,
								mv.vehicle_code,
								mv.description as vehicle_description,
								mv.vendor_bp_id,
								bp.partner_code as vendor_bp_code,
								bp.partner_name as vendor_bp_name,							
							GROUP_CONCAT(tpi.sales_ar_invoice_id) as invoice_id,
							GROUP_CONCAT(IFNULL(sariv.document_number, \'-\')) as document_number,
							GROUP_CONCAT(IFNULL(msd.name, \'-\')) as invoice_status'
					);
							
		$this->app_db->from(TRANSPORT. " a");
		$this->app_db->join(MASTER_VEHICLE." mv", 'mv.id = a.vehicle_id', 'left');
		$this->app_db->join(BUSINESS_PARTNER." bp", 'bp.id = mv.vendor_bp_id', 'left');

		$this->app_db->join(TRANSPORT_INVOICES." tpi", 'tpi.transport_id = a.id', 'left');

		$this->app_db->join(SALES_AR_INVOICE." sariv", 'sariv.id = tpi.sales_ar_invoice_id', 'left');
		$this->app_db->join(MASTER_STATIC_DATA." msd", 'msd.master_id = tpi.status AND msd.type = "TRANSPORT_INVOICE_STATUS"', 'left');
		$this->app_db->where('a.barcode_number',$bcode);
		$this->app_db->where('a.is_deleted',0);
		$this->app_db->where('a.status',1);
		$this->app_db->group_by('a.barcode_number');	

		$rs = $this->app_db->get();
		$resultData =  $rs->result_array();	
		return $resultData;
    }



}
