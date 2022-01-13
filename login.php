<?php  include("header.php"); ?>
<style>
    @media(max-width:500px){
        .material-label-primary1 {
    background-color: #4092d9;
    border: 1px solid #4092d9;
    color: #fff;
    width: 100%;
    height: auto;
    padding: 14px;
    box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.298039);
}
.loginhear{margin-top: 28px !important;}
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
</style>
<!--banner-->
<div class="banner-top">
	<div class="container" style="text-align: center;margin-top: 102px;">
		<h1>Login</h1>
		<em></em>
		<h2><a href="index.html">Home</a><label>/</label>Login</a></h2>
	</div>
</div>
<!--login-->
<div id="loading" style="display:none;">
        <img id="loading-image" src="images/show_loader.gif" alt="Loading..." />
              </div>
<div class="container">

		<div class="login loginhear">
		<span class="label label-primary material-label material-label_primary material-label-primary1 material-label_lg main-container__column">Login</span>
		
			<form method="post" enctype="multipart/form-data" action="post.php?action=Loginusers">
			<div class="col-md-6 login-do material-card" style="    padding: 34px;">
			<?php if(isset($_SESSION['SUCESS'])){ ?><div class="alert alert-success"><?php echo $_SESSION['SUCESS']; ?></div><?php unset($_SESSION['SUCESS']); } ?>
			<?PHP if(isset($_SESSION['ERROR'])) { ?><div class="alert alert-danger"><?php echo $_SESSION['ERROR']; ?></div><?php unset($_SESSION['ERROR']); } ?>
			<?php if(isset($_GET['details'])){ ?>
			<input type="hidden" id="pid" name="pid" value="<?php echo $_GET['details']; ?>">
			<?php }else{ ?>
			<input type="hidden" id="pid" name="pid" value="">
			<?php } ?>
				<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
					<input type="email" placeholder="Email" name="email" required="" class="form-control materail-input">
					<i  class="glyphicon glyphiconform glyphicon-envelope"></i>
					<span class="materail-input-block__line"></span>
				</div>
				<div class="form-group materail-input-block materail-input-block_primary materail-input_slide-line">
					<input type="password" placeholder="Password" name="Password" required="" class="form-control materail-input">
					<i class="glyphicon glyphiconform glyphicon-lock"></i>
					<span class="materail-input-block__line"></span>
				</div>
				   <a class="news-letter " href="#" data-toggle="modal" data-target="#ForgotPassword">
						Forget Password
					   </a>
				<label class="btn material-btn material-btn_primary main-container__column">
					<input type="submit" value="login">
				</label>
			</div>
			<div class="col-md-6 login-right loginhear">
				 <h3>Completely Free Account</h3>
				 
                                 <p><strong>BUY MART Trade India</strong> offers everything from food to clothes to diapers, and provides a very large variety of merchandise. <strong>BUY MART Trade India</strong> has a website, where products can be bought or viewed online. <strong>BUY MART Trade India</strong> is the first Korean retailer to advance into India with the aim to become one of top leading global retailers.
                                     .</p>
				<a href="register.php" class=" btn material-btn material-btn_primary main-container__column">Register</a>

			</div>
			
			<div class="clearfix"> </div>
			</form>
		</div>

</div>

<div class="modal fade" id="ForgotPassword"  data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Forgot Password</h4>
      </div>
      <div class="modal-body">
      <form  role="form" method="post" id="feedbackform"  enctype="multipart/form-data"> 
	  <span id="notfoundemailid" style="color:red"></span>
         <div class="row">
            <div class="form-group col-md-12">
                <label class="col-md-3 control-lable" for="lastName">Enter Mobile No.<span class="star">*</span></label>
                <div class="col-md-9">
                    <input type="email" data-error="Please Enter Valid Email Address" id="email1" name="email1" onChange="emptyemailsvalidation()" placeholder="Please enter Mobile Number" class="form-control input-sm"/>
                 <div class="help-block with-errors" style="color:red" id="errormessageforgotpassword"></div>
				</div>
            </div>
        </div>     
        <div class="row">
		<div class="col-md-3"></div>
		
            <div class="col-md-9">
                <input type="button" value="Send" onClick="sendforgotpassword();" class="hvr-skew-backward btn-sm">
            </div>

        </div>
    </form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
<script>
function emptyemailsvalidation(){ if($('email1').val()==''){}else{ $("#errormessageforgotpassword").html(''); }}
function sendforgotpassword(){
		var email =document.getElementById('email1').value.trim();
		var emailpattern = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/;
                var mobilenovalidation=/^\d{10}$/;
		//var email=document.getElementById('forgotpasswordemails').value.trim();
		
                    if(email==''){
                    $("#errormessageforgotpassword").html('Please enter Mobile Number');
                     return false;
                        }
			if (!(email.match(mobilenovalidation))) {
			$("#errormessageforgotpassword").html("Please enter valid 10 digit mobile number");	
			return false;
			}
                    $("#loading").show();
		var postTo = 'post.php?action=SendForgotPassword';
		var data = { email:email,};
		jQuery.post(postTo, data,
		function(data) {
	      if(data==2){ 
                   $("#loading").hide();
	      $('#notfoundemailid').html(email+" This Mobile number not registered in buymarttradeindia");
              }else{
              window.location='login.php';   
              }		
	  });
	}
	
</script>
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
			<!--//brand-->
			</div>
			<br /><br />
		</div>
	<!--//content-->
		<!--//footer-->
	<?php include("footer.php"); ?>