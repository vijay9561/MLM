 <?php
 error_reporting(0);
 require_once('config.php');
  if($_SESSION['ADMIN_TYPE']=='Admin'){
  $notification=mysql_query("select notification_message,oid,status,uid,notification_date from notification_master where admin_id='Admin' order by nid desc limit 0,6");
  $notificationcount=mysql_query("select notification_message,oid,status,uid,notification_date from notification_master where status='active' and  admin_id='Admin'");
  $notification1=mysql_query("select notification_message,oid,status,uid,notification_date from notification_master order where admin_id='Admin' by nid desc");
  
 ?>
 <?php if(mysql_num_rows($notificationcount)>=1){  
 $counts=mysql_num_rows($notificationcount);?>
 <span id="getnotifications" class="nofticationicons"><?php echo $counts; ?></span>
 <?php } ?>
		<a href="#"  onclick="return updathidenotification()" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-globe"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
		 <ul class="dropdown-menu" role="menu" >
		 <?php if(mysql_num_rows($notification)>=1){ while($rows=mysql_fetch_array($notification)){ 
		 $registration=mysql_query("select name from registration where rid='".$rows['uid']."'"); 
		 $u=mysql_fetch_array($registration);
		 
		 ?>
            <li>
			<div class="row">
			<div class="col-md-4 col-xs-4">
			<!--<img src="images/user.png" style="width:70px; height:60px;" />-->
                            <i class="fa fa-bell" style="font-size:70px;"></i>
			</div>
			<div class="col-md-8 col-xs-8"><strong><?php echo $u['name']; ?></strong><br />
			<p  style="font-size:11px; padding:1px; margin:0px;"><?php echo $rows['notification_message']; ?></p>
			<p style="font-size:12px;padding:1px; margin:0px; color:green;"><i class="fa fa-clock-o"></i>&nbsp;<?php echo $time_elapsed = timeAgo($rows['notification_date']); ?></p>
			<?php if($rows['notification_message']=='New orders enquiry') { ?>
                        <p style="font-size:11px;padding:1px; margin:0px;"><a href="product_enquiry.php" style="color:#000099;">New Enquiry</a></p>
			<?php }elseif($rows['notification_message']=='order is cancel'){ ?>
				<p style="font-size:11px;padding:1px; margin:0px;"><a href="cancel-orders.php" style="color:#000099;">Cancel Orders Details</a></p>
			<?php }elseif($rows['notification_message']=='add review on product'){ ?>
			<p style="font-size:11px;padding:1px; margin:0px;"><a href="product-review.php" style="color:#000099;">Review Details</a></p>
			<?php }elseif($rows['notification_message']=='new account is create'){ ?>
	     	<p style="font-size:11px;padding:1px; margin:0px;"><a href="users.php" style="color:#000099;">Users Details</a></p>	
			<?php }else{ ?>
			<p style="font-size:11px;padding:1px; margin:0px;"><a href="new-orders.php" style="color:#000099;">Orders Details</a></p>
			<?php } ?>
			</div>
			</div>
			</li>
			<hr style="margin-top:1px; margin-bottom:1px;">
			<?php } ?>
			<?php if(mysql_num_rows($notification1)>6){ ?>
			<li style="background-color: #c9c1c1;padding-bottom: 0px;margin-bottom: -5px;"><p style="font-size:13px;padding:2px; margin:0px; text-align:center;">
			<a href="notification-details.php" style="color:#000000;">More Details..</a></p></li>
			<?php } ?>
			<?php }else{ ?>
			<li style="padding:4px;">Notfication Not Found</li>
			<?php } ?>
         </ul>
      </ul>
  <?php }else if($_SESSION['ADMIN_TYPE']=='Employee'){
      $emp_id=$_SESSION['ADMIN_ID'];
       $notification=mysql_query("select notification_message,oid,status,uid,notification_date,emp_id from notification_master where emp_id='$emp_id' order by nid desc limit 0,6");
  $notificationcount=mysql_query("select notification_message,oid,status,uid,notification_date,emp_id from notification_master where emp_id='$emp_id' and status='active'");
  $notification1=mysql_query("select notification_message,oid,status,uid,notification_date,emp_id from notification_master where emp_id='$emp_id' order by nid desc");
  
      ?>
      <?php if(mysql_num_rows($notificationcount)>=1){  
 $counts=mysql_num_rows($notificationcount);?>
 <span id="getnotifications" class="nofticationicons"><?php echo $counts; ?></span>
 <?php } ?>
		<a href="#"  onclick="return updathidenotification()" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-globe"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
		 <ul class="dropdown-menu" role="menu" >
		 <?php if(mysql_num_rows($notification)>=1){ while($rows=mysql_fetch_array($notification)){ 
		 $registration=mysql_query("select name,mobile from registration where rid='".$rows['uid']."'"); 
		 $u=mysql_fetch_array($registration);
		 
		 ?>
            <li>
			<div class="row">
			<div class="col-md-4 col-xs-4">
			<!--<img src="images/user.png" style="width:70px; height:60px;" />-->
                            <i class="fa fa-bell" style="font-size:70px;"></i>
			</div>
			<div class="col-md-8 col-xs-8"><strong><?php echo $u['name']; ?> <br>Mob.(<?php echo $u['mobile']; ?>)</strong><br />
			<p  style="font-size:11px; padding:1px; margin:0px;"><?php echo $rows['notification_message']; ?></p>
			<p style="font-size:12px;padding:1px; margin:0px; color:green;"><i class="fa fa-clock-o"></i>&nbsp;<?php echo $time_elapsed = timeAgo($rows['notification_date']); ?></p>
                        <p style="font-size:11px;padding:1px; margin:0px;"><a href="product_enquiry_inprogress.php?opendata=<?php echo $rows['oid']; ?>" style="color:#000099;">View Details</a></p>
			
                        
			</div>
			</div>
			</li>
			<hr style="margin-top:1px; margin-bottom:1px;">
			<?php } ?>
			<?php if(mysql_num_rows($notification1)>6){ ?>
			<li style="background-color: #c9c1c1;padding-bottom: 0px;margin-bottom: -5px;"><p style="font-size:13px;padding:2px; margin:0px; text-align:center;">
			<a href="notification-details.php" style="color:#000000;">More Details..</a></p></li>
			<?php } ?>
			<?php }else{ ?>
			<li style="padding:4px;">Notfication Not Found</li>
			<?php } ?>
         </ul>
      </ul>
  <?php }else if($_SESSION['ADMIN_TYPE']=='Vendors'){
      $emp_id=$_SESSION['ADMIN_ID'];
       $notification=mysql_query("select notification_message,oid,status,uid,notification_date,emp_id from notification_master where vendor_id='$emp_id' order by nid desc limit 0,6");
  $notificationcount=mysql_query("select notification_message,oid,status,uid,notification_date,emp_id from notification_master where vendor_id='$emp_id' and status='active'");
  $notification1=mysql_query("select notification_message,oid,status,uid,notification_date,emp_id from notification_master where vendor_id='$emp_id' order by nid desc");
  
      ?>
      <?php if(mysql_num_rows($notificationcount)>=1){  
 $counts=mysql_num_rows($notificationcount);?>
 <span id="getnotifications" class="nofticationicons"><?php echo $counts; ?></span>
 <?php } ?>
		<a href="#"  onclick="return updathidenotification()" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-globe"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
		 <ul class="dropdown-menu" role="menu" >
		 <?php if(mysql_num_rows($notification)>=1){ while($rows=mysql_fetch_array($notification)){ 
		 $registration=mysql_query("select name,mobile from registration where rid='".$rows['uid']."'"); 
		 $u=mysql_fetch_array($registration);
		 
		 ?>
            <li>
			<div class="row">
			<div class="col-md-4 col-xs-4">
			<!--<img src="images/user.png" style="width:70px; height:60px;" />-->
                            <i class="fa fa-bell" style="font-size:70px;"></i>
			</div>
			<div class="col-md-8 col-xs-8"><strong><?php echo $u['name']; ?> <br>Mob.(<?php echo $u['mobile']; ?>)</strong><br />
			<p  style="font-size:11px; padding:1px; margin:0px;"><?php echo $rows['notification_message']; ?></p>
			<p style="font-size:12px;padding:1px; margin:0px; color:green;"><i class="fa fa-clock-o"></i>&nbsp;<?php echo $time_elapsed = timeAgo($rows['notification_date']); ?></p>
                        <p style="font-size:11px;padding:1px; margin:0px;"><a href="product_enquiry_inprogress.php?opendata=<?php echo $rows['oid']; ?>" style="color:#000099;">View Details</a></p>
			
                        
			</div>
			</div>
			</li>
			<hr style="margin-top:1px; margin-bottom:1px;">
			<?php } ?>
			<?php if(mysql_num_rows($notification1)>6){ ?>
			<li style="background-color: #c9c1c1;padding-bottom: 0px;margin-bottom: -5px;"><p style="font-size:13px;padding:2px; margin:0px; text-align:center;">
			<a href="notification-details.php" style="color:#000000;">More Details..</a></p></li>
			<?php } ?>
			<?php }else{ ?>
			<li style="padding:4px;">Notfication Not Found</li>
			<?php } ?>
         </ul>
      </ul>
  <?php } ?>
	  
	    <?php function timeAgo($time_ago)
        {
    $time_ago = strtotime($time_ago);
    $cur_time   = time();
    $time_elapsed   = $cur_time - $time_ago;
    $seconds    = $time_elapsed ;
    $minutes    = round($time_elapsed / 60 );
    $hours      = round($time_elapsed / 3600);
    $days       = round($time_elapsed / 86400 );
    $weeks      = round($time_elapsed / 604800);
    $months     = round($time_elapsed / 2600640 );
    $years      = round($time_elapsed / 31207680 );
    // Seconds
    if($seconds <= 60){
        return "Just Now";
    }
    //Minutes
    else if($minutes <=60){
        if($minutes==1){
            return "1 Minute ago";
        }
        else{
            return "$minutes Minutes Ago";
        }
    }
    //Hours
    else if($hours <=24){
        if($hours==1){
            return $hours." Hour Ago";
        }else{
            return "$hours Hrs Ago";
        }
    }
    //Days
    else if($days <= 7){
        if($days==1){
            return "yesterday";
        }else{
            return "$days days Ago";
        }
    }
    //Weeks
    else if($weeks <= 4.3){
        if($weeks==1){
            return "$weeks Week Ago";
        }else{
            return "$weeks Weeks Ago";
        }
    }
    //Months
    else if($months <=12){
        if($months==1){
            return "$months Month Ago";
        }else{
            return "$months Months Ago";
        }
    }
    //Years
    else{
        if($years==1){
            return "one Year Ago";
        }else{
            return "$Years Years Ago";
        }
    }
}
 ?>