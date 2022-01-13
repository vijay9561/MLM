
<?php
 include('header.php');
 if(!$_SESSION['ID']){ 
?>
<script>
window.location="index.php";
</script>
<?PHP 
}else{
include_once "function.php";
if(isset($_GET["page"]))
$page = (int)$_GET["page"];
else
$page = 1;
$setLimit = 18;
$pageLimit = ($page * $setLimit) - $setLimit;
$query='';
$query="select *from product_review where uid='".$_SESSION['ID']."' order by prid desc LIMIT ".$pageLimit." , ".$setLimit;
$mysqluery=mysql_query($query);
?>
<style>
.wishlistproducts{height:80px; width:100px;}
</style>

<!--banner-->
<div class="banner-top">
	<div class="container" style="text-align: center;margin-top: 102px;">
		<h1>My Review</h1>
		<em></em>
		<h2><a href="index.php">Home</a><label>/</label>My Review</a></h2>
	</div>
</div>
	<!--content-->
		<div class="product">
			<div class="container">
			<div class="col-md-9">
			<?php if(isset($_SESSION['Success'])) { ?><div class="alert alert-success"><?php echo $_SESSION['Success']; ?></div><?php unset($_SESSION['Success']); } ?>
			<?php if(mysql_num_rows($mysqluery)>=1){ ?>
			<div class="mid-popular">
				<div class="material-card">
			<div class="panel-heading">MY Review(<?php $countrows=mysql_num_rows($mysqluery); echo  $countrows; ?>) </a></div>
			<div class="panel-body">
			<?php while($wislist=mysql_fetch_array($mysqluery)) {   
			     $product=mysql_query("select *from product_details where pid='".$wislist['pid']."'");
				 $rows=mysql_fetch_array($product);
			    $productimages=mysql_query("select *from product_images where pid=".$wislist['pid']." order by piid asc");
                            //  echo "select *from product_images where pid=".$rows['pid']." order by piid asc";
				 $images=mysql_fetch_array($productimages);
				?>
					<div class="row">
					<div class="col-md-2 col-xs-4"><img src="admin/images/product/<?php echo $images['product_path']; ?>" class="img-responsive product-home-images img-thumbnail wishlistproducts" style="" alt=""></div>
					<div class="col-md-10 col-xs-7"><h3 style="margin-bottom:2px; font-size:17px;color: #760075;"><?php echo $rows['title']; ?></h3>
					<p style="text-align:justify; color:#666666;font-size: 14px;"><?php echo $wislist['description']; ?></p>
					<p style="color:#666666;font-size: 14px;"><i class="glyphicon glyphicon-calendar"></i>&nbsp;&nbsp;Reviews On <?php echo $recived_date=date('D-F-j-Y', strtotime($wislist['date'])); ?> </p>
					</div>
					
					</div>
					<hr style="margin-top: 9px;margin-bottom: 9px;">
					<?php } ?>
					</div></div>
				</div>
				<div class="row">
				<div class="col-md-12">
				<?php echo myreviews($setLimit,$page,$_SESSION['ID']); ?>
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
			</div>-->
			<!--//brand-->
			</div>
			
		</div>
	<!--//content-->
		<!--//footer-->
	<?php include('footer.php'); ?>
	<?PHP } ?>