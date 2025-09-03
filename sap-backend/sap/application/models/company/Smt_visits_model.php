<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Smt_visits_model.php
* @Class  			 : Smt_visits_model
* Model Name         : Smt_visits_model
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
class Smt_visits_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'SMT_VISITS';
		$this->tableName 	= constant($this->tableNameStr);
    }
	
	
	/**
	* @METHOD NAME 	: getLastEmpAttendanceRecord()
	*
	* @DESC 		: TO GET THE LAST EMPLOYEE ATTENDANCE RECORD 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function getEmpLastAttendanceRecord()
    {
		$empId  = $this->currentUserId;
		//$empId  = 5;
		// CHECK LAST RECORD IN EMPLOYEE ATTENDANCE TABLE
		$this->app_db->select(array(
								EMPLOYEE_ATTENDANCE.'.id',
								EMPLOYEE_ATTENDANCE.'.punch_in_datetime',
								EMPLOYEE_ATTENDANCE.'.punch_out_datetime',
							));
        $this->app_db->from(EMPLOYEE_ATTENDANCE);
		$this->app_db->where(EMPLOYEE_ATTENDANCE.'.is_deleted', '0');
		$this->app_db->where(EMPLOYEE_ATTENDANCE.'.branch_id', $this->currentbranchId);
		$this->app_db->where(EMPLOYEE_ATTENDANCE.'.emp_id', $empId);
		$this->app_db->order_by('id', 'desc');
		$this->app_db->limit(1, 0);		
		$rs = $this->app_db->get();
        $empAttendanceDetails = $rs->result_array();
		return $empAttendanceDetails;
	}
	
	
	/**
	* @METHOD NAME 	: getEmpLastVisitsRecord()
	*
	* @DESC 		: TO GET THE EMPLOYEE LAST VISIT RECORD 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function getEmpLastVisitsRecord()
    {
		$empId  = $this->currentUserId;
		//$empId  = 5;
		// CHECK LAST RECORD IN EMPLOYEE ATTENDANCE TABLE
		$this->app_db->select(array(
								SMT_VISITS.'.id',
								SMT_VISITS.'.check_out_datetime',
							));
        $this->app_db->from(SMT_VISITS);
		$this->app_db->where(SMT_VISITS.'.is_deleted', '0');
		$this->app_db->where(SMT_VISITS.'.branch_id', $this->currentbranchId);
		$this->app_db->where(SMT_VISITS.'.emp_id', $empId);
		$this->app_db->order_by('id', 'desc');
		$this->app_db->limit(1, 0);		
		$rs = $this->app_db->get();
        $empVisitsDetails = $rs->result_array();
		return $empVisitsDetails;		
	}
	
	
	/**
	* @METHOD NAME 	: checkInEmployeeVisit()
	*
	* @DESC 		: TO CHECK IN THE EMPLOYEE VISITS
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function checkInEmployeeVisit($getPostData)
    {	
		$empId  = $this->currentUserId;
		//$empId  = 5;
		
		$empAttendanceDetails = $this->getEmpLastAttendanceRecord();
		

		
		if(count($empAttendanceDetails) == 0 ){
		  $modelOutput['flag']	 = 2 ; //  "Please, punch in your attendance";
		  return $modelOutput;
		}else{
			$punchInDate	 	= date('Y-m-d',strtotime($empAttendanceDetails[0]['punch_in_datetime']));
			$checkInDate 		= date('Y-m-d',strtotime($getPostData['checkInDatetime']));
			if($punchInDate != $checkInDate){
				$modelOutput['flag']	 = 3 ; //  "Please, punch in your attendance for the current date";
				return $modelOutput;
			}else{
				if($empAttendanceDetails[0]['punch_out_datetime'] != "0000-00-00 00:00:00"){
					$modelOutput['flag']	 = 5; //  "You already punched out for the particular day.";
					return $modelOutput;
				}
			}
		}
		
		// CHECK THE LAST RECORD IN SMT VISITS TO CHECK THE CHECKOUT IS PENDING
		$smtVisitRecord  = $this->getEmpLastVisitsRecord();
		if(count($smtVisitRecord) > 0 ){
			if($smtVisitRecord[0]['check_out_datetime'] == "0000-00-00 00:00:00"){
				$modelOutput['flag']	 = 4 ; //  "Please, check out and try check in";
				return $modelOutput;
			}
		}
		
	
		// IF EVERYTHING SUCESS PROCEED 
		$employeeTeamDetails  = $this->commonModel->getEmployeeTeamDetails($empId);
		
		// INSERT EMPLOYEE ATTENDANCE
		$teamId	    = $employeeTeamDetails[0]['team_id'];
		/*
		$insertData = array(
								'team_id'				=> $teamId,
								'emp_id' 				=> $empId,
								'check_in_datetime'		=> $getPostData['checkInDatetime'],
								'check_in_latitude' 	=> $getPostData['checkInLatitude'],
								'check_in_longitude'	=> $getPostData['checkInLongitude'],
								'business_partner_id'	=> $getPostData['businessPartnerId'],
								'remarks'				=> $getPostData['remarks'],
								'branch_id'				=> $this->currentbranchId,
							);
		$insertId 				  = $this->commonModel->insertQry(SMT_VISITS,$insertData);
		*/
		
		// ROW DATA 
		$rowData  				=  bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);
		$rowData['branch_id'] 	=  $this->currentbranchId;
		$rowData['emp_id'] 		=  $empId;
		$rowData['team_id'] 	=  $teamId;
		$insertId 				=  $this->commonModel->insertQry($this->tableName, $rowData);
		
		$modelOutput['checkInId'] = $insertId;
		$modelOutput['flag'] 	  = 1;
        return $modelOutput;
    }
	
	
	/**
	* @METHOD NAME 	: getSmtVisitsById()
	*
	* @DESC 		: TO GET THE SMT VISITS BY ID 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function getSmtVisitsById($getPostData)
    {
		$flag		= 0;
		$checkInId  = $getPostData['checkInId'];
		
		// CHECK LAST RECORD IN EMPLOYEE ATTENDANCE TABLE
		$this->app_db->select(array(
								SMT_VISITS.'.id',
								SMT_VISITS.'.check_out_datetime',
							));
        $this->app_db->from(SMT_VISITS);
		$this->app_db->where(SMT_VISITS.'.is_deleted', '0');
		$this->app_db->where(SMT_VISITS.'.branch_id', $this->currentbranchId);
		$this->app_db->where(SMT_VISITS.'.id', $checkInId);
		$rs = $this->app_db->get();
        $empVisitsDetails = $rs->result_array();
		
		if($rs->num_rows()>0){ // NUM ROWS 
			if($empVisitsDetails[0]['check_out_datetime'] == "0000-00-00 00:00:00"){
				$flag = 1 ; // success 
			}else{
				$flag = 2; // ALREADY CHECKED OUT
			}	
		}else{
			$flag = 3; // INVALID RECORD PASSED 
		}
		return $flag;
	}
	
	
	/**
	* @METHOD NAME 	: checkOutEmployeeVisit()
	*
	* @DESC 		: TO CHECK OUT THE EMPLOYEE VISIT 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function checkOutEmployeeVisit($getPostData)
    {	
		$empId  = $this->currentUserId;
		
		$chkCheckInId =  $this->getSmtVisitsById($getPostData);
		
		// PUNCH OUT DETAILS 
		if($chkCheckInId!=1){
			if($chkCheckInId==2){
				$modelOutput['flag'] = 4;  // Already checked out for the day 
			}else if($chkCheckInId==3) {
				$modelOutput['flag'] = 3; // InValid Id Passed 
			}
			 return $modelOutput;
		}
			
		/*
		
			$updateData = array(
									'check_out_datetime'	=> $getPostData['checkOutDateTime'],
									'check_out_latitude' 	=> $getPostData['checkOutLatitude'],
									'check_out_longitude'	=> $getPostData['checkOutLongitude'],
									'remarks'				=> $getPostData['remarks'],
								);
			
			$this->commonModel->updateQry(SMT_VISITS,$updateData,$whereQry);
		
		*/
		$whereQry 	= array('id'=>$getPostData['checkInId']);	
		$rowData 	= bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
		$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);
			
		$modelOutput['flag'] = 1;
   
		return $modelOutput;
    }
    
	
	/**
	* @METHOD NAME 	: getEmployeeLastCheckInId()
	*
	* @DESC 		: TO GET THE EMPLOYEE LAST CHECK IN ID 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function getEmployeeLastCheckInId($getPostData)
    {
		$this->app_db->select(array(
								SMT_VISITS.'.id',
								SMT_VISITS.'.check_out_datetime',
							));
        $this->app_db->from(SMT_VISITS);
		$this->app_db->where(SMT_VISITS.'.is_deleted', '0');
		$this->app_db->where(SMT_VISITS.'.check_out_datetime', "0000-00-00 00:00:00");
		$this->app_db->where(SMT_VISITS.'.branch_id', $this->currentbranchId);
		$this->app_db->where(SMT_VISITS.'.emp_id',  $this->currentUserId);
		$this->app_db->order_by('id', 'desc');
		$this->app_db->limit(1, 0);		
		$rs = $this->app_db->get();
		
        $empCheckInDetails = $rs->result_array();
		
		if($rs->num_rows()>0){ // NUM ROWS 
			$modelOutput['flag'] = 1; 	
			$modelOutput['checkInId'] = $empCheckInDetails[0]['id']; 	
		}else{
			$modelOutput['flag'] = 2; 	
		}
		return $modelOutput;
	}

	
	
    /**
	* @METHOD NAME 	: getVisitList()
	*
	* @DESC 		: TO GET THE VISIT LIST
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getVisitList($getPostData)
    {
		// CHECK REPORTING MANAGER 
		$chkReportingManager = $this->commonModel->checkReportingManager($this->currentUserId);
		$getTeamListRs		 = $this->commonModel->getTeamListByHead($this->currentUserId);
		
        // SELECT 
        $this->app_db->select(array(
								SMT_VISITS.'.id',
								SMT_VISITS.'.team_id',
								SMT_VISITS.'.emp_id',
								SMT_VISITS.'.business_partner_id',
								SMT_VISITS.'.check_in_datetime',
								SMT_VISITS.'.check_out_datetime',
								SMT_VISITS.'.check_in_latitude',
								SMT_VISITS.'.check_in_longitude',
								SMT_VISITS.'.check_out_latitude',
								SMT_VISITS.'.check_out_longitude',
								SMT_VISITS.'.remarks',
								SMT_VISITS.'.posting_status',
								SMT_VISITS.'.sap_id',
								SMT_VISITS.'.sap_error',
								SMT_TEAM.'.team_name',
								EMPLOYEE_PROFILE.'.profile_img',
								EMPLOYEE_PROFILE.'.emp_code',
								'CONCAT('.EMPLOYEE_PROFILE.'.first_name," ",'.EMPLOYEE_PROFILE.'.last_name
									) as empName',
								BUSINESS_PARTNER.".partner_name",								
								BUSINESS_PARTNER.".partner_code"	
							));
							
        $this->app_db->from(SMT_VISITS);
		$this->app_db->join(EMPLOYEE_PROFILE, EMPLOYEE_PROFILE.'.id = '.SMT_VISITS.'.emp_id', '');
		$this->app_db->join(SMT_TEAM, SMT_TEAM.'.id = '.SMT_VISITS.'.team_id', '');
		$this->app_db->join(BUSINESS_PARTNER, BUSINESS_PARTNER.'.id = '.SMT_VISITS.'.business_partner_id', '');
		
        $this->app_db->where(SMT_VISITS.'.branch_id', $this->currentbranchId);
        $this->app_db->where(SMT_VISITS.'.is_deleted', '0');
		
		if($this->currentAccessControlId!=1){		
			if($chkReportingManager){
				
				if($getTeamListRs->num_rows()>0){
					$teamArray 	= $getTeamListRs->result_array();				
					$teamIds  	=  array_column($teamArray, 'id');
					$this->app_db->where_in(SMT_VISITS.'.team_id',$teamIds);
					$this->app_db->or_where(SMT_VISITS.'.emp_id',$this->currentUserId);
				}else{
					$this->app_db->where(SMT_VISITS.'.emp_id', $this->currentUserId);
				}				
			}else{
				$this->app_db->where(SMT_VISITS.'.emp_id', $this->currentUserId);
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
					}else if($fieldName=="empName"){
						$this->app_db->like('LCASE(CONCAT('.EMPLOYEE_PROFILE.'.first_name,
						'.EMPLOYEE_PROFILE.'.last_name,'.EMPLOYEE_PROFILE.'.emp_code))', strtolower($fieldValue));
					}else if($fieldName=="partnerName"){
						$this->app_db->like('LCASE(CONCAT('.BUSINESS_PARTNER.'.partner_name,
						'.BUSINESS_PARTNER.'.partner_code))', strtolower($fieldValue));
					}else if($fieldName=="checkInDatetime"){
						//$this->app_db->where("DATE(".SMT_VISITS.'.check_in_datetime'.")>=", $fieldValue);
					}else if($fieldName=="checkOutDatetime"){
						//$this->app_db->where("DATE(".SMT_VISITS.'.check_out_datetime'.")<=", $fieldValue);
					}else if($fieldName=="remarks") {
						$this->app_db->like('LCASE('.SMT_VISITS.'.remarks)', strtolower($fieldValue));
					}else if ($fieldName == "sapId") {
						$this->app_db->where(SMT_VISITS.'.sap_id', $fieldValue);
					} else if ($fieldName == "postingStatus") {
						$this->app_db->where(SMT_VISITS.'.posting_status', $fieldValue);
					}
                }
            }
			
			// checkInDatetime && checkOutDatetime
			if(!empty($filters['checkInDatetime']) && !empty($filters['checkOutDatetime'])){
					$getCheckInDateTime 	= $filters['checkInDatetime'];
					$getCheckOutDateTime	= $filters['checkOutDatetime'];
					
					/* 
					// IST METHOD 
					$dateWhereQuery = "
					((DATE(".SMT_VISITS.'.check_in_datetime'.")>='".$getCheckInDateTime."') AND DATE(".SMT_VISITS.'.check_in_datetime'.") <= '".$getCheckOutDateTime."'
					OR 
					(DATE(".SMT_VISITS.'.check_out_datetime'.")>='".$getCheckInDateTime."' AND DATE(".SMT_VISITS.'.check_out_datetime'.") <= '".$getCheckOutDateTime."'))";
					$this->app_db->where($dateWhereQuery);
					*/
					
					// 2nd Method 
					$dateWhereQuery = "
					((DATE(".SMT_VISITS.'.check_in_datetime'.")>='".$getCheckInDateTime."') AND DATE(".SMT_VISITS.'.check_in_datetime'.") <= '".$getCheckOutDateTime."')";
					$this->app_db->where($dateWhereQuery);
					
			}
			
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('SMT_VISITS')['columns_list'],$fieldName);
				
			if(!empty($fieldName)){
				$this->app_db->order_by($fieldName, $sortOrder);
			}
			
			
        }else{
			$this->app_db->order_by(SMT_VISITS.'.updated_on', 'desc');
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
}
?>