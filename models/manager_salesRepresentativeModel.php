<?php
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('manager_salesRepresentativeController');

$GLOBALS['$dbFramework'] = new LConnectDataAccess();
class manager_salesRepresentativeModel extends CI_Model{    
  public function __construct() {
    parent::__construct();
  }
  
    private function getChildrenForParent($user_id) {
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
            return $childNodes;
        }
        $ids = implode("','", $childNodes);
        return $ids;
    }

    private function fetchChildNodes($givenID, & $childNodes, $allParentNodes)  {
        foreach ($allParentNodes as $user_id => $reporting_to) {
            if ($reporting_to == $givenID)  {
                array_push($childNodes, $user_id);
                $this->fetchChildNodes($user_id, $childNodes, $allParentNodes);                
            }
        }
    }
    public function view_data($user) {
      $children = $this->getChildrenForParent($user);
     $query = $GLOBALS['$dbFramework']->query("
                          SELECT ud.user_id AS rep_id, ud.user_name AS repname, ur.role_name AS designation, t.teamname AS teamname, ud2.user_name AS manager
                          from user_details AS ud, user_roles AS ur, teams AS t, user_details AS ud2, user_module_plugin_mapping AS umpm
                          where (ud.user_id IN ('$children')) AND umpm.user_id=ud.user_id AND json_extract(umpm.module_id, '$.sales')!=0  AND json_extract(umpm.module_id, '$.Manager')=0 AND ur.role_id=ud.designation AND t.teamid=ud.team_id AND ud.reporting_to=ud2.user_id");
                          return $query->result();
                          }
    
  public function rep_data($rep_id){
    $query=$GLOBALS['$dbFramework']->query("SELECT a.user_id as rep_id,a.travel_cost,a.outgoingsms_cost,a.outgoingcall_cost,
    a.outgoingsms_currency,a.outgoingcall_currency,b.user_name as rep_name,h.hvalue2 as location,b.team_id,d.teamname,
    g.Department_name,c.user_name as manager,r.role_name as designation,b.address1,b.address2,b.user_name as repname,
    b.phone_num->'$.mobile[0]' as phone1,b.dob,b.user_gender,b.emailId->'$.work[0]' as email
    FROM 
        representative_details a,
        user_details b,
        hierarchy h,
        teams d,
        department g,
        user_details c,
        user_roles r
    Where 
        a.user_id='$rep_id' 
        and b.location=h.hkey2 
        and b.team_id=d.teamid 
        and b.department=g.Department_id 
        and a.user_id=b.user_id
        and b.reporting_to=c.user_id
        and b.designation=r.role_id");        
    return $query->result();
  }
    
  public function view_productdata($teamid){
	 $remark="product";
   $que=$GLOBALS['$dbFramework']->query("SELECT a.product_id,a.currency_id,a.team_id,b.hkey2,b.hvalue2 as productname
   FROM
    product_currency_mapping a,
    hierarchy b 
    where
        a.product_id=b.hkey2  
        and a.togglebit='1' 
        and  a.team_id='$teamid' 
        and remarks='$remark' 
        group by a.product_id");
    $arr=$que->result_array();
       
    $row=0;
      if($que->num_rows()>0){
        for($i=0;$i<count($arr);$i++){
          $product_id=$arr[$i]['product_id'];
          $a[$row]['productname']=$arr[$i]['productname'];
          $a[$row]['product_id']=$arr[$i]['product_id'];
          $query1=$GLOBALS['$dbFramework']->query("SELECT a.currency_id,(select currency_name 
          from 
              currency 
          where 
              a.currency_id=currency_id)as currencyname
          from
             product_currency_mapping a 
          where
              product_id='$product_id' order by id ");
              $arr1=$query1->result_array();
              for($j=0;$j<count($arr1);$j++){
                $a[$row]['curdata'][$j]['currency_id']=$arr1[$j]['currency_id'];
                $a[$row]['curdata'][$j]['currencyname']=$arr1[$j]['currencyname'];
              }
             $row++;   
        }
      }
      return $a;		

    return $query->result();
  }

	public function rep_products($rep_id){
    $query=$GLOBALS['$dbFramework']->query("SELECT a.map_type,a.map_id,a.map_key,a.map_value,b.hvalue2 as product_name 
    FROM
        user_mappings a,
        hierarchy b 
    where
        a.user_id='$rep_id' 
        and a.map_type='product' 
        and a.map_key='currency'
        and a.map_id=b.hkey2 
        group by a.map_id");

    $arr=$query->result_array();
       
    $row=0;
     if($query->num_rows()>0){
      for($i=0;$i<count($arr);$i++){
        $product_id=$arr[$i]['map_id'];
        $a[$row]['product_id']=$arr[$i]['map_id'];
        $a[$row]['product_name']=$arr[$i]['product_name'];

        $query1=$GLOBALS['$dbFramework']->query("SELECT a.map_type,a.map_id,a.map_key, a.map_value, c.currency_name
        FROM 
            user_mappings a,
            currency c
        where
            a.user_id='$rep_id' 
            and a.map_type='product' 
            and a.map_key='currency'
            and a.map_value=c.currency_id
            group by a.map_id");
        $arr1=$query1->result_array();
          for($j=0;$j<count($arr1);$j++){
            $a[$row]['curdata'][$j]['currency_id']=$arr1[$j]['map_value'];
            $a[$row]['curdata'][$j]['currency_name']=$arr1[$j]['currency_name'];

          }
        $row++;   
      }
    }
    return $a;    
  }
   
   
    public function targetDetails($rep_id){
      $query=$GLOBALS['$dbFramework']->query("SELECT a.target_id,a.target_data,b.map_value as target_id,d.currency_name,
      b.map_id as product_id,c.hvalue2 as product_name
      FROM 
          rep_target_details a,
          user_mappings b,
          hierarchy c,
          currency d
      where
          a.target_id=b.map_value 
          AND b.map_id=c.hkey2
          AND b.user_id='$rep_id'
          AND a.target_data->'$.target_currency[0]'
          group by a.target_id");

      return $query->result();
    }
    
    public function update_rep_data($data,$rep_id) {
      $this->db->where('user_id', $rep_id);
      $update = $this->db->update('representative_details', $data);
      return $update;
    }
    
    public function insert_target($targetID,$data){ 
      $insert=$GLOBALS['$dbFramework']->query("INSERT INTO `rep_target_details` (`target_id`, `target_data`)
      VALUES 
          ('$targetID', '$data')");
      return $insert;       
    }
   
    public function insertTargetMap($data1,$product_id,$rep_id,$targetCurrency){
         $query=$GLOBALS['$dbFramework']->query("SELECT * from user_mappings a,rep_target_details b 
                                                where a.map_value=b.target_id
                                                and a.map_id='$product_id'
                                                and b.target_data->'$.target_currency[0]'='$targetCurrency'
                                                and a.user_id='$rep_id'");
         if($query->num_rows()>0)
            {
                return false;
            }
            else
            {
                 $insert=$this->db->insert('user_mappings',$data1);
                 return $insert;
            }
    
    }

    public function targetProducts($rep_id){
      $query=$GLOBALS['$dbFramework']->query("SELECT target_data->'$.product_ids' AS product_ids
      from 
        rep_target_details  
      where 
          rep_id='$rep_id'");
      return $query->result();
    }

    public function delete_products($rep_id){
		  $query=$GLOBALS['$dbFramework']->query("DELETE FROM rep_product_mapping WHERE rep_id='$rep_id'");
		  return $query;
    }

            
    public function update_rep_target($targetID,$data){
      $query=$this->db->query("UPDATE rep_target_details
			SET 
        target_data = '$data'
			WHERE 
          target_id='$targetID'");     	
      return TRUE;
    }


    public function targetProductsInfo($rep_id){   
      $query=$GLOBALS['$dbFramework']->query(" SELECT distinct a.map_type,a.map_id as product_id,b.hvalue2 as product_name
      FROM 
          user_mappings a,hierarchy b
      where 
          a.user_id='$rep_id' 
          and a.map_type='product' 
          and a.map_key='currency'
          and a.map_id=b.hkey2 
          group by a.map_id"); 
      return $query->result();       
    }

    public function targetCurrencyInfo($productId){
      $query=$GLOBALS['$dbFramework']->query("SELECT distinct a.map_value as currency_id,c.currency_name
      FROM 
          user_mappings a,
          currency c
      where 
          a.map_id='$productId'
          and a.map_type='product' 
          and a.map_key='currency'
          and a.map_value=c.currency_id");

      return $query->result();
    }

    public function insert_procurrency($add_user,$prodCurrencymain,$user_team){
      $map_type="product";
      $query=$GLOBALS['$dbFramework']->query("SELECT * 
      FROM 
          user_mappings 
      where 
          user_id='$add_user' 
          and map_type='$map_type' 
          and map_key='currency'");
      $query->result_array();
        if($query->num_rows()>0){
          $query1=$this->db->query("DELETE 
          FROM
              user_mappings 
          where
              user_id='$add_user' 
              and map_type='$map_type' 
              and map_key='currency'");                            
        }

      $map_type="product";
      $map_value="currency";
      $dt=date('ymdHis'); 
        foreach ($prodCurrencymain as $val){
          $prod=$val->prod;
          $currency=$val->currency;

          if($currency <> ""){
            $currency=rtrim($currency,',');
            $currency1=explode(',',$currency);
            for($i=0;$i<count($currency1);$i++){
              $procurmapID="PC";
              $procurmapID.=$dt;
              $procurmapID1=uniqid($procurmapID);
              $curid=$currency1[$i];
              $que=$GLOBALS['$dbFramework']->query("INSERT into user_mappings(user_mapping_id,user_id,map_type,map_id,map_key,map_value,transaction_Id)
              values('$procurmapID1','$add_user','$map_type','$prod','$map_value','$curid','$user_team')");
            }
            
          } 
					else{
					      $procurmapID="PC";
                $procurmapID.=$dt;
                $procurmapID1=uniqid($procurmapID);
						    $que=$GLOBALS['$dbFramework']->query("INSERT into user_mappings(user_mapping_id,user_id,map_type,map_id,map_key,transaction_Id)
                values('$procurmapID1','$add_user','$map_type','$prod','$map_value','$user_team')");						
  				}
        }
        return TRUE;
    }
}

?>
