<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Activity_job_model.php
* @Class  			 : Activity_job_model
* Model Name         : Activity_job_model
* Description        :
* Module             : pages
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 01 MAY 2021
* @LastModifiedDate  : 01 MAY 2021
* @LastModifiedBy    : 
* @LastModifiedDesc  : Added Mail / Popup notification setup for cron.
* Features           : 
*/
class Activity_job_model extends CI_Model {

	
	public function __construct()
	{
		parent::__construct();
		$this->tableNameStr = 'ACTIVITY';
		$this->empProfileTableNameStr = 'EMPLOYEE_PROFILE';
		// $this->itemTableColumnRef = '';
		// $this->itemTableColumnReqRef = '';
		$this->tableName = constant($this->tableNameStr);
		$this->empProfileTableName = constant($this->empProfileTableNameStr);
	}
	

	/**
	 * @METHOD NAME   : listActivityRecords()
	 *
	 * @DESC 		  : -
	 * @RETURN VALUE  : $rs array
	 * @PARAMETER 	  : $getPostData array
	 * @SERVICE 	  : WEB
	 * @ACCESS POINT  : -
	 **/
	public function listActivityRecords()
	{

		date_default_timezone_set("Asia/Calcutta");
		$currentDateTime = date('Y-m-d H:i:s');
		$currentHour = date('H');

		$this->app_db->select("a.id,activity_type_id,assigned_to_id,remarks,start_date_time,
		end_date_time,DATE_FORMAT(start_date_time,'%H:%i:%s') as recurring_time,
		DATE_FORMAT(DATE_SUB(start_date_time, INTERVAL reminder_before_time MINUTE),'%H:%i:%s') as recurring_trigger_time, recurrence_type_id,
		reminder_before_time,reminder_type_id,first_name,last_name,email_id");
		$this->app_db->from($this->tableName.' a');
		$this->app_db->join($this->empProfileTableName.' b', 'a.assigned_to_id ='.'b.id','left');
		$this->app_db->where("a.start_date_time <= '".$currentDateTime."'");
		$this->app_db->where("a.end_date_time >= '".$currentDateTime."'");
		$this->app_db->where('a.is_deleted', '0');
		$this->app_db->having("(recurring_trigger_time >= '".$currentHour.":00'
		and recurring_trigger_time <= '".$currentHour.":59')");

		$rs = $this->app_db->get();
		return  $rs->result_array();
	}

	
}
?>