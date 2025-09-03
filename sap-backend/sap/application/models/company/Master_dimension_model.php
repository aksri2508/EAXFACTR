<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_dimension_model.php
* @Class  			 : Master_dimension_model
* Model Name         : Master_dimension_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 11 MAY 2018
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Master_dimension_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'MASTER_DIMENSION';
		$this->tableName 	= constant($this->tableNameStr);
    }
	
	
	/**
	* @METHOD NAME 	: updateDimension()
	*
	* @DESC 		: TO UPDATE THE DIMENSION
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateDimension($getPostData)
    {	
		// Adding Transaction Start
		$this->app_db->trans_start();
			
			// DIMENSION DETAILS
			foreach($getPostData as $dimensionKey => $dimensionValue){
				
				$id 	 = $dimensionValue['id'];
				$data	 = array(
								'dimensionName'	    => $dimensionValue['dimensionName'],
								'dimensionDescription' => $dimensionValue['dimensionDescription'],
								'status'			    => $dimensionValue['status'],
							);

				// EMPTY RECORD DATA
				if(!empty($id)){
					$whereQry = array('id'=>$id);
					$rowData = bindConfigTableValues($this->tableNameStr, 'UPDATE', $data);
					$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);
				}
			}
		
		$this->app_db->trans_complete(); // TRANSACTION COMPLETE
	
		
	// Check the transaction status
		if ($this->app_db->trans_status() === FALSE) {
			$modelOutput['flag'] = 2; // Failure
		} else {
			$modelOutput['flag'] = 1; // Success
		}
		return $modelOutput;
    }
    
	
    /**
	* @METHOD NAME 	: getDimensionList()
	*
	* @DESC 		: TO GET THE DIMENSION LIST
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getDimensionList($getPostData)
    {
        $this->app_db->select(array(
										MASTER_DIMENSION.'.id',
										MASTER_DIMENSION.'.dimension_name',
										MASTER_DIMENSION.'.dimension_description',
										MASTER_DIMENSION.'.status',
										MASTER_DIMENSION.'.posting_status',
										MASTER_DIMENSION.'.sap_id',
										MASTER_DIMENSION.'.sap_error',
										MASTER_STATIC_DATA.'.name as statusName'
							));
							
        $this->app_db->from(MASTER_DIMENSION);
		$this->app_db->join(MASTER_STATIC_DATA, MASTER_STATIC_DATA.'.master_id = '.MASTER_DIMENSION.'.status', '');
        $this->app_db->where(MASTER_STATIC_DATA.'.type', 'COMMON_STATUS');
        $this->app_db->where(MASTER_DIMENSION.'.is_deleted', '0');
		$rs = $this->app_db->get();
	    return $rs->result_array();
    }
}
?>
