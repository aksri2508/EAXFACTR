<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Approval_status_report_model.php
* @Class  			 : Approval_status_report_model
* Model Name         : Approval_status_report_model
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
class Approval_status_report_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'APPROVAL_STATUS_REPORT';
		$this->tableName 	= constant($this->tableNameStr);
    }
	
		
    /**
	* @METHOD NAME 	: getApprovalStatusReportList()
	*
	* @DESC 		: TO GET THE APPROVAL TEMPLATE LIST
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getApprovalStatusReportList($getPostData)
    {

    //   $this->currentUserId = 203;

      // Additional Condition for Checking Approver ID 
      $approverWhereQry = "(".APPROVAL_STATUS_REPORT.".created_by='".$this->currentUserId."' OR FIND_IN_SET(".$this->currentUserId.','.APPROVAL_STATUS_REPORT.".approvers_id) >0 )";

       // SELECT 
        $this->app_db->select(array(
                                    APPROVAL_STATUS_REPORT.'.id',
                                    APPROVAL_STATUS_REPORT.'.document_id',
                                    APPROVAL_STATUS_REPORT.'.document_type_id',
                                    APPROVAL_STATUS_REPORT.'.document_number',
                                    APPROVAL_STATUS_REPORT.'.overall_approval_status as approval_status',
                                    APPROVAL_STATUS_REPORT.'.last_remarks as remarks',
                                    APPROVAL_STATUS_REPORT.'.total_approved',
                                    APPROVAL_STATUS_REPORT.'.total_rejected',
                                    APPROVAL_STATUS_REPORT.'.approvers_id',
                                    APPROVAL_STATUS_REPORT.'.created_by',
                                    APPROVAL_STATUS_REPORT.'.sap_id',
                                    APPROVAL_STATUS_REPORT.'.posting_status',
                                    APPROVAL_STATUS_REPORT.'.sap_error',
                                    MASTER_STATIC_DATA.'.name as document_type_name',
                                ));
        $this->app_db->from(APPROVAL_STATUS_REPORT);
        $this->app_db->join(MASTER_STATIC_DATA, MASTER_STATIC_DATA.'.master_id = '.APPROVAL_STATUS_REPORT.'.document_type_id', 'left');
        $this->app_db->where(MASTER_STATIC_DATA.'.type', 'DOCUMENT_TYPE');
        $this->app_db->where(APPROVAL_STATUS_REPORT.'.is_deleted', '0');
        $this->app_db->where($approverWhereQry);
        
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                if ($fieldValue!="") {
                    if($fieldName == "approvalStatus") {
                        $this->app_db->where(APPROVAL_STATUS_REPORT.'.overall_approval_status', $fieldValue);
                    } else if($fieldName == "documentNumber") {
                        $this->app_db->like('LCASE('.APPROVAL_STATUS_REPORT.'.document_number)', strtolower($fieldValue));
                    } else if($fieldName == "remarks") {
                        $this->app_db->like('LCASE('.APPROVAL_STATUS_REPORT.'.last_remarks)', strtolower($fieldValue));
                    } else if($fieldName == "documentTypeName") {
                        $this->app_db->like('LCASE(document_type_name)', strtolower($fieldValue));
                    }else if ($fieldName == "sapId") {
						$this->app_db->where(APPROVAL_STATUS_REPORT.'.sap_id', $fieldValue);
					} else if ($fieldName == "postingStatus") {
						$this->app_db->where(APPROVAL_STATUS_REPORT.'.posting_status', $fieldValue);
					}
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('APPROVAL_STATUS_REPORT')['columns_list'],$fieldName);
				
			if(!empty($fieldName)){
				$this->app_db->order_by($fieldName, $sortOrder);
			}
			
        }else{
			$this->app_db->order_by(APPROVAL_STATUS_REPORT.'.updated_on', 'desc');
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