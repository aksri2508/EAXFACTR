<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_country_model.php
* @Class  			 : Master_country_model
* Model Name         : Master_country_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 22 MAY 2021
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Master_country_model extends CI_Model
{    
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'MASTER_COUNTRY';
		$this->tableName 	= constant($this->tableNameStr);
    }

    /**
	* @METHOD NAME 	: getCountryList()
	*
	* @DESC 		: TO GET THE UOM LIST
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getCountryList($getPostData)
    {
        // SELECT 
        $this->app_db->select(array(
								MASTER_COUNTRY.'.id',
								MASTER_COUNTRY.'.country_name',
								MASTER_COUNTRY.'.country_code',
								MASTER_COUNTRY.'.iso_code',
								MASTER_COUNTRY.'.status',
								MASTER_COUNTRY.'.posting_status',
								MASTER_COUNTRY.'.sap_id',
								MASTER_COUNTRY.'.sap_error',
								MASTER_STATIC_DATA.'.name as statusName',
							));
        $this->app_db->from(MASTER_COUNTRY);
   
		$this->app_db->join(MASTER_STATIC_DATA, MASTER_STATIC_DATA.'.master_id = '.MASTER_COUNTRY.'.status', '');
        $this->app_db->where(MASTER_STATIC_DATA.'.type', 'COMMON_STATUS');
		$this->app_db->where(MASTER_COUNTRY.'.is_deleted', '0');
		
        
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0 || $fieldValue == "0") {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                if (!empty($fieldValue)) {
					if($fieldName=="countryName") {
						$this->app_db->like('LCASE(country_name)', strtolower($fieldValue));
					}else if($fieldName=="countryCode"){
						$this->app_db->where(MASTER_COUNTRY.'.country_code', $fieldValue);
					}else if($fieldName=="isoCode"){
						$this->app_db->where(MASTER_COUNTRY.'.iso_code', $fieldValue);
					}else if($fieldName=="status"){
						$this->app_db->where(MASTER_COUNTRY.'.status', $fieldValue);
					}else if ($fieldName == "sapId") {
						$this->app_db->where(MASTER_COUNTRY.'.sap_id', $fieldValue);
					} else if ($fieldName == "postingStatus") {
						$this->app_db->where(MASTER_COUNTRY.'.posting_status', $fieldValue);
					}
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('MASTER_UOM')['columns_list'],$fieldName);
				
			if(!empty($fieldName)){
				$this->app_db->order_by($fieldName, $sortOrder);
			}
			
        }else{
			$this->app_db->order_by(MASTER_COUNTRY.'.updated_on', 'desc');
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