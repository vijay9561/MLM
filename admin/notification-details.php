<?php include('header.php'); 
 $emp_id=$_SESSION['ADMIN_ID'];
 $mysqluery='';
		if(isset($_SESSION['JOBPORTALADMIN'])) {
		
		include_once "function.php";
if(isset($_GET["page"]))
	$page = (int)$_GET["page"];
	else
	$page = 1;

	$setLimit = 10;
	$pageLimit = ($page * $setLimit) - $setLimit;
$query='';
if($_SESSION['ADMIN_TYPE']=='Admin'){
if(isset($_GET['searchkeyowords'])){
$searchid=$_GET['searchkeyowords'];
$query="select *from notification_master  where (notification_message LIKE '%".$searchid."%') AND admin_id='Admin' order by nid desc LIMIT ".$pageLimit." , ".$setLimit;
$mysqluery=mysql_query($query);
}else{
$query="select *from notification_master where admin_id='Admin'  order by nid desc LIMIT ".$pageLimit." , ".$setLimit;
$mysqluery=mysql_query($query);
}
}elseif($_SESSION['ADMIN_TYPE']=='Employee'){
if(isset($_GET['searchkeyowords'])){
$searchid=$_GET['searchkeyowords'];
$query="select *from notification_master where (notification_message LIKE '%".$searchid."%') AND emp_id='$emp_id' order by nid desc LIMIT ".$pageLimit." , ".$setLimit;
$mysqluery=mysql_query($query);
}else{
$query="select *from notification_master where emp_id='$emp_id'  order by nid desc LIMIT ".$pageLimit." , ".$setLimit;
$mysqluery=mysql_query($query);
}    
}elseif($_SESSION['ADMIN_TYPE']=='Vendors'){
  if(isset($_GET['searchkeyowords'])){
$searchid=$_GET['searchkeyowords'];
$query="select *from notification_master where (notification_message LIKE '%".$searchid."%') AND vendor_id='$emp_id' order by nid desc LIMIT ".$pageLimit." , ".$setLimit;
$mysqluery=mysql_query($query);
}else{
$query="select *from notification_master where vendor_id='$emp_id'  order by nid desc LIMIT ".$pageLimit." , ".$setLimit;
$mysqluery=mysql_query($query);
}  
}
		 ?>
		<style>
		td{ padding-left:20px;}
		th{ padding-left:30px;}
		</style>
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
		
			<ol class="breadcrumb">
				<li><a href="index.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
				<li class="active">Notification Mangement</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<!--<h1 class="page-header">Notification Mangement</h1>-->
				<?php if(isset($_SESSION['SUCESSMSG'])){ ?>
				<div class="alert bg-success" role="alert">
			<svg class="glyph stroked checkmark"><use xlink:href="#stroked-checkmark"></use></svg><?php echo $_SESSION['SUCESSMSG']; ?><a href="#" class="pull-right"><span class="glyphicon glyphicon-remove"></span></a>
				</div>
				<?php unset($_SESSION['SUCESSMSG']); } ?>
			</div>
		</div><!--/.row-->
				
		<?php if(isset($_GET['add-new'])){ ?>
		<!-- /.row -->	
		<?php }elseif(isset($_GET['action'])){ 
		$query=mysql_query("select  *from pincode_master where pmid='".$_GET['pincodeid']."'"); $pin=mysql_fetch_array($query);
		  ?>
		<!-- /.row -->
		<?php }else{ ?>
		<div class="row">
		<div class="col-lg-12">
		<div class="row">
		<div class="col-md-6">
	<!--	<a href="pin-code-master.php?add-new=pincodemaster" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Add New</a> -->
		</div>
<div class="col-md-6">
<div class="form-group pull-right">
<form method="post">
<input type="text" id="search_id" placeholder="Search" value="<?php if(isset($_GET['searchkeyowords'])) { echo $_GET['searchkeyowords']; } ?>" required style="display: initial; width:auto;" title="Type Here" class="form-control">&nbsp;&nbsp;
<input type="submit" class="btn btn-primary" value="Search" onclick="return search_result()" />
</form>
</div>
</div>		
		</div>
		<div class="table-responsive">
		
		    <table id="datatable" class="table table-striped table-bordered">
			<thead>
			<tr>
			<th style="padding-left: 30px;">Sr No</th>
			<th style="padding-left: 30px;">Notification Details</th>
			<th style="padding-left: 30px;">Action</th>
			</tr>
			</thead>
                      <tbody>
					  <?php $serial = ($pageLimit * $setLimit) + 1;
									//  $sn = ($pageLimit * $limit) + 1;
									  $sn = ($page * $setLimit) + 1;
									  $page_num   =   (int) (!isset($_GET['page']) ? 1 : $_GET['page']);
                                      $start_num =((($page_num*$setLimit)-$setLimit)+1);
									   $i=1;
									   $j= (($page-1) * $setLimit) + $i; 
									   while($rows=mysql_fetch_array($mysqluery)){  
								    	 $registration=mysql_query("select name,city_name,state_name,mobile from registration where rid='".$rows['uid']."'"); 
		                                 $u=mysql_fetch_array($registration);
							             $num = $sn ++;
                                                                     
                                                                     $product_enquiry=mysql_query("select product_id from product_enquiry where id='".$rows['oid']."'");
                                                                     $re=mysql_fetch_array($product_enquiry);
                                                                     $prod_id=$re['product_id'];
                                                                     $title=mysql_query("select title,price from product_details where pid='".$prod_id."'");
                                                                     $prod=mysql_fetch_array($title);
                                                                     
                                                                     ?>
                        <tr>
						<td style="padding-left: 30px;"><?php echo $j++; ?></td>
						<td style="padding-left: 30px;">
							<?php if($rows['notification_message']=='New orders enquiry') { ?>
                                                    <strong><?php echo $u['name']; ?></strong><br />
							<p  style="font-size:14px; padding:1px; margin:0px;"><?php echo $rows['notification_message']; ?>
							<span style="font-size:14px;padding:1px; margin:0px; color:green;"><i class="fa fa-clock-o"></i>&nbsp;<?php echo $time_elapsed = timeAgo($rows['notification_date']); ?> &nbsp;&nbsp;
							   <i class="fa fa-calendar"></i>&nbsp;&nbsp;<?php  $recived_date=date('d-F-Y', strtotime($rows['notification_date'])); echo $recived_date; ?>
							</span>
                                                            <span style="font-size:14px;padding:1px; margin:0px;"><a href="product_enquiry.php?opendata=<?php echo $rows['oid']; ?>" style="color:#000099;">New Enquiry Details</a></span>
							<?php }elseif($rows['notification_message']=='This customer enquiry assign for you'){ ?>
                                                            <strong><?php echo $u['name']; ?></strong><br />
							<p  style="font-size:14px; padding:1px; margin:0px;"><?php echo $rows['notification_message']; ?>
							<span style="font-size:14px;padding:1px; margin:0px; color:green;"><i class="fa fa-clock-o"></i>&nbsp;<?php echo $time_elapsed = timeAgo($rows['notification_date']); ?> &nbsp;&nbsp;
							   <i class="fa fa-calendar"></i>&nbsp;&nbsp;<?php  $recived_date=date('d-F-Y', strtotime($rows['notification_date'])); echo $recived_date; ?>
							</span>
							<span style="font-size:14px;padding:1px; margin:0px;"><a href="product_enquiry_inprogress.php?opendata=<?php echo $rows['oid']; ?>" style="color:#000099;">View Enquiry Details</a></span>
							<?php }elseif($rows['notification_message']=='New Enquiry Orders'){ ?>
                                                        <strong><?php echo $prod['title']; ?> (<?php echo $u['city_name'].', '.$u['state_name']; ?>) <br><?php echo $u['name']; ?> (<?php echo $u['mobile']; ?>)</strong><br />
							<p  style="font-size:14px; padding:1px; margin:0px;"><?php echo $rows['notification_message']; ?>
							<span style="font-size:14px;padding:1px; margin:0px; color:green;"><i class="fa fa-clock-o"></i>&nbsp;<?php echo $time_elapsed = timeAgo($rows['notification_date']); ?> &nbsp;&nbsp;
							   <i class="fa fa-calendar"></i>&nbsp;&nbsp;<?php  $recived_date=date('d-F-Y', strtotime($rows['notification_date'])); echo $recived_date; ?>
							</span>
							<span style="font-size:14px;padding:1px; margin:0px;"><a href="product_enquiry_inprogress.php?opendata=<?php echo $rows['oid']; ?>" style="color:#000099;">View Enquiry Details</a></span>
							<?php }elseif($rows['notification_message']=='new account is create'){ ?>
							<span style="font-size:14px;padding:1px; margin:0px;"><a href="users.php" style="color:#000099;">Users Details</a></span>
							<?php }else{ ?>
							<span style="font-size:14px;padding:1px; margin:0px;"><a href="new-orders.php" style="color:#000099;">Orders Details</a></span>
							<?php } ?>
							</p>
						</td>
						<td style="padding-left: 30px;">
						  <a href="#" onclick="return deletepincodes(<?php echo $rows['nid']; ?>)" class="btn btn-danger" title="Delete" ><span class="glyphicon glyphicon-trash"></span></a></td>
                        </tr>
						<?php $i++; } ?>
                      </tbody>
                    </table>
					<div class="row">
				<div class="col-md-12">
				
				<?php
                                if($_SESSION['ADMIN_TYPE']=='Admin'){
                                 if(isset($_GET['searchkeyowords'])){
                                    $searchid=$_GET['searchkeyowords'];
                                    $query="SELECT COUNT(*) as totalCount FROM notification_master where (notification_message LIKE '%".$searchid."%') and admin_id='Admin' order by nid desc";
                                    echo searchnotificationsusers($setLimit,$page,$query); 
                                    } else{ 
                                        
                                         $query="SELECT COUNT(*) as totalCount FROM notification_master where admin_id='Admin'  order by nid desc";
                                        echo notificationusers($setLimit,$page,$query);  }
                                    
                                }elseif($_SESSION['ADMIN_TYPE']=='Employee'){
                                    
                                   if(isset($_GET['searchkeyowords'])){
                                    $searchid=$_GET['searchkeyowords'];
                                    $query="SELECT COUNT(*) as totalCount FROM notification_master where (notification_message LIKE '%".$searchid."%')  and  emp_id='$emp_id' order by nid desc";
                                    echo searchnotificationsusers($setLimit,$page,$query); 
                                    } else{   $query="SELECT COUNT(*) as totalCount FROM notification_master where  emp_id='$emp_id'  order by nid desc";
                                        echo notificationusers($setLimit,$page,$query);  }  
                                }elseif($_SESSION['ADMIN_TYPE']=='Vendors'){
                                   if(isset($_GET['searchkeyowords'])){
                                    $searchid=$_GET['searchkeyowords'];
                                    $query="SELECT COUNT(*) as totalCount FROM notification_master where (notification_message LIKE '%".$searchid."%') and   vendor_id='$emp_id' order by nid desc";
                                    echo searchnotificationsusers($setLimit,$page,$query); 
                                    } else{   $query="SELECT COUNT(*) as totalCount FROM notification_master where vendor_id='$emp_id'  order by nid desc";
                                        echo notificationusers($setLimit,$page,$query);  }  
                                    
                                } ?>
		</div>
			</div>
		</div></div>
		</div>
		<?php } ?>
			<br /><br />
				<br /><br />
	</div>

	<?php include('footer.php'); ?>
	<script>
	function deletepincodes(id){
	var con=confirm('are you sure to this remove records !');
	if(con==true){
	$.ajax({
	url: "post.php?action=removenotifications",
	type: 'POST',
	data: {id:id},
	success: function(data) {
	if(data==1){
	location.reload();
	}else{ alert("Not Deleted")}
	}
	});
	}else{
	}
	}
	
	function inactivestatus(id){
	var con=confirm('are you sure to the update status !');
	if(con==true){
	$.ajax({
	url: "post.php?action=inactivestatus",
	type: 'POST',
	data: {id:id},
	success: function(data) {
	if(data==1){
	location.reload();
	}else{ alert("Not Deleted")}
	}
	});
	}else{
	}
	}
	
	function activeusersstatus(id){
	var con=confirm('are you sure to the update status !');
	if(con==true){
	$.ajax({
	url: "post.php?action=activeusersstatus",
	type: 'POST',
	data: {id:id},
	success: function(data) {
	if(data==1){
	location.reload();
	}else{ alert("Not Deleted")}
	}
	});
	}else{
	}
	}
	
	function search_result(){
var search_id=$("#search_id").val();
var search_id2=search_id.trim();
if(search_id==""){
 alert("Please enter keywords");
 return false;
}
window.location='notification-details.php?searchkeyowords='+search_id2;
return false;
}
 function pincoder(){if($('#pincode').val()==""){  }else{ $('#pincoder').html(' ') }}
 function area_countryr(){if($('#area_country').val()==""){  }else{ $('#area_countryr').html(' ') }}
 function listBoxr(){if($('#listBox').val()==""){  }else{ $('#listBoxr').html(' ') }}
 function secondlistr(){if($('#secondlist').val()==""){  }else{ $('#secondlistr').html(' ') }}
  function arear(){if($('#area').val()==""){  }else{ $('#arear').html(' ') }}
   
function addpincodevalues(){
              	var pat1=/^\d{6}$/;
              var pincode=document.getElementById('pincode').value.trim();
			  var area_country=document.getElementById('area_country').value.trim();
			  var listBox=document.getElementById('listBox').value.trim();
			   var secondlist=document.getElementById('secondlist').value.trim();
			    var area=document.getElementById('area').value.trim();
			  if(pincode==''){
			     $('#pincoder').html("Please enter pincode");
				 $('#pincode').focus();
				 return false;
			  }
			  if (!(pincode.match(pat1))) {
			    $("#pincoder").html("Please 6 digit pincode");
				 $('#pincode').focus();	
		     	return false;
			   }
			   if(area_country==''){
			     $('#area_countryr').html("Please select country");
				 $('#area_country').focus();
				 return false;
			  }
			   if(listBox==''){
			     $('#listBoxr').html("Please select state");
				 $('#listBox').focus();
				 return false;
			  }
			    if(secondlist==''){
			     $('#secondlistr').html("Please select city");
				 $('#secondlist').focus();
				 return false;
			  }
			    if(area==''){
			     $('#arear').html("Please enter area name");
				 $('#area').focus();
				 return false;
			  }
			  var formData = new FormData($("#pincodemangement")[0]);
			  $.ajax({   
				url: "post.php?action=addpincodemasters",
				data : formData,
				processData: false,
				contentType: false,
				type: 'POST',
				success: function(data){
					if(data==2){
					window.location="pin-code-master.php";
				     	}else {
						$("#errormessages").show();
                     $(document).ready(function(){ setTimeout(function(){ $("#errormessages").fadeOut('slow'); }, 5000); });
					 return false;
					}
			    }
			});
		return false;
	}
	
	function updatepincodes(){
              	var pat1=/^\d{6}$/;
              var pincode=document.getElementById('pincode').value.trim();
			  var area_country=document.getElementById('area_country').value.trim();
			 
			   var secondlist=document.getElementById('secondlist').value.trim();
			    var area=document.getElementById('area').value.trim();
			  if(pincode==''){
			     $('#pincoder').html("Please enter pincode");
				 $('#pincode').focus();
				 return false;
			  }
			  if (!(pincode.match(pat1))) {
			    $("#pincoder").html("Please 6 digit pincode");
				 $('#pincode').focus();	
		     	return false;
			   }
			   if(area_country==''){
			     $('#area_countryr').html("Please select country");
				 $('#area_country').focus();
				 return false;
			  }
			    if(secondlist==''){
			     $('#secondlistr').html("Please select city");
				 $('#secondlist').focus();
				 return false;
			  }
			    if(area==''){
			     $('#arear').html("Please enter area name");
				 $('#area').focus();
				 return false;
			  }
			  var formData = new FormData($("#pincodemangement")[0]);
			  $.ajax({   
				url: "post.php?action=updatepincodes",
				data : formData,
				processData: false,
				contentType: false,
				type: 'POST',
				success: function(data){
					if(data==1){
					<?php if(isset($_GET['searchkeyowords'])) { ?>
					window.location="pin-code-master.php?searchkeyowords="+<?php echo $_GET['searchkeyowords'];?>;
					<?php }else{ ?>
					window.location="pin-code-master.php";
					<?php } ?>
					}else if(data==2){
				<?php if(isset($_GET['searchkeyowords'])) { ?>
					window.location="pin-code-master.php?searchkeyowords="+<?php echo $_GET['searchkeyowords'];?>;
					<?php }else{ ?>
					window.location="pin-code-master.php";
					<?php } ?>
				     	}else {
						$("#errormessages").show();
                     $(document).ready(function(){ setTimeout(function(){ $("#errormessages").fadeOut('slow'); }, 5000); });
					 return false;
					}
			    }
			});
		return false;
	}
	
		$(document).ready(function(){
		$('.pull-right').click(function(){
		$('.bg-success').hide();
		});
		});
	
</script>
	
<?php }else{  
   header('Location:login.php');
?>
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
            return "Yesterday";
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