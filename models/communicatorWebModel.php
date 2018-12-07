<?php
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('communicatorWebModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();

class communicatorWebModel extends CI_Model{

     public function __construct(){
        parent::__construct();
    }

     public function validate_data($name,$mobile,$email)
    {
        try{    //AND json_extract(filters,'$.endtime')='".$filters1->endtime."'
                $cnt=0;
                $query=$GLOBALS['$dbFramework']->query("SELECT * FROM contact_details WHERE json_extract(contact_number, '$.*') LIKE '%".$mobile."%'; ");
                $arr=$query->result_array();
                if($query->num_rows()==0)
                {
                   return 0;
                }
                else
                {
                   $arr1=array();
                   for($i=0;$i<count($arr);$i++)
                   {
                      $id=$arr[$i]['id'];
                      $contact_name=$arr[$i]['contact_name'];
                      //echo "hello".$contact_name;
                      $arr1=array(
                              'contact_id'=>$id,
                              'contact_name'=>$contact_name
                      );
                   }
                   return $arr1;
                }
            }
            catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
            }
    }
    public function Kyc_file_upload($file_data)
    {
        try{
            $flag=0;
            $files=$file_data['files'];
            $contact_id=$file_data['contactdetail_id'];
            $email=$file_data['login_email'];
            $login_name=$file_data['login_name'];
            $dirpath='./uploads/kyc/';
            $dirpath1='kyc/';
           // $dirpath = 'kyc/'; ;
           /*	if(!is_dir($dirpath))    {
        				mkdir($dirpath);
        	}*/
            $upload['upload_path'] 	= $dirpath;
        	$upload['allowed_types']= 'gif|jpg|jpeg|png|bmp|doc|docx|pdf';
        	$upload['overwrite'] 	= true;
        	$upload['max_size'] 	= 2048;

        	$count = count($files['userfile']['name']);
        	$errors = array();
        	$docsData = array();
        	$this->load->library('upload');
            $dt=date('ymdHis');
            $messageid = uniqid(date('ymdHis'));
        	for($i = 0; $i < $count; $i++)
            {
              $_FILES['userfile']['name']=$filename= $messageid."_".$files['userfile']['name'][$i];
              $_FILES['userfile']['type']=$filetype= $files['userfile']['type'][$i];
              $_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'][$i];
              $_FILES['userfile']['error'] 	= $files['userfile']['error'][$i];
              $_FILES['userfile']['size']=$filesize= $files['userfile']['size'][$i];
                          if ($_FILES['userfile']['error'] == 4) {
               continue;
              }
              $this->upload->initialize($upload);
              if (!$this->upload->do_upload()){
                        $error = array('error' => $this->upload->display_errors(),
                                 'name' => $_FILES['userfile']['name']);
                        array_push($errors, $error);
              }
              else
              {
      			    if($flag==0)
                      {
                           // echo"Insert into support_group_emails(message_id,TYPE,contact_id,lead_cust_opp_id)
                                                            // SELECT $messageid,contact_for,contact_id,lead_cust_id FROM contact_details WHERE id='".$contact_id."'";
                            $query=$GLOBALS['$dbFramework']->query("Insert into support_group_emails(message_id,TYPE,contact_id,lead_cust_opp_id
                                                              ,from_name,mail_date,mail_associated_state,associated_user_id)
                                                             SELECT '".$messageid."',contact_for,contact_id,lead_cust_id,'".$login_name."','".$dt."','3',
                                                             customer_rep_owner
                                                             FROM contact_details a,customer_info b WHERE a.lead_cust_id=b.customer_id and a.id='".$contact_id."'");

                            $query=$GLOBALS['$dbFramework']->query("SELECT id FROM contact_details WHERE json_extract(contact_email, '$.email') LIKE '".$email."';");
                            if(!$query->num_rows())
                            {
                                $query=$GLOBALS['$dbFramework']->query("UPDATE contact_details SET contact_email=json_array_append(contact_email,'$.email','".$email."')
                                                                  WHERE id='".$contact_id."'");
                            }

                            $query=$GLOBALS['$dbFramework']->query("select  customer_rep_owner,customer_manager_owner
                                                             FROM contact_details a,customer_info b WHERE a.lead_cust_id=b.customer_id and a.id='".$contact_id."'");
                            if($query->num_rows())
                            {
                                $arr=$query->result_array();
                                $customer_rep_owner=$arr[0]['customer_rep_owner'];
                                $customer_manager_owner=$arr[0]['customer_manager_owner'];

                                $query=$GLOBALS['$dbFramework']->query("Insert into notifications(notificationID,notificationShortText,notificationText,notificationTimestamp,
                                                             to_user,action_details,read_state) values
                                                             ('".uniqid(rand())."','Documents Received from ".$login_name."','Documents Received from ".$login_name."','".date('ymdHis')."',
                                                              '".$customer_rep_owner."','document_upload',0)");
                                if($customer_rep_owner!=$customer_manager_owner)
                                {
                                    $query=$GLOBALS['$dbFramework']->query("Insert into notifications(notificationID,notificationShortText,notificationText,notificationTimestamp,
                                                             to_user,action_details,read_state) values
                                                             ('".uniqid(rand())."','Documents Received from ".$login_name."','Documents Received from ".$login_name."','".date('ymdHis')."',
                                                              '".$customer_manager_owner."','document_upload',0)");
                                }
                            }


                            /*$query=$GLOBALS['$dbFramework']->query("Insert into notifications(notificationID,notificationShortText,notificationText,notificationTimestamp,
                                                             to_user,action_details)
                                                             SELECT '".uniqid(rand())."','Documents Received from ".$login_name."','Documents Received from ".$login_name."','".date('ymdHis')."',
                                                             customer_rep_owner,'document_upload'
                                                             FROM contact_details a,customer_info b WHERE a.lead_cust_id=b.customer_id and a.id='".$contact_id."'");

                            $query=$GLOBALS['$dbFramework']->query("Insert into notifications(notificationID,notificationShortText,notificationText,notificationTimestamp,
                                                             to_user,action_details)
                                                             SELECT '".uniqid(rand())."','Documents Received from ".$login_name."','Documents Received from ".$login_name."','".date('ymdHis')."',
                                                             customer_manager_owner,'document_upload'
                                                             FROM contact_details a,customer_info b WHERE a.lead_cust_id=b.customer_id and a.id='".$contact_id."'");*/

                       }

                       $flag=1;
              }
              $document_path=$dirpath1.$filename;
                /*echo"Insert into support_group_email_attachments(message_id,mail_attachment_filename,mail_attachment_path,mail_attachment_type,mail_attachment_size)
                                                       values ('".$messageid."','".$filename."','".$document_path."','".$filetype."','".$filesize."')";*/
              $query=$GLOBALS['$dbFramework']->query("Insert into support_group_email_attachments(message_id,mail_attachment_filename,mail_attachment_path,
                                                        mail_attachment_type,mail_attachment_size)
                                                       values ('".$messageid."','".$filename."','".$document_path."','".$filetype."','".$filesize."')");

			} // end of for
                return array(
                         'errors_response'=>$errors,
                         'dataresponse'=>true
                    );

         }
         catch (LConnectApplicationException $e)
         {
                 $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                 throw $e;
         }
    }



}

?>