<?php
include('config.php');
if($_GET['action']=='delete_cart_items'){
$cid=$_POST['id'];
$query=mysql_query("delete from cart where cid='$cid' and uid='".$_SESSION['ID']."'");

$cart=mysql_query("select *from cart where uid='".$_SESSION['ID']."'"); 
$sumallquanity = mysql_query("SELECT SUM(quantity) AS qty FROM cart where uid='".$_SESSION['ID']."'");
$sumqty=mysql_fetch_array($sumallquanity);
$countcart=mysql_num_rows($cart);
  $_SESSION['Success']='Cart Item Deleted Successfully';
 ?>
 <style>

.v-center {
  height: 100vh;
  width: 100%;
  display: table;
  position: relative;
  text-align: center;
}

.v-center > div {
  display: table-cell;
  vertical-align: middle;
  position: relative;
  top: -10%;
}



.modal-box {
  display: none;
  position:fixed;
  z-index: 99999;
  width:50%;
  background: white;
  border-bottom: 1px solid #aaa;
  border-radius: 4px;
  box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
  border: 1px solid rgba(0, 0, 0, 0.1);
  background-clip: padding-box;
}
@media(max-width:500px){
.modal-box {
  display: none;
  position:fixed;
  z-index: 99999;
  width:90%;
  background: white;
  border-bottom: 1px solid #aaa;
  border-radius: 4px;
  box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
  border: 1px solid rgba(0, 0, 0, 0.1);
  background-clip: padding-box;
}
}
@media (min-width: 32em) {

.modal-box { width:50%; }
}

.modal-box header,
.modal-box .modal-header {
  padding: 1.25em 1.5em;
  border-bottom: 1px solid #ddd;
}

.modal-box header h3,
.modal-box header h4,
.modal-box .modal-header h3,
.modal-box .modal-header h4 { margin: 0; }

.modal-box .modal-body { padding: 2em 1.5em; }

.modal-box footer,
.modal-box .modal-footer {
  padding: 1em;
  border-top: 1px solid #ddd;
  background: rgba(0, 0, 0, 0.02);
  text-align: right;
}

.modal-overlay {
  opacity: 0;
  filter: alpha(opacity=0);
  position: absolute;
  top: 0;
  left: 0;
  z-index: 900;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.3) !important;
}

a.close {
  line-height: 1;
  font-size: 1.5em;
  position: absolute;
  top: 2%;
  right: 2%;
  text-decoration: none;
  color:#CCCCCC;
}

a.close:hover {
  color:#FFFFFF;
  -webkit-transition: color 1s ease;
  -moz-transition: color 1s ease;
  transition: color 1s ease;
}
.images{ width:160px; height:70px;}
</style>
<script>
$(document).ready(function(){ setTimeout(function(){ $(".alert-success").fadeOut('slow'); }, 5000); }); 
$(function(){

var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");

	$('a[data-modal-id]').click(function(e) {
		e.preventDefault();
    $("body").append(appendthis);
    $(".modal-overlay").fadeTo(500, 0.7);
    //$(".js-modalbox").fadeIn(500);
		var modalBox = $(this).attr('data-modal-id');
		$('#'+modalBox).fadeIn($(this).data());
	});  
  
  
$(".js-modal-close, .modal-overlay").click(function() {
    $(".modal-box, .modal-overlay").fadeOut(500, function() {
        $(".modal-overlay").remove();
    });
 
});
 
$(window).resize(function() {
    $(".modal-box").css({
        top: ($(window).height() - $(".modal-box").outerHeight()) / 4,
        left: ($(window).width() - $(".modal-box").outerWidth()) / 2
    });
});
 
$(window).resize();
 
});
</script>
<div class="container" id="getcartdetails">
	<div class="check-out">
	<div class="bs-example4" data-example-id="simple-responsive-table">
	<?php if(isset($_SESSION['Success'])){ ?><div class="alert alert-success"><?php echo $_SESSION['Success']; ?></div><?php unset($_SESSION['Success']); } ?>
	<div class="panel panel-default">
			<div class="panel-heading">MY CART(<?php echo $countcart; ?>) </a></div>
			<div class="panel-body">
    <div class="table-responsive">
    	    <table class="table table-bordered">
			<thead>
		  <tr>
			<th>Item</th>
			<th>Quntity</th>			
			<th>Prices</th>
			<th>Subtotal</th>
			<th>Action</th>
		  </tr>
		  </thead>
		  <?php 
		  if(mysql_num_rows($cart)>=1){ $pricetotal=''; $subpricetotal='';  while($c=mysql_fetch_array($cart)) {
		     $product=mysql_query("select *from  product_details where pid='".$c['pid']."'");
			 $p=mysql_fetch_array($product);
			 $productimages=mysql_query("select *from product_images where pid=".$c['pid']." order by piid asc");
			 $images=mysql_fetch_array($productimages);
		   ?>
		  <tr>
            <input type="hidden" id="productid<?php echo $c['cid']; ?>" value="<?php echo $p['pid']; ?>">
			<td><a href="product-details.php?details=<?php echo $c['pid']; ?>" class="at-in"><img src="admin/images/product/<?php echo $images['product_path']; ?>" class="img-responsive images " alt=""></a>
			<div class="sed">
				<h5><a href="product-details.php?details=<?php echo $c['pid']; ?>"><?php  echo substr($p['title'],0,50); ?></a></h5>
				<p><?php  echo substr($p['description'],0,50).'..'; ?> </p>
			
			</div>
			<div class="clearfix"> </div>
			</td>
			<td><div class="quantity"> 
								<div class="quantity-select">                           
									<div class="entry value-minus" onClick="minusvalues(<?php echo $c['cid']; ?>)">&nbsp;</div>
									<div class="entry value"><span id="carta_count<?php echo $c['cid']; ?>"><?php echo $c['quantity']; ?></span></div>
									<div class="entry value-plus active" onClick="plusvalues(<?php echo $c['cid']; ?>)">&nbsp;</div>
								</div>
							</div></td>
			<td>Rs. <?php echo $p['discount_price']; $pricetotal=$pricetotal+$p['discount_price'];?></td>
			<td class="item_price">Rs.<?php $subtotal= $c['quantity']*$p['discount_price']; $subpricetotal= $subtotal+$subpricetotal; echo $subtotal ?></td>
			<td class="add-check"><?php /*?><a class="item_add hvr-skew-backward" href="#" onClick="return update_cart_yours(<?php echo $c['cid']; ?>)">Cart Update</a> <?php */?>&nbsp;&nbsp;<a class="item_add hvr-skew-backward" href="#" onClick="return deletecartitems(<?php echo $c['cid']; ?>)"><i class="glyphicon glyphicon-remove"></i></a> </td>
		  </tr>
		  <?php } ?>
		  <tr><td><strong>Total: <?php $totalcount=mysql_num_rows($cart); echo  $totalcount; ?></strong></td>
		  <td><strong>Total: <?php echo $sumqty['qty']; ?></strong></td>
		  <td><strong>Total: <?php echo $pricetotal; ?></strong></td>
		    <td><strong>Total: <?php echo $subpricetotal; ?></strong></td>
		  </tr>
		  <?php }else{ ?>
		  <tr><td colspan="6"><div class="alert alert-danger">No Item Added on your cart</div></td></tr>
		  <?php } ?>
		  
		  
	</table>
	</div>
	<?php   if(mysql_num_rows($cart)>=1){ ?>
	<div class="produced">
	<a  style="cursor:pointer;"  href="#" data-modal-id="popup1" class="hvr-skew-backward js-open-modal">Produced To Buy</a>
	 </div>
	 <?php } ?>
	</div>
	<div class="panel-footer">
	My Cart
	</div>
	</div>
	</div>
	
    </div>
</div>

<div id="popup1" class="modal-box">
  <header  style="background-color: #760075;color: white;"> <a href="#" class="js-modal-close close">&times;</a>
    <h3>Address Details</h3>
  </header>
  <div class="modal-body">
  <div class="alert alert-danger" id="productoutoffstock12" style="display:none;"></div>
  <?php $query=mysql_query("select *from address_details where uid='".$_SESSION['ID']."'");  $Q=mysql_fetch_array($query);?>
  <form method="post" enctype="multipart/form-data" id="addtocardforms">
  <div class="row">
  <div class="col-md-12">
  <div class="form-group">
  <label>Name <b style="color:red;">*</b></label>
  <input type="text" class="form-control" name="name" onChange="namer();" value="<?php echo $Q['name']; ?>" id="name">
  <span id="namer" style="color:red;"></span>
  </div>
  <div class="form-group">
  <label>Mobile No <b style="color:red;">*</b></label>
  <input type="text" class="form-control" name="mobile_no" id="mobile_no" value="<?php echo $Q['mobile_no']; ?>" onChange="mobile_nor()">
    <span id="mobile_nor" style="color:red;"></span>
  </div>
   <div class="form-group">
  <label>Pin Code <b style="color:red;">*</b></label>
  <input type="text" class="form-control" name="pincode" id="pincode"  value="<?php echo $Q['pincode']; ?>"  onChange="pincoder()">
     <span id="pincoder" style="color:red;"></span>
  </div>
   <div class="form-group">
  <label>State <b style="color:red;">*</b></label>
  <input type="text" class="form-control" name="state" onChange="stater()" value="<?php echo $Q['state']; ?>" id="state" required>
  <span id="stater" style="color:red;"></span>
  </div>
   <div class="form-group">
  <label>City <b style="color:red;">*</b></label>
  <input type="text" class="form-control" onChange="cityr" name="city" id="city" value="<?php echo $Q['city']; ?>" required>
  <span id="cityr" style="color:red;"></span>
  </div>
   <div class="form-group">
  <label>Address Details <b style="color:red;">*</b></label>
  <textarea type="text" class="form-control" name="address_details" id="address_details" value="" onChange="address_detailsr()" style="resize:none;" required><?php echo $Q['address']; ?></textarea>
    <span id="address_detailsr" style="color:red;"></span>
  </div>
   <div class="form-group">
   <input type="submit" class="hvr-skew-backward" value="Submit" onClick="return place_your_orders();">
   </div>
  </div>
  </div>
  </form>
  </div>
  <footer> <a href="#" class="btn btn-small js-modal-close">Close</a> </footer>
</div>
<?php } ?>
<style>

.v-center {
  height: 100vh;
  width: 100%;
  display: table;
  position: relative;
  text-align: center;
}

.v-center > div {
  display: table-cell;
  vertical-align: middle;
  position: relative;
  top: -10%;
}
.btn:hover {
  background-color: #ddd;
  -webkit-transition: background-color 1s ease;
  -moz-transition: background-color 1s ease;
  transition: background-color 1s ease;
}

.btn-small {
  padding: .75em 1em;
  font-size: 0.8em;
}

.modal-box {
  display: none;
  position:fixed;
  z-index: 99999;
  width:50%;
  background: white;
  border-bottom: 1px solid #aaa;
  border-radius: 4px;
  box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
  border: 1px solid rgba(0, 0, 0, 0.1);
  background-clip: padding-box;
}
@media(max-width:500px){
.modal-box {
  display: none;
  position:fixed;
  z-index: 99999;
  width:90%;
  background: white;
  border-bottom: 1px solid #aaa;
  border-radius: 4px;
  box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
  border: 1px solid rgba(0, 0, 0, 0.1);
  background-clip: padding-box;
}
}
@media (min-width: 32em) {

.modal-box { width:50%; }
}

.modal-box header,
.modal-box .modal-header {
  padding: 1.25em 1.5em;
  border-bottom: 1px solid #ddd;
}

.modal-box header h3,
.modal-box header h4,
.modal-box .modal-header h3,
.modal-box .modal-header h4 { margin: 0; }

.modal-box .modal-body { padding: 2em 1.5em; }

.modal-box footer,
.modal-box .modal-footer {
  padding: 1em;
  border-top: 1px solid #ddd;
  background: rgba(0, 0, 0, 0.02);
  text-align: right;
}

.modal-overlay {
  opacity: 0;
  filter: alpha(opacity=0);
  position: absolute;
  top: 0;
  left: 0;
  z-index: 900;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.3) !important;
}

a.close {
  line-height: 1;
  font-size: 1.5em;
  position: absolute;
  top: 2%;
  right: 2%;
  text-decoration: none;
  color:#CCCCCC;
}

a.close:hover {
  color:#FFFFFF;
  -webkit-transition: color 1s ease;
  -moz-transition: color 1s ease;
  transition: color 1s ease;
}
.images{ width:160px; height:70px;}
</style>
<script>
$(document).ready(function(){ setTimeout(function(){ $(".alert-success").fadeOut('slow'); }, 5000); }); 
$(function(){

var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");

	$('a[data-modal-id]').click(function(e) {
		e.preventDefault();
    $("body").append(appendthis);
    $(".modal-overlay").fadeTo(500, 0.7);
    //$(".js-modalbox").fadeIn(500);
		var modalBox = $(this).attr('data-modal-id');
		$('#'+modalBox).fadeIn($(this).data());
	});  
  
  
$(".js-modal-close, .modal-overlay").click(function() {
    $(".modal-box, .modal-overlay").fadeOut(500, function() {
        $(".modal-overlay").remove();
    });
 
});
 
$(window).resize(function() {
    $(".modal-box").css({
        top: ($(window).height() - $(".modal-box").outerHeight()) / 4,
        left: ($(window).width() - $(".modal-box").outerWidth()) / 2
    });
});
 
$(window).resize();
 
});
</script>
<?php 
if($_GET['action']=='get_temp_items_details'){
$cart=mysql_query("select *from cart where uid='".$_SESSION['ID']."'"); 
$sumallquanity = mysql_query("SELECT SUM(quantity) AS qty FROM cart where uid='".$_SESSION['ID']."'");
$sumqty=mysql_fetch_array($sumallquanity);
$countcart=mysql_num_rows($cart);
 // $_SESSION['Success']='Cart Item Deleted Successfully';
 ?>
<div class="container" id="getcartdetails">
	<div class="check-out">
	<div class="bs-example4" data-example-id="simple-responsive-table">
	<?php if(isset($_SESSION['Success'])){ ?><div class="alert alert-success"><?php echo $_SESSION['Success']; ?></div><?php unset($_SESSION['Success']); } ?>
	<div class="panel panel-default">
			<div class="panel-heading">MY CART(<?php echo $countcart; ?>) </a></div>
			<div class="panel-body">
    <div class="table-responsive">
    	    <table class="table table-bordered">
			<thead>
		  <tr>
			<th>Item</th>
			<th>Quntity</th>			
			<th>Prices</th>
			<th>Subtotal</th>
			<th>Action</th>
		  </tr>
		  </thead>
		  <?php 
		  if(mysql_num_rows($cart)>=1){ $pricetotal=''; $subpricetotal='';  while($c=mysql_fetch_array($cart)) {
		     $product=mysql_query("select *from  product_details where pid='".$c['pid']."'");
			 $p=mysql_fetch_array($product);
			 $productimages=mysql_query("select *from product_images where pid=".$c['pid']." order by piid asc");
			 $images=mysql_fetch_array($productimages);
		   ?>
		  <tr>
            <input type="hidden" id="productid<?php echo $c['cid']; ?>" value="<?php echo $p['pid']; ?>">
			<td><a href="product-details.php?details=<?php echo $c['pid']; ?>" class="at-in"><img src="admin/images/product/<?php echo $images['product_path']; ?>" class="img-responsive images " alt=""></a>
			<div class="sed">
				<h5><a href="product-details.php?details=<?php echo $c['pid']; ?>"><?php  echo substr($p['title'],0,50); ?></a></h5>
				<p><?php  echo substr($p['description'],0,50).'..'; ?> </p>
			
			</div>
			<div class="clearfix"> </div>
			</td>
			<td><div class="quantity"> 
								<div class="quantity-select">                           
									<div class="entry value-minus" onClick="minusvalues(<?php echo $c['cid']; ?>)">&nbsp;</div>
									<div class="entry value"><span id="carta_count<?php echo $c['cid']; ?>"><?php echo $c['quantity']; ?></span></div>
									<div class="entry value-plus active" onClick="plusvalues(<?php echo $c['cid']; ?>)">&nbsp;</div>
								</div>
							</div></td>
			<td>Rs. <?php echo $p['discount_price']; $pricetotal=$pricetotal+$p['discount_price'];?></td>
			<td class="item_price">Rs.<?php $subtotal= $c['quantity']*$p['discount_price']; $subpricetotal= $subtotal+$subpricetotal; echo $subtotal ?></td>
			<td class="add-check"><?php /*?><a class="item_add hvr-skew-backward" href="#" onClick="return update_cart_yours(<?php echo $c['cid']; ?>)">Cart Update</a><?php */?> &nbsp;&nbsp;<a class="item_add hvr-skew-backward" href="#" onClick="return deletecartitems(<?php echo $c['cid']; ?>)"><i class="glyphicon glyphicon-remove"></i></a> </td>
		  </tr>
		  <?php } ?>
		  <tr><td><strong>Total: <?php $totalcount=mysql_num_rows($cart); echo  $totalcount; ?></strong></td>
		  <td><strong>Total: <?php echo $sumqty['qty']; ?></strong></td>
		  <td><strong>Total: <?php echo $pricetotal; ?></strong></td>
		    <td><strong>Total: <?php echo $subpricetotal; ?></strong></td>
		  </tr>
		  <?php }else{ ?>
		  <tr><td colspan="6"><div class="alert alert-danger">No Item Added on your cart</div></td></tr>
		  <?php } ?>
		  
		  
	</table>
	</div>
	<?php   if(mysql_num_rows($cart)>=1){ ?>
	<div class="produced">
	<a  style="cursor:pointer;"  href="#" data-modal-id="popup1" class="hvr-skew-backward js-open-modal">Produced To Buy</a>
	 </div>
	 <?php } ?>
	</div>
	<div class="panel-footer">
	My Cart
	</div>
	</div>
	</div>
	
    </div>
</div>

<div id="popup1" class="modal-box">
  <header  style="background-color: #760075;color: white;"> <a href="#" class="js-modal-close close">&times;</a>
    <h3>Address Details</h3>
  </header>
  <div class="modal-body">
  <div class="alert alert-danger" id="productoutoffstock12" style="display:none;"></div>
  <?php $query=mysql_query("select *from address_details where uid='".$_SESSION['ID']."'");  $Q=mysql_fetch_array($query);?>
  <form method="post" enctype="multipart/form-data" id="addtocardforms">
  <div class="row">
  <div class="col-md-12">
  <div class="form-group">
  <label>Name <b style="color:red;">*</b></label>
  <input type="text" class="form-control" name="name" onChange="namer();" value="<?php echo $Q['name']; ?>" id="name">
  <span id="namer" style="color:red;"></span>
  </div>
  <div class="form-group">
  <label>Mobile No <b style="color:red;">*</b></label>
  <input type="text" class="form-control" name="mobile_no" id="mobile_no" value="<?php echo $Q['mobile_no']; ?>" onChange="mobile_nor()">
    <span id="mobile_nor" style="color:red;"></span>
  </div>
   <div class="form-group">
  <label>Pin Code <b style="color:red;">*</b></label>
  <input type="text" class="form-control" name="pincode" id="pincode"  value="<?php echo $Q['pincode']; ?>"  onChange="pincoder()">
     <span id="pincoder" style="color:red;"></span>
  </div>
   <div class="form-group">
  <label>State <b style="color:red;">*</b></label>
  <input type="text" class="form-control" name="state" onChange="stater()" value="<?php echo $Q['state']; ?>" id="state" required>
  <span id="stater" style="color:red;"></span>
  </div>
   <div class="form-group">
  <label>City <b style="color:red;">*</b></label>
  <input type="text" class="form-control" onChange="cityr" name="city" id="city" value="<?php echo $Q['city']; ?>" required>
  <span id="cityr" style="color:red;"></span>
  </div>
   <div class="form-group">
  <label>Address Details <b style="color:red;">*</b></label>
  <textarea type="text" class="form-control" name="address_details" id="address_details" value="" onChange="address_detailsr()" style="resize:none;" required><?php echo $Q['address']; ?></textarea>
    <span id="address_detailsr" style="color:red;"></span>
  </div>
   <div class="form-group">
   <input type="submit" class="hvr-skew-backward" value="Submit" onClick="return place_your_orders();">
   </div>
  </div>
  </div>
  </form>
  </div>
  <footer> <a href="#" class="btn btn-small js-modal-close">Close</a> </footer>
</div>
<?php } ?>
<?php if($_GET['action']=='view_wishlist_products') {
$page =$_POST['page'];
$setLimit = 18;
$pageLimit = ($page * $setLimit) - $setLimit;
$query='';
$query="select *from wishlist where uid='".$_SESSION['ID']."' order by wid desc LIMIT ".$pageLimit." , ".$setLimit;
$mysqluery=mysql_query($query);
 ?>
		<?php if(isset($_SESSION['Success'])) { ?><div class="alert alert-success"><?php echo $_SESSION['Success']; ?></div><?php unset($_SESSION['Success']); } ?>
			<?php if(mysql_num_rows($mysqluery)>=1){ ?>
			<div class="mid-popular" id="getwishlistproducts">
				<input type="hidden" name="pagedid"  id="pagedid" value="<?php echo $page; ?>" />
		
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
								<!--<a style="cursor:pointer;"  onClick="return addproductincart(<?php echo $rows['pid']; ?>)"><img src="images/ca.png" alt=""></a>-->
								<?php if($rows['product_quantity']<=10){ ?>
								<span>Left <?php echo $rows['product_quantity']; ?></span>
								<?php } ?>
							</div>
							<?php }else{ ?>
							<div class="img item_add">
								<a  href="login.php"><img src="images/ca.png" alt=""></a>
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
				<?php }else{ ?>
				<div class="alert alert-danger">No Wishlist Records Founds</div>
				<?php } ?>
				<link href="css/popuo-box.css" rel="stylesheet" type="text/css" media="all"/>
			<script src="js/jquery.magnific-popup.js" type="text/javascript"></script>
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

<?php } ?>