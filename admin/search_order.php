<?php include('header.php'); 
 mysql_query("delete from image where amid='1'");
		if(isset($_SESSION['JOBPORTALADMIN'])) { 
include_once "function.php";
if(isset($_GET["page"]))
	$page = (int)$_GET["page"];
	else
	$page = 1;

	$setLimit = 100;
	$pageLimit = ($page * $setLimit) - $setLimit;
$query='';
if(isset($_GET['searchkeyowords'])){
$searchid=$_GET['searchkeyowords'];
$query="select *from orders_details where status='confirm' and order_id LIKE '%".$searchid."%' order by oid desc LIMIT ".$pageLimit." , ".$setLimit;
$mysqluery=mysql_query($query);
}else{
$query="select *from orders_details where status='confirm' order by oid desc LIMIT ".$pageLimit." , ".$setLimit;
$mysqluery=mysql_query($query);
}
?>
<style>
</style>
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
		<div id="loading" style="display:none;">
        <img id="loading-image" src="images/show_loader.gif" alt="Loading..." />
              </div>

			<ol class="breadcrumb">
				<li><a href="index.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
				<li class="active">Search Orders Details</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Search Orders Details</h1>
				<?php if(isset($_SESSION['SUCESSMSG'])){ ?>
				<div class="alert bg-success" role="alert">
			<svg class="glyph stroked checkmark"><use xlink:href="#stroked-checkmark"></use></svg><?php echo $_SESSION['SUCESSMSG']; ?><a href="#" class="pull-right"><span class="glyphicon glyphicon-remove"></span></a>
				</div>
				<?php unset($_SESSION['SUCESSMSG']); } ?>
			</div>
		</div><!--/.row-->
		
		
	<?php if(isset($_GET['vieworders'])) { $mysql=mysql_query("select *from orders_details where order_id='".$_GET['vieworders']."'");
        if(mysql_num_rows($mysql)>=1){
        $orders=mysql_fetch_array($mysql); ?>
	<div class="row">
		<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">Order ID &nbsp;<a class="btn btn-primary" href="#"><?php echo $orders['order_id']; ?> </a>&nbsp;&nbsp;
			<?php if(isset($_GET['searchkeyowords'])){ ?>
			<a href="confirm-orders.php?searchkeyowords=<?php echo $_GET['searchkeyowords']; ?>" class="btn btn-danger">Back</a>
			<?php }else{ ?>
				<a href="index.php" class="btn btn-danger">Back</a>
			<?php } ?>
                                <span class="dropdown">                           
  <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Dropdown Example
  <span class="caret"></span></button>
  <ul class="dropdown-menu">
    <li><a href="#">HTML</a></li>
    <li><a href="#">CSS</a></li>
    <li><a href="#">JavaScript</a></li>
  </ul>
                                </span>
			</div>
			<div class="panel-body">
			<div class="table-responsive">
			 <table id="datatable" class="table table-striped table-bordered">
			 <thead><tr>
			<th colspan="2" style="padding-left:20px;">Product Details</th>
			<th style="padding-left:20px;">Quntity</th>			
			<th style="padding-left:20px;">Prices</th>
			<th style="padding-left:20px;">Subtotal</th>
		  </tr>
		   </thead> 
		  <tbody>
		  <?php $orders_itmes=mysql_query("select pid,oid,uid,quantity from order_items_details where oid='".$orders['oid']."'");
		  $alltotal=''; $tot='';$qty='';$price=''; while($items=mysql_fetch_array($orders_itmes)){  
		   $product=mysql_query("select discount_price,title,description from product_details where pid='".$items['pid']."'"); $p=mysql_fetch_array($product);
		   $productimages=mysql_query("select *from product_images where pid=".$items['pid']." order by piid asc");
			 $images=mysql_fetch_array($productimages);
		   ?> 
		   <tr>
		   <td><img src="images/product/<?php echo $images['product_path']; ?>" class="img-responsive" style="width:150px;height:100px;" alt=""></td>
		   <td><?php echo $p['title']; ?><br> Order Date <?php echo $orders['date']; ?></td>
		   <td><?php echo $items['quantity']; ?></td>
		   <td>Rs.<?php echo $p['discount_price']; ?></td>
		   <td><?php $qty=$qty+$items['quantity']; echo $items['quantity']*$p['discount_price'];  $price=$price+$p['discount_price'];
		    $tot=$items['quantity']*$p['discount_price'];
		    $alltotal=$tot+$alltotal; ?></td>
		   </tr>
		   <?php } ?>
		   <tr><td></td><td></td><td>Total Qty:&nbsp;<?php echo $qty; ?></td><td>Total Price  Rs. <?php echo  $price; ?></td><td>Total Subprice <?php echo $alltotal; ?></td></tr>
		  </tbody>
		 
			 </table>
			 </div>
			 <div class="row">
			 <div class="col-md-12">
			 <h3>Delivery Address Details <?php $ordersdetails=mysql_query("select name,mobile_no,address,city,state,pincode,uid from address_details where uid='".$orders['uid']."'"); $add=mysql_fetch_array($ordersdetails); ?></h3>
			 <p><strong>To, <?php echo $add['name']; ?></strong> </p>
			 <p><strong>Address:&nbsp; <?php echo $add['address']; ?>&nbsp;&nbsp;,&nbsp;<?php echo $add['city']; ?>&nbsp;&nbsp;,&nbsp;<?php echo $add['state']; ?>&nbsp;-&nbsp;<?php echo $add['pincode']; ?></strong></p>	
			 <p><strong>Mobile Number:&nbsp; <?php echo $add['mobile_no']; ?></strong></p>		 
			 </div>
			 </div>
			</div>
			<div class="panel-footer">
			
			</div></div>
		</div></div>
        <?php }else{ ?> 
                <div class="alert alert-danger">No Records Found</div>
       <?php  } }else{ ?>
		
		<?php } ?>
		<br><br>
	</div>
	<?php include('footer.php'); ?>
<?php }else{  
   header('Location:login.php');
?>
<?php } ?>


