<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Smt_team_model.php
* @Class  			 : Smt_team_model
* Model Name         : Smt_team_model
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
class Smt_team_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'SMT_TEAM';
		$this->tableName 	= constant($this->tableNameStr);
    }
	
	
	/**
	* @METHOD NAME 	: saveTeam()
	*
	* @DESC 		: TO SAVE THE TEAM DETAILS
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveTeam($getPostData)
    {	
		// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
		$whereExistsQry = array(
							  'LCASE(team_name)' => strtolower($getPostData['teamName']),
							);
							
		$chkRecord 		= $this->commonModel->isExists($this->tableName,$whereExistsQry);
		
        if (0 == $chkRecord) {
			
			$rowData = bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);
			$rowData['branch_id'] 	= $this->currentbranchId;
			$insertId 				= $this->commonModel->insertQry($this->tableName, $rowData);
			
            $modelOutput['flag'] = 1;
        } else {
            $modelOutput['flag'] = 2;
        }
        return $modelOutput;
    }
    
    
	/**
	* @METHOD NAME 	: updateTeam()
	*
	* @DESC 		: TO UPDATE THE TEAM
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateTeam($getPostData)
    {
		$whereExistsQry = array(
								 'LCASE(team_name)' => strtolower($getPostData['teamName']),
								'id!='				=> $getPostData['id'],
								);	
		
		$totRows = $this->commonModel->isExists($this->tableName,$whereExistsQry);
		               
        if(0 == $totRows) {
			
			$this->app_db->trans_start();

				// Status Checking 
				if($getPostData['status'] == 2) { // MAKE IN ACTIVE
					$this->deActivateTeamMembers($getPostData['id']);
				}
									
				$whereQry	= array('id'=>$getPostData['id']);		
								
				$rowData = bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
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
	* @METHOD NAME 	: deActivateTeamMembers()
	*
	* @DESC 		: TO De-Activate all the Team Members
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function deActivateTeamMembers($teamId)
    {
			$updateData = array(
									'is_deleted'	=> 1,
								);
								
			$whereQry	= array('team_id'=>$teamId);		
			
			$this->commonModel->updateQry(SMT_TEAM_MEMBERS,$updateData,$whereQry);
    }
	
	
    /**
	* @METHOD NAME 	: editTeam()
	*
	* @DESC 		: TO EDIT THE TEAM DETAILS 
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editTeam($getPostData)
    {
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $getPostData['id']);
        $this->app_db->select($rowData);
        //$this->app_db->select(array('id','team_name','team_head_id','remarks','status'));
        $this->app_db->from($this->tableName);
        $this->app_db->where('id', $getPostData['id']);
        $this->app_db->where('is_deleted', '0');
        $rs = $this->app_db->get();
        return $rs->result_array();
    }
    
   
    /**
	* @METHOD NAME 	: getTeamList()
	*
	* @DESC 		: TO GET THE TEAM LIST
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getTeamList($getPostData)
    {
        // SELECT 
        $this->app_db->select(array(
								SMT_TEAM.'.id',
								SMT_TEAM.'.team_name',
								SMT_TEAM.'.team_head_id',
								SMT_TEAM.'.remarks',
								SMT_TEAM.'.status',
								SMT_TEAM.'.posting_status',
								SMT_TEAM.'.sap_id',
								SMT_TEAM.'.sap_error',
								EMPLOYEE_PROFILE.'.profile_img',
								EMPLOYEE_PROFILE.'.emp_code as teamHeadCode',
								MASTER_STATIC_DATA.'.name as statusName',
								'CONCAT('.EMPLOYEE_PROFILE.'.first_name," ",'.EMPLOYEE_PROFILE.'.last_name
									) as team_head_name'
							));
							
        $this->app_db->from(SMT_TEAM);
		$this->app_db->join(EMPLOYEE_PROFILE, EMPLOYEE_PROFILE.'.id = '.SMT_TEAM.'.team_head_id', '');
		$this->app_db->join(MASTER_STATIC_DATA, MASTER_STATIC_DATA.'.master_id = '.SMT_TEAM.'.status', '');
        $this->app_db->where(SMT_TEAM.'.is_deleted', '0');
		 $this->app_db->where(MASTER_STATIC_DATA.'.type', 'COMMON_STATUS');
        
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
						$this->app_db->where(SMT_TEAM.'.status', $fieldValue);
					}else if($fieldName=="remarks"){					
						$this->app_db->like('LCASE(remarks)', strtolower($fieldValue));						
					}else if($fieldName=="teamHeadName"){					
						$this->app_db->like('LCASE(CONCAT('.EMPLOYEE_PROFILE.'.first_name,
						'.EMPLOYEE_PROFILE.'.last_name,'.EMPLOYEE_PROFILE.'.emp_code))', strtolower($fieldValue));
					}else if ($fieldName == "sapId") {
						$this->app_db->where(SMT_TEAM.'.sap_id', $fieldValue);
					} else if ($fieldName == "postingStatus") {
						$this->app_db->where(SMT_TEAM.'.posting_status', $fieldValue);
					}
					
					
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('SMT_TEAM')['columns_list'],$fieldName);
				
			if(!empty($fieldName)){
				$this->app_db->order_by($fieldName, $sortOrder);
			}
			
			
        }else{
			$this->app_db->order_by(SMT_TEAM.'.updated_on', 'desc');
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
			$value['teamHeadImgUrl'] 	= getFullImgUrl('employee',$value['profile_img']);
			$searchResultSet[$key] 	= $value;	
		}
		
		// MODEL DATA 
        $modelData['searchResults'] = $searchResultSet;
        $modelData['totalRecords']  = $totalRecords;
        return $modelData;
    }
	
	
	/**
	* @METHOD NAME 	: getTeamMembersCount()
	*
	* @DESC 		: TO GET THE TEAM MEMBERS DETAILS 
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getTeamMembersCount($teamId)
    {
        $this->app_db->select(array('id'));
        $this->app_db->from(SMT_TEAM_MEMBERS);
        $this->app_db->where('team_id', $teamId);
        $this->app_db->where('is_deleted', '0');
        $this->app_db->where('status', '1'); // Active
        $rs = $this->app_db->get();
        return $rs->num_rows();
    }

}
?>