<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Manage_notifications_model.php
* @Class  			 : Manage_notifications_model
* Model Name         : Manage_notifications_model
* Description        :
* Module             : COMMON
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : -
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Manage_notifications_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'NOTIFICATIONS';
		$this->tableName 	= constant($this->tableNameStr);
    }
	
	
   /**
	* @METHOD NAME 	: markAsRead()
	*
	* @DESC 		: TO MARK AS READ 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function markAsRead($getPostData)
    {

		$rowData['status'] 	= 2; // status hardcoded
        $this->app_db->where_in("id", $getPostData['id']);
		$this->app_db->update($this->tableName, $rowData);

		$modelOutput['flag'] = 1;
        return $modelOutput;
    }
	
	
	 /**
	* @METHOD NAME 	: markAllAsRead()
	*
	* @DESC 		: TO MARK ALL AS READ 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function markAllAsRead($getPostData)
    {
		$whereQry = array('receiver_id'=> $this->currentUserId);
		$rowData['status'] 	= 2; // status hardcoded
		$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);
		$modelOutput['flag'] = 1;
        return $modelOutput;
    }
	
	
    /**
	* @METHOD NAME 	: getMyNotifications()
	*
	* @DESC 		: TO GET THE NOTIFICATION DETAILS 
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getMyNotifications($getPostData)
    {
        
        // Get Unread Count.
        $query1 = $this->app_db
              ->select('status, count(id) AS message_count')
              ->where(NOTIFICATIONS.'.is_deleted', '0')
              ->where(NOTIFICATIONS.'.status', '1')
              ->where(NOTIFICATIONS.'.receiver_id', $this->currentUserId)
              ->get(NOTIFICATIONS);
        $unReadCountResult = $query1->result();
        $unreadCount = $unReadCountResult[0]->message_count;

        // Get Read Count.
        // $query2 = $this->app_db
        //     ->select('status, count(id) AS message_count')
        //     ->where(NOTIFICATIONS.'.is_deleted', '0')
        //     ->where(NOTIFICATIONS.'.status', '2')
        //     ->where(NOTIFICATIONS.'.receiver_id', $this->currentUserId)
        //     ->get(NOTIFICATIONS);
        // $readCountResult = $query2->result();
        // $readCount = $readCountResult[1]->message_count;

        // SELECT 
        $this->app_db->select(array(
										NOTIFICATIONS.'.id',
										NOTIFICATIONS.'.document_id',
										NOTIFICATIONS.'.document_type_id',
										NOTIFICATIONS.'.receiver_id',
										NOTIFICATIONS.'.notification_type',
										NOTIFICATIONS.'.content',
										NOTIFICATIONS.'.status',
										NOTIFICATIONS.'.created_on',
										NOTIFICATIONS.'.created_by',
										EMPLOYEE_PROFILE.'.first_name',
										EMPLOYEE_PROFILE.'.last_name',
                                        MASTER_STATIC_DATA.'.name as document_type_name',
							));
							
        $this->app_db->from(NOTIFICATIONS);
        $this->app_db->join(MASTER_STATIC_DATA, MASTER_STATIC_DATA.'.master_id = '.NOTIFICATIONS.'.document_type_id', 'left');
		$this->app_db->where(NOTIFICATIONS.'.is_deleted', '0');
        $this->app_db->where(MASTER_STATIC_DATA.'.type', 'DOCUMENT_TYPE');
		$this->app_db->join(EMPLOYEE_PROFILE, EMPLOYEE_PROFILE.'.id = '.NOTIFICATIONS.'.created_by', '');

        // $this->currentUserId = 2;
		$this->app_db->where(NOTIFICATIONS.'.receiver_id', $this->currentUserId);
		
		
        
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                if (!empty($fieldValue)) {
					if($fieldName=="status") {
						$this->app_db->where(NOTIFICATIONS.'.status', $fieldValue);
					}
                    else if($fieldName=="documentTypeId"){
						$this->app_db->where(NOTIFICATIONS.'.document_type_id', $fieldValue);
                    }
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('NOTIFICATIONS')['columns_list'],$fieldName);
				
			if(!empty($fieldName)){
				$this->app_db->order_by($fieldName, $sortOrder);
			}
			
			
        }else{
			$this->app_db->order_by(NOTIFICATIONS.'.updated_on', 'desc');
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
        // $modelData['totalCountOverall']  = ($unreadCount + $readCount);
        $modelData['totalUnreadCount']  = $unreadCount;
        // $modelData['totalReadCount']  = $readCount;
        return $modelData;
    }
	
}
?>
