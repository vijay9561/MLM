<?php include('header.php'); 
	include_once "function.php";
if(isset($_GET["page"]))
	$page = (int)$_GET["page"];
	else
	$page = 1;

	$setLimit = 18;
	$pageLimit = ($page * $setLimit) - $setLimit;
$query='';
if(isset($_GET['maincategory'])) {
$query="select *from product_details where category_id='".$_GET['maincategory']."' ORDER BY rand(), pid ASC LIMIT ".$pageLimit." , ".$setLimit;
}elseif(isset($_GET['subcategory'])){
$query="select *from product_details where sub_category_id='".$_GET['subcategory']."' ORDER BY rand(), pid ASC LIMIT ".$pageLimit." , ".$setLimit;
}elseif(isset($_GET['subsubcategory'])){
$query="select *from product_details where sub_sub_category_id='".$_GET['subsubcategory']."' ORDER BY rand(), pid ASC LIMIT ".$pageLimit." , ".$setLimit;
}else{
$query="select *from product_details ORDER BY rand(), pid ASC LIMIT ".$pageLimit." , ".$setLimit;
}
$mysqluery=mysql_query($query)
?>
<style type="text/css">
	
	</style>    
<!--banner-->
<div class="banner-top">
	<div class="container" style="text-align: center;margin-top: 102px;">
		<h1>Products</h1>
		<em></em>
		<h2><a href="index.php">Home</a><label>/</label>Products</a></h2>
	</div>
</div>
	<!--content-->
		<div class="product">
			<div class="container">
			<div class="col-md-12" style="    border-right: 3px solid #EFEFEF;">
			<?php if(mysql_num_rows($mysqluery)>=1){ ?>
			<div class="mid-popular">
			<?php while($rows=mysql_fetch_array($mysqluery)) {   
                            $productimages=mysql_query("select *from product_images where pid=".$rows['pid']." order by piid asc");
				 $images=mysql_fetch_array($productimages);
				?>
					<div class="col-md-3 item-grid simpleCart_shelfItem" style="margin-bottom:30px;">
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
                                                <a href="login.php" class=""><span class="glyphicon glyphicon-comment icon" aria-hidden="true"></span></a>
						<?php } ?>
						</div>
						</div>
						<div class="mid-1">
						<div class="women">
						<div class="women-top">
						<?php $maincategory=mysql_query("select *from category where cid='".$rows['category_id']."'"); $cat=mysql_fetch_array($maincategory);?>
							<span><?php   $cat12 = (strlen($cat['category_name'])>15) ? substr($cat['category_name'],0,12).'...' : $cat['category_name']; echo $cat12; ?></span>
							<?php  $title = (strlen($rows['title'])>15) ? substr($rows['title'],0,12).'...' : $rows['title']; ?>
							<h6><a href="product-details.php?details=<?php echo $rows['pid']; ?>"><?php  echo $title; ?></a></h6>
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
				<?php if(isset($_GET['category'])) { ?>
				<?php echo maincategory($setLimit,$page,$_GET['maincategory']); ?>
				<?php  }elseif(isset($_GET['subcategory'])) { ?>
				<?php echo subcategory($setLimit,$page,$_GET['subcategory']); ?>
				<?php }elseif(isset($_GET['subsubcategory'])) { ?>
				<?php echo subsubcategory($setLimit,$page,$_GET['subsubcategory']); ?>
				<?php }else{ ?>
				<?php echo allproductloaded($setLimit,$page); ?>
				<?php } ?>
				</div>
				</div>
				<?php }else{ ?>
				<div class="alert alert-danger">No Records Founds</div>
				<?php } ?>
			</div>
		
			<!--<div class="col-md-3 product-bottom">
			
				<div class="panel panel-default material-panel material-panel_info">
						<h4 class="panel-heading material-panel__heading">Categories</h4>
						<?php $category=mysql_query("select category_name,status,date,cid from category order by cid desc"); ?>
							 <ul class="menu-drop panel-body material-panel__body">
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
                                                
                       <?php $result =mysql_query("select product_path,pid,piid from product_images order by rand() limit 20"); ?>
                   <?php $count=mysql_num_rows($result); if($count>=1){ ?>                             
               
                                                
                   <?php } ?>
					</div>
					

		</div>-->
			</div class="clearfix"></div>
				<!--products-->
			
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