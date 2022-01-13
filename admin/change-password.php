<?php include('header.php'); 
		if(isset($_SESSION['JOBPORTALADMIN'])) { 
?>
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
		<div id="loading" style="display:none;">
        <img id="loading-image" src="images/show_loader.gif" alt="Loading..." />
              </div>

			<ol class="breadcrumb">
				<li><a href="index.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
				<li class="active">Change Password</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<!--<h1 class="page-header">Change Password</h1>-->
				<?php if(isset($_SESSION['SUCESSMSG'])){ ?>
				<div class="alert bg-success" role="alert">
			<svg class="glyph stroked checkmark"><use xlink:href="#stroked-checkmark"></use></svg><?php echo $_SESSION['SUCESSMSG']; ?><a href="#" class="pull-right"><span class="glyphicon glyphicon-remove"></span></a>
				</div>
				<?php unset($_SESSION['SUCESSMSG']); } ?>
			</div>
		</div><!--/.row-->
		
		
	
		<div class="row">
			<div class="col-md-3"></div>
		<div class="col-md-6">
	
		 <div class="panel panel-primary">
            <div class="panel-heading"><h3 style="color: white;font-size: 25px;text-align: center;margin-top:-3px;">Change Password</h3></div>
			 <div class="panel-body">
		<form class="form-horizontal" id="addyourdocumentfile"  enctype="multipart/form-data" method="post">
			<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8">
			<div id="passwordSuccess" class="alert alert-success"  style="display:none;"></div>
                <div class="form-group">
                  <label class="control-label">Old Password <b style="color:red;">*</b></label>
                 
                     <input type="password" class="form-control"  maxlength="10"  onChange="remove_error_oldpassword()" ng-model="user.oldPassword" id="oldPassword" name="oldPassword">
									
									  <span id="oldPasswordError" style="color:red; font-size: 15px;"></span> </div>
              
                <div class="form-group">
                  <label class="control-label">Enter New Password  <b style="color:red;">*</b></label>
                  
                  <input type="password" class="form-control" onChange="remove_error_newpassword()" onKeyUp="CheckPasswordStrength(this.value)"  maxlength="10"  ng-model="user.newPassword" id="newPassword" name="newPassword">
                   <span id="password_strength"></span> <br />
									  <span id="newPasswordError" style="color:red; font-size: 15px;"></span> </div>
               
				
				<div class="form-group">
                  <label class="control-label">Re-Enter New Password <b style="color:red;">*</b></label>
                
               <input type="password" class="form-control"  maxlength="10" onChange="remove_errror_renpassword()"  ng-model="user.newPassword1" id="newPassword1" name="newPassword1">
                  	  <span id="newPasswordError1" style="color:red; font-size: 15px;"></span> </div>
					  </div>
					  <div class="col-md-2"></div>
					  </div>
           
                <div class="form-group">
                  <label class="col-sm-3 control-label"></label>
                  <div class="col-sm-9">
                    <input type="button"  class="btn btn-primary" value="Change Password" onClick="return changePassword();">
                  </div>
                </div>
              </form>
			  </div></div>
		
		</div>
			<div class="col-md-3"></div>
		</div>
		
		<br><br>
	</div>
	<?php include('footer.php'); ?>
	
	
<?php }else{  
   header('Location:login.php');
?>
<?php } ?>

<script>
function remove_errror_renpassword (){ if($("#newPassword1").val()=='') { } else { $('#newPasswordError1').html(' '); } }
	function remove_error_newpassword (){ if($("#newPassword").val()=='') { } else { $('#newPasswordError').html(' '); $('#password_strength').html(' '); } 
	 var regularExpression = /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,16}$/;
	  var password=document.getElementById('newPassword').value.trim();
			 var regularExpression = /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,16}$/;
        if (!(password.match(regularExpression))) {
           // alert("Password must contain at least six character,one digit,one special character");
			 $('#newPasswordError').html('Password Must Contain At Least Six Character,One Digit,One Special Character');
              password.focus()
            return false;
        }  else {
		$('#newPasswordError').html(' ');
		}
	}
		function remove_error_oldpassword (){ if($("#oldPassword").val()=='') { } else { $('#oldPasswordError').html(' '); } }

		function changePassword(){
			if(document.getElementById('oldPassword').value==""){
				$("#oldPasswordError").html('Please Enter Old Password');
				$('#oldPassword').focus();
				return false;
			}
			if(document.getElementById('newPassword').value==""){
				$("#newPasswordError").html('Please Enter New Password');
				$('#newPassword').focus();
				return false;
			}
			 var password=document.getElementById('newPassword').value.trim();
			 var regularExpression = /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,16}$/;
        if (!(password.match(regularExpression))) {
           // alert("Password must contain at least six character,one digit,one special character");
			 $('#newPasswordError').html('Password Must Contain At Least Six Character,One Digit,One Special Character');
              password.focus()
            return false;
        }  
			if(document.getElementById('newPassword1').value==""){
				$("#newPasswordError1").html('Please Re-enter new password');
				$('#newPassword1').focus();
				return false;
			}
			var oldPassword=document.getElementById('oldPassword').value.trim();
			var newPassword=document.getElementById('newPassword').value.trim();
			var newPassword1=document.getElementById('newPassword1').value.trim();
			if(newPassword!=newPassword1){
				$("#newPasswordError1").html('Password Does Not Match');
				return false;
			}
			var postTo = 'post.php?action=change-password';
			var data = {
				oldPassword: oldPassword,
				newPassword: newPassword
			};
			jQuery.post(postTo, data,
			function(data) {
				if(data==1){
				   // $("#passwordSuccess").show();
					$("#passwordSuccess").html('<b>Password Changed Successfully</b>');
					document.getElementById('oldPassword').value="";
					document.getElementById('newPassword').value="";
					document.getElementById('newPassword1').value="";
					window.location='change-password.php';
					$('#passwordError').hide();
				}else{
					$("#oldPasswordError").html('<b>Incorrect Old Password</b>');
				}
			});
		}
		
		function CheckPasswordStrength(password) {
        var password_strength = document.getElementById("password_strength");
 
        //TextBox left blank.
        if (password.length == 0) {
            password_strength.innerHTML = "";
            return;
        }
 
        //Regular Expressions.
        var regex = new Array();
        regex.push("[A-Z]"); //Uppercase Alphabet.
        regex.push("[a-z]"); //Lowercase Alphabet.
        regex.push("[0-9]"); //Digit.
        regex.push("[$@$!%*#?&]"); //Special Character.
 
        var passed = 0;
 
        //Validate for each Regular Expression.
        for (var i = 0; i < regex.length; i++) {
            if (new RegExp(regex[i]).test(password)) {
                passed++;
            }
        }
 
        //Validate for length of Password.
        if (passed > 2 && password.length > 8) {
            passed++;
        }
 
        //Display status.
        var color = "";
        var strength = "";
        switch (passed) {
            case 0:
            case 1:
                strength = "Weak";
                color = "red";
                break;
            case 2:
                strength = "Good";
                color = "darkorange";
                break;
            case 3:
            case 4:
                strength = "Strong";
                color = "green";
                break;
            case 5:
                strength = "Very Strong";
                color = "darkgreen";
                break;
        }
        password_strength.innerHTML = strength;
        password_strength.style.color = color;
    }
$(document).ready(function(){
		$('.pull-right').click(function(){
		$('.bg-success').hide();
		});
		});
	 
</script>


