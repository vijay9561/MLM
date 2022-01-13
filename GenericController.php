<?php

class GenericController extends MY_Controller {

    var $current_date;
    var $current_timestamp;

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('date');
        $current_date = date('Y-m-d');
        $current_timestamp = date('Y-m-d h:m:s');
        $this->load->model("UserGenericModel");
        $this->load->model("GenericModel");
        $this->load->model("AdminGenericModel");
        $this->load->model("AdminGenericETHModel");
        $this->load->model("ETHLendingModel");
        $SITENAME = "Growese";
    }

    public function updateDuration() {
        echo "Tenure Updated successfully<br>Duration = " . $this->uri->segment(3) . " percent = " . $this->uri->segment(4);
        $this->GenericModel->getQueryResult("update kit set contract_duration=" . $this->uri->segment(3) . ', mining_percent=' . $this->uri->segment(4));
        $this->GenericModel->getQueryResult("update contract set contract_duration=" . $this->uri->segment(3) . ', mining_percent=' . $this->uri->segment(4));
    }

    public function bulkEmail($type, $message = false) {
        $query = "select distinct(emailid) from user where userid!=1";
        $array = $this->GenericModel->getQueryResult($query);
        foreach ($array as $v) {
            $subject = "Growese - Reminder";

            $messagebody = "Hi Miner, <br><br>" .
                    "<b>Gentle reminder</b><br>In association with Growese, " .
                    "We are coming up with new policy for mining contracts with respect to which criteria will be as follows<br><br><br>" .
                    "1. Mining contract Duration - 750days ( ~25 months) <br>" .
                    "2. Mining Output - 5.5% (per 30 days on contract signed) <br>" .
                    "3. Total Output on contract completion - 237% <br>" .
                    "(e.g. - For 1btc you will receive 2.37btc on contract completion that is 1.37 BTC mining output + 1 BTC capital)" .
                    "<br><br><b>This policy applicables from 1st April 2017.</b>" .
                    "<br><b>Our advice: If you are looking for short term investment so you should invest before 31st March 2017. Benefits for investing before 31st March you get 6.5% returns every month for next 18 months.</b>" .
                    "<br><br>Incase you have any query please contact us at support@Growese.com" .
                    "<br><br>_________________________________________________________<br><br>" .
                    "Regards, <br>Growese Team." .
                    "<br><br><i>Visit Us At <a href = 'http://www.growese.com'>www.growese.com</a></i>" .
                    "<br><br>For Any Help Contact on <span><a>support@growese.com</a></span>";
            $this->GenericModel->sendEmail($v['emailid'], "admin@growese.com", $subject, $messagebody, $successmsg, $errmsg);
        }
    }

    public function payoutcompletion() {
//load library phpExcel
        $this->load->library("PHPExcel");
//here i used microsoft excel 2007
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
//set to read only
        $objReader->setReadDataOnly(true);
//load excel file
        $filename = "payoutDec5.xlsx";
        $file = "D:\clientspace\hikecoin\$filename";
        $objPHPExcel = $objReader->load($file);
        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
//load model
        $this->load->model("GenericModel");
//loop from first data until last data
        $highestRow = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
        $datecreated = "2016-12-05";
        for ($i = 2; $i <= $highestRow; $i++) {

            $username = $objWorksheet->getCellByColumnAndRow(1, $i)->getValue();
            $transactionbtc = $objWorksheet->getCellByColumnAndRow(3, $i)->getValue();
            $walletaddresssentto = $objWorksheet->getCellByColumnAndRow(2, $i)->getValue();
            $userid = $objWorksheet->getCellByColumnAndRow(0, $i)->getValue();
            $txId = $objWorksheet->getCellByColumnAndRow(4, $i)->getValue();
            $data_payout = array(
                "datecreated" => $datecreated,
                "transactionbtc" => $transactionbtc,
                "walletaddresssentto" => $walletaddresssentto,
                "userid" => $userid,
                "txId" => $txId
            );
            $this->GenericModel->add_data($data_payout);
        }
    }

    /*     * *******************************DYNAMIC CONTRACT DURATION AND MINING PERCENTAGE********************************************* */

    public function cronjob() {
       // echo 'Hi';
       // exit;

        $d = date('Y-m-d h:i:s');
        $date = date_create($d);
        $da = date_sub($date, date_interval_create_from_date_string("1 days"));
        $miningdate = date_format($da, "Y-m-d h:i:s");
       // $miningdate = "2018-05-25 00:00:01";
        $record = $this->GenericModel->getQueryResult("select distinct contractid,contractprice,userid,contract_duration,mining_percent from miningcontract where DATE_FORMAT(signdate,'%Y-%m-%d')<DATE_FORMAT('" . $miningdate . "','%Y-%m-%d') and DATEDIFF(DATE_FORMAT(CURRENT_DATE,'%Y-%m-%d'),DATE_FORMAT(signdate,'%Y-%m-%d'))<=contract_duration and status='Active' and contractid not in (select contractid from miningop where DATE_FORMAT(miningdate,'%Y-%m-%d')=DATE_FORMAT('" . $miningdate . "','%Y-%m-%d')) order by userid");
        foreach ($record as $val) {
            //insert query to insert the mining op in the table
            $status = 0;
            $miningcalculated = ($val['contractprice'] * ($val['mining_percent'] / 100)) / 30;
            //   $insertquery = "insert into miningop (miningdate,miningop,contractid,userid) values ('" . $miningdate . "'," . $miningcalculated . "," . $val['contractid'] . "," . $val['userid'] . ")";
            $data = array(
                "miningdate" => $miningdate,
                "miningop" => $miningcalculated,
                "contractid" => $val['contractid'],
                "userid" => $val['userid'],
                "status" => 0
            );
            $this->db->insert("miningop", $data);
        }
        if ($record[0]['contractid'] != null) {
            $this->db->query("CALL bin_updateMiningOutput('$miningdate')");
            $r = $this->GenericModel->getQueryResult("select sum(miningop)'sum1' from miningop where miningdate ='" . $miningdate . "'");
            echo "Auto generated Mining Payout \n For Date " . $miningdate . "\n At Time " . $d . "\nBitcoins " . $r[0]['sum1'];
        } else {
            echo "Mining Payout \n For Date " . $miningdate . "\n Already Generated ";
        }
    }

    /*     * **************************************************************************************************************** */


    /*     * ******Fixed mining percent and duration
      public function cronjob() {
      $d = date('Y-m-d h:i:s');
      $date = date_create($d);
      $da = date_sub($date, date_interval_create_from_date_string("1 days"));
      $miningdate = date_format($da, "Y-m-d h:i:s");
      //$miningdate="2017-03-04 00:00:01";
      $record = $this->GenericModel->getQueryResult("select distinct contractid,contractprice,userid from miningcontract where DATE_FORMAT(signdate,'%Y-%m-%d')<DATE_FORMAT('" . $miningdate . "','%Y-%m-%d') and DATEDIFF(DATE_FORMAT(CURRENT_DATE,'%Y-%m-%d'),DATE_FORMAT(signdate,'%Y-%m-%d'))<=540 and contractid not in (select contractid from miningop where DATE_FORMAT(miningdate,'%Y-%m-%d')=DATE_FORMAT('" . $miningdate . "','%Y-%m-%d')) order by userid");
      foreach ($record as $val) {
      //insert query to insert the mining op in the table
      $status = 0;
      $miningcalculated = ($val['contractprice'] * 0.065) / 30;
      //   $insertquery = "insert into miningop (miningdate,miningop,contractid,userid) values ('" . $miningdate . "'," . $miningcalculated . "," . $val['contractid'] . "," . $val['userid'] . ")";
      $data = array(
      "miningdate" => $miningdate,
      "miningop" => $miningcalculated,
      "contractid" => $val['contractid'],
      "userid" => $val['userid'],
      "status" => 0
      );
      $this->db->insert("miningop", $data);
      }
      if ($record[0]['contractid'] != null) {
      $this->db->query("CALL bin_updateMiningOutput('$miningdate')");
      $r = $this->GenericModel->getQueryResult("select sum(miningop)'sum1' from miningop where miningdate ='" . $miningdate . "'");
      echo "Auto generated Mining Payout \n For Date " . $miningdate . "\n At Time " . $d . "\nBitcoins " . $r[0]['sum1'];
      } else {
      echo "Mining Payout \n For Date " . $miningdate . "\n Already Generated ";
      }
      } */

  /*  public function payoutcronjob() {
     //   echo 'Hi'; exit;
        $d = date('Y-m-d');
        $d1 = date('Y-m-d h:i:s');
        $date = date_create($d);
        $da = date_sub($date, date_interval_create_from_date_string("1 days"));
        $miningdate = date_format($da, "Y-m-d h:i:s");

        $deletedate = date_sub($date, date_interval_create_from_date_string("1 month"));
        $deletetodate = date_format($deletedate, "Y-m-d h:i:s");
        $deletetransdate = date_format($deletedate, "Y-m-d");

//Execute Binary
        if (date('d') == '1' || date('d') == '16') {
            $query = "call bin_updateBinaryIncome()";
            $this->db->query($query);
//Execute Payout To be Sent
            $query = "call payout_update_pending_payout()";
            $this->db->query($query);
            $query = "select * from cycle where CURRENT_DATE>=cyclestartdate and cycleendate>=CURRENT_DATE limit 1";
            $r = $this->GenericModel->getQueryResult($query);
            $currentcycle = 0;
            $currentcycle = $r[0]['cycleid'];
            $query = "select * from cycle where cycleid=" . ($currentcycle - 1);
            $r = $this->GenericModel->getQueryResult($query);
            $prevcyclestartdate = $r[0]['cyclestartdate'];
            $prevcycleenddate = $r[0]['cycleendate'];
//Execute miningop payout completion

            $query = "update miningop set status=1 where miningdate>='" . $prevcyclestartdate . "' and miningdate<='" . $prevcycleenddate . "'";
            $r = $this->db->query($query);
            $query1 = "update lendingop set status=1 where lendingdate>='" . $prevcyclestartdate . "' and lendingdate<='" . $prevcycleenddate . "'";
            $r = $this->db->query($query1);

            $query12= "update lendingop_eth set status=1 where lendingdate>='" . $prevcyclestartdate . "' and lendingdate<='" . $prevcycleenddate . "'";
            $r = $this->db->query($query12);

            echo "Payout Generated Successfully till " . $d1;
//Alert to all account which donot have wallet address updated
            $query = "select username,emailid from user where userwalletaddress=null or userwalletaddress=''";
            $r = $this->GenericModel->getQueryResult($query);
            

            // $query2 = "delete from miningop where miningdate <='" . $deletetodate . "'";
            // $r = $this->db->query($query2);
            // $query3 = "delete from lendingop where lendingdate <='" . $deletetodate . "'";
            // $r = $this->db->query($query3);
            // $query4 = "delete from mlm_transaction where trans_date <='" . $deletetransdate . "'";
            // $r = $this->db->query($query4);
        } else
            echo "Payout Cannot Be Generated Till 1st or 16th of the month\n Tried to Access At " . $d1;
    }*/
    public function payoutcronjob() {
        
        $d = date('Y-m-d');
        $d1 = date('Y-m-d h:i:s');
        $date = date_create($d);
        $da = date_sub($date, date_interval_create_from_date_string("1 days"));
        $miningdate = date_format($da, "Y-m-d h:i:s");

        $deletedate = date_sub($date, date_interval_create_from_date_string("1 month"));
        $deletetodate = date_format($deletedate, "Y-m-d h:i:s");
        $deletetransdate = date_format($deletedate, "Y-m-d");

//Execute Binary
        if (date('d') == '01' || date('d') == '16') {
            $query = "call bin_updateBinaryIncome()";
            $this->db->query($query);
//Execute Payout To be Sent
            $query = "call payout_update_pending_payout()";
            $this->db->query($query);

            $query = "select * from cycle where CURRENT_DATE>=cyclestartdate and cycleendate>=CURRENT_DATE limit 1";
            $r = $this->GenericModel->getQueryResult($query);
           // echo $this->db->last_query();
            //exit; 
            $currentcycle = 0;
            $currentcycle = $r[0]['cycleid'];
            $query = "select * from cycle where cycleid=" . ($currentcycle - 1);
            $r = $this->GenericModel->getQueryResult($query);
            $prevcyclestartdate = $r[0]['cyclestartdate'];
            $prevcycleenddate = $r[0]['cycleendate'];
//Execute miningop payout completion
            echo "Payout Generated Successfully till " . $d1;
//Alert to all account which donot have wallet address updated
            $query = "select username,emailid from user where userwalletaddress=null or userwalletaddress=''";
            $r = $this->GenericModel->getQueryResult($query);
            

            // $query2 = "delete from miningop where miningdate <='" . $deletetodate . "'";
            // $r = $this->db->query($query2);
            // $query3 = "delete from lendingop where lendingdate <='" . $deletetodate . "'";
            // $r = $this->db->query($query3);
            // $query4 = "delete from mlm_transaction where trans_date <='" . $deletetransdate . "'";
            // $r = $this->db->query($query4);
        } else
            echo "Payout Cannot Be Generated Till 1st or 16th of the month\n Tried to Access At " . $d1;
    }

    public function index() {
        $data['template'] = 'home';
        $data['title'] = 'Home';
        $this->front_layout($data);
		if((isset($_GET['user'])) && (isset($_GET['trak']))){
	      $username=base64_decode($_GET['user']);
		  $userid=base64_decode($_GET['trak']);
		  $template_id=base64_decode($_GET['promotional_id']);
		  $date=date('Y-m-d');
	$duplication=$this->db->query("select userid,username,template_id from promotinal_email_traking where userid='$userid' and template_id='$template_id' and date='$date'")->result();
	if(count($duplication)==0){
	     $insert_data=array('userid'=>$userid,'username'=>$username,'template_id'=>$template_id,'date'=>$date);
		 $this->db->insert('promotinal_email_traking',$insert_data);
	       }	    
	    }
    }

    public function about() {
        $data['template'] = 'about';
        $data['title'] = 'About';
        $this->front_layout($data);
    }

    public function tandc() {
        $data['template'] = 'tandc';
        $data['title'] = 'TermsNConditions';
        $this->admin_layout($data);
    }
    
    public function tandcfront() {
        $data['template'] = 'tandc';
        $data['title'] = 'TermsNConditions';
        $this->front_layout($data);
    }

    public function errorpage() {
        $data['template'] = 'errorpage';
        $data['title'] = 'Error Page';
        $this->front_layout($data);
    }

    public function pagenotfound() {
        $data['template'] = 'pagenotfound';
        $data['title'] = 'Page Not Found';
        $this->front_layout($data);
    }

   /* public function sendEnquiry() {
        $info = $this->input->post();
        $r = $this->GenericModel->sendEmail("support@Growese.com", $info['email'], $info['subject'], $info['message']);
        if ($r['status'])
            redirect(base_url() . '#section-9');
        else
            redirect(base_url() . 'errorpage');
    }
*/
//To check if value exist in database
    public function checkIfExist() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        // echo '<pre>';print_r($request);print_r('here');die();
        $data = array(
            "tablename" => $request->tablename,
            "data" => array(
                $request->fieldname => $request->value
            )
        );
        $response = $this->GenericModel->checkIfExist($data);
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function checkIfExistReturnId() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $data = array(
            "tablename" => $request->tablename,
            "data" => array(
                $request->fieldname => $request->value
            ),
            "select" => $request->select
        );
        $response = $this->GenericModel->checkIfExistReturnId($data);
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    // public function LoggingIn() {
    //     $postdata = file_get_contents("php://input");
    //     $info = json_decode($postdata);
    //     $data = array(
    //         "select" => "userid,emailid,username,refuser,contactno,userwalletaddress,currency,btcmonk_ci,userwalletaddress",
    //         "tablename" => "user",
    //         "where" => array(
    //             "username" => $info->username,
    //             "password" => md5($info->password),
    //             "status" => 'Active',
    //         )
    //     );

    //     $response = $this->GenericModel->getSingleRecordlogin($data);
    //     if ($response['status'] == 200) {
    //         $this->session->set_userdata($response['data']);
    //         $response = array(
    //             "status" => 200,
    //             "msg" => "Successfully Logged In Redirecting...."
    //         );
    //     } else {
    //         $response = array(
    //             "status" => 400,
    //             "msg" => "Login Failed! Enter Correct Login Details"
    //         );
    //     }
    //     $this->output->set_content_type('application/json')->set_output(json_encode($response));
    // }

    public function LoggingIn() {
       
        $postdata = file_get_contents("php://input");
        $info = json_decode($postdata);
        $data = array(
            "select" => "userid,emailid,username,refuser,contactno,userwalletaddress,currency,btcmonk_ci,userwalletaddress",
            "tablename" => "user",
            "where" => array(
                "username" => $info->username,
                "password" => md5($info->password),
                "status" => 'Active',
            )
        );
        $response = $this->GenericModel->getSingleRecordlogin($data);
        if ($response['status'] == 200) {
            
            $session_data['userids']=$response['data']['userid']; 
            $session_data['emailids']=$response['data']['emailid']; 
            $session_data['usernames']=$response['data']['username']; 
            $session_data['refusers']=$response['data']['userid'];
            $session_data['contactnos']=$response['data']['contactno'];
            $session_data['userwalletaddresss']=$response['data']['userwalletaddress'];
            $session_data['btcmonk_cis']=$response['data']['btcmonk_ci'];
            $session_data['currencys']=$response['data']['currency'];
            $session_data['currencysymbol']='$';
            $this->session->set_userdata($session_data);
             $base_64 = base64_encode($response['data']['userid']);
             $userid = rtrim($base_64, '=');
            $response = array(
                "status" => 200,
                "msg" => "Successfully Logged In Redirecting....",
                 "userid" => $userid,
            );
        } else {
            $response = array(
                "status" => 400,
                "msg" => "Login Failed! Enter Correct Login Details"
            );
        }
         
       $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function ChangeCredential() {
        $postdata = file_get_contents("php://input");
        $info = json_decode($postdata);
        $data = array(
            "select" => "userid",
            "tablename" => "user",
            "where" => array(
                "userid" => $this->session->userdata("userids"),
                "password" => md5($info->password),
            )
        );
        $response = $this->GenericModel->getSingleRecord($data);
        if ($response['status'] == 200) {
            $response = array(
                "status" => 200,
            );
        } else {
            $response = array(
                "status" => 400,
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function signup() {

        $this->db->trans_start();
        $info = $this->input->post();
        $ifexist = $this->db->select('username')->from('user')->where('username', $info['username'])->get()->row();
        if ($ifexist) {
            $this->session->set_flashdata('sucessmessage', 'Username already exist');
            redirect(base_url() . "Login");
        }
        $data = array(
            "select" => "username,userid",
            "tablename" => "user",
            "where" => array(
                "username" => $info['referralname']
            )
        );
        $userinfo = $this->GenericModel->getSingleRecord($data);
        $pswd = $this->GenericModel->generate_random_password();
        $data = array(
            "tablename" => "user",
            "data" => array(
                "emailid" => $info['emailid'],
                "username" => $info['username'],
                "password" => md5($pswd),
                "my_sponsar_sys_id" => $userinfo['data']['username'],
                "parent_node_id" => $userinfo['data']['userid'],
//                "my_sponsar_sys_id" => $this->session->userdata('username'),
//                "parent_node_id" => $this->session->userdata('userids'),
                "node_flag" => 1,
//                 "referral_id" => $this->session->userdata('userids'),
//                "refuser" => $this->session->userdata('userids'),
                "referral_id" => $userinfo['data']['userid'],
                "refuser" => $userinfo['data']['userid'],
                "contactno" => $info['contactno'],
                "userwalletaddress" => $info['userwalletaddress'],
                "btcmonk_ci" => $info['btcmonk_ci'],
                "currency" => $info['currency'],
                "datecreated" => date('Y-m-d h:m:s')
            )
        );

        $this->AdminGenericModel->insertRecord($data);
        $d['select'] = "userid";
        $d['tablename'] = $data['tablename'];
        $d['where'] = array("username" => $info['username']);
        $r = $this->AdminGenericModel->getSingleRecord($d);
        $id = $r['userid'];
        $refid = $userinfo['data']['userid'];
        $pos = $info['position'];
        $accdata = array(
            "tablename" => "account",
            "data" => array(
                "userid" => $id,
                "nodeweight" => $info['username'],
                "accstatus" => 'Inactive'
            )
        );
        $this->AdminGenericModel->insertRecord($accdata);

            $lending_balance='0.00';
            $btc_balance='0.00000000';
            $cap_inr_balance='0.00';
            $cap_btc_balance='0.00000000';
            $users_balances=array('userid'=>$id,'lending_balance'=>$lending_balance,'btc_balance'=>$btc_balance,'cap_inr_balance'=>$cap_inr_balance,'cap_btc_balance'=>$cap_btc_balance);
            $this->db->insert('user_balances',$users_balances);
            

        $this->db->query("CALL reg_addRegister('$id', '$refid', '$pos')");
        $subject = "Growese - Registration Confirmation Mail";
      /*  $messagebody = "Hi " . $info['username'] . ",<br><br>";
        $messagebody = "Thankyou for Registering with Growese.<br>"
                . "We Welcome You To Achive Immense Gain with Mining BTC.<br>"
                . "Your Credential <br><br>"
                . "Username : " . $info['username'] .
                "<br>Password : " . $pswd;
        $messagebody.="<br><br>_________________________________________________________<br><br>";
        $messagebody.="Thankyou & Regards,<br><br>";
        $messagebody.="Growese Team.";*/
			$messagebody='<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--[if !mso]><!-->
<!--<![endif]-->
<title></title>
<link href="https://fonts.googleapis.com/css?family=Droid+Serif" rel="stylesheet" type="text/css">
</head><body style="background-color:#edeff0">
<table class="nl-container" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;min-width: 320px;Margin: 0 auto;background-color: #edeff0;width: 100%; padding-top:20px; padding-bottom:20px;" cellpadding="0" cellspacing="0">
  <tbody>
    <tr style="vertical-align: top">
      <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top; padding-top: 20px;padding-bottom: 20px;"><!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="background-color: #FADDBB;"><![endif]-->
        <div style="background-color:transparent;">
          <div style="Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #FFFFFF;" class="block-grid ">
            <div style="border-collapse: collapse;display: table;width: 100%;background-color:#FFFFFF;">
              <div class="col num12" style="min-width: 320px;max-width: 600px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;">
                  <!--[if (!mso)&(!IE)]><!-->
                  <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:0px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                    <!--<![endif]-->
                    <div align="center" class="img-container center  autowidth  fullwidth " style="padding-right: 0px;  padding-left: 0px;background-color: #2b2a2a;">
                      <div style="line-height:15px;font-size:1px">&#160;</div>
                      <img class="center  autowidth  fullwidth" align="center" border="0" src="https://www.growese.com/assets/images/hickcoins-logo.png" alt="Image" title="Image" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: 0;float: none;width: 320px;max-width: 600px" width="600">
                      <div style="line-height:15px;font-size:1px">&#160;</div>
                      <!--[if mso]></td></tr></table><![endif]-->
                    </div>
                    <!--[if (!mso)&(!IE)]><!-->
                  </div>
                  <!--<![endif]-->
                </div>
              </div>
              <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
            </div>
          </div>
        </div>
        <div style="background-color:transparent;">
          <div style="Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #FFFFFF;" class="block-grid ">
            <div style="border-collapse: collapse;display: table;width: 100%;background-color:#FFFFFF;">
              <div class="col num12" style="min-width: 320px;max-width: 600px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;">
                  <!--[if (!mso)&(!IE)]><!-->
                  <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                    <!--<![endif]-->
                    <div class="">
                      <div style="line-height:120%;color:#f39229;font-family:Droid Serif, Georgia, Times, Times New Roman, serif; padding-right: 10px; padding-left: 10px; padding-top: 0px;
padding-bottom: 3px;">
                        <div style="font-size:12px;line-height:14px;font-family:Droid Serif, Georgia, Times, Times New Romanserif;color:#f39229;text-align:left;">
                          <p style="margin: 0;font-size: 14px;line-height: 17px;text-align: center"><span style="font-size: 36px; line-height:20px;"><strong><span style="line-height:20px; font-size:22px;">WELCOME TO GROWESE</span></strong></span></p>
                        </div>
                      </div>
                      <!--[if mso]></td></tr></table><![endif]-->
                    </div>
                    <div >
                    </div>
                    <div>
             <div style="color:#555555;font-family:Droid SerifGeorgia, Times,Times New Roman,serif; padding-right: 10px; padding-left: 10px; padding-top: 10px; margin-top:-50px;">
                        <div style="font-size:12px;line-height:24px;color:#555555;font-family:Droid SerifGeorgia, Times,Times New Roman,serif;text-align:left;">
                          <span style="font-size:16px; line-height: 32px; margin-left:20px;">
						    <p style="font-size: 14px;text-align:left;"><strong><span style="font-size:18px;">Hello '. $info['username'].',</span></strong></p>
                          <p style="color: #000000; text-align:justify; font-size:14px;">Thank you for registering with Growese. We welcome you to achieve immense gain with  Lending your BTC.<br />Your following credential details are - <br />
						  <p style="border: 1px solid #f39229;text-align: center;color: #fffafa;background-color: #121212;box-shadow: 0px 0px 3px 4px #f39229;font-size: 18px;
padding-top: 10px;
padding-bottom: 10px;"><b>Username:</b> '. $info['username'].'<br /><b>Password:</b> '.$pswd.'</p></p>
                        </div>
                      </div>
                    
                    </div>
              
					<div style="margin-left:10px; margin-bottom:20px;"><strong style="font-size:16px;font-style: italic;">Thanks & Regards</strong><br />
					<span style="padding-top:20px;  margin-top:10px;">Growese Team</span><br />
					<span style="padding-top:20px;  margin-top:10px;">Visit us at&nbsp;&nbsp;<a target="_blank" href="http://www.growese.com"> www.growese.com</a></span>
					</div>
                    <div style="background-color: #4e4646;">
                      <div class="">
                     
                        <div style="line-height:120%;color:#F99495;font-family:Droid SerifGeorgia, Times,Times New Roman,serif; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 25px;">
                          <div style="font-size:12px;line-height:14px;color:#fff;font-family:Droid SerifGeorgia, Times,Times New Roman,serif;text-align:left;">
                            <p style="margin: 0;font-size:18px;line-height: 17px;text-align: center"><span style="font-size:14px; line-height: 13px;"> 
							Please do not reply to this email. Emails sent to this address will not be answered.
							<br />Note: If it wasn&#39;t you please immediately contact <a href="mailto:support@growese.com" style="color:#fab029;" target="_blank">support@growese.com</a>.
                              Once again, we thank you for using Growese.
                              </span>
                            </p>
                          </div>
                        </div>
                        <!--[if mso]></td></tr></table><![endif]-->
                      </div>
                      <div align="center" style="padding-right: 10px; padding-left: 10px; padding-bottom:10px;" class="">
                        <div style="line-height:10px;font-size:1px">&#160;</div>
                        <div style="display: table;">
                          <table align="left" border="0" cellspacing="0" cellpadding="0" tyle="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;Margin-right: 5px">
                            <tbody>
                              <tr style="vertical-align: top">
                                <td align="left" valign="middle">
								<a href="https://www.facebook.com/growese/" style="color: #4e4646;" title="Facebook" target="_blank"> <img src="https://app-rsrc.getbee.io/public/resources/social-networks-icon-sets/circle-color/facebook@2x.png" alt="Facebook" title="Facebook" width="32" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;border: none;float: none;max-width: 32px !important"> </a> &nbsp;&nbsp;
								<a href="https://twitter.com/growese" style="color: #4e4646;" title="Twitter" target="_blank"> <img src="https://app-rsrc.getbee.io/public/resources/social-networks-icon-sets/circle-color/twitter@2x.png" alt="Twitter" title="Twitter" width="32" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;border: none;float: none;max-width: 32px !important"> </a>&nbsp;&nbsp;
								<a style="color: #4e4646;" href="https://t.me/growese" title="Telegram" target="_blank"> <img src="https://app-rsrc.getbee.io/public/resources/social-networks-icon-sets/circle-color/telegram@2x.png" alt="Telegram" title="Telegram" width="32" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;border: none;float: none;max-width:32px !important"> </a>
                                </td>
							
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>
</body>
</html>';
        $successmsg = "Email Has been Sent to " . $data['data']['emailid'] . " Successfully. <br> Check Your email to complete Registration";
        $errmsg = "Sorry Couldnt Register with " . $data['data']['emailid'];
        $response = array();
        $response = $this->GenericModel->sendEmail($info['emailid'], "noreply@growese.com", $subject, $messagebody, $successmsg, $errmsg);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('message', 'Fail, Please Try Again');
            redirect('Logout');
        } else {
              $this->session->set_flashdata('sucessmessage', 'Registration Successfull');
            $this->db->trans_commit();
          
            redirect('Logout');
        }

        // $this->Login($response);
    }

    public function Login($response) {
        $this->load->view("login", $response);
    }

    public function explorenow() {

        $this->load->view("user/btc_info");
    }

    public function loginhike() {
        if(isset($_GET['request'])){
			// echo 'hell';
		 $rquest_id=$_GET['request'];
		 $google_auth_code='';
		 $gauthstatus='';
		 $userid=base64_decode($rquest_id);
         $this->db->query("update user set google_auth_code='$google_auth_code',gauthstatus='$gauthstatus' where userid='$userid'");
        }

        if ($this->session->userdata('userids') == '') {
            //echo "usernahi";
            //exit;
            $this->session->sess_destroy();
            $this->load->view("login");
        } else {

            if ($this->session->flashdata('sucessmessage')) {
                $this->session->set_flashdata('sucessmessage', 'New User Registration Successfully');
            } else {
                $this->session->set_flashdata('message', 'Fail, Please Try Again');
            }
            redirect(base_url() . "Revenue");
        }
    }

    public function session_logout() {
        //$this->session->sess_destroy();
        if ($this->session->flashdata('sucessmessage')) {
            $this->session->set_flashdata('sucessmessage', 'Registration Successfull ! Please check your email for password');
        } else {
            $this->session->set_flashdata('message', 'Fail, Please Try Again');
        }
        redirect(base_url() . "Login");
    }

    public function recoverPassword() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $data = array(
            "select" => "userid,emailid",
            "tablename" => "user",
            "where" => array(
                "username" => $request->username,
            )
        );
        $response = $this->GenericModel->getSingleRecord($data);
        if (isset($response['status']) && $response['status'] == 200) {
            $request->userid = $response['data']['userid'];
            $request->timestamped = time();
            $pswd = $this->GenericModel->generate_random_password();
            $request->password = $pswd;
            $link = base_url() . "gmcuwidtl/" . base64_encode($this->GenericModel->mc_encrypt($request, ENCRYPTION_KEY));
            $link = str_replace('=', '', $link);
           $subject = "Growese - Reset Password Request";
           /* $messagebody = "You have made a Request To Recover Password<br>"
                    . "Your New Credential are :"
                    . "<br>Username : " . $request->username
                    . "<br>Password : " . $pswd
                    . "<br>To Confirm Password Reset <a href=" . $link . ">Click Here</a><br><br>Note:<br><i> Incase this request is not made by you please report to Growese at support@Growese.com</i>";
            $messagebody.="<br><br>_________________________________________________________<br><br>";
            $messagebody.="Regards,<br>Growese Team.";
            $messagebody.="<br><br><i>Visit Us At <a href='http://www.Growese.com'>www.Growese.com</a></i>";*/
			
$messagebody='<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--[if !mso]><!-->
<!--<![endif]-->
<title></title>
<link href="https://fonts.googleapis.com/css?family=Droid+Serif" rel="stylesheet" type="text/css">
</head><body style="background-color:#edeff0">
<table class="nl-container" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;min-width: 320px;Margin: 0 auto;background-color: #edeff0;width: 100%; padding-top:20px; padding-bottom:20px;" cellpadding="0" cellspacing="0">
  <tbody>
    <tr style="vertical-align: top">
      <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top; padding-top: 20px;padding-bottom: 20px;"><!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="background-color: #FADDBB;"><![endif]-->
        <div style="background-color:transparent;">
          <div style="Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #FFFFFF;" class="block-grid ">
            <div style="border-collapse: collapse;display: table;width: 100%;background-color:#FFFFFF;">
              <div class="col num12" style="min-width: 320px;max-width: 600px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;">
                  <!--[if (!mso)&(!IE)]><!-->
                  <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:0px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                    <!--<![endif]-->
                    <div align="center" class="img-container center  autowidth  fullwidth " style="padding-right: 0px;  padding-left: 0px;background-color: #2b2a2a;">
                      <div style="line-height:15px;font-size:1px">&#160;</div>
                      <img class="center  autowidth  fullwidth" align="center" border="0" src="https://www.growese.com/assets/images/hickcoins-logo.png" alt="Image" title="Image" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: 0;float: none;width: 320px;max-width: 600px" width="600">
                      <div style="line-height:15px;font-size:1px">&#160;</div>
                      <!--[if mso]></td></tr></table><![endif]-->
                    </div>
                    <!--[if (!mso)&(!IE)]><!-->
                  </div>
                  <!--<![endif]-->
                </div>
              </div>
              <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
            </div>
          </div>
        </div>
        <div style="background-color:transparent;">
          <div style="Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #FFFFFF;" class="block-grid ">
            <div style="border-collapse: collapse;display: table;width: 100%;background-color:#FFFFFF;">
              <div class="col num12" style="min-width: 320px;max-width: 600px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;">
                  <!--[if (!mso)&(!IE)]><!-->
                  <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                    <!--<![endif]-->
                    <div class="">
             <div style="line-height:120%;color:#f39229;font-family:Droid Serif, Georgia, Times, Times New Roman, serif; padding-right: 10px; padding-left: 10px; padding-top: 0px;
padding-bottom: 3px;">
                        <div style="font-size:12px;line-height:14px;font-family:Droid Serif, Georgia, Times, Times New Romanserif;color:#f39229;text-align:left;">
                          <p style="margin: 0;font-size: 14px;line-height: 17px;text-align: center"><span style="font-size: 36px; line-height:20px;"><strong><span style="line-height:20px; font-size:22px;">GROWESE RESET PASSWORD</span></strong></span></p>
                        </div>
                      </div>
                      <!--[if mso]></td></tr></table><![endif]-->
                    </div>
                    <div >
                    </div>
                    <div>
             <div style="color:#555555;font-family:Droid SerifGeorgia, Times,Times New Roman,serif; padding-right: 10px; padding-left: 10px; padding-top: 10px; margin-top:-50px;">
                        <div style="font-size:12px;line-height:24px;color:#555555;font-family:Droid SerifGeorgia, Times,Times New Roman,serif;text-align:left;">
                          <span style="font-size:16px; line-height:19px; margin-left:20px;">
						    <p style="font-size: 14px;text-align:left;"><strong><span style="font-size:18px;">Hi '.$request->username.',</span></strong></p>
							<p style="font-size:16px;border:1px solid #000000;padding:10px;color: #000000;text-align: center;"><strong style="color:#000000;">Your new requested Password: <span style="color: green;font-size: 20px;">'.$pswd.'</span> </strong></p>
                          <p style="color: #000000; text-align:justify; font-size:16px;">Growese has received a request to reset the password for your account. If you did not requested to reset your password, please ignore this email.</p>
						  <div style="text-align: center;margin-top: 32px;">
		<a href="'.$link.'" style="background-color: #f39229;padding: 10px;border-radius: 6px;text-decoration: none;color: white;font-size: 20px;">Click Here To Reset Password</a>
						  </div>
                        </div>
                      </div>
                    </div>
              <br /><br />
					<div style="margin-left:10px; margin-bottom:10px;"><strong style="font-size:16px;font-style: italic;">Thanks & Regards</strong><br />
					<span style="padding-top:20px; margin-top:10px;">Growese Team</span><br />
					<span style="padding-top:20px; margin-top:10px;">Visit us at&nbsp;&nbsp;<a target="_blank" href="http://www.growese.com"> www.growese.com</a></span>
					</div>
                    <div style="background-color: #4e4646;">
                      <div class="">
                     
                        <div style="line-height:120%;color:#F99495;font-family:Droid SerifGeorgia, Times,Times New Roman,serif; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 25px;">
                          <div style="font-size:12px;line-height:14px;color:#fff;font-family:Droid SerifGeorgia, Times,Times New Roman,serif;text-align:left;">
                            <p style="margin: 0;font-size:18px;line-height: 17px;text-align: center"><span style="font-size:14px; line-height: 13px;"> 
							Please do not reply to this email. Emails sent to this address will not be answered.
							<br />Note: If it wasn&#39;t you please immediately contact  <a href="mailto:support@growese.com" style="color:#fab029;" target="_blank">support@growese.com</a>.
                              Once again, we thank you for using Growese trusted products.
                              </span>
                            </p>
                          </div>
                        </div>
                        <!--[if mso]></td></tr></table><![endif]-->
                      </div>
                      <div align="center" style="padding-right: 10px; padding-left: 10px; padding-bottom:10px;" class="">
                        <div style="line-height:10px;font-size:1px">&#160;</div>
                        <div style="display: table;">
                          <table align="left" border="0" cellspacing="0" cellpadding="0" tyle="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;Margin-right: 5px">
                            <tbody>
                              <tr style="vertical-align: top">
                                <td align="left" valign="middle">
								<a href="https://www.facebook.com/hikecoins/" title="Facebook" target="_blank"> <img src="https://app-rsrc.getbee.io/public/resources/social-networks-icon-sets/circle-color/facebook@2x.png" alt="Facebook" title="Facebook" width="32" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;border: none;float: none;max-width: 32px !important"> </a> 
								<a href="https://twitter.com/hikecoin11" title="Twitter" target="_blank"> <img src="https://app-rsrc.getbee.io/public/resources/social-networks-icon-sets/circle-color/twitter@2x.png" alt="Twitter" title="Twitter" width="32" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;border: none;float: none;max-width: 32px !important"> </a> <a href="https://t.me/hikecoins" title="Telegram" target="_blank"> <img src="https://app-rsrc.getbee.io/public/resources/social-networks-icon-sets/circle-color/telegram@2x.png" alt="Telegram" title="Telegram" width="32" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;border: none;float: none;max-width:32px !important"> </a>
                                </td>
							
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>
</body>
</html>';
            $successmsg = "<span class='text-info'>Recovery Link has been Sent To The Registerd Email Id</span>";
            $errmsg = "Some Issue in the Profile Update. Please Raise a Ticket";
//            echo $link;
            $response = $this->GenericModel->sendEmail($response['data']['emailid'], "noreply@growese.com", $subject, $messagebody, $successmsg, $errmsg);
//            $this->output->set_content_type('application/json')->set_output(json_encode($response));
            echo 1;
            echo  $this->session->set_flashdata('sucessmessage', '<div class="alert alert-success">Recovery Link has been Sent To The Registerd Email Id  </div>');            exit();
            // $this->output->set_content_type('application/json')->set_output(json_encode($response));
        } else {
            $response = array(
                "status" => 400,
                "msg" => "Invalid Username"
            );
            echo 0;
            exit();
        }
    }
    public function cronjoblend() {
        $d = date('Y-m-d h:i:s');
        $date = date_create($d);
        $da = date_sub($date, date_interval_create_from_date_string("1 days"));
       $miningdate = date_format($da, "Y-m-d h:i:s");
       //$miningdate = "2018-05-25 05:36:01";
        // $record = $this->GenericModel->getQueryResult("select distinct lc.contractid,lt.amountusd,lc.userid,lc.contract_duration,lc.lending_percent from lendingcontract as lc,lendingtb as lt where lc.contractid=lt.purchaseid and DATE_FORMAT(lc.signdate,'%Y-%m-%d')<DATE_FORMAT('" . $miningdate . "','%Y-%m-%d') and DATEDIFF(DATE_FORMAT(CURRENT_DATE,'%Y-%m-%d'),DATE_FORMAT(lc.signdate,'%Y-%m-%d'))<=lc.contract_duration and lc.status='Active' and lc.contractid not in (select contractid from lendingop where DATE_FORMAT(lendingdate,'%Y-%m-%d')=DATE_FORMAT('" . $miningdate . "','%Y-%m-%d')) order by userid");
        $record =  $this->GenericModel->getQueryResult("select distinct lc.contractid,lt.amountusd,lc.userid,lc.contract_duration,lc.lending_percent,lc.status,u.username,lc.referral_percentage from lendingcontract lc inner join lendingtb lt on lt.purchaseid=lc.contractid inner join user u on u.userid=lc.userid where u.status='Active' and lc.status='Active' and lc.contractid=lt.purchaseid and DATE_FORMAT(lc.signdate,'%Y-%m-%d')<DATE_FORMAT('" . $miningdate . "','%Y-%m-%d') and DATEDIFF(DATE_FORMAT(CURRENT_DATE,'%Y-%m-%d'),DATE_FORMAT(lc.signdate,'%Y-%m-%d'))<=lc.contract_duration and lc.status='Active' and lc.contractid not in (select contractid from lendingop where DATE_FORMAT(lendingdate,'%Y-%m-%d')=DATE_FORMAT('" . $miningdate . "','%Y-%m-%d')) order by userid");
       
        foreach ($record as $val) {
            //insert query to insert the lending op in the table
            $status = 0;
            $lendingcalculated = ($val['amountusd'] * ($val['lending_percent'] / 100)) / 30;
            $data = array(
                "lendingdate" => $miningdate,
                "lendingop" => $lendingcalculated,
                "contractid" => $val['contractid'],
                "userid" => $val['userid'],
                "dates"=>date('Y-m-d'),
                "status" => 0
            );
            $this->db->insert("lendingop", $data);
            // referral %
              $refuser=$this->db->query("select refuser from user where userid='".$val['userid']."'")->row();
              $refer_lend_calculate='';
$referal_condition=$this->db->query("SELECT * FROM lendingcontract WHERE DATE(signdate)>='2018-04-15' AND DATE(signdate)<='2018-09-15' and contractid='".$val['contractid']."'")->result();
           /*  if(count($referal_condition)>=1){
              if(count($refuser)>=1){ // check the referral user exit or not
                $lending_referal_contract=$this->db->query("select status from lendingcontract where userid='".$refuser->refuser."' and status='Active'")->result();
        
                $referral_contract=count($lending_referal_contract);
               if($referral_contract>=1){ // check the referral user current at least one contract active condtion check
	     $refer_lend_calculate = (($val['amountusd'] * ($val['referral_percentage']/ 100)) / 30);
             $lending_history=array('userid'=>$refuser->refuser,'contract_id'=>$val['contractid'],'referral_username'=>$val['username'],'amount'=>$refer_lend_calculate,'created_date'=>date('Y-m-d'),'status'=>'unpaid');
             $this->db->insert('lending_history',$lending_history);
			 }
             }
}*/
            // end referral
        }
        if ($record[0]['contractid'] != null) {
            $this->db->query("CALL bin_updateLendingOutput('$miningdate')");
            $r = $this->GenericModel->getQueryResult("select sum(lendingop)'sum1' from lendingop where lendingdate ='" . $miningdate . "'");
            echo "Auto generated Lending Payout \n For Date " . $miningdate . "\n At Time " . $d . "\nBitcoins " . $r[0]['sum1'];
        } else {
            echo "Lending Payout \n For Date " . $miningdate . "\n Already Generated ";
        }
    }

    public function cronjobcurrencychanges() {
        if (date('d') == '14' || date('d') == '16') {
            $this->db->select('username,currency,currencytochange,currencyrequest.userid,currencyrequest.status');
            $this->db->from('currencyrequest');
            $this->db->join('user', 'user.userid=currencyrequest.userid', 'left');
            $this->db->where('currencyrequest.status', 'Pending');
            $usersrequest = $this->db->get()->result();
            foreach ($usersrequest as $res) {
                $uid = $res->userid;
                $from = $res->currency;
                $to = $res->currencytochange;

                $this->db->select('purchaseid,userid,amountusd,amountbtc');
                $this->db->from('lendingtb');
                $this->db->where('userid', $res->userid);
                $usercontracts = $this->db->get()->result();
                if (!empty($usercontracts)) {
                    foreach ($usercontracts as $contract) {
                        $purchaseid = $contract->purchaseid;
                        $amount = $contract->amountusd;
                        $amountbtc = $contract->amountbtc;
                        $changedcurencyamount = $this->convertCurrency($amount, $from, $to);
                        if (!empty($changedcurencyamount)) {
                            // transfer previous amount to bitcoin wallet
                            $lendingwallet = $this->AdminGenericModel->selectlendingwallet($uid);
                            if ($lendingwallet > 0 && !empty($lendingwallet)) {
                                $this->load->library('site');
                                 $response = $this->site->getbrokerage();
                                if ($curr == 'INR') {
                                    $res = 1;
                                } else {
                                    $res = $this->site->currencyconvertor('INR', $curr);
                                }
                                $onebtctocurrency = $response['buy'] * $res;
                                $currentprice = $onebtctocurrency;
                                $amteqibtc = $lendingwallet / $currentprice;
                                $transferdata = array(
                                    "tablename" => "lending_to_btc_transfer",
                                    "data" => array(
                                        "userid" => $uid,
                                        "usdamount" => $lendingwallet,
                                        "btcamount" => number_format($amteqibtc, 8, '.', ''),
                                        "currency" => $from,
                                        "datecreated" => date('Y-m-d h:m:s'),
                                        "status" => 1,
                                    )
                                );
                                //  echo '<pre>'; print_r($transferdata); die(); 
                                $result = $this->AdminGenericModel->insertRecord($transferdata);
                            }
                            //  echo '<pre>'; print_r($transferdata); die(); 
                            //  update lendingtb to convert currency
                            $this->db->where('amountusd', $amount);
                            $this->db->where('purchaseid', $purchaseid);
                            $this->db->where('userid', $uid);
                            $update = $this->db->update('lendingtb', array('amountusd' => $changedcurencyamount));
                            if ($update) {
                                // change user currency and set change status in currencyrequest
                                $this->db->where('userid', $uid);
                                $updateusercurrency = $this->db->update('user', array('currency' => $to));

                                $this->db->where('userid', $uid);
                                $updateusercurrency = $this->db->update('currencyrequest', array('status' => 'Changed'));
                            }
                        }
                    }
                } else {
                    // change user currency and set change status in currencyrequest
                    $this->db->where('userid', $uid);
                    $updateusercurrency = $this->db->update('user', array('currency' => $to));

                    $this->db->where('userid', $uid);
                    $updateusercurrency = $this->db->update('currencyrequest', array('status' => 'Changed'));
                }
            }
        }
    }

    public function convertCurrency($amount, $from, $to) {
        if($from == $to) {
         return $amount;
        }
       // $url = "http://api.fixer.io/latest?base=$from";
		//$url="https://data.fixer.io/api/latest?access_key=5ea152951f0f22a09c5ef9fa8f870458&base=$from";
		//$url='https://data.fixer.io/api/latest?access_key=5ea152951f0f22a09c5ef9fa8f870458&base=$from';
		/*$url="https://data.fixer.io/api/latest?access_key=5ea152951f0f22a09c5ef9fa8f870458&base=$from";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $result = curl_exec($curl);
        curl_close($curl);
        $json_a = json_decode($result, true);*/
		$get=$this->db->query("select inr_usd from brokerage where id=1")->row();
        return round(($amount * $get->inr_usd), 8);
        
    }

    public function oneusdtousrcurr() {
        $to = $this->session->userdata('currency');
        if($to == 'USD'){
            return 1;
        }
     /*   $url = "https://data.fixer.io/api/latest?access_key=5ea152951f0f22a09c5ef9fa8f870458&base=USD";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $result = curl_exec($curl);
        curl_close($curl);
        $json_a = json_decode($result, true);
        echo round($json_a['rates'][$to], 8);*/
		 $get=$this->db->query("select inr_usd from brokerage where id=1")->row();
        return round($get->inr_usd, 8);
        // $url = "https://finance.google.com/finance/converter?a=1&from=USD&to=$to";
        // $data = file_get_contents($url);
        // preg_match("/<span class=bld>(.*)<\/span>/", $data, $converted);
        // $converted = preg_replace("/[^0-9.]/", "", $converted[1]);
        // echo round($converted, 2);

        // $from = 'USD';
        // $to = $this->session->userdata('currency');
        // $conv_id = "{$from}_{$to}";
        // $url = "http://free.currencyconverterapi.com/api/v3/convert?q=$conv_id&compact=ultra";
        // $curl = curl_init();
        // curl_setopt($curl, CURLOPT_URL, $url);
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($curl, CURLOPT_HEADER, false);
        // $result = curl_exec($curl);
        // curl_close($curl);
        // $json_a = json_decode($result, true);
        // print_r($json_a );die();
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect(base_url() . 'Login');
    }

    /*send support mail for hikecoins users*/
    public function send_mail_to_support(){
    $username=trim($this->input->post('username'));
    $issue_type=trim($this->input->post('issue_type'));
    $issue_details=trim($this->input->post('issue_details'));
    $ci_no=trim($this->input->post('ci_no'));
    $checking_users=$this->db->query("select username,emailid,userid from user where username='$username' and btcmonk_ci='$ci_no'")->row();
    $subject="Growese Support Issue";
    if(count($checking_users)>=1){
       $date=date('Y-m-d H:i:s');
       $tickets_id = mt_rand(100000, 999999);
       $get_data=$this->db->query("select support_id from support_assign_issue where issue_type='$issue_type'")->row();
      $support_id=$get_data->support_id;
       $create_tickets_array=array('tickets_id'=>$tickets_id,'userid'=>$checking_users->userid,'username'=>$checking_users->username,'emailid'=>$checking_users->emailid,'issue_type'=>$issue_type,'issue_details'=>$issue_details,'tickets_status'=>'open','created_date'=>$date,'support_id'=>$support_id);
       
       $this->db->insert('support_tickets',$create_tickets_array);
      // $last_id=$this->db->insert_id();
           $notification_array=array('notification_details'=>$tickets_id.' '.$issue_type,'ticket_id'=>$tickets_id,'support_id'=>$support_id,'userid'=>'support team','status'=>'open','created_date'=>date('Y-m-d H:i:s'));
     $this->db->insert('support_notification',$notification_array);
        $messagebody = "Hi,  Growese TEAM<br><br>" .
                    "<table border='1' style='border-collapse: collapse;padding:10px;text-align: left;width:50%;'>
                    <tr><th style='width:150px;'>Username:</th><td>".$username.'</td></tr>
                    <tr><th style="width:150px;">CI NO:</th><td>'.$ci_no.'</td></tr>
                    <tr><th style="width:150px;">'.'Issue Type:</th><td>'.$issue_type.'</td></tr>
                     <tr><th style="width:150px;">Issue Details: </th><td>
                          '.$issue_details.'</td></tr></table>'."<br>" .
                    "Thanks & Regards,<br>Growese Team.".
                    "<br><i>Visit Us At <a href='http://www.growese.com'>www.growese.com</a></i>";
                    //$response = $this->GenericModel->sendEmail('hikecoinsweb@gmail.com', $checking_users->emailid, $subject, $messagebody);
        echo 1; // users name valid
        exit;
        }else{
        echo 0; // means username invalid    
        exit;
         }
     }

        public function setBrokerage(){
            $arrContextOptions = array(
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ),
            );
            
            $url="https://api.trademonk.com/api/v1/market";
           $ch = curl_init();
           curl_setopt($ch, CURLOPT_URL, $url);
           curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
           $data = curl_exec($ch);
           curl_close($ch);
           $datarate = json_decode($data, true);
           if ($datarate[41]['pair'] == 'TMNK-INR' && !empty($datarate[41]['sell'])) {
               $this->db->where('id',1);
               $result = $this->db->update('brokerage', array('buy' => $datarate[41]['sell']));
               echo 'Success Updated';
           }
         
           

        }

        // cron to update lending wallet balance for every payout 
    public function update_for_lending_payout_chronjob(){
        echo date('d').'Success Vijay';
        exit;
		//echo 'Hi';
		//exit;
			// lending 15 days payout add in lending wallet
            $days=date('d');
            if($days==02){
            //exit;
             $date =date('Y-m-d', strtotime('last day of last month'));
             $year=date('Y',strtotime($date));
             $month=date('m',strtotime($date));
             $last_days=cal_days_in_month(CAL_GREGORIAN, date($month), date($year));
             $month_mid_days= date("Y-m-d", mktime(0, 0, 0, $month, 16, $year));
             $month_last_days= date("Y-m-d", mktime(0, 0, 0, $month, $last_days, $year));
             $users=$this->db->query("SELECT DISTINCT userid FROM lendingcontract where userid between 2801 and 3000")->result();
			 $this->db->query("UPDATE lending_history SET status='paid' WHERE created_date>='".$month_mid_days."' AND created_date<='".$month_last_days."'");
               foreach($users as $row){
                      $uid=$row->userid;
                      $lendamount = 0;
                        // lending payout total
                        $this->db->select("SUM(debit_amount) AS lendingop");
                        $this->db->where('mlm_transaction.trans_date >=', $month_mid_days);
                        $this->db->where('mlm_transaction.trans_date <=', $month_last_days);
                        $this->db->where('mlm_transaction.userid', $uid);
                        $this->db->where('description', 'Lending Income');
                        $this->db->from('mlm_transaction');
                        $lendingtotal = $this->db->get()->result();
                        if (empty($lendingtotal[0]->lendingop)) {
                            $lendingtotal = 0.0;
                        } else {
                            $lendingtotal = $lendingtotal[0]->lendingop;
                        }
                    $dailypayout=$this->db->query("select SUM(amount) AS dailyamount from lending_history where (created_date>='".$month_mid_days."' and created_date<='".$month_last_days."') and userid='$uid'")->result();
                    $total_refer_amount='';
                    if(!empty($dailypayout[0]->dailyamount)){
                    $total_refer_amount=$dailypayout[0]->dailyamount;
                    }else{
                    $total_refer_amount='0.0';
                    }
                   $lendamount = $lendamount + $lendingtotal;
                   $updated_lend_balance = $lendamount+$total_refer_amount;
                   $date=date('Y-m-d');
                   $duplicate=$this->db->query("select *from lending_payout_history_btc where userid='".$uid."' and created_date='".$date."'")->result();
                   if(count($duplicate)==0){
                   $this->db->query("update user_balances set lending_balance = lending_balance + '$updated_lend_balance',modified_date1='".date('Y-m-d')."' where userid='$uid'");
                   $this->db->query("insert into lending_payout_history_btc(userid,btcamount,created_date)values('".$uid."','".$updated_lend_balance."','".date('Y-m-d')."')");
                   }
				//============================================Shooping Wallet Every Month 1st Days============================================//
				    /* $last_days =date('Y-m-d', strtotime('last day of last month'));
					  $first_days=date('Y-m-01',strtotime($last_days));
					 
					$this->db->select("SUM(debit_amount) AS lendingop");
                        $this->db->where('mlm_transaction.trans_date >=', $first_days);
                        $this->db->where('mlm_transaction.trans_date <=', $last_days);
                        $this->db->where('mlm_transaction.userid', $uid);
                        $this->db->where('description', 'Lending Income');
                        $this->db->from('mlm_transaction');
						
                         $lendingtotal = $this->db->get()->result();
						 
						
                        if (empty($lendingtotal[0]->lendingop)) {
                            $lendingtotal = 0.0;
                        } else {
							$lendingtotal = $lendingtotal[0]->lendingop;
                        }
					 
				$shopping_wallet = (($lendingtotal * (0.50/ 100)));
			
               if($shopping_wallet>2500)
                 { 
                $shopping_wallet=2500;
                 $credited_date=date('Y-m-d');   
               $this->db->query("update user_balances set recharge_inr_balance = recharge_inr_balance + '$shopping_wallet' where userid='$uid'");
               $this->db->query("insert into shopping_wallet_history(amount,actualamount,userid,credited_date)values('$shopping_wallet','$lendingtotal','$uid','$credited_date')");
                 }else{
                      $credited_date=date('Y-m-d');
                  
                $this->db->query("update user_balances set recharge_inr_balance = recharge_inr_balance + '$shopping_wallet' where userid='$uid'");
   $this->db->query("insert into shopping_wallet_history(amount,actualamount,userid,credited_date)values('$shopping_wallet','$lendingtotal','$uid','$credited_date')");
                 }
				 */
				//======================================End Code Shopping Wallet==============================================================//
              }
             }   else if ($days==16) {
			// echo 1;
			// exit;
             $date =date('Y-m-d', strtotime('first day of this month'));
             $year=date('Y',strtotime($date));
             $month=date('m',strtotime($date));
             $last_days=cal_days_in_month(CAL_GREGORIAN, date($month), date($year));
             $month_first_days= date("Y-m-d", mktime(0, 0, 0, $month, 01, $year));
             $month_mid_days= date("Y-m-d", mktime(0, 0, 0, $month,15, $year));
             $users=$this->db->query("SELECT DISTINCT userid FROM lendingcontract where userid between 1 and 200")->result();
			 $this->db->query("UPDATE lending_history SET status='paid' WHERE created_date>='".$month_first_days."' AND created_date<='".$month_mid_days."'");
             $i=1; foreach($users as $row){
                      $uid=$row->userid;
                    $lendamount = 0;
                        // lending payout total
                        $this->db->select("SUM(debit_amount) AS lendingop");
                        $this->db->where('mlm_transaction.trans_date >=', $month_first_days);
                        $this->db->where('mlm_transaction.trans_date <=', $month_mid_days);
                        $this->db->where('mlm_transaction.userid', $uid);
                        $this->db->where('description', 'Lending Income');
                        $this->db->from('mlm_transaction');
                        $lendingtotal = $this->db->get()->result();
                        if (empty($lendingtotal[0]->lendingop)) {
                            $lendingtotal = 0.0;
                        } else {
                            $lendingtotal = $lendingtotal[0]->lendingop;
                        }
                   $lendamount = $lendamount + $lendingtotal;
                   $dailypayout=$this->db->query("select SUM(amount) AS dailyamount from lending_history where (created_date>='".$month_first_days."' and created_date<='".$month_mid_days."') and userid='$uid'")->result();
                   $total_refer_amount='';
                    if(!empty($dailypayout[0]->dailyamount)){
                    $total_refer_amount=$dailypayout[0]->dailyamount;
                    }else{
                    $total_refer_amount='0.0';
                    }
                   $updated_lend_balance=$lendamount+$total_refer_amount;
                   $date=date('Y-m-d');
                   $duplicate=$this->db->query("select *from lending_payout_history_btc where userid='".$uid."' and created_date='".$date."'")->result();
                   if(count($duplicate)==0){
                   $this->db->query("update user_balances set lock_btc_balance=lock_btc_balance+'$updated_lend_balance',modified_date1='".date('Y-m-d')."' where userid='$uid'");
                   $this->db->query("insert into lending_payout_history_btc(userid,btcamount,created_date)values('".$uid."','".$updated_lend_balance."','".date('Y-m-d')."')");
                   }
				   //===================Shooping Wallet Expired Amount====================================================================//
			/*	  $month_ini = new DateTime("first day of last month");
				  $lastmonthdate=$month_ini->format('Y-m-d H:i:s');
				  $todaydate=date('Y-m-d H:i:s');
				   $wallets_balance=$this->db->query("select transferdate,sum(sendedamount) as send from lending_tranferto_shoppingwallet where transferdate BETWEEN '" . $lastmonthdate . "' AND  '" . $todaydate . "' and userid='$uid'")->result();
				$getrechargedata=$this->db->query("select recharge_inr_balance from user_balances where userid='$uid'")->row();
				$first_day_this_month = date('Y-m-01');
				$shopping_walletamount=$this->db->query("select amount from shopping_wallet_history where userid='$uid' and credited_date='$first_day_this_month'")->row();
			   $actualamount=$wallets_balance[0]->send+$shopping_walletamount->amount;
				if($actualamount > $getrechargedata->recharge_inr_balance)
			    	{
					$recharge_inr_balance=$getrechargedata[0]->recharge_inr_balance;
					$this->db->query("update user_balances set recharge_inr_balance ='$recharge_inr_balance' where userid='$uid'");
				   }else{
					$this->db->query("update user_balances set recharge_inr_balance ='$actualamount' where userid='$uid'");
			    	}*/
				   //=============================End Shopping Wallet====================================================================//
				
               }
	
              }
		echo 'Success';
		exit;
      }
	  
	//all 15 days payout contract//
	//===============================================================================Binary Payout Corn================================================
        public function update_mining_payout_chronjob(){ 
          echo 'Vijay Success';
           exit;
            $days=date('d');
            if($days==01){
              //  echo 1; exit;
             $date =date('Y-m-d', strtotime('last day of last month'));
             $year=date('Y',strtotime($date));
             $month=date('m',strtotime($date));
             $last_days=cal_days_in_month(CAL_GREGORIAN, date($month), date($year));
             $month_mid_days= date("Y-m-d", mktime(0, 0, 0, $month, 16, $year));
             $month_last_days= date("Y-m-d", mktime(0, 0, 0, $month, $last_days, $year));
             $users=$this->db->query("SELECT DISTINCT userid FROM miningcontract where userid between 1601 and 1900")->result();
               foreach($users as $row){
                      $uid=$row->userid;
                      $lendamount = 0;
                        // lending payout total
                        $this->db->select("SUM(debit_amount) AS lendingop");
                        $this->db->where('mlm_transaction.trans_date >=', $month_mid_days);
                        $this->db->where('mlm_transaction.trans_date <=', $month_last_days);
                        $this->db->where('mlm_transaction.userid', $uid);
                        $this->db->where('description', 'Mining Income');
                        $this->db->from('mlm_transaction');
                        $lendingtotal = $this->db->get()->result();
                        if (empty($lendingtotal[0]->lendingop)) {
                            $lendingtotal = 0.0;
                        } else {
                            $lendingtotal = $lendingtotal[0]->lendingop;
                        }
                    $lendamount = $lendamount + $lendingtotal;
                    $date=date('Y-m-d');
                    $duplicate=$this->db->query("select *from mining_payout_history where userid='".$uid."' and created_date='".$date."'")->result();
                    if(count($duplicate)==0){
					if(!empty($lendamount)){
					$this->db->query("insert into mining_payout_history(btcamount,userid,created_date)values('".$lendamount."','".$uid."','".date('Y-m-d')."')");
                   $this->db->query("update user_balances set lock_btc_balance = lock_btc_balance + '$lendamount' where userid='$uid'");
                   }
                }
              }
             } else if ($days==16) {
                // echo 1;
                 //exit;
             $date =date('Y-m-d', strtotime('first day of this month'));
             $year=date('Y',strtotime($date));
             $month=date('m',strtotime($date));
             $last_days=cal_days_in_month(CAL_GREGORIAN, date($month), date($year));
             $month_first_days= date("Y-m-d", mktime(0, 0, 0, $month, 01, $year));
             $month_mid_days= date("Y-m-d", mktime(0, 0, 0, $month,15, $year));
             $users=$this->db->query("SELECT DISTINCT userid FROM miningcontract where userid between 1 and 200")->result();
               foreach($users as $row){
                      $uid=$row->userid;
                      $lendamount = 0;
                        // lending payout total
                        $this->db->select("SUM(debit_amount) AS lendingop");
                        $this->db->where('mlm_transaction.trans_date >=', $month_first_days);
                        $this->db->where('mlm_transaction.trans_date <=', $month_mid_days);
                        $this->db->where('mlm_transaction.userid', $uid);
                        $this->db->where('description', 'Mining Income');
                        $this->db->from('mlm_transaction');
                        $lendingtotal = $this->db->get()->result();
						//echo $this->db->last_query();
						//exit;
                        if (empty($lendingtotal[0]->lendingop)) {
                            $lendingtotal = 0.0;
                        } else {
                            $lendingtotal = $lendingtotal[0]->lendingop;
                        }
                    $lendamount = $lendamount + $lendingtotal;
                    $date=date('Y-m-d');
                    $duplicate=$this->db->query("select *from mining_payout_history where userid='".$uid."' and created_date='".$date."'")->result();
                    if(count($duplicate)==0){
					if(!empty($lendamount)){
					$this->db->query("insert into mining_payout_history(btcamount,userid,created_date)values('".$lendamount."','".$uid."','".date('Y-m-d')."')");
                   $this->db->query("update user_balances set btc_balance = btc_balance + '$lendamount' where userid='$uid'");
				   }
                 }
                }
              }
	     echo 'Success';
		 exit;
            }

//================================================================================END==============================================================
//===============================================================================Direct Payout Corn================================================

 public function update_direct_payout_chronjob(){
    // echo 'success vIJAY';
    // exit;
            $days=date('d');
            if($days==01){
             $date =date('Y-m-d', strtotime('last day of last month'));
             $year=date('Y',strtotime($date));
             $month=date('m',strtotime($date));
             $last_days=cal_days_in_month(CAL_GREGORIAN, date($month), date($year));
             $month_mid_days= date("Y-m-d", mktime(0, 0, 0, $month, 16, $year));
             $month_last_days= date("Y-m-d", mktime(0, 0, 0, $month, $last_days, $year));
             $this->db->select("mlm_transaction.userid");
             $this->db->where('mlm_transaction.trans_date >=', $month_mid_days);
             $this->db->where('mlm_transaction.trans_date <=', $month_last_days);
             $this->db->where('mlm_transaction.description', 'Direct Income');
             $this->db->distinct('mlm_transaction.userid');
             $this->db->from('mlm_transaction');
             $users = $this->db->get()->result();
       
               foreach($users as $row){
                      $uid=$row->userid;
                      $lendamount = 0;
                        // lending payout total
                        $this->db->select("SUM(debit_amount) AS lendingop");
                        $this->db->where('mlm_transaction.trans_date >=', $month_mid_days);
                        $this->db->where('mlm_transaction.trans_date <=', $month_last_days);
                        $this->db->where('mlm_transaction.userid', $uid);
                        $this->db->where('description', 'Direct Income');
                        $this->db->from('mlm_transaction');
                        $lendingtotal = $this->db->get()->result();
                   // echo $this->db->last_query();
                    //exit;
                        if (empty($lendingtotal[0]->lendingop)) {
                            $lendingtotal = 0.0;
                        } else {
                            $lendingtotal = $lendingtotal[0]->lendingop;
                        }
                    $lendamount = $lendamount + $lendingtotal;
                    $date=date('Y-m-d');
                    $duplicate=$this->db->query("select *from direct_payout_history where userid='".$uid."' and created_date='".$date."'")->result();
                    if(count($duplicate)==0){
					if(!empty($lendamount)){
					$this->db->query("insert into direct_payout_history(btcamount,userid,created_date)values('".$lendamount."','".$uid."','".date('Y-m-d')."')");
                   $this->db->query("update user_balances set btc_balance = btc_balance + '$lendamount' where userid='$uid'");
                   }
                }
              }
             } else if ($days==16) {
               
             $date =date('Y-m-d', strtotime('first day of this month'));
             $year=date('Y',strtotime($date));
             $month=date('m',strtotime($date));
             $last_days=cal_days_in_month(CAL_GREGORIAN, date($month), date($year));
             $month_first_days= date("Y-m-d", mktime(0, 0, 0, $month, 01, $year));
             $month_mid_days= date("Y-m-d", mktime(0, 0, 0, $month,15, $year));
             $this->db->select("mlm_transaction.userid");
             $this->db->where('mlm_transaction.trans_date >=', $month_first_days);
             $this->db->where('mlm_transaction.trans_date <=', $month_mid_days);
             $this->db->where('mlm_transaction.description', 'Direct Income');
             $this->db->distinct('mlm_transaction.userid');
             $this->db->from('mlm_transaction');
              $users = $this->db->get()->result();
           //   echo $this->db->last_query();
            //  exit;
               foreach($users as $row){
                      $uid=$row->userid;
                      $lendamount = 0;
                        // lending payout total
                        $this->db->select("SUM(debit_amount) AS lendingop");
                        $this->db->where('mlm_transaction.trans_date >=', $month_first_days);
                        $this->db->where('mlm_transaction.trans_date <=', $month_mid_days);
                        $this->db->where('mlm_transaction.userid', $uid);
                        $this->db->where('description', 'Direct Income');
                        $this->db->from('mlm_transaction');
                        $lendingtotal = $this->db->get()->result();
						//echo $this->db->last_query();
						//exit;
                        if (empty($lendingtotal[0]->lendingop)) {
                            $lendingtotal = 0.0;
                        } else {
                            $lendingtotal = $lendingtotal[0]->lendingop;
                        }
                    $lendamount = $lendamount + $lendingtotal;
                    $date=date('Y-m-d');
                    $duplicate=$this->db->query("select *from direct_payout_history where userid='".$uid."' and created_date='".$date."'")->result();
                    if(count($duplicate)==0){
					if(!empty($lendamount)){
					$this->db->query("insert into direct_payout_history(btcamount,userid,created_date)values('".$lendamount."','".$uid."','".date('Y-m-d')."')");
                   $this->db->query("update user_balances set btc_balance = btc_balance + '$lendamount' where userid='$uid'");
				   }
                 }
                }
              }
			  echo 'Success';
		     exit;
            }
//================================================================================END==============================================================
// ===============================================================================Binary Payout Corn===============================================
 public function update_basic_binary_payout_chronjob(){
  //   echo 'success vijay';
 //	exit;
            $days=date('d');
            if($days==01){
         //       exit;
             $date =date('Y-m-d', strtotime('last day of last month'));
             $year=date('Y',strtotime($date));
             $month=date('m',strtotime($date));
			 $todays_date=date('Y-m-d');
             $last_days=cal_days_in_month(CAL_GREGORIAN, date($month), date($year));
             $month_mid_days= date("Y-m-d", mktime(0, 0, 0, $month, 16, $year));
             $month_last_days= date("Y-m-d", mktime(0, 0, 0, $month, $last_days, $year));
             $users=$this->db->query("select distinct u.userid,u.currency from user u inner join mlm_transaction m on m.userid=u.userid where m.trans_date='".$todays_date."' and m.description='Basic Binary Income' and u.status='Active'")->result();    
         //    echo $this->db->last_query();
           // exit;

             foreach($users as $row){
                      $uid=$row->userid;
                      $lendamount = 0;
                        // lending payout total
                        $this->db->select("SUM(debit_amount) AS lendingop");
                        $this->db->where('mlm_transaction.trans_date',$todays_date);
                        $this->db->where('mlm_transaction.userid', $uid);
                        $this->db->where('description', 'Basic Binary Income');
                        $this->db->from('mlm_transaction');
                        $lendingtotal = $this->db->get()->result();
                        if (empty($lendingtotal[0]->lendingop)) {
                            $lendingtotal = 0.0;
                        } else {
                            $lendingtotal = $lendingtotal[0]->lendingop;
                        }
                    $lendamount = $lendamount + $lendingtotal;
					
					$this->load->library('site');
					$curr=$row->currency;
					$response = $this->site->getbrokerage();
					if ($curr == 'INR') {
					$res = 1;
					} else {
					$res = $this->site->currencyconvertor('INR', $curr);
					}
					$onebtctocurrency = $response['buy'] * $res;
                     $finalfiatcurrency=$lendamount*$onebtctocurrency;
                     $date=date('Y-m-d');
                     $duplicate=$this->db->query("select *from binary_payout_history where userid='".$uid."' and created_date='".$date."'")->result();
                     if(count($duplicate)==0){
					if(!empty($lendamount)){
					$this->db->query("insert into binary_payout_history(btcamount,userid,created_date)values('".$lendamount."','".$uid."','".date('Y-m-d')."')");
                   $this->db->query("update user_balances set btc_balance = btc_balance + '$lendamount',modified_date1='".date('Y-m-d')."' where userid='$uid'");
                   }
                }
              }
             } else if ($days==16) {
			  $todays_date=date('Y-m-d');
             $date =date('Y-m-d', strtotime('first day of this month'));
             $year=date('Y',strtotime($date));
             $month=date('m',strtotime($date));
             $last_days=cal_days_in_month(CAL_GREGORIAN, date($month), date($year));
             $month_first_days= date("Y-m-d", mktime(0, 0, 0, $month, 01, $year));
             $month_mid_days= date("Y-m-d", mktime(0, 0, 0, $month,15, $year));
             $users=$this->db->query("select distinct u.userid,u.currency from user u inner join mlm_transaction m on m.userid=u.userid where m.trans_date='$todays_date' and m.description='Basic Binary Income' and u.status='Active'")->result();
            // echo $this->db->last_query();
             //exit;
               foreach($users as $row){
                      $uid=$row->userid;
                      $lendamount = 0;
                        // lending payout total
                        $this->db->select("SUM(debit_amount) AS lendingop");
                        $this->db->where('mlm_transaction.trans_date',$todays_date);
                        $this->db->where('mlm_transaction.userid', $uid);
                        $this->db->where('description', 'Basic Binary Income');
                        $this->db->from('mlm_transaction');
                        $lendingtotal = $this->db->get()->result();
                        if (empty($lendingtotal[0]->lendingop)) {
                            $lendingtotal = 0.0;
                        } else {
                            $lendingtotal = $lendingtotal[0]->lendingop;
                        }
                    $lendamount = $lendamount + $lendingtotal;
					$this->load->library('site');
					$curr=$row->currency;
					$response = $this->site->getbrokerage();
					if ($curr == 'INR') {
					$res = 1;
					} else {
					$res = $this->site->currencyconvertor('INR', $curr);
					}
					$onebtctocurrency = $response['buy'] * $res;
                     $finalfiatcurrency=$lendamount*$onebtctocurrency;
                     $date=date('Y-m-d');
                     $duplicate=$this->db->query("select *from binary_payout_history where userid='".$uid."' and created_date='".$date."'")->result();
                     if(count($duplicate)==0){
					if(!empty($lendamount)){
					$this->db->query("insert into binary_payout_history(btcamount,userid,created_date)values('".$lendamount."','".$uid."','".date('Y-m-d')."')");
                   $this->db->query("update user_balances set btc_balance = btc_balance +'$lendamount',modified_date1='".date('Y-m-d')."' where userid='$uid'");
				   }
                 }
                }
              }
			  echo 'Success';
		    exit;
            }
//========================END==========================================================================================

public function lock_contract_reinvest_cron_job(){
    echo 'success1';
	exit;
    $lending_lock=$this->db->query("select k.id,l.lending_percent,l.contract_duration,l.contractid,l.referral_percentage,l.userid,u.currency,u.emailid,u.username,l.investment from lendingcontract l inner join user u on u.userid=l.userid inner join lending_contract_locking k on k.contractId=l.contractid where l.status='Active' and l.reinvest_status='Lock'")->result();
  // echo $this->db->last_query();
   // exit;
	$i=1;
foreach($lending_lock as $info){
$userid=$info->userid;
$contractid=$info->contractid;

 $days=date('d');
            if($days==03){
			// first lending payout sume calculations
             $date =date('Y-m-d', strtotime('last day of last month'));
             $year=date('Y',strtotime($date));
             $month=date('m',strtotime($date));
             $current_moth=date('Y-m-d');
             $last_days=cal_days_in_month(CAL_GREGORIAN, date($month), date($year));
             $month_mid_days= date("Y-m-d", mktime(0, 0, 0, $month, 16, $year));
             $month_last_days= date("Y-m-d", mktime(0, 0, 0, $month,$last_days, $year));
	                    $this->db->select("SUM(lendingop) AS payoutoutput");
                        $this->db->where('lendingdate >=', $month_mid_days);
                        $this->db->where('lendingdate <=','2018-12-01');
                        $this->db->where('userid', $userid);
                        $this->db->where('contractid',$contractid);
                        $this->db->from('lendingop');
                        $lendingtotal = $this->db->get()->result();
                  //  echo $this->db->last_query();
                   // exit; die;
                        if (empty($lendingtotal[0]->payoutoutput)) {
                            $dollaramount1 = 0.0;
                        } else {
                            $dollaramount1 = $lendingtotal[0]->payoutoutput;
                        }
		if(!empty($dollaramount1)){
        if($dollaramount1>=10){
     // end additional code for api run on local
	      $curr = $info->currency;
     $response = $this->site->getbrokerage();
     if ($curr == 'INR') {
         $res = 1;
     } else {
         $res = $this->site->currencyconvertor('INR', $curr);
     }
     $onebtctocurrency = $response['buy'] * $res;
     //$this->db->trans_start();
     $time = date("Y-m-d h:m:s");
      $dollaramount =$dollaramount1;
      $bitcoinamount1 =$dollaramount*1/$onebtctocurrency;
      $bitcoinamount=number_format($bitcoinamount1, 8, '.', '');
     $dollar_rate=$this->site->oneusdtousrcurr();
     $peramount=round($dollaramount/$dollar_rate);
	 $lending_percent=$info->lending_percent+1;
     $referral_percentage=$info->referral_percentage;
    
     $purchaseid = time().$userid.$i;
    
   //  $purchaseid = time().$;
	// this is for reinvest
        
     /*Lending wallet deduction amount*/
	 $lend_wallet_amount= $this->AdminGenericModel->selectlendingwallet($userid);
	 
     if(!empty($dollaramount)){
         $lend_wallet_amount=$lend_wallet_amount-$dollaramount;
       }else{
        $lend_wallet_amount=$lending_balance;
        } 
      $update_lending_array=array('lending_balance'=>$lend_wallet_amount);
      $this->db->where('userid',$userid);
      $this->db->update('user_balances',$update_lending_array);         
      /*----------------------------------end--------------------------------*/
	  // reinvest code
	   $reinvest = array("userid" => $userid,"usdamount" => $dollaramount,"date" => date('Y-m-d h:m:s'),"comment" => "Reinvest");
         $result = $this->db->insert('reinvest',$reinvest);
     // lendingtb
     $lendingdata=array("purchaseid" => $purchaseid,"userid" => $userid,"amountbtc" => $bitcoinamount,"amountusd" => $dollaramount,"originalamount" => $dollaramount,"datecreated" => date('Y-m-d h:m:s'),"status" => 1,"comment" => "lended","currencysymbol" =>$info->currency);
     $result = $this->db->insert('lendingtb',$lendingdata);
	 // lending contract inserted records 
 $lendcontractdata =array("contractid" => $purchaseid,"userid" => $userid,"paidbtc" => $bitcoinamount,"contract_duration" => '750',"investment" => $info->investment,"lending_percent" => $lending_percent,'referral_percentage'=>$info->referral_percentage,"status" => "Active","signdate" => date('Y-m-d h:m:s'));
     $result = $this->db->insert('lendingcontract',$lendcontractdata);

     $limitcontract=array('userid'=>$userid,'usdamount'=>$dollaramount,'create_date'=>date('Y-m-d'));
     $this->db->insert('contract_limitation',$limitcontract);
     //Update User Tag
     $query = "select * from account where userid=" .$userid;
     $r = $this->AdminGenericModel->getQueryResult($query);
        $newamt = $r[0]['nodeweight'] + $bitcoinamount;
        if ($newamt > 0 && $newamt < 0.5) {
            //set accstatus in account to active            
            $this->db->query("update account set accstatus='active', nodeweight=" . $newamt . " where userid=" .$userid);
        } else if ($newamt >= 0.5) {
            //set accstatus in account to silver
            $this->db->query("update account set accstatus='silver', nodeweight=" . $newamt . " where userid=" .$userid);
        }
     $query = "CALL reg_topuplend('" .$userid."','" . $bitcoinamount . "')";
     $this->db->query($query);
     $data = array('amountbtc', 'amountusd');
     $this->session->unset_userdata($data);
     $this->session->set_userdata("contractpurchase", 'lending');
     $subject = "Growese - Lending Contract Acknowledgment";
     $messagebody= "Hi,<br><br>";
     $messagebody.= "Congratulations!!! <b>" . $info->username.
             "</b> You have purchased the Bitcoin Lending Contract worth ".$info->currency." " . $dollaramount . ".<br>";
     $messagebody .= "Sit Back And Relax We Are Making You Gain The Best Benefits Out Of Your Investment.<br>";
     $messagebody .= "Request To Update Your User Profile.<br>";
     $messagebody .= "<br><br>_________________________________________________________<br><br>";
     $messagebody .= "Regards,<br>Growese Team.";
     $messagebody .= "<br><br><i>Visit Us At <a href='http://www.growese.com'>www.growese.com</a></i>";
     $successmsg = "";
     $errmsg = "Some Issue in the Sending Acknowledgment. Please Raise a Ticket";
     $response = $this->GenericModel->sendEmail($info->emailid,"noreply@growese.com", $subject, $messagebody, $successmsg, $errmsg);
    }
}
     }else if ($days==16) {
             $date =date('Y-m-d', strtotime('first day of this month'));
             $year=date('Y',strtotime($date));
             $month=date('m',strtotime($date));
             $last_days=cal_days_in_month(CAL_GREGORIAN, date($month), date($year));
             $month_first_days= date("Y-m-d", mktime(0, 0, 0, $month, 01, $year));
             $month_mid_days= date("Y-m-d", mktime(0, 0, 0, $month,16, $year));
	        $this->db->select("SUM(lendingop) AS payoutoutput");
                        $this->db->where('lendingdate >=', $month_first_days);
                        $this->db->where('lendingdate <=', $month_mid_days);
                        $this->db->where('userid', $userid);
                        $this->db->where('contractid',$contractid);
                        $this->db->from('lendingop');
						$lendingtotal = $this->db->get()->result();
						if (empty($lendingtotal[0]->payoutoutput)) {
                            $dollaramount1 = 0.0;
                        } else {
                            $dollaramount1 = $lendingtotal[0]->payoutoutput;
                        }
     // end additional code for api run on local
	 if(!empty($dollaramount1)){
        if($dollaramount1>=10){
	      $curr = $info->currency;
     $response = $this->site->getbrokerage();
     if ($curr == 'INR') {
         $res = 1;
     } else {
         $res = $this->site->currencyconvertor('INR', $curr);
     }
     $onebtctocurrency = $response['buy'] * $res;
     //$this->db->trans_start();
     $time = date("Y-m-d h:m:s");
      $dollaramount =$dollaramount1;
      $bitcoinamount1 =$dollaramount*1/$onebtctocurrency;
      $bitcoinamount=number_format($bitcoinamount1, 8, '.', '');
     $dollar_rate=$this->site->oneusdtousrcurr();
     $peramount=round($dollaramount/$dollar_rate);
	 $lending_percent=$info->lending_percent+1;
	 $referral_percentage=$info->referral_percentage;
     $purchaseid = time().$userid.$i;
//     $purchaseid = time().$;
     /*Lending wallet deduction amount*/
	 $lend_wallet_amount= $this->AdminGenericModel->selectlendingwallet($userid);
	 
     if(!empty($dollaramount)){
         $lend_wallet_amount=$lend_wallet_amount-$dollaramount;
       }else{
        $lend_wallet_amount=$lend_wallet_amount;
        } 
      $update_lending_array=array('lending_balance'=>$lend_wallet_amount);
      $this->db->where('userid',$userid);
      $this->db->update('user_balances',$update_lending_array);         
      /*----------------------------------end--------------------------------*/
       $reinvest = array("userid" => $userid,"usdamount" => $dollaramount,"date" => date('Y-m-d h:m:s'),"comment" => "Reinvest");
         $result = $this->db->insert('reinvest',$reinvest);
     // lendingtb
     $lendingdata=array("purchaseid" => $purchaseid,"userid" => $userid,"amountbtc" => $bitcoinamount,"amountusd" => $dollaramount,"originalamount" => $dollaramount,"datecreated" => date('Y-m-d h:m:s'),"status" => 1,"comment" => "lended","currencysymbol" =>$info->currency);
     $lend = $this->db->insert('lendingtb',$lendingdata);
	
	 
	 // lending contract inserted records 
 $lendcontractdata =array("contractid" => $purchaseid,"userid" => $userid,"paidbtc" => $bitcoinamount,"contract_duration" => '750',"investment" => $info->investment,"lending_percent" => $lending_percent,'referral_percentage'=>$info->referral_percentage,"status" => "Active","signdate" => date('Y-m-d h:m:s'));
     $le = $this->db->insert('lendingcontract',$lendcontractdata);

     $limitcontract=array('userid'=>$userid,'usdamount'=>$dollaramount,'create_date'=>date('Y-m-d'));
     $this->db->insert('contract_limitation',$limitcontract);
	 //Update User Tag
     $query = "select * from account where userid=" .$userid;
     $r = $this->AdminGenericModel->getQueryResult($query);
        $newamt = $r[0]['nodeweight'] + $bitcoinamount;
        if ($newamt > 0 && $newamt < 0.5) {
            //set accstatus in account to active            
            $this->db->query("update account set accstatus='active', nodeweight=" . $newamt . " where userid=" .$userid);
        } else if ($newamt >= 0.5) {
            //set accstatus in account to silver
            $this->db->query("update account set accstatus='silver', nodeweight=" . $newamt . " where userid=" .$userid);
        }
     $query = "CALL reg_topuplend('" .$userid."','" . $bitcoinamount . "')";
     $this->db->query($query);
     $data = array('amountbtc', 'amountusd');
     $this->session->unset_userdata($data);
     $this->session->set_userdata("contractpurchase", 'lending');
     $subject = "Growese - Lending Contract Acknowledgment";
     $messagebody= "Hi,<br><br>";
     $messagebody.= "Congratulations!!! <b>" . $info->username.
             "</b> You have purchased the Bitcoin Lending Contract worth ".$info->currency." " . $dollaramount . ".<br>";
     $messagebody .= "Sit Back And Relax We Are Making You Gain The Best Benefits Out Of Your Investment.<br>";
     $messagebody .= "Request To Update Your User Profile.<br>";
     $messagebody .= "<br><br>_________________________________________________________<br><br>";
     $messagebody .= "Regards,<br>Growese Team.";
     $messagebody .= "<br><br><i>Visit Us At <a href='http://www.growese.com'>www.growese.com</a></i>";
     $successmsg = "";
     $errmsg = "Some Issue in the Sending Acknowledgment. Please Raise a Ticket";
     $response = $this->GenericModel->sendEmail($info->emailid, "noreply@growese.com", $subject, $messagebody, $successmsg, $errmsg);
         }
        }
       }
       $i++;
      }
	  	echo 'Success';
		exit;
			
			//====================================End===========================================================================
}

            public function mining_contract_expired(){
                $result=$this->db->query("select contractid,mining_percent,DATE_FORMAT(signdate,'%d, %b %y') 'signdate' ,DATE_FORMAT(DATE_ADD(signdate,INTERVAL contract_duration DAY),'%Y-%m-%d') 'expiredate',contractprice,paidbtc,userid,status,contract_duration,mining_percent,contracttypeid from miningcontract")->result();
                foreach($result as $row){
                    $uid=$row->userid;
                    $originalamount=$row->paidbtc;
                    $username=$this->db->query("select username,emailid from user where userid='$uid'")->row();
                $contact_expiry_date=strtotime("+1 day", strtotime($row->expiredate));
                $current_date = strtotime(date('Y-m-d'));
                /*match equal date contract to the current date thtrough*/
                if($contact_expiry_date==$current_date){
                    /*mining contract status changed*/
                    $lendingcontract=array('status'=>'Expired');
                    $this->db->where('contractid',$row->contractid);
                    $this->db->update('miningcontract',$lendingcontract);
                    /*-------------------end-----------------*/
                    /*mining add history */
                    $data = array(
                        'contractId'=>$row->contractid,
                        'userId'=>$uid,
                        'btcAmount'=>$originalamount,
                        'contractExpDate'=>$row->expiredate
                  );
                    $this->db->where('userid',$uid);
                    $this->db->insert('capital_mining_history',$data);
                    /*end */
                    /*mining users_balances udpate*/
                    $user_balances=$this->db->query("select cap_btc_balance from user_balances where userid='$uid'")->row();
                    $cap_btc_balance=$user_balances->cap_btc_balance+$originalamount;
                    $update_users_balances=array('cap_btc_balance'=>$cap_btc_balance);
                    $this->db->where('userid',$uid);
                    $this->db->update('user_balances',$update_users_balances);
                    /*-----------end---------------*/
                    /*mining contract expiry notification mails*/
                        $subject = "Growese - Contract Expired Acknowledgment";
                        $messagebody.= "Hi,";
                        $messagebody.= "!!! <b>" .$username->username."</b><br>Your contract is expired and your expired contract original amount 
                                       added to the your Capital Bitcoin wallet <br>";
                        $messagebody.= "!!! <b>Following Contract Expired Details</b><br><br>";
                        $messagebody.="<table border='1' style='border-collapse: collapse;padding:10px;text-align: left;width:50%;'>
                                        <tr><th>Contract ID</th><td>".$row->contractid."</td></tr>
                                        <tr><th>Original Amount</th><td>".$row->originalamount."</td></tr>
                                        <tr><th>Contract Expired Date</th><td>".$row->expiredate."</td></tr></table>";
                        $messagebody.= "Sit Back And Relax We Are Making You Gain The Best Benefits Out Of Your Investment.<br>";
                        $messagebody.= "<br><br>_________________________________________________________<br><br>";
                        $messagebody.= "Regards,<br>Growese Team.";
                        $messagebody.= "<br><br><i>Visit Us At <a href='http://www.growese.com'>www.growese.com</a></i>";
                        $this->GenericModel->sendEmail($username->emailid, "noreply@growese.com", $subject, $messagebody);	
                            }
                          }
                      }

                      public function lending_contract_expired(){
                        $result=$this->db->query("select lc.contractid,DATE_FORMAT(lc.signdate,'%d, %b %y') 'signdate' ,DATE_FORMAT(DATE_ADD(lc.signdate,INTERVAL lc.contract_duration DAY),'%Y-%m-%d') 'expiredate',lc.paidbtc,lc.userid,lc.status,lc.contract_duration,lc.investment,lc.lending_percent,lt.amountusd,lt.currencysymbol,lt.amountbtc,lt.originalamount from lendingcontract as lc,lendingtb as lt where lc.contractid=lt.purchaseid and lc.status='Active'")->result();
                       foreach($result as $row){
                           $uid=$row->userid;
                           $originalamount=$row->originalamount;
                     $username=$this->db->query("select username,emailid from user where userid='$uid'")->row();
                    $contact_expiry_date=strtotime("+1 day", strtotime($row->expiredate));
                    $current_date = strtotime(date('Y-m-d'));
                    /*match equal date contract to the current date thtrough*/
                    if($contact_expiry_date==$current_date){
                     
                     /*insert mlm_transaction table entry*/
                     $capital_lending_history=array('userid'=>$uid,'amount'=>$originalamount,'expire_date'=>$row->expiredate,'contractId'=>$row->contractid);
                     $this->db->insert('capital_lending_history',$insert_mlm_transaction_array);
                     /*------------------end-------------------------*/
                     
                     /*lending contract status changed*/
                     $lendingcontract=array('status'=>'Expired');
                     $this->db->where('contractid',$row->contractid);
                     $this->db->update('lendingcontract',$lendingcontract);
                     /*-------------------end-----------------*/
                     
                     /*lending users_balances udpate*/
                     $user_balances=$this->db->query("select btc_balance,lending_balance,cap_inr_balance from user_balances where userid='$uid'")->row();
                     $lending_balance=$user_balances->cap_inr_balance+$originalamount;
                     $update_users_balances=array('cap_inr_balance'=>$lending_balance);
                     $this->db->where('userid',$uid);
                     $this->db->update('user_balances',$update_users_balances);
                     /*-----------end---------------*/
                     
                     /*lending contract expiry notification mails*/
                         $subject = "Growese - Contract Expired Acknowledgment";
                         $messagebody.= "Hi,";
                         $messagebody.= "<b>" .$username->username."</b><br>Your contract is expired and your expired contract original amount 
                                        add to the your  capital lending wallet<br>";
                         $messagebody.= "!!! <b>Following Contract Expired Details</b><br><br>";
                         $messagebody.="<table border='1' style='border-collapse: collapse;padding:10px;text-align: left;width:50%;'>
                                         <tr><th>Contract ID</th><td>".$row->contractid."</td></tr>
                                         <tr><th>Original Amount</th><td>".$row->originalamount."</td></tr>
                                         <tr><th>Contract Expired Date</th><td>".$row->expiredate."</td></tr></table>";
                         $messagebody.= "Sit Back And Relax We Are Making You Gain The Best Benefits Out Of Your Investment.<br>";
                         $messagebody.= "<br><br>_________________________________________________________<br><br>";
                         $messagebody.= "Regards,<br>Growese Team.";
                         $messagebody.= "<br><br><i>Visit Us At <a href='http://www.growese.com'>www.growese.com</a></i>";
                         $this->GenericModel->sendEmail($username->emailid, "noreply@growese.com", $subject, $messagebody);    
                             }
                           }
                           echo 'status updated successfully';
                       }
        // public function bal_shift(){
        //     // $users=$this->db->query("SELECT userid from user")->result();
        //     // for($i=1500; $i < count($users); $i++){
        //     // $userid=$users[$i]->userid;
        //     $lending_amount= $this->AdminGenericModel->selectlendingwallet($userid);
        //     $btc_amount = $this->AdminGenericModel->selectbitcoinwallet($userid);
        //     $insert=array('userid'=>$userid,'lending_balance'=>$lending_amount,'btc_balance'=>$btc_amount,'cap_inr_balance'=>'0.00','cap_btc_balance'=>'0.00000000');
        //     $this->db->insert('user_balances',$insert);  
        //     print_r($userid);
        //     // }
        // }

        public function hikecoin_slab_notifications(){
            $result=$this->db->query("select emailid,username from user GROUP BY emailid")->result();
             foreach($result as $row){
              $emailid=$row->emailid;
              $username=$row->username;
              $subject = "Growese - Important Notice:";
              $messagebody = "Hi, ".$username." <div style='padding:10px;'><b>Now pay your utility bill payments with the help of Growese. </b><br>
              <p>We have introduced Recharges section available on Growese in which you all can make your payments for the following things:.</p>
                         <br><p>1. Mobile Prepaid/ Postpaid</p><p>2. Data Card</p><p>3. DTH Recharges</p><p>4. Electricity</p><br><p>
                            <b>All these above recharges you can do with your available INR/BTC on Growese.com from your Lending INR Wallet or Bitcoin Wallet.</b></p>Thanks & Regards,<br>Growese Team<br><i>Visit Us At <a href='http://www.growese.com'>www.growese.com</a></i></div>";
                 $sent = $this->GenericModel->sendEmail($emailid,"support@growese.com",$subject, $messagebody);        
             }
            //  echo '<pre>';print_r(count($result));die();
            echo 'All mails sent successfully';
         }
		 //start coding lending 
public function contract_locking(){
$contractId=$this->input->post('contractid');
$lending=$this->db->query("select lc.contractid,DATE_FORMAT(lc.signdate,'%d, %b %y') 'signdate' ,DATE_FORMAT(DATE_ADD(lc.signdate,INTERVAL lc.contract_duration DAY),'%Y-%m-%d') 'expiredate',lc.paidbtc,lc.userid,lc.status,lc.contract_duration,lc.investment,lc.lending_percent,lt.amountusd,lt.currencysymbol,lt.amountbtc,lt.originalamount from lendingcontract as lc,lendingtb as lt where lc.contractid=lt.purchaseid and lc.contractid='$contractId'")->row();
$now = time(); // or your date as well
$your_date = strtotime($lending->expiredate);
$datediff = $now - $your_date;
$lock_expired_date=date('Y-m-d', strtotime("+180 day"));
$created_date=date('Y-m-d');
$contract_lock_duration=180;
 $contract_duration=abs(round($datediff / (60 * 60 * 24)));
if($contract_duration>=180){
  $insert_data=array('contractId'=>$contractId,'contract_lock_duration'=>$contract_lock_duration,'lock_expired_date'=>$lock_expired_date,'created_date'=>$created_date);
  $this->db->insert('lending_contract_locking',$insert_data);
  $lock_status=array('reinvest_status'=>'Lock');
  $this->db->where('contractid',$contractId);
  $this->db->update('lendingcontract',$lock_status);
  echo 1;
  exit;
     }else{
	 echo 2;
	 exit;
	 }
   }
   
   // CONTACT SEND
   
       public function sendEnquiry() {
     //   echo "hi";
    $info = $this->input->post();
  //  echo $email=$info['email'];
    //echo $subject=$info['subject'];
    //echo $message=$info['message'];
    //exit();
	 $messagebody='<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--[if !mso]><!-->
<!--<![endif]-->
<title></title>
<link href="https://fonts.googleapis.com/css?family=Droid+Serif" rel="stylesheet" type="text/css">
</head><body style="background-color:#edeff0">
<table class="nl-container" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;min-width: 320px;Margin: 0 auto;background-color: #edeff0;width: 100%; padding-top:20px; padding-bottom:20px;" cellpadding="0" cellspacing="0">
  <tbody>
    <tr style="vertical-align: top">
      <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top; padding-top: 20px;padding-bottom: 20px;"><!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="background-color: #FADDBB;"><![endif]-->
        <div style="background-color:transparent;">
          <div style="Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #FFFFFF;" class="block-grid ">
            <div style="border-collapse: collapse;display: table;width: 100%;background-color:#FFFFFF;">
              <div class="col num12" style="min-width: 320px;max-width: 600px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;">
                  <!--[if (!mso)&(!IE)]><!-->
                  <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:0px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                    <!--<![endif]-->
                    <div align="center" class="img-container center  autowidth  fullwidth " style="padding-right: 0px;  padding-left: 0px;background-color: #2b2a2a;">
                      <div style="line-height:15px;font-size:1px">&#160;</div>
                      <img class="center  autowidth  fullwidth" align="center" border="0" src="https://www.growese.com/assets/images/hickcoins-logo.png" alt="Image" title="Image" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: 0;float: none;width: 320px;max-width: 600px" width="600">
                      <div style="line-height:15px;font-size:1px">&#160;</div>
                      <!--[if mso]></td></tr></table><![endif]-->
                    </div>
                    <!--[if (!mso)&(!IE)]><!-->
                  </div>
                  <!--<![endif]-->
                </div>
              </div>
              <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
            </div>
          </div>
        </div>
        <div style="background-color:transparent;">
          <div style="Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #FFFFFF;" class="block-grid ">
            <div style="border-collapse: collapse;display: table;width: 100%;background-color:#FFFFFF;">
              <div class="col num12" style="min-width: 320px;max-width: 600px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;">
                  <!--[if (!mso)&(!IE)]><!-->
                  <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                    <!--<![endif]-->
                    <div class="">
             <div style="line-height:120%;color:#febc11;font-family:Droid Serif, Georgia, Times, Times New Roman, serif; padding-right: 10px; padding-left: 10px; padding-top: 0px;
padding-bottom: 3px;">
                        <div style="font-size:12px;line-height:14px;font-family:Droid Serif, Georgia, Times, Times New Romanserif;color:#f39229;text-align:left;">
                          <p style="margin: 0;font-size: 14px;line-height: 17px;text-align: center"><span style="font-size: 36px; line-height:20px;"><strong><span style="line-height:20px; font-size:22px;">Welcome To Growese</span></strong></span></p>
                        </div>
                      </div>
                      <!--[if mso]></td></tr></table><![endif]-->
                    </div>
                    <div >
                    </div>
                    <div>
             <div style="color:#555555;font-family:Droid SerifGeorgia, Times,Times New Roman,serif; padding-right: 10px; padding-left: 10px; padding-top: 10px; margin-top:-50px;">
                        <div style="font-size:12px;line-height:24px;color:#555555;font-family:Droid SerifGeorgia, Times,Times New Roman,serif;text-align:left;">
                          <span style="font-size:16px; line-height:19px; margin-left:20px;">
						    <p style="font-size: 14px;text-align:left;"><strong><span style="font-size:18px;">Hello Growese Team,</strong></p>
                          <p style="color: #000000; text-align:justify; font-size:16px;">
						  '.$info['message'].'
						 </p> 
						  </p>
                        </div>
                      </div>
                    </div>
              <br /><br />
					<div style="margin-left:10px; margin-bottom:10px;"><strong style="font-size:16px;font-style: italic;">Thanks & Regards</strong><br />
					<span style="padding-top:20px; margin-top:10px;">Growese Teams</span><br />
					<span style="padding-top:20px; margin-top:10px;">Visit Us At<a target="_blank" href="http://www.growese.com"> www.growese.com</a></span>
					</div>
                    <div style="background-color: #4e4646;">
                      <div class="">
                     
                        <div style="line-height:120%;color:#F99495;font-family:Droid SerifGeorgia, Times,Times New Roman,serif; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 25px;">
                          <div style="font-size:12px;line-height:14px;color:#fff;font-family:Droid SerifGeorgia, Times,Times New Roman,serif;text-align:left;">
                            <p style="margin: 0;font-size:18px;line-height: 17px;text-align: center"><span style="font-size:14px; line-height: 13px;"> 
							Please do not reply to this email. Emails sent to this address will not be answered
							<br />Note: If it wasnt you please immediately contact support@growese.com.
                              Once again, we thank you for using Growese trusted products.
                              </span>
                            </p>
                          </div>
                        </div>
                        <!--[if mso]></td></tr></table><![endif]-->
                      </div>
                      <div align="center" style="padding-right: 10px; padding-left: 10px; padding-bottom:10px;" class="">
                        <div style="line-height:10px;font-size:1px">&#160;</div>
                        <div style="display: table;">
                          <table align="left" border="0" cellspacing="0" cellpadding="0" tyle="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;Margin-right: 5px">
                            <tbody>
                              <tr style="vertical-align: top">
                                <td align="left" valign="middle">
								<a href="https://www.facebook.com/growese/" title="Facebook" target="_blank"> <img src="https://app-rsrc.getbee.io/public/resources/social-networks-icon-sets/circle-color/facebook@2x.png" alt="Facebook" title="Facebook" width="32" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;border: none;float: none;max-width: 32px !important"> </a> 
								<a href="https://twitter.com/growese" title="Twitter" target="_blank"> <img src="https://app-rsrc.getbee.io/public/resources/social-networks-icon-sets/circle-color/twitter@2x.png" alt="Twitter" title="Twitter" width="32" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;border: none;float: none;max-width: 32px !important"> </a> <a href="https://t.me/growese" title="Telegram" target="_blank"> <img src="https://app-rsrc.getbee.io/public/resources/social-networks-icon-sets/circle-color/telegram@2x.png" alt="Telegram" title="Telegram" width="32" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;border: none;float: none;max-width:32px !important"> </a>
                                </td>
							
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>
</body>
</html>';
$r = $this->GenericModel->sendEmail("support@growese.com", $info['email'], $info['subject'],$messagebody);
//print_r($r);        
if ($r['status'])
    
{   
       $this -> session -> set_flashdata('sucessmessage1', '<div class="alert alert-success " style="width:fit-content;margin-left:70px;"><strong> Your message has been sent Thanks for Contact us...!</strong><a href="#" class="close" data-dismiss="alert" aria-label="close">�</a>	</div>');
      // redirect('GenericController');
       redirect(site_url().'#contact');
    
    //redirect(base_url() . '#section-9');
	//  $this->session->set_flashdata('succ_message', 'Your message has been sent');
        
}          
	   else
	   {
//
        redirect(base_url() . 'errorpage');
    }
}
public function currency_exchange_rate_cron(){
	$arrContextOptions = array(
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ),
            );
            $zebpay = file_get_contents('https://data.fixer.io/api/latest?access_key=07b6818ce1819910a643942f961b554d&base=inr', false, stream_context_create($arrContextOptions));
            $inr_usd_res = json_decode($zebpay, true);
            $inr_rate=file_get_contents('https://data.fixer.io/api/latest?access_key=07b6818ce1819910a643942f961b554d&base=usd', false, stream_context_create($arrContextOptions));
            //	print_r($zebpay_res);
               $usd_res = json_decode($inr_rate, true);
                $inr_usd=$inr_usd_res['rates']['USD'];
                $inr_eur=$inr_usd_res['rates']['EUR'];
                 $usd_inr=$usd_res['rates']['INR'];
		//	print_r($zebpay_res);
	     //$inr_usd=$inr_usd_res['rates']['USD'];
         $this->db->query("update brokerage set inr_usd='$inr_usd',usd_inr='$usd_inr',inr_eur='$inr_eur' where id=1");
        echo 'update successfully';
  }
 // Array ( [query] => Array ( [count] => 1 ) [results] => Array ( [INR_USD] => Array ( [id] => INR_USD [val] => 0.014568 [to] => USD [fr] => INR ) ) )

 //=========================Mobile OTP Functionality Code Start=============================================================
/*public function send_sms() {   
    $mobileNumber=$this->input->post('contactno1');
    $country_code=$this->input->post('country1');
    $test=$this->input->post('test');
    if($test=='secure'){
     $otp = rand(1000, 9999);
    }
$sms1="Your OTP code:".$otp;
$sms= urlencode($sms1);
// for International SMS	   
$url = "https://www.mgage.solutions/SendSMS/sendmsg.php?uname=btcmonk&pass=welcome1&send=HKCOIN&dest=$country_code$mobileNumber&msg=$sms&intl=1&concat=1";

$ch=curl_init($url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch,CURLOPT_POST,1);
curl_setopt($ch,CURLOPT_POSTFIELDS,"");
curl_setopt($ch, CURLOPT_RETURNTRANSFER,2);
$data = curl_exec($ch);
echo '<br/> <br/>';
print($data); /* result of API call

$sms91_resp = file_get_contents($url);
$resp = json_decode($sms91_resp, true);
var_dump($resp);
exit;
$query = $this->GenericModel->send_sms($mobileNumber,$country_code,$otp);
$_SESSION['id']=$query;
}*/


function get_fcontent( $url,  $javascript_loop = 0, $timeout = 5 ) {
    $url = str_replace( "&amp;", "&", urldecode(trim($url)) );

    $cookie = tempnam ("/tmp", "CURLCOOKIE");
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_COOKIEJAR, $cookie );
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt( $ch, CURLOPT_ENCODING, "" );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
    curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
    curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
    $content = curl_exec( $ch );
    $response = curl_getinfo( $ch );
    curl_close ( $ch );

    if ($response['http_code'] == 301 || $response['http_code'] == 302) {
        ini_set("user_agent", "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");

        if ( $headers = get_headers($response['url']) ) {
            foreach( $headers as $value ) {
                if ( substr( strtolower($value), 0, 9 ) == "location:" )
                    return get_url( trim( substr( $value, 9, strlen($value) ) ) );
            }
        }
    }

    if (    ( preg_match("/>[[:space:]]+window\.location\.replace\('(.*)'\)/i", $content, $value) || preg_match("/>[[:space:]]+window\.location\=\"(.*)\"/i", $content, $value) ) && $javascript_loop < 5) {
        return get_url( $value[1], $javascript_loop+1 );
    } else {
        return array( $content, $response );
    }
}

public function send_sms123() {   

    $arrContextOptions = array(
        "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false,
        ),
    );
    $mobileNumber=$this->input->post('contactno1');
    $country_code=$this->input->post('country1');
    $test=$this->input->post('test');
    if($test=='secure'){
     $otp = rand(1000, 9999);
    }
$sms1="Your OTP code:".$otp;
$sms= urlencode($sms1);
// for International SMS	   
$url = "https://www.mgage.solutions/SendSMS/sendmsg.php?uname=btcmonk&pass=welcome1&send=HKCOIN&dest=$country_code$mobileNumber&msg=$sms&intl=1&concat=1";
$lurl=$this->get_fcontent($url);
echo $lurl[0];
//exit;


if(!$response){
    $response = file_get_contents($url);
}else{
    echo 1; exit;
}
ini_set('display_errors', 1);
//echo $url;
$url = file_get_contents($url, false, stream_context_create($arrContextOptions));

$requestParams = array(
    'uname' => 'btcmonk',
    'pass' => 'welcome1',
    'send' => 'HKCOIN',
    'dest' => $country_code.$mobileNumber,
    'msg' => $sms,
    'intl'=>1,
    'concat'=>1);

    $apiUrl = "https://www.mgage.solutions/SendSMS/sendmsg.php?";
foreach($requestParams as $key => $val){
    $apiUrl .= $key.'='.urlencode($val).'&';
}
$apiUrl = rtrim($apiUrl, "&");

//echo $apiUrl;
//exit;
//API call
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result=curl_exec($ch);
var_dump($result);
curl_close($ch);


$resp = json_decode($sms91_resp, true);
$query = $this->GenericModel->send_sms($mobileNumber,$country_code,$otp);
$_SESSION['id']=$query;
}

public function send_sms() {   
    $mobileNumber=$this->input->post('contactno1');
    $country_code=$this->input->post('country1');
    $test=$this->input->post('test');
        if($test=='secure'){
        $otp = rand(1000, 9999);
    }
$numbers='91'.$mobileNumber;
    $otp = rand(1000, 9999);
    $sms1="Your_OTP_Is:".$otp;
    $sms= urlencode($sms1);
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://localxcoin.com/send_sms.php");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "uname=btcmonk&pass=welcome1&send=HKCOIN&dest=$numbers&msg=$sms&intl=1&concat=1");
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    $output = curl_exec($ch);
    echo '<pre>'; print_r($output);
    $query = $this->GenericModel->send_sms($mobileNumber,$country_code,$otp);
    $_SESSION['id']=$query;
    exit; 
}


public function check_send_sms($id) {
$userotp=$this->input->post('userotp1');
$contactno=$this->input->post('contactno');
$response = $this->GenericModel->check_send_sms($userotp,$contactno);
if($response==1)
{
echo 1;    
}
elseif($response==2)
{
echo 2;
}
else
{
echo 0;	
}
}

public function check_verify_OTP () {
$userotp=$this->input->post('userotp'); 
$checking_otp=$this->db->query("select status from users_otp where otp ='$userotp' and status='1'")->row();
if(count($checking_otp) >= 1){
   echo 1;
   exit();
}
else {
echo 0;   
exit();
}
}
public function mobile_no_verifications(){
$contactno = $this->input->post('contactno');
$users_otp=$this->db->query("select *from users_otp where mobileNumber='$contactno' and status='1' order by id desc")->result();
if(count($users_otp)>=1){
echo 1;
exit;
}else{
echo 2;
exit;
}
}

public function duplicate_mobile_numbers(){
    $contactno=$this->input->post('contactno');
       $q=$this->db->query("select contactno from user where contactno='$contactno'")->result();
     //  echo $this->db->last_query();
     if(count($q)==0){
      echo 1;
      exit;
     }else{
         echo 2;
         exit;
     }
}

//=======================================END Mobile OTP==========================================================

// Daily ETH Cron Details================================================================
public function cronjob_eth_daily_payout() {
    $d = date('Y-m-d h:i:s');
    $date = date_create($d);
    $da = date_sub($date, date_interval_create_from_date_string("1 days"));
   $miningdate = date_format($da, "Y-m-d h:i:s");
   $miningdate1 = date_format($da, "Y-m-d");
   //$miningdate = "2018-08-21 05:36:01";
    // $record = $this->GenericModel->getQueryResult("select distinct lc.contractid,lt.amountusd,lc.userid,lc.contract_duration,lc.lending_percent from lendingcontract as lc,lendingtb as lt where lc.contractid=lt.purchaseid and DATE_FORMAT(lc.signdate,'%Y-%m-%d')<DATE_FORMAT('" . $miningdate . "','%Y-%m-%d') and DATEDIFF(DATE_FORMAT(CURRENT_DATE,'%Y-%m-%d'),DATE_FORMAT(lc.signdate,'%Y-%m-%d'))<=lc.contract_duration and lc.status='Active' and lc.contractid not in (select contractid from lendingop where DATE_FORMAT(lendingdate,'%Y-%m-%d')=DATE_FORMAT('" . $miningdate . "','%Y-%m-%d')) order by userid");
    $record =  $this->GenericModel->getQueryResult("select distinct lc.contractid,lt.amountusd,lc.userid,lc.contract_duration,lc.lending_percent,lc.status,u.username,lc.referral_percentage from lendingcontract_eth lc inner join lendingtb_eth lt on lt.purchaseid=lc.contractid inner join user u on u.userid=lc.userid where u.status='Active' and lc.status='Active' and lc.contractid=lt.purchaseid and DATE_FORMAT(lc.signdate,'%Y-%m-%d')<DATE_FORMAT('" . $miningdate . "','%Y-%m-%d') and DATEDIFF(DATE_FORMAT(CURRENT_DATE,'%Y-%m-%d'),DATE_FORMAT(lc.signdate,'%Y-%m-%d'))<=lc.contract_duration and lc.status='Active' and lc.contractid not in (select contractid from lendingop_eth where DATE_FORMAT(lendingdate,'%Y-%m-%d')=DATE_FORMAT('" . $miningdate . "','%Y-%m-%d')) order by userid");
   //echo $this->db->last_query();
   //exit;
   $duplicate_entry=$this->db->query("SELECT * FROM lendingop_eth where DATE(lendingdate) ='$miningdate1'")->result();
   if(count($duplicate_entry)==0){
    foreach ($record as $val) {
        //insert query to insert the lending op in the table
        $status = 0;
        $lendingcalculated = ($val['amountusd'] * ($val['lending_percent'] / 100)) / 30;
       $data = array("lendingdate" => $miningdate,"lendingop" => $lendingcalculated,"contractid" => $val['contractid'],"userid" => $val['userid'],"status"=>0);
        $this->db->insert("lendingop_eth", $data);
        // referral %
          $refuser=$this->db->query("select refuser from user where userid='".$val['userid']."'")->row();
          $refer_lend_calculate='';
$referal_condition=$this->db->query("SELECT * FROM lendingcontract_eth WHERE DATE(signdate)>='2018-04-15' AND DATE(signdate)<='2018-09-15' and contractid='".$val['contractid']."'")->result();
         if(count($referal_condition)>=1){
          if(count($refuser)>=1){ // check the referral user exit or not
            $lending_referal_contract=$this->db->query("select status from lendingcontract_eth where userid='".$refuser->refuser."' and status='Active'")->result();
           // $mining_referal_contract=$this->db->query("select status from miningcontract where userid='".$refuser->refuser."' and status='Active'")->result();
            $referral_contract=count($lending_referal_contract);
           if($referral_contract>=1){
        $refer_lend_calculate = (($val['amountusd'] * ($val['referral_percentage']/ 100)) / 30);
          $lending_history=array('userid'=>$refuser->refuser,'contract_id'=>$val['contractid'],'referral_username'=>$val['username'],'amount'=>$refer_lend_calculate,'created_date'=>date('Y-m-d'),'status'=>'unpaid');
         $this->db->insert('lending_history_eth',$lending_history);
         }
         }
        }
        // end referral
    }
    if ($record[0]['contractid'] != null) {
       // $this->db->query("CALL bin_updateLendingOutput('$miningdate')");
        $r = $this->GenericModel->getQueryResult("select sum(lendingop)'sum1' from lendingop_eth where lendingdate ='" . $miningdate . "'");
        echo "Auto generated Lending Payout \n For Date " . $miningdate . "\n At Time " . $d . "\nUSD" . $r[0]['sum1'];
    } else {
        echo "Lending Payout \n For Date " . $miningdate . "\n Already Generated ";
    }
   }else{
    echo "Lending Payout \n For Date " . $miningdate . "\n Already Generated ";
   }
// Contract Unlock	

}

public function update_cronjob_eth_payout(){
 	
    $days=date('d');
    if($days==01){
     $date =date('Y-m-d', strtotime('last day of last month'));
     $year=date('Y',strtotime($date));
     $month=date('m',strtotime($date));
     $todays_date=date('Y-m-d');
     $last_days=cal_days_in_month(CAL_GREGORIAN, date($month), date($year));
     $month_mid_days= date("Y-m-d", mktime(0, 0, 0, $month, 16, $year));
     $month_last_days= date("Y-m-d", mktime(0, 0, 0, $month, $last_days, $year));
     $users=$this->db->query("select distinct userid from lendingcontract_eth group by userid")->result();
   //  $date=date('Y-m-d');
     $this->db->query("UPDATE lending_history_eth SET status='paid' WHERE created_date>='".$month_mid_days."' AND created_date<='".$month_last_days."'");
       foreach($users as $row){
           $date1=('Y-m-d');
              $uid=$row->userid;
              $lendamount = 0;
                $this->db->select("SUM(lendingop) AS lendingop");  $this->db->where('lendingdate >=', $month_mid_days);
                $this->db->where('lendingdate <=', $date1); $this->db->where('lendingop_eth.userid', $uid);
                $this->db->from('lendingop_eth'); $lendingtotal = $this->db->get()->result();
                if (empty($lendingtotal[0]->lendingop)) { $lendingtotal = 0.0;
                 } else { $lendingtotal = $lendingtotal[0]->lendingop;
             }
             $dailypayout=$this->db->query("select SUM(amount) AS dailyamount from lending_history_eth where (created_date>='".$month_mid_days."' and created_date<='".$month_last_days."') and userid='$uid'")->result();
             $total_refer_amount='';
             if(!empty($dailypayout[0]->dailyamount)){
             $total_refer_amount=$dailypayout[0]->dailyamount;
             }else{
             $total_refer_amount='0.0';
             }
            $lendamount = $lendamount + $lendingtotal;
            $updated_lend_balance = $lendamount+$total_refer_amount;
            $this->db->query("update user_balances set lending_eth_balance = lending_eth_balance + '$updated_lend_balance',update_date='".date('Y-m-d H:i:s')."' where userid='$uid'");
            $this->db->query("insert into lending_eth_payout_history(userid,amount,created_date)values('".$uid."','".$updated_lend_balance."','".date('Y-m-d')."')");
      }
     } else if ($days==16) {
      $todays_date=date('Y-m-d');
     $date =date('Y-m-d', strtotime('first day of this month'));
     $year=date('Y',strtotime($date));
     $month=date('m',strtotime($date));
     $last_days=cal_days_in_month(CAL_GREGORIAN, date($month), date($year));
     $month_first_days= date("Y-m-d", mktime(0, 0, 0, $month, 01, $year));
     $month_mid_days= date("Y-m-d", mktime(0, 0, 0, $month,16, $year));
     $users=$this->db->query("select distinct userid from lendingcontract_eth group by userid")->result();
     $this->db->query("UPDATE lending_history_eth SET status='paid' WHERE created_date>='".$month_first_days."' AND created_date<='".$month_last_days."'");
     foreach($users as $row){
            $uid=$row->userid;
            $lendamount = 0;
              $this->db->select("SUM(lendingop) AS lendingop");  $this->db->where('lendingdate >=', $month_first_days);
              $this->db->where('lendingdate <=', $month_mid_days); $this->db->where('lendingop_eth.userid', $uid);
              $this->db->from('lendingop_eth'); $lendingtotal = $this->db->get()->result();
              if (empty($lendingtotal[0]->lendingop)) { $lendingtotal = 0.0;
               } else { $lendingtotal = $lendingtotal[0]->lendingop;
           }
           $dailypayout=$this->db->query("select SUM(amount) AS dailyamount from lending_history_eth where (created_date>='".$month_first_days."' and created_date<='".$month_mid_days."') and userid='$uid'")->result();
           $total_refer_amount='';
           if(!empty($dailypayout[0]->dailyamount)){
           $total_refer_amount=$dailypayout[0]->dailyamount;
           }else{
           $total_refer_amount='0.0';
           }
          $lendamount = $lendamount + $lendingtotal;
          $updated_lend_balance = $lendamount+$total_refer_amount;
          $this->db->query("update user_balances set lending_eth_balance = lending_eth_balance + '$updated_lend_balance',update_date='".date('Y-m-d H:i:s')."' where userid='$uid'");
          $this->db->query("insert into lending_eth_payout_history(userid,amount,created_date)values('".$uid."','".$updated_lend_balance."','".date('Y-m-d')."')");
          }
      }
      echo 'Success';
    exit;
    }


    //====================================================================Locked ETH Contract Reinvest========================================================
public function lock_reinvest_coronjob_eth_contract(){
    $this->load->model("AdminGenericETHModel");
    $this->load->model("ETHLendingModel");
$lending_lock=$this->db->query("select l.lending_percent,l.contract_duration,l.contractid,l.referral_percentage,l.userid,u.currency,u.emailid,u.username,l.investment from lendingcontract_eth l inner join user u on u.userid=l.userid where l.status='Active' and l.reinvest_status='Lock'")->result();
foreach($lending_lock as $info){
$userid=$info->userid;
$contractid=$info->contractid;
$days=date('d');
        if($days==01){
            $date=('Y-m-d');
        // first lending payout sume calculations
         $date =date('Y-m-d', strtotime('last day of last month'));
         $year=date('Y',strtotime($date));
         $month=date('m',strtotime($date));
         $last_days=cal_days_in_month(CAL_GREGORIAN, date($month), date($year));
         $month_mid_days= date("Y-m-d", mktime(0, 0, 0, $month, 16, $year));
         $month_last_days= date("Y-m-d", mktime(0, 0, 0, $month,$last_days, $year));
                    $this->db->select("SUM(lendingop) AS payoutoutput");
                    $this->db->where('lendingdate >=', $month_mid_days);
                    $this->db->where('lendingdate <=', $date);
                    $this->db->where('userid', $userid);
                    $this->db->where('contractid',$contractid);
                    $this->db->from('lendingop_eth');
                    $lendingtotal = $this->db->get()->result();
                    if (empty($lendingtotal[0]->payoutoutput)) {
                        $dollaramount1 = 0.0;
                    } else {
                        $dollaramount1 = $lendingtotal[0]->payoutoutput;
                    }
    if(!empty($dollaramount1)){
    if($dollaramount1>=10){
 // end additional code for api run on local
      $curr = $info->currency;
 $response = $this->site->getbrokerage();
 if ($curr == 'INR') {
     $res = 1;
 } else {
     $res = $this->site->currencyconvertor('INR', $curr);
 }
 $onebtctocurrency = $response['eth_buy'] * $res;

 $onebtctocurrency1 = $response['buy'] * $res;

 //$this->db->trans_start();
 $time = date("Y-m-d h:m:s");
  $dollaramount =$dollaramount1;
  $bitcoinamount1 =$dollaramount*1/$onebtctocurrency;
  $bitcoinamount=number_format($bitcoinamount1, 8, '.', '');
 $dollar_rate=$this->site->oneusdtousrcurr();
 $peramount=round($dollaramount/$dollar_rate);
 $lending_percent=$info->lending_percent+1;
 $referral_percentage=$info->referral_percentage;
 $purchaseid = time().$userid;
$binarybitcoins =number_format($dollaramount*1/$onebtctocurrency1,8,'.',' '); 
// this is for reinvest
    
 /*Lending wallet deduction amount*/
 $lend_wallet_amount= $this->AdminGenericETHModel->selectlendingwallet($userid);
 
 if(!empty($dollaramount)){
     $lend_wallet_amount=$lend_wallet_amount-$dollaramount;
   }else{
    $lend_wallet_amount=$lending_balance;
    } 
  $update_lending_array=array('lending_eth_balance'=>$lend_wallet_amount);
  $this->db->where('userid',$userid);
  $this->db->update('user_balances',$update_lending_array);         
  /*----------------------------------end--------------------------------*/
  // reinvest code
   $reinvest = array("userid" => $userid,"usdamount" => $dollaramount,"date" => date('Y-m-d h:m:s'),"comment" => "Reinvest");
     $result = $this->db->insert('reinvest_eth',$reinvest);
 // lendingtb
 $lendingdata=array("purchaseid" => $purchaseid,"userid" => $userid,"amountbtc" => $bitcoinamount,"amountusd" => $dollaramount,"originalamount" => $dollaramount,"datecreated" => date('Y-m-d h:m:s'),"status" => 1,"comment" => "lended","currencysymbol" =>$info->currency);
 $result = $this->db->insert('lendingtb_eth',$lendingdata);
 // lending contract inserted records 
$lendcontractdata =array("contractid" => $purchaseid,"userid" => $userid,"paidbtc" => $bitcoinamount,"contract_duration" => '750',"investment" => $info->investment,"lending_percent" => $lending_percent,'referral_percentage'=>$info->referral_percentage,"status" => "Active","signdate" => date('Y-m-d h:m:s'));
 $result = $this->db->insert('lendingcontract_eth',$lendcontractdata);
 //Update User Tag
 $query = "select * from account where userid=" .$userid;
 $r = $this->AdminGenericModel->getQueryResult($query);
    $newamt = $r[0]['nodeweight'] + $binarybitcoins;
    if ($newamt > 0 && $newamt < 0.5) {
        //set accstatus in account to active            
        $this->db->query("update account set accstatus='active', nodeweight=" . $newamt . " where userid=" .$userid);
    } else if ($newamt >= 0.5) {
        //set accstatus in account to silver
        $this->db->query("update account set accstatus='silver', nodeweight=" . $newamt . " where userid=" .$userid);
    }
 $query = "CALL reg_topuplend('" .$userid."','" .$binarybitcoins. "')";
 $this->db->query($query);
 $data = array('amountbtc', 'amountusd');
 $this->session->unset_userdata($data);
 $this->session->set_userdata("contractpurchase", 'lending');
 $subject = "Growese - Lending Contract Acknowledgment";
 $messagebody= "Hi,<br><br>";
 $messagebody.= "Congratulations!!! <b>" . $info->username.
         "</b> You have purchased the Ethereum Lending Contract worth ".$info->currency." " . $dollaramount . ".<br>";
 $messagebody .= "Sit Back And Relax We Are Making You Gain The Best Benefits Out Of Your Investment.<br>";
 $messagebody .= "Request To Update Your User Profile.<br>";
 $messagebody .= "<br><br>_________________________________________________________<br><br>";
 $messagebody .= "Regards,<br>Growese Team.";
 $messagebody .= "<br><br><i>Visit Us At <a href='http://www.growese.com'>www.growese.com</a></i>";
 $successmsg = "";
 $errmsg = "Some Issue in the Sending Acknowledgment. Please Raise a Ticket";
 $response = $this->GenericModel->sendEmail($info->emailid,"noreply@growese.com", $subject, $messagebody, $successmsg, $errmsg);
}
    }
 }else if ($days=='16') {
   //  echo 'Hi Vijay';
    // exit;
         $date =date('Y-m-d', strtotime('first day of this month'));
         $year=date('Y',strtotime($date));
         $month=date('m',strtotime($date));
         $last_days=cal_days_in_month(CAL_GREGORIAN, date($month), date($year));
         $month_first_days= date("Y-m-d", mktime(0, 0, 0, $month, 01, $year));
         $month_mid_days= date("Y-m-d", mktime(0, 0, 0, $month,16, $year));
        $this->db->select("SUM(lendingop) AS payoutoutput");
                    $this->db->where('lendingdate >=', $month_first_days);
                    $this->db->where('lendingdate <=', $month_mid_days);
                    $this->db->where('userid', $userid);
                    $this->db->where('contractid',$contractid);
                    $this->db->from('lendingop_eth');
                    $lendingtotal = $this->db->get()->result();
                    if (empty($lendingtotal[0]->payoutoutput)) {
                        $dollaramount1 = 0.0;
                    } else {
                        $dollaramount1 = $lendingtotal[0]->payoutoutput;
                    }
 // end additional code for api run on local
 if(!empty($dollaramount1)){
    if($dollaramount1>=10){
    // end additional code for api run on local
    $curr = $info->currency;
    $response = $this->site->getbrokerage();
    if ($curr == 'INR') {
        $res = 1;
    } else {
        $res = $this->site->currencyconvertor('INR', $curr);
    }
    $onebtctocurrency = $response['eth_buy'] * $res;

    $onebtctocurrency1 = $response['buy'] * $res;

    //$this->db->trans_start();
    $time = date("Y-m-d h:m:s");
     $dollaramount =$dollaramount1;
     $bitcoinamount1 =$dollaramount*1/$onebtctocurrency;
     $bitcoinamount=number_format($bitcoinamount1, 8, '.', '');
    $dollar_rate=$this->site->oneusdtousrcurr();
    $peramount=round($dollaramount/$dollar_rate);
    $lending_percent=$info->lending_percent+1;
    $referral_percentage=$info->referral_percentage;
    $purchaseid = time().$userid;
   $binarybitcoins =number_format($dollaramount*1/$onebtctocurrency1,8,'.',' '); 
   // this is for reinvest
       
    /*Lending wallet deduction amount*/
    $lend_wallet_amount= $this->AdminGenericETHModel->selectlendingwallet($userid);
 
    if(!empty($dollaramount)){
        $lend_wallet_amount=$lend_wallet_amount-$dollaramount;
      }else{
       $lend_wallet_amount=$lending_balance;
       } 
     $update_lending_array=array('lending_eth_balance'=>$lend_wallet_amount);
     $this->db->where('userid',$userid);
     $this->db->update('user_balances',$update_lending_array);         
     /*----------------------------------end--------------------------------*/
     // reinvest code
      $reinvest = array("userid" => $userid,"usdamount" => $dollaramount,"date" => date('Y-m-d h:m:s'),"comment" => "Reinvest");
        $result = $this->db->insert('reinvest_eth',$reinvest);
    // lendingtb
    $lendingdata=array("purchaseid" => $purchaseid,"userid" => $userid,"amountbtc" => $bitcoinamount,"amountusd" => $dollaramount,"originalamount" => $dollaramount,"datecreated" => date('Y-m-d h:m:s'),"status" => 1,"comment" => "lended","currencysymbol" =>$info->currency);
    $result = $this->db->insert('lendingtb_eth',$lendingdata);
    // lending contract inserted records 
$lendcontractdata =array("contractid" => $purchaseid,"userid" => $userid,"paidbtc" => $bitcoinamount,"contract_duration" => '750',"investment" => $info->investment,"lending_percent" => $lending_percent,'referral_percentage'=>$info->referral_percentage,"status" => "Active","signdate" => date('Y-m-d h:m:s'));
    $result = $this->db->insert('lendingcontract_eth',$lendcontractdata);
    //Update User Tag
    $query = "select * from account where userid=" .$userid;
    $r = $this->AdminGenericModel->getQueryResult($query);
       $newamt = $r[0]['nodeweight'] + $binarybitcoins;
       if ($newamt > 0 && $newamt < 0.5) {
           //set accstatus in account to active            
           $this->db->query("update account set accstatus='active', nodeweight=" . $newamt . " where userid=" .$userid);
       } else if ($newamt >= 0.5) {
           //set accstatus in account to silver
           $this->db->query("update account set accstatus='silver', nodeweight=" . $newamt . " where userid=" .$userid);
       }
    $query = "CALL reg_topuplend('" .$userid."','" .$binarybitcoins. "')";
    $this->db->query($query);
    $data = array('amountbtc', 'amountusd');
    $this->session->unset_userdata($data);
    $this->session->set_userdata("contractpurchase", 'lending');
    $subject = "Growese - Lending Contract Acknowledgment";
    $messagebody= "Hi,<br><br>";
    $messagebody.= "Congratulations!!! <b>" . $info->username.
            "</b> You have purchased the Ethereum Lending Contract worth ".$info->currency." " . $dollaramount . ".<br>";
    $messagebody .= "Sit Back And Relax We Are Making You Gain The Best Benefits Out Of Your Investment.<br>";
    $messagebody .= "Request To Update Your User Profile.<br>";
    $messagebody .= "<br><br>_________________________________________________________<br><br>";
    $messagebody .= "Regards,<br>Growese Team.";
    $messagebody .= "<br><br><i>Visit Us At <a href='http://www.growese.com'>www.growese.com</a></i>";
    $successmsg = "";
    $errmsg = "Some Issue in the Sending Acknowledgment. Please Raise a Ticket";
    $response = $this->GenericModel->sendEmail($info->emailid,"noreply@growese.com", $subject, $messagebody, $successmsg, $errmsg);
     }
    }
   }
  }
      echo 'Success';
    exit;
        
        //====================================End===========================================================================
}

// Interest Module Code

public function cron_for_daily_hoursly_calculate_balance(){
   // $column=date('H');
 // echo  $column_name='t_'.$column;
    echo 'Success';
    exit;
    $userbalance= $this->db->query("SELECT * FROM user_balances")->result(); 
    foreach($userbalance as $row){ 
        $userid=$row->userid;
        $btc_balance=$row->btc_balance;
         $column=date('H');
         $column_name='t_'.$column;
         $hours=date('H:i:s');
     $check_exitence_users=$this->db->query("select *from tb_btc_hours_average_balance where userid='$userid'")->row();
     if(count($check_exitence_users)==0){
        $this->db->query("insert into tb_btc_hours_average_balance(userid,$column_name,date,u_time)values('$userid','$btc_balance','".date('Y-m-d')."','$hours')");
       }else{
       $this->db->query("update tb_btc_hours_average_balance set $column_name='$btc_balance',date='".date('Y-m-d')."',u_time='$hours' where userid='$userid'");
       }
    }
   echo 'Hours Interest updated successfully.';
   }
   
  // Daily Interest 
  public function cron_for_daily_interest_calculations(){
    echo 'Success';
    exit;
  $userbalance= $this->db->query("SELECT * FROM tb_btc_hours_average_balance")->result();  
  $intrest_percent=0.03;
  $refferal_interest=0.01;
  $date=date('Y-m-d');
  foreach($userbalance as $row){
  $userid=$row->userid;
 $btc=$this->db->query("SELECT SUM(t_01+t_02+t_03+t_04+t_05+t_06+t_07+t_08+t_09+t_10+t_11+t_12+t_13+t_14+t_15+t_16+t_17+t_18+t_19+t_20+t_21+t_22+t_23+t_00)/24 as average_btc FROM tb_btc_hours_average_balance WHERE userid='$userid' ORDER BY userid ASC")->row();
 //echo $this->db->last_query();
 //exit;
    if($btc->average_btc>=0.02){
    $interest_amt = number_format((($btc->average_btc * $intrest_percent) / 100), 8, '.', '');
    $interest_amt_refferal = number_format((($btc->average_btc * $refferal_interest) / 100), 8, '.', '');
    $duplicate_entry= $this->db->query("SELECT * FROM tb_daily_btc_interest WHERE date='$date' and userid='$userid'")->row(); 
    if(count($duplicate_entry)==0){
        $insert_array=array('interest_btc'=>$interest_amt,'userid'=>$userid,'intrest_percent'=>$intrest_percent,'date'=>$date);
        $this->db->insert('tb_daily_btc_interest',$insert_array);
        $referal_users=$this->db->query("select refuser from user where userid='$userid'")->row();
       $reffer_userid=$referal_users->refuser;
       $mining_contract=$this->db->query("select userid from miningcontract where userid='".$reffer_userid."' and status='Active'")->result();
       $lending_contract=$this->db->query("select userid from lendingcontract where userid='".$reffer_userid."' and status='Active'")->result();
       $lending_contract_eth=$this->db->query("select userid from lendingcontract_eth where userid='".$reffer_userid."' and status='Active'")->result();
       $active_contract=count($mining_contract)+count($lending_contract)+count($lending_contract_eth);
       if($active_contract>=1){
        $insert_array_referal=array('interest_btc'=>$interest_amt_refferal,'userid'=>$reffer_userid,'intrest_percent'=>$refferal_interest,'date'=>$date);
       $this->db->insert('tb_daily_refferal_btc_interest',$insert_array_referal);       
        }
       }
     }
     $b='0.00000000';
  }
     $this->db->query("update tb_btc_hours_average_balance set t_01='$b',t_02='$b',t_03='$b',t_04='$b',t_05='$b',t_06='$b',t_07='$b',t_08='$b',t_09='$b',t_10='$b',t_11='$b',t_12='$b',t_13='$b',t_14='$b',t_15='$b',t_16='$b',t_17='$b',t_18='$b',t_19='$b',t_20='$b',t_21='$b',t_22='$b',t_23='$b',t_00='$b'");
      echo 'Daily Interest updated successfully.';
    }
  public function cron_for_monthly_intreset_calculations(){
    echo 'Success';
    exit;
 $last_days=date('Y-m-d', strtotime('last day of last month'));
 $first_days=date('Y-m-d',strtotime('first day of last month'));             
 $query=$this->db->query("select sum(interest_btc) as btcamount,userid from tb_daily_btc_interest where date>='".$first_days."' and  date<='".$last_days."' group by userid")->result();
 if(date('d')=='01'){ 
 foreach($query as $row){
      $userid=$row->userid;
      $query_reffer=$this->db->query("select sum(interest_btc) as btcamount,userid from tb_daily_refferal_btc_interest where date>='".$first_days."' and  date<='".$last_days."' and userid='".$userid."' group by userid")->result();
      $bitcoinamt=$row->btcamount+$query_reffer[0]->btcamount;
      $current_date=date('Y-m-d');
      $duplicate_entry=$this->db->query("select *from tb_monthly_btc_interest where date='".$current_date."' and  userid='".$userid."'")->row();
      $users=$this->db->query("select userid from user where userid='$userid' and status='Active'")->row();
      if(count($users)>=1){
      if(count($duplicate_entry)==0){
     $insert_arr=array('userid'=>$userid,'intreset_amount'=>$bitcoinamt,'date'=>$current_date);     
     $this->db->insert('tb_monthly_btc_interest',$insert_arr);
      $this->db->query("update user_balances set btc_balance = btc_balance + '$bitcoinamt',modified_date1='".date('Y-m-d')."' where userid='$userid'");
       } 
      }
    }
 echo 'Interest Payout Updated Successfully';
 }else{   
 echo 'Interest Payout Only Updated of every month starting 1st';    
 }
 }
 public function time_check(){

    echo date('H');
 }


public function lock_btc_contract_expired(){
    $current_date=date('Y-m-d');
    $locking_contract=$this->db->query("select lc.lock_expired_date,lc.contractId,lc.id,l.userid,u.username,u.currency,u.emailid,l.lending_percent,l.referral_percentage,l.investment from lending_contract_locking lc inner join lendingcontract l on l.contractid=lc.contractId inner join user u on l.userid=u.userid where lc.status='Active' and lc.lock_expired_date='$current_date'")->result();
   $i=1; 
   foreach($locking_contract as $info){
        $userid=$info->userid;
        $dollaramount1 = $this->AdminGenericModel->selectlendingwallet($userid);
      
       $Unlock=array('reinvest_status'=>'Unlock');
       $Expired=array('status'=>'Expired');
            // Lending lending contract locking status update	   
       $this->db->where('id',$info->id);
       $this->db->update('lending_contract_locking',$Expired);
       // Lending contract reinvest status update
       $this->db->where('contractid',$info->contractId);
       $this->db->update('lendingcontract',$Unlock);
      
     /*   if($dollaramount1>=10){
     //  echo $userid;
       // exit;
            // end additional code for api run on local
                 $curr = $info->currency;
            $response = $this->site->getbrokerage();
            if ($curr == 'INR') {
                $res = 1;
            } else {
                $res = $this->site->currencyconvertor('INR', $curr);
            }
            $onebtctocurrency = $response['buy'] * $res;
            //$this->db->trans_start();
            $time = date("Y-m-d h:m:s");
             $dollaramount =$dollaramount1;
             $bitcoinamount1 =$dollaramount*1/$onebtctocurrency;
             $bitcoinamount=number_format($bitcoinamount1, 8, '.', '');
            $dollar_rate=$this->site->oneusdtousrcurr();
            $peramount=round($dollaramount/$dollar_rate);
            $lending_percent=$info->lending_percent;
            $referral_percentage=$info->referral_percentage;
            $purchaseid = time().$i;
             $update_lending_array=array('lending_balance'=>'0.00');
             $this->db->where('userid',$userid);
             $this->db->update('user_balances',$update_lending_array);   
             $i++;
             /*----------------------------------end--------------------------------
             // reinvest code
              $reinvest = array("userid" => $userid,"usdamount" => $dollaramount,"date" => date('Y-m-d h:m:s'),"comment" => "Reinvest");
                $result = $this->db->insert('reinvest',$reinvest);
            // lendingtb
            $lendingdata=array("purchaseid" => $purchaseid,"userid" => $userid,"amountbtc" => $bitcoinamount,"amountusd" => $dollaramount,"originalamount" => $dollaramount,"datecreated" => date('Y-m-d h:m:s'),"status" => 1,"comment" => "lended","currencysymbol" =>$info->currency);
            $result = $this->db->insert('lendingtb',$lendingdata);
            // lending contract inserted records 
        $lendcontractdata =array("contractid" =>$purchaseid,"userid" => $userid,"paidbtc" => $bitcoinamount,"contract_duration" => '750',"investment" => $info->investment,"lending_percent" => $lending_percent,'referral_percentage'=>$info->referral_percentage,"status" => "Active","signdate" => date('Y-m-d h:m:s'));
            $result = $this->db->insert('lendingcontract',$lendcontractdata);
            //Update User Tag
            $query = "select * from account where userid=" .$userid;
            $r = $this->AdminGenericModel->getQueryResult($query);
               $newamt = $r[0]['nodeweight'] + $bitcoinamount;
               if ($newamt > 0 && $newamt < 0.5) {   //set accstatus in account to active            
                   $this->db->query("update account set accstatus='active', nodeweight=" . $newamt . " where userid=" .$userid);
               } else if ($newamt >= 0.5) {       //set accstatus in account to silver
                   $this->db->query("update account set accstatus='silver', nodeweight=" . $newamt . " where userid=" .$userid);
               }
            $query = "CALL reg_topuplend('" .$userid."','" . $bitcoinamount . "')";
            $this->db->query($query);
            $data = array('amountbtc', 'amountusd');
            $this->session->unset_userdata($data);
            $this->session->set_userdata("contractpurchase", 'lending');
            $subject = "Growese - Lending Contract Acknowledgment";
            $messagebody= "Hi,<br><br>";
            $messagebody.= "Congratulations!!! <b>" . $info->username.
                    "</b> You have purchased the Bitcoin Lending Contract worth ".$info->currency." " . $dollaramount . ".<br>";
            $messagebody .= "Sit Back And Relax We Are Making You Gain The Best Benefits Out Of Your Investment.<br>";
            $messagebody .= "Request To Update Your User Profile.<br>";
            $messagebody .= "<br><br>_________________________________________________________<br><br>";
            $messagebody .= "Regards,<br>Growese Team.";
            $messagebody .= "<br><br><i>Visit Us At <a href='http://www.growese.com'>www.growese.com</a></i>";
            $successmsg = "";
            $errmsg = "Some Issue in the Sending Acknowledgment. Please Raise a Ticket";
            $response = $this->GenericModel->sendEmail($info->emailid,"noreplay@growese.com", $subject, $messagebody, $successmsg, $errmsg);
           }*/
    }
    echo 'Successfull Reinvest to the all expired locking contract';
}

/*=====================================================================================
 * ETH Interest Calculation Cron Start
 * ==================================================================================== */
public function cron_for_daily_hoursly_calculate_balance_eth(){
    echo 'Success';
    exit;
    $userbalance= $this->db->query("SELECT * FROM user_balances")->result(); 
    foreach($userbalance as $row){ 
        $userid=$row->userid;
        $btc_balance=$row->eth_balance;
         $column=date('H');
         $hours=date('H:i:s');
         $column_name='t_'.$column;
     $check_exitence_users=$this->db->query("select *from tb_eth_hours_average_balance where userid='$userid'")->row();
     if(count($check_exitence_users)==0){
        $this->db->query("insert into tb_eth_hours_average_balance(userid,$column_name,date,u_time)values('$userid','$btc_balance','".date('Y-m-d')."','$hours')");
       }else{
       $this->db->query("update tb_eth_hours_average_balance set $column_name='$btc_balance',date='".date('Y-m-d')."',u_time='$hours' where userid='$userid'");
       }
    }
   echo 'Hours Interest updated successfully.';
   }
   
  // Daily Interest 
  public function cron_for_daily_interest_calculations_eth(){
    echo 'Success';
    exit;
  $userbalance= $this->db->query("SELECT * FROM tb_eth_hours_average_balance")->result();  
  $intrest_percent=0.03;
  $refferal_interest=0.01;
  $date=date('Y-m-d');
  foreach($userbalance as $row){
  $userid=$row->userid;
 $btc=$this->db->query("SELECT SUM(t_01+t_02+t_03+t_04+t_05+t_06+t_07+t_08+t_09+t_10+t_11+t_12+t_13+t_14+t_15+t_16+t_17+t_18+t_19+t_20+t_21+t_22+t_23+t_00)/24 as average_eth FROM `tb_eth_hours_average_balance` WHERE userid='$userid' ORDER BY userid ASC")->row();
 //echo $this->db->last_query();
 //exit;
    if($btc->average_eth>=0.2){
    $interest_amt = number_format((($btc->average_eth * $intrest_percent) / 100), 8, '.', '');
    $interest_amt_refferal = number_format((($btc->average_eth * $refferal_interest) / 100), 8, '.', '');
    $duplicate_entry= $this->db->query("SELECT * FROM tb_daily_eth_interest WHERE date='$date' and userid='$userid'")->row(); 
    if(count($duplicate_entry)==0){
        $insert_array=array('interest_btc'=>$interest_amt,'userid'=>$userid,'intrest_percent'=>$intrest_percent,'date'=>$date);
        $this->db->insert('tb_daily_eth_interest',$insert_array);
        $referal_users=$this->db->query("select refuser from user where userid='$userid'")->row();
        $reffer_userid=$referal_users->refuser;
        $mining_contract=$this->db->query("select userid from miningcontract where userid='".$reffer_userid."' and status='Active'")->result();
        $lending_contract=$this->db->query("select userid from lendingcontract where userid='".$reffer_userid."' and status='Active'")->result();
        $lending_contract_eth=$this->db->query("select userid from lendingcontract_eth where userid='".$reffer_userid."' and status='Active'")->result();
        $active_contract=count($mining_contract)+count($lending_contract)+count($lending_contract_eth);
        if($active_contract>=1){
         $insert_array_referal=array('interest_btc'=>$interest_amt_refferal,'userid'=>$reffer_userid,'intrest_percent'=>$refferal_interest,'date'=>$date);
        $this->db->insert('tb_daily_refferal_eth_interest ',$insert_array_referal);       
        }
       }
     }
     $b='0.00000000';
  }
     $this->db->query("update tb_eth_hours_average_balance set t_01='$b',t_02='$b',t_03='$b',t_04='$b',t_05='$b',t_06='$b',t_07='$b',t_08='$b',t_09='$b',t_10='$b',t_11='$b',t_12='$b',t_13='$b',t_14='$b',t_15='$b',t_16='$b',t_17='$b',t_18='$b',t_19='$b',t_20='$b',t_21='$b',t_22='$b',t_23='$b',t_00='$b'");
      echo 'Daily Interest updated successfully.';
    }
  public function cron_for_monthly_intreset_calculations_eth(){
    echo 'Success';
    exit;
 $last_days=date('Y-m-d', strtotime('last day of last month'));
 $first_days=date('Y-m-d',strtotime('first day of last month'));             
 $query=$this->db->query("select sum(interest_btc) as btcamount,userid from tb_daily_eth_interest where date>='".$first_days."' and  date<='".$last_days."' group by userid")->result();
 $query_reffer=$this->db->query("select sum(interest_btc) as btcamount,userid from tb_daily_refferal_eth_interest where date>='".$first_days."' and  date<='".$last_days."' group by userid")->result();
 if(date('d')=='01'){ 
 foreach($query as $row){
  $userid=$row->userid;
    $query_reffer=$this->db->query("select sum(interest_btc) as ethamount,userid from tb_daily_refferal_eth_interest where date>='".$first_days."' and  date<='".$last_days."' and userid='".$userid."' group by userid")->result();
      $ethamount=$row->btcamount+$query_reffer[0]->ethamount;
      $current_date=date('Y-m-d');
      $duplicate_entry=$this->db->query("select *from tb_monthly_eth_interest where date='".$current_date."' and  userid='".$userid."'")->row();
      if(count($duplicate_entry)==0){
     $insert_arr=array('userid'=>$userid,'intreset_amount'=>$ethamount,'date'=>$current_date);     
     $this->db->insert('tb_monthly_eth_interest',$insert_arr);
     $this->db->query("update user_balances set eth_balance = eth_balance + '$ethamount',modified_date1='".date('Y-m-d')."' where userid='$userid'");     
      }
    }
 echo 'Interest Payout Updated Successfully';
 }else{   
 echo 'Interest Payout Only Updated of every month starting 1st';    
 }
 }
 /*=======================================================================================
  * ETH Interest Calculation Cron End
  * =====================================================================================*/

  public function fixed_deposit_contract_expired(){
  //  echo 'Success Vijay';
   // exit;
              $result=$this->db->query("select lc.contractid,DATE_FORMAT(lc.signdate,'%d, %b %y') 'signdate' ,DATE_FORMAT(DATE_ADD(lc.signdate,INTERVAL lc.contract_duration DAY),'%Y-%m-%d') 'expiredate',lc.paidbtc,lc.userid,lc.status,lc.referral_percentage,lc.contract_duration,lc.investment,lc.lending_percent,lt.amountusd,lt.currencysymbol,lt.amountbtc,lt.originalamount,lc.signdate from lendingcontract_fd as lc,lendingtb_fd as lt where lc.contractid=lt.purchaseid and lc.status='Active'")->result();
              
              foreach($result as $row){
                if($contact_expiry_date==$current_date){
                    $lending_percent=$row->lending_percent;
              $expiry_date=date('Y-m-d',strtotime($row->expiredate));
              $signdate=date('Y-m-d',strtotime($row->signdate));
             $fd_amount='';
             $fd_outputamount='';
                  $uid=$row->userid;
                  $originalamount=$row->paidbtc;
                  $contractid=$row->contractid;
                  $reffer_userid=$username->refuser;
                        
              $contact_expiry_date=strtotime("+1 day", strtotime($row->expiredate));
              $current_date = strtotime(date('Y-m-d'));
            
            
              /*match equal date contract to the current date thtrough*/
             
                 $duplicate=$this->db->query("select *from fixed_deposit_expiry_history where contractId='$contractid'")->result(); 
                 if(count($duplicate)==0){
              $month='';
             if($row->contract_duration=='270'){
              $month=9;   
             }elseif($row->contract_duration=='360'){
              $month=12;
             }elseif($row->contract_duration=='450'){
             $month=15;  
             }elseif($row->contract_duration=='540'){
              $month=18;   
             }
              if($row->investment=='BTC'){
               $wallet='Bitcoin';
               $fd_amount=$row->paidbtc;
               $investment=$row->investment;
              $fd_outputamount = number_format((($row->paidbtc * ($row->lending_percent / 100))*$month), 8, '.','');
              $lendingcalculated =number_format( (($row->paidbtc * ($row->lending_percent / 100))*$month+$row->paidbtc), 8, '.',''); 
               $this->db->query("insert into fixed_deposit_expiry_history(contractId,userid,fd_amount,fd_output_amount,output_percent,investment_type,comment,expiry_date
                       ,signdate)values('$contractid','$uid','$fd_amount','$fd_outputamount','$lending_percent','$investment','Fixed Deposit Amount Release','$expiry_date','$signdate')");
               $this->db->query("update user_balances set btc_balance = btc_balance + '$lendingcalculated',modified_date1='".date('Y-m-d')."' where userid='$uid'");  
              }elseif($row->investment=='USD'){
                  $wallet='Lending';
               $fd_amount=$row->amountusd;
               $investment=$row->investment;
              // number_format($btc_balance, 8, '.', '');
               $lendingcalculated = number_format((($row->amountusd * ($row->lending_percent / 100))*$month+$row->amountusd), 2, '.',''); 
               $fd_outputamount = number_format((($row->amountusd * ($row->lending_percent / 100))*$month), 2, '.','');
               
       
               $this->db->query("insert into fixed_deposit_expiry_history(contractId,userid,fd_amount,fd_output_amount,output_percent,investment_type,comment,expiry_date
                       ,signdate)values('$contractid','$uid','$fd_amount','$fd_outputamount','$lending_percent','$investment','Fixed Deposit Amount Release','$expiry_date','$signdate')");
               $this->db->query("update user_balances set lending_balance = lending_balance + '$lendingcalculated',modified_date1='".date('Y-m-d')."' where userid='$uid'");
               
              }
                  /*mining contract status changed*/
                  $lendingcontract=array('status'=>'Expired');
                  $this->db->where('contractid',$row->contractid);
                  $this->db->update('lendingcontract_fd',$lendingcontract);
                  /*-------------------end-----------------*/
                  /*-----------end---------------*/
                  /*mining contract expiry notification mails*/
                      $subject = "Growese - Fixed Deposit Contract Expired Acknowledgment";
                     $messagebody='';
                      $messagebody.= "<br>Your fixed deposit contract is expired and your expired contract original amount 
                                     added to the your ".$wallet."  wallet <br>";
                      $messagebody.= "!!! <b>Following Fixed Deposit Contract Expired Details</b><br><br>";
                      $messagebody.="<table border='1' style='border-collapse: collapse;padding:10px;text-align: left;width:100%;'>
                                      <tr><th>Contract ID</th><td>".$row->contractid."</td></tr>
                                      <tr><th>Invest Amount In ".$investment."</th><td>".$fd_amount."</td></tr>
                                      <tr><th>Income Amount In ".$investment."</th><td>".$fd_outputamount."</td></tr>
                                      <tr><th>Total Amount In ".$investment."</th><td>".$lendingcalculated."</td></tr>
                                      <tr><th>Contract Expired Date</th><td>".$row->expiredate."</td></tr></table>";
                      $messagebody.= "Sit Back And Relax We Are Making You Gain The Best Benefits Out Of Your Investment.<br>";
$messagebody1='<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--[if !mso]><!-->
<!--<![endif]-->
<title></title>
<link href="https://fonts.googleapis.com/css?family=Droid+Serif" rel="stylesheet" type="text/css">
</head><body style="background-color:#edeff0">
<table class="nl-container" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;min-width: 320px;Margin: 0 auto;background-color: #edeff0;width: 100%; padding-top:20px; padding-bottom:20px;" cellpadding="0" cellspacing="0">
<tbody>
  <tr style="vertical-align: top">
    <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top; padding-top: 20px;padding-bottom: 20px;"><!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="background-color: #FADDBB;"><![endif]-->
      <div style="background-color:transparent;">
        <div style="Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #FFFFFF;" class="block-grid ">
          <div style="border-collapse: collapse;display: table;width: 100%;background-color:#FFFFFF;">
            <div class="col num12" style="min-width: 320px;max-width: 600px;display: table-cell;vertical-align: top;">
              <div style="background-color: transparent; width: 100% !important;">
                <!--[if (!mso)&(!IE)]><!-->
                <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:0px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                  <!--<![endif]-->
                  <div align="center" class="img-container center  autowidth  fullwidth " style="padding-right: 0px;  padding-left: 0px;background-color: #2b2a2a;">
                    <div style="line-height:15px;font-size:1px">&#160;</div>
                    <img class="center  autowidth  fullwidth" align="center" border="0" src="https://www.growese.com/assets/images/hickcoins-logo.png" alt="Image" title="Image" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: 0;float: none;width: 320px;max-width: 600px" width="600">
                    <div style="line-height:15px;font-size:1px">&#160;</div>
                    <!--[if mso]></td></tr></table><![endif]-->
                  </div>
                  <!--[if (!mso)&(!IE)]><!-->
                </div>
                <!--<![endif]-->
              </div>
            </div>
            <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
          </div>
        </div>
      </div>
      <div style="background-color:transparent;">
        <div style="Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #FFFFFF;" class="block-grid ">
          <div style="border-collapse: collapse;display: table;width: 100%;background-color:#FFFFFF;">
            <div class="col num12" style="min-width: 320px;max-width: 600px;display: table-cell;vertical-align: top;">
              <div style="background-color: transparent; width: 100% !important;">
                <!--[if (!mso)&(!IE)]><!-->
                <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                  <!--<![endif]-->
                  <div class="">
           <div style="line-height:120%;color:#febc11;font-family:Droid Serif, Georgia, Times, Times New Roman, serif; padding-right: 10px; padding-left: 10px; padding-top: 0px;
padding-bottom: 3px;">
                      <div style="font-size:12px;line-height:14px;font-family:Droid Serif, Georgia, Times, Times New Romanserif;color:#f39229;text-align:left;">
                        <p style="margin: 0;font-size: 14px;line-height: 17px;text-align: center"><span style="font-size: 36px; line-height:20px;"><strong><span style="line-height:20px; font-size:20px;">Fixed Deposit Contract Expired Acknowledge</span></strong></span></p>
                      </div>
                    </div>
                    <!--[if mso]></td></tr></table><![endif]-->
                  </div>
                  <div >
                  </div>
                  <div>
           <div style="color:#555555;font-family:Droid SerifGeorgia, Times,Times New Roman,serif; padding-right: 10px; padding-left: 10px; padding-top: 10px; margin-top:-50px;">
                      <div style="font-size:12px;line-height:24px;color:#555555;font-family:Droid SerifGeorgia, Times,Times New Roman,serif;text-align:left;">
                        <span style="font-size:16px; line-height:19px; margin-left:20px;">
                          <p style="font-size: 14px;text-align:left;"><strong><span style="font-size:18px;">Hello '.$username->username.',</strong></p>
                        <p style="color: #000000; text-align:justify; font-size:16px;">
                        '.$messagebody.'
                       </p> 
                        </p>
                      </div>
                    </div>
                  </div>
                  <br>
                  
                  <div style="margin-left:10px; margin-bottom:10px;"><strong style="font-size:16px;font-style: italic;">Thanks & Regards</strong><br />
                  <span style="padding-top:20px; margin-top:10px;">Growese Team</span><br />
                  <span style="padding-top:20px; margin-top:10px;">Visit us at   <a target="_blank" href="http://www.growese.com"> www.growese.com</a></span>
                  </div>
                  <div style="background-color: #4e4646;">
                    <div class="">
                   
                      <div style="line-height:120%;color:#F99495;font-family:Droid SerifGeorgia, Times,Times New Roman,serif; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 25px;">
                        <div style="font-size:12px;line-height:14px;color:#fff;font-family:Droid SerifGeorgia, Times,Times New Roman,serif;text-align:left;">
                          <p style="margin: 0;font-size:18px;line-height: 17px;text-align: center"><span style="font-size:14px; line-height: 13px;"> 
                          Please do not reply to this email. Emails sent to this address will not be answered.
                          <br />Note: If it wasn&#39;t you please immediately contact  <a href="mailto:support@growese.com" style="color:#fab029;" target="_blank">support@growese.com</a>.
                            Once again, we thank you for using Growese trusted products.
                            </span>
                          </p>
                        </div>
                      </div>
                      <!--[if mso]></td></tr></table><![endif]-->
                    </div>
                    <div align="center" style="padding-right: 10px; padding-left: 10px; padding-bottom:10px;" class="">
                      <div style="line-height:10px;font-size:1px">&#160;</div>
                      <div style="display: table;">
                        <table align="left" border="0" cellspacing="0" cellpadding="0" tyle="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;Margin-right: 5px">
                          <tbody>
                            <tr style="vertical-align: top">
                              <td align="left" valign="middle">
                              <a href="https://www.facebook.com/growese/" title="Facebook" target="_blank"> <img src="https://app-rsrc.getbee.io/public/resources/social-networks-icon-sets/circle-color/facebook@2x.png" alt="Facebook" title="Facebook" width="32" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;border: none;float: none;max-width: 32px !important"> </a> 
                              <a href="https://twitter.com/growese" title="Twitter" target="_blank"> <img src="https://app-rsrc.getbee.io/public/resources/social-networks-icon-sets/circle-color/twitter@2x.png" alt="Twitter" title="Twitter" width="32" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;border: none;float: none;max-width: 32px !important"> </a> <a href="https://t.me/growese" title="Telegram" target="_blank"> <img src="https://app-rsrc.getbee.io/public/resources/social-networks-icon-sets/circle-color/telegram@2x.png" alt="Telegram" title="Telegram" width="32" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;border: none;float: none;max-width:32px !important"> </a>
                              </td>
                          
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </td>
  </tr>
</tbody>
</table>
</body>
</html>';
$this->GenericModel->sendEmail($username->emailid, "noreplay@growese.com", $subject, $messagebody1);	
 }
}
}

foreach($result as $row){
    $uid=$row->userid;
    $originalamount=$row->paidbtc;
    $contractid=$row->contractid;
$username=$this->db->query("select username,emailid,refuser from user where userid='$uid'")->row();
$reffer_userid=$username->refuser;
$mining_contract=$this->db->query("select userid from miningcontract where userid='".$reffer_userid."' and status='Active'")->result();
$lending_contract=$this->db->query("select userid from lendingcontract where userid='".$reffer_userid."' and status='Active'")->result();
$lending_contract_eth=$this->db->query("select userid from lendingcontract_eth where userid='".$reffer_userid."' and status='Active'")->result();
$lending_contract_fd=$this->db->query("select userid from lendingcontract_fd where userid='".$reffer_userid."' and status='Active'")->result();
$active_contract=count($mining_contract)+count($lending_contract)+count($lending_contract_eth)+count($lending_contract_fd);
if($row->referral_interest=='yes'){
if($active_contract>=1){
$refer_lend_calculate = (($row->amountusd * ($row->referral_percentage/ 100)) / 30);
$lending_history=array('userid'=>$username->refuser,'contract_id'=>$contractid,'referral_username'=>$row->username,'amount'=>$refer_lend_calculate,'created_date'=>date('Y-m-d'),'status'=>'unpaid');
$this->db->insert('lending_history',$lending_history);
}
}
}
echo 'Success';
exit;
}

public function onedayamount_credit(){
    echo 'Exit VIJAY';
    exit;
    $query=$this->db->query("SELECT t.userid,t.amountbtc,t.amountusd,t.datecreated,l.lending_percent FROM `lendingtb` t INNER JOIN lendingcontract l on l.contractid=t.purchaseid WHERE date(`datecreated`)>='2017-06-05' AND date(`datecreated`)<='2018-11-29'")->result();
    foreach($query as $val){
        $uid=$val->userid;
      $lendingcalculated = ($val->amountusd *($val->lending_percent / 100)) / 30;
      $this->db->query("update user_balances set lending_balance=lending_balance+'$lendingcalculated',modified_date1='".date('Y-m-d')."' where userid='$uid'");
      
    }
    echo 'Success';
 }


        // cron to update lending wallet balance for every payout 
        public function update_for_lending_payout_chronjob_tmnk(){
            $days=date('d');
            if($days==01){
            //exit;
             $date =date('Y-m-d', strtotime('last day of last month'));
             $year=date('Y',strtotime($date));
             $month=date('m',strtotime($date));
             $last_days=cal_days_in_month(CAL_GREGORIAN, date($month), date($year));
             $month_mid_days= date("Y-m-d", mktime(0, 0, 0, $month, 01, $year));
             $month_last_days= date("Y-m-d", mktime(0, 0, 0, $month, $last_days, $year));
             $users=$this->db->query("SELECT DISTINCT userid FROM lendingcontract")->result();
             $query1 = "update lendingop set status=1 where lendingdate>='" . $month_mid_days . "' and lendingdate<='" . $month_last_days . "'";
             $r = $this->db->query($query1);
             
             foreach($users as $row){
                      $uid=$row->userid;
                      $lendamount = 0;
                        // lending payout total
                        $this->db->select("SUM(debit_amount) AS lendingop");
                        $this->db->where('mlm_transaction.trans_date >=', $month_mid_days);
                        $this->db->where('mlm_transaction.trans_date <=', $month_last_days);
                        $this->db->where('mlm_transaction.userid', $uid);
                        $this->db->where('description', 'Lending Income');
                        $this->db->from('mlm_transaction');
                        $lendingtotal = $this->db->get()->result();
                        if (empty($lendingtotal[0]->lendingop)) {
                            $lendingtotal = 0.0;
                        } else {
                            $lendingtotal = $lendingtotal[0]->lendingop;
                        }      
                   $inserest = $lendamount + $lendingtotal;
                   $capital_method = $lendamount + $lendingtotal;
                   $updated_lend_balance = $inserest+$capital_method;
                   $date=date('Y-m-d');
                   $duplicate=$this->db->query("select *from lending_payout_history_btc where userid='".$uid."' and created_date='".$date."'")->result();
                   if(count($duplicate)==0){
                   $this->db->query("update user_balances set lending_balance = lending_balance + '$updated_lend_balance',modified_date1='".date('Y-m-d')."' where userid='$uid'");
                   $this->db->query("insert into lending_payout_history_btc(userid,btcamount,capital_amount,created_date)values('".$uid."','".$inserest."','".$capital_method."','".date('Y-m-d')."')");
                   }		
                }
             }
          echo 'SuccessFully Generated Payout';
          exit;
      }

 public function update_direct_payout_chronjob_tmnk(){
            $days=date('d');
            if($days==01){
             $date =date('Y-m-d', strtotime('last day of last month'));
             $year=date('Y',strtotime($date));
             $month=date('m',strtotime($date));
             $last_days=cal_days_in_month(CAL_GREGORIAN, date($month), date($year));
             $month_mid_days= date("Y-m-d", mktime(0, 0, 0, $month, 16, $year));
             $month_last_days= date("Y-m-d", mktime(0, 0, 0, $month, $last_days, $year));
             $this->db->select("mlm_transaction.userid");
             $this->db->where('mlm_transaction.trans_date >=', $month_mid_days);
             $this->db->where('mlm_transaction.trans_date <=', $month_last_days);
             $this->db->where('mlm_transaction.description', 'Direct Income');
             $this->db->distinct('mlm_transaction.userid');
             $this->db->from('mlm_transaction');
             $users = $this->db->get()->result();
       
               foreach($users as $row){
                      $uid=$row->userid;
                      $lendamount = 0;
                        // lending payout total
                        $this->db->select("SUM(debit_amount) AS lendingop");
                        $this->db->where('mlm_transaction.trans_date >=', $month_mid_days);
                        $this->db->where('mlm_transaction.trans_date <=', $month_last_days);
                        $this->db->where('mlm_transaction.userid', $uid);
                        $this->db->where('description', 'Direct Income');
                        $this->db->from('mlm_transaction');
                        $lendingtotal = $this->db->get()->result();
                   // echo $this->db->last_query();
                    //exit;
                        if (empty($lendingtotal[0]->lendingop)) {
                            $lendingtotal = 0.0;
                        } else {
                            $lendingtotal = $lendingtotal[0]->lendingop;
                        }
                    $lendamount = $lendamount + $lendingtotal;
                    $date=date('Y-m-d');
                    $duplicate=$this->db->query("select *from direct_payout_history where userid='".$uid."' and created_date='".$date."'")->result();
                    if(count($duplicate)==0){
					if(!empty($lendamount)){
					$this->db->query("insert into direct_payout_history(btcamount,userid,created_date)values('".$lendamount."','".$uid."','".date('Y-m-d')."')");
                   $this->db->query("update user_balances set btc_balance = btc_balance + '$lendamount' where userid='$uid'");
                   }
                }
              }
             } else if ($days==16) { 
             $date =date('Y-m-d', strtotime('first day of this month'));
             $year=date('Y',strtotime($date));
             $month=date('m',strtotime($date));
             $last_days=cal_days_in_month(CAL_GREGORIAN, date($month), date($year));
             $month_first_days= date("Y-m-d", mktime(0, 0, 0, $month, 01, $year));
             $month_mid_days= date("Y-m-d", mktime(0, 0, 0, $month,15, $year));
             $this->db->select("mlm_transaction.userid");
             $this->db->where('mlm_transaction.trans_date >=', $month_first_days);
             $this->db->where('mlm_transaction.trans_date <=', $month_mid_days);
             $this->db->where('mlm_transaction.description', 'Direct Income');
             $this->db->distinct('mlm_transaction.userid');
             $this->db->from('mlm_transaction');
              $users = $this->db->get()->result();
               foreach($users as $row){
                      $uid=$row->userid;
                      $lendamount = 0;
                        // lending payout total
                        $this->db->select("SUM(debit_amount) AS lendingop");
                        $this->db->where('mlm_transaction.trans_date >=', $month_first_days);
                        $this->db->where('mlm_transaction.trans_date <=', $month_mid_days);
                        $this->db->where('mlm_transaction.userid', $uid);
                        $this->db->where('description', 'Direct Income');
                        $this->db->from('mlm_transaction');
                        $lendingtotal = $this->db->get()->result();
						//echo $this->db->last_query();
						//exit;
                        if (empty($lendingtotal[0]->lendingop)) {
                            $lendingtotal = 0.0;
                        } else {
                            $lendingtotal = $lendingtotal[0]->lendingop;
                        }
                    $lendamount = $lendamount + $lendingtotal;
                    $date=date('Y-m-d');
                    $duplicate=$this->db->query("select *from direct_payout_history where userid='".$uid."' and created_date='".$date."'")->result();
                    if(count($duplicate)==0){
		if(!empty($lendamount)){
		$this->db->query("insert into direct_payout_history(btcamount,userid,created_date)values('".$lendamount."','".$uid."','".date('Y-m-d')."')");
                $this->db->query("update user_balances set btc_balance = btc_balance + '$lendamount' where userid='$uid'");
		  }
                 }
                }
              }
	     echo 'SuccessFully Generated Payout';
             exit;
            }
//================================================================================END==============================================================
// ===============================================================================Binary Payout Corn===============================================
 public function update_basic_binary_payout_chronjob_tmnk(){
            $days=date('d');
            if($days==01){
            $todays_date=date('Y-m-d');          
             $users=$this->db->query("select distinct u.userid,u.currency from user u inner join mlm_transaction m on m.userid=u.userid where m.trans_date='".$todays_date."' and m.description='Basic Binary Income' and u.status='Active'")->result();    
             foreach($users as $row){
                      $uid=$row->userid;
                      $lendamount = 0;
                        // lending payout total
                        $this->db->select("SUM(debit_amount) AS lendingop");
                        $this->db->where('mlm_transaction.trans_date',$todays_date);
                        $this->db->where('mlm_transaction.userid', $uid);
                        $this->db->where('description', 'Basic Binary Income');
                        $this->db->from('mlm_transaction');
                        $lendingtotal = $this->db->get()->result();
                        if (empty($lendingtotal[0]->lendingop)) {
                            $lendingtotal = 0.0;
                        } else {
                            $lendingtotal = $lendingtotal[0]->lendingop;
                        }
                      $lendamount = $lendamount + $lendingtotal;
                     $date=date('Y-m-d');
                     $duplicate=$this->db->query("select *from binary_payout_history where userid='".$uid."' and created_date='".$date."'")->result();
                     if(count($duplicate)==0){
					if(!empty($lendamount)){
					$this->db->query("insert into binary_payout_history(btcamount,userid,created_date)values('".$lendamount."','".$uid."','".date('Y-m-d')."')");
                   $this->db->query("update user_balances set btc_balance = btc_balance + '$lendamount',modified_date1='".date('Y-m-d')."' where userid='$uid'");
                   }
                }
              }
             } else if ($days==16) {
	     $todays_date=date('Y-m-d');  
             $users=$this->db->query("select distinct u.userid,u.currency from user u inner join mlm_transaction m on m.userid=u.userid where m.trans_date='$todays_date' and m.description='Basic Binary Income' and u.status='Active'")->result();
               foreach($users as $row){
                      $uid=$row->userid;
                      $lendamount = 0;
                        // lending payout total
                        $this->db->select("SUM(debit_amount) AS lendingop");
                        $this->db->where('mlm_transaction.trans_date',$todays_date);
                        $this->db->where('mlm_transaction.userid', $uid);
                        $this->db->where('description', 'Basic Binary Income');
                        $this->db->from('mlm_transaction');
                        $lendingtotal = $this->db->get()->result();
                        if (empty($lendingtotal[0]->lendingop)) {
                            $lendingtotal = 0.0;
                        } else {
                            $lendingtotal = $lendingtotal[0]->lendingop;
                        }
                    $lendamount = $lendamount + $lendingtotal;
                     $date=date('Y-m-d');
                     $duplicate=$this->db->query("select *from binary_payout_history where userid='".$uid."' and created_date='".$date."'")->result();
                     if(count($duplicate)==0){
					if(!empty($lendamount)){
					$this->db->query("insert into binary_payout_history(btcamount,userid,created_date)values('".$lendamount."','".$uid."','".date('Y-m-d')."')");
                   $this->db->query("update user_balances set btc_balance = btc_balance +'$lendamount',modified_date1='".date('Y-m-d')."' where userid='$uid'");
				   }
                 }
                }
              }
	      echo 'SuccessFully Generated Payout';
	      exit;
            }
//========================END==========================================================================================
          public function lending_contract_expired_for_tmnk_cronjob(){
                        $result=$this->db->query("select lc.contractid,DATE_FORMAT(lc.signdate,'%d, %b %y') 'signdate' ,DATE_FORMAT(DATE_ADD(lc.signdate,INTERVAL lc.contract_duration DAY),'%Y-%m-%d') 'expiredate',lc.paidbtc,lc.userid,lc.status,lc.contract_duration,lc.investment,lc.lending_percent,lt.amountusd,lt.currencysymbol,lt.amountbtc,lt.originalamount from lendingcontract as lc,lendingtb as lt where lc.contractid=lt.purchaseid and lc.status='Active'")->result();
                       foreach($result as $row){
                           $uid=$row->userid;
                           $originalamount=$row->originalamount;
                     $username=$this->db->query("select username,emailid from user where userid='$uid'")->row();
                    $contact_expiry_date=strtotime("+1 day", strtotime($row->expiredate));
                    $current_date = strtotime(date('Y-m-d'));
                    /*match equal date contract to the current date thtrough*/
                    if($contact_expiry_date==$current_date){
                     
                     /*insert mlm_transaction table entry*/
                     $capital_lending_history=array('userid'=>$uid,'amount'=>$originalamount,'expire_date'=>$row->expiredate,'contractId'=>$row->contractid);
                     $this->db->insert('capital_lending_history',$insert_mlm_transaction_array);
                     /*------------------end-------------------------*/
                     
                     /*lending contract status changed*/
                     $lendingcontract=array('status'=>'Expired');
                     $this->db->where('contractid',$row->contractid);
                     $this->db->update('lendingcontract',$lendingcontract);
                     /*-------------------end-----------------*/
                     /*lending contract expiry notification mails*/
                         $subject = "Growese -Lending TMNK Contract Expired Acknowledgment";
                         $messagebody.= "Hi,";
                         $messagebody.= "<b>" .$username->username."</b><br>Your contract is expired and your expired contract all amount is refund<br>";
                         $messagebody.= "!!! <b>Following Contract Expired Details</b><br><br>";
                         $messagebody.="<table border='1' style='border-collapse: collapse;padding:10px;text-align: left;width:50%;'>
                                         <tr><th>Contract ID</th><td>".$row->contractid."</td></tr>
                                         <tr><th>Original Amount</th><td>".$row->originalamount."</td></tr>
                                         <tr><th>Contract Expired Date</th><td>".$row->expiredate."</td></tr></table>";
                         $messagebody.= "Sit Back And Relax We Are Making You Gain The Best Benefits Out Of Your Investment.<br>";
                         $messagebody.= "<br><br>_________________________________________________________<br><br>";
                         $messagebody.= "Regards,<br>Growese Team.";
                         $messagebody.= "<br><br><i>Visit Us At <a href='http://www.growese.com'>www.growese.com</a></i>";
                         $this->GenericModel->sendEmail($username->emailid, "noreply@growese.com", $subject, $messagebody);    
                             }
                           }
                           echo 'status updated successfully';
                       }

                       public function activated_account_id(){
                    //    echo 'Hi';
                     //   exit;
                       $temp_acount=$this->db->query("select *from temp_acount")->result();
                      foreach($temp_acount as $row){
                         $accstatus=$row->accstatus;
                         $userid=$row->userid;
                         $this->db->query("update account set accstatus='$accstatus' where userid='$userid'");
                       }
                    echo 'Success';
                     } 
   
          // CONTACT SEND


}
