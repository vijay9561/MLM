<?php include("header.php");
$cities=mysql_query("select *from cities group by city_state");
$cities1=mysql_query("select *from cities order by city_name asc");
?>
<!--banner-->
<script>
function namer(){if($('#name').val()==''){}else{ $('#namer').html(' '); }}
function emailr(){if($('#email').val()==''){  }else{ $('#emailr').html(' ');
var email=$("#email").val();
		$.ajax({   
		url: "post.php?action=duplicateemailaddress",
		type: "POST",
		data: {email:email},
		success: function(data){
		if(data==1){
		} else{
		document.getElementById('email').value='';
		$('#emailr').html(email+'&nbsp;This Email ID already Registered')
		}
		}
		});
 }}
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
function refferal_idr(){if($('#refferal_id').val()==''){ $("#refferal_idr").css('color','red');  }else{ $('#refferal_idr').html(' ');
               var refferal_id=$("#refferal_id").val();
		$.ajax({   
		url: "post.php?action=checking_refferal_id",
		type: "POST",
		data: {refferal_id:refferal_id},
		success: function(data){
		if(data==1){
                  $("#refferal_idr").html('Valid Referral Name');
                  $("#refferal_idr").css('color','green');
		} else if(data==3){
		document.getElementById('refferal_id').value='';
		$('#refferal_idr').html(refferal_id+'&nbsp;This Refferal ID Already 5 users use')
                 $("#refferal_idr").css('color','red');
		}else{
                  document.getElementById('refferal_id').value='';
		 $('#refferal_idr').html(refferal_id+'&nbsp;  This Refferal ID Invalid')
                  $("#refferal_idr").css('color','red');
                 }
		}
		});
 }}
 
 function mobiler(){if($('#mobile').val()==''){}else{ $('#mobiler').html(' ');
          var  mobile=$("#mobile").val();
        $.ajax({   
		url: "post.php?action=mobile_vendors_duplication",
		type: "POST",
		data: {mobile:mobile},
		success: function(data){
		if(data==1){
		} else{
		document.getElementById('mobile').value='';
		$('#mobiler').html(mobile+'&nbsp;This Mobile Number Already Registered')
		}
		}
		});
        }}
 
function passwordr(){if($('#password').val()==''){}else{ $('#passwordr').html(' '); }}
function regiterusers(){
        var namecheck = /[A-Za-z]+$/;      
		var emailpattern = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/;
		var mobilenovalidation=/^\d{10}$/;
		var positvenumber=/[0-9 -()+]+$/;
		var chequeno=/^\d{6,14}$/;
	
		var  name=document.getElementById('name').value.trim();
	        var  email=document.getElementById('email').value.trim();
		var  mobile=document.getElementById('mobile').value.trim();
		var  password=document.getElementById('password').value.trim();
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
			if(password==''){
			$("#passwordr").html('Please enter password');
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
				
	}
	
	

</script>
<div class="banner-top">
	<div class="container" style="text-align: center;margin-top: 102px;">
		<h1>Register</h1>
		<em></em>
		<h2><a href="index.php">Home</a><label>/</label>Register</a></h2>
	</div>
</div>
<!--login-->
<div class="container">
		<div class="login">
			
                    <div class="col-md-3"></div>
			<form method="post" action="post.php?action=userregistration">
			<div class="col-md-6 login-do">
                            <div class="material-card">
                             <div class="panel-heading material-panel__heading"><h2 class="panel-title">Registration For Buy Mart Trade India </h2></div>
                             <div class="panel-body">
			<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
					<input type="text" placeholder="Name"  name="name" id="name" onChange="namer()" class="form-control materail-input">
					<i  class="glyphicon glyphiconform glyphicon-user"></i>
					<span class="materail-input-block__line"></span>
			
				</div>
                            <span id="namer" style="color:red;"></span>
                           
					<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
					<input type="text" placeholder="Mobile Number" id="mobile" name="mobile" onChange="mobiler();" class="form-control materail-input">
					<i  class="glyphicon glyphiconform glyphicon-phone"></i>
					<span class="materail-input-block__line"></span>
				
				</div>
                    <span id="mobiler" style="color:red;"></span>
                                       
				<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
					<input type="text" placeholder="Email"  id="email" name="email" onChange="emailr();" class="form-control materail-input">
					<i  class="glyphicon glyphiconform glyphicon-envelope"></i>
					<span class="materail-input-block__line"></span>
				
				</div>
					<span id="emailr" style="color:red;"></span>
				<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
					<input type="password" placeholder="Password"  id="password" name="password" onChange="passwordr();" class="form-control materail-input">
					<i class="glyphicon glyphiconform glyphicon-lock"></i>
					<span class="materail-input-block__line"></span>
				
				</div>
					<span id="passwordr" style="color:red;"></span>
                                        
                                          <div class="form-group">
                                            <br>
                                            <select type="text" placeholder="Password" class="form-control"  id="state_name" name="state_name" onChange="state_namer();">
                                            <option value="">Select State</option> 
                                            <?php while($row=mysql_fetch_array($cities)){ ?>
                                             <option value="<?php echo $row['city_state']; ?>"><?php echo $row['city_state']; ?></option> 
                                            <?php } ?>
                                        </select>
					
				</div>
					                                        
			  <span id="state_namer" style="color:red;"></span>
                          
                           <div class="form-group" id="getdata">
                                           
                                            <select type="text" placeholder="Password" class="form-control"  id="city_name" name="city_name" onChange="city_namer();">
                                            <option value="">Select City</option> 
                                            <?php while($row=mysql_fetch_array($cities1)){ ?>
                                             <option value="<?php echo $row['city_name']; ?>"><?php echo $row['city_name']; ?></option> 
                                            <?php } ?>
                                        </select>
					
				</div>
                          <span id="city_namer" style="color:red;"></span>
				  <!-- <a class="news-letter " href="#">
						 <label class="checkbox1"><input type="checkbox" name="checkbox" ><i> </i>Remember Password</label>
					   </a>-->
                                   <br>
				<label class="btn material-btn material-btn_primary main-container__column">
                                   
					<input type="submit" value="Submit" onClick="return regiterusers()">
				</label>
			
			</div>
                             </div>
			</div>
			
			
			<div class="clearfix"> </div>
			</form>
		</div>
<br /><br />
</div>

<!--//login-->

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
			
		<!--//footer-->
	<?php include("footer.php"); ?>