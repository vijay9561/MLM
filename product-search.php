<?php include('header.php'); 
	include_once "function.php";
if(isset($_GET["page"]))
	$page = (int)$_GET["page"];
	else
	$page = 1;

	$setLimit = 18;
	$pageLimit = ($page * $setLimit) - $setLimit;
$query='';
$searchTerm=$_GET['search'];
$query="select *from product_details where  title LIKE  '%".$searchTerm."%' order by pid desc LIMIT ".$pageLimit." , ".$setLimit;
$mysqluery=mysql_query($query)
?>
<style type="text/css">
	
	</style>    
<!--banner-->
<div class="banner-top">
	<div class="container">
		<h1>Search Result</h1>
		<em></em>
		<h2><a href="index.php">Home</a><label>/</label>Search Result</a></h2>
	</div>
</div>
	<!--content-->
		<div class="product">
			<div class="container">
			<div class="col-md-9">
			<?php if(mysql_num_rows($mysqluery)>=1){ ?>
			<div class="mid-popular">
			<?php while($rows=mysql_fetch_array($mysqluery)) {   $productimages=mysql_query("select *from product_images where pid=".$rows['pid']." order by piid asc");
				 $images=mysql_fetch_array($productimages);
				?>
					<div class="col-md-4 item-grid simpleCart_shelfItem" style="margin-bottom:30px;">
					<div class=" mid-pop">
					<div class="pro-img">
						<img src="admin/images/product/<?php echo $images['product_path']; ?>" class="img-responsive product-home-images" alt="">
						<div class="zoom-icon ">
					<!--<a   class="picture" href="admin/images/product/<?php echo $images['product_path']; ?>" rel="title" class="b-link-stripe b-animate-go  thickbox"><i class="glyphicon glyphicon-search icon "></i></a>-->
						<a href="product-details.php?details=<?php echo $rows['pid']; ?>"><i class="glyphicon glyphicon-eye-open icon"></i></a>
						<?php if(isset($_SESSION['ID'])) { ?>
                                                <a  style="cursor:pointer;"  onClick="return wislistaddedproducts(<?php echo $rows['pid'] ?>)" class=""><span class="glyphicon glyphicon-heart icon" aria-hidden="true"></span></a>
                                                <a href="#" style="cursor:pointer;" data-toggle="modal" data-target="#product_enquiry" onClick="return product_enquiry_details_get(<?php echo $rows['pid']; ?>)"><span class="glyphicon glyphicon-comment icon" aria-hidden="true"></span></a>
						<?PHP }else{ ?>
						<a href="login.php" class=""><span class="glyphicon glyphicon-heart icon" aria-hidden="true"></span></a>
                                                <a href="login.php" class=""><span class="glyphicon glyphicon-envelope icon" aria-hidden="true"></span></a>
						<?php } ?>
						</div>
						</div>
						<div class="mid-1">
						<div class="women">
						<div class="women-top">
						<?php $maincategory=mysql_query("select *from category where cid='".$rows['category_id']."'"); $cat=mysql_fetch_array($maincategory);?>
							<span><?php echo $cat['category_name']; ?></span>
							<?php  $title = (strlen($rows['title'])>15) ? substr($rows['title'],0,12).'...' : $rows['title']; ?>
							<h6><a href="product-details.php?details=<?php echo $rows['pid']; ?>"><?php echo $title; ?></a></h6>
							</div>
							<?php if(isset($_SESSION['ID'])) { ?>
							<div class="img item_add">
								<!--<a style="cursor:pointer;"  onClick="return addproductincart(<?php echo $rows['pid']; ?>)"><img src="images/ca.png" alt=""></a>-->
								<?php if($rows['product_quantity']<=10){ ?>
								<span>Left <?php echo $rows['product_quantity']; ?></span>
								<?php } ?>
							</div>
							<?php }else{ ?>
							<div class="img item_add">
								<!--<a  href="login.php"><img src="images/ca.png" alt=""></a>-->
								<?php if($rows['product_quantity']<=10){ ?>
								<span>Left <?php echo $rows['product_quantity']; ?></span>
								<?php } ?>
							</div>
							<?php } ?>
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
							
						</div>
                                            <?php if(isset($_SESSION['ID'])) { ?>
                                                       <a href="#" class="btn material-btn material-btn_primary main-container__column btn-block" style="cursor:pointer;" data-toggle="modal" data-target="#product_enquiry" onClick="return product_enquiry_details_get(<?php echo $rows['pid']; ?>)">Product Enquiry <i class="fa fa-comment"></i></a>
                                                        <?php }else{ ?>
                                                      <a href="login.php" class="btn material-btn material-btn_primary main-container__column btn-block">Product Enquiry <i class="fa fa-comment"></i></a>
                                                        <?php } ?>
					</div>
                                            
					</div>
                            
					<?php } ?>
				</div>
				<div class="row">
				<div class="col-md-12">
				<?php echo searchpaginations($setLimit,$page,$_GET['search']); ?>
				</div>
				</div>
				<?php }else{ ?>
				<div class="alert alert-danger">No Records Founds</div>
				<?php } ?>
			</div>
		
			<div class="col-md-3 product-bottom">
			<!--categories-->
				<div class=" rsidebar span_1_of_left">
						<h4 class="cate">Categories</h4>
						<?php $category=mysql_query("select category_name,status,date,cid from category order by cid desc"); ?>
							 <ul class="menu-drop">
							 <?php if(mysql_num_rows($category)>=1){ while($cat=mysql_fetch_array($category)){ 
							 $subcategory=mysql_query("select category_name,scid,mid from sub_category where mid='".$cat['cid']."'"); ?>
							<li class="item1"><a href="#"><?php echo $cat['category_name']; ?> </a>
								<ul class="cute">
								<?php if(mysql_num_rows($subcategory)>=1) { while($sub=mysql_fetch_array($subcategory)) { ?>
									<li class="subitem1"><a href="product.php?subcategory=<?php echo $sub['scid']; ?>"><?php echo $sub['category_name']; ?></a></li>
									<?php } }else{ ?>
									<li class="subitem1"><a href="#"><div class="alert alert-danger">No Found Subcategory</div></a></li>
									<?php } ?>
								</ul>
							</li>
							<?php } } ?>
						</ul>
					</div>
				<!--initiate accordion-->
						<script type="text/javascript">
							$(function() {
							    var menu_ul = $('.menu-drop > li > ul'),
							           menu_a  = $('.menu-drop > li > a');
							    menu_ul.hide();
							    menu_a.click(function(e) {
							        e.preventDefault();
							        if(!$(this).hasClass('active')) {
							            menu_a.removeClass('active');
							            menu_ul.filter(':visible').slideUp('normal');
							            $(this).addClass('active').next().stop(true,true).slideDown('normal');
							        } else {
							            $(this).removeClass('active');
							            $(this).next().stop(true,true).slideUp('normal');
							        }
							    });
							
							});
						</script>
<!--//menu-->
<!--  <section  class="sky-form">
					<h4 class="cate">Discounts</h4>
					 <div class="row row1 scroll-pane">
						 <div class="col col-4">
						   <label class="checkbox"><input type="checkbox" name="checkbox" checked=""><i></i>Upto - 10% (20)</label>
						 </div>
						 <div class="col col-4">
						   <label class="checkbox"><input type="checkbox" name="checkbox"><i></i>40% - 50% (5)</label>
						   <label class="checkbox"><input type="checkbox" name="checkbox"><i></i>30% - 20% (7)</label>
						   <label class="checkbox"><input type="checkbox" name="checkbox"><i></i>10% - 5% (2)</label>
						   <label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Other(50)</label>
						 </div>
					 </div>
				 </section> 				 				 
				  -->
					
					 <!---->
					<!--  <section  class="sky-form">
						<h4 class="cate">Type</h4>
							<div class="row row1 scroll-pane">
								<div class="col col-4">
								  <label class="checkbox"><input type="checkbox" name="checkbox" checked=""><i></i>Sofa Cum Beds (30)</label>
								</div>
								<div class="col col-4">
								  <label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Bags  (30)</label>
								  <label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Caps & Hats (30)</label>
								  <label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Jackets & Coats   (30)</label>
								  <label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Jeans  (30)</label>
								  <label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Shirts   (30)</label>
								  <label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Sunglasses  (30)</label>
								  <label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Swimwear  (30)</label>
								</div>
							</div>
				   </section> -->
				   		<!-- <section  class="sky-form">
						<h4 class="cate">Brand</h4>
							<div class="row row1 scroll-pane">
								<div class="col col-4">
								  <label class="checkbox"><input type="checkbox" name="checkbox" checked=""><i></i>Roadstar</label>
								</div>
								<div class="col col-4">
								  <label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Levis</label>
								  <label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Persol</label>
								  <label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Nike</label>
								  <label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Edwin</label>
								  <label class="checkbox"><input type="checkbox" name="checkbox" ><i></i>New Balance</label>
								  <label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Paul Smith</label>
								  <label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Ray-Ban</label>
								</div>
							</div>
				   </section>		 -->
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