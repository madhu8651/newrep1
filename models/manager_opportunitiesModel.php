<?php
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();
$GLOBALS['$log'] = Logger::getLogger('manager_opportunitiesModel');
class manager_opportunitiesModel extends CI_Model	{

	function __construct()	{
		parent::__construct();
        $this->load->model('common_opportunitiesModel','opp_common');
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-RECEIVED OPPO-=-=-=-=-=-=-=-=-=-=-=-=-*/
	public function fetch_received_opportunities($user){
		try {
			$query=$GLOBALS['$dbFramework']->query("
				SELECT
				a.opportunity_id as opportunity_id,a.opportunity_name as opportunity_name,a.cycle_id as cycle_id,
				a.opportunity_currency as opportunity_currency,
				a.sell_type as sell_type,
				a.opportunity_industry as opportunity_industry,hInd.hvalue2 as industry_name,
				a.opportunity_location as opportunity_location,hLoc.hvalue2 as location_name,
				coalesce(a.opportunity_value, '-') as opportunity_value,
				coalesce(a.opportunity_numbers, '-') as opportunity_quantity,
				coalesce(a.opportunity_date, '-') as expected_close_date,
	            a.opportunity_product as product_id,c.hvalue2 as product_name,
	            a.opportunity_stage as stage_id,i.stage_name as stage_name,
	            a.lead_cust_id, (SELECT CASE a.sell_type
            	WHEN 'new_sell' THEN (select distinct lead_name from lead_info where lead_id=a.lead_cust_id)
            	ELSE (select distinct customer_name from customer_info where customer_id=a.lead_cust_id)
    	        END) AS lead_cust_name,

				a.owner_manager_status as owner_manager_status, a.manager_owner_id as manager_owner_id,
				IF (a.manager_owner_id IS NOT NULL, (select user_name from user_details where user_id=a.manager_owner_id),'pending') as manager_owner_name,
				(SELECT count(*) from oppo_user_map
				where opportunity_id=a.opportunity_id and from_user_id='$user' and state=1 and action ='ownership rejected') as ownerreject,

	            a.stage_manager_owner_status as stage_manager_owner_status, a.stage_manager_owner_id as stage_manager_owner_id,
				IF (a.stage_manager_owner_id IS NOT NULL, (select user_name from user_details where user_id=a.stage_manager_owner_id),'pending') as stage_manager_owner_name,
				(SELECT count(*) from oppo_user_map
				where opportunity_id=a.opportunity_id and from_user_id='$user' and state=1 and action ='stage rejected') as stagereject

				FROM opportunity_details a
				LEFT JOIN hierarchy c on a.opportunity_product=c.hkey2
				LEFT JOIN user_details d on a.manager_owner_id=d.user_id
				LEFT JOIN user_details e on a.owner_id=e.user_id
				LEFT JOIN user_details f on a.stage_manager_owner_id=f.user_id
				LEFT JOIN user_details g on a.stage_owner_id=g.user_id
				LEFT JOIN sales_stage i on a.opportunity_stage=i.stage_id
				LEFT JOIN oppo_user_map h on a.opportunity_id=h.opportunity_id
				left join hierarchy hInd on a.opportunity_industry=hInd.hkey2
				left join hierarchy hLoc on a.opportunity_location=hLoc.hkey2

				WHERE a.opportunity_id=h.opportunity_id and h.to_user_id='$user' and h.state=1 and h.module='manager'
					and h.action in('stage assigned','ownership assigned','ownership reassigned','stage reassigned') and
					(a.owner_manager_status=1 or a.stage_manager_owner_status=1) and (a.closed_reason IS NULL)
				GROUP BY a.opportunity_id");
			return $query->result();
		} catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}
	}

    public function check_state($op_id,$opp_reject){
        try {
        	$return_status = array();
        	for($i=0;$i<count($opp_reject);$i++){
            	if($opp_reject[$i]=='Ownership'){
					$query=$GLOBALS['$dbFramework']->query("SELECT owner_manager_status from opportunity_details where opportunity_id='$op_id'");
					$opp= $query->result();
					$owner_status= $opp[0]->owner_manager_status;
					$return_status['Ownership'] = $owner_status;
            	} else if($opp_reject[$i]=='Stage_Ownership') {
					$query=$GLOBALS['$dbFramework']->query("SELECT stage_manager_owner_status from opportunity_details where opportunity_id='$op_id'");
					$opp= $query->result();
					$owner_status= $opp[0]->stage_manager_owner_status;
					$return_status['Stage_Ownership'] = $owner_status;
        	    }
    	    }
	        return $return_status;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function assign_count($opportunity, $remarks=''){
        try {
            $userid=$this->session->userdata('uid');
            $query1=$GLOBALS['$dbFramework']->query("
            	SELECT *
            	from oppo_user_map
            	where opportunity_id='$opportunity' and (action IN ('ownership assigned','ownership reassigned')) and state='1' and module='manager'");

            $ss = $query1->result();
            $data2= array(
                'mapping_id'=>uniqid(rand()),
                'opportunity_id'=>$opportunity,
                'lead_cust_id'=> $ss[0]->lead_cust_id,
                'from_user_id'=> $userid,
                'to_user_id'=> $ss[0]->from_user_id,
                'cycle_id'=> $ss[0]->cycle_id,
                'stage_id'=>$ss[0]->stage_id,
                'module'=>'manager' ,
                'sell_type'=> $ss[0]->sell_type,
                'timestamp'=> date('Y-m-d H:i:s'),
                'action' => 'ownership rejected',
                'state' => '1',
                'remarks' => $remarks
            );
            $insert = $GLOBALS['$dbFramework']->insert('oppo_user_map',$data2);

            $total_assigned = $query1->num_rows();

            $query2=$GLOBALS['$dbFramework']->query("
            	SELECT *
            	FROM oppo_user_map
            	WHERE opportunity_id='$opportunity' and (action='ownership rejected') and state='1' and module='manager'");

            $total_rejects = $query2->num_rows();

            if($total_rejects==$total_assigned){
                $query4=$GLOBALS['$dbFramework']->query("
                	UPDATE oppo_user_map
                	SET state='0'
                	WHERE opportunity_id='$opportunity' and state='1' and module='manager' and
                	(action IN ('ownership assigned','ownership reassigned','ownership accepted', 'ownership rejected'))");
                $query3=$GLOBALS['$dbFramework']->query("
                	UPDATE opportunity_details
                	SET owner_manager_status='3'
                	WHERE opportunity_id='$opportunity'");
            }
            return true;
        } catch (LConnectApplicationException $e){
        	$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function assign_count_stage($opportunity, $remarks=''){
        try {
            $userid=$this->session->userdata('uid');
            $query1=$GLOBALS['$dbFramework']->query("
            	SELECT *
            	from oppo_user_map
            	where opportunity_id='$opportunity' and (action IN ('stage assigned','stage reassigned')) and state='1' and module='manager'");

            $ss = $query1->result();
            $data2= array(
                'mapping_id'=>uniqid(rand()),
                'opportunity_id'=>$opportunity,
                'lead_cust_id'=> $ss[0]->lead_cust_id,
                'from_user_id'=> $userid,
                'to_user_id'=> $ss[0]->from_user_id,
                'cycle_id'=> $ss[0]->cycle_id,
                'stage_id'=>$ss[0]->stage_id,
                'module'=>'manager' ,
                'sell_type'=> $ss[0]->sell_type,
                'timestamp'=> date('Y-m-d H:i:s'),
                'action' => 'stage rejected',
                'state' => '1',
                'remarks' => $remarks
            );
            $insert = $GLOBALS['$dbFramework']->insert('oppo_user_map',$data2);

            $total_assigned = $query1->num_rows();

            $query2=$GLOBALS['$dbFramework']->query("
            	SELECT *
            	FROM oppo_user_map
            	WHERE opportunity_id='$opportunity' and (action='stage rejected') and state='1' and module='manager'");

            $total_rejects = $query2->num_rows();

            if($total_rejects==$total_assigned){
                $query4=$GLOBALS['$dbFramework']->query("
                	UPDATE oppo_user_map
                	SET state='0'
                	WHERE opportunity_id='$opportunity' and state='1' and module='manager' and
                	(action IN ('stage assigned','stage reassigned','stage accepted', 'stage rejected'))");
                $query3=$GLOBALS['$dbFramework']->query("
                	UPDATE opportunity_details
                	SET stage_manager_owner_status='3'
                	WHERE opportunity_id='$opportunity'");
            }
            return true;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

	/*-=-=-=-=-=-=-=-=-=-=-=-UNASSIGNED OPPO-=-=-=-=-=-=-=-=-=-=-=-=-*/
	public function fetch_unassigned_opportunities($manager_id){
		try {
			$children = "'".$manager_id."','";
			$children .= $this->getChildrenForParent($manager_id)."'";
            
			$query=$GLOBALS['$dbFramework']->query("
				SELECT
				a.opportunity_id as opportunity_id,a.opportunity_name as opportunity_name,a.cycle_id as cycle_id,
				a.opportunity_currency as opportunity_currency,
				a.sell_type as sell_type,
				a.opportunity_industry as opportunity_industry,hInd.hvalue2 as industry_name,
				a.opportunity_location as opportunity_location,hLoc.hvalue2 as location_name,
				coalesce(a.opportunity_value, '-') as opportunity_value,
				coalesce(a.opportunity_numbers, '-') as opportunity_quantity,
				coalesce(a.opportunity_date, '-') as expected_close_date,
				a.opportunity_product,(SELECT hvalue2 FROM hierarchy WHERE opportunity_product=hkey2) AS product_name,
				b.stage_id,(SELECT stage_name FROM sales_stage where stage_id=opportunity_stage ) AS stage_name,
				a.lead_cust_id,(CASE a.sell_type
					WHEN 'new_sell' THEN (SELECT distinct lead_name FROM lead_info WHERE lead_id=a.lead_cust_id )
					ELSE (SELECT distinct customer_name FROM customer_info WHERE customer_id=a.lead_cust_id)
				END) AS lead_cust_name,
				owner_status,stage_owner_status,
				(SELECT user_name FROM user_details WHERE user_id=owner_id) AS opp_rep,owner_id,owner_status AS opp_repstatus,
				(SELECT user_name FROM user_details WHERE user_id=manager_owner_id) AS opp_man,manager_owner_id,owner_manager_status AS opp_manstatus,
				(SELECT user_name FROM user_details WHERE user_id=stage_owner_id) AS stage_rep,stage_owner_id,stage_owner_status AS stage_repstatus,
				(SELECT user_name FROM user_details WHERE user_id=stage_manager_owner_id) AS stage_man,stage_manager_owner_id,stage_manager_owner_status AS stage_manstatus,to_user_id
				FROM oppo_user_map b, opportunity_details a
				left join hierarchy hInd on a.opportunity_industry=hInd.hkey2
				left join hierarchy hLoc on a.opportunity_location=hLoc.hkey2
				WHERE  closed_reason is NULL and
				a.opportunity_id=b.opportunity_id AND b.to_user_id in ($children) and module<>'sales' and
				action<>'ownership assigned'
				AND ((owner_id IS NULL AND owner_status=0) OR (stage_owner_id IS NULL AND stage_owner_status=0))
				AND ACTION='ownership accepted'
				GROUP BY a.opportunity_id");
                /*WHERE a.manager_owner_id in ($children) and */
				return $query->result();
		} catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}
	}

	public function reset_status($opp_id,$fieldname,$status)	{
		try {
			$query = $GLOBALS['$dbFramework']->query("UPDATE opportunity_details set $fieldname='$status' where opportunity_id='$opp_id'");
			return $query;
		} catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-ASSIGNED OPPO-=-=-=-=-=-=-=-=-=-=-=-=-*/
	public function fetch_assigned_opportunities($manager_id,$status){
		try {
			$children = $manager_id."','";
			$children .= $this->getChildrenForParent($manager_id); 
            if($status == 'teamopp'){
                $query=$GLOBALS['$dbFramework']->query("
				SELECT
				a.opportunity_id as opportunity_id,a.opportunity_name as opportunity_name,a.cycle_id as cycle_id,
				a.opportunity_currency as opportunity_currency,
				a.sell_type as sell_type,
				a.opportunity_industry as opportunity_industry,hInd.hvalue2 as industry_name,
				a.opportunity_location as opportunity_location,hLoc.hvalue2 as location_name,
				coalesce(a.opportunity_value, '-') as opportunity_value,
				coalesce(a.opportunity_numbers, '-') as opportunity_quantity,
				coalesce(a.opportunity_date, '-') as expected_close_date,
				b.stage_id, coalesce((SELECT stage_name FROM sales_stage where stage_id=opportunity_stage ),'-') AS stage_name,
				opportunity_product,
                (SELECT count(*) FROM oppo_product_map opm where opm.opportunity_id=a.opportunity_id) as product_name,

				a.lead_cust_id, (CASE a.sell_type
					WHEN 'new_sell' THEN (SELECT distinct coalesce(lead_name,'-') FROM lead_info WHERE lead_id=a.lead_cust_id )
					ELSE (SELECT distinct customer_name FROM customer_info WHERE customer_id=a.lead_cust_id)
				END) AS lead_cust_name,
				(SELECT user_name FROM user_details WHERE user_id=owner_id) AS opp_rep,owner_id,owner_status AS opp_repstatus,
				(SELECT user_name FROM user_details WHERE user_id=manager_owner_id) AS opp_man,manager_owner_id,owner_manager_status AS opp_manstatus,
				(SELECT user_name FROM user_details WHERE user_id=stage_owner_id) AS stage_rep,stage_owner_id,stage_owner_status AS stage_repstatus,
				(SELECT user_name FROM user_details WHERE user_id=stage_manager_owner_id) AS stage_man,stage_manager_owner_id,stage_manager_owner_status AS stage_manstatus,to_user_id
				FROM oppo_user_map b, opportunity_details a
					left join hierarchy hInd on a.opportunity_industry=hInd.hkey2
					left join hierarchy hLoc on a.opportunity_location=hLoc.hkey2
				WHERE a.closed_reason is NULL AND (
                    ((a.stage_owner_id in ('$children') and a.stage_manager_owner_id not in ('$children')) or
                        (a.owner_id in ('$children') and a.manager_owner_id not in ('$children'))  )
					and
					((a.owner_status='1' or a.owner_status='2' or a.owner_status='3') or
					(a.stage_owner_status='1' or a.stage_owner_status='2' or a.stage_owner_status='3'))
				) and
				a.opportunity_id=b.opportunity_id AND b.to_user_id in ('$children') and module in ('sales','manager')
				and (b.action IN ('ownership assigned','ownership reassigned','ownership accepted','stage assigned', 'stage reassigned', 'stage accepted'))
				group by a.opportunity_id");

            }else{

                $query=$GLOBALS['$dbFramework']->query("
				SELECT
				a.opportunity_id as opportunity_id,a.opportunity_name as opportunity_name,a.cycle_id as cycle_id,
				a.opportunity_currency as opportunity_currency,
				a.sell_type as sell_type,
				a.opportunity_industry as opportunity_industry,hInd.hvalue2 as industry_name,
				a.opportunity_location as opportunity_location,hLoc.hvalue2 as location_name,
				coalesce(a.opportunity_value, '-') as opportunity_value,
				coalesce(a.opportunity_numbers, '-') as opportunity_quantity,
				coalesce(a.opportunity_date, '-') as expected_close_date,
				b.stage_id, coalesce((SELECT stage_name FROM sales_stage where stage_id=opportunity_stage ),'-') AS stage_name,
				opportunity_product,
                (SELECT count(*) FROM oppo_product_map opm where opm.opportunity_id=a.opportunity_id) as product_name,

				a.lead_cust_id, (CASE a.sell_type
					WHEN 'new_sell' THEN (SELECT distinct coalesce(lead_name,'-') FROM lead_info WHERE lead_id=a.lead_cust_id )
					ELSE (SELECT distinct customer_name FROM customer_info WHERE customer_id=a.lead_cust_id)
				END) AS lead_cust_name,
				(SELECT user_name FROM user_details WHERE user_id=owner_id) AS opp_rep,owner_id,owner_status AS opp_repstatus,
				(SELECT user_name FROM user_details WHERE user_id=manager_owner_id) AS opp_man,manager_owner_id,owner_manager_status AS opp_manstatus,
				(SELECT user_name FROM user_details WHERE user_id=stage_owner_id) AS stage_rep,stage_owner_id,stage_owner_status AS stage_repstatus,
				(SELECT user_name FROM user_details WHERE user_id=stage_manager_owner_id) AS stage_man,stage_manager_owner_id,stage_manager_owner_status AS stage_manstatus,to_user_id
				FROM oppo_user_map b, opportunity_details a
					left join hierarchy hInd on a.opportunity_industry=hInd.hkey2
					left join hierarchy hLoc on a.opportunity_location=hLoc.hkey2
				WHERE a.closed_reason is NULL AND (
                    ((a.stage_manager_owner_id in ('$children')) or
                        (a.manager_owner_id in ('$children')) )
					and
					((a.owner_status='1' or a.owner_status='2' or a.owner_status='3') or
					(a.stage_owner_status='1' or a.stage_owner_status='2' or a.stage_owner_status='3'))
				) and
				a.opportunity_id=b.opportunity_id AND b.to_user_id in ('$children') and module in ('sales', 'manager')
				and (b.action IN ('ownership assigned','ownership reassigned','ownership accepted','stage assigned', 'stage reassigned', 'stage accepted'))
				group by a.opportunity_id");


            }


                /* ((a.manager_owner_id in ('$children') or a.owner_id IN ('$children')) or
					(a.stage_manager_owner_id in ('$children') or a.stage_owner_id IN ('$children'))) */

			return $query->result();
		} catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}
	}

	public function reassign_reset($opp_id,$updateData)	{
		try {
			$var = $GLOBALS['$dbFramework']->update('opportunity_details', $updateData, array('opportunity_id' => $opp_id));
            return $var;
		} catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-CLOSED OPPO-=-=-=-=-=-=-=-=-=-=-=-=-*/
	public function fetch_closed_opportunities($managerid,$opt)	{
		try {
			$children = $managerid."','";
			$children .= $this->getChildrenForParent($managerid);
				// od.cycle_id as cycle_id,
				// od.sell_type as sell_type,
				// od.opportunity_stage AS stage_id,
				// od.lead_cust_id AS lead_cust_id,
				// od.opportunity_currency as opportunity_currency,
				// od.opportunity_industry as opportunity_industry,
				// od.opportunity_location as opportunity_location,
                //	LEFT JOIN user_details ud ON ud.user_id = od.stage_owner_id
            if($opt=='myopp')
            {
              $heirarchyString="and (
					(od.manager_owner_id in ('$children') or
					od.stage_manager_owner_id in ('$children'))
				)";
                   /* od.owner_id IN ('$children') or
					od.stage_owner_id IN ('$children')*/
            }else{
               $heirarchyString="and (
					(od.manager_owner_id not in ('$children') and od.owner_id IN ('$children'))  or
					(od.stage_manager_owner_id not in ('$children') and od.stage_owner_id IN ('$children'))
				)";
            }
			$query=$GLOBALS['$dbFramework']->query("
				SELECT
				od.opportunity_id as opportunity_id,od.opportunity_name as opportunity_name,
				hInd.hvalue2 as industry_name,
				hLoc.hvalue2 as location_name,
				od.closed_reason as reason,
				coalesce(od.opportunity_value, '-') as opportunity_value,
				coalesce(od.opportunity_numbers, '-') as opportunity_quantity,
				(Select date_format(TIMESTAMP, '%d-%m-%Y') from oppo_user_map where opportunity_id=od.opportunity_id and action ='closed won') as expected_close_date,
				(CASE od.sell_type
					WHEN 'new_sell' THEN (select distinct lead_name from lead_info where lead_id=od.lead_cust_id)
					ELSE (select distinct customer_name from customer_info where customer_id=od.lead_cust_id)
				END) AS lead_cust_name,
				coalesce(hProd.hvalue2,'-') as product,
				coalesce(ss.stage_name,'-') as stage_name,
				coalesce(ud.user_name,'-') as stage_owner
				FROM oppo_user_map oum, opportunity_details od
				left join hierarchy hInd on od.opportunity_industry=hInd.hkey2
				left join hierarchy hLoc on od.opportunity_location=hLoc.hkey2
				LEFT JOIN hierarchy hProd ON od.opportunity_product=hProd.hkey2
				LEFT JOIN sales_stage ss ON ss.stage_id=od.opportunity_stage
                LEFT JOIN user_details ud ON ud.user_id=(select from_user_id from oppo_user_map oum where oum.opportunity_id=od.opportunity_id AND oum.action IN('closed won') )
				WHERE od.closed_reason is not NULL and od.closed_reason ='closed_won' ".$heirarchyString."
				group by  od.opportunity_id ");
			return $query->result();
		} catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}
	}

    public function fetch_closed_lost_opportunities($managerid,$opt)	{
		try {
			$children = $managerid."','";
			$children .= $this->getChildrenForParent($managerid);
            if($opt=='myopp')
            {
              $heirarchyString="and (
					(od.manager_owner_id in ('$children') or
					od.stage_manager_owner_id in ('$children'))
				)";
                   /* od.owner_id IN ('$children') or
					od.stage_owner_id IN ('$children')*/
            }else{
               $heirarchyString="and (
					(od.manager_owner_id not in ('$children') and od.owner_id IN ('$children'))  or
					(od.stage_manager_owner_id not in ('$children') and od.stage_owner_id IN ('$children'))
				)";
            }
			$query=$GLOBALS['$dbFramework']->query("
				SELECT
				od.opportunity_id as opportunity_id,od.opportunity_name as opportunity_name,od.stage_owner_id as stage_owner_id,opportunity_contact,
				hInd.hvalue2 as industry_name,
				hLoc.hvalue2 as location_name,
				od.closed_reason as reason,
				coalesce(od.opportunity_value, '-') as opportunity_value,
				coalesce(od.opportunity_numbers, '-') as opportunity_quantity,
				(Select date_format(TIMESTAMP, '%d-%m-%Y') from oppo_user_map where opportunity_id=od.opportunity_id and action in ('temporary loss','permanent loss') and state=1) as expected_close_date,od.lead_cust_id,
				(CASE od.sell_type
					WHEN 'new_sell' THEN (select distinct lead_name from lead_info where lead_id=od.lead_cust_id)
					ELSE (select distinct customer_name from customer_info where customer_id=od.lead_cust_id)
				END) AS lead_cust_name,
				coalesce(hProd.hvalue2,'-') as product,
				coalesce(ss.stage_name,'-') as stage_name,
				coalesce(ud.user_name,'-') as stage_owner
				FROM oppo_user_map oum, opportunity_details od
				left join hierarchy hInd on od.opportunity_industry=hInd.hkey2
				left join hierarchy hLoc on od.opportunity_location=hLoc.hkey2
				LEFT JOIN hierarchy hProd ON od.opportunity_product=hProd.hkey2
				LEFT JOIN sales_stage ss ON ss.stage_id=od.opportunity_stage
                LEFT JOIN user_details ud ON ud.user_id=(select from_user_id from oppo_user_map oum where oum.opportunity_id=od.opportunity_id AND oum.action IN('permanent loss','temporary loss') and oum.state=1)
				WHERE od.closed_reason is not NULL and od.closed_reason in ('permanent_loss','temporary_loss')  ".$heirarchyString."
				group by  od.opportunity_id  ");


			$opp_close=$query->result();
           /********** query to check whether logged in user has the authority to delete the lead or not and give authority for reopen and
             to check whether the lead is not closedwon******************/
            for($i=0;$i<count($query->result_array());$i++)
            {
               //echo"SELECT lead_id FROM lead_info where ('$user'=lead_manager_owner or lead_rep_owner='$user') and lead_id='".$opp_close[$i]->lead_cust_id."'";

                //closed won leads cannot be reopened
                $query1=$GLOBALS['$dbFramework']->query("SELECT lead_id FROM lead_info where  lead_id='".$opp_close[$i]->lead_cust_id."' and lead_status<>2");
                //$check_reopen=$query1->result_array();
                if($query1->num_rows()>0)
                {
                   // lead is in progress
                  $query2=$GLOBALS['$dbFramework']->query("SELECT lead_id FROM lead_info where lead_id='".$opp_close[$i]->lead_cust_id."'
                                                           and lead_status NOT IN(3,4);");
                  //$check_reopen1=$query2->result_array();
                  if($query2->num_rows()>0)
                  {
                     $opp_close[$i]->reopen='true';
                     $opp_close[$i]->reopen_reason='';
                  }
                  else
                  {
                      $query3=$GLOBALS['$dbFramework']->query("SELECT lead_id FROM lead_info where ('$managerid'=lead_manager_owner or lead_rep_owner='$managerid')
                                                               and lead_id='".$opp_close[$i]->lead_cust_id."' and lead_status  IN(3,4); ");
                      //$check_reopen2=$query3->result_array();
                      if($query3->num_rows()>0)
                      {
                         $opp_close[$i]->reopen='true';
                         $opp_close[$i]->reopen_reason='Reopening Opportunity will reopen the Lead';
                      }else{
                         $opp_close[$i]->reopen='false';
                         $opp_close[$i]->reopen_reason='Unable to reopen Opportunity since the Lead is closed';
                      }
                  }
                }else{
                  $opp_close[$i]->reopen='false';
                  $opp_close[$i]->reopen_reason='Unable to reopen Opportunity since the Lead is closed won';
                }
                /*************** query to populate the list of activities incase of temporary loss********************/
                $data = array();
			    $data['opportunity_contact'] = $opp_close[$i]->opportunity_contact;
                $contacts = $this->opp_common->fetch_extraDetails($data);
               //print_r($contacts);
    			$contact_array = array();
    			foreach ($contacts as $c) {
    				array_push($contact_array, array('contact_id' => $c->contact_id, 'contact_name' => $c->contact_name));
    			}
    			$opp_close[$i]->contactlist=$contact_array;
            }

            $query_activity=$GLOBALS['$dbFramework']->query("SELECT * FROM lookup WHERE lookup_name='activity'");
            $activitydata=$query_activity->result();

            return array(
                  'opportunitydata'=>$opp_close,
                  'activitydata'=>$activitydata
            );
		} catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}
	}

    //****************** function to reopen,perm-loss,temp-loss the opportunity**********************************//
    public function changestate($opp_usermap_data,$updateoppdetails,$extradata)
    {
     try{
            if($opp_usermap_data['action']=='reopen')
            {
              // condition to check whether the lead is been closed,if closed then reopen the lead and then go ahead with reopening of opportunity
              //this happens only if the logged in user is the lead manager owner or lead repowner
              if($extradata['leadclosedstatus']==3 || $extradata['leadclosedstatus']==4) //which means lead is 3-temporary or 4-permanent closed
              {
                    $lead1=array(
                    'lead_closed_reason'=>NULL,
                    'lead_status'=>1
                    );
                    $lead2=array(
                    'state'=>0
                    );
                    $lead3=array(
                          'state'=>1,
                          'action'=>'reopened',
                          'from_user_id'=>$opp_usermap_data['from_user_id'],
                          'to_user_id'=>$opp_usermap_data['from_user_id'],
                          'type'=>'lead',
                          'mapping_id'=>uniqid(rand(),TRUE),
                          'module'=>'manager',
                          'timestamp'=>date('Y-m-d H:i:s'),
                          'lead_cust_id' =>$opp_usermap_data['lead_cust_id'],
                    );
                 	$query_lead1= $GLOBALS['$dbFramework']->update('lead_info',$lead1,array('lead_id'=> $opp_usermap_data['lead_cust_id']));
        			$query_lead2= $GLOBALS['$dbFramework']->update('lead_cust_user_map',$lead2, array('lead_cust_id'=> $opp_usermap_data['lead_cust_id']));
        			$query_lead3= $GLOBALS['$dbFramework']->insert('lead_cust_user_map',$lead3);
              }
            }
            if($opp_usermap_data['action']=='temporary loss')
            {
               // check whether the associated lead is in permanent loss state,if yes check whether loggedin user has authority to change the state of the lead
               // if not then prompt the user that he cannot change the state of the opportunity to temporary loss since lead is in permanent loss state
               if($extradata['leadclosedstatus']==4)
               {
                  $auth=$GLOBALS['$dbFramework']->query("SELECT lead_id FROM lead_info where
                                                 ((lead_manager_owner='".$opp_usermap_data['from_user_id']."') or (lead_rep_owner='".$opp_usermap_data['from_user_id']."'))
                                                 and lead_id='".$opp_usermap_data['lead_cust_id']."'");
                  //$check_auth=$auth->result_array();
                  if($auth->num_rows()<=0)
                  {
                    return 2;
                  }else{
                           $lead1=array(
                                  'lead_closed_reason'=>'temporary_loss',
                                  'lead_status'=>3,
                                  'lead_approach_date' =>$extradata['approachdate'],
                                  );
                          $lead2=array(
                                  'state'=>0
                                  );
                          $lead3=array(
                                  'state'=>1,
                                  'action'=>'closed',
                                  'from_user_id'=>$opp_usermap_data['from_user_id'],
                                  'to_user_id'=>$opp_usermap_data['from_user_id'],
                                  'type'=>'lead',
                                  'mapping_id'=>uniqid(rand(),TRUE),
                                  'module'=>'manager',
                                  'timestamp'=>date('Y-m-d H:i:s'),
                                  'lead_cust_id' =>$opp_usermap_data['lead_cust_id'],
                                  'mapping_id' =>uniqid(rand(),TRUE),
                                  'remarks'=>'Lead closed temporarily due to temporary close of opportunity',
                                  );
                       	$query_lead1= $GLOBALS['$dbFramework']->update('lead_info',$lead1,array('lead_id'=> $opp_usermap_data['lead_cust_id']));
              			$query_lead2= $GLOBALS['$dbFramework']->update('lead_cust_user_map',$lead2, array('lead_cust_id'=> $opp_usermap_data['lead_cust_id']));
              			$query_lead3= $GLOBALS['$dbFramework']->insert('lead_cust_user_map',$lead3);
                   }
               }// end of lead closed state 4
                        // inserting task for temp_loss
                        $dt = date('ymdHis');
                        $lead_reminder_id = '';
                        $lead_reminder_id .= $dt;
                        $lead_reminder_id = uniqid($lead_reminder_id);
                        //calculate the meeting end
                        $duration=$extradata['activityDuration'];
                        $seconds = new DateTime("1970-01-01 $duration", new DateTimeZone('UTC'));
                        $activity_duration = (int)$seconds->getTimestamp();
                        $start = new DateTime($extradata['approachdate']);
                        $event_end = $start->add(new DateInterval('PT'.$activity_duration.'S')); // adds 674165 secs
                        $event_end = $event_end->format('Y-m-d H:i:s');
                        $event_start_date=date('Y-m-d', strtotime($extradata['approachdate']));
                        $event_start_time = date('H:i', strtotime($extradata['approachdate']));
                        $event_start=$start->format('Y-m-d H:i:s');
                        $data_leadreminder = array(
                                        'lead_reminder_id' => $lead_reminder_id,
                                        'lead_id'   => $opp_usermap_data['opportunity_id'],
                                        'opportunity_id' => $opp_usermap_data['stage_id'],
                                        'rep_id'    => $opp_usermap_data['from_user_id'],
                                        'leadempid' => $extradata['contactType'],  //'contactid',
                                        'remi_date' => $event_start_date,
                                        'rem_time'  => $event_start_time,
                                        'conntype'  => $extradata['futureActivity'],
                                        'status'    => "scheduled",
                                        'meeting_start'    => $event_start,
                                        'meeting_end'      => $event_end,
                                        'addremtime'       => $extradata['alertBefore'],
                                        'timestamp'        => date('Y-m-d H:i:s'),
                                        'remarks'          => $extradata['remarks'],
                                        'event_name'       => $extradata['title'],
                                        'duration'         => $extradata['activityDuration'],
                                        'type' => "opportunity",
                                        'created_by'=>$opp_usermap_data['from_user_id'],
                                        'module_id'=>'manager'
                        );
                        //inserting data in lead reminder
                        $insert_leadreminder = $GLOBALS['$dbFramework']->insert('lead_reminder',$data_leadreminder);
            }

            $update = $GLOBALS['$dbFramework']->update('opportunity_details' ,$updateoppdetails, array('opportunity_id' => ($opp_usermap_data['opportunity_id'])));
            if($update)
            {
                //this will help to show only those opportunities which are closed and not reopened yet.
               $update_opp2 = $GLOBALS['$dbFramework']->query("Update oppo_user_map set state='0' where opportunity_id='".$opp_usermap_data['opportunity_id']."' and
                                                       (action='permanent loss' or action='temporary loss')");


               $insert = $GLOBALS['$dbFramework']->insert('oppo_user_map',$opp_usermap_data);


                //insertion in notification table
                // in case the opportunity is reopenede before assiging to any rep then stage_owner_id will be null

                  $dt = date('ymdHis');
                  $notify_id= uniqid($dt);
                  $data_notify= array(
                                'notificationID' =>$notify_id,
                                'from_user'=>$opp_usermap_data['from_user_id'],
                                'to_user'=>$extradata['stage_owner_id'],
                                'action_details'=>'Opportunity',
                                'notificationTimestamp'=>date('Y-m-d H:i:s'),
                                'read_state'=>0,
                                'remarks'=>$extradata['remarks']
                              );

                   $data_notify['to_user']=$extradata['stage_owner_id'];
                    if($extradata['stage_owner_id']==null || $extradata['stage_owner_id']==''){
                        $data_notify['to_user']=$extradata['stage_manager_owner_id'];
                        if($extradata['stage_manager_owner_id']==null || $extradata['stage_manager_owner_id']=='')
                        {
                          $data_notify['to_user']=$extradata['manager_owner_id'];
                        }
                    }
                  if($opp_usermap_data['action']=='permanent loss')
                  {
                       $data_notify['notificationShortText']='Opportunity State changed to Closed(Permanent)';
                       $data_notify['notificationText']='Opportunity '.$extradata['opportunity_name'].' closed by '.$extradata['loggedin_username'];
                  }else if($opp_usermap_data['action']=='temporary loss'){
                        if($extradata['leadclosedstatus']==4)
                          {
                             $data_notify['notificationShortText']='State of Lead and Opportunity changed to Closed(Temporary)';
                             $data_notify['notificationText'] =$extradata['lead_cust_name'].' Lead closed Since Opportunity '.$extradata['opportunity_name'].' closed and a reminder task created by'.$extradata['loggedin_username'];
                          }else{
                             $data_notify['notificationShortText']='State of Opportunity changed to Closed(Temporary)';
                             $data_notify['notificationText'] ='Opportunity '.$extradata['opportunity_name'].' closed and a reminder task created by '.$extradata['loggedin_username'];
                          }
                  }else if($opp_usermap_data['action']=='reopen'){
                          if($extradata['leadclosedstatus']==3 || $extradata['leadclosedstatus']==4) //which means lead is 3-temporary or 4-permanent closed
                          {
                             $data_notify['notificationShortText']='Lead and Opportunity re-opened';
                             $data_notify['notificationText'] =' Lead '.$extradata['lead_cust_name'].' and Opportunity '.$extradata['opportunity_name'].' reopened by '.$extradata['loggedin_username'];
                          }else{
                             $data_notify['notificationShortText']='Opportunity re-opened';
                             $data_notify['notificationText'] ='Opportunity '.$extradata['opportunity_name'].' reopened by '.$extradata['loggedin_username'];
                          }
                  }
                  $insert_notify = $GLOBALS['$dbFramework']->insert('notifications',$data_notify);

               return $insert;
            }else{
               return 0;
            }
    	} catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

     //****************** function to reopen,perm-loss,temp-loss the opportunity**********************************//
    public function reopen($opp_usermap_data,$updateoppdetails,$extradata)
    {
     try{
              // condition to check whether the lead is been closed,if closed then reopen the lead and then go ahead with reopening of opportunity
              //this happens only if the logged in user is the lead manager owner or lead repowner
              if($extradata['leadclosedstatus']==3 || $extradata['leadclosedstatus']==4) //which means lead is 3-temporary or 4-permanent closed
              {
                    $lead1=array(
                    'lead_closed_reason'=>NULL,
                    'lead_status'=>1
                    );
                    $lead2=array(
                    'state'=>0
                    );
                    $lead3=array(
                    'state'=>1,
                    'action'=>'reopened',
                    'from_user_id'=>$opp_usermap_data['from_user_id'],
                    'to_user_id'=>$opp_usermap_data['from_user_id'],
                    'type'=>'lead',
                    'mapping_id'=>uniqid(rand(),TRUE),
                    'module'=>'sales',
                    'timestamp'=>date('Y-m-d H:i:s'),
                    'lead_cust_id' =>$opp_usermap_data['lead_cust_id'],
                    );
                 	$query_lead1= $GLOBALS['$dbFramework']->update('lead_info',$lead1,array('lead_id'=> $opp_usermap_data['lead_cust_id']));
        			$query_lead2= $GLOBALS['$dbFramework']->update('lead_cust_user_map',$lead2, array('lead_cust_id'=> $opp_usermap_data['lead_cust_id']));
        			$query_lead3= $GLOBALS['$dbFramework']->insert('lead_cust_user_map',$lead3);
              }

            $update = $GLOBALS['$dbFramework']->update('opportunity_details' ,$updateoppdetails, array('opportunity_id' => ($opp_usermap_data['opportunity_id'])));
            if($update)
            {
                //this will help to show only those opportunities which are closed and not reopened yet.
               $update_opp2 = $GLOBALS['$dbFramework']->query("Update oppo_user_map set state='0' where opportunity_id='".$opp_usermap_data['opportunity_id']."' and
                                                       (action='permanent loss' or action='temporary loss')");


               $insert = $GLOBALS['$dbFramework']->insert('oppo_user_map',$opp_usermap_data);


                 // in case the opportunity is reopenede before assiging to any rep then stage_owner_id will be null

               //insertion in notification table
                $dt = date('ymdHis');
                $notify_id= uniqid($dt);
                $data_notify= array(
                              'notificationID' =>$notify_id,
                              'from_user'=>$opp_usermap_data['from_user_id'],
                              'to_user'=>$extradata['stage_owner_id'],
                              'action_details'=>'Opportunity',
                              'notificationTimestamp'=>date('Y-m-d H:i:s'),
                              'read_state'=>0,
                              'remarks'=>$extradata['remarks']
                            );
                  $data_notify['to_user']=$extradata['stage_owner_id'];
                    if($extradata['stage_owner_id']==null || $extradata['stage_owner_id']==''){
                        $data_notify['to_user']=$extradata['stage_manager_owner_id'];
                        if($extradata['stage_manager_owner_id']==null || $extradata['stage_manager_owner_id']=='')
                        {
                          $data_notify['to_user']=$extradata['manager_owner_id'];
                        }
                    }

                        if($extradata['leadclosedstatus']==3 || $extradata['leadclosedstatus']==4) //which means lead is 3-temporary or 4-permanent closed
                        {
                           $data_notify['notificationShortText']='Lead and Opportunity re-opened';
                           $data_notify['notificationText'] =' Lead '.$extradata['lead_cust_name'].' and Opportunity '.$extradata['opportunity_name'].' reopened ';
                        }else{
                           $data_notify['notificationShortText']='Opportunity re-opened';
                           $data_notify['notificationText'] ='Opportunity '.$extradata['opportunity_name'].' reopened';
                        }
                $insert_notify = $GLOBALS['$dbFramework']->insert('notifications',$data_notify);

                return $insert;
            }else{
               return 0;
            }
    	} catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

	public function fetch_users($data,$assign_data)	{
		//fetch children and run below query for children
		try {
			$prod_id = $data->prod_id;
			$currency = $data->currency;
			$ind_id = $data->ind_id;
			$loc_id = $data->loc_id;
			$opp_id = $data->opp_id;
			$sell_type = $data->sell_type;
			$manager_id = $data->user_id;
			$children = $manager_id."','";
			$children .= $this->getChildrenForParent($manager_id);
			if ($assign_data == 'oppOwner')
			{
				$query = $GLOBALS['$dbFramework']->query("
					SELECT um.user_id,ud.user_name,
					#if user has sales module and its not assigned or reassigned or accepted by him
				    #then return 1 so that he can assign
				    #else return 0
					(CASE WHEN
						(ul.sales_module <> '0') AND (SELECT COUNT(oum.to_user_id)
							FROM oppo_user_map oum
							WHERE oum.opportunity_id IN ('$opp_id')
								AND oum.action IN ( 'ownership accepted','ownership assigned', 'ownership reassigned','ownership rejected')
								AND oum.state = 1
                                AND oum.module = 'sales'
								AND oum.to_user_id = um.user_id) = 0
						THEN 1
						ELSE 0
					END) AS sales_module,
					(CASE WHEN
						(ul.manager_module <> '0') AND (SELECT COUNT(oum.to_user_id)
							FROM oppo_user_map oum
							WHERE oum.opportunity_id IN ('$opp_id')
								AND oum.action IN ('ownership assigned', 'ownership reassigned','ownership accepted','ownership rejected')
								AND oum.state = 1
								AND oum.module = 'manager'
								AND oum.to_user_id = um.user_id) = 0
						THEN 1
						ELSE 0
					END) AS manager_module
				FROM
					user_mappings um,
					opportunity_details od,
                    oppo_product_map opm,
					user_licence ul,
					user_details ud
				WHERE
					um.map_type = 'product'
					AND od.opportunity_id IN ('$opp_id')
                    AND opm.opportunity_id = od.opportunity_id
					AND um.map_id = opm.product_id
					AND ul.user_id = um.user_id
					AND ud.user_id = um.user_id
				GROUP BY um.user_id
				HAVING COUNT(distinct opm.product_id) = (SELECT COUNT(distinct product_id)
					FROM oppo_product_map opm
					WHERE opm.opportunity_id IN ('$opp_id'))
					AND um.user_id IN (SELECT ud1.user_id
					FROM opportunity_details od,
						user_details ud1,
						user_mappings um1,
						user_mappings um2,
						user_mappings um3
					WHERE (ud1.user_id IN ('$children'))
						AND od.opportunity_id IN ('$opp_id')
						AND ud1.user_state = 1
						AND ud1.user_id = um1.user_id
						AND ud1.user_id = um2.user_id
						AND ud1.user_id = um3.user_id
						AND um3.map_type = 'sell_type' AND um3.map_id = od.sell_type
						AND um1.map_type = 'clientele_industry' AND um1.map_id = od.opportunity_industry
                        AND um2.map_type = 'business_location' AND um2.map_id = od.opportunity_location)
						order by opm.product_id");
			}
			else
			{
				$query = $GLOBALS['$dbFramework']->query("
					SELECT um.user_id,ud.user_name,
					#if user has sales module and its not assigned or reassigned or accepted by him
				    #then return 1 so that he can assign
				    #else return 0
					(CASE WHEN
						(ul.sales_module <> '0') AND (SELECT COUNT(oum.to_user_id)
							FROM oppo_user_map oum
							WHERE oum.opportunity_id IN ('$opp_id')
								AND oum.action IN ('stage reassigned','stage assigned' , 'stage rejected','stage accepted')
								AND oum.state = 1
                                AND oum.module = 'sales'
								AND oum.to_user_id = um.user_id) = 0
						THEN 1
						ELSE 0
					END) AS sales_module,
					(CASE WHEN
						(ul.manager_module <> '0') AND (SELECT COUNT(oum.to_user_id)
							FROM oppo_user_map oum
							WHERE oum.opportunity_id IN ('$opp_id')
								AND oum.action IN ('stage assigned', 'stage reassigned', 'stage accepted', 'stage rejected')
								AND oum.state = 1
								AND oum.module = 'manager'
								AND oum.to_user_id = um.user_id) = 0
						THEN 1
						ELSE 0
					END) AS manager_module
				FROM
					user_mappings um,
					opportunity_details od,
                    oppo_product_map opm,
					user_licence ul,
					user_details ud
				WHERE
					um.map_type = 'product'
					AND od.opportunity_id IN ('$opp_id')
                    AND opm.opportunity_id = od.opportunity_id
					AND um.map_id = opm.product_id
					AND ul.user_id = um.user_id
					AND ud.user_id = um.user_id
				GROUP BY um.user_id
				HAVING COUNT(distinct opm.product_id) = (SELECT COUNT(distinct product_id)
					FROM oppo_product_map opm
					WHERE opm.opportunity_id IN ('$opp_id'))
					AND um.user_id IN (SELECT ud1.user_id
					FROM opportunity_details od,
						user_details ud1,
						user_mappings um1,
						user_mappings um2,
						user_mappings um3
					WHERE (ud1.user_id IN ('$children'))
						AND od.opportunity_id IN ('$opp_id')
						AND ud1.user_state = 1
						AND ud1.user_id = um1.user_id
						AND ud1.user_id = um2.user_id
						AND ud1.user_id = um3.user_id
						AND um3.map_type = 'sell_type' AND um3.map_id = od.sell_type
						AND um1.map_type = 'clientele_industry' AND um1.map_id = od.opportunity_industry
                        AND um2.map_type = 'business_location' AND um2.map_id = od.opportunity_location)
						order by opm.product_id");
			}

			return $query->result();
		} catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}
	}

	public function owner_manager_status($opp_id){
    	try{
    		$query=$GLOBALS['$dbFramework']->query("
    			SELECT owner_manager_status FROM opportunity_details WHERE opportunity_id='$opp_id'");
    		return $query->result();
    	} catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function stage_manager_owner_status($opp_id){
    	try{
    		$query=$GLOBALS['$dbFramework']->query("select stage_manager_owner_status from opportunity_details where opportunity_id='$opp_id'");
    		return $query->result();
    	} catch (LConnectApplicationException $e) {
    		$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

	public function update_oppo_transaction($opportunity_id, $module, $type='') {
		try {
			$action = '';
			if ($type == 'ownership') {
				$action = 'ownership assigned\',\'ownership reassigned\',\'ownership accepted\', \'ownership rejected';
			} else if ($type == 'stage') {
				$action = 'stage assigned\',\'stage reassigned\',\'stage accepted\', \'stage rejected';
			} else {
				$action = 'ownership assigned\',\'ownership reassigned\',\'ownership accepted\', \'ownership rejected\',\'stage assigned\',\'stage reassigned\',\'stage accepted\', \'stage rejected';
			}
			$query4=$GLOBALS['$dbFramework']->query("
				UPDATE oppo_user_map
				SET state='0'
				WHERE opportunity_id='$opportunity_id' and module='$module' and
				(action IN ('$action'))");
			return $query4;

		} catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}
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
					  'user_id');
			}
			$childNodes = array();
			$this->fetchChildNodes($user_id, $childNodes, $allParentNodes);
			if (count($childNodes) == 0) {
				//M166, M168
				return '';
			}
			$ids = implode("','", $childNodes);
			return $ids;
		} catch (LConnectApplicationException $e) {
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

	/*-=-=-=-=-=-=-=-=--=-=-=-=CREATE OPPORTUNITY PAGE-=-=-=-=-=-=-=-=-=-=-=-=-*/

	public function fetch_Leads($manager_id)	{
		try {
			$children = $manager_id."','";
			$children .= $this->getChildrenForParent($manager_id);
			$query = $GLOBALS['$dbFramework']->query('
				SELECT li.lead_id, li.lead_name, li.lead_industry as industry, li.lead_business_loc as bloc
				FROM `lead_info` AS li
				WHERE ((li.customer_id IS NULL) OR (li.customer_id="")) AND
				(li.lead_status = 1) AND (li.lead_rep_status = 2) AND
				((li.lead_manager_owner IN (\''.$children.'\')) OR (li.lead_rep_owner IN (\''.$children.'\')))
				ORDER BY li.lead_name');
			return $query->result();
		} catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}
	}

	public function fetch_Customers($manager_id)	{
		try {
			$children = $manager_id."','";
			$children .= $this->getChildrenForParent($manager_id);
			$query = $GLOBALS['$dbFramework']->query('
				SELECT ci.customer_id as lead_id, ci.customer_name as lead_name, ci.customer_industry as industry, ci.customer_business_loc as bloc
				FROM `customer_info` AS ci
				WHERE (ci.customer_rep_status = 2) AND
				(
					(ci.customer_manager_owner IN (\''.$children.'\'))
				) OR
				(
					(ci.customer_rep_owner IN (\''.$children.'\'))
				)
				ORDER BY ci.customer_name');
			return $query->result();
		} catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}
	}

    //----Function to insert into 'notifications' table----//
	public function rej_opp_notification($data){
		try {
            $insertQuery = $GLOBALS['$dbFramework']->insert_batch('notifications', $data);
			return $insertQuery;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
    //----Function to get the owner who assigned the opp to manager from 'opp user map' table//
	public function get_from_userid($op_id){
		try {
			$Query = $GLOBALS['$dbFramework']->query("SELECT distinct from_user_id FROM oppo_user_map where opportunity_id='".$op_id."'
                                                    and action = 'ownership assigned' or 'stage assigned' or 'ownership reassigned' or 'stage reassigned'; ");
            if($Query->num_rows()>0){
                          foreach ($Query->result() as $row1)
                          {
                               $from_userid=$row1->from_user_id;
                          }
            }else{
                $from_userid="";
            }
			return $from_userid;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}

    //----Function to get the owner who assigned the opp to manager from 'opp user map' table//
	public function get_from_userid1($op_id,$gvnuserid){
		try {
			$Query = $GLOBALS['$dbFramework']->query("SELECT distinct from_user_id FROM oppo_user_map where opportunity_id='".$op_id."' and to_user_id='".$gvnuserid."'
                                                and action = 'ownership assigned' or 'stage assigned' or 'ownership reassigned' or 'stage reassigned'; ");
            if($Query->num_rows()>0){
                          foreach ($Query->result() as $row1)
                          {
                               $from_userid=$row1->from_user_id;
                          }
            }else{
                $from_userid="";
            }
			return $from_userid;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
    public function getusernamae($uid){
		try {
			$Query = $GLOBALS['$dbFramework']->query("SELECT user_name from user_details where user_id='".$uid."'; ");
            if($Query->num_rows()>0){
                          foreach ($Query->result() as $row1)
                          {
                               $from_userid=$row1->user_name;
                          }
            }else{
                $from_userid="";
            }
			return $from_userid;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}


}
?>

