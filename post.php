<?php $action=$_GET['action']; 
include('config.php'); 
include('smsgateway.php');
if($action=='checking_refferal_id'){
     $referral_id=$_POST['refferal_id'];
    $referral_id1=mysql_query("select *from referral_users where refferral_id='$referral_id'");
     $valid_referral_id=mysql_query("select *from registration where referral_id='$referral_id'");
    $num_refferral=mysql_num_rows($referral_id1);
     $num_refferral_users=mysql_num_rows($valid_referral_id);
     //echo $num_refferral_users;
    // exit;
     if($num_refferral_users==0){
      echo 2; exit;   
     }elseif($num_refferral>=5){
      echo 3; exit;  
    }else{
        echo 1;
        exit;
    }
}
if($action=='userregistration'){
$name=mysql_real_escape_string($_POST['name']);
$mobile=mysql_real_escape_string($_POST['mobile']);
$email=mysql_real_escape_string($_POST['email']);
$password=mysql_real_escape_string($_POST['password']);
$city_name=mysql_real_escape_string($_POST['city_name']);
$state_name=mysql_real_escape_string($_POST['state_name']);
//$refferal_id=mysql_real_escape_string($_POST['refferal_id']);
$random = mt_rand(100000, 999999);
   $date=date('Y-m-d H:i:s');
$query=mysql_query("insert into registration(name,email,mobile,password,status,date,city_name,state_name)values('$name','$email','$mobile','$password','inactive','$date','$city_name','$state_name')");
$uid=mysql_insert_id();
$otp = mt_rand(100000, 999999);
 mysql_query("insert into vendors_otp(mobile,otp,userid,send_count)values('$mobile','$otp','$uid',1)");
 $message="One Time password is ".$otp.' Do not share this OTP to anyone for security reasons http://buymarttradeindia.com';
$url='http://login.bulksmsrocket.com/sendhttp.php?user=buymarttrade&password=Vijay@123&message='.urlencode($message).'&mobiles='.urlencode($mobile).'&sender=BUYMTI';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_exec($ch);
curl_close($ch); 
//mysql_query("insert into referral_users(refferral_id,userid,referral_point,created_date)values('$refferal_id','$uid','100','".date('Y-m-d')."')");
//mysql_query("insert into notification_master(notification_message,uid,status,notification_date)values('new account is create','$uid','active','$date')");
if($query==true){
$_SESSION['SUCESS']='Your Registration Successfully and Please enter OTP Here';
header("Location:vendor_register.php?verify_mobile=".base64_encode($mobile));
}else{
echo 'not insert';
}

}
// Vendor Registration
if($action=='Vendor_Registrations'){
$name=mysql_real_escape_string($_POST['name']);
$mobile=mysql_real_escape_string($_POST['mobile']);
$email=mysql_real_escape_string($_POST['email']);
$password=mysql_real_escape_string($_POST['password']);
$vendor_store=mysql_real_escape_string($_POST['vendor_store_name']);
$city_name=mysql_real_escape_string($_POST['city_name']);
$state_name=mysql_real_escape_string($_POST['state_name']);
$random = mt_rand(100000, 999999);
$date=date('Y-m-d H:i:s');
$otp = mt_rand(100000, 999999);

$query=mysql_query("insert into registration(name,email,mobile,password,status,date,vendor_store_name,user_type,city_name,state_name)values('$name','$email','$mobile','$password','inactive','$date','$vendor_store','Vendors','$city_name','$state_name')");
$userid=mysql_insert_id();
       mysql_query("insert into vendors_otp(mobile,otp,userid,send_count)values('$mobile','$otp','$userid',1)");
       
$message="One Time password is ".$otp.' Do not share this OTP to anyone for security reasons http://buymarttradeindia.com';
$url='http://login.bulksmsrocket.com/sendhttp.php?user=buymarttrade&password=Vijay@123&message='.urlencode($message).'&mobiles='.urlencode($mobile).'&sender=BUYMTI';
//echo $url;
//exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_exec($ch);
curl_close($ch); 

if($query==true){
$_SESSION['SUCESS']='Your Registration Successfully and Please enter OTP Here';
header("Location:vendor_register.php?verify_mobile=".base64_encode($mobile));
}else{
echo 'not insert';
}

}
if($action=='OTPverfication'){
    $otp=$_POST['otp'];
    $id_numbers= base64_decode($_POST['id_numbers']);
    $query=mysql_query("select *from vendors_otp where mobile='".$id_numbers."' and otp='$otp'");
$count=mysql_num_rows($query);
if($count>=1){
 mysql_query("update vendors_otp set verify_no='1' where otp='$otp' and mobile='$id_numbers'");
 mysql_query("update registration  set status='active' where mobile='$id_numbers'");
 $_SESSION['SUCESS']='Your Mobile number is verify successfully. please login here';
 echo 1; exit;   
}else{
 echo 2; exit;
}    
}
if($action=='ResentOTP'){
    $id_numbers=base64_decode($_GET['verify_mobile']);
    $vendors_otp=mysql_query("select otp,mobile,send_count from vendors_otp where mobile='$id_numbers'");
  $row=mysql_fetch_array($vendors_otp);
if($row['send_count']==2){
  $_SESSION['ERRORMSG']='You have resent OTP Password Limit is Exceed';
  header("Location:vendor_register.php?verify_mobile=".base64_encode($id_numbers));
 }else{
     mysql_query("update vendors_otp set send_count=send_count+1 where mobile='$id_numbers'");  
     
     $message='One Time password is '.$row['otp'].' Do not share this OTP to anyone for security reasons http://buymarttradeindia.com';
$url='http://login.bulksmsrocket.com/sendhttp.php?user=buymarttrade&password=Vijay@123&message='.urlencode($message).'&mobiles='.urlencode($id_numbers).'&sender=BUYMTI';
//echo $url;
//exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_exec($ch);
curl_close($ch); 
$_SESSION['SUCESS']='OTP has been send your registered mobile number';
header("Location:vendor_register.php?verify_mobile=".base64_encode($id_numbers));
 }  
}
if($action=='mobile_vendors_duplication'){
$mobile=mysql_real_escape_string($_POST['mobile']);
$query=mysql_query("select mobile from registration where mobile='".$mobile."'");
$count=mysql_num_rows($query);
if($count==0){
 echo 1;
 }else{
 echo 2;
 }
}



if($action=='duplicateemailaddress'){
$email=mysql_real_escape_string($_POST['email']);
$query=mysql_query("select email from registration where email='".$email."'");
$count=mysql_num_rows($query);
if($count==0){
 echo 1;
 }else{
 echo 2;
 }
}

if($action=="Loginusers"){
$emails=$_POST['email'];
$password = $_POST['Password'];
$pid=$_POST['pid'];
$login = mysql_query("select * from registration WHERE email = '$emails'  and password  = '$password' and  status = 'active'");
if(mysql_num_rows($login)>=1){
$data=mysql_fetch_array($login);
if($data['user_type']=='Users'){
 $_SESSION['ID']=$data['rid']; 
 $_SESSION['EMAIL']=$data['email'];
 
  
 if(!empty($pid)){
  header('Location:product-details.php?details='.$pid);
  }else{
 header('Location:index.php');
}
}elseif($data['user_type']=='Admin' || $data['user_type']=='Vendors' || $data['user_type']=='Employee'){
 $_SESSION['JOBPORTALADMIN'] = $data['email'];
 $_SESSION['ADMIN_USERNAME'] = $data['name'];
  $_SESSION['ADMIN_TYPE'] = $data['user_type'];
 $_SESSION['STORE_NAME'] = $data['vendor_store_name'];
 $_SESSION['ADMIN_ID'] = $data['rid'];
 header('Location:admin/index.php');   
}
}else{
    $_SESSION['ERROR']='Email ID or Password Incorrect?';
    header('Location:login.php');
 } 
} 

if($action=='add_to_cart'){
$pid=$_POST['id'];
$uid=$_SESSION['ID'];
$date=date('Y-m-d H:i:s');
$product=mysql_query("select *from product_details where pid='$pid'");
$cart=mysql_query("select *from cart where uid='$uid'");
$cart1=mysql_query("select *from cart where uid='$uid' and pid='$pid'");
$c=mysql_fetch_array($cart1);
$p=mysql_fetch_array($product);
if(mysql_num_rows($cart)>=20){
echo 5;
}else if($pid==$c['pid']){
 echo 1;
 }
elseif($p['product_quantity']>=1){
  mysql_query("insert into cart(pid,uid,quantity,date)values('$pid','$uid','1','$date')");
  echo 2;
  }else{
   echo 3; 
   }
}
if($action=='view_itmes_counts'){
$uid=$_SESSION['ID'];
$cart=mysql_query("select *from cart where uid='$uid'");
$count=mysql_num_rows($cart);
echo $count;
}

if($action=='delete_cart_items'){
$cid=$_POST['id'];
$query=mysql_query("delete from cart where cid='$cid'");
if($query==true){
echo 1;
  $_SESSION['Success']='Cart Item Deleted Successfully';
exit;
}else{
echo 2;
exit;
}
}

if($action=='cart_update_yours'){
$pid=$_POST['pid'];
$uid=$_SESSION['ID'];
$qty=$_POST['qty'];
$cid=$_POST['id'];
$product=mysql_query("select *from product_details where pid='$pid'");
$cart=mysql_query("select *from cart where uid='$uid'");
$c=mysql_fetch_array($cart);
$p=mysql_fetch_array($product);
if($qty<=$p['product_quantity']){
  mysql_query("update cart set quantity='$qty' where cid='$cid'");
  $_SESSION['Success']='Cart Updated Successfully..';
  echo 1;
  exit;
 }else{
   echo 3; 
   exit;
   }
}
if($action=='add_to_cart_details_pages'){
$pid=$_POST['id'];
$uid=$_SESSION['ID'];
$qty=$_POST['qty'];
$date=date('Y-m-d H:i:s');
$product=mysql_query("select *from product_details where pid='$pid'");
$cart=mysql_query("select *from cart where uid='$uid'");
$cart1=mysql_query("select *from cart where uid='$uid' and pid='$pid'");
$c=mysql_fetch_array($cart1);
$p=mysql_fetch_array($product);
if(mysql_num_rows($cart)>=20){
echo 5;
}else if($pid==$c['pid']){
 echo 1;
 }
elseif($p['product_quantity']>=1){
  mysql_query("insert into cart(pid,uid,quantity,date)values('$pid','$uid','$qty','$date')");
  echo 2;
  }else{
   echo 3; 
   }
}
if($action=='placemyordersdetails'){

		$uid=$_SESSION['ID'];
		$unique = 'ID-'.mt_rand( 100000, 999999);
		$date=date('Y-m-d H:i:s');
		$name=mysql_real_escape_string($_POST['name']);
		$mobile_no=mysql_real_escape_string($_POST['mobile_no']);
		$pincode=mysql_real_escape_string($_POST['pincode']);
		$state=mysql_real_escape_string($_POST['state']);
		$city=mysql_real_escape_string($_POST['city']);
		$address_details=mysql_real_escape_string($_POST['address_details']);
		$address=mysql_query("select *from address_details where uid='$uid'");
		//$query='';
		$cart=mysql_query("select *from cart where uid='$uid'"); 
		$cart12=mysql_query("select *from cart where uid='$uid'"); 
		$pincodeproduct=mysql_query("select pincode from pincode_master where pincode='$pincode'");
		if(mysql_num_rows($pincodeproduct)==0){
		echo 5;
		exit;
		}else{
		if(mysql_num_rows($address)==0){
		mysql_query("insert into address_details(name,address,city,state,pincode,mobile_no,uid)values('$name','$address_details','$city','$state','$pincode','$mobile_no',' $uid')");
		}else{
		mysql_query("update address_details set name='$name',address='$address_details',city='$city',state='$state',pincode='$pincode',mobile_no='$mobile_no' where uid='$uid'");
		}  
		
		while($c=mysql_fetch_array($cart)){
		$product=mysql_query("select *from product_details where pid='".$c['pid']."'");
		$p=mysql_fetch_array($product);
		if($c['quantity']<=$p['product_quantity']){
		}else{
		 echo 3; 
		 exit();
		 }
		}
		$query=mysql_query("insert into orders_details(uid,order_id,date,status)values('$uid','$unique','$date','send')");
		$orderid=mysql_insert_id();
		mysql_query("insert into notification_master(notification_message,oid,uid,status,notification_date)values('New orders is purchase','$orderid','$uid','active','$date')");
		while($ca=mysql_fetch_array($cart12)){
		$product=mysql_query("select *from product_details where pid='".$ca['pid']."'");
		$p=mysql_fetch_array($product);
		$qty=$p['product_quantity']-$ca['quantity'];
		$query=mysql_query("insert into order_items_details(oid,uid,quantity,pid)values('$orderid','$uid','".$ca['quantity']."','".$ca['pid']."')");
		mysql_query("update product_details set product_quantity='$qty' where pid='".$ca['pid']."'");
		mysql_query("delete from cart where pid='".$ca['pid']."' and uid='".$uid."'");
		}
		if($query==true){
		echo 2;
		$_SESSION['SUCESSMSG1']='Your Order Placed Successfully..';
		exit;
	}
}
}
if($action=='product_details_get_orders'){
        $uid=$_SESSION['ID'];
		$unique = 'ID-'.mt_rand( 100000, 999999);
		$date=date('Y-m-d H:i:s');
		$name=mysql_real_escape_string($_POST['name']);
		$mobile_no=mysql_real_escape_string($_POST['mobile_no']);
		$pincode=mysql_real_escape_string($_POST['pincode']);
		$state=mysql_real_escape_string($_POST['state']);
		$city=mysql_real_escape_string($_POST['city']);
		$address_details=mysql_real_escape_string($_POST['address_details']);
		$qty1=$_POST['qty'];
		$pid=$_POST['pid'];
		$product=mysql_query("select *from product_details where pid='".$pid."'");
		$p=mysql_fetch_array($product);
		$pincodeproduct=mysql_query("select pincode from pincode_master where pincode='$pincode'");
		if(mysql_num_rows($pincodeproduct)==0){
		echo 5;
		exit;
		}else{
		if($qty1<=$p['product_quantity']){
		mysql_query("insert into orders_details(uid,order_id,date,status)values('$uid','$unique','$date','send')");
		$orderid=mysql_insert_id();
		mysql_query("insert into notification_master(notification_message,oid,uid,status,notification_date)values('New orders is purchase','$orderid','$uid','active','$date')");
		$address=mysql_query("select *from address_details where uid='$uid'");
		if(mysql_num_rows($address)==0){
		mysql_query("insert into address_details(name,address,city,state,pincode,mobile_no,uid)values('$name','$address_details','$city','$state','$pincode','$mobile_no',' $uid')");
		}else{
		mysql_query("update address_details set name='$name',address='$address_details',city='$city',state='$state',pincode='$pincode',mobile_no='$mobile_no' where uid='$uid'");
		}
		$qty=$p['product_quantity']-$qty1;
		$query=mysql_query("insert into order_items_details(oid,uid,quantity,pid)values('$orderid','$uid','".$qty1."','".$pid."')");
		mysql_query("update product_details set product_quantity='$qty' where pid='".$pid."'");
		echo 1;
		$_SESSION['SUCESSMSG1']='Your Order Placed Successfully..';
        }else{
	    echo 2;
	   }
	}
}
if($action=='OrdersCancelations'){
$oid=$_POST['oid'];
$uid=$_SESSION['ID'];
$cancelation_reason=$_POST['cancelation_reason'];
$comment=mysql_real_escape_string($_POST['comment']);
$ordersquery=mysql_query("select status from orders_details where oid='$oid' and uid='$uid'");
$orders=mysql_fetch_array($ordersquery);
$date=date('Y-m-d H:i:s');
if($orders['status']!='recived'){
   mysql_query("update orders_details set cancelation_date='$date',status='cancel' where oid='$oid'");
  $ordersitems=mysql_query("select *from order_items_details where oid='$oid' and uid='$uid'");
  while($items=mysql_fetch_array($ordersitems)){
   $products=mysql_query("select product_quantity from product_details where pid='".$items['pid']."'"); 
   $p=mysql_fetch_array($products);
   $qty=$p['product_quantity']+$items['quantity']; 
   mysql_query("update product_details set product_quantity='$qty' where pid='".$items['pid']."'");
   }
   mysql_query("insert into orders_cancelation(oid,cancelation_reason,comment,uid,status,date)values('$oid','$cancelation_reason','$comment','$uid','active','$date')");
   mysql_query("insert into notification_master(notification_message,oid,uid,status,notification_date)values('order is cancel','$oid','$uid','active','$date')");
   echo 1;
  $_SESSION['SUCESSMSG1']='Your Order Cancelations Successfully..';
  exit;
 }else{
   echo 2;
 }
}
if($action=='add_product_your_wish_list'){
$id=$_POST['pid'];
$uid=$_SESSION['ID'];
$date=date('Y-m-d H:i:s');
$wishlist=mysql_query("select *from wishlist where pid='$id' and uid='$uid'");
$wishlistlesss=mysql_query("select *from wishlist where  uid='$uid'");
if(mysql_num_rows($wishlistlesss)>100){
echo 0;
exit;
}elseif(mysql_num_rows($wishlist)==0){
mysql_query("insert into wishlist(pid,uid,date,status)values('$id','$uid','$date','active')");
echo 1;
exit;
 }else{
 echo 2;  
 exit;
}
}
if($action=='wilistitemdelete'){
$wid=$_POST['id'];
$query=mysql_query("delete from wishlist where wid='$wid'");
if($query==true){
//header("Location:wishlist.php");
 $_SESSION['Success']='Wishlist Item Deleted Successfully..'; 
 echo 1;
}else{
  echo 'Not Deleted Records';
}
}
if($action=='ProductReview'){
  $pid=$_POST['productid'];
  $comment=mysql_real_escape_string($_POST['comment']);
  $date=date('Y-m-d H:i:s');
  $uid=$_SESSION['ID'];
  $query=mysql_query("insert into product_review(uid,pid,description,date,status)values('$uid','$pid','$comment','$date','inactive')");
  mysql_query("insert into notification_master(notification_message,oid,uid,status,notification_date)values('add review on product','$pid','$uid','active','$date')");
  if($query==true){
   $_SESSION['Success']='Review Added Successfully..'; 
   echo 1; exit;
   }else{
     echo 2; exit;
   }
  }
  if($action=='change-password'){
$oldPassword=$_POST['oldPassword'];
$newPassword=$_POST['newPassword'];
 $tid=$_SESSION['ID'];
 $checkpassword=mysql_query("select password from registration where rid='$tid'");
 $get_rows=mysql_fetch_array($checkpassword);
 if($oldPassword==$get_rows['password']){
 mysql_query("update registration set password='$newPassword' where rid='$tid'");
// $_SESSION['success']='Password Changed Successfully';
 echo 1;
 exit;
 }else{
 echo 2;
 exit;
 }
}
if($action=='duplicateemailaddressupdate'){
$email=mysql_real_escape_string($_POST['email']);
$query=mysql_query("select email from registration where email='".$email."' and rid='".$_SESSION['ID']."'");
$others=mysql_query("select email from registration where email='".$email."'");
$rows=mysql_fetch_array($query);
$count=mysql_num_rows($others);
  if($email==$rows['email']){
   echo 0;
  }elseif($count==0){
   echo 1;
 }else{
 echo 2;
 }
}
if($action=='UpdateMyProfiles'){
 if(!empty($_FILES["profile_images"]["name"])){
	 $string=explode('.',$_FILES["profile_images"]["name"]);
	   $profile_images =  $string[0].'_'. rand(0, 10000) . '.' . end(explode(".", $_FILES["profile_images"]["name"]));
		move_uploaded_file($_FILES["profile_images"]["tmp_name"], "images/profile/" . $profile_images);
		}else{$profile_images=mysql_real_escape_string($_POST['defaultimage']); }
		$name=mysql_real_escape_string($_POST['name']);
		$email=mysql_real_escape_string($_POST['email']);
		$mobile=mysql_real_escape_string($_POST['mobile']);
		$id=$_SESSION['ID'];
                $city_name=mysql_real_escape_string($_POST['city_name']);
                $state_name=mysql_real_escape_string($_POST['state_name']);
                
		$query=mysql_query("update  registration set name='$name',email='$email',city_name='$city_name',state_name='$state_name',mobile='$mobile',profile_picture='$profile_images' where rid='$id'");
	if($query==true){
	header("Location:my-profile.php");
	$_SESSION['Success']='Your Profile Update Successfully...'; 
	}else{
	echo 'Not Deleted Records';
	}
		
}

if($action=="SendMails"){
$body='';
$body1='';
$message=$_POST['message'];
$name=$_POST['name'];
$email=$_POST['email'];
$Mobile=$_POST['Mobile'];
$body='<!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Saibroking</title>
    <style>
  
      img {
        border: none;
        -ms-interpolation-mode: bicubic;
        max-width: 100%; }

      body {
        font-family: sans-serif;
        -webkit-font-smoothing: antialiased;
        font-size: 14px;
        line-height: 1.4;
        margin: 0;
        padding: 0; 
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%; }

      table {
        border-collapse: separate;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
        width: 100%; }
        table td {
          font-family: sans-serif;
          font-size: 14px;
          vertical-align: top; }


      .body {
        width: 100%; }

      
      .container {
        display: block;
        Margin: 0 auto !important;
        padding: 10px;
        /*width: 1000px;*/
		    width: 90%; }

     
      .content {
        box-sizing: border-box;
        display: block;
        Margin: 0 auto;
        width: 90%;
        padding: 10px;
		border: 1px solid #0400f3;
		border-radius: 14px; }

    
      .main {
        background: #fff;
        border-radius: 3px;
        width: 100%; }

      .wrapper {
        box-sizing: border-box;
        padding: 20px; }

      .footer {
        clear: both;
        padding-top: 10px;
        width: 100%; }
        .footer td,
        .footer p,
        .footer span,
        .footer a {
          color:#1d1c1c;
          font-size: 12px;
          text-align: center; }

     
      h1,
      h2,
      h3,
      h4 {
        color: #000000;
        font-family: sans-serif;
        font-weight: 400;
        line-height: 1.4;
        margin: 0;
        Margin-bottom: 30px; }

      h1 {
        font-size: 35px;
        font-weight: 300;
        text-align: center;
        text-transform: capitalize; }

      p,
      ul,
      ol {
        font-family: sans-serif;
        font-size: 14px;
        font-weight: normal;
        margin: 0;
        Margin-bottom: 15px; }
        p li,
        ul li,
        ol li {
          list-style-position: inside;
          margin-left: 5px; }

      a {
        color: #3498db;
        text-decoration: underline; }

   
      .btn {
        box-sizing: border-box;
        width: 100%; }
        .btn > tbody > tr > td {
          padding-bottom: 15px; }
        .btn table {
          width: auto; }
        .btn table td {
          background-color: #ffffff;
          border-radius: 5px;
          }
        .btn a {
          background-color: #ffffff;
          border: solid 1px #3498db;
          border-radius: 5px;
          box-sizing: border-box;
          color: #3498db;
          cursor: pointer;
          display: inline-block;
          font-size: 14px;
          font-weight: bold;
          margin: 0;
          padding: 12px 25px;
          text-decoration: none;
          text-transform: capitalize; }


      .btn-primary a {
        background-color: #3498db;
        border-color: #3498db;
        color: #ffffff; }

   
      .last {
        margin-bottom: 0; }

      .first {
        margin-top: 0; }

      .align-center {
        text-align: center; }

      .align-right {
        text-align: right; }

      .align-left {
        text-align: left; }

      .clear {
        clear: both; }

      .mt0 {
        margin-top: 0; }

      .mb0 {
        margin-bottom: 0; }

      .preheader {
        color: transparent;
        display: none;
        height: 0;
        max-height: 0;
        max-width: 0;
        opacity: 0;
        overflow: hidden;
        mso-hide: all;
        visibility: hidden;
        width: 0; }

      .powered-by a {
        text-decoration: none; }

      hr {
        border: 0;
        border-bottom: 1px solid #f6f6f6;
        Margin: 20px 0; }

 
      @media only screen and (max-width: 620px) {
        table[class=body] h1 {
          font-size: 28px !important;
          margin-bottom: 10px !important; }
        table[class=body] p,
        table[class=body] ul,
        table[class=body] ol,
        table[class=body] td,
        table[class=body] span,
        table[class=body] a {
          font-size: 16px !important; }
        table[class=body] .wrapper,
        table[class=body] .article {
          padding: 10px !important; }
        table[class=body] .content {
          padding: 0 !important; }
        table[class=body] .container {
          padding: 0 !important;
          width: 100% !important; }
        table[class=body] .main {
          border-left-width: 0 !important;
          border-radius: 0 !important;
          border-right-width: 0 !important; }
        table[class=body] .btn table {
          width: 100% !important; }
        table[class=body] .btn a {
          width: 100% !important; }
        table[class=body] .img-responsive {
          height: auto !important;
          max-width: 100% !important;
          width: auto !important; }}

   
      @media all {
        .ExternalClass {
          width: 100%; }
        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
          line-height: 100%; }
        .apple-link a {
          color: inherit !important;
          font-family: inherit !important;
          font-size: inherit !important;
          font-weight: inherit !important;
          line-height: inherit !important;
          text-decoration: none !important; } 
        .btn-primary a:hover {
          background-color: #34495e !important;
          border-color: #34495e !important; } }
		  th{text-align: left;}

    </style>
  </head>
  <body class="">
    <table border="0" cellpadding="0" cellspacing="0" class="body">
      <tr>
        <td>&nbsp;</td>
        <td class="container">
          <div class="content">
            <table class="main">

            
              <tr>
                <td class="wrapper">
                  <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>
                        <p>Welcome To Prasad Thakar & Associates,</p>
						
                        <p>'.$name.' Send Feedback.</p>
						<hr style=" border-bottom: 1px solid #0400f3;">
						<p><strong>Following Users Details,</strong></p>
                        <table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
                          <tbody>
                            <tr>
                              <td align="left">
                                <table border="0" cellpadding="0" cellspacing="0">
                                  <tbody>
                                    <tr>
                                     <th>Users Name:&nbsp;&nbsp;</th><td>'.$name.'</td>
                                    </tr>
									
									<tr>
                                     <th>Users Email:&nbsp;&nbsp;</th><td>'.$email.'</td>
                                    </tr>
									<tr>
                                     <th>Mobile:&nbsp;&nbsp;</th><td>'.$Mobile.'</td>
                                    </tr>
									<tr>
                                     <th>Users Feedback:&nbsp;&nbsp;</th><td>'.$message.'</td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                      
                  </table>
                </td>
              </tr>

              </table>

       
            <div class="footer">
              <table border="0" cellpadding="0" cellspacing="0">
			
                <tr>
				 <td class="content-block">
				 <span class="content-block">
				<h1 style="color: #043971;font-size: 3em;font-weight: 400;font-family:cursive;">Ecommerece</h1></span>
				 </td>
				    <td> <span class="content-block">
				<strong>xyz

Telephone: +91 xxx xxx xxxx / <br />
E-mail:xyx@gmail.com 
			 <br>
				<a href="http://globallianz.in/ecommerece/">http://globallianz.in/ecommerece/</a>
				 </span></td>
                  <td class="content-block">
                    <span class="apple-link"><br><br>
					<a href="#" target="_blank"><img src="http://www.wiu.edu/cas/images/facebook_circle_color-512.png" style="height:30px; width:30px;"></a>
					<a  target="_blank" href="#"><img src="http://yolna.com/wp-content/uploads/2015/12/twitter-circle-logo.png" style="height:30px;width:30px;"></a>&nbsp;
					<a href="#" target="_blank"><img src="http://icons.iconarchive.com/icons/martz90/circle/512/google-plus-icon.png" style="height:26px; width:26px;"></a>&nbsp;&nbsp;
					<a href="#" target="_blank"><img src="https://www.stuorg.iastate.edu/uploads/org-site/ckuploads/1419/Social%20Media%20Icons/youtube.png" style="width:27px; height:27px;"></a>
					</span>
                 
                  </td>
                </tr>
               
              </table>
            </div></div>
        </td>
        <td>&nbsp;</td>
      </tr>
    </table>
  </body>
</html>';


$body1='<!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Saibroking</title>
    <style>
  
      img {
        border: none;
        -ms-interpolation-mode: bicubic;
        max-width: 100%; }

      body {
        font-family: sans-serif;
        -webkit-font-smoothing: antialiased;
        font-size: 14px;
        line-height: 1.4;
        margin: 0;
        padding: 0; 
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%; }

      table {
        border-collapse: separate;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
        width: 100%; }
        table td {
          font-family: sans-serif;
          font-size: 14px;
          vertical-align: top; }


      .body {
        width: 100%; }

      
      .container {
        display: block;
        Margin: 0 auto !important;
        padding: 10px;
        /*width: 1000px;*/
		    width: 90%; }

     
      .content {
        box-sizing: border-box;
        display: block;
        Margin: 0 auto;
        width: 90%;
        padding: 10px;
		border: 1px solid #0400f3;
		border-radius: 14px; }

    
      .main {
        background: #fff;
        border-radius: 3px;
        width: 100%; }

      .wrapper {
        box-sizing: border-box;
        padding: 20px; }

      .footer {
        clear: both;
        padding-top: 10px;
        width: 100%; }
        .footer td,
        .footer p,
        .footer span,
        .footer a {
          color:#1d1c1c;
          font-size: 12px;
          text-align: center; }

     
      h1,
      h2,
      h3,
      h4 {
        color: #000000;
        font-family: sans-serif;
        font-weight: 400;
        line-height: 1.4;
        margin: 0;
        Margin-bottom: 30px; }

      h1 {
        font-size: 35px;
        font-weight: 300;
        text-align: center;
        text-transform: capitalize; }

      p,
      ul,
      ol {
        font-family: sans-serif;
        font-size: 14px;
        font-weight: normal;
        margin: 0;
        Margin-bottom: 15px; }
        p li,
        ul li,
        ol li {
          list-style-position: inside;
          margin-left: 5px; }

      a {
        color: #3498db;
        text-decoration: underline; }

   
      .btn {
        box-sizing: border-box;
        width: 100%; }
        .btn > tbody > tr > td {
          padding-bottom: 15px; }
        .btn table {
          width: auto; }
        .btn table td {
          background-color: #ffffff;
          border-radius: 5px;
          }
        .btn a {
          background-color: #ffffff;
          border: solid 1px #3498db;
          border-radius: 5px;
          box-sizing: border-box;
          color: #3498db;
          cursor: pointer;
          display: inline-block;
          font-size: 14px;
          font-weight: bold;
          margin: 0;
          padding: 12px 25px;
          text-decoration: none;
          text-transform: capitalize; }


      .btn-primary a {
        background-color: #3498db;
        border-color: #3498db;
        color: #ffffff; }

   
      .last {
        margin-bottom: 0; }

      .first {
        margin-top: 0; }

      .align-center {
        text-align: center; }

      .align-right {
        text-align: right; }

      .align-left {
        text-align: left; }

      .clear {
        clear: both; }

      .mt0 {
        margin-top: 0; }

      .mb0 {
        margin-bottom: 0; }

      .preheader {
        color: transparent;
        display: none;
        height: 0;
        max-height: 0;
        max-width: 0;
        opacity: 0;
        overflow: hidden;
        mso-hide: all;
        visibility: hidden;
        width: 0; }

      .powered-by a {
        text-decoration: none; }

      hr {
        border: 0;
        border-bottom: 1px solid #f6f6f6;
        Margin: 20px 0; }

 
      @media only screen and (max-width: 620px) {
        table[class=body] h1 {
          font-size: 28px !important;
          margin-bottom: 10px !important; }
        table[class=body] p,
        table[class=body] ul,
        table[class=body] ol,
        table[class=body] td,
        table[class=body] span,
        table[class=body] a {
          font-size: 16px !important; }
        table[class=body] .wrapper,
        table[class=body] .article {
          padding: 10px !important; }
        table[class=body] .content {
          padding: 0 !important; }
        table[class=body] .container {
          padding: 0 !important;
          width: 100% !important; }
        table[class=body] .main {
          border-left-width: 0 !important;
          border-radius: 0 !important;
          border-right-width: 0 !important; }
        table[class=body] .btn table {
          width: 100% !important; }
        table[class=body] .btn a {
          width: 100% !important; }
        table[class=body] .img-responsive {
          height: auto !important;
          max-width: 100% !important;
          width: auto !important; }}

   
      @media all {
        .ExternalClass {
          width: 100%; }
        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
          line-height: 100%; }
        .apple-link a {
          color: inherit !important;
          font-family: inherit !important;
          font-size: inherit !important;
          font-weight: inherit !important;
          line-height: inherit !important;
          text-decoration: none !important; } 
        .btn-primary a:hover {
          background-color: #34495e !important;
          border-color: #34495e !important; } }
		  th{text-align: left;}

    </style>
  </head>
  <body class="">
    <table border="0" cellpadding="0" cellspacing="0" class="body">
      <tr>
        <td>&nbsp;</td>
        <td class="container">
          <div class="content">
            <table class="main">

            
              <tr>
                <td class="wrapper">
                  <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>
                   
                        <p>Dear '.$name.'<br>Your Message Send Successfully we will contact soon</p>
						<hr style=" border-bottom: 1px solid #0400f3;">
                        <table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
                          <tbody>
                          
                                  </tbody>
                                </table>
                              </td>
                      
                  </table>
                </td>
              </tr>

              </table>

       
           <div class="footer">
              <table border="0" cellpadding="0" cellspacing="0">
			
                <tr>
				 <td class="content-block">
				 <span class="content-block">
				<h1 style="color: #043971;font-size: 3em;font-weight: 400;font-family:cursive;">Ecommerece</h1></span>
				 </td>
				    <td> <span class="content-block">
				<strong>xyz

Telephone: +91 xxx xxx xxxx / <br />
E-mail:xyx@gmail.com 
			 <br>
				<a href="http://globallianz.in/ecommerece/">http://globallianz.in/ecommerece/</a>
				 </span></td>
                  <td class="content-block">
                    <span class="apple-link"><br><br>
					<a href="#" target="_blank"><img src="http://www.wiu.edu/cas/images/facebook_circle_color-512.png" style="height:30px; width:30px;"></a>
					<a  target="_blank" href="#"><img src="http://yolna.com/wp-content/uploads/2015/12/twitter-circle-logo.png" style="height:30px;width:30px;"></a>&nbsp;
					<a href="#" target="_blank"><img src="http://icons.iconarchive.com/icons/martz90/circle/512/google-plus-icon.png" style="height:26px; width:26px;"></a>&nbsp;&nbsp;
					<a href="#" target="_blank"><img src="https://www.stuorg.iastate.edu/uploads/org-site/ckuploads/1419/Social%20Media%20Icons/youtube.png" style="width:27px; height:27px;"></a>
					</span>
                 
                  </td>
                </tr>
               
              </table>
            </div></div>
        </td>
        <td>&nbsp;</td>
      </tr>
    </table>
  </body>
</html>';
           $headers = "MIME-Version: 1.0" . "\r\n";
          $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
          $headers .= 'From:Ecommerece<info@globallianz.in>' . "\r\n";
		  
		  $headers1 = "MIME-Version: 1.0" . "\r\n";
          $headers1 .= "Content-type:text/html;charset=UTF-8" . "\r\n";
          $headers1 .= 'From:Ecommerece<info@globallianz.in>' . "\r\n";
		  $mail_to='info@globallianz.in';
		  $mail_subject1="Feedback sent for Ecommerece";
		  $mail_subject='Ecommerece Contact Us Users';
		   $mail_sent = mail($mail_to,$mail_subject,$body,$headers);
		  $mail_sent1 = mail($email,$mail_subject1,$body1,$headers1);
		 if($mail_sent==true && $mail_sent1==true)
		    {
		   $_SESSION['success']='Your Message Send Successfully';
		   header("Location:contact.php");
		 }else{
		 echo "Mail Not Send Successfuly";
		 exit;
		 }
}

if($action=='InsertMyProduct'){
 $myid = $_POST['myid'];
 $myipd='U'.mt_rand(100000,999999);
 $ipaddress='';
 if(isset($_SESSION['RemoteIpAddress'])){
 $ipaddress=$_SESSION['RemoteIpAddress'];
 }else{
 $ipaddress=$myipd;
 $_SESSION['RemoteIpAddress']= $myipd;
 }
 $compaireproducts=mysql_query("select pid from compaire_product where ip_address='$ipaddress'");
 if(mysql_num_rows($compaireproducts)>=1){
 $comp=mysql_fetch_array($compaireproducts);
 $oldproducts=mysql_query("select category_id from product_details where pid='".$comp['pid']."'");
 $o=mysql_fetch_array($oldproducts);
 $currentproducts=mysql_query("select category_id from product_details where pid='$myid'");
 $p=mysql_fetch_array($currentproducts);
 if($o['category_id']==$p['category_id']){
 $query123=mysql_query("select *from compaire_product where ip_address='$ipaddress'");
$query=mysql_query("select *from compaire_product where ip_address='$ipaddress' and pid='$myid'");
if(mysql_num_rows($query)==0 && mysql_num_rows($query123)<3){
$query11=mysql_query("insert into compaire_product(pid,ip_address)values('$myid.','$ipaddress')");
echo 1;
exit;
}else{
echo 2; 
exit; 
 }
 }else{
 echo 4;
 exit;
 }
 }else{
  //  $ipaddress='U'.mt_rand(100000,999999);
  $query123=mysql_query("select *from compaire_product where ip_address='$ipaddress'");
$query=mysql_query("select *from compaire_product where ip_address='$ipaddress' and pid='$myid'");
if(mysql_num_rows($query)==0 && mysql_num_rows($query123)<3){
$query11=mysql_query("insert into compaire_product(pid,ip_address)values('$myid.','$ipaddress')");
echo 1;
exit;
}else{
echo 2; 
exit; 
 }
}
}
if($action=="DeletemyProduct"){
  $myid = $_POST['myid'];
 $ipaddress= $_SESSION['RemoteIpAddress'];
 $query=mysql_query("delete from compaire_product where pid='$myid' and ip_address='$ipaddress'");
if($query==true){
echo 1;
}else{
echo 2;  
}
}
if($action=="DeleteAllProduct"){
 $ipaddress=$_SESSION['RemoteIpAddress'];
 $query=mysql_query("delete from compaire_product where ip_address='$ipaddress'");
 unset($_SESSION['RemoteIpAddress']);
 if($query==true){
echo 1;
}else{
echo 2;  
}
}
if($action=='GetMyProduct'){
 $ipaddress=$_SESSION['RemoteIpAddress'];
 $query=mysql_query("select *from compaire_product where ip_address='$ipaddress'");
 $data='<div class="mynewdiv">';
 while($rows=mysql_fetch_array($query)){
 $product=mysql_query("select *from product_details where pid='".$rows['pid']."'"); $p=mysql_fetch_array($product);
  $images=mysql_query("select *from product_images where pid='".$rows['pid']."' order by piid asc limit 1"); $img=mysql_fetch_array($images);
  $data.='<div class="image-div">
         <img class="img-thumbnail myimage" src="admin/images/product/'.$img['product_path'].'" style="height:100px;height:100px;width:138px;">
		  <button type="button" class="btn btn-danger mybuttons" title="Compare Product" name="cbox"  onClick="temimagesdelete('.$rows['pid'].')"><i class="glyphicon glyphicon-remove" style="color:#FFFFFF;"></i></button>
		 </div><h6><i class="fa fa-incr  fa-1x" aria-hidden="true"></i>'.$p['discount_price'].'Rs
		'.substr($p['title'],0,12).'</h6>';
 }
  if(mysql_num_rows($query)>=2){
  $expireAfter = 30;
  if(isset($_SESSION['last_action'])){
    $secondsInactive = time() - $_SESSION['last_action'];
   }
	  $data.='<input type="submit" class="btn btn-primary customebutton" style="font-size: 13px;" value="Compare" onClick="return compaireviewsprices123()">';
	 
}

 $data.='&nbsp;&nbsp;<input type="button" onClick="clearall()" style="font-size: 13px;" class="btn btn-danger" value="Clear"></div>';
 echo $data;
}

if($action=='DeleteImages'){
$id=$_POST['id'];
$query=mysql_query("delete from compaire_product where pid='$id'");
 $ipaddress=$_SESSION['RemoteIpAddress'];
 $myquery=mysql_query("select *from compaire_product where ip_address='$ipaddress'");
 if(mysql_num_rows($myquery)==0){
 echo 3;
 exit;
}elseif($query==true){
echo 1;exit;
 }else{
 echo 2;exit;
 }
}


if($action=="EmailsSubscriptions"){
$body='';
$body1='';
$email=$_POST['email'];
$body='<!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Saibroking</title>
    <style>
  
      img {
        border: none;
        -ms-interpolation-mode: bicubic;
        max-width: 100%; }

      body {
        font-family: sans-serif;
        -webkit-font-smoothing: antialiased;
        font-size: 14px;
        line-height: 1.4;
        margin: 0;
        padding: 0; 
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%; }

      table {
        border-collapse: separate;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
        width: 100%; }
        table td {
          font-family: sans-serif;
          font-size: 14px;
          vertical-align: top; }


      .body {
        width: 100%; }

      
      .container {
        display: block;
        Margin: 0 auto !important;
        padding: 10px;
        /*width: 1000px;*/
		    width: 90%; }

     
      .content {
        box-sizing: border-box;
        display: block;
        Margin: 0 auto;
        width: 90%;
        padding: 10px;
		border: 1px solid #0400f3;
		border-radius: 14px; }

    
      .main {
        background: #fff;
        border-radius: 3px;
        width: 100%; }

      .wrapper {
        box-sizing: border-box;
        padding: 20px; }

      .footer {
        clear: both;
        padding-top: 10px;
        width: 100%; }
        .footer td,
        .footer p,
        .footer span,
        .footer a {
          color:#1d1c1c;
          font-size: 12px;
          text-align: center; }

     
      h1,
      h2,
      h3,
      h4 {
        color: #000000;
        font-family: sans-serif;
        font-weight: 400;
        line-height: 1.4;
        margin: 0;
        Margin-bottom: 30px; }

      h1 {
        font-size: 35px;
        font-weight: 300;
        text-align: center;
        text-transform: capitalize; }

      p,
      ul,
      ol {
        font-family: sans-serif;
        font-size: 14px;
        font-weight: normal;
        margin: 0;
        Margin-bottom: 15px; }
        p li,
        ul li,
        ol li {
          list-style-position: inside;
          margin-left: 5px; }

      a {
        color: #3498db;
        text-decoration: underline; }

   
      .btn {
        box-sizing: border-box;
        width: 100%; }
        .btn > tbody > tr > td {
          padding-bottom: 15px; }
        .btn table {
          width: auto; }
        .btn table td {
          background-color: #ffffff;
          border-radius: 5px;
          }
        .btn a {
          background-color: #ffffff;
          border: solid 1px #3498db;
          border-radius: 5px;
          box-sizing: border-box;
          color: #3498db;
          cursor: pointer;
          display: inline-block;
          font-size: 14px;
          font-weight: bold;
          margin: 0;
          padding: 12px 25px;
          text-decoration: none;
          text-transform: capitalize; }


      .btn-primary a {
        background-color: #3498db;
        border-color: #3498db;
        color: #ffffff; }

   
      .last {
        margin-bottom: 0; }

      .first {
        margin-top: 0; }

      .align-center {
        text-align: center; }

      .align-right {
        text-align: right; }

      .align-left {
        text-align: left; }

      .clear {
        clear: both; }

      .mt0 {
        margin-top: 0; }

      .mb0 {
        margin-bottom: 0; }

      .preheader {
        color: transparent;
        display: none;
        height: 0;
        max-height: 0;
        max-width: 0;
        opacity: 0;
        overflow: hidden;
        mso-hide: all;
        visibility: hidden;

        width: 0; }

      .powered-by a {
        text-decoration: none; }

      hr {
        border: 0;
        border-bottom: 1px solid #f6f6f6;
        Margin: 20px 0; }

 
      @media only screen and (max-width: 620px) {
        table[class=body] h1 {
          font-size: 28px !important;
          margin-bottom: 10px !important; }
        table[class=body] p,
        table[class=body] ul,
        table[class=body] ol,
        table[class=body] td,
        table[class=body] span,
        table[class=body] a {
          font-size: 16px !important; }
        table[class=body] .wrapper,
        table[class=body] .article {
          padding: 10px !important; }
        table[class=body] .content {
          padding: 0 !important; }
        table[class=body] .container {
          padding: 0 !important;
          width: 100% !important; }
        table[class=body] .main {
          border-left-width: 0 !important;
          border-radius: 0 !important;
          border-right-width: 0 !important; }
        table[class=body] .btn table {
          width: 100% !important; }
        table[class=body] .btn a {
          width: 100% !important; }
        table[class=body] .img-responsive {
          height: auto !important;
          max-width: 100% !important;
          width: auto !important; }}

   
      @media all {
        .ExternalClass {
          width: 100%; }
        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
          line-height: 100%; }
        .apple-link a {
          color: inherit !important;
          font-family: inherit !important;
          font-size: inherit !important;
          font-weight: inherit !important;
          line-height: inherit !important;
          text-decoration: none !important; } 
        .btn-primary a:hover {
          background-color: #34495e !important;
          border-color: #34495e !important; } }
		  th{text-align: left;}

    </style>
  </head>
  <body class="">
    <table border="0" cellpadding="0" cellspacing="0" class="body">
      <tr>
        <td>&nbsp;</td>
        <td class="container">
          <div class="content">
            <table class="main">

            
              <tr>
                <td class="wrapper">
                  <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>
                        <p><strong>Welcome Ecommerece,</strong></p>
                        <p>'.$email.' This Email ID Users Subscribes Our Product.</p>
						<hr style=" border-bottom: 1px solid #0400f3;">
						<p><strong>Following Users Details,</strong></p>
                       
                      </td>
                      
                  </table>
                </td>
              </tr>
              </table>
            <div class="footer">
              <table border="0" cellpadding="0" cellspacing="0">
                <tr>
				 <td class="content-block">
				 <span class="content-block">
				<h1 style="color: #043971;font-size: 3em;font-weight: 400;font-family:cursive;">Ecommerece</h1></span>
				 </td>
				    <td> <span class="content-block">
				<strong>xyz
					Telephone: +91 xxx xxx xxxx / <br />
					E-mail:xyx@gmail.com 
			        <br>
				<a href="http://globallianz.in/ecommerece/">http://globallianz.in/ecommerece/</a>
				 </span></td>
                  <td class="content-block">
                    <span class="apple-link"><br><br>
					<a href="#" target="_blank"><img src="http://www.wiu.edu/cas/images/facebook_circle_color-512.png" style="height:30px; width:30px;"></a>
					<a  target="_blank" href="#"><img src="http://yolna.com/wp-content/uploads/2015/12/twitter-circle-logo.png" style="height:30px;width:30px;"></a>&nbsp;
					<a href="#" target="_blank"><img src="http://icons.iconarchive.com/icons/martz90/circle/512/google-plus-icon.png" style="height:26px; width:26px;"></a>&nbsp;&nbsp;
					<a href="#" target="_blank"><img src="https://www.stuorg.iastate.edu/uploads/org-site/ckuploads/1419/Social%20Media%20Icons/youtube.png" style="width:27px; height:27px;"></a>
					</span>
                 
                  </td>
                </tr>
               
              </table>
            </div></div>
        </td>
        <td>&nbsp;</td>
      </tr>
    </table>
  </body>
</html>';

$body1='<!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Ecommerece</title>
    <style> 
      img {
        border: none;
        -ms-interpolation-mode: bicubic;
        max-width: 100%; }
      body {
        font-family: sans-serif;
        -webkit-font-smoothing: antialiased;
        font-size: 14px;
        line-height: 1.4;
        margin: 0;
        padding: 0; 
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%; }
      table {
        border-collapse: separate;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
        width: 100%; }
        table td {
          font-family: sans-serif;
          font-size: 14px;
          vertical-align: top; }
      .body {
        width: 100%; }
      .container {
        display: block;
        Margin: 0 auto !important;
        padding: 10px;
        /*width: 1000px;*/
		    width: 90%; }
      .content {
        box-sizing: border-box;
        display: block;
        Margin: 0 auto;
        width: 90%;
        padding: 10px;
		border: 1px solid #0400f3;
		border-radius: 14px; }
      .main {
        background: #fff;
        border-radius: 3px;
        width: 100%; }
      .wrapper {
        box-sizing: border-box;
        padding: 20px; }
      .footer {
        clear: both;
        padding-top: 10px;
        width: 100%; }
        .footer td,
        .footer p,
        .footer span,
        .footer a {
          color:#1d1c1c;
          font-size: 12px;
          text-align: center; }
      h1,h2,h3,h4 {
        color: #000000;
        font-family: sans-serif;
        font-weight: 400;
        line-height: 1.4;
        margin: 0;
        Margin-bottom: 30px; }
      h1 {
        font-size: 35px;
        font-weight: 300;
        text-align: center;
        text-transform: capitalize; }
      p,
      ul,
      ol {
        font-family: sans-serif;
        font-size: 14px;
        font-weight: normal;
        margin: 0;
        Margin-bottom: 15px; }
        p li,
        ul li,
        ol li {
          list-style-position: inside;
          margin-left: 5px; }

      a {
        color: #3498db;
        text-decoration: underline; }

   
      .btn {
        box-sizing: border-box;
        width: 100%; }
        .btn > tbody > tr > td {
          padding-bottom: 15px; }
        .btn table {
          width: auto; }
        .btn table td {
          background-color: #ffffff;
          border-radius: 5px;
          }
        .btn a {
          background-color: #ffffff;
          border: solid 1px #3498db;
          border-radius: 5px;
          box-sizing: border-box;
          color: #3498db;
          cursor: pointer;
          display: inline-block;
          font-size: 14px;
          font-weight: bold;
          margin: 0;
          padding: 12px 25px;
          text-decoration: none;
          text-transform: capitalize; }
      .btn-primary a {
        background-color: #3498db;
        border-color: #3498db;
        color: #ffffff; }
      .last {
        margin-bottom: 0; }
      .first {
        margin-top: 0; }
      .align-center {
        text-align: center; }
      .align-right {
        text-align: right; }

      .align-left {
        text-align: left; }

      .clear {
        clear: both; }

      .mt0 {
        margin-top: 0; }

      .mb0 {
        margin-bottom: 0; }

      .preheader {
        color: transparent;
        display: none;
        height: 0;
        max-height: 0;
        max-width: 0;
        opacity: 0;
        overflow: hidden;
        mso-hide: all;
        visibility: hidden;
        width: 0; }

      .powered-by a {
        text-decoration: none; }

      hr {
        border: 0;
        border-bottom: 1px solid #f6f6f6;
        Margin: 20px 0; }

 
      @media only screen and (max-width: 620px) {
        table[class=body] h1 {
          font-size: 28px !important;
          margin-bottom: 10px !important; }
        table[class=body] p,
        table[class=body] ul,
        table[class=body] ol,
        table[class=body] td,
        table[class=body] span,
        table[class=body] a {
          font-size: 16px !important; }
        table[class=body] .wrapper,
        table[class=body] .article {
          padding: 10px !important; }
        table[class=body] .content {
          padding: 0 !important; }
        table[class=body] .container {
          padding: 0 !important;
          width: 100% !important; }
        table[class=body] .main {
          border-left-width: 0 !important;
          border-radius: 0 !important;
          border-right-width: 0 !important; }
        table[class=body] .btn table {
          width: 100% !important; }
        table[class=body] .btn a {
          width: 100% !important; }
        table[class=body] .img-responsive {
          height: auto !important;
          max-width: 100% !important;
          width: auto !important; }}

   
      @media all {
        .ExternalClass {
          width: 100%; }
        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
          line-height: 100%; }
        .apple-link a {
          color: inherit !important;
          font-family: inherit !important;
          font-size: inherit !important;
          font-weight: inherit !important;
          line-height: inherit !important;
          text-decoration: none !important; } 
        .btn-primary a:hover {
          background-color: #34495e !important;
          border-color: #34495e !important; } }
		  th{text-align: left;}

    </style>
  </head>
  <body class="">
    <table border="0" cellpadding="0" cellspacing="0" class="body">
      <tr>
        <td>&nbsp;</td>
        <td class="container">
          <div class="content">
            <table class="main">
              <tr>
                <td class="wrapper">
                  <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>
                        <p>Thank you for subscribe ecommerece</p>
						<hr style=" border-bottom: 1px solid #0400f3;">
                        <table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
                          <tbody>
					  </tbody>
					</table>
				  </td>
                  </table>
                </td>
              </tr>
              </table>
           <div class="footer">
              <table border="0" cellpadding="0" cellspacing="0">
                <tr>
				 <td class="content-block">
				 <span class="content-block">
				<h1 style="color: #043971;font-size: 3em;font-weight: 400;font-family:cursive;">Ecommerece</h1></span>
				 </td>
				    <td> <span class="content-block">
				<strong>xyz

Telephone: +91 xxx xxx xxxx / <br />
E-mail:xyx@gmail.com 
			 <br>
				<a href="http://globallianz.in/ecommerece/">http://globallianz.in/ecommerece/</a>
				 </span></td>
                  <td class="content-block">
                    <span class="apple-link"><br><br>
					<a href="#" target="_blank"><img src="http://www.wiu.edu/cas/images/facebook_circle_color-512.png" style="height:30px; width:30px;"></a>
					<a  target="_blank" href="#"><img src="http://yolna.com/wp-content/uploads/2015/12/twitter-circle-logo.png" style="height:30px;width:30px;"></a>&nbsp;
					<a href="#" target="_blank"><img src="http://icons.iconarchive.com/icons/martz90/circle/512/google-plus-icon.png" style="height:26px; width:26px;"></a>&nbsp;&nbsp;
					<a href="#" target="_blank"><img src="https://www.stuorg.iastate.edu/uploads/org-site/ckuploads/1419/Social%20Media%20Icons/youtube.png" style="width:27px; height:27px;"></a>
					</span>
                  </td>
                </tr>
               
              </table>
            </div></div>
        </td>
        <td>&nbsp;</td>
      </tr>
    </table>
  </body>
</html>';
           $headers = "MIME-Version: 1.0" . "\r\n";
          $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
          $headers .= 'From:Ecommerece<info@globallianz.in>' . "\r\n";
		  $headers1 = "MIME-Version: 1.0" . "\r\n";
          $headers1 .= "Content-type:text/html;charset=UTF-8" . "\r\n";
          $headers1 .= 'From:Ecommerece<info@globallianz.in>' . "\r\n";
		  $mail_to='info@globallianz.in';
		  $mail_subject1=$email." This Email ID Users Subscribe";
		  $mail_subject='Thank you for subscribe Ecommerece'; 
		$query=mysql_query("select email from subscribes where email='$email'");
			if(mysql_num_rows($query)==0)
			{ $date=date('Y-m-d H:i:s');
			mysql_query("insert into subscribes(email,date,status)values('$email','$date','active')");
			mail($mail_to,$mail_subject,$body,$headers);
			mail($email,$mail_subject1,$body1,$headers1);
			echo 1;
			}else{
			echo "Mail Not Send Successfuly";
			exit;
			}
}


if($action=='SendForgotPassword'){
			$email=$_POST['email'];
			$forgot=mysql_query("select *from registration where mobile='$email'");
			$count=mysql_num_rows($forgot);
			if($count==1){
			$rows=mysql_fetch_array($forgot);
			$pass=$rows['password'];
                        
                         $message='Your Buymarttrade Password is: '.$pass;
                        $url='http://login.bulksmsrocket.com/sendhttp.php?user=buymarttrade&password=Vijay@123&message='.urlencode($message).'&mobiles='.urlencode($email).'&sender=BUYMTI';
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_HEADER, 0);
                        curl_exec($ch);
                        curl_close($ch);
			$_SESSION['SUCESS']='Password has been  sent to text message your registerd mobile number&nbsp;'.$email;
			echo 1;
			exit;
			}else{ echo 2; exit;}
}
if($action=='returnordersprocess'){
  $uid=$_SESSION['ID'];
  $date=date('Y-m-d H:i:s');
   $datedifference=strtotime(date('Y-m-d H:i:s'))-strtotime($_POST['reciveddates']);
   $days  = round($datedifference / 86400);
   $oid=$_POST['roid1'];
   $uniqueid123=$_POST['uniqueid123'];
   $rcomment=mysql_real_escape_string($_POST['rcomment']);
  // $arrays=$_POST['itemsname'];
 // print_r($_POST['itemsname']);
 // exit;
    if($days<=4){
      $orders_details=mysql_query("select oid from product_return where oid='$oid'");
	  mysql_query("insert into notification_master(notification_message,oid,uid,status,notification_date)values('order is return','$oid','$uid','active','$date')");
	  if(mysql_num_rows($orders_details)==0){
	    mysql_query("insert into product_return(oid,uid,return_reason,status,return_date,unique_id)values('$oid','$uid','$rcomment','inactive','$date','$uniqueid123')");
	  }
	  foreach($_POST['itemsname'] as $pid){
	    $itmes_details=mysql_query("select oid,uid,quantity,pid from order_items_details where oid='$oid' and uid='$uid' and pid='$pid'");
		$it=mysql_fetch_array($itmes_details);
		 $itmes_details1=mysql_query("select oid,uid,quantity,pid from order_return_items where oid='$oid' and pid='$pid' and uid='$uid'");
		if(mysql_num_rows($itmes_details1)==0){
		 mysql_query("insert into order_return_items(oid,uid,quantity,pid)values('$oid','$uid','".$it['quantity']."','$pid')");
	     }
	   }
	  $_SESSION['SUCESSMSG1']='Order return process request is send successfully...';
	  echo 1;
     }else{
	 echo 2;
	}
}
if($action=="getstatecityname"){
    $state_name=$_POST['state_name'];
    $query=mysql_query("select city_name from cities where city_state='$state_name'");
    $data='';
      $data.='<select type="text" placeholder="Password" class="form-control"  id="city_name" name="city_name" onChange="city_namer();">
                <option value="">Select City</option>'; 
               while($row=mysql_fetch_array($query)){
                $data.='<option value="'.$row['city_name'].'">'.$row['city_name'].'</option>'; 
               }
            $data.='</select>';   
     echo $data; exit;
}
if($action=="Send_Product_Enquiry"){
 $product_id_enquiry= $_POST['product_id_enquiry'];

 $query=mysql_query("select pid,vendor_id from product_details where pid='$product_id_enquiry'");
 $rows=mysql_fetch_array($query);
 $count=mysql_num_rows($query);
 if($count>=1){
  $vendor_id=$rows['vendor_id'];
  $product_id=$rows['pid'];
  $userid=$_SESSION['ID'];
  $customer_needs=$_POST['customer_needs'];
  $quantity=$_POST['qty_selection'];
  $otp = 'BUYMTI'.mt_rand(100000, 999999);
  mysql_query("insert into product_enquiry(userid,vendor_id,product_id,created_date,customer_needs,enquiry_id,quantity)values('$userid','$vendor_id','$product_id','".date('Y-m-d H:i:s')."','$customer_needs','$otp','$quantity')");
  $orderid=mysql_insert_id();
  mysql_query("insert into notification_master(notification_message,oid,uid,status,notification_date,admin_id)values('New orders enquiry','$orderid','$userid','active','".date('Y-m-d H:i:s')."','Admin')");
  $_SESSION['Success']='Your enquiry send successfully';
  header('Location:product-details.php?details='.$product_id);
  }else{
    $_SESSION['Errors']='Your enquiry send successfully';
  header('Location:product-details.php?details='.$product_id_enquiry);  
  }
 }
 
 if($action=='cancel_eqnuiry_details'){
   $uid=$_SESSION['ID'];
   $date=date('Y-m-d H:i:s');
   $oid=$_POST['oid'];
   $rcomment=mysql_real_escape_string($_POST['comment']);
   $cancelation_reason=mysql_real_escape_string($_POST['cancelation_reason']);
   $prod= mysql_query("update product_enquiry set  cancel_type='$cancelation_reason',cancel_note='$rcomment',status='Cancel',cancel_date='".date('Y-m-d H:i:s')."' where id='$oid'");
      if($prod==true){
	  $_SESSION['SUCESSMSG1']='Your Product Enquiry Cancel Successfully';
	  echo 1; exit;
       }else{
     echo 2; exit; 
   }
}
?>