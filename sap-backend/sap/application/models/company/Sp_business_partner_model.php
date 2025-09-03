<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Sp_business_partner_model.php
* @Class  			 : Sp_business_partner_model
* Model Name         : Sp_business_partner_model
* Description        :
* Module             : COMPANY
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 16 MAY 2019
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Sp_business_partner_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
		$this->tableNameStr = 'SP_BUSINESS_PARTNER';
		$this->tableName 	= constant($this->tableNameStr);
    }
	
	
	/**
	* @METHOD NAME 	: saveSpBusinessPartner()
	*
	* @DESC 		: TO SAVE THE SPECIAL PRICE BUSINESS PARTNER 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function saveSpBusinessPartner($getPostData)
    {			
		// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
		$whereExistsQry = array(
							  'item_id' 			=> $getPostData['itemId'],
							  'business_partner_id' => $getPostData['businessPartnerId'],
							);
							
		$chkRecord		= $this->commonModel->isExists($this->tableName,$whereExistsQry);
		
		if (0 == $chkRecord) {
			$rowData  = bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);
			$insertId = $this->commonModel->insertQry($this->tableName, $rowData);
			$modelOutput['flag'] = 1;
		} else {
			$modelOutput['flag'] = 2;
		}
		return $modelOutput;
    }
    
    
	/**
	* @METHOD NAME 	: updateSpBusinessPartner()
	*
	* @DESC 		: TO UPDATE THE SP BUSINESS PARTNER 
	* @RETURN VALUE : $modelOutput array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function updateSpBusinessPartner($getPostData)
    {
		// CHECK WHETHER DATA ALREADY EXISTS IN TABLE 			
		$whereExistsQry = array(
							  'item_id' 			=> $getPostData['itemId'],
							  'business_partner_id' => $getPostData['businessPartnerId'],
							  'id!='				=> $getPostData['id'],
						);
			
		$totRows = $this->commonModel->isExists($this->tableName,$whereExistsQry);
		               
        if(0 == $totRows) {
			$whereQry 	= array('id'=>$getPostData['id']);
			$rowData 	= bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
			$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);
		    $modelOutput['flag'] = 1;
        } else {
            $modelOutput['flag'] = 2;
        }
		return $modelOutput;
    }
    
	
    /**
	* @METHOD NAME 	: editSpBusinessPartner()
	*
	* @DESC 		: TO EDIT SPEICAL PRICE BUSINESS PARTNER 
	* @RETURN VALUE : $rs array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function editSpBusinessPartner($getPostData)
    {
		$id =  $getPostData['id'];
        $rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $id);
		$this->app_db->select($rowData);
		$this->app_db->from($this->tableName);
		$this->app_db->where('id', $id);
		$this->app_db->where('is_deleted', '0');
		$rs = $this->app_db->get();
		return  $rs->result_array();
    }
    
   
    /**
	* @METHOD NAME 	: getSpBusinessPartnerList()
	*
	* @DESC 		: TO GET THE SPECIAL PRICE BUSINESS PARTNER LIST 
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
    public function getSpBusinessPartnerList($getPostData)
    {
        // SELECT 
        $this->app_db->select(array(
								SP_BUSINESS_PARTNER.'.id',
								SP_BUSINESS_PARTNER.'.business_partner_id',
								SP_BUSINESS_PARTNER.'.item_id',
								SP_BUSINESS_PARTNER.'.price_list_id',
								SP_BUSINESS_PARTNER.'.discount_percentage',
								SP_BUSINESS_PARTNER.'.unit_price',
								SP_BUSINESS_PARTNER.'.price_after_discount',
								SP_BUSINESS_PARTNER.'.posting_status',
								SP_BUSINESS_PARTNER.'.sap_id',
								SP_BUSINESS_PARTNER.'.sap_error',
								BUSINESS_PARTNER.".partner_name",								
								BUSINESS_PARTNER.".partner_code",
								MASTER_ITEM.'.item_name',
								MASTER_ITEM.'.item_code',
								MASTER_PRICE_LIST.'.price_list_name'
							));
							
        $this->app_db->from(SP_BUSINESS_PARTNER);
        $this->app_db->where(SP_BUSINESS_PARTNER.'.is_deleted', '0');
		$this->app_db->join(BUSINESS_PARTNER, BUSINESS_PARTNER.'.id = '.SP_BUSINESS_PARTNER.'.business_partner_id', 'left');
		$this->app_db->join(MASTER_ITEM, MASTER_ITEM.'.id = '.SP_BUSINESS_PARTNER.'.item_id', 'left');
		$this->app_db->join(MASTER_PRICE_LIST, MASTER_PRICE_LIST.'.id = '.SP_BUSINESS_PARTNER.'.price_list_id', 'left');
		
        // TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
        $tableProperties = $getPostData['tableProperties'];
        $filters         = $getPostData['search'];
        
        // SEARCH
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $fieldName  = $key;
                $fieldValue = $value;
                if ($fieldValue!="") {
					if($fieldName=="partnerName"){
						$this->app_db->like('LCASE(CONCAT('.BUSINESS_PARTNER.'.partner_name,
						'.BUSINESS_PARTNER.'.partner_code))', strtolower($fieldValue));
					}else if($fieldName=="itemName"){
						//$this->app_db->like('LCASE(concat(item_code,item_name))',strtolower($fieldValue));
					}else if($fieldName=="priceListName"){
						$this->app_db->like('price_list_name', $fieldValue);
					}else if($fieldName=="unitPrice"){
						$this->app_db->where(SP_BUSINESS_PARTNER.'.unit_price', $fieldValue);
					}else if($fieldName=="discountPercentage"){
						$this->app_db->like('discount_percentage', $fieldValue);
					}else if($fieldName=="priceAfterDiscount"){
						$this->app_db->where('price_after_discount', $fieldValue);
					}else if ($fieldName == "sapId") {
						$this->app_db->where(SP_BUSINESS_PARTNER.'.sap_id', $fieldValue);
					} else if ($fieldName == "postingStatus") {
						$this->app_db->where(SP_BUSINESS_PARTNER.'.posting_status', $fieldValue);
					}
					
					
                }
            }
        }
        
        // ORDERING 
        if (isset($tableProperties['sortField'])) {
            $fieldName = $tableProperties['sortField'];
            $sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";
			
			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('SP_BUSINESS_PARTNER')['columns_list'],$fieldName);
				
			if(!empty($fieldName)){
				$this->app_db->order_by($fieldName, $sortOrder);
			}
			
        }else{
			$this->app_db->order_by(SP_BUSINESS_PARTNER.'.updated_on', 'desc');
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