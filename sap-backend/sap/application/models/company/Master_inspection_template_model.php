<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_inspection_template_model.php
* @Class  			 : Master_inspection_template_model
* Model Name         : Master_inspection_template_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 01 MAY 2021
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Master_inspection_template_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->tableNameStr 			= 'MASTER_INSPECTION_TEMPLATE';
		$this->tableName 				= constant($this->tableNameStr);
	}


	/**
	 * @METHOD NAME 	: saveInspectionTemplate()
	 *
	 * @DESC 			: TO SAVE THE INSPECTION TEMPLATE
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function saveInspectionTemplate($getPostData)
	{

		$rowData = bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);

		$rowData['template_details'] = json_encode($rowData['template_details']);

		// Adding Transaction Start
		$this->app_db->trans_start();

		$whereExistsQry = array(
			'LCASE(template_name)' => strtolower($getPostData['templateName']),
		);
		$chkRecord = $this->commonModel->isExists(MASTER_INSPECTION_TEMPLATE, $whereExistsQry);

		if (0 == $chkRecord) {

			$insertId 	= $this->commonModel->insertQry($this->tableName, $rowData);

			$getPostData['itemId'] 	= $insertId;

			if ($insertId > 0) {

				$this->app_db->trans_complete(); // TRANSACTION COMPLETE
			}

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
	 * @METHOD NAME 	: updateInspectionTemplate()
	 *
	 * @DESC 			: TO UPDATE THE INSPECTION TEMPLATE..
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function updateInspectionTemplate($getPostData)
	{

		$rowData	 = bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);

		$rowData['template_details'] = json_encode($rowData['template_details']);

		$id   = $getPostData['id'];

		$whereExistsQry = array(
			'LCASE(template_name)' => strtolower($getPostData['templateName']),
			'id!='				  => $getPostData['id'],
		);

		$totRows = $this->commonModel->isExists(MASTER_INSPECTION_TEMPLATE, $whereExistsQry);

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


}
