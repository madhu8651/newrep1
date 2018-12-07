<?php
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('sales_customerModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();

class sales_customerModel extends CI_Model{

     public function __construct(){
        parent::__construct();
    }
    
    public function assignedCustomerInfo($user){
        try {
                $query=$GLOBALS['$dbFramework']->query("
                SELECT coalesce(udm.user_name,'') as customer_manager_owner,coalesce( group_concat(distinct cp.hvalue2),'-') as customer_products,ci.customer_zip,ci.customer_website,ci.customer_name,ci.customer_id,
                ci.customer_city as city, coalesce (JSON_UNQUOTE(ci.customer_number->'$.phone[0]'),'-') as customer_number,
                ci.customer_country,ci.customer_state,ci.customer_logo as customer_logo,ci.customer_location_coord as coordinate,
                coalesce (JSON_UNQUOTE(ci.customer_email->'$.email[0]'),'-') as customer_email,ci.customer_business_loc,
                ci.customer_city as city,ci.customer_remarks as customer_remarks,ci.customer_address as customer_address,
                cd.contact_name,
                cd.contact_id, cd.contact_desg,cd.contact_address,cd.contact_type,
                JSON_UNQUOTE(cd.contact_number->'$.phone[0]') as mobile_number1,
                JSON_UNQUOTE(cd.contact_number->'$.phone[1]') as mobile_number2,
                JSON_UNQUOTE(cd.contact_email->'$.email[0]') as contact_email1,
                JSON_UNQUOTE(cd.contact_email->'$.email[1]') as contact_email2,
                coalesce (bl.hvalue2,'-') as customer_city,
                coalesce (st.lookup_value,'-') as state_name,
                coalesce (cn.lookup_value,'-') as country_name,
                coalesce (ind.hvalue2,'-') as industry_name,
                lk.lookup_value as contacttype,
                (SELECT CASE WHEN COUNT(customer_id)>0 THEN 'Active' ELSE 'Inactive' END
                FROM product_purchase_info where (customer_id=ci.customer_id) AND  (now() < coalesce(purchase_end_date, addtime(now(),'1 1:1:1')))) as status 
                from customer_info as ci 
                LEFT JOIN lookup as st 
                ON ci.customer_state=st.lookup_id
                LEFT JOIN lookup as cn
                ON ci.customer_country=cn.lookup_id
                LEFT JOIN contact_details as cd 
                ON (ci.customer_id=cd.lead_cust_id or ci.lead_id = cd.lead_cust_id)
                LEFT JOIN hierarchy as bl
                ON ci.customer_business_loc=bl.hkey2
                LEFT JOIN product_purchase_info as ppi
                ON ci.customer_id = ppi.customer_id
                LEFT JOIN hierarchy as cp
                ON ppi.product_id = cp.hkey2
                LEFT JOIN user_details as udm
                ON ci.customer_manager_owner = udm.user_id
                LEFT JOIN hierarchy as ind
                ON ci.customer_industry=ind.hkey2
                LEFT JOIN lookup as lk
                ON cd.contact_type=lk.lookup_id,
                lead_cust_user_map as lcum
                WHERE 
                (lcum.lead_cust_id in 
                (SELECT lead_cust_id 
                FROM lead_cust_user_map 
                WHERE to_user_id='$user' and (action='assigned' or action='reassigned') 
                and type='customer'))
                AND
                (lcum.lead_cust_id not in 
                (SELECT lead_cust_id 
                FROM lead_cust_user_map 
                WHERE from_user_id='$user' AND (action='accepted' or action='rejected') 
                AND type='customer' AND state=1))
                AND ci.customer_rep_status = 1
                AND ci.customer_id=lcum.lead_cust_id
                GROUP BY ci.customer_id
                ORDER BY ci.customer_name

                ");

                return $query->result();
        }
         catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
         
    }

    public function updateLeadMgrOwner($leadid, $user) {
      //returns true or false indicating whether lead manager owner was successfully updated or not
        try {
                $query = $this->db->query('
                UPDATE customer_info 
                SET customer_manager_owner="'.$user.'", customer_manager_status=1
                WHERE customer_id="'.$leadid.'" AND customer_manager_status=0');
                $updateStatus = $this->db->affected_rows();
                if ($updateStatus < 1) {
                return false;
                }
                $data=array(
                'lead_cust_id' => $leadid,
                'from_user_id' => $user,
                'to_user_id' => $user,
                'action'=>'accepted',
                'module'=>'manager',
                'timestamp'=>date('Y-m-d H:i:s'),
                'type'=>'customer'
                );
                //insert a row into transaction table as you have updated owner
                $insertQuery = $GLOBALS['$dbFramework']->insert('lead_cust_user_map', $data);
                if ($insertQuery == false) {
                return false;        
                }
                return $leadid; 
        }
         catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
      
    } 


    public function checkForRepOwner($customerid, $user) {
      //return null if lead_manager_owner is NULL or if given user's reporting manager
      //else returns lead_name of those unqualified
        try {
                $query = $GLOBALS['$dbFramework']->query("
                          SELECT  
                          CASE
                          WHEN ci.customer_rep_owner IS NULL THEN NULL 
                          ELSE ci.customer_name
                          END AS customer_rep_owner    
                          from customer_info as ci 
                          where ci.customer_id='$customerid'");     
                $repOwner = $query->result();
                $repOwner = $repOwner[0]->customer_rep_owner;
                return $repOwner;
        }
         catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function fetch_userPrivilages($user_id) {
      try {
              $sell_types = array();
              $query = $GLOBALS['$dbFramework']->query("SELECT map_id from user_mappings where user_id='$user_id' and map_type='sell_type'");
              foreach ($query->result_array() as $row)  {
              array_push($sell_types, $row['map_id']);
              }
              $finalArray = array();
              $finalArray['leads'] = 0;
              $finalArray['customers'] = 0;
              foreach ($sell_types as $sell_type) {
              if ($sell_type=='new_sell') {
              $finalArray['leads']=1;
              } else if (($sell_type=='cross_sell')||($sell_type='up_sell')){
              $finalArray['customers']=1;
              }
              }
              $finalArray['sell_types'] = $sell_types;
              return $finalArray;
      }
       catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
  }  

    public function getListOfManager($user){  
    //Get Only active user list  
      try {
              $query= $GLOBALS['$dbFramework']->query("SELECT ud.user_name,ud.user_id 
                              FROM user_details as ud 
                              where ud.user_id='$user'");             
              return $query->result();       
      }  
     catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
      }
    
    }

     public function getListOfCurrency($userId) {
        try {
                $query=$GLOBALS['$dbFramework']->query("SELECT a.map_value as currency_id,b.currency_name 
                                      from user_mappings a,currency b 
                                      where a.map_key='currency' and a.map_value=b.currency_id and a.user_id='$userId'
                                      group by currency_id");
                return $query->result();         
        }
         catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
       
  }

  public function user_plugin($userid){
    try{
      $query= $GLOBALS['$dbFramework']->query("select b.plugin_id from user_details a,user_module_plugin_mapping b where a.user_id=b.user_id
                        and a.user_id='$userid'");
                        return $query->result();
    }
    catch (LConnectApplicationException $e) {
      $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
      throw $e;
    }   
  }

  public function customerProductData($user_id) { 
    try {
            $query=$GLOBALS['$dbFramework']->query("SELECT a.hvalue2 as product_name,a.hkey2 as product_id 
                              from hierarchy a, user_mappings c
                             where a.hkey2=c.map_id 
                             and c.user_id='$user_id' 
                             and c.map_type='product'
                              group by a.hkey2");
            return $query->result();
    }
    catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
    } 

 }

  public function getCurrency($products,$ownerId) {
    try {
          $products=implode("','", $products);
            $query=$GLOBALS['$dbFramework']->query("SELECT cu.currency_name,cu.currency_id 
                                                    from user_mappings as um,currency as cu
                                                    where um.user_id='$ownerId' 
                                                    and um.map_type='product'
                                                    and um.map_id IN ('$products') 
                                                    and um.map_value=cu.currency_id
                                                    group by cu.currency_id;");
            return $query->result();
    }
    catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
    } 
  }

   public function addProductPurchaseInfo($purchaseArray) {
      try {
            $query=$this->db->insert_batch('product_purchase_info',$purchaseArray);         
            return $query;
      }
      catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
     }
       
  }

  public function updateCustomLead($customDataArray) {   
      try {

            $query= $GLOBALS['$dbFramework']->update_batch('lead_info',$customDataArray, 'lead_id');
             return true; 
      }
      catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
      }
       
   }

    public function fetchLeadId($customerId) {
      try{

          $query=$GLOBALS['$dbFramework']->query("
          SELECT ci.lead_id as lead_id 
          from customer_info as ci
          where ci.customer_id='$customerId' 
          ");
          return $query->result();   
      } 
      catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
     }
    }

    public function fetchOppId($leadId) {
         try {
                $query=$GLOBALS['$dbFramework']->query("SELECT od.opportunity_id as opportunity_id 
                  FROM opportunity_details as od WHERE od.lead_cust_id='$leadId'");
                  return $query->result();
      }
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function oppLogData($oppids) {
        try {

              $query=$GLOBALS['$dbFramework']->query("
               SELECT lu.lookup_value as activity, ud.user_name as rep_name, ci.opportunity_name as customer_name, DATE_FORMAT(rl.starttime, '%D %b %Y %h:%i %p') AS 'starttime', rl.note as note, rl.rating as rating, rl.call_type as action, DATE_FORMAT(rl.endtime, '%Y/%m/%d %H:%i:%s') AS end, DATE_FORMAT(rl.starttime, '%Y/%m/%d %H:%i:%s') AS start,'completed' as status,rl.path as path, rl.logtype as conntype
                from opportunity_details ci, rep_log rl, user_details ud, lookup lu
                where rl.leadid IN ('$oppids')
                and rl.leadid=ci.opportunity_id
                and rl.call_type ='complete'
                and rl.type='opportunity'
                and rl.logtype=lu.lookup_id
                and ud.user_id=rl.rep_id");
              return $query->result();
        }
        catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
        }

    }   

  public function checkLeadID($customerId) {
      try{

      $query=$GLOBALS['$dbFramework']->query("SELECT ci.lead_id FROM customer_info as ci WHERE ci.customer_id='$customerId' and ci.lead_id IS NOT NULL");
        if($query->num_rows()>0){
          return 1;
        }
        else{
          return 0;
        }

      }
      catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
      }
    } 

  public function fetchCustomCustomer($customerId){
       try{

          $query=$GLOBALS['$dbFramework']->query("
         SELECT aa.attribute_key,aa.attribute_name,aa.attribute_type,coalesce(ci.attribute,'') as attribute,ci.customer_id as id,aa.module as module,aa.attribute_validation_string
          from `admin_attributes` as aa,customer_info as ci
          where ci.customer_id='$customerId'
          and module IN ('Customer')");
          return $query->result();   
      } 
      catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
     }
    }

    public function fetchCustomLead($customerId){
     try{

        $query=$GLOBALS['$dbFramework']->query("
          SELECT aa.attribute_key,aa.attribute_name,aa.attribute_type,coalesce (li.attribute,'') as attribute,li.lead_id as id,aa.module as module,aa.attribute_validation_string
          from `admin_attributes` as aa,customer_info as ci,lead_info as li
          where ci.customer_id='customerId'
          and ci.lead_id=li.lead_id
          and module IN ('Lead')");
          $arr=$query->result(); 

      }
      catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
    }
    }

 public function customerProductPurchase($customerID) {
      try {
            $query= $GLOBALS['$dbFramework']->query("SELECT 
                                                    ppi.purchase_id AS purchase_id,ppi.id,
                                                   coalesce(ppi.purchase_end_date,' - ') AS purchase_end_date,
                                                    ppi.purchase_start_date AS purchase_start_date,
                                                    ppi.opportunity_id AS opportunity_id,
                                                    ppi.product_id AS product_id,
                                                    hi.hvalue2  as product_name,ppi.Quantity as quantity,ppi.amount,ppi.currency,coalesce(ppi.opportunity_id,'-'),cu.currency_name as currency_name,ppi.id,
                                                    coalesce(ppi.reference_number,' -')  as reference_number,
                                                    ppi.product_owner,ud.user_name as product_owner,ppi.renewal_date as renewal_date,coalesce(ppi.rate,'') as rate,coalesce(ppi.score,'') as score,coalesce(ppi.customer_code,'')  as customer_code,coalesce(ppi.priority,'')  as priority
                                                    FROM
                                                        product_purchase_info AS ppi 
                                                        LEFT JOIN currency AS cu 
                                                        ON ppi.currency =cu.currency_id,
                                                        hierarchy AS hi,
                                                        user_details AS ud
                                                    WHERE
                                                        ppi.customer_id = '$customerID'
                                                    AND ppi.product_id = hi.hkey2
                                                    AND ppi.product_owner =ud.user_id
                                                    group by ppi.purchase_id,ppi.product_id
                                                    order by ppi.id
                          
                          "); 
          $arr=$query->result_array();
          $row = array();
          if($query->num_rows() > 0) {
               for($i=0;$i<count($arr);$i++){
                $purchase_id = $arr[$i]['purchase_id'];
                $row[$purchase_id] = array();
                $product_array = array();
                for($j=0;$j<count($arr);$j++){
                    if($purchase_id==$arr[$j]['purchase_id']){
                        $some_array= array(
                        'product_id'=>$arr[$j]['product_id'],
                        'product_name'=>$arr[$j]['product_name'],
                        'purchase_end_date'=>$arr[$j]['purchase_end_date'],
                        'purchase_start_date'=>$arr[$j]['purchase_start_date'],
                        'quantity'=>$arr[$j]['quantity'],
                        'amount'=>$arr[$j]['amount'],
                        'id'=>$arr[$j]['id'],
                        'renewal_date'=>$arr[$j]['renewal_date'],
                        'rate'=>$arr[$j]['rate'],
                        'score'=>$arr[$j]['score'],
                        'customer_code'=>$arr[$j]['customer_code'],
                        'priority'=>$arr[$j]['priority']


                        );
                      array_push($product_array, $some_array); 
                    }
                } 
                $some_array = array(
                  'opp_id' => $arr[$i]['opportunity_id'],
                  'currency_name' => $arr[$i]['currency_name'],
                  'currency_id' => $arr[$i]['currency'],
                   'reference_number' => $arr[$i]['reference_number'],
                    'product_owner' => $arr[$i]['product_owner'],
                  'purchase_id' => $purchase_id,
                  'prod_data' => $product_array,
          
                );
              $row[$purchase_id] = $some_array;
            } 
          }
          $finalArray = array();
          foreach ($row as $key=>$value) {
            $finalArray[] =$row[$key];
          }
          return $finalArray;
      }

      catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
      }
 } 

 public function updateProductPurchaseInfo($purchaseArray)  {
    try {
      
              $query= $GLOBALS['$dbFramework']->update_batch('product_purchase_info',$purchaseArray,'id');
              return true; 
     } 
     catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
    }
  }

     public function customer_rep_owner_update($data,$lid)  {
        try {
                $update = $GLOBALS['$dbFramework']->update('customer_info', $data,array('customer_id'=>$lid));       
                return $update;
        }
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

     public function customer_accept_mgr($data) {
        try {
                $query=$GLOBALS['$dbFramework']->insert('lead_cust_user_map', $data);  
                return $query;
        }
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
  
    }

    public function acceptedCustomerInfo($user) {
      try {
              $query=$GLOBALS['$dbFramework']->query("SELECT coalesce(udm.user_name,'') as customer_manager_owner,coalesce( group_concat(distinct cp.hvalue2),'') as customer_products,ci.customer_zip,ci.customer_website,ci.customer_name,ci.customer_id,
              ci.customer_city as city, coalesce (JSON_UNQUOTE(ci.customer_number->'$.phone[0]'),'') as customer_number,
              ci.customer_country,ci.customer_state,
              coalesce (JSON_UNQUOTE(ci.customer_email->'$.email[0]'),'') as customer_email,ci.customer_business_loc,ci.customer_logo as customer_logo,ci.customer_location_coord as coordinate,
              ci.customer_city as city,ci.customer_remarks as customer_remarks,ci.customer_address as customer_address,
              cd.contact_name,
              cd.contact_id, cd.contact_desg,cd.contact_address,cd.contact_type,
              JSON_UNQUOTE(cd.contact_number->'$.phone[0]') as mobile_number1,
              JSON_UNQUOTE(cd.contact_number->'$.phone[1]') as mobile_number2,
              JSON_UNQUOTE(cd.contact_email->'$.email[0]') as contact_email1,
              JSON_UNQUOTE(cd.contact_email->'$.email[1]') as contact_email2,
              coalesce (bl.hvalue2,'') as customer_city,
              coalesce (st.lookup_value,'') as state_name,
              coalesce (cn.lookup_value,'') as country_name,
              coalesce (ind.hvalue2,'') as industry_name,lk.lookup_value as contacttype,
              (select CASE WHEN COUNT(customer_id)>0 THEN 'Active' ELSE 'Inactive' END
              FROM product_purchase_info where (customer_id=ci.customer_id) AND  (now() < coalesce(purchase_end_date, addtime(now(),'1 1:1:1')))) as status 
              from customer_info as ci 
              LEFT JOIN lookup as st 
              ON ci.customer_state=st.lookup_id
              LEFT JOIN lookup as cn
              ON ci.customer_country=cn.lookup_id
              LEFT JOIN contact_details as cd 
              ON (ci.customer_id=cd.lead_cust_id or ci.lead_id = cd.lead_cust_id)
              LEFT JOIN hierarchy as bl
              ON ci.customer_business_loc=bl.hkey2
              LEFT JOIN hierarchy as ind
              ON ci.customer_industry=ind.hkey2
              LEFT JOIN product_purchase_info as ppi
              ON ci.customer_id = ppi.customer_id
              LEFT JOIN lookup as lk
                ON cd.contact_type=lk.lookup_id
              LEFT JOIN hierarchy as cp
              ON ppi.product_id = cp.hkey2
              LEFT JOIN user_details as udm
              ON ci.customer_manager_owner = udm.user_id
              WHERE 
              ci.customer_rep_owner='$user' 
              AND (ci.customer_status=0 OR ci.customer_status=1)
              AND ci.customer_rep_status=2
              GROUP BY ci.customer_id 
              ORDER BY ci.customer_id

              ");
              return $query->result();
      }
     catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
      }
    	
    }

    public function myCustomersData($user) {
      try{

        $query=$GLOBALS['$dbFramework']->query(" SELECT coalesce(udm.user_name,'') as customer_manager_owner,coalesce( group_concat(distinct cp.hvalue2),'') as customer_products,ci.customer_zip,ci.customer_website,ci.customer_name,ci.customer_id,
              ci.customer_city as city, coalesce (JSON_UNQUOTE(ci.customer_number->'$.phone[0]'),'') as customer_number,
              ci.customer_country,ci.customer_state,
              coalesce (JSON_UNQUOTE(ci.customer_email->'$.email[0]'),'') as customer_email,ci.customer_business_loc,ci.customer_logo as customer_logo,ci.customer_location_coord as coordinate,
              ci.customer_city as city,ci.customer_remarks as customer_remarks,ci.customer_address as customer_address,
              cd.contact_name,
              cd.contact_id, cd.contact_desg,cd.contact_type,cd.contact_address,
              JSON_UNQUOTE(cd.contact_number->'$.phone[0]') as mobile_number1,
              JSON_UNQUOTE(cd.contact_number->'$.phone[1]') as mobile_number2,
              JSON_UNQUOTE(cd.contact_email->'$.email[0]') as contact_email1,
              JSON_UNQUOTE(cd.contact_email->'$.email[1]') as contact_email2,
              coalesce (bl.hvalue2,'') as customer_city,
              coalesce (st.lookup_value,'') as state_name,
              coalesce (cn.lookup_value,'') as country_name,
              coalesce (ind.hvalue2,'') as industry_name,lk.lookup_value as contacttype,
              (select CASE WHEN COUNT(customer_id)>0 THEN 'Active' ELSE 'Inactive' END
              FROM product_purchase_info where (customer_id=ci.customer_id) AND  (now() < coalesce(purchase_end_date, addtime(now(),'1 1:1:1')))) as status 
              from customer_info as ci 
              LEFT JOIN lookup as st 
              ON ci.customer_state=st.lookup_id
              LEFT JOIN lookup as cn
              ON ci.customer_country=cn.lookup_id
              LEFT JOIN contact_details as cd 
              ON (ci.customer_id=cd.lead_cust_id or ci.lead_id = cd.lead_cust_id)
              LEFT JOIN hierarchy as bl
              ON ci.customer_business_loc=bl.hkey2
              LEFT JOIN hierarchy as ind
              ON ci.customer_industry=ind.hkey2
              LEFT JOIN lookup as lk
              ON cd.contact_type=lk.lookup_id
              LEFT JOIN product_purchase_info as ppi
              ON ci.customer_id = ppi.customer_id
              LEFT JOIN hierarchy as cp
              ON ppi.product_id = cp.hkey2
              LEFT JOIN user_details as udm
              ON ci.customer_manager_owner = udm.user_id,
              lead_info as li
              where
              ci.lead_id=li.lead_id 
              and 
             (li.lead_rep_owner='$user' or ci.customer_rep_owner ='$user')
              AND (ci.customer_status=0 OR ci.customer_status=1)
              GROUP BY ci.customer_id 
              ORDER BY ci.customer_id");

              return $query->result();
      }
      catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
      }

    }

    public function updateCustomerOwner($user,$customerRepStatus,$acceptedValue,$reportingTo) {  try {
              $query=$this->db->query("UPDATE customer_info 
                                SET customer_rep_owner='$user',customer_rep_status='$customerRepStatus',customer_manager_owner='$reportingTo'
                                WHERE customer_id='$acceptedValue'");
              return $query;
        } 
      catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
      }
         
    }

    public function rejectedDataInsert($rejectedData) {
      try {
              $query=$this->db->insert('lead_cust_user_map',$rejectedData);         
              return $query;
      }
      catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

     public function insertCustomerUser($data1) {
        try {
                $query=$GLOBALS['$dbFramework']->insert('lead_cust_user_map',$data1);         
                return $query;   
        }
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

     public function view_country(){
          try {
                  $query=$this->db->query("SELECT * FROM lookup WHERE lookup_name='country'");
                  return $query->result();
          }
          catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
            }
    }

     public function state($countrId) {
        try {
                $query=$this->db->query("SELECT * FROM lookup WHERE lookup_name='$countrId'");
                return $query->result();          
        }
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
        
   }

    public function contact() {
        try {
                $query=$this->db->query("SELECT * FROM lookup WHERE lookup_name='Buyer Persona'");
                return $query->result();          
        }
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
        
   }

   public function industry($user){
        try {
                $query=$this->db->query("SELECT a.hvalue2 as industry_name,a.hkey2 as industry_id 
                                from hierarchy a,user_mappings c, user_details d
                               where a.hkey2=c.map_id 
                              and c.map_type='clientele_industry' and d.team_id=c.transaction_id and c.user_id='$user'
                    group by a.hkey2");
                return $query->result();
          }
          catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
        
   }

   public function location($user){
        try {
                $query=$this->db->query("SELECT a.hvalue2 as business_location_name,a.hkey2 as business_location_id 
                    from hierarchy a,user_mappings c, user_details d
                    where a.hkey2=c.map_id 
                    and c.map_type='business_location' and d.team_id=c.transaction_id and c.user_id='$user'
                group by a.hkey2");
                return $query->result();
        }
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

   }

   public function customerLogData($customerId) {
        try {
                $query=$GLOBALS['$dbFramework']->query("
                                SELECT 
                                lu.lookup_value AS activity,
                                ud.user_name AS rep_name,
                                ci.customer_name AS customer_name,
                                DATE_FORMAT(rl.starttime, '%D %b %Y %h:%i %p') AS 'starttime',
                                rl.note AS note,
                                DATE_FORMAT(rl.starttime, '%Y/%m/%d %H:%i:%s') AS start,
                                rl.rating AS rating,
                                rl.call_type AS action,
                                DATE_FORMAT(rl.endtime, '%Y/%m/%d %H:%i:%s') AS end,
                                'completed' AS status,rl.path as path,rl.logtype as conntype
                                FROM
                                customer_info ci,
                                rep_log rl,
                                user_details ud,
                                lookup lu
                                WHERE
                                rl.leadid = '$customerId'
                                AND rl.leadid = ci.customer_id
                                AND rl.call_type = 'complete'
                                AND rl.type = 'customer'
                                AND rl.logtype = lu.lookup_id
                                AND ud.user_id = rl.rep_id");
                return $query->result();
        }
        catch (LConnectApplicationException $e) {
              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
              throw $e;
        }
  
   }

   public function fetchCustomerScheduleData($customer_id){ 
      try{                  
      $query = $GLOBALS['$dbFramework']->query("
      SELECT a.status, DATE_FORMAT(a.meeting_start, '%D %b %Y %h:%i %p') as start_date, DATE_FORMAT(a.meeting_end, '%D %b %Y %h:%i %p') as end_date, a.conntype, a.remarks,
      b.customer_name as leadname, c.user_name as activity_owner, d.lookup_value as activity,a.status as status,a.duration as duration
      from
      lead_reminder a , customer_info b, user_details c, lookup d
      where 
      a.lead_id='$customer_id'
      and a.status in ('scheduled','pending')
      and a.lead_id=b.customer_id
      and a.rep_id=c.user_id
      and a.conntype=d.lookup_id");
      return $query->result();    
    }
    catch (LConnectApplicationException $e) {
      $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
      throw $e;
    }
  }

  public function customerOppData($customerId) {
      try {
              $query=$GLOBALS['$dbFramework']->query("SELECT opd.opportunity_id,opd.opportunity_name,
                                                      ss.stage_name as stage_name,hi.hvalue2 as opportunity_product,coalesce(DATE_FORMAT(opd.opportunity_date, '%D %b %Y %h:%i %p'),'-') AS opportunity_date ,ud.user_name as stage_owner
                                                    FROM opportunity_details as opd,sales_stage as ss,hierarchy as hi,user_details as ud
                                                    WHERE lead_cust_id='$customerId'
                                                    AND opd.opportunity_stage=ss.stage_id
                                                    AND opd.opportunity_product=hi.hkey2
                                                    and opd.stage_owner_id=ud.user_id");
                                                    return $query->result();
      } 
      catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
      }
   }

   public function updateCustomerInfo($customerDataArray) {   
      try {
             $query= $GLOBALS['$dbFramework']->update_batch('customer_info',$customerDataArray, 'customer_id');
            return true; 
      }
      catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
      }
       
   }

   public function phone_updateCustomerInfo($customerDataArray){

     try {
             $query= $GLOBALS['$dbFramework']->update_batch('customer_info',$customerDataArray, 'customer_id');
            return true; 
      }
      catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
      }

   }

    public function Phone_get_product_purchase($customer_id) {
      try {
            $query= $GLOBALS['$dbFramework']->query("SELECT 
                                                    ppi.purchase_id AS purchase_id,
                                                    ppi.purchase_end_date AS purchase_end_date,
                                                    ppi.purchase_start_date AS purchase_start_date,
                                                    ppi.opportunity_id AS opportunity_id,
                                                    ppi.product_id AS product_id,
                                                    hi.hvalue2  as product_name,ppi.Quantity as quantity,ppi.amount,ppi.currency,coalesce(ppi.opportunity_id,'-'),cu.currency_name as currency_name,ppi.id
                                                    FROM
                                                        product_purchase_info AS ppi,
                                                        hierarchy AS hi,
                                                        currency AS cu,
                                                        user_details AS ud
                                                    WHERE
                                                        ppi.customer_id = '$customer_id'
                                                    AND ppi.product_id = hi.hkey2
                                                    AND ppi.currency =cu.currency_id
                                                    group by ppi.purchase_id
                          
                          "); 
          $arr=$query->result_array();
          $row = array();
          if($query->num_rows() > 0) {
               for($i=0;$i<count($arr);$i++){
                $purchase_id = $arr[$i]['purchase_id'];
                $row[$purchase_id] = array();
                $product_array = array();
                for($j=0;$j<count($arr);$j++){
                    if($purchase_id==$arr[$j]['purchase_id']){
                        $some_array= array(
                        'product_id'=>$arr[$j]['product_id'],
                        'product_name'=>$arr[$j]['product_name'],
                        'purchase_end_date'=>$arr[$j]['purchase_end_date'],
                        'purchase_start_date'=>$arr[$j]['purchase_start_date'],
                        'quantity'=>$arr[$j]['quantity'],
                        'amount'=>$arr[$j]['amount'],
            'id'=>$arr[$j]['id']
                        );
                      array_push($product_array, $some_array); 
                    }
                } 
                $some_array = array(
                  'opp_id' => $arr[$i]['opportunity_id'],
                  'currency_name' => $arr[$i]['currency_name'],
                  'currency_id' => $arr[$i]['currency'],
                  'purchase_id' => $purchase_id,
                  'prod_data' => $product_array,
          
                );
              $row[$purchase_id] = $some_array;
            } 
          }
          $finalArray = array();
          foreach ($row as $key=>$value) {
            $finalArray[] =$row[$key];
          }
          return $finalArray;
      }

      catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
      }
 } 

   public function updateContactInfo($contactDataArray) {  
      try {
            $query= $GLOBALS['$dbFramework']->update_batch('contact_details',$contactDataArray, 'contact_id');
            return true;   
      } 
      catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
      } 
     
   }

   public function phone_updateContactInfo($contactDataArray){  
      try {
            $query= $GLOBALS['$dbFramework']->update_batch('contact_details',$contactDataArray, 'contact_id');
            return true;   
      } 
      catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
      }       
   }

    public function getReportingPerson($user) {
      try {
              $query = $this->db->query("
              SELECT a.reporting_to as report
              FROM user_details a 
              WHERE a.user_id='$user'
              group by a.user_id");
          return $query->result();
      } 
      catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
      } 

    }


    public function getFromUser($user,$fromUserValues)  {
      try {
              $query=$this->db->query("SELECT from_user_id 
                FROM lead_cust_user_map 
                WHERE to_user_id='$user' 
                AND  lead_cust_id='$fromUserValues'");
              return $query->result();
      }
      catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
      } 

    }

    public function update_transaction($customerid){
      try{
        $user=$this->session->userdata('uid');
        $query=$GLOBALS['$dbFramework']->query("UPDATE lead_cust_user_map set state=0 where lead_cust_id='$customerid' and  action IN ('assigned','reassigned') and type='customer' and module = 'sales'");
         $query1=$GLOBALS['$dbFramework']->query("UPDATE lead_reminder  set rep_id='$user'
              where lead_id='$customerid' and module_id='sales' and status in('pending',
              'scheduled') and type='customer'");
        return $query;


      }
      catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
      }
    }

    public function insert_transaction($data) {
       try{
            $insert = $GLOBALS['$dbFramework']->insert('lead_cust_user_map',$data); 
            return $insert;
     } 
     catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
     }
   }

     public function rep_owner($leadid) {
          try {
                
                $query=$GLOBALS['$dbFramework']->query("SELECT customer_rep_status from customer_info where customer_id='$leadid'");
                return $query->result();
          }
          catch (LConnectApplicationException $e) {
              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
              throw $e;
        }
     }

    public function accept_datails($data) {
      try {
              $insert = $GLOBALS['$dbFramework']->insert('lead_cust_user_map',$data); 
      }
      catch (LConnectApplicationException $e) {
              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
              throw $e;
        }
    }

    public function insertNotificationData($notificationDataArray) {
        try{
                $insert = $GLOBALS['$dbFramework']->insert_batch('notifications',$notificationDataArray);
                return $insert;
        } 
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }     

   public function accept_customer($customerid,$data){
      try {
              $update = $GLOBALS['$dbFramework']->update('customer_info' ,$data, 
              array('customer_id' => ($customerid)));
              return $update;      
      }
      catch (LConnectApplicationException $e) {
              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
              throw $e;
      }
      
   }

   public function checkEditCustomer($customerName,$customerId){
        try{
       $query=$GLOBALS['$dbFramework']->query("SELECT * FROM customer_info where LCASE(customer_name)='".strtolower($customerName)."' and  customer_id!='$customerId'");
        if ($query->num_rows() > 0){
            return 0;
        }else{
            return 1;
        } 
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function Phone_checkEditCustomer($customer_name,$customer_id){
        try{
       $query=$GLOBALS['$dbFramework']->query("SELECT * FROM customer_info where LCASE(customer_name)='".strtolower($customer_name)."' and  customer_id!='$customer_id'");
        if ($query->num_rows() > 0){
            return 0;
        }else{
            return 1;
        } 
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function notificationShowStatus($notifyUpdateData,$cmp_lead,$user_id) {
        try{

            $update = $GLOBALS['$dbFramework']->update('notifications',$notifyUpdateData,array('task_id'=>$cmp_lead,'action' =>'assigned'));
            return $update;
        }
        catch(LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    } 

    public function fetchAssignedManager($leadCustId,$user) {
        try{

            $query = $GLOBALS['$dbFramework']->query("SELECT lcum.from_user_id as managerid,
                                                      ud.user_name as managername 
                                                      from lead_cust_user_map as lcum,user_details as ud 
                                                      where lcum.to_user_id = '$user'
                                                      AND lcum.action IN ('assigned','reassigned') 
                                                      AND lcum.from_user_id = ud.user_id
                                                      AND lcum.lead_cust_id = '$leadCustId'
                                                      AND lcum.type = 'customer'");
            return $query->result();
        }
        catch(LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }

    public function getCustomerName($custid) {
        try{

            $query=$GLOBALS['$dbFramework']->query("SELECT customer_name FROM customer_info where customer_id = '$custid'");
            return $query->result();  
        }
        catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
        } 
    }


    public function fetchUserName($user){
      try{
            $query = $GLOBALS['$dbFramework']->query("SELECT user_name 
                                                FROM user_details 
                                                WHERE user_id='$user'");
            return $query->result();
        } 
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }              

   public function last_reject($customerid,$remarks){
      try{
        
        $userid=$this->session->userdata('uid');
        $query=$GLOBALS['$dbFramework']->query("SELECT * from lead_cust_user_map where state=1 and lead_cust_id='$customerid' and action in ('assigned','reassigned') and module='sales'");
        $count_reject = $query->num_rows();
        $result=$query->result();
        $data1= array(
            'mapping_id' =>uniqid(rand(),TRUE) ,
            'lead_cust_id' =>$customerid,
            'type'=>'customer',
            'state' =>0,
            'action'=>"rejected",
            'module'=>"sales",
            'from_user_id'=>$userid,
            'to_user_id'=>$result[0]->from_user_id,
            'remarks'=>$remarks,
            'timestamp'=>date('Y-m-d H:i:s'),
           );
          
           $insert = $GLOBALS['$dbFramework']->insert('lead_cust_user_map',$data1);
           // $insert2 = $GLOBALS['$dbFramework']->insert('notifications',$data2); 
           $query2=$GLOBALS['$dbFramework']->query("SELECT * from lead_cust_user_map where lead_cust_id='$customerid' and action in ('assigned','reassigned') and state=1 and module='sales'");
           $count_reject1 = $query2->num_rows();
            if($count_reject1==$count_reject){
                $query4=$GLOBALS['$dbFramework']->query("update lead_cust_user_map set state=0 where lead_cust_id='$customerid' and action in ('assigned','reassigned') and state=1 and module='sales'");
                $query3=$GLOBALS['$dbFramework']->query("update customer_info set customer_rep_status=3 where customer_id='$customerid'");
            }
            return true; 
      }catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
      }
    }

    public function customerHistory($customer_id){
    try{
          $query = $GLOBALS['$dbFramework']->query("SELECT lcum.timestamp as timestamp, ci.customer_name as lead_cust_name, lcum.remarks as remarks, lcum.action as action, lcum.lead_cust_id as lead_id,lcum.from_user_id as from_user_id,lcum.mapping_id as mapping_id, lcum.to_user_id as to_user_id,lcum.module,ud.user_name as from_user_name,ud1.user_name as to_user_name 
          from lead_cust_user_map as lcum,user_details ud,user_details ud1, customer_info as ci
          where lcum.lead_cust_id='$customer_id'
          and ci.customer_id=lcum.lead_cust_id      
          and lcum.type='customer'           
          and (lcum.action='created' or lcum.action='assigned' or lcum.action='accepted' or lcum.action='rejected' or lcum.action='reassigned' or lcum.action='seen')
          and lcum.from_user_id=ud.user_id
          and lcum.to_user_id=ud1.user_id
          order by lcum.timestamp");
          return $query->result();
    }
    catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
    }     
  } 

  public function updateCustomerPhoto($customerid,$data) {
        try{
    $update = $GLOBALS['$dbFramework']->update('customer_info' ,$data, array('customer_id' => ($customerid)));
      return $update;
      } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }


    public function leadLogData($leadId) {
        try {
                $query=$GLOBALS['$dbFramework']->query("
                SELECT lu.lookup_value as activity, ud.user_name as rep_name, ci.lead_name as customer_name, DATE_FORMAT(rl.starttime, '%D %b %Y %h:%i %p') AS 'starttime', rl.note as note, rl.rating as rating, rl.call_type as action, DATE_FORMAT(rl.endtime, '%Y/%m/%d %H:%i:%s') AS end,'completed' as status,DATE_FORMAT(rl.starttime, '%Y/%m/%d %H:%i:%s') AS start,rl.path as path, rl.logtype as conntype
                from lead_info ci, rep_log rl, user_details ud, lookup lu
                where rl.leadid='$leadId'
                and rl.leadid=ci.lead_id
                and rl.call_type ='complete'
                and rl.type='lead'
                and rl.logtype=lu.lookup_id
                and ud.user_id=rl.rep_id");
                return $query->result();
        }
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    } 
    public function fetchAllContacts($leadCustId) {
      try{

        $query=$GLOBALS['$dbFramework']->query("
                SELECT cd.contact_id AS contact_id, cd.contact_name AS contact_name, cd.contact_for AS contact_for,
                    coalesce(lo.lookup_value,'-') AS contact_type_name, lo.lookup_id AS contact_type_id,
                    ci.customer_id AS lead_cust_id, ci.customer_name AS lead_cust_name,
                    JSON_UNQUOTE(coalesce(cd.contact_number->'$.phone[0]','')) as employeephone1,
                    JSON_UNQUOTE(coalesce(cd.contact_number->'$.phone[1]','')) as employeephone2,
                    JSON_UNQUOTE(coalesce(cd.contact_email->'$.email[0]','')) as employeeemail,
                    JSON_UNQUOTE(coalesce(cd.contact_email->'$.email[1]','')) as employeeemail2,
                    coalesce(cd.contact_desg,'-') as contact_desg,coalesce(cd.contact_photo,''), cd.contact_created_time,
                    coalesce(cd.contact_dob,'-') AS contact_dob, cd.contact_address AS contact_address, cd.remarks AS remarks
                FROM customer_info AS ci, contact_details AS cd LEFT JOIN `lookup` AS lo ON cd.contact_type = lo.lookup_id AND lo.lookup_name = 'Buyer Persona'
                WHERE (ci.customer_id = '$leadCustId') 
                AND (ci.customer_id=cd.lead_cust_id or ci.lead_id = cd.lead_cust_id)
                GROUP BY cd.contact_id
                ORDER BY cd.contact_name");
        return $query->result();
      }
      catch(LConnectApplicationException $e){
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;

      }
    }       

}

?>