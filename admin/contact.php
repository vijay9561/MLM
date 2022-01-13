<?php include("header.php"); ?>
<!-- //header -->
   <!--banner-slider-->
		     <div class="banner two">
			 <h2 class="tittle wow fadeInUp"  data-wow-duration="1s" data-wow-delay=".3s">Contact Us</h2>
             </div>
			<!--//end-banner-->
	<!--start-albums-->
	 <div class="clearfix"></div>
	 <!--contact-->
  <div class="contact" id="contact">
		<div class="container">
			 <div class="contact-inner">
			 <?php if(isset( $_SESSION['success'])){ ?>
			 <div class="alert alert-success">
                   <?php echo  $_SESSION['success']; ?>
</div>
			 <?php unset( $_SESSION['success']); } ?>
				  <div class="col-md-7 contact-text">
				      <h3 class="tittle con wow fadeInDown"  data-wow-duration="1s" data-wow-delay=".3s">Company info</h3>
					   <div class="contact-text-inner wow fadeInUp"  data-wow-duration="1s" data-wow-delay=".3s">
					          <address>
								  <strong>Address</strong><br>
								  795 Folsom Ave, Suite 600<br>
								  San Francisco, CA 94107<br>
								  <abbr title="Phone">P :</abbr> (123) 456-7890
								</address>
								<!-- <img src="images/contact.jpg" alt="img20"/> -->
								 <div class="lost-para">
							      <p>Fax : 123345456</p>
								  <p>Mobile : 0123345456</p>
								</div>
                      </div>
					   <div class="contact-text-inner wow fadeInUp"  data-wow-duration="1s" data-wow-delay=".3s">
					          <address>
								  <strong>Contact</strong><br>
								  795 Folsom Ave, Suite 600<br>
								  San Francisco, CA 94107<br>
								  <a href="mailto:info@example.com">mail@example.com</a>
							   </address>
							   <!-- <img src="images/contact1.jpg" alt="img20"/> -->
							    <div class="lost-para">
							      <p>Fax : 123345456</p>
								  <p>Mobile : 0123345456</p>
								</div>
					   </div>
					   <div class="clearfix"></div>
				  </div>
				  <div class="col-md-5 con-form wow fadeInUp"  data-wow-duration="1s" data-wow-delay=".3s">
				     <form method="post" action="post.php?action=SendMails">
						<p class="your-para">Your Name:</p>
						 <input type="text" required=""   placeholder="Your Name" name="name">
						<p class="your-para">Your Mail:</p>
						 <input type="email" required=""  placeholder="Your Mail" name="email">
						 <p class="your-para">Your Mobile:</p>
						 <input type="text" name="Mobile" placeholder="Your Mobile" required=""  pattern="[789][0-9]{9}"  title='Please enter 10 digit mobile number'>
						<p class="your-para">Your Message:</p>
						<textarea required="" placeholder="Your Message" name="message"></textarea>

						<div class="send">
							<input type="submit" value="Send" >
						</div>
					</form>

				  </div>
				  <div class="clearfix"></div>
			 </div>
			<div class="map wow fadeInUp"  data-wow-duration="1s" data-wow-delay=".3s">
            <!--  <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1578265.0941403757!2d-98.9828708842255!3d39.41170802696131!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x54eab584e432360b%3A0x1c3bb99243deb742!2sUnited+States!5e0!3m2!1sen!2sin!4v1407515822047"> </iframe> -->
			 <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15133.094255998076!2d73.8534051!3d18.5165359!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x8183304bb8c2684f!2sAppa+Balwant+Intersection+Bus+Stop!5e0!3m2!1sen!2sin!4v1487838489708" frameborder="0" style="border:0" allowfullscreen></iframe>
			 </div>
		</div>
	</div>

	<!--//contact-->
		<!--footer--->
		<?php include("footer.php"); ?>