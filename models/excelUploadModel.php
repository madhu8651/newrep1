<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('excelUploadModel');

/**
* Description Excel Upload in Admin
* @author suresh.n
*/
class excelUploadModel extends CI_Model{
	public function __construct(){
		parent::__construct();
    }

    public function fetchAllUsers($ind,$bus){
    	try{
    		$query=$GLOBALS['$dbFramework']->query("
                                                    SELECT 
                                                    ud.user_name,ud.user_id,
                                                    ul.manager_module AS manager,
                                                    ul.sales_module AS sales_module
                                                    FROM
                                                    user_mappings AS um,
                                                    user_details AS ud,
                                                    user_mappings AS um1,
                                                    user_mappings AS um2,
                                                    user_licence AS ul
                                                    WHERE
                                                    um.map_id = 'new_sell'
                                                    AND um.map_type = 'sell_type'
                                                    AND um.user_id = ud.user_id
                                                    AND um1.map_id = '$ind'
                                                    AND um1.user_id = ud.user_id
                                                    AND um1.map_type = 'clientele_industry'
                                                    AND um2.map_id = '$bus'
                                                    AND um2.user_id = ud.user_id
                                                    AND um2.map_type = 'business_location'
                                                    AND ud.user_state = 1
                                                    AND ul.user_id = ud.user_id
                                                    GROUP BY ud.user_id
                                            ");
    		return $query->result();	        
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    } 

    public function fetchNumberofLeads(){
    	try{
    		$query=$GLOBALS['$dbFramework']->query("
													SELECT 
    												COUNT(*) as count
													FROM
    												lead_info as li
    												WHERE
    												li.source_flag = '0'
												");
    		return $query->result();	        
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function fetchLeadSource($value='') {
    	try{
    		$query=$GLOBALS['$dbFramework']->query("
													SELECT b.id,b.hierarchy_id,b.hkey2,b.hvalue2,b.hkey1,b.hvalue1
													From
												hierarchy_class a,hierarchy b
												where
												a.Hierarchy_Class_Name='lead_source'
												and b.hkey2!='0'
												and b.Hierarchy_Class_ID=a.hierarchy_class_id
												order by b.hierarchy_id
			");
    		return $query->result();	        
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function location(){
        try{
            $query=$this->db->query("
                        SELECT a.hvalue2 as business_location_name,a.hkey2 as business_location_id 
                        from hierarchy a,user_mappings c
                        where a.hkey2=c.map_id 
                        and c.map_type='business_location'
                        group by a.hkey2
                        ");
            return $query->result();
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }    


    public function fetchLeadsIndustry($value='') {
    	try{
    		$query=$GLOBALS['$dbFramework']->query("
													SELECT a.hvalue2 as industry_name,a.hkey2 as industry_id 
			from hierarchy a,user_mappings c, user_details d
			where a.hkey2=c.map_id 
			and c.map_type='clientele_industry' 
			and d.team_id=c.transaction_id 
			group by a.hkey2
			");
    		return $query->result();	        
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    
    public function insertLeadData($leadsDataArray,$contactDataArray,$userMapDataArray) {
        try{

            $leadInsert=$GLOBALS['$dbFramework']->insert_batch("lead_info",$leadsDataArray);
            $contactInsert=$GLOBALS['$dbFramework']->insert_batch('contact_details',$contactDataArray);
            $userMapInsert=$GLOBALS['$dbFramework']->insert_batch('lead_cust_user_map',$userMapDataArray);

            return true;
                     
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function getLeadNames($leadids) {
        try{
            $query=$GLOBALS['$dbFramework']->query("
                                                    SELECT 
                                                        lead_name
                                                    FROM
                                                        lead_info
                                                    WHERE
                                                        lead_id IN ('$leadids')
                                                    ");
            return $query->result();            
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }    
    


}



?>