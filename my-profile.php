
<?php
include('header.php');
$cities=mysql_query("select *from cities group by city_state");
$cities1=mysql_query("select *from cities order by city_name asc");
if(!$_SESSION['ID']){ 
?>
<script>
window.location="index.php";
</script>
<?PHP 
//echo 'Hi';
//exit;
}else{

 
?>
<style>
.profilesimages{height:150px; width:150px;}
@media(max-width:500px){
.profilesimages{height:120px; width:150px;}
}
input[type='file'] {
  color: transparent;
}
.referral_text{
background-color: #f90;
color: white;
padding: 5px;
border-radius: 10px;
box-shadow: 0px 8px 8px -3px #6c6565;
margin-bottom: 4px;
margin-top: 3px;
}
</style>
<link rel="stylesheet" href="js/style.css" />
 <script src="js/circlos.js"></script>
    <script>
     $(document).ready(function(){
      // init
         $(".cdev").circlos();
     });
      function state_namer(){ if($("#state_name").val()==""){ }else{ 
           var state_name=$("#state_name").val();
           
            $.ajax({   
		url: "post.php?action=getstatecityname",
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
<script>
function profile_photor(){
			var lblError = document.getElementById("errorprofilelable");
			     myfile= $('#profile_images').val();
				   var ext = myfile.split('.').pop();
 if(ext=="png" || ext=="PNG" || ext=="jpg" || ext=="jpeg" || ext=="JPEG" || ext=="JPG" || ext=="gif" ||  ext=="BMP" ||  ext=="bmp"  ||  ext=="PPM" ||  ext=="ppm" ||  ext=="PGM" ||  ext=="Exif" ||  ext=="PNM" ||  ext=="PBM" || ext=="JFIF"){
      // alert('Valid');
	    lblError.innerHTML='';
   } else{
         lblError.innerHTML = "Please upload files having extensions: <b> only png,jpg,jpeg,gif</b> only.";
			document.getElementById('profile_images').value='';
			return false;
   }
    var fileUpload = document.getElementById("profile_images");
                if (typeof (FileReader) != "undefined") {
                    var dvPreview = document.getElementById("tempprofileimage");
                    dvPreview.innerHTML = "";
                    var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/;
                    for (var i = 0; i < fileUpload.files.length; i++) {
                        var file = fileUpload.files[i];
                        if (regex.test(file.name.toLowerCase())) {
                            var reader = new FileReader();
                            reader.onload = function (e) {
                                var img = document.createElement("IMG");
                                img.height = "150";
                                img.width = "150";
                                img.src = e.target.result;
								img.class="img-thumbnail";
                                dvPreview.appendChild(img);
								
                            }
                            reader.readAsDataURL(file);
							
                        } 
						
						else {
                            alert(file.name + " is not a valid image file.");
                            dvPreview.innerHTML = "";
								$('#profile_images').val('');
                            return false;
                        }
                    }
                } else {
                    alert("This browser does not support HTML5 FileReader.");
                }
         
			}
			
function namer(){if($('#name').val()==''){}else{ $('#namer').html(' '); }}
function emailr(){if($('#email').val()==''){}else{ $('#emailr').html(' ');
var email=$("#email").val();
		$.ajax({   
		url: "post.php?action=duplicateemailaddressupdate",
		type: "POST",
		data: {email:email},
		success: function(data){
		if(data==0){
		} else if(data==1){
		} else{
		document.getElementById('email').value='';
		$('#emailr').html(email+'&nbsp;This Email ID already Registered')
		}
		}
		});
 }}
function mobiler(){if($('#mobile').val()==''){}else{ $('#mobiler').html(' '); }}
function regiterusers(){
        var namecheck = /[A-Za-z]+$/;      
		var emailpattern = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/;
		var numbermatch= /^[1-9]\d{0,10}(?:\.\d)?$|^0\.[1-9]$/;
		var mobilenovalidation=/^\d{10}$/;
		var positvenumber=/[0-9 -()+]+$/;
		var chequeno=/^\d{6,14}$/;
	
		var   	name=document.getElementById('name').value.trim();
	        var  email=document.getElementById('email').value.trim();
		var  mobile=document.getElementById('mobile').value.trim();
                 var state_name=document.getElementById('state_name').value.trim();
                var city_name=document.getElementById('city_name').value.trim();
		  if(name==''){
			$("#namer").html('Please enter name');
			return false;
			}
			if (!(name.match(namecheck))) {
			$("#namer").html("Please enter valid name");	
			return false;
			}
			if(mobile==''){
			$("#mobiler").html('Please enter contact number');
			return false;
			}
			if (!(mobile.match(mobilenovalidation))) {
			$("#mobiler").html("Please enter valid contact number");	
			return false;
			}
			if(email==''){
			$("#emailr").html('Please enter email address');
			return false;
			}
			var email = email.toLowerCase();
			if (emailpattern.test(email) == false){
			$("#emailr").html("Please Enter Valid Email Address");					       
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
		}

</script>
<!--banner-->
<div class="banner-top">
	<div class="container" style="text-align: center;margin-top: 102px;">
		<h1>My Profile</h1>
		<em></em>
		<h2><a href="index.php">Home</a><label>/</label>My Profile</a></h2>
	</div>
</div>
	<!--content-->
		<div class="product">
			<div class="container">
			<div class="col-md-9">
			<?php if(isset($_SESSION['Success'])) { ?><div class="alert alert-success"><?php echo $_SESSION['Success']; ?></div><?php unset($_SESSION['Success']); } 
			  $users=mysql_query("select name,email,mobile,rid,profile_picture,referral_id,city_name,state_name from registration where rid='".$_SESSION['ID']."'");
			  $rows=mysql_fetch_array($users);
			?>
			<div class="mid-popular">
				<div class="material-card">
			<div class="panel-heading">MY Profile</div>
			<div class="panel-body">
			        <form method="post" enctype="multipart/form-data" action="post.php?action=UpdateMyProfiles">
					<div class="row">
					<div class="col-md-3 col-xs-5">
					<div id="tempprofileimage">
					<?php if(!empty($rows['profile_picture'])) { 
                                          $mysql_q=mysql_query("select sum(referral_point) as total_amt from referral_users where refferral_id='".$rows['referral_id']."'");
                                         // echo "select sum(referral_point) as total_amt from referral_users where refferral_id='".$rows['referral_id']."'";
                                          $point='';
                                           $count1=mysql_fetch_array($mysql_q);
                                           if(!empty( $count1['total_amt'])){ //echo 'Hi';
                                               $point= $count1['total_amt'];
                                           }else{  $point=0; }
                                          ?>
                                            <?php // echo $count1['total_amt']; exit; ?>
					<img src="images/profile/<?php echo $rows['profile_picture'];?>" class="img-responsive product-home-images img-thumbnail profilesimages"  style="" alt="">
					<?php }else{
                                            
                                         ?>
					<img src="images/default.jpg" class="img-responsive product-home-images img-thumbnail profilesimages"  style="" alt="">
					<?php } ?>
                                        
					</div>
					<input type="hidden" name="defaultimage" value="<?php echo $rows['profile_picture'];?>">
					<input type="file" name="profile_images" id="profile_images" onChange="profile_photor();">
                                      
                                       
					</div>
					<div class="col-md-8 col-xs-7">
					<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
					<label for="name">Name</label>
					<input type="text" class="form-control materail-input" name="name" id="name" value="<?php echo $rows['name']; ?>" onChange="namer()">
					<span id="namer" style="color:red;"></span>
					<span class="materail-input-block__line"></span>
					</div>
					<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
					<label for="name">Mobile No</label>
					<input type="text" class="form-control materail-input" name="mobile" id="mobile" onChange="mobiler()" value="<?php echo $rows['mobile']; ?>">
					<span id="mobiler" style="color:red;"></span>
					<span class="materail-input-block__line"></span>
					</div>
					<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
					<label for="name">Email</label>
					<input type="text" class="form-control materail-input" name="email" id="email" onChange="emailr()" value="<?php echo $rows['email']; ?>">
					<span id="emailr" style="color:red;"></span>
					<span class="materail-input-block__line"></span>
					</div>
                                              <div class="form-group">
                                            <br>
                                            <select type="text" placeholder="Password" class="form-control"  id="state_name" name="state_name" onChange="state_namer();">
                                           <?php if(!empty($rows['state_name'])){ ?>
                                              <option value="<?php echo $rows['state_name']; ?>"><?php echo $rows['state_name']; ?></option> 
                                           <?php }else{ ?>
                                               <option value="">Select State</option> 
                                           <?php } ?>
                                            <?php while($row=mysql_fetch_array($cities)){ ?>
                                             <option value="<?php echo $row['city_state']; ?>"><?php echo $row['city_state']; ?></option> 
                                            <?php } ?>
                                        </select>
					  <span id="state_namer" style="color:red;"></span>
				</div>
					                                        
			
                          
                           <div class="form-group" id="getdata">
                                           
                                            <select type="text" placeholder="Password" class="form-control"  id="city_name" name="city_name" onChange="city_namer();">
                                            <?php if(!empty($rows['city_name'])){ ?>
                                              <option value="<?php echo $rows['city_name']; ?>"><?php echo $rows['city_name']; ?></option> 
                                           <?php }else{ ?>
                                               <option value="">Select City</option> 
                                           <?php } ?>
                                            <?php while($row=mysql_fetch_array($cities1)){ ?>
                                             <option value="<?php echo $row['city_name']; ?>"><?php echo $row['city_name']; ?></option> 
                                            <?php } ?>
                                        </select>
				 <span id="city_namer" style="color:red;"></span>	
				</div>
                         
					<div class="form-group">
					<input type="submit"  class="btn material-btn material-btn_success main-container__column" name="sub" onClick="return regiterusers()" value="Save Change">
					</div>
					</div>
					
					</div>
					</form>
					
					</div></div>
				</div>

			</div>
		
			<div class="col-md-3 product-bottom"> 
			<?php include('my-right-side.php'); ?>
		</div>
			</div class="clearfix"></div>
				<!--products-->
			
			<!--//products-->
		<!--brand-->
		<!--<div class="container">
			<div class="brand">
				<div class="col-md-3 brand-grid">
					<img src="images/ic.png" class="img-responsive" alt="">
				</div>
				<div class="col-md-3 brand-grid">
					<img src="images/ic1.png" class="img-responsive" alt="">
				</div>
				<div class="col-md-3 brand-grid">
					<img src="images/ic2.png" class="img-responsive" alt="">
				</div>
				<div class="col-md-3 brand-grid">
					<img src="images/ic3.png" class="img-responsive" alt="">
				</div>
				<div class="clearfix"></div>
			</div>
			</div>-->
			<!--//brand-->
			</div>
			
		</div>
	<!--//content-->
		<!--//footer-->
	<?php include('footer.php'); ?>
	
	<?php } ?>