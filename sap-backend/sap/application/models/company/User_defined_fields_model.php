<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : User_defined_fields_model.php
* @Class  			 : User_defined_fields_model
* Model Name         : User_defined_fields_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 19 JUNE 2019
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class User_defined_fields_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr		= 'UDF_MASTER_FORM_CONTROLS';
		$this->subTableNameStr1 = 'UDF_MASTER_FORM_CONTROLS_OPTIONS';
		$this->subTableNameStr2 = 'UDF_SCREEN_MAPPING';
		$this->tableName 		= constant($this->tableNameStr);
		$this->subTableName1 	= constant($this->subTableNameStr1);
		$this->subTableName2 	= constant($this->subTableNameStr2);
		$this->itemTableColumnRef	 = 'form_controls_id';
		$this->itemTableColumnReqRef = 'formControlsId';
    }
	
	
	/**
	* @METHOD NAME 	: bindValues()
	*
	* @DESC 		: TO BIND THE VALUES 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function bindValues($tableName,$getPostData){
		
		$rowData = array();

		if($tableName=='UDF_MASTER_FORM_CONTROLS'){
			$rowData = array(
							'field_type_id'	  => $getPostData['fieldTypeId'],
							'field_label'	  => $getPostData['fieldLabel'],
							'field_name'	  => $getPostData['fieldName'],
							'required_field'  => $getPostData['requiredField'],
							'branch_id' 	  => $this->currentbranchId, 
							'status'		  => $getPostData['status'],
						);
		}else if($tableName=='UDF_MASTER_FORM_CONTROLS_OPTIONS'){
			$rowData = array(
							'form_controls_id'	  	=> $getPostData['formControlsId'],
							'options_field_label'	=> $getPostData['optionsFieldLabel'],
							'default_selected'	  	=> $getPostData['defaultSelected'],
						);
		}else if($tableName=='UDF_SCREEN_MAPPING'){
			$rowData = array(
							'document_type_id'	  		=> $getPostData['documentTypeId'],
							'form_controls_id'	=> $getPostData['formControlsId'],
							'branch_id' 	 	=> $this->currentbranchId, 
						);
		}
		return $rowData;
	}
	
	
	/**
	* @METHOD NAME 	: getUdfFieldType()
	*
	* @DESC 		: TO GET THE UDF FIELD TYPE 
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getUdfFieldType($id='')
    {
        $this->app_db->select(array(
								UDF_MASTER_FIELD_TYPE.".id",
								UDF_MASTER_FIELD_TYPE.".field_type_name"								
							));
      
		$this->app_db->from(UDF_MASTER_FIELD_TYPE);
		
		if(!empty($id)){
			$this->app_db->where(UDF_MASTER_FIELD_TYPE.'.id', $id);		
		}
		
		if(empty($id)){
			 $this->app_db->where(UDF_MASTER_FIELD_TYPE.'.is_deleted', '0');		
		}		
        $rs = $this->app_db->get();
        return $rs->result_array();
    }
    
	
	/**
	* @METHOD NAME 	: saveFormControls()
	*
	* @DESC 		: TO SAVE THE FORM CONTROLS
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveFormControls($getPostData)
    {	
	
		$rowData = bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);
		$rowData['branch_id'] = $this->currentbranchId;
		
		// SUB-ARRAY FORMATION 
		$getListData         = $getPostData['selectOptionListArray'];
	
		// CHECK FIELD NAME IS ALREADY EXISTS IN THE TABLE 
			$whereExistsQry = array(
								'LCASE(field_name)' => strtolower($getPostData['fieldName']),
								'branch_id'			=> $this->currentbranchId
							);	

			$totRows = $this->commonModel->isExists($this->tableName,$whereExistsQry);
		   
			if(0 == $totRows) {
				
				// Transaction Start
			   $this->app_db->trans_start();
			   
					// MASTER FORM CONTROLS 
					$insertId 		= $this->commonModel->insertQry($this->tableName, $rowData);
					
					if($getPostData['fieldTypeId']==2){ // SELECT BOX
						foreach ($getListData as $key => $value) {
							$value['formControlsId'] = $insertId;			
							$this->saveFormControlOptions($value);
						}
					}
				
				$this->app_db->trans_complete();
			
				if ($this->app_db->trans_status() === FALSE) {
					$modelOutput['flag'] = 3; // Failure
				} else {
					$modelOutput['flag'] = 1; // Success
				}
			
			} else {
					$modelOutput['flag'] = 2;
			}
			return $modelOutput;
    }
	
	
	/**
	* @METHOD NAME 	: saveFormControlOptions()
	*
	* @DESC 		: TO SAVE THE FORM CONTROL OPTIONS 
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function saveFormControlOptions($getPostData)
    {
		if(!empty($getPostData['formControlsId'])){
			
			$rowData							= bindConfigTableValues($this->subTableNameStr1, 'CREATE', $getPostData);
			$rowData[$this->itemTableColumnRef] = $getPostData[$this->itemTableColumnReqRef];
			$insertId 							= $this->commonModel->insertQry($this->subTableName1, $rowData);

			$modelOutput['flag'] = 1; // Success
		}else{
			$modelOutput['flag'] = 2; // Failure
		}
		return $modelOutput;
	}
	
	
	/**
	* @METHOD NAME 	: updateFormControls()
	*
	* @DESC 		: TO UPDATE THE FORM CONTROLS DETAILS 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateFormControls($getPostData)
    {
		$deletedOptionChildIds = $getPostData['deletedOptionChildIds'];
        $id        		 = $getPostData['id'];
		$getListData     = $getPostData['selectOptionListArray'];
		
		$whereExistsQry = array(
								'LCASE(field_name)' => strtolower($getPostData['fieldName']),
								'id!='				=> $getPostData['id'],
								'branch_id'			=> $this->currentbranchId
							);
		
		$totRows = $this->commonModel->isExists($this->tableName,$whereExistsQry);
		               
		if(0 == $totRows) {
			
		// Adding Transaction Start
		$this->app_db->trans_start();
		
			
			$whereQry = array('id'=>$id);
			$rowData  = bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
			$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);
           
		   
		   // CHECK IN-ACTIVE CONDITION 
		   if($getPostData['status']==2){   // IN-ACTIVE 
				$checkExists = $this->checkFormControlIdExists($getPostData['id']);
				
				if($checkExists == 1)
				{
					$modelOutput['flag'] = 3; // FAILURE
					return $modelOutput;
				}
		   }
		   
		   
		   // IF SELECT BOX 
		   if($getPostData['fieldTypeId']==2){ // SELECT BOX
		   
				// DELETE OPERATION
				if (count($deletedOptionChildIds) > 0) { // Child values
					foreach ($deletedOptionChildIds as $key => $value) {
						$passId  = array('id' => $value);
						$this->deleteFormControlOptions($passId);					
					}
				}
			
				// LIST DATA 
				foreach ($getListData as $key => $value) {
					$value['formControlsId'] = $id;
					if (empty($value['id'])) { // INSERT THE RECORD 
							$this->saveFormControlOptions($value);
					} else {
							$value['id'] = $value['id'];
							$this->updateFormControlOptions($value);
					}
				}
		   }
			
			// To Complete the Transaction
			$this->app_db->trans_complete();

			if ($this->app_db->trans_status() === FALSE) {
				$modelOutput['flag'] = 2; // Failure
			} else {
				$modelOutput['flag'] = 1; // Success
			}
		}else {
				$modelOutput['flag'] = 4; 
		}
        return $modelOutput;
    }
    
	
	/**
	* @METHOD NAME 	: updateFormControlOptions()
	*
	* @DESC 		: TO UPDATE THE FORM CONTROL OPTIONS 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateFormControlOptions($getPostData)
    {
		$whereQry = array('id' => $getPostData['id']);
		$rowData  = bindConfigTableValues($this->subTableNameStr1, 'UPDATE', $getPostData);
		$this->commonModel->updateQry($this->subTableName1, $rowData, $whereQry);
		$modelOutput['flag'] = 1; // Success
		return $modelOutput;
	}
	
	
	/**
	* @METHOD NAME 	: deleteFormControls()
	*
	* @DESC 		: TO DELETE THE FORM CONTROLS 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function deleteFormControls($getPostData)
    {
		$this->app_db->trans_start();
		
			// DELETE IN FORM CONTROLS
			$whereQry  = array('id' => $getPostData['id']);			
			$this->commonModel->deleteQry(UDF_MASTER_FORM_CONTROLS,$whereQry);
		
			// DELETE IN FORM CONTROL OPTIONS 
			$whereQry  = array('form_controls_id' => $getPostData['id']);
			$this->commonModel->deleteQry(UDF_MASTER_FORM_CONTROLS_OPTIONS, $whereQry);
		
		$this->app_db->trans_complete();
		
        if ($this->app_db->trans_status() === FALSE) {
            $modelOutput['flag'] = 2; // Failure
        } else {
            $modelOutput['flag'] = 1; // Success
        }
        return $modelOutput;
    }
	
	
	/**
	* @METHOD NAME 	: deleteFormControlOptions()
	*
	* @DESC 		: TO DELETE THE FORM CONTROL OPTIONS 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function deleteFormControlOptions($getPostData)
    {
		$whereQry  = array('id' => $getPostData['id']);			
		$this->commonModel->deleteQry(UDF_MASTER_FORM_CONTROLS_OPTIONS,$whereQry);
		$modelOutput['flag'] = 1; // Success
        return $modelOutput;
    }
	
	
	/**
	* @METHOD NAME 	: editFormControls()
	*
	* @DESC 		: TO EDIT THE FORM CONTROLS 
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editFormControls($id)
    {
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $id);
		$this->app_db->select($rowData);
		/*
        $this->app_db->select(array(
								'id',
								'field_type_id',
								'field_label',
								'field_name',
								'required_field',								
								'status'								
								));
		*/
        $this->app_db->from($this->tableName);
        $this->app_db->where('id', $id);
        $this->app_db->where('is_deleted', '0');
		$this->app_db->where('branch_id', $this->currentbranchId);
        $rs = $this->app_db->get();
        return  $rs->result_array();
    }
	
	
    /**
	* @METHOD NAME 	: getFormControlsList()
	*
	* @DESC 		: TO GET FORM CONTROL LIST 
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getFormControlsList($getPostData,$downloadFlag='')
    {		
		$query 		= 'SELECT * FROM
						(SELECT	
							a.id, 
							a.field_type_id,
							a.field_label,
							a.field_name,
							a.required_field,
							a.updated_on,
							a.status,
							a.sap_id,
							a.posting_status,
							a.sap_error,
							
							 /* Master FIELD DATA */
							mft.field_type_name,
							
							/* Master STATIC DATA */
							msd.name as status_name
							   
						FROM '.UDF_MASTER_FORM_CONTROLS.' as a
						LEFT JOIN '.UDF_MASTER_FIELD_TYPE.' as mft 
							ON mft.id = a.field_type_id
						LEFT JOIN '.MASTER_STATIC_DATA.' as msd
								ON msd.master_id = a.status
							
						WHERE a.is_deleted = 0
						AND  msd.type = "COMMON_STATUS"
						AND a.branch_id = '.$this->currentbranchId.'
						)  as a
					
					WHERE id != 0 ';
		
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
				if ($fieldValue!="") {
					if($fieldName=="fieldLabel") {
						$query.=' AND LCASE(field_label) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					}else if($fieldName=="fieldName") {
						$query.=' AND LCASE(field_name) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					}
					else if($fieldName=="status"){
						$query.=' AND status = "'.$fieldValue.'"';					   
					}
					else if($fieldName=="fieldTypeId"){		
						$query.=' AND field_type_id = "'.$fieldValue.'"';					   
					}else if ($fieldName == "sapId") {
						$this->app_db->where(UDF_MASTER_FORM_CONTROLS.'.sap_id', $fieldValue);
					} else if ($fieldName == "postingStatus") {
						$this->app_db->where(UDF_MASTER_FORM_CONTROLS.'.posting_status', $fieldValue);
					}
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('UDF_MASTER_FORM_CONTROLS')['columns_list'],$fieldName);
				
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
		$totalRecords 	   = count($searchResultData);
		$searchResultSet   = getOffSetRecords($searchResultData,$offset,$limit);

		
		// MODEL DATA 
        $modelData['searchResults'] = $searchResultSet;
        $modelData['totalRecords']  = $totalRecords;
        return $modelData;
    }
	
	
	/**
	* @METHOD NAME 	: getFormControlOptionsList()
	*
	* @DESC 		: TO GET THE FORM CONTROL OPTIONS LIST 
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function getFormControlOptionsList($id){
		$this->app_db->select(array('id','options_field_label','default_selected'));
        $this->app_db->from($this->subTableName1);
        $this->app_db->where('form_controls_id', $id);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        return $rs->result_array();
	}

	
//////////////////////////////////////// SCREEN MAPPING ////////////////////////////////////////////////////
	/**
	* @METHOD NAME 	: getFormControlsDetailsByScreen()
	*
	* @DESC 		: TO GET THE FORM CONTROL DETAILS 
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getFormControlsDetailsByMappingScreen($getPostData)
    {
        $this->app_db->select(array(
								'id',
								'document_type_id',
								'form_controls_id'
								));
        $this->app_db->from(UDF_SCREEN_MAPPING);
        $this->app_db->where('is_deleted', '0');
		$this->app_db->where('branch_id', $this->currentbranchId);
		$this->app_db->where('document_type_id', $getPostData['documentTypeId']);
        $rs = $this->app_db->get();
        return  $rs->result_array();
    }
	
	
	/**
	* @METHOD NAME 	: checkFormControlIdExists()
	*
	* @DESC 		: TO CHECK THE FORM CONTROL ID EXISTS 
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function checkFormControlIdExists($formControlId)
    {
		$query = 'SELECT	
						a.id, 
						a.form_controls_id
						
					FROM '.UDF_SCREEN_MAPPING.' AS a
					WHERE 
						a.is_deleted = 0
					AND 
						 CONCAT(",",a.form_controls_id,",") like "%,'.$formControlId.',%"
					AND 
						a.branch_id = '.$this->currentbranchId;
				
		$rs				   = $this->app_db->query($query);
		
		$totalRows  = $rs->num_rows();
		
		if($totalRows>0){
			return 1;
		}else{
			return 0;
		}
		
    }
	
	
	/**
	* @METHOD NAME 	: getAllFormControls()
	*
	* @DESC 		: TO GET THE FORM CONTROLS LIST 
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getAllFormControls($formControlsIds='')
    {			
		$query = 'SELECT	
						a.id, 
						a.field_type_id,
						a.field_label,
						a.field_name,
						a.required_field,
						a.status
						
					FROM '.UDF_MASTER_FORM_CONTROLS.' AS a
					WHERE 
						a.is_deleted = 0
					AND 
						a.branch_id = '.$this->currentbranchId;
		
		if(!empty($formControlsIds)){
			$query.=' AND id in ('.$formControlsIds.') ORDER BY FIELD(id,'.$formControlsIds.')';	
		}else{
			$query.=' AND status = 1';	
		}
		
		$rs				   = $this->app_db->query($query);
		return $rs->result_array();
    }
	
	
	/**
	* @METHOD NAME 	: updateScreenMapping()
	*
	* @DESC 		: TO UPDATE THE SCREEN MAPPING FUNCTIONALITY 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateScreenMapping($getPostData)
    {
		if(empty($getPostData['screenMappingId'])){
			$rowData  = bindConfigTableValues($this->subTableNameStr2, 'CREATE', $getPostData);
			$rowData['branch_id'] = $this->currentbranchId;
			$insertId = $this->commonModel->insertQry($this->subTableName2, $rowData);
		}else{
			$whereQry = array('id'=>$getPostData['screenMappingId']);		
			$rowData  = bindConfigTableValues($this->subTableNameStr2, 'UPDATE', $getPostData);
			$this->commonModel->updateQry($this->subTableName2, $rowData, $whereQry);
		}
		$modelOutput['flag'] = 1;
		return $modelOutput;
    }
	
	
////////////////////////////////////////END OF SCREEN MAPPING /////////////////////////////

/////////////////////////////////////// ON SCREEN BINDING /////////////////////////////
	/**
	* @METHOD NAME 	: getUdfValueFromTable()
	*
	* @DESC 		: TO GET UDF FROM THE TABLE NAME
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getUdfValueFromTable($getPostData)
    {
        $this->app_db->select(array(
								'id',
								'udf_fields',
								));
        $this->app_db->from($getPostData['tableName']);
        $this->app_db->where('is_deleted', '0');
        $this->app_db->where('id', $getPostData['id']);
        $rs = $this->app_db->get();
        return  $rs->result_array();
    }
	
	
	/**
	* @METHOD NAME 	: getOnScreenFormControls()
	*
	* @DESC 		: TO GET THE ON SCREEN FORM CONTROL DETAILS 
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function getOnScreenFormControls($getPostData){
		
		$documentTypeName  = $getPostData['documentTypeName'];
		$id 		 = $getPostData['id']; 

		$screenList		= $this->config->item('SCREEN_NAMES');
		$screenDetails 	= $screenList[$documentTypeName];
		
		$passData['documentTypeId']	= $screenDetails['id'];
		$passData['tableName']	= $screenDetails['tableName'];
		$passData['id']			= $id;
		
		$formControlInfo 		= array();
		$screenFormControlList 	= $this->getFormControlsDetailsByMappingScreen($passData);
		$formControlDetails		= "";
		
		if(empty($id)){ // FOR SAVE OPERATION
			
			if(isset($screenFormControlList[0]['form_controls_id']) && !empty($screenFormControlList[0]['form_controls_id'])){
				$formControlId 		= $screenFormControlList[0]['form_controls_id'];
				$formControlDetails = $this->nativeModel->getAllFormControls($formControlId);
				
				foreach($formControlDetails as $selectedFormKey => $selectedValue){
					
					$formControlValue = "";
					$getOptionsList 		= $this->getFormControlOptionsList($selectedValue['id']);
					$formControlDetails[$selectedFormKey]['selectOptionListArray'] = $getOptionsList;
					
					if($selectedValue['field_type_id']==2){
						foreach($getOptionsList as $optionKey => $optionValue){
							if($optionValue['default_selected']==1){
								$formControlValue = $optionValue['options_field_label'];
								break;
							}
						}
					}
					$formControlDetails[$selectedFormKey]['value'] = $formControlValue;
				}
				$formControlInfo = $formControlDetails;
			}		
			
		}else if(!empty($id)){ // FOR EDIT OPERATION
			
			$udfDetails 	 = $this->getUdfValueFromTable($passData);
			$udfValue 		 = $udfDetails[0]['udf_fields'];		
			$arrayUdfData 	 = array();
			$screenFormControlIds = array();
			if(!empty($udfValue)){
			
				$arrayUdfData 	 	  = json_decode($udfValue,true);
				
				
				if(isset($screenFormControlList[0]['form_controls_id']) && 
						!empty($screenFormControlList[0]['form_controls_id'])){
						
					$screenFormControlIds = explode(",",$screenFormControlList[0]['form_controls_id']);	
					$rowFormControlIds 	  = array_column($arrayUdfData,'formControlId');
					
					foreach($screenFormControlIds as $arrayKey => $arrayValue){
					
						
					
						$formControlDetails = $this->nativeModel->getAllFormControls($arrayValue);
						$formControlValue = "";
						
						// Option List 
						$getOptionsList  					= $this->getFormControlOptionsList($arrayValue);
						$formControlDetails[0]['selectOptionListArray'] = $getOptionsList;						
						
						if(in_array($arrayValue,$rowFormControlIds)){ // RECORD EXISTS 
							$rowRecordDetails  = $this->findArrayRecord($arrayValue,$arrayUdfData);
							$formControlValue = $rowRecordDetails['value'];
						}else{
							// APPLICABLE FOR NEW RECORD ONLY 
							if($formControlDetails[0]['field_type_id']==2){
								foreach($getOptionsList as $optionKey => $optionValue){
									if($optionValue['default_selected']==1){
										$formControlValue = $optionValue['options_field_label'];
										break;
									}
								}								
							}							
						}		
						$formControlDetails[0]['value'] 	= $formControlValue;						
						$screenFormControlIds[$arrayKey] = $formControlDetails[0];
					}
				}
			}
			$formControlInfo = $screenFormControlIds;
		}
		return $formControlInfo;
	}
	
	
	/**
	* @METHOD NAME 	: findArrayRecord()
	*
	* @DESC 		: TO FIND THE ARRAY INDEX RECORD
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	function findArrayRecord($getKey,$getArray){
		foreach($getArray as $key => $value ){
			if($value['formControlId']==$getKey){
				return $getArray[$key];
			}
		}
	}
	

/////////////////////////////////////// END OF SCREEN BINDING /////////////////////////////

	
}
?>