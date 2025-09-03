<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_document_numbering_model.php
* @Class  			 : Master_document_numbering_model
* Model Name         : Master_document_numbering_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Master_document_numbering_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'MASTER_DOCUMENT_NUMBERING';
		$this->tableName 	= constant($this->tableNameStr);
    }
	
	
	/**
	* @METHOD NAME 	: saveDocumentNumbering()
	*
	* @DESC 		: TO SAVE THE DOCUMENT NUMBERING 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveDocumentNumbering($getPostData)
    {
		$rowData  = bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);
		
		$this->app_db->trans_start();
		
		// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
		$whereExistsQry = array(
							  'LCASE(document_numbering_name)' => strtolower($getPostData['documentNumberingName']),
							);
		$chkRecord		= $this->commonModel->isExists(MASTER_DOCUMENT_NUMBERING,$whereExistsQry);
	
        if (0 == $chkRecord) {

			
			// CHECK WHETHER THE SERIES EXISTS. 			
			// $whereExistsQry = array(
			// 	'LCASE(document_numbering_name)' => strtolower($getPostData['documentNumberingName']),
			// );
			// $chkRecord		= $this->commonModel->isExists(MASTER_DOCUMENT_NUMBERING,$whereExistsQry);
			
			// $this->db->where('$accommodation >=', minvalue);
			// $this->db->where('$accommodation <=', maxvalue);


			// print_r($rowData);exit;

           // To check data validation.
			$dataStatus = $this->checkDataValidOrNot($rowData);
            if($dataStatus == 0){
				$modelOutput['flag'] = 3;
				return $modelOutput;
			}
			else{
				// To check data series exists.
				$dataSeriesStatus = $this->checkDataSeriesExist($rowData);

				if($dataSeriesStatus == 0){
					$modelOutput['flag'] = 4;
					return $modelOutput;
				}
				else{
					$insertId	= $this->commonModel->insertQry($this->tableName, $rowData);
				}
			}
					  
        } else {
            $modelOutput['flag'] = 2;
			return $modelOutput;
        }
		$this->app_db->trans_complete();
		
		// Check the transaction status
		if ($this->app_db->trans_status() === FALSE) {
			$modelOutput['flag'] = 2; // Failure
		} else {
			$modelOutput['flag'] = 1; // Success
		}
		
        return $modelOutput;
    }
    
    
	/**
	* @METHOD NAME 	: updateDocumentNumbering()
	*
	* @DESC 		: TO UPDATE THE 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function updateDocumentNumbering($getPostData)
	{
		$rowData = bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
		$whereExistsQry = array(
			'LCASE(document_numbering_name)'	=> strtolower($getPostData['documentNumberingName']),
			'id!='				 	  		  	=> $getPostData['id'],
		);

		$totRows = $this->commonModel->isExists(MASTER_DOCUMENT_NUMBERING, $whereExistsQry);


		if (0 == $totRows) {

			// To check data validation.
			$dataStatus = $this->checkDataValidOrNot($rowData, $getPostData['id']);

			if ($dataStatus == 0) {
				$modelOutput['flag'] = 3;
			} else {


				$whereQry = array('id' => $getPostData['id']);
				$rowData['continue_series'] = 1;
				$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);
				$modelOutput['flag'] = 1;
			}
		} else {
			$modelOutput['flag'] = 2;
		}
		return $modelOutput;
	}


	/**
	* @METHOD NAME 	: checkDataValidOrNot()
	*
	* @DESC 		: TO DATA VALIDATION CHECKS. 
	* @RETURN VALUE : $returnVal array
	* @PARAMETER 	: $rowData array, $id nubmer
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function checkDataValidOrNot($rowData, $id = null) 
    {
		// On Insert Master.
		if($id == null){
			if (
				($rowData['last_number'] < $rowData['first_number']) ||
				($rowData['next_number'] < $rowData['first_number']) ||
				($rowData['next_number'] > ($rowData['last_number']))

			) {
				$returnVal = 0;
			}
			else{
				$returnVal = 1;
			}
			return $returnVal;

		}
		else{ // On Update Master.
			
			$this->app_db->select(["document_type_id","document_numbering_name","first_number","next_number","last_number","prefix","suffix"]);
			$this->app_db->from($this->tableName);
			$this->app_db->where('id', $id);
			$this->app_db->where('is_deleted', '0');
			$rs = $this->app_db->get();
	
			if($res = $rs->result_array()){
				$result = $res[0];
				// print_r($result);exit;
				$next_number = $result['next_number'];
	
				if ( // Post data last_number checks with db next number.
					($rowData['last_number'] < $next_number) ||
					// ($rowData['next_number'] <= $next_number) || 
					($rowData['next_number'] > ($rowData['last_number'])) // post data next_number checks with post data last_number.
				) {
					$returnVal = 0;
				}
				else{
					$returnVal = 1;
				}
			}
			else{
					$returnVal = 0;
			}

			return $returnVal;
		}
		
    }

	/**
	* @METHOD NAME 	: checkDataSeriesExist()
	*
	* @DESC 		: TO Check Series conflict checks. 
	* @RETURN VALUE : $returnVal array
	* @PARAMETER 	: $rowData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function checkDataSeriesExist($rowData) 
    {
		$this->app_db->select(["id"]);
		$this->app_db->from($this->tableName);
		$this->app_db->where('prefix', $rowData['prefix']);
		$this->app_db->where('suffix', $rowData['suffix']);
		$this->app_db->where('document_type_id', $rowData['document_type_id']);
		$this->app_db->where('(('.$rowData['first_number'].' >= first_number and '.$rowData['first_number'].' <= last_number) || ('.$rowData['last_number'].' >= first_number and '.$rowData['last_number'].' <= last_number))');
		$this->app_db->where('is_deleted', '0');
		$rs = $this->app_db->get();

		if($rs->num_rows() >= 1){

				$returnVal = 0;
		}
		else{
				$returnVal = 1;
		}
		return $returnVal;
		
    }
    
	
    /**
	* @METHOD NAME 	: editDocumentNumbering()
	*
	* @DESC 		: TO EDIT 
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editDocumentNumbering($getPostData)
    {
        $rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $getPostData['id']);
		$this->app_db->select($rowData);
		$this->app_db->from($this->tableName);
		$this->app_db->where('id', $getPostData['id']);
		$this->app_db->where('is_deleted', '0');
		$rs = $this->app_db->get();
		return  $rs->result_array();
    }
    
	
	/**
	* @METHOD NAME 	: getAnalyticsCount()
	*
	* @DESC 		: TO ANALYTICS COUNT FOR ACTIVITY
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getAnalyticsCount($lockId)
    {
		// GET THE EMPLOYEE DISTRIBUTION LIST 
		$empDetails  			= $this->commonModel->getProfileInformation($this->currentUserId);
		$empDistributionRulesId = $empDetails['profileInfo'][0]['distribution_rules_id'];
		
		// SELECT QUERY 
		$this->app_db->select(array('id'));
		$this->app_db->from(MASTER_DOCUMENT_NUMBERING);	
		
			if($lockId !=''){
				$this->app_db->where('is_lock',$lockId);
			}
			
		$this->app_db->where('is_deleted',0);
		$this->app_db->where_in(MASTER_DOCUMENT_NUMBERING.'.branch_id', $this->currentUserBranchIds);
		
		
		// ADMIN CONDITION 
		if(($this->hierarchyMode==2) && ($this->currentAccessControlId!=1)){			
			$this->app_db->where_in(MASTER_DOCUMENT_NUMBERING.'.created_by', $this->currentgroupUsers,false);
		}
		
		$rs = $this->app_db->get();
		$searchResultData =  $rs->result_array();	
		
		
		if($this->hierarchyMode==1){		
				// CHECK HIRARACHY MODE
				if($this->currentAccessControlId!=1)
				{	// TO FIND THE DISTRIBUTION RULES RECORD
					$searchResultData  = processDistributionRulesData($searchResultData,$empDistributionRulesId);
				}
		}
		$totalRecords 		=  count($searchResultData);
		return $totalRecords;
    }
	
   
    /**
	* @METHOD NAME 	: getDocumentNumberingList()
	*
	* @DESC 		: TO GET THE 
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getDocumentNumberingList($getPostData)
    {
        // SELECT 
        $this->app_db->select(array(
								MASTER_DOCUMENT_NUMBERING.'.id',
								MASTER_DOCUMENT_NUMBERING.'.document_type_id',
								MASTER_DOCUMENT_NUMBERING.'.document_numbering_name',
								MASTER_DOCUMENT_NUMBERING.'.document_numbering_type',
								MASTER_DOCUMENT_NUMBERING.'.first_number',
								MASTER_DOCUMENT_NUMBERING.'.next_number',
								MASTER_DOCUMENT_NUMBERING.'.last_number',
								MASTER_DOCUMENT_NUMBERING.'.prefix',
								MASTER_DOCUMENT_NUMBERING.'.suffix',
								MASTER_DOCUMENT_NUMBERING.'.digits',
								MASTER_DOCUMENT_NUMBERING.'.remarks',
								MASTER_DOCUMENT_NUMBERING.'.is_lock',
								MASTER_DOCUMENT_NUMBERING.'.branch_id',
								MASTER_DOCUMENT_NUMBERING.'.is_system_config',
								MASTER_DOCUMENT_NUMBERING.'.posting_status',
								MASTER_DOCUMENT_NUMBERING.'.sap_id',
								MASTER_DOCUMENT_NUMBERING.'.sap_error',								
								MASTER_BRANCHES.'.branch_code',
								MASTER_BRANCHES.'.branch_name',
								MASTER_STATIC_DATA.'.name as screen_name',
							));
							
        $this->app_db->from(MASTER_DOCUMENT_NUMBERING);
		$this->app_db->join(MASTER_BRANCHES , MASTER_BRANCHES.'.id = '.MASTER_DOCUMENT_NUMBERING.'.branch_id', 'left');
		$this->app_db->join(MASTER_STATIC_DATA, MASTER_STATIC_DATA.'.master_id = '.MASTER_DOCUMENT_NUMBERING.'.document_type_id', '');
        $this->app_db->where(MASTER_STATIC_DATA.'.type', 'DOCUMENT_TYPE');
        $this->app_db->where(MASTER_DOCUMENT_NUMBERING.'.branch_id', $this->currentbranchId);
	  
		$this->app_db->where(MASTER_DOCUMENT_NUMBERING.'.is_deleted', '0');
		
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                if ($fieldValue!="") {
					if($fieldName=="screenName") {
						$this->app_db->like('LCASE('.MASTER_STATIC_DATA.'.name)', strtolower($fieldValue));
					}else if($fieldName=="documentNumberingName") {
						$this->app_db->like('LCASE('.MASTER_DOCUMENT_NUMBERING.'.document_numbering_name)', strtolower($fieldValue));
					}else if($fieldName=="firstNumber"){
						$this->app_db->like('LCASE('.MASTER_DOCUMENT_NUMBERING.'.first_number)', strtolower($fieldValue));
					}else if($fieldName=="nextNumber"){
						$this->app_db->like('LCASE('.MASTER_DOCUMENT_NUMBERING.'.next_number)', strtolower($fieldValue));
					}else if($fieldName=="lastNumber"){
						$this->app_db->like('LCASE('.MASTER_DOCUMENT_NUMBERING.'.last_number)', strtolower($fieldValue));
					}else if($fieldName=="prefix"){
						$this->app_db->like('LCASE('.MASTER_DOCUMENT_NUMBERING.'.prefix)', strtolower($fieldValue));
					}else if($fieldName=="suffix"){
						$this->app_db->like('LCASE('.MASTER_DOCUMENT_NUMBERING.'.suffix)', strtolower($fieldValue));
					}else if($fieldName=="digits"){
						$this->app_db->where(MASTER_DOCUMENT_NUMBERING.'.digits', $fieldValue);
					}else if($fieldName=="remarks"){
						$this->app_db->like('LCASE('.MASTER_DOCUMENT_NUMBERING.'.remarks)', strtolower($fieldValue));
					}else if($fieldName=="isLock"){
						$this->app_db->where(MASTER_DOCUMENT_NUMBERING.'.is_lock', $fieldValue);
					}else if($fieldName=="branchName"){
						$this->app_db->like('LCASE(CONCAT('.MASTER_BRANCHES.'.branch_code,'.MASTER_BRANCHES.'.branch_name))', strtolower($fieldValue));
					}else if ($fieldName == "sapId") {
						$this->app_db->where(MASTER_DOCUMENT_NUMBERING.'.sap_id', $fieldValue);
					} else if ($fieldName == "postingStatus") {
						$this->app_db->where(MASTER_DOCUMENT_NUMBERING.'.posting_status', $fieldValue);
					}
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('MASTER_DOCUMENT_NUMBERING')['columns_list'],$fieldName);
				
			if(!empty($fieldName)){
				$this->app_db->order_by($fieldName, $sortOrder);
			}
        }else{
			$this->app_db->order_by(MASTER_DOCUMENT_NUMBERING.'.updated_on', 'desc');
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