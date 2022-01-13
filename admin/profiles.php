<?php include('header.php'); 
		if(isset($_SESSION['JOBPORTALADMIN'])) {  $userid=$_SESSION['ADMIN_ID'];
                    $query=mysql_query("select *from registration where rid='$userid'");
                    $qu=mysql_fetch_array($query);
                    $cities=mysql_query("select *from cities group by city_state");
                   $cities1=mysql_query("select *from cities order by city_name asc");
                    ?>
<style>
    .table>thead>tr>th {
    border-bottom: 1px solid #e6e7e8;
    vertical-align: middle;
    height: 50px;
    padding: 9px;
}
 #loading {
  width: 100%;
  height: 100%;
  top: 0px;
  left: 0px;
  position: fixed;
  display: block;
  opacity: 0.7;
  z-index: 99;
  text-align: center;
}

#loading-image {
  position: absolute;
  top: 20%;
  left: 50%;
  z-index: 100;
}
.img-wrap {
    font-size: 0;

}
.img-wrap .close {
 position: absolute;
top: 0px;
right: -22px;
z-index: 9999;
background-color: #16A823;
padding: 3px 3px 6px;
color: #030303;
font-weight: bold;
cursor: pointer;
opacity: .6;
text-align: center;
font-size: 25px;
line-height: 10px;
border-radius: 50%;
}
.img-wrap:hover .close {
    opacity: 2;
}
</style>
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
	<div class="row">
		<div id="loading" style="display:none;">
        <img id="loading-image" src="images/show_loader.gif" alt="Loading..." />
              </div>

			<ol class="breadcrumb">
				<li><a href="index.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
				<li class="active">Seller Profile</li>
			</ol>
		</div><!--/.row-->
			
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <div class="panel panel-primary">
                            <div class="panel-heading"><h3 class="panel-title">Profile Details <a href="#" class="btn btn-success" data-toggle="modal" data-target="#myprofilesdetails" style="float:right;margin-top:-6px;"><i class="fa fa-pencil"></i> Edit Profile</a> </h3></div>   
                            <div class="panel-body">
                                <?php if(isset($_SESSION['SUCESSMSG'])){ ?><div class="alert alert-success"><?php echo $_SESSION['SUCESSMSG']; ?></div><?php unset($_SESSION['SUCESSMSG']); } ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr><th>Store Name</th><td><?php echo $qu['vendor_store_name']; ?></td></tr>
                                         <tr><th>Owner Name </th><td><?php echo $qu['name']; ?></td></tr>
                                          <tr><th>Mobile No</th><td><?php echo $qu['mobile']; ?></td></tr>
                                           <tr><th>Store Address</th><td><?php echo $qu['Address']; ?></td></tr>
                                            <tr><th>City & State</th><td><?php echo $qu['city_name'].'&nbsp;'.$qu['state_name']; ?></td></tr>
                                           
                             
                                            <tr><th>Shope Licence No</th><td><?php echo $qu['shop_licence_no']; ?></td></tr>
                                            <tr><th>GST No</th><td><?php echo $qu['cst_no']; ?></td></tr>  
                                            <tr><th>Profile Created Date</th><td><?php echo date('d-m-Y', strtotime($qu['date'])); ?></td></tr>   
                                    </thead>   
                                    </thead>   
                                </table>
                            </div> 
                        </div>   
                    </div>
                </div>
                
<div id="myprofilesdetails" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Update Profile Details</h4>
      </div>
        <form method="post" id="submitforms">
      <div class="modal-body">
          <div class="form-group">
              <label>Shop Owner Name</label>
              <input type="text" class="form-control" name="name" value="<?php echo $qu['name']; ?>" placeholder="Shop Name" id="name" onchange="namer()"> 
              <span id="namer" class="text-danger"></span>
          </div>
          <div class="form-group">
              <label>Shop Name</label>
              <input type="text" class="form-control" name="vendor_store_name" value="<?php echo $qu['vendor_store_name']; ?>" placeholder="Shop Name" id="vendor_store_name" onchange="vendor_store_namer()"> 
              <span id="vendor_store_namer" class="text-danger"></span>
          </div>
           <div class="form-group">
                <label>Mobile No</label>
              <input type="text" class="form-control" name="mobile" placeholder="Mobile No" value="<?php echo $qu['mobile']; ?>" id="mobile" onchange="mobiler()"> 
              <span id="mobiler" class="text-danger"></span>
          </div>
          <div class="form-group">
              <textarea name="Address" id="Address" onchange="Addressr();" class="form-control"><?php echo $qu['Address']; ?></textarea> 
              <span id="Addressr" class="text-danger"></span>
          </div>
            <div class="form-group">
                <label>Select State</label>                             <br>
                                            <select type="text" placeholder="Password" class="form-control"  id="state_name" name="state_name" onChange="state_namer();">
                                            <option value="<?php echo $qu['state_name']; ?>"><?php echo $qu['state_name']; ?></option> 
                                            <?php while($row=mysql_fetch_array($cities)){ ?>
                                             <option value="<?php echo $row['city_state']; ?>"><?php echo $row['city_state']; ?></option> 
                                            <?php } ?>
                                        </select>
				 <span id="state_namer" style="color:red;"></span>	
				</div>
					                                        
			 
                          
                           <div class="form-group" id="getdata">
                               <label>City</label>           
                                            <select type="text" placeholder="Password" class="form-control"  id="city_name" name="city_name" onChange="city_namer();">
                                            <option value="<?php echo $qu['city_name']; ?>"><?php echo $qu['city_name']; ?></option> 
                                            <?php while($row=mysql_fetch_array($cities1)){ ?>
                                             <option value="<?php echo $row['city_name']; ?>"><?php echo $row['city_name']; ?></option> 
                                            <?php } ?>
                                        </select>
				 <span id="city_namer" style="color:red;"></span>	
				</div>
           <div class="form-group">
                <label>GST Number (Optional)</label>
              <input type="text" class="form-control" name="cst_no" placeholder="GST Number" value="<?php echo $qu['cst_no']; ?>" id="cst_no" onchange="cst_nor()"> 
              <span id="cst_nor" class="text-danger"></span>
          </div>
          <div class="form-group">
                <label>Shop Act Number (Optional)</label>
              <input type="text" class="form-control" name="shop_licence_no" placeholder="Shop Act Number" value="<?php echo $qu['shop_licence_no']; ?>" id="shop_licence_no" onchange="shop_licence_nor()"> 
              <span id="shop_licence_nor" class="text-danger"></span>
          </div>   
           <div class="form-group">
                <input type="submit" onclick="return regiterusers()" value="Update Profile" class="btn btn-primary">  
            </div>  
      </div>
           
     </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
		
</div>        
   <?php include('footer.php'); ?>
	
                
	
<?php }else{  
   header('Location:login.php');
?>
<?php } ?>


<script>
    
    function regiterusers(){
                var namecheck = /[A-Za-z]+$/;      
		var emailpattern = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/;
		var mobilenovalidation=/^\d{10}$/;
		var positvenumber=/[0-9 -()+]+$/;
		var chequeno=/^\d{6,14}$/;
		var name=document.getElementById('name').value.trim();
		var mobile=document.getElementById('mobile').value.trim();
                var Address=document.getElementById('Address').value.trim();
                var state_name=document.getElementById('state_name').value.trim();
                var city_name=document.getElementById('city_name').value.trim();
                var vendor_store_name=document.getElementById('vendor_store_name').value.trim(); 
               
			if(name==''){
			$("#namer").html('Please enter name');
			return false;
			}
                        if (!(name.match(namecheck))) {
			$("#namer").html("Please enter valid name");	
			return false;
			}
                        if(vendor_store_name==""){
                        $("#vendor_store_namer").html("Please enter shop name");
                        return false;
                        }
			
			if(mobile==''){
			$("#mobiler").html('Please enter contact number');
			return false;
			}
                        if(Address==""){
                          $("#Addressr").html('Please enter address');
			return false;  
                        }
			if (!(mobile.match(mobilenovalidation))) {
			$("#mobiler").html("Please enter valid contact number");	
			return false;
			}
                         if(state_name==''){
			$("#state_namer").html('Please select state');
			return false;
			}
                        if(city_name==''){
			$("#city_namer").html('Please select city name');
			return false;
			}
                    $("#loading").show(); 
	            var formData = new FormData($("#submitforms")[0]);
			$.ajax({   
				url: "post.php?action=UpdateMyProfiles",
				data : formData,
				processData: false,
				contentType: false,
				type: 'POST',
				success: function(data){
					if(data==1){
					window.location='profiles.php'; 
				        return false;
				     	}else {
                                       $("#mobiler").html('This Mobile number already exist');
				       $("#loading").fadeOut("slow");
					 return false;
					}
					
				}
			});      
                        
                    }
  function state_namer(){ if($("#state_name").val()==""){ }else{ 
           var state_name=$("#state_name").val();
           
            $.ajax({   
		url: "../post.php?action=getstatecityname",
		type: "POST",
		data: {state_name:state_name},
		success: function(data){
		if(data){
              //  $("#getdata").html(data);
                $("#getdata").fadeOut().html(data).fadeIn('slow');
		}
		}
		});
            $("#state_namer").html(" ") } } 
</script>

	<div id="loading" style="display:none;">
        <img id="loading-image" src="images/show_loader.gif" alt="Loading..." />
              </div>



