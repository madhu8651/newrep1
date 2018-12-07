<?php
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('manager_salesRepresentativeController');

class manager_teamManagersModel extends CI_Model{
    
    public function __construct() {
        parent::__construct();
    }

    private function getChildrenForParent($user_id) {
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
    public function view_data($user) {
      try {
              $children = $user."','";
              $children .= $this->getChildrenForParent($user);

              $query = $GLOBALS['$dbFramework']->query("
              SELECT ud.user_id AS rep_id, ud.user_name AS repname,ud.user_state as status, 
              ur.role_name AS designation, t.teamname AS teamname, ud2.user_name AS manager,umpm.module_id as modules
              from user_details AS ud, user_roles AS ur, teams AS t, user_details AS ud2, user_module_plugin_mapping AS umpm
              where (ud.user_id IN ('$children')) 
              AND umpm.user_id=ud.user_id 
              AND ur.role_id=ud.designation 
              AND t.teamid=ud.team_id 
              AND ud.reporting_to=ud2.user_id 
              GROUP BY ud.user_id
              ORDER BY ud.id ASC
              ");
              
              return $query->result();        
      }
       catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }

    public function manager_data($manager_id){
      //Multiple Location value should include 
       try {
              $query=$GLOBALS['$dbFramework']->query("SELECT a.user_id as manager_id,a.travel_cost,a.outgoingsms_cost,
                                    a.outgoingcall_cost,a.outgoingsms_currency,a.outgoingcall_currency,
                                    b.user_name as rep_name,b.team_id,d.teamname,
                                    g.Department_name,c.user_name as manager,r.role_name as designation,
                                    b.address1,b.address2,b.user_name as repname,b.phone_num->'$.mobile[0]' as phone1,b.dob,b.user_gender,b.emailId->'$.work[0]' as email
                                    FROM representative_details a,user_details b,hierarchy h,teams d,department g,user_details c,user_roles r
                                    Where a.user_id='$manager_id' 
                                    and a.user_id=b.user_id
                                 --   and b.location=h.hkey2 
                                    and b.team_id=d.teamid 
                                    and b.department=g.Department_id 
                                    and b.reporting_to=c.user_id
                                    and b.designation=r.role_id
                                    group by a.user_id");
            return $query->result();
       }
      catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
      }
    

    }

    public function manager_products($manager_id){
      try {
              $query=$GLOBALS['$dbFramework']->query("
                                                        SELECT a.map_type,a.map_id,a.map_key,a.map_value,
                                                        b.hvalue2 as product_name
                                                        FROM user_mappings a,hierarchy b
                                                        where a.user_id='$manager_id' 
                                                        and a.map_type='product' 
                                                        and a.map_key='currency'
                                                        and a.map_id=b.hkey2 
                                                        group by a.map_id
                                                    ");

              $arr=$query->result_array();

              $row=0;
              if($query->num_rows()>0){
              for($i=0;$i<count($arr);$i++){
                $product_id=$arr[$i]['map_id'];
                $a[$row]['product_id']=$arr[$i]['map_id'];
                $a[$row]['product_name']=$arr[$i]['product_name'];
                $query1=$GLOBALS['$dbFramework']->query("
                                                            SELECT a.map_type,a.map_id,a.map_key,a.map_value,
                                                              c.currency_name 
                                                              FROM user_mappings a,currency c 
                                                              where a.user_id='$manager_id' 
                                                              and a.map_type='product'
                                                              and a.map_id='$product_id' 
                                                              and a.map_key='currency' 
                                                              and a.map_value=c.currency_id
                                                              group by a.map_value
                                                        ");

                $arr1=$query1->result_array();
                  for($j=0;$j<count($arr1);$j++)
                  {
                      $a[$row]['curdata'][$j]['currency_id']=$arr1[$j]['map_value'];
                      $a[$row]['curdata'][$j]['currency_name']=$arr1[$j]['currency_name'];
                  }
                 $row++;   
              }

              }
              return $a;
      }
       catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }

    public function targetDetails($manager_id){
       try {
                $query=$GLOBALS['$dbFramework']->query("SELECT rpt.target_data,rpt.target_id,um.map_id as product_id,
                hi.hvalue2 as product_name,rpt.target_data->'$.target_currency[0]' as currency_id,cu.currency_name
                from rep_target_details as rpt LEFT JOIN currency as cu ON rpt.target_data->'$.target_currency[0]'= cu.currency_id,user_mappings as um,hierarchy as hi
                where 
                rpt.target_data->'$.manager_id'='$manager_id'
                and rpt.target_id=um.map_value
                and um.map_id=hi.hkey2
                order by rpt.target_id");

                return $query->result();        
       }
       catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }


    public function teamSellType($userID,$teamID) {
        try {
               $query=$GLOBALS['$dbFramework']->query("
                                                        SELECT map_id as sell_type from user_mappings 
                                                        WHERE user_id='$userID'  
                                                        AND map_type='sell_type'
                                                        ORDER BY (sell_type) ASC
                                                    ");
              return $query->result();
        }
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function update_manager_data($data,$managerId) {
        try {
              $this->db->where('user_id', $managerId);
              $update = $this->db->update('representative_details', $data);
              return $update;
        }
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

     public function insertTargetMap($data1,$data) 
     { 
        try {

             // Check for the target already exists, with all Combination. 

                $products = $data->product_ids[0];

                $checkTargetQuery = $GLOBALS['$dbFramework']->query("
                                                                    SELECT 
                                                                    *
                                                                    FROM
                                                                    rep_target_details AS rpt,
                                                                    user_mappings AS um
                                                                    WHERE
                                                                    rpt.target_data->'$.period' = '$data->period'
                                                                    AND
                                                                    rpt.target_data->'$.product_ids[0]' = '$products'
                                                                    AND
                                                                    rpt.target_data->'$.target_type' = '$data->target_type'
                                                                    AND
                                                                    rpt.target_data->'$.manager_id' = '$data->manager_id'
                                                                    AND
                                                                    rpt.target_id = um.map_value
                                                                    GROUP BY rpt.target_id
                                                            ");

                if ($checkTargetQuery->num_rows() > 0) 
                {
                    
                  return false;
                }
                else
                {
                    $insert=$GLOBALS['$dbFramework']->insert('user_mappings',$data1);
                    return $insert;
                    
                }
                
            }
        catch (LConnectApplicationException $e) 
        {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }

    public function insert_target($targetID,$data){ 
      try {
               $insert=$GLOBALS['$dbFramework']->query("INSERT INTO `rep_target_details` (`target_id`, `target_data`) VALUES ('$targetID', '$data')");
                return $insert;      
      }
      catch (LConnectApplicationException $e) {
              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
              throw $e;
      }          
    }

    public function update_rep_target($targetID,$data,$targetArray) {
        try {

                // UserMappings update

                $updateUserMappings = $GLOBALS['$dbFramework']->update_batch('user_mappings',$targetArray, 'map_value');

                $query=$this->db->query("
                                                UPDATE rep_target_details
                                                SET target_data = '$data'
                                                WHERE target_id='$targetID'
                                            ");      
                return TRUE;

                 
        }
        catch (LConnectApplicationException $e) {
              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
              throw $e;
        }
 
    }

   public function targetProductsInfo($rep_id) { 
      try {
              $query=$GLOBALS['$dbFramework']->query("SELECT distinct a.map_type,a.map_id as product_id,
                                  b.hvalue2 as product_name
                                FROM user_mappings a,hierarchy b
                                where a.user_id='$rep_id' 
                                and a.map_type='product' 
                                and a.map_key='currency'
                                and a.map_id=b.hkey2 
                                group by a.map_id"); 
              return $query->result();
        }
         catch (LConnectApplicationException $e) {
              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
              throw $e;
        }

  }

  public function view_productdata($teamid){
    try {
            $a=array();
                        $remark="product";
                        $que=$GLOBALS['$dbFramework']->query("
                                                                SELECT distinct a.product_id,
                                                                (select distinct hvalue2 from hierarchy where
                                                                hkey2=a.product_id) as productname,
                                                                (select distinct hvalue1 from hierarchy where
                                                                hkey2=a.product_id) as productname1 from product_currency_mapping a 
                                                                where a.team_id='$teamid'
                                                                    and a.remarks='product'
                                                                    and a.togglebit=1;

                                                                ");
                        $arr=$que->result_array();
                        $row=0;
                        if($que->num_rows()>0){
                          for($i=0;$i<count($arr);$i++){
                              $product_id=$arr[$i]['product_id'];
                              $a[$row]['productname']=$arr[$i]['productname']." (".$arr[$i]['productname1'].")";
                              $a[$row]['product_id']=$arr[$i]['product_id'];

                              $query1=$GLOBALS['$dbFramework']->query("
                                                                        SELECT a.currency_id,(select currency_name from currency where
                                                                        a.currency_id=currency_id)as currencyname from product_currency_mapping a
                                                                        where product_id='$product_id' 
                                                                        and a.team_id='$teamid'
                                                                        and a.togglebit=1 order by id 
                                                                        ");
                              $arr1=$query1->result_array();
                                  for($j=0;$j<count($arr1);$j++){
                                              $a[$row]['curdata'][$j]['currency_id']=$arr1[$j]['currency_id'];
                                              $a[$row]['curdata'][$j]['currencyname']=$arr1[$j]['currencyname'];
                                  }
                               $row++;
                          }
                        }
                        return $a;
    }
    catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
      }

 }

public function insertProductivityData($data){

     try{

            $prodCurData        = $data['prodCurData'];
            $assignedUser       = $data['assignedUser'];
            $workingHoursData   = $data['workingHoursData'];
            $spendCalData       = $data['spendCalData'] ;
            $workingCalendar    = $data['workingCalendar'];

            $prodCurArray   = array();
            $userSpendData  = array();
            // Inserting product data based on the team ,
            // Fetch team id

            // Fetching Department of User.
            $departmentData=$GLOBALS['$dbFramework']->query("
                                                        SELECT 
                                                        department
                                                        FROM user_details 
                                                        WHERE  
                                                        user_id ='$assignedUser'
                                                    ");
            $department = $departmentData->result();

            $departmentId = $department[0]->department;



            $teamQuery=$GLOBALS['$dbFramework']->query("
                                                        SELECT 
                                                        * 
                                                        FROM teams 
                                                        WHERE  
                                                        department_id ='$departmentId'
                                                    ");
            $teamData = $teamQuery->result();


            $userTeam = $teamData[0]->teamid;


                    //Working Data of user converting into cron expression

                    foreach($workingHoursData as $val)
                    {
                        $expression[] = $this->json_cron($val);
                    }

                    $workingdays = json_encode($expression);
                    $user_attribute_id = uniqid(date('ymdHis'));
                    $userAttributes = array(
                                                'user_id'           =>$assignedUser,
                                                'user_attribute_id' =>$user_attribute_id,
                                                'attribute_type'    =>'workingDetails',
                                                'expression'        =>$workingdays
                                            );

                    $this->updateWorkingDays($userAttributes,$assignedUser);


                    if ($spendCalData->accounting == 1) 
                    {
                       $userSpendData = array
                                        (
                                            'user_id'=>$assignedUser,
                                            'accounting'=>$spendCalData->accounting,
                                            'resource_currency'=>$spendCalData->resource_currency,
                                            'resource_cost'=>$spendCalData->resource_cost,
                                            'outgoingcall_currency'=>$spendCalData->outgoingcall_currency,
                                            'outgoingcall_cost'=>$spendCalData->outgoingcall_cost,
                                            'outgoingsms_currency'=>$spendCalData->outgoingsms_currency,
                                            'outgoingsms_cost'=>$spendCalData->outgoingsms_cost,
                                            'holiday_calender'=>$workingCalendar
                                        );
                    }
                     
                    // Updating user spend data, 
                    $this->updateUserSpendData($userSpendData,$assignedUser);       


                //Delete all product data related to assigned user then insert to mappings.

                // Deleting all product data
                $deleteProductCurrency = $this->deleteFromMappings($assignedUser,'product');      

               
                    foreach ($prodCurData as $Pkey => $Pvalue) 
                    {

                        foreach ($Pvalue->curdata as $Ckey => $Cvalue) 
                        {   
                            $dt                 =date('ymdHis');
                            $letter             =chr(rand(97,122));
                            $letter            .=chr(rand(97,122));
                            $procutMapId        =$letter;
                            $procutMapId       .=$dt;
                            $userMappingId      =uniqid($procutMapId);

                            $data  = array(

                                        'user_mapping_id' =>$userMappingId,
                                        'user_id'         =>$assignedUser,
                                        'map_type'        =>'product',
                                        'map_id'          =>$Pvalue->product_id,
                                        'map_key'         =>'currency',
                                        'map_value'       =>$Cvalue->currency_id,
                                        'transaction_Id'  =>$userTeam
                                        );

                            array_push($prodCurArray,$data);
                        }

                    }

                    echo $this->insertToMappings($prodCurArray);


                }
        catch (LConnectApplicationException $e)
        {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
        }
}


  public function targetCurrencyInfo($user,$productId)  {
    try{
          $query=$GLOBALS['$dbFramework']->query("
                                  SELECT um.map_value as currency_id,cu.currency_name from user_mappings as um,currency as cu 
                                  where um.user_id='$user'
                                  and um.map_key='currency'
                                  and um.map_value=cu.currency_id 
                                  and um.map_id='$productId'
                                  group by um.map_value");

          return $query->result(); 
    }
    catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
    }
    
  }

  public function getCurrency($user_id){
     try{
          $query=$GLOBALS['$dbFramework']->query("SELECT team_id from user_details where user_id ='$user_id'");
          $row=$query->result();
          $teamid=$row[0]->team_id;
          $query1=$GLOBALS['$dbFramework']->query("SELECT um.map_value as currency_id,cu.currency_name
                                                  From user_mappings as um,currency as cu 
                                                  where um.transaction_id='$teamid'
                                                  and um.map_key='currency'
                                                  and um.map_value=cu.currency_id 
                                                  group by um.map_value");
          return $query1->result();
    }
    catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
    }
   }  

   public function checkSuperior($user_id,$loginUser)
   {
        try{

         $checkSuperior = $GLOBALS['$dbFramework']->query("
                                                                    SELECT 
                                                                    *
                                                                    FROM
                                                                    user_details AS ud,
                                                                    user_details AS ud1
                                                                    WHERE
                                                                    ud.reporting_to = ud1.user_id
                                                                    AND ud1.user_name = 'Admin'
                                                                    AND ud.user_id = '$user_id'

                                                            ");
		 $moduleQuery = $GLOBALS['$dbFramework']->query("
																	SELECT 
																	*
																	FROM
																	user_licence AS ul
																	WHERE 
																	ul.user_id = '$user_id'

													");
		$moduleArray = $moduleQuery->result();											
		$permission = array();

                if ($checkSuperior->num_rows() > 0) 
                {
                    $permission = array(
                        'canAddHimself' => 'Yes',
                        'canEditHimself' => 'Yes',
						'manager_module' =>$moduleArray[0]->manager_module,
						'sales_module'=>$moduleArray[0]->sales_module,
						'canAdd'=> 'Yes',
						'canEdit'=>'Yes',
						'user_id'=>$moduleArray[0]->user_id,
						'loginUser'=>$loginUser
                    );

                    
                }
                else
                {
                    $permission = array(
                        'canAddHimself' => 'No',
                        'canEditHimself' => 'No',
						'manager_module' =>$moduleArray[0]->manager_module,
						'sales_module'=>$moduleArray[0]->sales_module,
						'canAdd'=> 'Yes',
						'canEdit'=>'Yes',
						'user_id'=>$moduleArray[0]->user_id,
						'loginUser'=>$loginUser
                    );
                }

                return array($permission);


        }
        catch (LConnectApplicationException $e) 
        {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
   }

   public function getTeamId($userId)
   {
        try{
                $query=$GLOBALS['$dbFramework']->query("
                                    SELECT team_id from user_details where user_id ='$userId'
                                    ");
                return $query->result();
        
        }
        catch (LConnectApplicationException $e) 
        {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
   }

   public function fetchTeamRelatedData($teamId, $userId)
   {
       try{

            // Fetching Department of User.
            $departmentData=$GLOBALS['$dbFramework']->query("
                                                        SELECT 
                                                        department
                                                        FROM user_details 
                                                        WHERE  
                                                        user_id ='$userId'
                                                    ");
            $department = $departmentData->result();

            $departmentId = $department[0]->department;



            $teamQuery=$GLOBALS['$dbFramework']->query("
                                                        SELECT 
                                                        * 
                                                        FROM teams 
                                                        WHERE  
                                                        department_id ='$departmentId'
                                                    ");
            $teamData = $teamQuery->result();

            $bussinessRoot = $teamData[0]->business_location_id;
            $industryRoot  = $teamData[0]->industry_id;

            // Fetching team bussiness location,product and calendar.
            // Bussiness Location Data. 
            $bussiness=array();
            $industry=array();
            $userBusiness = FALSE;
            $userCurrency = FALSE;
            $userProduct  = FALSE;
            $userIndustry = FALSE;

            if ($bussinessRoot != '' OR $bussinessRoot !=NULL) 
            {
                
                      $cnt=0;
                      $row=0;
                      $hierarchy_class="business_location";
                      $bussinessQuery=$GLOBALS['$dbFramework']->query(" call get_tree_leafnode('".$hierarchy_class."','".$bussinessRoot."'); ");

                           if($bussinessQuery->num_rows()>0)
                           {
                                $arr2=$bussinessQuery->result_array();
                                    for($j=0;$j<count($arr2);$j++)
                                    {
                                        $hkey2=$arr2[$j]['nodeid'];
                                        $nodename=$arr2[$j]['nodename'];
                                        $nodename1=$arr2[$j]['nodename1'];

                                            $bussiness[$cnt]['nodeid']=$hkey2;
                                            $bussiness[$cnt]['nodename']=$nodename;
                                            $bussiness[$cnt]['bussinessLoc1'] = $nodename1;
                                            $cnt=$cnt+1;
                                    }
                           }
            }
            // Indusrty Data

            if ($industryRoot != '' OR $industryRoot !=NULL) 
            {
                
                      $cnt=0;
                      $row=0;
                      $hierarchy_class="industry";
                      $industryQuery=$GLOBALS['$dbFramework']->query(" call get_tree_leafnode('".$hierarchy_class."','".$industryRoot."'); ");

                           if($industryQuery->num_rows()>0)
                           {
                                $arr2=$industryQuery->result_array();
                                    for($j=0;$j<count($arr2);$j++)
                                    {
                                        $hkey2=$arr2[$j]['nodeid'];
                                        $nodename=$arr2[$j]['nodename'];
                                        $nodename1=$arr2[$j]['nodename1'];

                                            $industry[$cnt]['nodeid']=$hkey2;
                                            $industry[$cnt]['nodename']=$nodename;
                                            $industry[$cnt]['clientInds1']=$nodename1;
                                            $cnt=$cnt+1;
                                    }
                           }
                        
            }

            //Product Data.
            $product = $this->view_productdata($teamId);


            // Calendar Data.

            $calendar = array();

            $calendarQuery=$GLOBALS['$dbFramework']->query("
                                                        SELECT 
                                                        * 
                                                        FROM 
                                                        calender 
                                                        ORDER BY 
                                                        calendername
                                                        ");
            $calendar = $calendarQuery->result();

            // Spend Calculation Currency 

            $currency = array();

            $currencyQuery=$GLOBALS['$dbFramework']->query("
                                                            SELECT 
                                                            *
                                                            FROM
                                                            currency AS ci,
                                                            currency_category AS cc
                                                            WHERE
                                                            ci.currency_category_id = cc.currency_category_id
                                                            AND cc.currency_category_name = 'Spend Calculation'
                                                        ");
            $currency = $currencyQuery->result();



            $finalArray = array(
                                'bussinessLocation' => $bussiness,
                                'industry'          => $industry,
                                'product'           => $product,
                                'calendar'          => $calendar,
                                'currency'          => $currency
                                );

            return ($finalArray); 

        
        }
        catch (LConnectApplicationException $e) 
        {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
   }


    public function json_cron($work)
    {
        $stime = explode(":",$work->start_time);
        $etime = explode(":",$work->end_time);
        $week = $work->day_of_week;

        $expression = "0 ".(int)$stime[1].",".(int)$etime[1]." ".(int)$stime[0].",".(int)$etime[0]." * * ";
            switch($week)
            {
                case 'SUN' : $expression .= "1";
                break;
                case 'MON' : $expression .= "2";
                break;
                case 'TUE' : $expression .= "3";
                break;
                case 'WED' : $expression .= "4";
                break;
                case 'THU' : $expression .= "5";
                break;
                case 'FRI' : $expression .= "6";
                break;
                case 'SAT' : $expression .= "7";
                break;
            }

        return $expression;
    } 


    public function updateUserSpendData($data,$user)
    {
        // Check if data exits insert,else update.
        
        $query=$GLOBALS['$dbFramework']->query("
                                                SELECT 
                                                * 
                                                FROM 
                                                representative_details 
                                                where 
                                                user_id='$user'
                                                ");

        if($query->num_rows()>0)
        {
            $update = $GLOBALS['$dbFramework']->update('representative_details' ,$data, array('LOWER(user_id)' => strtolower($user)));
            return $update;
        }
        else
        {
            $insert = $GLOBALS['$dbFramework']->insert('representative_details',$data);
            return $insert;
        }  

    }

    public function updateWorkingDays($data,$user)
    {
       try{

            $query=$GLOBALS['$dbFramework']->query("
                                                    SELECT 
                                                    * 
                                                    FROM 
                                                    user_attributes 
                                                    where user_id=
                                                    '$user'");
            if($query->num_rows()>0)
            {
                $update = $GLOBALS['$dbFramework']->update('user_attributes' ,$data, array('LOWER(user_id)' => strtolower($user)));
                return $update;
            }
            else
            {
                $insert = $GLOBALS['$dbFramework']->insert('user_attributes',$data);
                return $insert;    
            }
        }
        catch (LConnectApplicationException $e)
        {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function insertToMappings($data)
    {
        try
        {   
            $query = $GLOBALS['$dbFramework']->insert_batch('user_mappings',$data);         
            return $query;
        }
        catch (LConnectApplicationException $e) 
        {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function updateBusinessDetails($data)
    {
        try
        {   
            $businessLocData = $data['businessLocData'];
            $industryData    = $data['industryData'];
            $assignedUser    = $data['assignedUser'];

            $bussinessDataArray = array();
            $industryDataArray  = array();
            // Fetch team id

            // Fetching Department of User.
            $departmentData=$GLOBALS['$dbFramework']->query("
                                                                SELECT 
                                                                department
                                                                FROM user_details 
                                                                WHERE  
                                                                user_id ='$assignedUser'
                                                            ");
            $department = $departmentData->result();

            $departmentId = $department[0]->department;



            $teamQuery=$GLOBALS['$dbFramework']->query("
                                                            SELECT 
                                                            * 
                                                            FROM teams 
                                                            WHERE  
                                                            department_id ='$departmentId'
                                                        ");
            $teamData = $teamQuery->result();


            $userTeam = $teamData[0]->teamid;

            
            // Assigned Business Location Data.
            foreach ($businessLocData as $Bkey => $Bvalue) 
            {   
                $dt             =date('ymdHis');
                $letter         =chr(rand(97,122));
                $letter         .=chr(rand(97,122));
                $bussLocID      =$letter;
                $bussLocID      .=$dt;
                $userMappingId  =uniqid($bussLocID);

                $businessData =array(
                                        'user_mapping_id'   =>$userMappingId,
                                        'user_id'           =>$assignedUser,
                                        'map_type'          =>"business_location",
                                        'map_id'            =>$Bvalue->nodeid,
                                        'transaction_Id'    =>$assignedUser
                                    );

                array_push($bussinessDataArray, $businessData);   
            }

            // Assigned Industry Data.

            foreach ($industryData as $Ikey => $Ivalue) 
            {   
                $dt             =date('ymdHis');
                $letter         =chr(rand(97,122));
                $letter         .=chr(rand(97,122));
                $industryID      =$letter;
                $industryID      .=$dt;
                $userMappingId  =uniqid($industryID);

                $industryData =array(
                                        'user_mapping_id'   =>$userMappingId,
                                        'user_id'           =>$assignedUser,
                                        'map_type'          =>"clientele_industry",
                                        'map_id'            =>$Ivalue->nodeid,
                                        'transaction_Id'    =>$assignedUser
                                    );

                array_push($industryDataArray, $industryData);   
            }

            //Delete all business data related to assigned user then insert to mappings.

            // Deleting all bussiness data
            $deleteBusinessLoc = $this->deleteFromMappings($assignedUser,'business_location');  

            //Inserting to map table
            $this->insertToMappings($bussinessDataArray);

            //Delete all industry data related to assigned user then insert to mappings.

            // Deleting all industry data

            $deleteIndustry = $this->deleteFromMappings($assignedUser,'clientele_industry');

            //Inserting to map table      

            $this->insertToMappings($industryDataArray);

            return TRUE;


        }
        catch (LConnectApplicationException $e) 
        {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function deleteFromMappings($user,$mapType)
    {
        try{

            $success = 0;

            $query=$GLOBALS['$dbFramework']->query("
                                                    SELECT 
                                                    * 
                                                    FROM 
                                                    user_mappings
                                                    WHERE 
                                                    user_id='$user' 
                                                    AND 
                                                    map_type='$mapType'
                                                    ");
            if($query->num_rows()>0)
            {
                $query1=$GLOBALS['$dbFramework']->query("
                                                        DELETE 
                                                        FROM 
                                                        user_mappings 
                                                        WHERE 
                                                        user_id='$user' 
                                                        AND map_type='$mapType'
                                                        ");
                
                return $success = $query1;
            }

        }
        catch (LConnectApplicationException $e)
        {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }



}


  ?>
