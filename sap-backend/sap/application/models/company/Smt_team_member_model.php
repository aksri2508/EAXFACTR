<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Smt_team_member_model.php
* @Class  			 : Smt_team_member_model
* Model Name         : Smt_team_member_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 22 MAY 2019
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Smt_team_member_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'SMT_TEAM_MEMBERS';
		$this->tableName 	= constant($this->tableNameStr);
    }
	
	
	/**
	* @METHOD NAME 	: saveTeamMember()
	*
	* @DESC 		: TO SAVE THE TEAM MEMBER
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveTeamMember($getPostData)
    {	
		// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
		$this->app_db->select(array(SMT_TEAM_MEMBERS.'.id',
									SMT_TEAM_MEMBERS.'.team_id',
									SMT_TEAM.'.team_name')
							 );
        $this->app_db->from(SMT_TEAM_MEMBERS);
		$this->app_db->join(SMT_TEAM, SMT_TEAM.'.id = '.SMT_TEAM_MEMBERS.'.team_id', '');
        $this->app_db->where(SMT_TEAM_MEMBERS.'.emp_id', $getPostData['empId']);
        $this->app_db->where(SMT_TEAM_MEMBERS.'.status', '1');
        $rs = $this->app_db->get();
        $resultArray = $rs->result_array();
		$totRows     = $rs->num_rows();
		
        if (0 == $totRows) {			
			
			$rowData 			  = bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);
			$rowData['branch_id'] = $this->currentbranchId;
			$insertId 			  = $this->commonModel->insertQry($this->tableName, $rowData);
            $modelOutput['flag']  = 1;
        } else {
			// GET TEAM NAME DETAILS 
            $modelOutput['teamName'] = $resultArray[0]['team_name'];
            $modelOutput['flag'] 	 = 2;
        }
        return $modelOutput;
    }
    
    
	/**
	* @METHOD NAME 	: updateTeamMember()
	*
	* @DESC 		: TO UPDATE THE TEAM MEMBER
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateTeamMember($getPostData)
    {
		$whereExistsQry = array(
								 'emp_id'	=> strtolower($getPostData['empId']),
								 'status'	=> 1,
								 'id!='		=> $getPostData['id'],
								);	
		
		$totRows = $this->commonModel->isExists(SMT_TEAM_MEMBERS,$whereExistsQry);
		               
        if(0 == $totRows) {
			
			$this->app_db->trans_start();
				
				$whereQry = array('id'=>$getPostData['id']);	
				$rowData  = bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
				$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);
				$modelOutput['flag'] = 1;
				
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
	* @METHOD NAME 	: editTeamMember()
	*
	* @DESC 		: TO EDIT THE TEAM MEMBER DETAILS 
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editTeamMember($getPostData)
    {
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $getPostData['id']);
		$this->app_db->select($rowData);
        //$this->app_db->select(array('id','team_id','emp_id','status'));
        $this->app_db->from(SMT_TEAM_MEMBERS);
        $this->app_db->where('id', $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        return $rs->result_array();
    }
    
	
    /**
	* @METHOD NAME 	: getTeamMemberList()
	*
	* @DESC 		: TO GET THE TEAM MEMBER LIST
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getTeamMemberList($getPostData)
    {
		// CHECK REPORTING MANAGER 
		$chkReportingManager = $this->commonModel->checkReportingManager($this->currentUserId);
		$getTeamListRs		 = $this->commonModel->getTeamListByHead($this->currentUserId);
		
        // SELECT 
        $this->app_db->select(array(
								SMT_TEAM_MEMBERS.'.id',
								SMT_TEAM_MEMBERS.'.team_id',
								SMT_TEAM_MEMBERS.'.emp_id',
								SMT_TEAM_MEMBERS.'.status',
								SMT_TEAM_MEMBERS.'.posting_status',
								SMT_TEAM_MEMBERS.'.sap_id',
								SMT_TEAM_MEMBERS.'.sap_error',
								'CONCAT('.EMPLOYEE_PROFILE.'.first_name," ",'.EMPLOYEE_PROFILE.'.last_name
									) as employee_name',
								SMT_TEAM.'.team_name',
								EMPLOYEE_PROFILE.'.profile_img',
								EMPLOYEE_PROFILE.'.emp_code',
								MASTER_STATIC_DATA.'.name as statusName',
							));
							
        $this->app_db->from(SMT_TEAM_MEMBERS);
		$this->app_db->join(EMPLOYEE_PROFILE, EMPLOYEE_PROFILE.'.id = '.SMT_TEAM_MEMBERS.'.emp_id', '');
		$this->app_db->join(SMT_TEAM, SMT_TEAM.'.id = '.SMT_TEAM_MEMBERS.'.team_id', '');
		
		$this->app_db->join(MASTER_STATIC_DATA, MASTER_STATIC_DATA.'.master_id = '.SMT_TEAM_MEMBERS.'.status', '');
		
        $this->app_db->where(SMT_TEAM_MEMBERS.'.is_deleted', '0');
		$this->app_db->where(MASTER_STATIC_DATA.'.type', 'COMMON_STATUS');
        
	
		if($this->currentAccessControlId!=1){		
			if($chkReportingManager){
				if($getTeamListRs->num_rows()>0){
					$teamArray 	= $getTeamListRs->result_array();
					$teamIds  	=  array_column($teamArray, 'id');
					$this->app_db->where_in(SMT_TEAM_MEMBERS.'.team_id',$teamIds);
				}			
			}else{
				$this->app_db->where(SMT_TEAM_MEMBERS.'.emp_id', $this->currentUserId);
			}
		}
		
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                if ($fieldValue!="") {
					if($fieldName=="teamName") {
						$this->app_db->like('LCASE(team_name)', strtolower($fieldValue));
					}else if($fieldName=="status"){
						$this->app_db->where(SMT_TEAM_MEMBERS.'.status', $fieldValue);
					}else if($fieldName=="employeeName"){
						$this->app_db->like('LCASE(CONCAT('.EMPLOYEE_PROFILE.'.first_name,
						'.EMPLOYEE_PROFILE.'.last_name,'.EMPLOYEE_PROFILE.'.emp_code))', strtolower($fieldValue));
					}else if ($fieldName == "sapId") {
						$this->app_db->where(SMT_TEAM_MEMBERS.'.sap_id', $fieldValue);
					} else if ($fieldName == "postingStatus") {
						$this->app_db->where(SMT_TEAM_MEMBERS.'.posting_status', $fieldValue);
					}
					
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('SMT_TEAM_MEMBERS')['columns_list'],$fieldName);
				
			if(!empty($fieldName)){
				$this->app_db->order_by($fieldName, $sortOrder);
			}
			
			
        }else{
			$this->app_db->order_by(SMT_TEAM_MEMBERS.'.updated_on', 'desc');
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
        
		// FRAME PHOTO URL 
		foreach($searchResultSet as $key => $value){
			$value['userImgUrl'] 	= getFullImgUrl('employee',$value['profile_img']);
			$searchResultSet[$key] 	= $value;	
		}
		
		// MODEL DATA 
        $modelData['searchResults'] = $searchResultSet;
        $modelData['totalRecords']  = $totalRecords;
        return $modelData;
    }
	
	
	/**
	* @METHOD NAME 	: getMyTeamMemberList()
	*
	* @DESC 		: TO GET THE MY TEAM MEMBER LIST 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getMyTeamMemberList($getPostData)
	{
		$teamId 	   	= $getPostData['teamId'];
		$teamDetails  	= $this->getTeamDetails($getPostData);
		$teamHeadId  	= $teamDetails[0]['team_head_id'];
		$empArrayIds	= $this->commonModel->getGroupUsersByRM($teamHeadId);
		
		$empIds 		=  array_column($empArrayIds, 'id');
		
		if(empty($empIds)){
			return '';
		}
		
	//	print_r($empIds);
		
	    $this->app_db->select(array(
							EMPLOYEE_PROFILE.'.id',
							EMPLOYEE_PROFILE.'.emp_code',
							'CONCAT('.EMPLOYEE_PROFILE.'.first_name," ",'.EMPLOYEE_PROFILE.'.last_name) as employee_name'));
		$this->app_db->from(EMPLOYEE_PROFILE);	
		$this->app_db->where_in(EMPLOYEE_PROFILE.'.id',$empIds);		
		$this->app_db->where(EMPLOYEE_PROFILE.'.is_deleted', '0');
		$this->app_db->where(EMPLOYEE_PROFILE.'.status', '1'); 	// Show only active employees
		$rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
	
	/**
	* @METHOD NAME 	: getTeamDetails()
	*
	* @DESC 		: TO GET THE TEAM DETAILS 
	* @RETURN VALUE : result_array() array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getTeamDetails($getPostData)
	{
		$teamId  = $getPostData['teamId'];
	    $this->app_db->select(array(
								SMT_TEAM.'.id',
								SMT_TEAM.'.team_name',
								SMT_TEAM.'.team_head_id'
								)
							);
		$this->app_db->from(SMT_TEAM);
		$this->app_db->where(SMT_TEAM.'.id', $teamId);
		//$this->app_db->where(EMPLOYEE_PROFILE.'.status', '1'); 	// Show only active employees
		$rs = $this->app_db->get();
	    return $rs->result_array();
	}
	
}
?>