<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Company_details_model.php
* @Class  			 : Company_details_model
* Model Name         : Commpany_details_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 02 JULY 2023
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Company_details_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'COMPANY_DETAILS';
		$this->tableName 	= constant($this->tableNameStr);
    }

	/**
	* @METHOD NAME 	: editCompanyDetails()
	*
	* @DESC 		:  TO EDIT THE COMPANY DETAILS  
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editCompanyDetails($getPostData)
    {
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $getPostData['id']);
        $this->app_db->select(array('id','company_name','company_logo','smtp_host','smtp_secure','smtp_protocol','smtp_port','smtp_username','smtp_password','mail_provider','sender_user_name','sender_user_emailid'));
        $this->app_db->select($rowData);
        $this->app_db->from(COMPANY_DETAILS);
        $this->app_db->where('id', $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        return $rs->result_array();
    }


	/**
	* @METHOD NAME 	: updateCompanyDetails()
	*
	* @DESC 		: TO UPDATE THE COMPANY DETAILS 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateCompanyDetails($getPostData)
    {
		$rowData = bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
		
		$this->app_db->trans_start();
		
		// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
		$whereExistsQry = array(
								 'LCASE(id)' => strtolower($getPostData['id']),
								);	
		$chkRecord 		= $this->commonModel->isExists(EMPLOYEE_PROFILE,$whereExistsQry);
		
		
        if(1 == $chkRecord) {
			
			$whereQry = array('id'	=>	$getPostData['id']);			
			$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);
			
			$this->app_db->trans_complete();

            $modelOutput['flag'] = 1;

        } else {
            $modelOutput['flag'] = 2;
        }
        return $modelOutput;
    }
	
}
?>