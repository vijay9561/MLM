
<?php
//error_reporting(0);
 include('header.php');
 if(!$_SESSION['ID']){ 
?>
<script>
window.location="index.php";
</script>
<?PHP 
//echo 'Hi';
//exit;
}else{
	include_once "function.php";
if(isset($_GET["page"]))
	$page = (int)$_GET["page"];
	else
	$page = 1;

	$setLimit = 18;
	$pageLimit = ($page * $setLimit) - $setLimit;
$query='';
$query="select *from wishlist where uid='".$_SESSION['ID']."' order by wid desc LIMIT ".$pageLimit." , ".$setLimit;
$mysqluery=mysql_query($query)
?>

<!--banner-->
<div class="banner-top">
	<div class="container" style="text-align: center;margin-top: 102px;">
		<h1>Wishlist</h1>
		<em></em>
		<h2><a href="index.php">Home</a><label>/</label>Wishlist</a></h2>
	</div>
</div>
	<!--content-->
		<div class="product">
			<div class="container">
			<div class="col-md-9" id="getwishlistproducts" style="    border-right: 3px solid #EFEFEF;">
			
						<?php if(isset($_SESSION['Success'])) { ?><div class="alert alert-success"><?php echo $_SESSION['Success']; ?></div><?php unset($_SESSION['Success']); } ?>
			<?php if(mysql_num_rows($mysqluery)>=1){ ?>
				<input type="hidden" name="pagedid"  id="pagedid" value="<?php echo $page; ?>" />
			<div class="mid-popular">
			<?php while($wislist=mysql_fetch_array($mysqluery)) {   
			     $product=mysql_query("select *from product_details where pid='".$wislist['pid']."'");
				 $rows=mysql_fetch_array($product);
			    $productimages=mysql_query("select *from product_images where pid=".$rows['pid']." order by piid asc");
				 $images=mysql_fetch_array($productimages);
				?>
					<div class="col-md-4 item-grid simpleCart_shelfItem" style="margin-bottom:30px;">
				
					<div class=" mid-pop">
					<div class="pro-img">
						<img src="admin/images/product/<?php echo $images['product_path']; ?>" class="img-responsive product-home-images" alt="">
						<div class="zoom-icon ">
					 <!-- <a class="picture" href="admin/images/product/<?php echo $images['product_path']; ?>" rel="title" class="b-link-stripe b-animate-go  thickbox"><i class="glyphicon glyphicon-search icon "></i></a>-->
						<a href="product-details.php?details=<?php echo $rows['pid']; ?>"><i class="glyphicon glyphicon-eye-open icon"></i></a>
		<a   style="cursor:pointer;" onclick="return deletecartitems(<?php echo $wislist['wid']; ?>)"><span class="glyphicon glyphicon-remove icon" aria-hidden="true"></span></a>
                <a href="#" style="cursor:pointer;" data-toggle="modal" data-target="#product_enquiry" onClick="return product_enquiry_details_get(<?php echo $rows['pid']; ?>)"><span class="glyphicon glyphicon-comment icon" aria-hidden="true"></span></a>
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
								<a style="cursor:pointer;"  onClick="return addproductincart(<?php echo $rows['pid']; ?>)"><!--<img src="images/ca.png" alt=""></a>-->
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
								<p ><label>Rs.<?php echo $rows['price']; ?> INR</label><em class="item_price">Rs <?php echo $rows['discount_price']; ?> INR</em></p>
								  <!-- <div class="block">
									<div class="starbox small ghosting"> </div>
								</div> -->
								
								<div class="clearfix"></div>
							</div>
							
						</div>
					</div>
					</div>
					<?php } ?>
				</div>
				<div class="row">
				<div class="col-md-12">
				<?php echo wislistitems($setLimit,$page,$_SESSION['ID']); ?>
				</div>
				</div>
				<?php }else{ ?>
				<div class="alert alert-danger">No Wishlist Records Founds</div>
				<?php } ?>
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
			</div> -->
			<!--//brand-->
			</div>
			
		</div>
	<!--//content-->
		<!--//footer-->
		<script>
		
		function deletecartitems(id) {
		page=document.getElementById('pagedid').value.trim();
		var r=confirm('Are you sure you want to delete this item?');
		if(r==true)
		{   
		$("#loading").show(); 
		$.ajax({
		url: "post.php?action=wilistitemdelete",
		type: 'POST',
		data: {id: id},
		success: function(data) {
		if(data==1){
			$.ajax({
			url: "ajax-cart.php?action=view_wishlist_products",
			type: 'POST',
			data: {page:page},
			success: function(data) {
		 $("#getwishlistproducts").fadeOut().html(data).fadeIn('slow');
			}
			});
		$("#loading").hide(); 
		}else{
		alert("wishlist item not deleted"); 
		}
		}
		});
		return false;
		} else
		{
		return false;	
		}
		}
		
		</script>
	<?php include('footer.php'); ?>
	<?PHP } ?>