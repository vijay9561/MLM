<?php include('header.php'); ?>
<!--banner-->
	<div class="banner-top">
	<div class="container" style="text-align: center;margin-top: 102px;">
		<h1>Contact</h1>
		<em></em>
		<h2><a href="index.html">Home</a><label>/</label>Contact</a></h2>
	</div>
</div>	
		
			<div class="contact">
					
				<div class="contact-form">
					<div class="container">
					<div class="col-md-6 contact-left">
						<h3>Get In Touch</h3>
					<div class="address">
					<div class=" address-grid">
							<i class="glyphicon glyphicon-map-marker"></i>
							<div class="address1">
								<h3>Address</h3>
								<p>Buy Mart Trade India New Mondha Market, Cidco Nanded-431603, Maharashtra
								</p>
							</div>
							<div class="clearfix"> </div>
						</div>
						<div class=" address-grid ">
							<i class="glyphicon glyphicon-phone"></i>
							<div class="address1">
							<h3>Our Mobile:<h3>
								<p>+91 9823336898</p>
							</div>
							<div class="clearfix"> </div>
						</div>
						<div class=" address-grid ">
							<i class="glyphicon glyphicon-envelope"></i>
							<div class="address1">
							<h3>Email:</h3>
								<p><a href="mailto:contact@buymarttradeindia.com"> contact@buymarttradeindia.com</a></p>
							</div>
							<div class="clearfix"> </div>
						</div>
						<div class=" address-grid ">
							<i class="glyphicon glyphicon-bell"></i>
							<div class="address1">
								<h3>Open Hours:</h3>
								<p>Monday-Saturday, 9AM-7PM</p>
							</div>
							<div class="clearfix"> </div>
						</div>
</div>
				</div>
				<div class="col-md-6 contact-top material-card" style="padding: 24px;">
				<?php if(isset($_SESSION['success'])) { ?><div class="alert alert-danger"><?php echo $_SESSION['success']; ?></div><?PHP } unset($_SESSION['success']); ?>
					<h3>Want to work with me?</h3>
					<form method="post" action="post.php?action=SendMails">
						<div>
							<span> Name </span>		
							<input type="text" value="" id="name" name="name" required>						
						</div>
						<div>
							<span> Email </span>		
							<input type="email" value="" id="email" name="email" required>						
						</div>
						<div>
							<span>Mobile No</span>		
							<input type="text" value="" id="mobile" name="Mobile"  pattern="[7896][0-9]{9}"  title='Please enter 10 digit mobile number' required>	
						</div>
						<div>
							<span> Message</span>		
							<textarea id="message" name="message" required> </textarea>	
						</div>
						<label class="hvr-skew-backward">
								<input type="submit" value="Send" >
						</label>
</form>						
				</div>
		<div class="clearfix"></div>
		</div>
		</div>
		<!--<div class="map">
		
						<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15134.35184287535!2d73.933264!3d18.502314!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x6cea1f483159803b!2sGloballianz%2C+LED+Screen+Manufacturer!5e0!3m2!1sen!2sin!4v1497523048716"></iframe>
					</div>-->
	</div>

<!--//contact-->
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