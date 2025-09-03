<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Settings_model.php
* @Class  			 : Settings_model
* Model Name         : Settings_model
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
class Settings_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'SETTINGS';
		$this->tableName 	= constant($this->tableNameStr);
    }
	
    
	/**
	* @METHOD NAME 	: updateSettings()
	*
	* @DESC 		: TO UPDATE THE SETTINGS 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateSettings($getPostData)
    {
		$whereQry = array('id'=>$getPostData['id']);
		$rowData = bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
		
		$whereExistsQry = array(
								 'LCASE(branch_id)'   => $this->currentbranchId,
								 'id!='				  => $getPostData['id']
								);
		
		$totRows = $this->commonModel->isExists($this->tableName,$whereExistsQry);
		               
        if(0 == $totRows) {
			$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);
			$modelOutput['flag'] = 1;
		}else{
			 $modelOutput['flag'] = 2; 
		}
        return $modelOutput;
    }
	
	
    /**
	* @METHOD NAME 	: getSettingsDetails()
	*
	* @DESC 		: TO GET THE REASON LIST INFORMATION
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getSettingsDetails($getPostData)
    {
        $rowData = array("id","sales_tax_id","purchase_tax_id","bp_credit_limit_strict_mode","posting_status","sap_id","sap_error");
		$this->app_db->select($rowData);
		$this->app_db->from($this->tableName);
		$this->app_db->where('is_deleted', '0');
		$this->app_db->where('branch_id', $this->currentbranchId);
		$rs = $this->app_db->get();
		return  $rs->result_array();
    }
	
}
?>