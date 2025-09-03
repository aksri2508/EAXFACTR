<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Smt_map_model.php
* @Class  			 : Smt_map_model
* Model Name         : Smt_map_model
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
class Smt_map_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
    }
	
	
    /**
	* @METHOD NAME 	: getMapVisitList()
	*
	* @DESC 		: TO GET THE MAP VISIT LIST
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getMapVisitList($getPostData)
    {
		$teamId  = $getPostData['teamId'];
		
		//	$teamId 		 = 1;
		//$selDate 		 = '2019-10-11';
		$teamMembersList = $this->commonModel->getTeamMembersByTeamId($teamId);
		
		//printr($teamMembersList);
		
		if(count($teamMembersList) > 0){
			foreach($teamMembersList as $empKey => $empValue){

				// EMPLOYEE PROFILE INFO 
				$mapInfo 			= array();
				$empId 				= $empValue['emp_id'];
				$employeeDetails 	= $this->commonModel->getProfileInformation($empId);
				$mapInfo['empInfo'] = $employeeDetails['profileInfo'];
				
				// ATTENDANCE RESULTS 
				$attendanceResults 				= $this->getEmployeeAttendanceDetails($getPostData,$empId);
				$mapInfo['attendance_details']  = $attendanceResults;
				
				$visitResults				= $this->getSmtVisitsDetails($getPostData,$empId);
				$mapInfo['visit_details'] 	= $visitResults;
				$empInfo[] = $mapInfo;
			}
		}
		return $empInfo;
    }
	
	
	/**
	* @METHOD NAME 	: getEmployeeAttendanceDetails()
	*
	* @DESC 		: TO GET THE EMPLOYEE ATTENDACE DETAILS  
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function getEmployeeAttendanceDetails($getPostData,$empId)
    {
		$selDate = $getPostData['selDate'];
		
		// GET THE ATTENDANE OF EMPLOYEE 
			$this->app_db->select(array(
									EMPLOYEE_ATTENDANCE.'.id',
									EMPLOYEE_ATTENDANCE.'.team_id',
									EMPLOYEE_ATTENDANCE.'.emp_id',
									EMPLOYEE_ATTENDANCE.'.punch_in_datetime',
									EMPLOYEE_ATTENDANCE.'.punch_out_datetime',
									EMPLOYEE_ATTENDANCE.'.punch_in_latitude',
									EMPLOYEE_ATTENDANCE.'.punch_in_longitude',
									EMPLOYEE_ATTENDANCE.'.punch_out_latitude',
									EMPLOYEE_ATTENDANCE.'.punch_out_longitude',
									EMPLOYEE_ATTENDANCE.'.posting_status',
									EMPLOYEE_ATTENDANCE.'.sap_id',
									EMPLOYEE_ATTENDANCE.'.sap_error',
									EMPLOYEE_PROFILE.'.profile_img',
									EMPLOYEE_PROFILE.'.emp_code',
									'CONCAT('.EMPLOYEE_PROFILE.'.first_name," ",'.EMPLOYEE_PROFILE.'.last_name
										) as empName',
									'TIME_FORMAT(TIMEDIFF(('.EMPLOYEE_ATTENDANCE.'.punch_out_datetime),('.EMPLOYEE_ATTENDANCE.'.punch_in_datetime)),"%H:%i") as time',
								));
								
			$this->app_db->from(EMPLOYEE_ATTENDANCE);
			$this->app_db->where(EMPLOYEE_ATTENDANCE.'.emp_id',$empId);
			$this->app_db->join(EMPLOYEE_PROFILE, EMPLOYEE_PROFILE.'.id = '.EMPLOYEE_ATTENDANCE.'.emp_id', '');
			$this->app_db->where(EMPLOYEE_ATTENDANCE.'.is_deleted', '0');
			$this->app_db->where("DATE(".EMPLOYEE_ATTENDANCE.'.punch_in_datetime'.")", $selDate);
			$this->app_db->where(EMPLOYEE_ATTENDANCE.'.branch_id', $this->currentbranchId);
			$attendanceRs 					= $this->app_db->get();
			$attendanceResults				= $attendanceRs->result_array();
		
			return $attendanceResults;
	}
	
	
	/**
	* @METHOD NAME 	: getSmtVisitsDetails()
	*
	* @DESC 		: TO GET THE SMT VISITS DETAILS 
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function getSmtVisitsDetails($getPostData,$empId)
    {
		$selDate = $getPostData['selDate'];
		
			// GET THE VISIT DETAILS 
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
									BUSINESS_PARTNER.".partner_name",								
									BUSINESS_PARTNER.".partner_code"	
								));
								
			$this->app_db->from(SMT_VISITS);
			$this->app_db->join(BUSINESS_PARTNER, BUSINESS_PARTNER.'.id = '.SMT_VISITS.'.business_partner_id', '');
			$this->app_db->where(SMT_VISITS.'.branch_id', $this->currentbranchId);
			$this->app_db->where(SMT_VISITS.'.is_deleted', '0');
			$this->app_db->where(SMT_VISITS.'.emp_id',$empId);
			$this->app_db->where("DATE(".SMT_VISITS.'.check_in_datetime'.")", $selDate); // DATE BASED RESULT

			$visitRs 					= $this->app_db->get();
			$visitResults				= $visitRs->result_array();
			
			return $visitResults;
	}
	
	
	/**
	* @METHOD NAME 	: getEmployeeMapVisitList()
	*
	* @DESC 		: TO GET THE EMPLOYEE MAP VISITS LIST 
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getEmployeeMapVisitList($getPostData)
    {
		
		$empId = $this->currentUserId;
		
		// EMPLOYEE PROFILE INFO
		$mapInfo 					= array();
		$employeeDetails 	= $this->commonModel->getProfileInformation($empId);
		$mapInfo['empInfo'] = $employeeDetails['profileInfo'];
		
		// ATTENDANCE RESULTS 
		$attendanceResults 				= $this->getEmployeeAttendanceDetails($getPostData,$empId);
		$mapInfo['attendance_details']  = $attendanceResults;
		
		$visitResults				= $this->getSmtVisitsDetails($getPostData,$empId);
		
		$mapInfo['visit_details'] 	= $visitResults;
		$empInfo[] = $mapInfo;
			
		return $empInfo;
    }
	
}
?>