<?php

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_teamModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();

class admin_teamModel extends CI_Model{
     public function __construct(){
        parent::__construct();
    }
 public function view_data(){
            //$this->db->initialize();
    try{
              $row=0;
              $sellstr=$sellstr1="";
              $a=$str1=array();
              $que=$GLOBALS['$dbFramework']->query("select a.teamid,a.teamname,a.location,a.productid,a.department_id,a.business_location_id,a.industry_id,customer_management,
                                      (select distinct hvalue2 from hierarchy where a.location=hkey2) as locationname,
                                      (select distinct hvalue2 from hierarchy where hkey2=a.productid) as productname,
                                      (select distinct hvalue2 from hierarchy where hkey2=a.business_location_id) as business,
                                      (select distinct hvalue2 from hierarchy where hkey2=a.industry_id) as industry,
                                      (select Department_name from department where Department_id=a.department_id)
                                      as deptname,a.regionid   from teams a ;");
              $arr=$que->result_array();
              if($que->num_rows()>0){
                for($i=0;$i<count($arr);$i++){
                    $teamid=$arr[$i]['teamid'];
                    $business_location_id=$arr[$i]['business_location_id'];
                    $industry_id=$arr[$i]['industry_id'];
                    $a[$row]['teamid']=$arr[$i]['teamid'];
                    $a[$row]['teamname']=$arr[$i]['teamname'];
                    $a[$row]['location']=$arr[$i]['location'];
                    $a[$row]['productid']=$arr[$i]['productid'];
                    $a[$row]['department_id']=$arr[$i]['department_id'];
                    $a[$row]['business_location_id']=$arr[$i]['business_location_id'];
                    $a[$row]['industry_id']=$arr[$i]['industry_id'];
                    $a[$row]['customer_management']=$arr[$i]['customer_management'];
                    $a[$row]['locationname']=$arr[$i]['locationname'];
                    $a[$row]['productname']=$arr[$i]['productname'];
                    $a[$row]['business']=$arr[$i]['business'];
                    $a[$row]['industry']=$arr[$i]['industry'];
                    $a[$row]['deptname']=$arr[$i]['deptname'];
                    $a[$row]['regionid']=$arr[$i]['regionid'];

                    $str=$arr[$i]['regionid'];
                    $str1=explode(',',$str);
                    $result = "'" . implode ( "', '", $str1 ) . "'";
                    $sellstr="";
                    $query11=$GLOBALS['$dbFramework']->query("select * from lookup where lookup_name='support_process' and lookup_id in (".$result."); ");
                    $arr11=$query11->result_array();
                    for($k=0;$k<count($arr11);$k++){
                          $sellstr.=" ".$arr11[$k]['lookup_value'].",";
                    }
                    $sellstr1=rtrim($sellstr,',');
                    $sellstr1=ltrim($sellstr1,' ');
                    $a[$row]['selltype']=$sellstr1;
                    $row++;
                }
              }
             return $a;

         }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
         }
    }

    public function dept_data(){
       try{
                $query=$GLOBALS['$dbFramework']->query("select * from department order by id");
                return $query->result();
         }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
         }
    }
    public function getselltype(){
       try{
                $query=$GLOBALS['$dbFramework']->query("select * from lookup WHERE lookup_name='support_process' and togglebit=1 order by id");
                return $query->result();
         }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
         }
    }
    public function view_productdata(){
      try{
              $query = $GLOBALS['$dbFramework']->query(" call get_hierarchy_details('products','onload','','');");
              //$this->db->close();
              return $query->result();
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
         }

    }
    public function view_business(){
       try{
              $query = $GLOBALS['$dbFramework']->query("call get_hierarchy_details('business_location','onload','','');");
              //$this->db->close();
              return $query->result();
       }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
         }

    }
     public function view_industry(){
       try{
              $query = $GLOBALS['$dbFramework']->query("call get_hierarchy_details('industry','onload','','');");
              //$this->db->close();
              return $query->result();
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
         }
    }

    public function view_locationdata(){
      try{
        $query =$GLOBALS['$dbFramework']->query("call get_hierarchy_details('office_location','onload','','');");

        return $query->result();
      }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
      }


    }
    public function insert_data($teamdata,$teamname,$teamid,$prodCurrencymain,$OfficeLocLastNode,$bussiLocLastNode,$indusLocLastNode){
      try{

                  $query=$GLOBALS['$dbFramework']->query("select * from teams where LOWER(teamname)=LOWER('".$teamname."')");
                  //$query=$GLOBALS['$dbFramework']->query("call common_check_duplicate('teams','teamname','".ucfirst(strtolower($teamname))."','','')");
                  if($query->num_rows()>0){
                    //$this->db->close();
                    return false;
                  }
                  else{
                      $GLOBALS['$dbFramework']->insert('teams', $teamdata);
                      $dt=date('ymdHis');
                      $proclassid=$teamdata['productid'];
                      $indusclassid=$teamdata['location'];
                      foreach ($prodCurrencymain as  $value) {
                              $prod=$value->prod;
                              $currency=$value->currency;
                              if($currency <> ""){
                                  $currency=rtrim($currency,',');
                                  $currency1=explode(',',$currency);
                                  for($i=0;$i<count($currency1);$i++){
                                      $curid=$currency1[$i];
                                      $letter=chr(rand(97,122));
                                      $letter.=chr(rand(97,122));
                                      $procurmapID=$letter;
                                      $procurmapID.=$dt;
                                      $procurmapID1=uniqid($procurmapID);


                                      $que=$GLOBALS['$dbFramework']->query("insert into product_currency_mapping(product_currency_map_id,product_classid,
                                                              product_id,currency_id,team_id,remarks)values('".$procurmapID1."','".$proclassid."','".$prod."','".$curid."','".$teamid."','product')");
                                  }
                              }else{

                                  $letter=chr(rand(97,122));
                                  $letter.=chr(rand(97,122));
                                  $procurmapID=$letter;
                                  $procurmapID.=$dt;
                                  $procurmapID1=uniqid($procurmapID);

                                  $que=$GLOBALS['$dbFramework']->query("insert into product_currency_mapping(product_currency_map_id,product_classid,
                                                              product_id,team_id,remarks)values('".$procurmapID1."','".$proclassid."','".$prod."','".$teamid."','product')");
                              }
                      }

                      if($OfficeLocLastNode){
                        $attr = (array)$OfficeLocLastNode;// convert the object into array
                        $row=0;
                            foreach($attr as $key => $val)
                            {
                                      $attrname=$key;
                                      $attr_val=$attr[$key];

                                      $letter=chr(rand(97,122));
                                      $letter.=chr(rand(97,122));
                                      $procurmapID=$letter;
                                      $procurmapID.=$dt;
                                      $procurmapID1=uniqid($procurmapID);

                                      $que=$GLOBALS['$dbFramework']->query("insert into product_currency_mapping(product_currency_map_id,product_classid,
                                                              product_id,team_id,remarks)values('".$procurmapID1."','".$indusclassid."','".$attrname."','".$teamid."','ofloc')");

                            }
                      }
                      if($bussiLocLastNode){
                        $attr = (array)$bussiLocLastNode;// convert the object into array
                        $row=0;
                            foreach($attr as $key => $val)
                            {
                                      $attrname=$key;
                                      $attr_val=$attr[$key];

                                      $letter=chr(rand(97,122));
                                      $letter.=chr(rand(97,122));
                                      $procurmapID=$letter;
                                      $procurmapID.=$dt;
                                      $procurmapID1=uniqid($procurmapID);

                                      $que=$GLOBALS['$dbFramework']->query("insert into product_currency_mapping(product_currency_map_id,product_classid,
                                                              product_id,team_id,remarks)values('".$procurmapID1."','".$indusclassid."','".$attrname."','".$teamid."','busloc')");

                            }
                      }
                      if($indusLocLastNode){
                        $attr = (array)$indusLocLastNode;// convert the object into array
                        $row=0;
                            foreach($attr as $key => $val)
                            {
                                      $attrname=$key;
                                      $attr_val=$attr[$key];

                                      $letter=chr(rand(97,122));
                                      $letter.=chr(rand(97,122));
                                      $procurmapID=$letter;
                                      $procurmapID.=$dt;
                                      $procurmapID1=uniqid($procurmapID);

                                      $que=$GLOBALS['$dbFramework']->query("insert into product_currency_mapping(product_currency_map_id,product_classid,
                                                              product_id,team_id,remarks)values('".$procurmapID1."','".$indusclassid."','".$attrname."','".$teamid."','indus')");

                            }
                      }
                      //$this->db->close();
                      return TRUE;
                  }
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
      }
    }
    public function update_data($teamdata,$teamdata1,$teamname,$teamid,$prodCurrency,$OfficeLocLastNode,$bussiLocLastNode,$indusLocLastNode){
    try{

              $query=$GLOBALS['$dbFramework']->query("SELECT * FROM teams where LOWER(teamname)=LOWER('".$teamname."') AND teamid<>'".$teamid."'");
              //$query=$GLOBALS['$dbFramework']->query("call common_check_duplicate('teams','teamname','".ucfirst(strtolower($teamname))."','','')");
                if($query->num_rows()>0){

                        foreach ($query->result() as $row)
                        {
                             $teamid1 = $row->teamid;
                        }

                        if(strtolower($teamid1) <> strtolower($teamid)){
                            return false;
                        }else{

                        $GLOBALS['$dbFramework']->update('teams' ,$teamdata1, array('LOWER(teamid)' => strtolower($teamid)));
                        $GLOBALS['$dbFramework']->query("delete from product_currency_mapping where team_id='".$teamid."'");

                        $dt=date('ymdHis');
                        $proclassid=$teamdata1['productid'];
                        $indusclassid=$teamdata1['location'];
                        $business_location_id=$teamdata['business_location_id'];
                        $industry_id=$teamdata['industry_id'];
                        foreach ($prodCurrency as  $value) {
                                $prod=$value->prod;
                                $currency=$value->currency;
                                if($currency <> ""){
                                    $currency=rtrim($currency,',');
                                    $currency1=explode(',',$currency);
                                    for($i=0;$i<count($currency1);$i++){
                                        $curid=$currency1[$i];
                                        $letter=chr(rand(97,122));
                                        $letter.=chr(rand(97,122));
                                        $procurmapID=$letter;
                                        $procurmapID.=$dt;
                                        $procurmapID1=uniqid($procurmapID);


                                        $que=$GLOBALS['$dbFramework']->query("insert into product_currency_mapping(product_currency_map_id,product_classid,
                                                                product_id,currency_id,team_id,remarks)values('".$procurmapID1."','".$proclassid."','".$prod."','".$curid."','".$teamid."','product')");
                                    }
                                }else{

                                    $letter=chr(rand(97,122));
                                    $letter.=chr(rand(97,122));
                                    $procurmapID=$letter;
                                    $procurmapID.=$dt;
                                    $procurmapID1=uniqid($procurmapID);

                                    $que=$GLOBALS['$dbFramework']->query("insert into product_currency_mapping(product_currency_map_id,product_classid,
                                                                product_id,team_id,remarks)values('".$procurmapID1."','".$proclassid."','".$prod."','".$teamid."','product')");
                                }
                        }

                        if($OfficeLocLastNode){
                          $attr = (array)$OfficeLocLastNode;// convert the object into array
                          $row=0;
                              foreach($attr as $key => $val)
                              {
                                        $attrname=$key;
                                        $attr_val=$attr[$key];

                                        $letter=chr(rand(97,122));
                                        $letter.=chr(rand(97,122));
                                        $procurmapID=$letter;
                                        $procurmapID.=$dt;
                                        $procurmapID1=uniqid($procurmapID);

                                        $que=$GLOBALS['$dbFramework']->query("insert into product_currency_mapping(product_currency_map_id,product_classid,
                                                                product_id,team_id,remarks)values('".$procurmapID1."','".$indusclassid."','".$attrname."','".$teamid."','ofloc')");

                              }
                        }
                        if($bussiLocLastNode){
                          $attr = (array)$bussiLocLastNode;// convert the object into array
                          $row=0;
                              foreach($attr as $key => $val)
                              {
                                        $attrname=$key;
                                        $attr_val=$attr[$key];

                                        $letter=chr(rand(97,122));
                                        $letter.=chr(rand(97,122));
                                        $procurmapID=$letter;
                                        $procurmapID.=$dt;
                                        $procurmapID1=uniqid($procurmapID);

                                        $que=$GLOBALS['$dbFramework']->query("insert into product_currency_mapping(product_currency_map_id,product_classid,
                                                                product_id,team_id,remarks)values('".$procurmapID1."','".$business_location_id."','".$attrname."','".$teamid."','busloc')");

                              }
                        }
                        if($indusLocLastNode){
                          $attr = (array)$indusLocLastNode;// convert the object into array
                          $row=0;
                              foreach($attr as $key => $val)
                              {
                                        $attrname=$key;
                                        $attr_val=$attr[$key];

                                        $letter=chr(rand(97,122));
                                        $letter.=chr(rand(97,122));
                                        $procurmapID=$letter;
                                        $procurmapID.=$dt;
                                        $procurmapID1=uniqid($procurmapID);

                                        $que=$GLOBALS['$dbFramework']->query("insert into product_currency_mapping(product_currency_map_id,product_classid,
                                                                product_id,team_id,remarks)values('".$procurmapID1."','".$industry_id."','".$attrname."','".$teamid."','indus')");

                              }
                        }
                            //$this->db->close();
                            return true;
                      }

                }
                else{
                        //$this->db->where('teamid',$teamid);
                        //$this->db->update('teams',$teamdata);
                        $GLOBALS['$dbFramework']->update('teams' ,$teamdata, array('LOWER(teamid)' => strtolower($teamid)));
                        $GLOBALS['$dbFramework']->query("delete from product_currency_mapping where team_id='".$teamid."'");
                        $dt=date('ymdHis');
                        $proclassid=$teamdata['productid'];
                        $indusclassid=$teamdata['location'];
                        $business_location_id=$teamdata['business_location_id'];
                        $industry_id=$teamdata['industry_id'];
                        foreach ($prodCurrency as  $value) {
                                $prod=$value->prod;
                                $currency=$value->currency;
                                if($currency <> ""){
                                    $currency=rtrim($currency,',');
                                    $currency1=explode(',',$currency);
                                    for($i=0;$i<count($currency1);$i++){
                                        $curid=$currency1[$i];
                                        $letter=chr(rand(97,122));
                                        $letter.=chr(rand(97,122));
                                        $procurmapID=$letter;
                                        $procurmapID.=$dt;
                                        $procurmapID1=uniqid($procurmapID);


                                        $que=$GLOBALS['$dbFramework']->query("insert into product_currency_mapping(product_currency_map_id,product_classid,
                                                                product_id,currency_id,team_id,remarks)values('".$procurmapID1."','".$proclassid."','".$prod."','".$curid."','".$teamid."','product')");
                                    }
                                }else{

                                    $letter=chr(rand(97,122));
                                    $letter.=chr(rand(97,122));
                                    $procurmapID=$letter;
                                    $procurmapID.=$dt;
                                    $procurmapID1=uniqid($procurmapID);

                                    $que=$GLOBALS['$dbFramework']->query("insert into product_currency_mapping(product_currency_map_id,product_classid,
                                                                product_id,team_id,remarks)values('".$procurmapID1."','".$proclassid."','".$prod."','".$teamid."','product')");
                                }
                        }

                        if($OfficeLocLastNode){
                          $attr = (array)$OfficeLocLastNode;// convert the object into array
                          $row=0;
                              foreach($attr as $key => $val)
                              {
                                        $attrname=$key;
                                        $attr_val=$attr[$key];

                                        $letter=chr(rand(97,122));
                                        $letter.=chr(rand(97,122));
                                        $procurmapID=$letter;
                                        $procurmapID.=$dt;
                                        $procurmapID1=uniqid($procurmapID);

                                        $que=$GLOBALS['$dbFramework']->query("insert into product_currency_mapping(product_currency_map_id,product_classid,
                                                                product_id,team_id,remarks)values('".$procurmapID1."','".$indusclassid."','".$attrname."','".$teamid."','ofloc')");

                              }
                        }
                        if($bussiLocLastNode){
                          $attr = (array)$bussiLocLastNode;// convert the object into array
                          $row=0;
                              foreach($attr as $key => $val)
                              {
                                        $attrname=$key;
                                        $attr_val=$attr[$key];

                                        $letter=chr(rand(97,122));
                                        $letter.=chr(rand(97,122));
                                        $procurmapID=$letter;
                                        $procurmapID.=$dt;
                                        $procurmapID1=uniqid($procurmapID);

                                        $que=$GLOBALS['$dbFramework']->query("insert into product_currency_mapping(product_currency_map_id,product_classid,
                                                                product_id,team_id,remarks)values('".$procurmapID1."','".$business_location_id."','".$attrname."','".$teamid."','busloc')");

                              }
                        }
                        if($indusLocLastNode){
                          $attr = (array)$indusLocLastNode;// convert the object into array
                          $row=0;
                              foreach($attr as $key => $val)
                              {
                                        $attrname=$key;
                                        $attr_val=$attr[$key];

                                        $letter=chr(rand(97,122));
                                        $letter.=chr(rand(97,122));
                                        $procurmapID=$letter;
                                        $procurmapID.=$dt;
                                        $procurmapID1=uniqid($procurmapID);

                                        $que=$GLOBALS['$dbFramework']->query("insert into product_currency_mapping(product_currency_map_id,product_classid,
                                                                product_id,team_id,remarks)values('".$procurmapID1."','".$industry_id."','".$attrname."','".$teamid."','indus')");

                              }
                        }

                        //$this->db->close();
                        return TRUE;
                }
      }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
      }
    }
    public function currency_data($proarray){
        try{
                  if($proarray){
                  $attr = (array)$proarray;// convert the object into array
                  $row=0;
                      foreach($attr as $key => $val)
                      {
                              $attrname=$key;
                              $attr_val=$attr[$key];

                              $que=$GLOBALS['$dbFramework']->query("select distinct a.product_id,(select distinct hvalue2 from hierarchy where hkey2=a.product_id) as productname
                                                                    ,(select distinct hvalue1 from hierarchy where hkey2=a.product_id) as productname1
                                                   from product_attributes a where a.product_id='".$attrname."'");
                              $arr=$que->result_array();
                              if($que->num_rows()>0){
                                for($i=0;$i<count($arr);$i++){
                                    $product_id=$arr[$i]['product_id'];
                                    $a[$row]['productname']=$arr[$i]['productname']." (".$arr[$i]['productname1'].")";
                                    $a[$row]['product_id']=$arr[$i]['product_id'];

                                    $query1=$GLOBALS['$dbFramework']->query("select a.currency_id,(select currency_name from currency where a.currency_id=currency_id)as currencyname
                                                                from product_attributes a where product_id='".$product_id."' and a.togglebit=1
                                                                and a.currency_id is not null order by id ");
                                    $arr1=$query1->result_array();
                                        for($j=0;$j<count($arr1);$j++){
                                                    $a[$row]['curdata'][$j]['currency_id']=$arr1[$j]['currency_id'];
                                                    $a[$row]['curdata'][$j]['currencyname']=$arr1[$j]['currencyname'];
                                        }
                                }
                              }else{

                                  $a[$row]['productname']=$attr_val;
                                  $a[$row]['product_id']=$attrname;

                              }
                              $row++;
                      }

                  }
                  //$this->db->close();
                  return $a;
          }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
      }
    }
    public function locationdata_edit($teamid){
       try{
                  $json=array();
                  $a=array();
                  $c=array();

                  $query = $GLOBALS['$dbFramework']->query("call edit_tree('office_location','team_page','$teamid','Editloc');");
                  if($query->num_rows()>0){
                      $json=$query->result();
                  }

                  /* -------------- saved products and currency ------------------------------- */
                  $row=0;
                  $query1=$GLOBALS['$dbFramework']->query("select distinct a.product_id,(select distinct hvalue2 from hierarchy where hkey2=a.product_id) as productname
                                                   from product_currency_mapping a where a.team_id='".$teamid."' and a.remarks='ofloc' ");
                        $arr1=$query1->result_array();
                        for($j=0;$j<count($arr1);$j++){
                                    $product_id=$arr1[$j]['product_id'];
                                    $a[$row]['locdata'][$j]['product_id']=$arr1[$j]['product_id'];
                                    $a[$row]['locdata'][$j]['productname']=$arr1[$j]['productname'];

                        }
                  return array(

                                'offhie'=> $json,
                                'offloc1'=>$a

                  );
       }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
      }

    }
    public function businessdata_edit($teamid){
            //$this->db->initialize();
            $json=array();
            $a=array();
            $c=array();

       try{

                $query = $GLOBALS['$dbFramework']->query("call edit_tree('business_location','team_page','$teamid','Editbuss');");
                  if($query->num_rows()>0){
                      $json=$query->result();
                  }
                //return $json;
                /* -------------- saved products and currency ------------------------------- */
                  $row=0;
                  $query1=$GLOBALS['$dbFramework']->query("select distinct a.product_id,(select distinct hvalue2 from hierarchy where hkey2=a.product_id) as productname
                                                   from product_currency_mapping a where a.team_id='".$teamid."' and a.remarks='busloc' ");
                        $arr1=$query1->result_array();
                        for($j=0;$j<count($arr1);$j++){
                                    $product_id=$arr1[$j]['product_id'];
                                    $a[$row]['busdata'][$j]['product_id']=$arr1[$j]['product_id'];
                                    $a[$row]['busdata'][$j]['productname']=$arr1[$j]['productname'];

                        }
                  return array(

                                'bushie'=> $json,
                                'busloc1'=>$a

                  );
         }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
      }
    }
    public function industrydata_edit($teamid){
            //$this->db->initialize();
            $json=array();
            $a=array();
            $c=array();

        try{

            $query = $GLOBALS['$dbFramework']->query("call edit_tree('industry','team_page','$teamid','Editind');");
            if($query->num_rows()>0){
                $json=$query->result();
            }

            //return $json;

             /* -------------- saved products and currency ------------------------------- */
            $row=0;
            $query1=$GLOBALS['$dbFramework']->query("select distinct a.product_id,(select distinct hvalue2 from hierarchy where hkey2=a.product_id) as productname
                                             from product_currency_mapping a where a.team_id='".$teamid."' and a.remarks='indus' ");
                  $arr1=$query1->result_array();
                  for($j=0;$j<count($arr1);$j++){
                              $product_id=$arr1[$j]['product_id'];
                              $a[$row]['indusdata'][$j]['product_id']=$arr1[$j]['product_id'];
                              $a[$row]['indusdata'][$j]['productname']=$arr1[$j]['productname'];

                  }
            return array(

                          'indushie'=> $json,
                          'indusloc1'=>$a

            );

         }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
      }
    }
    public function productdata_edit($teamid){
            //$this->db->initialize();
            $json=array();
            $a=array();
            $c=array();

       try{

            $query = $GLOBALS['$dbFramework']->query("call edit_tree('products','team_page','$teamid','Editprod');");
            if($query->num_rows()>0){
                $json=$query->result();
            }

            /* saved products and currency for selected team */
            $row=0;
            $query1=$GLOBALS['$dbFramework']->query("select distinct a.product_id,(select distinct hvalue2 from hierarchy where hkey2=a.product_id) as productname
                                             from product_currency_mapping a where a.team_id='".$teamid."' and a.remarks='product'and a.togglebit=1 ");
                  $arr1=$query1->result_array();
                  for($j=0;$j<count($arr1);$j++){
                              $product_id=$arr1[$j]['product_id'];
                              $a[$row]['procurdata'][$j]['product_id']=$arr1[$j]['product_id'];
                              $a[$row]['procurdata'][$j]['productname']=$arr1[$j]['productname'];

                              $query11=$GLOBALS['$dbFramework']->query("select  a.currency_id,(select currency_name from currency where a.currency_id=currency_id)as currencyname,
                                                        (select product_value from product_attributes where a.currency_id=currency_id and product_id=a.product_id)
                                                        as curvalue from product_currency_mapping a where a.product_id='".$product_id."' and a.team_id='".$teamid."' and a.togglebit=1 ");
                              $arr11=$query11->result_array();
                              for($k=0;$k<count($arr11);$k++){

                                    $a[$row]['procurdata'][$j]['curdata'][$k]['currency_id']=$arr11[$k]['currency_id'];
                                    $a[$row]['procurdata'][$j]['curdata'][$k]['currencyname']=$arr11[$k]['currencyname'];
                                    $a[$row]['procurdata'][$j]['curdata'][$k]['curvalue']=$arr11[$k]['curvalue'];

                              }
                  }

           return array(

                          'prohie'=> $json,
                          'procur'=>$a

            );

        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
      }
    }

    public function get_viewdata($teamid,$business_location_id,$industry_id){
           $a=array();
           $b=array();
           $c=array();
           $d=array();
        try{
            $row=0;
            $query1=$GLOBALS['$dbFramework']->query("select distinct a.product_id,(select distinct hvalue2 from hierarchy where hkey2=a.product_id) as productname,
                                            (select distinct hvalue1 from hierarchy where hkey2=a.product_id) as productname1
                                             from product_currency_mapping a where a.team_id='".$teamid."' and a.remarks='product'and a.togglebit=1 ");
            $arr1=$query1->result_array();
            for($j=0;$j<count($arr1);$j++){
                        $product_id=$arr1[$j]['product_id'];
                        $a[$row]['procurdata'][$j]['product_id']=$arr1[$j]['product_id'];
                        $a[$row]['procurdata'][$j]['productname']=$arr1[$j]['productname']."<b> (".$arr1[$j]['productname1'].")</b>";

                        $query11=$GLOBALS['$dbFramework']->query("select  a.currency_id,(select currency_name from currency where a.currency_id=currency_id)as currencyname,
                                                  (select product_value from product_attributes where a.currency_id=currency_id and product_id=a.product_id)
                                                  as curvalue from product_currency_mapping a where a.product_id='".$product_id."' and a.team_id='".$teamid."' and a.togglebit=1 ");
                        $arr11=$query11->result_array();
                        for($k=0;$k<count($arr11);$k++){

                              $a[$row]['procurdata'][$j]['curdata'][$k]['currency_id']=$arr11[$k]['currency_id'];
                              $a[$row]['procurdata'][$j]['curdata'][$k]['currencyname']=$arr11[$k]['currencyname'];
                              $a[$row]['procurdata'][$j]['curdata'][$k]['curvalue']=$arr11[$k]['curvalue'];

                        }
                        $row++;
            }
            $row=0;
            $query1=$GLOBALS['$dbFramework']->query("select distinct a.product_id,(select distinct hvalue2 from hierarchy where hkey2=a.product_id) as productname,
                                                 (select distinct hvalue1 from hierarchy where hkey2=a.product_id) as productname1
                                       from product_currency_mapping a where a.team_id='".$teamid."' and a.remarks='ofloc' ");
            $arr1=$query1->result_array();
            for($j=0;$j<count($arr1);$j++){
                        $product_id=$arr1[$j]['product_id'];
                        $b[$row]['locdata'][$j]['product_id']=$arr1[$j]['product_id'];
                        $b[$row]['locdata'][$j]['productname']=$arr1[$j]['productname']."<b> (".$arr1[$j]['productname1'].")</b>";
                        $row++;
            }

            $row=0;
            $query1=$GLOBALS['$dbFramework']->query("select distinct a.product_id,(select distinct hvalue2 from hierarchy where hkey2=a.product_id) as productname,
                                                 (select distinct hvalue1 from hierarchy where hkey2=a.product_id) as productname1
                                       from product_currency_mapping a where a.team_id='".$teamid."' and a.remarks='busloc' ");
            $arr1=$query1->result_array();
            for($j=0;$j<count($arr1);$j++){
                        $product_id=$arr1[$j]['product_id'];
                        $c[$row]['blocdata'][$j]['product_id']=$arr1[$j]['product_id'];
                        $c[$row]['blocdata'][$j]['productname']=$arr1[$j]['productname']."<b> (".$arr1[$j]['productname1'].")</b>";
                        $row++;
            }

            $row=0;
            $query1=$GLOBALS['$dbFramework']->query("select distinct a.product_id,(select distinct hvalue2 from hierarchy where hkey2=a.product_id) as productname,
                                                 (select distinct hvalue1 from hierarchy where hkey2=a.product_id) as productname1
                                       from product_currency_mapping a where a.team_id='".$teamid."' and a.remarks='indus' ");
            $arr1=$query1->result_array();
            for($j=0;$j<count($arr1);$j++){
                        $product_id=$arr1[$j]['product_id'];
                        $d[$row]['indusdata'][$j]['product_id']=$arr1[$j]['product_id'];
                        $d[$row]['indusdata'][$j]['productname']=$arr1[$j]['productname']."<b> (".$arr1[$j]['productname1'].")</b>";
                        $row++;
            }

            /*$row=0;
            $cnt=0;

                $query=$GLOBALS['$dbFramework']->query(" call get_tree_leafnode('business_location','".$business_location_id."'); ");
                if($query->num_rows()>0){
                     $arr2=$query->result_array();
                        for($j=0;$j<count($arr2);$j++){
                                  $hkey2=$arr2[$j]['nodeid'];
                                  $nodename=$arr2[$j]['nodename'];
                                  $nodename1=$arr2[$j]['nodename1'];
                                  $c[$row]['blocdata'][$cnt]['nodeid']=$hkey2;
                                  $c[$row]['blocdata'][$cnt]['nodename']=$nodename."(".$nodename1.")";
                                  $cnt=$cnt+1;
                                  $row++;
                        }
                }

                $cnt=0;
                $row=0;
                $query=$GLOBALS['$dbFramework']->query(" call get_tree_leafnode('industry','".$industry_id."'); ");
                if($query->num_rows()>0){
                     $arr2=$query->result_array();
                        for($j=0;$j<count($arr2);$j++){
                                  $hkey2=$arr2[$j]['nodeid'];
                                  $nodename=$arr2[$j]['nodename'];
                                  $nodename1=$arr2[$j]['nodename1'];
                                  $d[$row]['indusdata'][$cnt]['nodeid']=$hkey2;
                                  $d[$row]['indusdata'][$cnt]['nodename']=$nodename."(".$nodename1.")";
                                  $cnt=$cnt+1;
                                  $row++;
                        }
                }*/

              return array(
                          'procur'=>$a,
                          'offdata'=>$b,
                          'bldata'=>$c,
                          'indata'=>$d
                );

         }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
      }


    }

}

?>