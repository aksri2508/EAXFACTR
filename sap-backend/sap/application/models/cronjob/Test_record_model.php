<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Test_record_model.php
* @Class  			 : Test_record_model
* Model Name         : Test_record_model
* Description        :
* Module             : pages
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 14 APR 2021
* @LastModifiedDate  : 14 APR 2021
* @LastModifiedBy    : 
* @LastModifiedDesc  : Added Mail notification setup for cron.
* Features           : 
*/
class Test_record_model extends CI_Model {

	
	public function __construct()
	{
		parent::__construct();
		$this->tableNameStr = 'SALES_QUOTE';
		$this->itemTableNameStr = 'SALES_QUOTE_ITEMS';
		$this->itemTableColumnRef = 'sales_quote_id';
		$this->itemTableColumnReqRef = 'salesQuoteId';
		$this->tableName = constant($this->tableNameStr);
		$this->itemTableName = constant($this->itemTableNameStr);
	}
	

	/**
	 * @METHOD NAME   : editSalesQuote()
	 *
	 * @DESC 		  : -
	 * @RETURN VALUE  : $rs array
	 * @PARAMETER 	  : $getPostData array
	 * @SERVICE 	  : WEB
	 * @ACCESS POINT  : -
	 **/
	public function listRecords($id)
	{
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $id);
		$this->app_db->select($rowData);
		$this->app_db->from($this->tableName);
		$this->app_db->where('id', $id);
		$this->app_db->where('is_deleted', '0');
		$this->app_db->where('branch_id', $this->currentbranchId);
		$rs = $this->app_db->get();
		return  $rs->result_array();
	}

	
}
?>