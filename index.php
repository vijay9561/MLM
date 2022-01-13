
<!--banner-->
<?php include("header.php"); ?>
<style>
.bannersmallimages{
 width:30%;
 height:230px;
}
.slider_images{width:100%;height:300px !important;}
.carousel {
    position: relative;
    margin-top:100px;
}
@media(max-width:500px){
.bannersmallimages{
 width:35%;
 height:100px;
}
.carousel-inner {
    position: relative;
    width: 100%;
    overflow: hidden;
    margin-top: -16px;
}
.slider_images {
    width: 100%;
    height: 200px !important;
}
}

</style>
<script>
$('#carouselExampleIndicators').carousel({
        interval: 4000
    })


</script>
<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner">
      <div class="item active">
          <img src="images/slider1.jpg" alt="Los Angeles" class="slider_images">
      </div>

      <div class="item">
          <img src="images/slider2.jpg" alt="Chicago" class="slider_images">
      </div>
    
      <div class="item">
          <img src="images/slider3.jpg" alt="New york" class="slider_images">
      </div>
    </div>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
	<!--content-->
		<div class="content">
			<div class="container">
				<!--products-->
			<div class="content-mid">
				<h3>Latest Product</h3>
				 <?php $query=mysql_query("select *from product_details ORDER BY rand(), pid ASC limit 0,44");  //echo "select *from product_details order by pid desc limit 0,8"; ?>
				<label class="line"></label>
				<div class="mid-popular row">
				<?php if(mysql_num_rows($query)>=1){ while($rows=mysql_fetch_array($query)) {   $productimages=mysql_query("select *from product_images where pid=".$rows['pid']." order by piid asc");
				 $images=mysql_fetch_array($productimages);
				?>
					<div class="col-md-3 item-grid simpleCart_shelfItem" style="margin-bottom:30px;">
					<div class=" mid-pop">
					<div class="pro-img">
						<img src="admin/images/product/<?php echo $images['product_path']; ?>" class="img-responsive product-home-images" alt="">
						<div class="zoom-icon ">
				<!--<a href="admin/images/product/<?php echo $images['product_path']; ?>" rel="title" class="b-link-stripe b-animate-go  thickbox picture"><i class="glyphicon glyphicon-search icon "></i></a>-->
						<a href="product-details.php?details=<?php echo $rows['pid']; ?>"><i class="glyphicon glyphicon-eye-open icon"></i></a>
						<?php if(isset($_SESSION['ID'])) { ?>
                                                <a  style="cursor:pointer;"  onClick="return wislistaddedproducts(<?php echo $rows['pid'] ?>)" class=""><span class="glyphicon glyphicon-heart icon" aria-hidden="true"></span></a>
                                                <a href="#" style="cursor:pointer;" data-toggle="modal" data-target="#product_enquiry" onClick="return product_enquiry_details_get(<?php echo $rows['pid']; ?>)"><span class="glyphicon glyphicon-comment icon" aria-hidden="true"></span></a>
						<?PHP }else{ ?>
						<a href="login.php" class=""><span class="glyphicon glyphicon-heart icon" aria-hidden="true"></span></a>
                                                <a href="login.php" class=""><span class="glyphicon glyphicon-comment icon" aria-hidden="true"></span></a>
						<?php } ?><br />
						<p style="background-color:#FF0000; color:#FFFFFF">
						
						</p>
						</div>
						</div>
						<div class="mid-1">
						<div class="women">
						<div class="women-top">
						<?php $maincategory=mysql_query("select *from category where cid='".$rows['category_id']."'"); $cat=mysql_fetch_array($maincategory);?>
							<span><?php echo $cat['category_name']; ?></span>
							<?php  $title = (strlen($rows['title'])>12) ? substr($rows['title'],0,9).'...' : $rows['title']; ?>
							<h6><a href="product-details.php?details=<?php echo $rows['pid']; ?>"><?php echo $title; ?></a></h6>
							</div>
							<div class="img item_add">
							<?php if(isset($_SESSION['ID'])) { ?>
								<!--<a style="cursor:pointer; " onClick="return addproductincart(<?php echo $rows['pid']; ?>)" title="Add Cart"><img src="images/ca.png" alt=""></a>-->
								<?php if($rows['product_quantity']<=10){ ?>
								<span>Left <?php echo $rows['product_quantity']; ?></span>
								<?php } ?>
								<?php }else{ ?>
								
								<!--<a href="login.php" title="Add Cart"><img src="images/ca.png" alt="" ></a>-->
								<?php if($rows['product_quantity']<=10){ ?>
								<!--<span>Left <?php echo $rows['product_quantity']; ?></span>-->
								<?php } ?>
								<?php } ?>
							</div>
							<div class="clearfix"></div>
							</div>
							<div class="mid-2">
								<p ><label>Rs.<?php echo $rows['price']; ?> INR</label><em class="item_price">Rs <?php echo $rows['discount_price']; ?> INR</em>
								<br />
								<input type="checkbox" name="cbox"  onClick="mycheckedboxmy(<?php echo $rows['pid']; ?>)"  id="checkid<?php echo $rows['pid']; ?>" title="Compare Item" /> Compare Item
								
								</p>
								  <!-- <div class="block">
									<div class="starbox small ghosting"> </div>
								</div> -->
								
								<div class="clearfix"></div>
							</div>
							<?php if(isset($_SESSION['ID'])) { ?>
                                                       <a href="#" class="btn material-btn material-btn_primary main-container__column btn-block" style="cursor:pointer;" data-toggle="modal" data-target="#product_enquiry" onClick="return product_enquiry_details_get(<?php echo $rows['pid']; ?>)">Product Enquiry <i class="fa fa-comment"></i></a>
                                                        <?php }else{ ?>
                                                      <a href="login.php" class="btn material-btn material-btn_primary main-container__column btn-block">Product Enquiry <i class="fa fa-comment"></i></a>
                                                        <?php } ?>
						</div>
					</div>
					</div>
					<?php } } ?>
				
				</div>
			</div>
			<!--//products-->
			<!--brand-->
	
			<!--<div class="brand row">
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
			</div>-->
			<!--//brand-->
			</div>
			
		</div>
	<!--//content-->
	<!--//footer-->
	<style>
	
	</style>
	
	<?php include("footer.php"); ?>
	
	<script>
		
	</script>
