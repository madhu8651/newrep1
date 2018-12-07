<?php
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('manager_contactModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();
class manager_contactModel extends CI_Model	{
	
	function __construct()	{
		parent::__construct();
	}

    public function getChildrenForParent($user_id) {
    	try {	
			$query = $GLOBALS['$dbFramework']->query("
				SELECT user_id, reporting_to FROM user_details");
			$full_structure = $query->result();
			$allParentNodes = [];
			if (version_compare(phpversion(), '7.0.0', '<')) {
			// php version isn't high enough to support array_column
				foreach($full_structure as $row)  {
					$allParentNodes[$row->user_id] = $row->reporting_to;
				}
			} else {
				$allParentNodes = array_column(
					$full_structure, 
					'reporting_to',
					'user_id'
				);
			}
			$childNodes = array();
			$this->fetchChildNodes($user_id, $childNodes, $allParentNodes);
			if (count($childNodes) == 0) {
				return '';
			}
			$ids = implode("','", $childNodes);
			return $ids;
    	}
		catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}
    }

    private function fetchChildNodes($givenID, & $childNodes, $allParentNodes)  {
        foreach ($allParentNodes as $user_id => $reporting_to) {
            if ($reporting_to == $givenID)  {
                array_push($childNodes, $user_id);
                $this->fetchChildNodes($user_id, $childNodes, $allParentNodes);                
            }
        }
    }

	public function fetch_Contacts($user_id)	{
		try {
				$children = $user_id."','";
				$children .= $this->getChildrenForParent($user_id);

				$query = $GLOBALS['$dbFramework']->query("
						(SELECT cd.contact_id AS contact_id, cd.contact_name AS contact_name, cd.contact_for AS contact_for,
						coalesce(lo.lookup_value,'-') AS contact_type_name, lo.lookup_id AS contact_type_id,
						li.lead_id AS lead_cust_id, li.lead_name AS lead_cust_name,
						JSON_UNQUOTE(cd.contact_email->'$.email') AS contact_email, 
						JSON_UNQUOTE(cd.contact_number->'$.phone') AS contact_number,
						coalesce(cd.contact_desg,'-') as contact_desg, cd.contact_photo, cd.contact_created_time,
						coalesce(cd.contact_dob,'-') AS contact_dob, cd.contact_address AS contact_address, cd.remarks AS remarks
						FROM lead_info AS li, contact_details AS cd LEFT JOIN `lookup` AS lo ON cd.contact_type = lo.lookup_id AND lo.lookup_name = 'Buyer Persona'
						WHERE ((li.lead_rep_owner IN ('$children')) OR (li.lead_manager_owner in ('$children'))) AND (li.lead_id=cd.lead_cust_id)
						GROUP BY cd.contact_id
						ORDER BY cd.contact_name)
						UNION 
						(SELECT cd.contact_id AS contact_id, cd.contact_name AS contact_name, cd.contact_for AS contact_for,
						coalesce(lo.lookup_value,'-') AS contact_type_name, lo.lookup_id AS contact_type_id,
						ci.customer_id AS lead_cust_id, ci.customer_name AS lead_cust_name,
						JSON_UNQUOTE(cd.contact_email->'$.email') AS contact_email, 
						JSON_UNQUOTE(cd.contact_number->'$.phone') AS contact_number,
						coalesce(cd.contact_desg,'-') as contact_desg, cd.contact_photo, cd.contact_created_time,
						coalesce(cd.contact_dob,'-') AS contact_dob, cd.contact_address AS contact_address, cd.remarks AS remarks
						FROM customer_info AS ci, contact_details AS cd LEFT JOIN `lookup` AS lo ON cd.contact_type = lo.lookup_id AND lo.lookup_name = 'Buyer Persona'
						WHERE ((ci.customer_rep_owner IN ('$children')) OR (ci.customer_manager_owner in ('$children')))AND (ci.customer_id=cd.lead_cust_id)
						GROUP BY cd.contact_id
						ORDER BY cd.contact_name,contact_created_time)
						UNION
						(select co.contact_id AS contact_id, co.contact_name AS contact_name, co.contact_for AS contact_for,
						coalesce(lu.lookup_value,'-') AS contact_type_name, lu.lookup_id AS contact_type_id,
						opp.opportunity_id AS lead_cust_id, opp.opportunity_name AS lead_cust_name,
						JSON_UNQUOTE(co.contact_email->'$.email') AS contact_email,
						JSON_UNQUOTE(co.contact_number->'$.phone') AS contact_number,
						ifnull(co.contact_desg,'-') as contact_desg, co.contact_photo, co.contact_created_time,
						ifnull(co.contact_dob,'-') AS contact_dob, co.contact_address AS contact_address, co.remarks AS remarks 
						from opportunity_details opp, contact_details co 
						join lookup lu on lu.lookup_name = 'Buyer Persona'
						where co.contact_created_by in ('$children')
                        AND co.lead_cust_id = opp.opportunity_id
						GROUP BY co.contact_id
						ORDER BY co.contact_name ,contact_created_time)
				");
				return $query->result();			
		}
		catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    
	}

	public function fetch_Leads($user_id)	{
		try {
				/*
				fetch all leads for all user_ids under given user_id
				*/
				$children = $user_id."','";
				$children .= $this->getChildrenForParent($user_id);			
				$query = $GLOBALS['$dbFramework']->query("
				SELECT li.lead_id, li.lead_name, coalesce(li.lead_closed_reason,'-') as status
				FROM `lead_info` as li
				WHERE ((li.lead_rep_owner IN ('$children')) OR (li.lead_manager_owner in ('$children'))) AND (li.customer_id IS NULL OR li.customer_id='')
				ORDER BY li.lead_name");
				return $query->result();			
			}
		catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }	
		
	}
	public function fetch_Customers($user_id)	{
		try {
				/*
				fetch all customers for all user_ids under given user_id
				*/
				$children = $user_id."','";
				$children .= $this->getChildrenForParent($user_id);			
				$query = $GLOBALS['$dbFramework']->query("
				SELECT ci.customer_id, ci.customer_name
				FROM `customer_info` as ci
				WHERE  ((ci.customer_rep_owner IN ('$children')) OR (ci.customer_manager_owner in ('$children')))
				ORDER BY ci.customer_name");
				return $query->result();		
		}
		catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
		
	}

	public function fetch_Opportunity($user_id)	{
		try {				/*
				fetch all opportunity for all user_ids under given user_id
				*/
				$children = $user_id."','";
				$children .= $this->getChildrenForParent($user_id);			
				$query = $GLOBALS['$dbFramework']->query("
				SELECT od.opportunity_id, od.opportunity_name, coalesce(od.closed_reason,'-') as status
				FROM `opportunity_details` as od
				WHERE  ((od.owner_id IN ('$children')) OR (od.manager_owner_id in ('$children')) OR
				(od.stage_owner_id in ('$children')) OR (od.stage_manager_owner_id in ('$children')))
				ORDER BY od.opportunity_name");
				return $query->result();		
		}
		catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
		
	}
	
	public function fetch_contactTypes()	{
		try {
				$query = $GLOBALS['$dbFramework']->query("
				SELECT lookup_id AS contact_type_id, lookup_value AS contact_type_name 
				FROM lookup 
				WHERE lookup_name='Buyer Persona'");
				return $query->result();		
		}
		catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	
	}
	
	public function fetch_ContactsForLead($leadid, $user_id)	{
		try {
				$query = $GLOBALS['$dbFramework']->query("
				SELECT cd.contact_id, cd.leadid, cd.contact_name, cd.contact_desg, cd.contact_type, 
				cd.contact_email, cd.contact_email2, 
				cd.contact_phone1, cd.contact_phone2, cd.contact_phone3, cd.contact_phone4, 
				cd.contact_photo, cd.user, li.leadname, lo.lookup_value, lo.lookup_id
				FROM `contact_details` AS cd, `lead_info` AS li, `lookup` AS lo, `lead_rep_info` AS lri
				WHERE cd.leadid = li.leadid AND cd.leadid = '$leadid' AND lo.lookup_name = 'Bio Persona' AND lo.lookup_id=cd.contact_type AND li.leadid=lri.leadid AND lri.rep_id = '$user_id' AND li.leadstate >= 3 AND li.leadstate != 4
				GROUP BY cd.contact_id
				ORDER BY cd.contact_name");
				return $query->result();			
		}
		catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
		
	}

	public function insert_contact($data)	{
		try {
				$var = $GLOBALS['$dbFramework']->insert('contact_details', $data);
				return $var;		
			}
		catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }	
		
	}
	
	public function edit_contact($data,$contact_id)	{
		try {
				$var = $GLOBALS['$dbFramework']->update('contact_details', $data, array('contact_id' => $contact_id));
				return $var;		
		}
		catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	
	}

	public function getContacts(){
		$query = $GLOBALS['$dbFramework']->query("SELECT od.opportunity_id, od.opportunity_contact
				FROM opportunity_details AS od");
				return $query->result();	
	}

	public function update_oppoContact($data, $opportunity_id)
	{
		$query = $GLOBALS['$dbFramework']->query("UPDATE opportunity_details SET opportunity_contact ='$data'
													where opportunity_id ='$opportunity_id'");
				return $query;	
	}

}
?>
