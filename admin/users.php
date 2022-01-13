<?php include('header.php'); 
		if(isset($_SESSION['JOBPORTALADMIN'])) {
		
		include_once "function.php";
if(isset($_GET["page"]))
	$page = (int)$_GET["page"];
	else
	$page = 1;

	$setLimit = 100;
	$pageLimit = ($page * $setLimit) - $setLimit;
$query='';
if(isset($_GET['searchkeyowords'])){
$searchid=$_GET['searchkeyowords'];
$query="select *from registration  where (name LIKE '%".$searchid."%' OR email LIKE '%".$searchid."%' OR mobile LIKE '%".$searchid."%') and user_type='Users' order by rid desc LIMIT ".$pageLimit." , ".$setLimit;
$mysqluery=mysql_query($query);
}else{
$query="select *from registration where user_type='Users'  order by rid desc LIMIT ".$pageLimit." , ".$setLimit;
$mysqluery=mysql_query($query);
}
		 ?>
		
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
		
			<ol class="breadcrumb">
				<li><a href="index.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
				<li class="active">Users Mangement</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Users Mangement</h1>
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
                          <th>Sr No</th>
                          <th>Name</th>
                          <th>Email ID</th>
						   <th>Mobile No</th>
						  <th>Image</th>
                          <th>status</th>
                          <th>Action</th>
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
									   while($row=mysql_fetch_array($mysqluery)){  
								//$slNo = $i+$start_num;
								    
							       $num = $sn ++;

					   ?>
                        <tr>
						<td><?php echo $j++; ?></td>
						<td><?php echo $row['name']; ?></td>
						<td><?php echo $row['email']; ?></td>
						<td><?php echo $row['mobile']; ?></td>
				<td><?php if(!empty($row['profile_picture'])) { ?><img src="../images/profile/<?php echo $row['profile_picture']; ?>" style="width:150px; height:100px;"  class="img-thumbnail"/><?php }else{ ?>
                                    <img  alt="No Image" src="user.png" style="width:150px; height:100px;"  class="img-thumbnail"/>
				<?php } ?>
				</td>
						<td><?php if($row['status']=='active') { ?>  
						<a href="#" onclick="return inactivestatus(<?php echo $row['rid']; ?>)" class="btn btn-success" title="Change Status" ><?php echo $row['status']; ?></a></td>
						<?php }else{ ?>
						<a href="#" onclick="return activeusersstatus(<?php echo $row['rid']; ?>)" class="btn btn-danger" title="Change Status" ><?php echo $row['status']; ?></a></td>
						<?php } ?>
                       <td>
						  <a href="#" onclick="return deletepincodes(<?php echo $row['rid']; ?>)" class="btn btn-danger" title="Delete" ><span class="glyphicon glyphicon-trash"></span></a></td>
                        </tr>
						<?php $i++; } ?>
                      </tbody>
                    </table>
					<div class="row">
				<div class="col-md-12">
				
				<?php if(isset($_GET['searchkeyowords'])){ echo userspaignsearchings($setLimit,$page,$_GET['searchkeyowords']); } else{  echo userspaging($setLimit,$page);  } ?>
		</div>
			</div>
		</div></div>
		</div>
		<?php } ?>
	</div>
	<?php include('footer.php'); ?>
	<script>
	function deletepincodes(id){
	var con=confirm('are you sure to this remove records !');
	if(con==true){
	$.ajax({
	url: "post.php?action=removeusersstatus",
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
window.location='users.php?searchkeyowords='+search_id2;
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


