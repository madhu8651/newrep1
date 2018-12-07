<?php
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('sales_contactListModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();
class sales_contactListModel extends CI_Model	{
	
	function __construct()	{
		parent::__construct();
	}

	public function fetch_Contacts($user_id)	{
		try {
				$query = $GLOBALS['$dbFramework']->query("
				(SELECT cd.contact_id AS contact_id, cd.contact_name AS contact_name, cd.contact_for AS contact_for,
					coalesce(lo.lookup_value,'-') AS contact_type_name, lo.lookup_id AS contact_type_id,
					li.lead_id AS lead_cust_id, li.lead_name AS lead_cust_name,
					JSON_UNQUOTE(cd.contact_email->'$.email') AS contact_email, 
					JSON_UNQUOTE(cd.contact_number->'$.phone') AS contact_number,
					coalesce(cd.contact_desg,'-') as contact_desg, cd.contact_photo, cd.contact_created_time,
					coalesce(cd.contact_dob,'-') AS contact_dob, cd.contact_address AS contact_address, cd.remarks AS remarks
				FROM lead_info AS li, contact_details AS cd LEFT JOIN `lookup` AS lo ON cd.contact_type = lo.lookup_id AND lo.lookup_name = 'Buyer Persona'
				WHERE li.lead_rep_owner='$user_id' AND (li.lead_id=cd.lead_cust_id)
				and cd.contact_id not in(select c1.contact_id from contact_details c1, lead_info li1, customer_info c2
                where  c1.lead_cust_id= li1.lead_id
                and c1.lead_cust_id = c2.lead_id
                and li1.lead_id = c2.lead_id)
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
				WHERE (ci.customer_rep_owner='$user_id') AND ((ci.customer_id=cd.lead_cust_id OR ci.lead_id = cd.lead_cust_id))
				GROUP BY cd.contact_id
				ORDER BY cd.contact_name)
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
						where co.contact_created_by = '$user_id'
                        AND co.lead_cust_id = opp.opportunity_id
						GROUP BY co.contact_id
						ORDER BY co.contact_name)
				ORDER BY contact_created_time desc
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
				//WHERE Condition for lead state needs to be put and 
				//also join Lead_sub_Info table to see which of the leads have i been assigned to.
				//obtain current user_id from the session variable
				$query = $GLOBALS['$dbFramework']->query("
				SELECT li.lead_id, li.lead_name
				FROM `lead_info` as li
				WHERE li.lead_rep_owner = '$user_id' AND (li.customer_id IS NULL OR li.customer_id='')
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
				//WHERE Condition for lead state needs to be put and 
				//also join Lead_sub_Info table to see which of the leads have i been assigned to.
				//obtain current user_id from the session variable
				$query = $GLOBALS['$dbFramework']->query("
				SELECT ci.customer_id, ci.customer_name
				FROM `customer_info` as ci
				WHERE ci.customer_rep_owner = '$user_id'
				ORDER BY ci.customer_name");
				return $query->result();
			}
		catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }	

	}

	public function fetch_Opportunity($user_id)	{
		try {
				
				$query = $GLOBALS['$dbFramework']->query("
				SELECT od.opportunity_id, od.opportunity_name, coalesce(od.closed_reason,'-') as status
				FROM `opportunity_details` as od
				WHERE (od.owner_id = '$user_id' or od.stage_owner_id = '$user_id')
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
				$query = $GLOBALS['$dbFramework']->query("SELECT lookup_id AS contact_type_id, lookup_value AS contact_type_name FROM lookup WHERE lookup_name='Buyer Persona'");
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
