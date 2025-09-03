<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Access_control_model.php
* @Class  			 : Access_control_model
* Model Name         : Access_control_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 9 JULY 2023
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Access_control_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'ACCESS_CONTROL_SCREEN_LIST';
		$this->tableName 	= constant($this->tableNameStr);

		$this->tableNameStr1 = 'ACCESS_CONTROL';
		$this->tableName1 	= constant($this->tableNameStr1);
    }
	
	
	/**
	* @METHOD NAME 	: saveAccessControl()
	*
	* @DESC 		: TO SAVE ACCESS CONTROL SCREEN
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveAccessControl($getPostData)
    {
		// Transaction Start
		$this->app_db->trans_start();
		
		$whereExistsQry = array(
			'LCASE(access_control_name)' => strtolower($getPostData['accessControlName'])
		 );

		$chkRecord 	= $this->commonModel->isExists($this->tableName1, $whereExistsQry);

		if (0 == $chkRecord) {
			$rowData['access_control_name'] = $getPostData['accessControlName'];
			$rowData['status'] 				= 1;
			$insertId 	= $this->commonModel->insertQry($this->tableName1, $rowData);
			$modelOutput['insert_id']	 = $insertId;
			
			// Process the Child Table 
			$getPostData['access_control_insertId']  = $insertId;
			$this->processAccessControlScreenList($getPostData);
			$modelOutput['flag'] = 1;
			$modelOutput['sId']	 = $insertId;
		}
		else {
			$modelOutput['flag'] = 4;
		}
		$this->app_db->trans_complete();
		return $modelOutput;
	}
		
	
	/**
	* @METHOD NAME 	: updateAccessControl()
	*
	* @DESC 		: TO UPDATE ACCESS CONTROL SCREEN
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateAccessControl($getPostData)
    {
		// Transaction Start
		$this->app_db->trans_start();
		
		$whereExistsQry = array(
			'LCASE(access_control_name)' => strtolower($getPostData['accessControlName']),
			'id!='			 			 => $getPostData['id']
		 );
		$chkRecord 	= $this->commonModel->isExists($this->tableName1, $whereExistsQry);
		if (0 == $chkRecord) {
			$whereQry						= array('id'=> $getPostData['id']);
			$rowData['access_control_name'] = $getPostData['accessControlName'];
			$rowData['status'] 				= 1;
			$this->commonModel->updateQry($this->tableName1, $rowData, $whereQry);
			
			// Process the Child Table 
			$getPostData['access_control_insertId']  = $getPostData['id'];
			$this->processAccessControlScreenList($getPostData);
			$modelOutput['sId']	 = $getPostData['id'];
			$modelOutput['flag'] = 1;
		}
		else {
			$modelOutput['flag'] = 4;
		}
		$this->app_db->trans_complete();
		return $modelOutput;		
	}
		
	
	/**
	* @METHOD NAME 	: processAccessControlScreenList()
	*
	* @DESC 		: -
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function processAccessControlScreenList($getPostData)
    {
		// Check for AccessControlData Process.
		 foreach($getPostData['accessControlData'] as $moduleData){

			foreach($moduleData['screenList'] as $screenData){
								
				$aclData = array();
				$aclData['master_module_screen_mapping_id'] = $screenData['screenId'];
				$aclData['access_control_id'] 				= $getPostData['access_control_insertId'];

				// Getting Control Permission Flag values as Int.
				$aclData['enable_view'] 		=  $screenData['enableView'];
				$aclData['enable_add'] 			=  $screenData['enableAdd'];
				$aclData['enable_update']		=  $screenData['enableUpdate'];
				$aclData['enable_download']		=  $screenData['enableDownload'];
						
				if(isset($screenData['accessControlScreenListId']) && !empty($screenData['accessControlScreenListId'])){
					$whereQry = array('id'=> $screenData['accessControlScreenListId']);
					$insertId = $this->commonModel->updateQry($this->tableName, $aclData, $whereQry);
				}else if(
						($screenData['enableView']==2 || $screenData['enableAdd']==2 || 
						 $screenData['enableUpdate']==2 || $screenData['enableDownload']==2)
						){
							$insertId 	= $this->commonModel->insertQry($this->tableName, $aclData);
					}
				}
		}
	}
	

	/**
	* @METHOD NAME 	: getModuleScreenList()
	*
	* @DESC 		: TO GET MODULE SCREEN MAPPING. 
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function getModuleScreenList()
    {
		
		$query = 'select * from
					(select	
					    a.id,
					    a.module_id,
						a.screen_name,
						a.screen_order,
						a.enable_udf,
						a.enable_document_numbering,
						a.enable_approval_process,
						a.enable_notification,
						a.not_available_columns,
						a.is_system_config,						
						a.created_on,
						a.created_by,
						a.updated_on,
						a.updated_by,

						/* Master Module  */
						mm.module_name,
						mm.module_order

					FROM '.MASTER_MODULE_SCREEN_MAPPING.' as a
					LEFT JOIN '.MASTER_MODULE.' as mm
					ON mm.id = a.module_id
					WHERE a.is_deleted = 0)  as a
				WHERE id != 0 ORDER BY module_order';
		
		
		$rs				   = $this->app_db->query($query);
		$searchResultData  = $rs->result_array();
		
		$totalRecords 					= count($searchResultData);
		$searchResultSet				= $searchResultData;

		// MODEL DATA 
        $modelData['searchResults'] = $searchResultSet;
        $modelData['totalRecords']  = $totalRecords;
        return $modelData;
    }


	/**
	* @METHOD NAME 	: getAccessControlList()
	*
	* @DESC 		: TO GET ACCESS CONTROL INFO. 
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getAccessControlList($getPostData,$downloadFlag='')
    {
		$query = 'select * from
					(select	
					    a.id,
						a.access_control_name, 
						a.status,
						a.is_system_config,
						a.created_on,
						a.created_by,
						a.updated_on,
						a.updated_by,

						/* Master STATIC DATA -1  */
						msd.name as status_name

					FROM '.ACCESS_CONTROL.' as a
					LEFT JOIN (SELECT * FROM '.MASTER_STATIC_DATA.' WHERE type = "COMMON_STATUS") as msd
					ON msd.master_id = a.status
					WHERE a.is_deleted = 0)  as a
				WHERE id != 0 ';
		
  
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                if ($fieldValue!=""){
					if($fieldName=="accessControlName") {
						$query.=' AND access_control_Name REGEXP LCASE(replace("'.$fieldValue.'"," ","|"))';
					}
					else if ($fieldName == "status") {
						$query .= ' AND status = "' . $fieldValue . '"';
					}
					else if ($fieldName == "sapId") {
						$query .= ' AND sap_id = "' . $fieldValue . '"';
					}
					
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('ACCESS_CONTROL')['columns_list'],$fieldName);

			if(!empty($fieldName)){
				$query.= ' ORDER BY '.$fieldName.' '.$sortOrder;
			}
			
        }else{
			$query.= ' ORDER BY updated_on desc';
		}
		
        
		// PAGINATION
		if (isset($tableProperties['first'])) {
			$offset = $tableProperties['first'];
			$limit  = $tableProperties['rows'];
		} else {
			$offset = 0;
			$limit  = $tableProperties['rows'];
		}
		
		$rs				   = $this->app_db->query($query);
		$searchResultData  = $rs->result_array();
		
		$totalRecords 					= count($searchResultData);
		$searchResultSet				= $searchResultData;

		// MODEL DATA 
        $modelData['searchResults'] = $searchResultSet;
        $modelData['totalRecords']  = $totalRecords;
        return $modelData;
    }
	
	
	/**
	* @METHOD NAME 	: editAccessControlScreenList()
	*
	* @DESC 		: TO EDIT ACCESS CONTROL SCREEN LIST 
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editAccessControlScreenList($id)
    {
			$query = 'SELECT * from
					(select	
						a.id, 
						a.access_control_name,
						a.status,
						a.is_system_config,
						acsl.id as access_control_screen_list_id,
						acsl.access_control_id,
						acsl.master_module_screen_mapping_id,
						acsl.enable_view,
						acsl.enable_add,
						acsl.enable_update,
						acsl.enable_download
					FROM '.ACCESS_CONTROL.' as a
					LEFT JOIN '.ACCESS_CONTROL_SCREEN_LIST.' as acsl 
						ON acsl.access_control_id = a.id
					WHERE a.is_deleted = 0
					)  as a
				WHERE id ='.$id;	
		
			//AND a.branch_id in ('.$this->currentUserBranchIds.')
		
			$rs			  = $this->app_db->query($query);
			$editRecords  = $rs->result_array();
			return $editRecords;
	}
	
}
?>