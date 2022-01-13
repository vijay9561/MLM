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
$query="select *from pincode_master  where (pincode LIKE '%".$searchid."%' OR area_city LIKE '%".$searchid."%' OR area_state LIKE '%".$searchid."%' OR area LIKE '%".$searchid."%') order by pmid desc LIMIT ".$pageLimit." , ".$setLimit;
$mysqluery=mysql_query($query);
}else{
$query="select *from pincode_master  order by pmid desc LIMIT ".$pageLimit." , ".$setLimit;
$mysqluery=mysql_query($query);
}
		 ?>
		
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
		
			<ol class="breadcrumb">
				<li><a href="index.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
				<li class="active">Pin Code Mangement</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Pin Code Mangement</h1>
				<?php if(isset($_SESSION['SUCESSMSG'])){ ?>
				<div class="alert bg-success" role="alert">
			<svg class="glyph stroked checkmark"><use xlink:href="#stroked-checkmark"></use></svg><?php echo $_SESSION['SUCESSMSG']; ?><a href="#" class="pull-right"><span class="glyphicon glyphicon-remove"></span></a>
				</div>
				<?php unset($_SESSION['SUCESSMSG']); } ?>
			</div>
		</div><!--/.row-->
				
		<?php if(isset($_GET['add-new'])){ ?>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
				
					<div class="panel-heading">Pin Code Add From</div>
					<div class="panel-body">
						<div  class="col-md-6">
						<div class="alert bg-danger" role="alert" style="display:none;" id="errormessages">This Pincode Already Exit</div>
							<form role="form" method="post"  id="pincodemangement">
							
								<div class="form-group">
									<label>Pin Code</label>
									 <input type="text" id="pincode" name="pincode" onchange="pincoder()" maxlength="50" required="required" class="form-control">
						  <span id="pincoder" style="color:red;"></span>
								</div>
								
								  <div class="form-group">
									<label>Select Country</label>
									 <select type="text" id="area_country" name="area_country" onchange="area_countryr()" maxlength="50" required="required" class="form-control">
									 <option value="">Select</option>
									 <option value="India">India</option>
									 </select>
						  <span id="area_countryr" style="color:red;"></span>
								</div>
							
							     <div class="form-group">
									<label for="email">Select State</label>
				                <div id="selection">
                             <select id="listBox" name="area_state" class="form-control" onchange='selct_district(this.value)'></select>
                          <span id="listBoxr" style="color:red;"></span>
                             </div>
				            </div>
							
							
							 <div class="form-group">
							 <label for="email">Select City</label>
							 <select id='secondlist' name="area_city"  class="form-control"  onchange="secondlistr()" required>
							 <option value="">Select City</option>
							 </select>
							  <span id="secondlistr" style="color:red;"></span>
							 </div>
							 
							  <div class="form-group">
									<label>Area Name</label>
									 <input type="text" id="area" name="area" onchange="arear()" maxlength="50" required="required" class="form-control">
						  <span id="arear" style="color:red;"></span>
								</div>
							
															
							<div class="form-group"><input type="submit" class="btn btn-primary" onClick="return addpincodevalues();" value="Add Pincode"></div>
							</div>
							
						</form>
					</div>
				</div>
			</div><!-- /.col-->
		</div><!-- /.row -->	
		<?php }elseif(isset($_GET['action'])){ 
		$query=mysql_query("select  *from pincode_master where pmid='".$_GET['pincodeid']."'"); $pin=mysql_fetch_array($query);
		  ?>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">Pin Code Update Form</div>
					<div class="panel-body">
						<div  class="col-md-6">
							<div class="alert bg-danger" role="alert" style="display:none;" id="errormessages">This Pincode Already Exit</div>
							<form role="form" method="post"  id="pincodemangement">
							<input type="hidden" name="pmid" id="pmid" value="<?php echo $_GET['pincodeid']; ?>" />
								<div class="form-group">
									<label>Pin Code</label>
									 <input type="text" id="pincode" name="pincode" value="<?php echo  $pin['pincode']; ?>" onchange="pincoder()" maxlength="50" required="required" class="form-control">
						  <span id="pincoder" style="color:red;"></span>
								</div>
								
								  <div class="form-group">
									<label>Select Country</label>
									 <select type="text" id="area_country" name="area_country" onchange="area_countryr()" maxlength="50" required="required" class="form-control">
									 <option value="<?php echo  $pin['area_country']; ?>"><?php echo  $pin['area_country']; ?></option>
									 </select>
						  <span id="area_countryr" style="color:red;"></span>
								</div>
							
							     <div class="form-group">
									<label for="email">Select State</label>
				                <div id="selection">
                             <select id="listBox" name="area_state" class="form-control" onchange='selct_district(this.value)'>
							  <option value="<?php echo  $pin['area_state']; ?>"><?php echo  $pin['area_state']; ?></option>
							 </select>
							 <input type="hidden" name="defaultselectstate"  value="<?php echo  $pin['area_state']; ?>" />
							 <span>You Have Selected State <strong><?php echo  $pin['area_state']; ?></strong></span>
                          <span id="listBoxr" style="color:red;"></span>
                             </div>
				            </div>
							 <div class="form-group">
							 <label for="email">Select City</label>
							 <select id='secondlist' name="area_city"  class="form-control"  onchange="secondlistr()" required>
							 <option value="<?php echo  $pin['area_city']; ?>"><?php echo  $pin['area_city']; ?></option>
							 </select>
							  <span id="secondlistr" style="color:red;"></span>
							 </div>
							 
							  <div class="form-group">
									<label>Area Name</label>
									 <input type="text" id="area" name="area" onchange="arear()" value="<?php echo  $pin['area']; ?>" maxlength="50" required="required" class="form-control">
						  <span id="arear" style="color:red;"></span>
								</div>
							
															
							<div class="form-group"><input type="submit" class="btn btn-primary" onClick="return updatepincodes();" value="Update Pincode"></div>
							</div>
							
						</form>
					</div>
				</div>
			</div><!-- /.col-->
		</div><!-- /.row -->
		<?php }else{ ?>
		<div class="row">
		<div class="col-lg-12">
		<div class="row">
		<div class="col-md-6">
		<a href="pin-code-master.php?add-new=pincodemaster" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Add New</a>
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
                          <th>Pin Code</th>
                          <th>Country</th>
						   <th>State</th>
						  <th>City</th>
                          <th>Address</th>
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
						<td><?php echo $row['pincode']; ?></td>
						<td><?php echo $row['area_country']; ?></td>
						<td><?php echo $row['area_state']; ?></td>
						<td><?php echo $row['area_city']; ?></td>
						<td><?php echo $row['area']; ?></td>
						
                       <td>
					   <?php if(isset($_GET['searchkeyowords'])){ ?>
					   <a href="pin-code-master.php?action=updatepincode&pincodeid=<?php echo $row['pmid']; ?>&searchkeyowords=<?php echo $_GET['searchkeyowords']; ?>"  class="btn btn-primary" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
					   <?php }else{ ?>
					    <a href="pin-code-master.php?action=updatepincode&pincodeid=<?php echo $row['pmid']; ?>"  class="btn btn-primary" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
					   <?php } ?>
						  <a href="#" onclick="return deletepincodes(<?php echo $row['pmid']; ?>)" class="btn btn-danger" title="Delete" ><span class="glyphicon glyphicon-trash"></span></a></td>
                        </tr>
						<?php $i++; } ?>
                      </tbody>
                    </table>
					<div class="row">
				<div class="col-md-12">
				
				<?php if(isset($_GET['searchkeyowords'])){ echo searchresultpincodepaging($setLimit,$page,$_GET['searchkeyowords']); } else{  echo pincodemasters($setLimit,$page);  } ?>
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
	url: "post.php?action=removepincodemasters",
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
window.location='pin-code-master.php?searchkeyowords='+search_id2;
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


