<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Employee_attendance_model.php
* @Class  			 : Employee_attendance_model
* Model Name         : Employee_attendance_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 30 MAY 2019
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Employee_attendance_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'EMPLOYEE_ATTENDANCE';
		$this->tableName 	= constant($this->tableNameStr);
    }
	
	
	/**
	* @METHOD NAME 	: getEmployeeAttendanceDetails()
	*
	* @DESC 		: TO GET THE EMPLOYEE ATTENDANCE DETAILS BY EMP ID 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function getEmployeeAttendanceDetails($getPostData)
    {
		$empId  = $this->currentUserId;
		$flag 	= 0;
		
        $this->app_db->select(array(
								EMPLOYEE_ATTENDANCE.'.id',
								EMPLOYEE_ATTENDANCE.'.punch_out_datetime',
								"date(".EMPLOYEE_ATTENDANCE.'.punch_in_datetime'.") as punch_in_datetime",
							));
        $this->app_db->from(EMPLOYEE_ATTENDANCE);
		$this->app_db->where(EMPLOYEE_ATTENDANCE.'.is_deleted', '0');
		$this->app_db->where(EMPLOYEE_ATTENDANCE.'.branch_id', $this->currentbranchId);
		$this->app_db->where(EMPLOYEE_ATTENDANCE.'.emp_id', $empId);
		$this->app_db->order_by('id', 'desc');
		$this->app_db->limit(1, 0);		
		$rs = $this->app_db->get();
		
        $empAttendanceDetails = $rs->result_array();
			
		
		if(count($empAttendanceDetails) == 0 ){
			$flag = 1; // SUCCESS
		}else{
			// CHECK ALREADY PUNCH IN FOR THE DAY 
			$dbPunchInData  = strtotime($empAttendanceDetails[0]['punch_in_datetime']);
			$punchInDate	= explode(" ",$getPostData['punchInDatetime']);
			$reqPunchInData = strtotime($punchInDate[0]);
		
	
			if($dbPunchInData === $reqPunchInData){
				$flag = 2;  // CURRENT DATE RECORD
			}else if($empAttendanceDetails[0]['punch_out_datetime'] == "0000-00-00 00:00:00"){
				$flag = 3;  // FAILURE  
			}else {
				$flag = 1;  // SUCCESS
			}
		}
		return $flag;
	}
	
	
	/**
	* @METHOD NAME 	: getEmployeeAttendanceDetailsById()
	*
	* @DESC 		: TO GET THE EMPLOYEE ATTENDANCE DETAILS BY ID
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function getEmployeeAttendanceDetailsById($getPostData)
    {
		$flag		= 0;
		$punchInId  = $getPostData['punchInId'];
		
        $this->app_db->select(array(
								EMPLOYEE_ATTENDANCE.'.id',
								EMPLOYEE_ATTENDANCE.'.punch_out_datetime',
								"date(".EMPLOYEE_ATTENDANCE.'.punch_in_datetime'.") as punch_in_datetime",
							));
        $this->app_db->from(EMPLOYEE_ATTENDANCE);
		$this->app_db->where(EMPLOYEE_ATTENDANCE.'.is_deleted', '0');
		$this->app_db->where(EMPLOYEE_ATTENDANCE.'.branch_id', $this->currentbranchId);
		$this->app_db->where(EMPLOYEE_ATTENDANCE.'.id', $punchInId);
		$this->app_db->order_by('id', 'desc');
		$this->app_db->limit(1, 0);		
		$rs = $this->app_db->get();
        $empAttendanceDetails = $rs->result_array();
		
		if($rs->num_rows()>0){ // NUM ROWS 
			if($empAttendanceDetails[0]['punch_out_datetime'] == "0000-00-00 00:00:00"){
				$flag = 1 ;
			}else{
				$flag = 2; // Already punched out for the day 
			}	
		}else{
			$flag = 3; // INVALID RECORD PASSED 
		}
		return $flag;
	}
	
	
	/**
	* @METHOD NAME 	: getEmployeeLastPunchInId()
	*
	* @DESC 		: TO GET THE EMPLOYEE LAST PUNCHIN ID 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function getEmployeeLastPunchInId($getPostData)
    {
        $this->app_db->select(array(
								EMPLOYEE_ATTENDANCE.'.id',
								EMPLOYEE_ATTENDANCE.'.punch_out_datetime',
								"date(".EMPLOYEE_ATTENDANCE.'.punch_in_datetime'.") as punch_in_datetime",
							));
        $this->app_db->from(EMPLOYEE_ATTENDANCE);
		$this->app_db->where(EMPLOYEE_ATTENDANCE.'.is_deleted', '0');
		$this->app_db->where(EMPLOYEE_ATTENDANCE.'.branch_id', $this->currentbranchId);
		$this->app_db->where(EMPLOYEE_ATTENDANCE.'.punch_out_datetime', "0000-00-00 00:00:00");
		$this->app_db->where(EMPLOYEE_ATTENDANCE.'.emp_id', $this->currentUserId);
		$this->app_db->order_by('id', 'desc');
		$this->app_db->limit(1, 0);
		$rs = $this->app_db->get();
        $empAttendanceDetails = $rs->result_array();
		
		if($rs->num_rows()>0){ // NUM ROWS 
			$modelOutput['flag'] = 1; 	
			$modelOutput['punchInId'] = $empAttendanceDetails[0]['id']; 	
		}else{
			$modelOutput['flag'] = 2; 	
		}
		return $modelOutput;
	}
	
	
	/**
	* @METHOD NAME 	: punchInEmployeeAttendance()
	*
	* @DESC 		: TO CHECK IN THE EMPLOYEE ATTENDANCE
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function punchInEmployeeAttendance($getPostData)
    {	
		// GET EMPLOYEE TEAM DETAILS 
		$empId  = $this->currentUserId;
		
		$chkPrevPunchOutRecords  = $this->getEmployeeAttendanceDetails($getPostData);
			
		if($chkPrevPunchOutRecords!=1){
			if($chkPrevPunchOutRecords==3){
				$modelOutput['flag'] = 4; 	// PUNCH OUT PROPERLY FOR PREVIOUS RECORDS 
			}else if($chkPrevPunchOutRecords==2){ // CANNOT DO MULTIPLE PUNCH IN FOR THE SAME DAY 
				$modelOutput['flag'] = 5; 
			}			
			return $modelOutput;
		}
		
		
		$employeeTeamDetails  = $this->commonModel->getEmployeeTeamDetails($empId);
		
		if(count($employeeTeamDetails)!=1){
			  $modelOutput['flag'] = 3; // TEAM DETAILS NOT FOUND 
			  return $modelOutput;
		}
		
		// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 
		$punchInDate	= explode(" ",$getPostData['punchInDatetime']);
		$reqPunchInData = $punchInDate[0];		
		
		$whereExistsQry = array(
								'emp_id'				  => $empId,
								'date(punch_in_datetime)' => $reqPunchInData,
							   );
							   
		$chkRecord		= $this->commonModel->isExists(EMPLOYEE_ATTENDANCE,$whereExistsQry);
		
	
		if($chkRecord!=0){
			$modelOutput['flag'] = 2;
			return $modelOutput;
		}
		
		$teamId	    = $employeeTeamDetails[0]['team_id'];
		
		/*
		// INSERT EMPLOYEE ATTENDANCE
		$insertData = array(
								'team_id'				=> $teamId,
								'emp_id' 				=> $empId,
								'punch_in_datetime'		=> $getPostData['punchInDatetime'],
								'punch_in_latitude' 	=> $getPostData['punchInLatitude'],
								'punch_in_longitude'	=> $getPostData['punchInLongitude'],
								'branch_id'				=> $this->currentbranchId,
							);
		$insertId 				  = $this->commonModel->insertQry(EMPLOYEE_ATTENDANCE,$insertData);
		*/
	
		
		// ROW DATA 
		$rowData  				=  bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);
		$rowData['branch_id'] 	=  $this->currentbranchId;
		$rowData['emp_id'] 		=  $empId;
		$rowData['team_id'] 	=  $teamId;
		$insertId 				=  $this->commonModel->insertQry($this->tableName, $rowData);
		
		
		$modelOutput['punchInId'] = $insertId;
		$modelOutput['flag'] 	  = 1;
        return $modelOutput;
    }
	
	
	
	/**
	* @METHOD NAME 	: checkpunchOutConditions()
	*
	* @DESC 		: TO CHEK THE PUNCH OUT CONDITIONS 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function checkpunchOutConditions($empId)
    {
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
        $empAttendanceDetails = $rs->result_array();
				
		if(count($empAttendanceDetails) == 0 ){
			return true;
		}else{
			if($empAttendanceDetails[0]['check_out_datetime'] == "0000-00-00 00:00:00"){
				return false;
			}else {
				return true;
			}
		}
	
	}
	
	
	/**
	* @METHOD NAME 	: punchOutEmployeeAttendance()
	*
	* @DESC 		: TO PUNCH OUT THE EMPLOYEE ATTENDANCE
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function punchOutEmployeeAttendance($getPostData)
    {	
		$empId  = $this->currentUserId;
		
		if(!$this->checkpunchOutConditions($empId)){
			 $modelOutput['flag'] = 2;
			 return $modelOutput;
		}
		$chkPunchInId = $this->getEmployeeAttendanceDetailsById($getPostData);
		
		// PUNCH OUT DETAILS 
		if($chkPunchInId!=1){
			if($chkPunchInId==2){
				$modelOutput['flag'] = 4;  // Already punched out for the day 
			}else if($chkPunchInId==3) {
				$modelOutput['flag'] = 3; // InValid Id Passed 
			}
			 return $modelOutput;
		}
		
		/*
			$updateData = array(
									'punch_out_datetime'	=> $getPostData['punchOutDatetime'],
									'punch_out_latitude' 	=> $getPostData['punchOutLatitude'],
									'punch_out_longitude'	=> $getPostData['punchOutLongitude'],
								);
			$whereQry = array('id'=>$getPostData['punchInId']);		
			$this->commonModel->updateQry(EMPLOYEE_ATTENDANCE,$updateData,$whereQry);
			$modelOutput['flag'] = 1;
		*/
		$whereQry 	= array('id'=>$getPostData['punchInId']);	
		$rowData 	= bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
		$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);
		$modelOutput['flag'] = 1;
		return $modelOutput;
    }
    
	
    /**
	* @METHOD NAME 	: getEmployeeAttendanceList()
	*
	* @DESC 		: TO GET THE EMPLOYEE ATTENDANCE LIST
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getEmployeeAttendanceList($getPostData)
    {
		// CHECK REPORTING MANAGER 
		$chkReportingManager = $this->commonModel->checkReportingManager($this->currentUserId);
		
        // SELECT 
        $this->app_db->select(array(
								EMPLOYEE_ATTENDANCE.'.id',
								EMPLOYEE_ATTENDANCE.'.team_id',
								EMPLOYEE_ATTENDANCE.'.emp_id',
								EMPLOYEE_ATTENDANCE.'.punch_in_datetime',
								EMPLOYEE_ATTENDANCE.'.punch_out_datetime',
								EMPLOYEE_ATTENDANCE.'.punch_in_latitude',
								EMPLOYEE_ATTENDANCE.'.punch_out_latitude',
								EMPLOYEE_ATTENDANCE.'.punch_in_longitude',
								EMPLOYEE_ATTENDANCE.'.punch_out_longitude',
								EMPLOYEE_ATTENDANCE.'.sap_id',
								EMPLOYEE_ATTENDANCE.'.posting_status',
								EMPLOYEE_ATTENDANCE.'.sap_error',
								EMPLOYEE_PROFILE.'.profile_img',
								EMPLOYEE_PROFILE.'.emp_code',
								'CONCAT('.EMPLOYEE_PROFILE.'.first_name," ",'.EMPLOYEE_PROFILE.'.last_name
									) as empName',
								'TIME_FORMAT(TIMEDIFF(('.EMPLOYEE_ATTENDANCE.'.punch_out_datetime),('.EMPLOYEE_ATTENDANCE.'.punch_in_datetime)),"%H:%i") as time',
							));
							
        $this->app_db->from(EMPLOYEE_ATTENDANCE);
		$this->app_db->join(EMPLOYEE_PROFILE, EMPLOYEE_PROFILE.'.id = '.EMPLOYEE_ATTENDANCE.'.emp_id', '');
		$this->app_db->where(EMPLOYEE_ATTENDANCE.'.is_deleted', '0');
		$this->app_db->where(EMPLOYEE_ATTENDANCE.'.branch_id', $this->currentbranchId);
		
		if($this->currentAccessControlId!=1){		
			if($chkReportingManager){				
				$this->app_db->where_in(EMPLOYEE_ATTENDANCE.'.emp_id',$this->currentgroupUsers);
			}else{
				$this->app_db->where(EMPLOYEE_ATTENDANCE.'.emp_id', $this->currentUserId);
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
					if($fieldName=="empName"){
						$this->app_db->like('LCASE(CONCAT('.EMPLOYEE_PROFILE.'.first_name,
						'.EMPLOYEE_PROFILE.'.last_name,'.EMPLOYEE_PROFILE.'.emp_code))', strtolower($fieldValue));
					}else if($fieldName=="punchInDatetime"){
						//$this->app_db->where("DATE(".EMPLOYEE_ATTENDANCE.'.punch_in_datetime'.")>=", $fieldValue);
					}else if($fieldName=="punchOutDatetime"){
						//$this->app_db->where("DATE(".EMPLOYEE_ATTENDANCE.'.punch_out_datetime'.")<=", $fieldValue);
					} else if ($fieldName == "sapId") {
						$this->app_db->where(EMPLOYEE_ATTENDANCE.'.sap_id', $fieldValue);
					} else if ($fieldName == "postingStatus") {
						$this->app_db->where(EMPLOYEE_ATTENDANCE.'.posting_status', $fieldValue);
					}
					
                }
            }
			
			// punchInDatetime && punchOutDatetime
			if(!empty($filters['punchInDatetime']) && !empty($filters['punchOutDatetime'])){
					$getPunchInDateTime 	= $filters['punchInDatetime'];
					$getPunchOutDateTime	= $filters['punchOutDatetime'];
					
					/*
					// 1st method 
					$dateWhereQuery = "
					((DATE(".EMPLOYEE_ATTENDANCE.'.punch_in_datetime1'.")>='".$getPunchInDateTime."') AND DATE(".EMPLOYEE_ATTENDANCE.'.punch_in_datetime'.") <= '".$getPunchOutDateTime."'
					OR 
					(DATE(".EMPLOYEE_ATTENDANCE.'.punch_out_datetime'.")>='".$getPunchInDateTime."' AND DATE(".EMPLOYEE_ATTENDANCE.'.punch_out_datetime'.") <= '".$getPunchOutDateTime."'))";
					$this->app_db->where($dateWhereQuery);
					*/
					
					// 2nd Method 
					$dateWhereQuery = "
					(DATE(".EMPLOYEE_ATTENDANCE.'.punch_in_datetime'.")>='".$getPunchInDateTime."') AND DATE(".EMPLOYEE_ATTENDANCE.'.punch_in_datetime'.") <= '".$getPunchOutDateTime."'";
					$this->app_db->where($dateWhereQuery);
			}
			
			
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('EMPLOYEE_ATTENDANCE')['columns_list'],$fieldName);
						
			if(!empty($fieldName)){
				$this->app_db->order_by($fieldName, $sortOrder);
			}
			
		
            $this->app_db->order_by($fieldName, $sortOrder);
        }else{
			$this->app_db->order_by(EMPLOYEE_ATTENDANCE.'.updated_on', 'desc');
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
			if($value['time']<0){
				$value['time'] = '';
			}
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