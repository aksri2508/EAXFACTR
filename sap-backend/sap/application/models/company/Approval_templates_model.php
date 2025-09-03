<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Approval_templates_model.php
* @Class  			 : Approval_templates_model
* Model Name         : Approval_templates_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : -
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Approval_templates_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'APPROVAL_TEMPLATES';
		$this->tableName 	= constant($this->tableNameStr);
    }
    
    
    /**
	* @METHOD NAME 	: checkDataConstraints()
	*
	* @DESC 		: TO check the originatorId and documentId repeated 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
    **/
    public function checkDataConstraints($getPostData,$operationFlag)
    {	
        // Form the data and provide the output
        $orginatorId = explode(",",$getPostData['originatorId']);
        $documentID  = explode(",",$getPostData['documentId']);
               
        $frameArr = array();
        foreach($orginatorId as $orgKey => $orgValue){
            foreach($documentID as $docKey => $docValue){
                $frameArr[] = $orgValue."-".$docValue;
            }
        }

        $hasDuplicates = count($frameArr) > count(array_unique($frameArr)); 

        if($hasDuplicates == 0){
            $this->app_db->select(array(
                'id','orginator_document_id'
            ));
            $this->app_db->from(APPROVAL_TEMPLATES_VALIDATION);
            $this->app_db->where_in(APPROVAL_TEMPLATES_VALIDATION.'.orginator_document_id',$frameArr);
            $this->app_db->where('is_deleted', '0');
            $rs = $this->app_db->get();
            $totRows = $rs->num_rows();
        
            if($totRows > 0) { // Exists
                $flag = 1;
            }else{
                $flag = 0;
            }
        }else{
            $flag = 2 ;
        }
        $outputData['flag']             = $flag;
        $outputData['frameOrgDocArray'] = $frameArr;
        return $outputData;
    }


	/**
	* @METHOD NAME 	: saveApprovalTemplate()
	*
	* @DESC 		: TO SAVE THE APPROVAL TEMPLATE
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveApprovalTemplate($getPostData)
    {	
		$rowData  = bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);
        
        $dataChkOutput = $this->checkDataConstraints($getPostData,1);

        if($dataChkOutput['flag'] == 1) { // Data for Orginator and Template already exists 
            $modelOutput['flag'] = 3;
            return $modelOutput;
        }else if($dataChkOutput['flag'] == 2){ // Wrong data feeded
            $modelOutput['flag'] = 4;
            return $modelOutput;
        }

        // CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
		$whereExistsQry = array(
								  'LCASE(template_name)' => strtolower($getPostData['templateName']),
							   );
							
        $chkRecord 		= $this->commonModel->isExists(APPROVAL_TEMPLATES,$whereExistsQry);
       
        $this->app_db->trans_start();
        if (0 == $chkRecord) {
		
            $insertId			 = $this->commonModel->insertQry($this->tableName, $rowData);
            $getPostData['id']   = $insertId;
            $this->processApprovalTemplateValidation($getPostData,$dataChkOutput,1);
            $modelOutput['flag'] = 1;
           
        $this->app_db->trans_complete();    

        } else {
            $modelOutput['flag'] = 2;
        }
        return $modelOutput;
    }
    

    /**
	* @METHOD NAME 	: processApprovalTemplateValidation()
	*
	* @DESC 		: TO PROCESS THE APPROVAL TEMPLATE VALIDATION
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function processApprovalTemplateValidation($getPostData,$dataChkOutput,$operationFlag)
    {
        if($operationFlag==1){ // INSERT 
            foreach($dataChkOutput['frameOrgDocArray'] as $key => $value){
                $rowData['approval_template_id'] = $getPostData['id'];
                $rowData['orginator_document_id'] = $value;
                $insertId			 = $this->commonModel->insertQry(APPROVAL_TEMPLATES_VALIDATION, $rowData);
            }
        }
    }
    
    
	/**
	* @METHOD NAME 	: updateApprovalTemplate()
	*
	* @DESC 		: TO UPDATE THE APPROVAL TEMPLATE 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateApprovalTemplate($getPostData)
    {

        // Check new record and old record Orginator id and Document Id are same 
        $getOldRecords   = $this->editApprovalTemplate($getPostData);

        $oldOrginatorId = explode(",",$getOldRecords[0]['originator_id']);
        $oldDocumentId  = explode(",",$getOldRecords[0]['document_id']);

        $newOrginatorId = explode(",",$getPostData["originatorId"]);
        $newDocumentId  = explode(",",$getPostData["documentId"]);

        sort($oldOrginatorId);
        sort($oldDocumentId);
        sort($newOrginatorId);
        sort($newDocumentId);

        $implodeOldOrginatorId = implode(",",$oldOrginatorId);
        $implodoldDocumentId = implode(",",$oldDocumentId);
        $implodenewOrginatorId = implode(",",$newOrginatorId);
        $implodenewDocumentId = implode(",",$newDocumentId);
        

		$whereExistsQry = array(
								 'LCASE(template_name)'  => strtolower($getPostData['templateName']),
								 'id!='				     => $getPostData['id'],
								);	
								
		$totRows		= $this->commonModel->isExists(APPROVAL_TEMPLATES,$whereExistsQry);
		               
        if(0 == $totRows) {
			$whereQry 	= array('id'=>$getPostData['id']);
			$rowData 	= bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
			$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);
		    $modelOutput['flag'] = 1;
        } else {
            $modelOutput['flag'] = 2;
        }
        return $modelOutput;
    }
    
	
    /**
	* @METHOD NAME 	: editApprovalTemplate()
	*
	* @DESC 		: TO EDIT THE APPROVAL TEMPLATE
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editApprovalTemplate($getPostData)
    {
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $getPostData['id']);
        $this->app_db->select($rowData);
        $this->app_db->from(APPROVAL_TEMPLATES);
        $this->app_db->where('id', $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        return $rs->result_array();
    }
    
	
    /**
	* @METHOD NAME 	: getApprovalTemplateList()
	*
	* @DESC 		: TO GET THE APPROVAL TEMPLATE LIST
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getApprovalTemplateList($getPostData)
    {
        // SELECT 
        $this->app_db->select(array(
                                    APPROVAL_TEMPLATES.'.id',
                                    APPROVAL_TEMPLATES.'.template_name',
                                    APPROVAL_TEMPLATES.'.template_description',
                                    APPROVAL_TEMPLATES.'.originator_id',
                                    APPROVAL_TEMPLATES.'.document_id',
                                    APPROVAL_TEMPLATES.'.approval_stages_id',
                                    APPROVAL_TEMPLATES.'.sap_id',
                                    APPROVAL_TEMPLATES.'.sap_error',
                                    APPROVAL_TEMPLATES.'.posting_status',
									APPROVAL_TEMPLATES.'.status',
                                    MASTER_STATIC_DATA.'.name as statusName',
                                ));
        $this->app_db->from(APPROVAL_TEMPLATES);
		$this->app_db->join(MASTER_STATIC_DATA, MASTER_STATIC_DATA.'.master_id = '.APPROVAL_TEMPLATES.'.status', '');
        $this->app_db->where(MASTER_STATIC_DATA.'.type', 'COMMON_STATUS');
        $this->app_db->where(APPROVAL_TEMPLATES.'.is_deleted', '0');
		
        
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                 if ($fieldValue!="") {
					if($fieldName=="templateName") {
						$this->app_db->like('LCASE(template_name)', strtolower($fieldValue));
					} else if($fieldName=="templateDescription") {
						$this->app_db->like('LCASE(template_description)', strtolower($fieldValue));
					} else if($fieldName=="status"){
						$this->app_db->where(APPROVAL_TEMPLATES.'.status', $fieldValue);
					} else if ($fieldName == "sapId") {
						$this->app_db->where(APPROVAL_TEMPLATES.'.sap_id', $fieldValue);
					} else if ($fieldName == "postingStatus") {
						$this->app_db->where(APPROVAL_TEMPLATES.'.posting_status', $fieldValue);
					}
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('APPROVAL_TEMPLATES')['columns_list'],$fieldName);
				
			if(!empty($fieldName)){
				$this->app_db->order_by($fieldName, $sortOrder);
			}
			
        }else{
			$this->app_db->order_by(APPROVAL_TEMPLATES.'.updated_on', 'desc');
		}
        
        // CLONE DB QUERY TO GET THE TOTAL RESULT BEFORE PAGINATION
        $tempdb       = clone $this->app_db;
        $totalRecords = $tempdb->count_all_results();
        
        // PAGINATION
        if (isset($tableProperties['first'])) {
            $offset = $tableProperties['first'];
            $limit  = $tableProperties['rows'];
        } else {
            $offset = 0;
            $limit  = $tableProperties['rows'];
        }
        $this->app_db->limit($limit, $offset);
        
        // GET RESULTS 		
        $searchResultSet = $this->app_db->get();
        $searchResultSet = $searchResultSet->result_array();
        
		// MODEL DATA 
        $modelData['searchResults'] = $searchResultSet;
        $modelData['totalRecords']  = $totalRecords;
        return $modelData;
    }
}
?>