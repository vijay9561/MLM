<?php include('config.php'); ?>
<!DOCTYPE html>
<html>
<head>
<title>G-CART</title>
<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<!-- Custom Theme files -->
<!--theme-style-->
<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />	
<!--//theme-style-->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Ecommerece Website" />

<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!--theme-style-->
<link href="css/style4.css" rel="stylesheet" type="text/css" media="all" />	
<link href="font-awesome/css/font-awesome.min.css" type="text/css" media="all" rel="stylesheet">
<!--//theme-style-->
<script src="js/jquery.min.js"></script>
<link rel="stylesheet" href="css/jquery-ui.css">
<script src="js/jquery-ui.js"></script>
 <link href="css/tinyslide.css" rel="stylesheet" />
 <script src="js/tinyslide.js" /></script>

<!--- start-rate---->
<script src="js/jstarbox.js"></script>
	<link rel="stylesheet" href="css/jstarbox.css" type="text/css" media="screen" charset="utf-8" />
		<script type="text/javascript">
			jQuery(function() {
			jQuery('.starbox').each(function() {
				var starbox = jQuery(this);
					starbox.starbox({
					average: starbox.attr('data-start-value'),
					changeable: starbox.hasClass('unchangeable') ? false : starbox.hasClass('clickonce') ? 'once' : true,
					ghosting: starbox.hasClass('ghosting'),
					autoUpdateAverage: starbox.hasClass('autoupdate'),
					buttons: starbox.hasClass('smooth') ? false : starbox.attr('data-button-count') || 5,
					stars: starbox.attr('data-star-count') || 5
					}).bind('starbox-value-changed', function(event, value) {
					if(starbox.hasClass('random')) {
					var val = Math.random();
					starbox.next().text(' '+val);
					return val;
					} 
				})
			});
		});
		
		</script>
		<script>
		

		</script>
		<link href="css/form.css" rel="stylesheet" type="text/css" media="all" />
		
<!---//End-rate---->

</head>
<body>
<style>
.fixedheaders{
position: fixed;
width: 100%;
background-color: #080707;
z-index: 99999;
border-bottom: 1px solid #121112;
box-shadow: 0px 0px 4px 2px;
top: 0px;
}
 #menu1 li a {
    display: block;
    padding: 3px 20px;
    clear: both;
    font-weight: normal;
    line-height: 1.42857143;
    color: #f6f6f6;
    white-space: nowrap;
}
.successmessages{ position:fixed; width:60%; left:15%; right:15%; top:20%;z-index:999;}
.successmessages12{ position:fixed; width:60%; left:15%; right:15%; top:20%;z-index:999999999;}
@media(max-width:500px){
.successmessages {
    position: fixed;
    width: 100%;
    left: 2%;
    right: 2%;
    top: 20%;
    z-index: 999;
}
.successmessages12 {
    position: fixed;
    width: 100%;
    left: 2%;
    right: 2%;
    top: 20%;
    z-index: 999;
}
}

#loading {
  width: 100%;
  height: 100%;
  top: 0px;
  left: 0px;
  position: fixed;
  display: block;
  opacity: 0.7;
  z-index: 99999999;
  text-align: center;
}

#loading-image {
  position: absolute;
  top: 10%;
  left:40%;
  z-index: 99999999;
}
.currroseroverr{ cursor:pointer;}
	.currroseroverr:hover{
	background-color:red;
	
	}
	
	.mynewdiv{background: rgba(22, 24, 23, 0.8) none repeat scroll 0% 0%;padding: 10px;}
	
.image-div {
position: relative;
}
.mybuttons {
position: absolute;
z-index: 9999;
right: -8px;
top: -14px;
border-radius: 50%;
padding: 0px 4px
}
</style>


<!--header-->
<div id="loading" style="display:none;">
        <img id="loading-image" src="images/show_loader.gif" alt="Loading..." />
              </div>
<div class="header fixedheaders">
<div class="container">
		<div class="head">
			<div class=" logo">
				<a href="index.php"><img src="images/logo.png" alt=""></a>	
			</div>
		</div>
	</div>
	<div class="header-top">
		<div class="container">
		<div class="col-sm-5 col-md-offset-2 header-social">
					
					<ul >
						<li><a href="#"><i></i></a></li>
						<li><a href="#"><i class="ic1"></i></a></li>
						<li><a href="#"><i class="ic2"></i></a></li> 
						<li><a href="#"><i class="ic3"></i></a></li> 
						<li><a href="#"><i class="ic4"></i></a></li> 
					</ul>
				</div>
				
			<div class="col-sm-5  header-login">		
					
					<ul>
					<?php if(!isset($_SESSION['ID'])){ ?>
						<li><a href="login.php">Login</a></li>
						<li><a href="register.php">Register</a></li>
						<?php }else{ $query=mysql_query("select *from registration where rid='".$_SESSION['ID']."'"); $users=mysql_fetch_array($query); ?>
						<li><a href="#"  class="dropdown-toggle"  id="menu1" data-toggle="dropdown">Hi&nbsp;<?php echo substr($users['name'],0,12).'..'; ?> <span class="caret"></span></a>
						
						<ul class="dropdown-menu" role="menu" aria-labelledby="menu1" id="menu1">
						<li role="presentation"><a role="menuitem" tabindex="-1" href="my-profile.php"><i class="fa fa-user"></i> My Profile</a></li>
						<li role="presentation"><a role="menuitem" tabindex="-1" href="my-orders.php"><i class="fa fa-shopping-cart"></i> My Order</a></li>
						<li role="presentation"><a role="menuitem" tabindex="-1" href="wishlist.php"><i class="fa fa-heart-o"></i> My Wishlist</a></li>
						<li role="presentation"><a role="menuitem" tabindex="-1" href="my-review.php"><i class="fa fa-comment"></i> My Review</a></li>
						<li role="presentation" class="divider"></li>
						<li role="presentation"><a role="menuitem" tabindex="-1" href="#" data-toggle="modal" data-target="#changepasswords"><i class="fa fa-pencil"></i> Change Password</a></li>
						<li role="presentation"><a role="menuitem" tabindex="-1" href="logout.php"> <i class="fa fa-circle-o-notch"></i> Logout</a></li>
						</ul>
						</li>
						<li><a href="cart.php">Checkout</a></li>
						<li><span id="cartitmeswwee" style="color:#FFFFFF;"></span></li>
						<?PHP }  //echo $_SESSION['ID'];?>
						<!--<li><a  href="#">About</a></li> -->
						<li ><a  href="contact.php">Contact</a></li>
					</ul>
			</div>
				<div class="clearfix"> </div>
		</div>
		</div>
		
		<div class="container">
		
			<div class="head-top">
			
		 <div class="col-sm-8 col-md-offset-2 h_menu4">
				<nav class="navbar nav_bottom" role="navigation">
 
 <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header nav_2">
      <button type="button" class="navbar-toggle collapsed navbar-toggle1" data-toggle="collapse" data-target="#bs-megadropdown-tabs">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
     
   </div> 
   <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-megadropdown-tabs">
        <ul class="nav navbar-nav nav_1">
		<?php  $maincategory=mysql_query("select category_name,cid from category order by cid asc");  while($cat=mysql_fetch_array($maincategory)) { ?>
    		<li  class="dropdown mega-dropdown active">
			    <a  class="color1" href="product.php?maincategory=<?php echo $cat['cid']; ?>" class="dropdown-toggle" data-toggle="dropdown"><?php echo ($cat['category_name']); ?><span class="caret"></span></a>				
				<div class="dropdown-menu ">
                    <div class="menu-top">
					<?php $subcategories=mysql_query("select  category_name,mid,scid from sub_category where mid='".$cat['cid']."'"); while($subcat=mysql_fetch_array($subcategories)) { ?>
						<div class="col1">
							<div class="h_nav">
								<h4 style="font-size:16px;"><a href="product.php?subcategory=<?php echo $subcat['scid']; ?>"><?php echo $subcat['category_name']; ?></a></h4>
									<ul>
								<?php $subsubcategories=mysql_query("select  category_name,cid,scid,sscid from sub_sub_category where scid='".$subcat['scid']."'"); 
								while($susubbcat=mysql_fetch_array($subsubcategories)) { ?>
										<li><a href="product.php?subsubcategory=<?php echo $susubbcat['sscid']; ?>"><?php echo $susubbcat['category_name']; ?></a></li>
										<?php } ?>
										
									</ul>	
							</div>							
						</div>
						<?php } ?>
						
						
			
						<div class="clearfix"></div>
					</div>                  
				</div>				
			</li>
			<?php } ?>
			
			<!--<li><a class="color3" href="#">Sale</a></li> -->
			
        </ul>
     </div><!-- /.navbar-collapse -->

</nav>
<style>
.countcart{
position: absolute;
top:2px;
left: 173px;
}
.cart_images{color: white;
font-size: 28px;
margin-top: 2px;}
.imagescarts{width:33px;}
@media(max-width:500px){
.countcart{
position: absolute;
top: 8px;
left: 176px;
font-size: 15px;
}
.imagescarts{width:33px;}
}
@media(max-width:320px){
.countcart {
    position: absolute;
    top: 8px;
    left: 148px;
    font-size: 15px;
}
}
</style>
			</div>
			<div class="col-sm-2 search-right">
				<ul class="heart">
				<?php if(isset($_SESSION['ID'])) {  ?>
				<li>
				<a href="wishlist.php"  title="Wishlist">
				<span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
				</a></li>
				<?php } ?>
				<li><a class="play-icon popup-with-zoom-anim" title="Search" href="#small-dialog"><i class="glyphicon glyphicon-search"> </i></a></li>
					</ul>
					<?php if(isset($_SESSION['ID'])) {  ?>
					<?php $cartcount=mysql_query("select *from cart where uid='".$_SESSION['ID']."'"); $COUNT=mysql_num_rows($cartcount);  ?>
					<div class="cart box_1">
						<a href="cart.php" title="Cart">
						<h3> <div class="total">
						
                                                        <span class="simpleCart_total countcart"  id="cartitmes" style="color:white;"><?php echo $COUNT; ?></span>
                                                        <i class="fa fa-shopping-cart cart_images"></i></div></h3>
						</a>
					</div>
					<?PHP } ?>
					<div class="clearfix"> </div>
					
						<!----->

						<!---pop-up-box---->					  
			<link href="css/popuo-box.css" rel="stylesheet" type="text/css" media="all"/>
			<script src="js/jquery.magnific-popup.js" type="text/javascript"></script>
			<!---//pop-up-box---->
			<div id="small-dialog" class="mfp-hide">
				<div class="search-top">
					<div class="login-search">
					<form method="post">
						<input type="text" value="" placeholder="Search" id="jobpostsearch" name="jobpostsearch"  onChange="jobpostsearchr();">	
						<input type="submit" class="btn btn-primary" onClick="return product_details()">
						<label style="color:red;" id="searchresults"></label>
						<input type="hidden" name="hiddensearchjob" id="hiddensearchjob">	
						</form>
					</div>
				</div>				
			</div>
		 <script>
			$(document).ready(function() {
			$('.popup-with-zoom-anim').magnificPopup({
			type: 'inline',
			fixedContentPos: false,
			fixedBgPos: true,
			overflowY: 'auto',
			closeBtnInside: true,
			preloader: false,
			midClick: true,
			removalDelay: 300,
			mainClass: 'my-mfp-zoom-in'
			});
																						
			});
			//var j = jQuery.noConflict();
$(document).ready(function(){
             $("#jobpostsearch").autocomplete({
                     source: "search-result.php",
                     minLength: 1,
                    select: function(event, data) {
                    $("#jobpostsearch").val(data.item.value);
					$("#hiddensearchjob").val(data.item.id);
                    },
                });
           });	
		   function  product_details(){
		   var jobpostsearch=document.getElementById('jobpostsearch').value.trim();
		   if(jobpostsearch==''){
		      $("#searchresults").html("Please enter search keywords");
			  return false;
		     }
			window.location="product-search.php?search="+jobpostsearch;
			return false;
		   }
		function jobpostsearchr(){ if($("#jobpostsearch").val()==''){ }else{ $("#searchresults").html(" "); } }
		</script>		
	
						<!----->
			</div>
			<div class="clearfix"></div>
		</div>	
	</div>	
</div>

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
				    $("#passwordSuccess").show();
					$("#passwordSuccess").html('<b>Password Changed Successfully</b>');
					document.getElementById('oldPassword').value="";
					document.getElementById('newPassword').value="";
					document.getElementById('newPassword1').value="";
					//window.location='profile.php?Profile=Change-Passowrd';
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

	  function mycheckedbox(id){
    myid=$("#checkid"+id).val();
	cnt = $("input[name='cbox']:checked").length;
	var maxAllowed = 3;
	if(cnt<=3){
    if (!$("#checkid"+id).is(':checked')) 
      {
			$.ajax({
			url: "post.php?action=DeletemyProduct",
			type: 'POST',
			data: {myid: myid},
			success: function(data) {
			if(data==1){
			///alert("Delete Successfully");
			//$("#getmycompaireproduct").fadeOut().html(data).fadeIn('slow');
					$.ajax({
					url: "post.php?action=GetMyProduct",
					type: 'POST',
					data: {},
					success: function(data) {
					$("#getmycompaireproduct").html(data).fadeIn('slow');
					}
					});
			}else{
			alert("Not Deleted");
			}
			}
			});
        }else{
          $.ajax({
            url: "post.php?action=InsertMyProduct",
            type: 'POST',
            data: {myid: myid},
            success: function(data) {
			if(data==4){
			alert("Please seme category select to the compare product");
			  return false;
			  }else if(data==1){
				$.ajax({
				url: "post.php?action=GetMyProduct",
				type: 'POST',
				data: {},
				success: function(data) {
				$("#getmycompaireproduct").html(data).fadeIn('slow');
				}
				});
             }else{
             alert('You can select maximum 3 products to compare  Or Already Added');
		     $("#checkid"+id).prop("checked", "");
          }
       }
      });
     }
	}else{
	  $("#checkid"+id).prop("checked", "");
	alert('You can select maximum ' + maxAllowed + ' Product Compaire!!');
   }
 }
 function clearall(){
                 $.ajax({
					url: "post.php?action=DeleteAllProduct",
					type: 'POST',
					data: {},
					success: function(data) {
					if(data==1){
					  $('input[name=cbox').prop('checked', false);
					$("#getmycompaireproduct").hide();
					location.reload();
					}
					}
					});
 }
    function compairemyproduct(){
	    window.location='product-compaire.php';
		 return false;
	}

function temimagesdelete(id) {
        var r=confirm('Are you sure you want to delete this product');
		if(r==true)
		 {
		   //$("#checkid"+id).addClass('btn-danger');
         // $("#checkid"+id).removeClass('btn-primary');
        $.ajax({
            url: "post.php?action=DeleteImages",
            type: 'POST',
            data: {id:id},
            success: function(data) {
			if(data==3){
			$("#getmycompaireproduct").hide();
               
		   }else if(data==1){
				$.ajax({
				url: "post.php?action=GetMyProduct",
				type: 'POST',
				data: {},
				success: function(data) {
				$("#getmycompaireproduct").fadeOut().html(data).fadeIn('slow');
				}
				});
		   }else{
		   alert("not deleted")
		   }
          }
        });
        return false;
	} else
	{
	   return false;	
     }
    }


 function mycheckedboxmy(id){
          $.ajax({
            url: "post.php?action=InsertMyProduct",
            type: 'POST',
            data: {myid: id},
            success: function(data) {
           if(data==4){
			alert("Please seme category select to the compare product");
			  return false;
			  }else if(data==1){
				$.ajax({
				url: "post.php?action=GetMyProduct",
				type: 'POST',
				data: {},
				success: function(data) {
				$("#getmycompaireproduct").fadeOut().html(data).fadeIn('slow');
				}
				});
             }else{
             alert('You can select maximum 3 products to compare  Or Already Added');
          }
       }
      });
 }
 function compaireviewsprices123(){
window.location="compare-product.php";
return false;
}

</script>

<div id="changepasswords" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Change Password</h4>
              </div>
              <div class="modal-body">
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
                    <input type="button"  class="item_add hvr-skew-backward" value="Change Password" onClick="return changePassword();">
                  </div>
                </div>
              </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="item_add hvr-skew-backward" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
		
		<div class="fixedmyproductcompaire" id="getmycompaireproduct">
	
<?php  $ipaddress='';
 if(isset($_SESSION['RemoteIpAddress'])){
$ipaddress=$_SESSION['RemoteIpAddress'];
}

 $query=mysql_query("select *from compaire_product where ip_address='$ipaddress'");
 if(mysql_num_rows($query)>=1){
 $data='<div class="mynewdiv">';
  while($rows=mysql_fetch_array($query)){
 $product=mysql_query("select *from product_details where pid='".$rows['pid']."'"); $p=mysql_fetch_array($product);
  $images=mysql_query("select *from product_images where pid='".$rows['pid']."' order by piid asc limit 1"); $img=mysql_fetch_array($images);
  $data.='<div class="image-div">
         <img class="img-thumbnail myimage" src="admin/images/product/'.$img['product_path'].'" style="height:100px;height:100px;width:138px;">
		  <button type="button" class="btn btn-danger mybuttons" title="Compare Product" name="cbox"  onClick="temimagesdelete('.$rows['pid'].')"><i class="glyphicon glyphicon-remove" style="color:#FFFFFF;"></i></button>
		 </div>
		<h6><i class="fa fa-incr  fa-1x" aria-hidden="true"></i>'.$p['discount_price'].'Rs
		'.substr($p['title'],0,12).'</h6>';
 }
 if(mysql_num_rows($query)>=2){
  $expireAfter = 30;
  if(isset($_SESSION['last_action'])){
    $secondsInactive = time() - $_SESSION['last_action'];
    $expireAfterSeconds = $expireAfter * 60;
    if($secondsInactive >= $expireAfterSeconds){
	   unset($_SESSION['last_action']);
	   }
	 $data.='<input type="submit" class="item_add hvr-skew-backward customebutton" value="Compare" style="font-size: 13px;" onClick="return compaireviewsprices123()">';
	   }else{
	  $data.='<input type="submit" class="item_add hvr-skew-backward customebutton" value="Compare" style="font-size: 13px;" onClick="return compaireviewsprices123()">';
 }
}
 // $data.='<input type="submit" class="btn btn-primary customebutton" value="Compare" onClick="return compaireviewsprices123()">';
 $data.='&nbsp;&nbsp;<input type="button" onClick="clearall()" class="item_add hvr-skew-backward" style="font-size: 13px;" value="Clear"></div>';
 echo $data;
 }
  ?>
	</div>