
<?php include('header.php'); ?>
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

.btn {
  font-size: 3vmin;
  padding: 0.75em 1.5em;
  background-color: #fff;
  border: 1px solid #bbb;
  color: #333;
  text-decoration: none;
  display: inline;
  border-radius: 4px;
  -webkit-transition: background-color 1s ease;
  -moz-transition: background-color 1s ease;
  transition: background-color 1s ease;
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
<!--banner-->
<div class="banner-top">
	<div class="container">
		<h1>Checkout</h1>
		<em></em>
		<h2><a href="index.html">Home</a><label>/</label>Checkout</a></h2>
	</div>
</div>
<?php
 $cart=mysql_query("select *from cart where uid='".$_SESSION['ID']."'"); 
		       $sumallquanity = mysql_query("SELECT SUM(quantity) AS qty FROM cart where uid='".$_SESSION['ID']."'");
			   $sumqty=mysql_fetch_array($sumallquanity);
			   $countcart=mysql_num_rows($cart);
 ?>
<!--login-->
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
		  <tr><td colspan="6"><div class="alert alert-danger">No Item(s) Added in your cart</div></td></tr>
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
<!--//login-->
<!--brand-->
		<?php /*?><div class="container">
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
			</div><?php */?>
			<!--//brand-->
			</div>
			<br />
		</div>
	<?php include('footer.php'); ?>
	
	<script>
	function plusvalues(id){
	//  plusvalues=$("#carta_count"+id).text();
	   newVal = parseInt($("#carta_count"+id).text(), 10)+1;
	   //  var newVal = $("#carta_count"+id).text();
			//alert(qty);
			//return false;
			 var pid = $("#productid"+id).val();
			// alert(pid);
			// return false;
				$("#loading").show(); 
	   if(newVal<=10){
	   $("#carta_count"+id).text(newVal);
	   $.ajax({
				url: "post.php?action=cart_update_yours",
				type: 'POST',
				data: {id:id,qty:newVal,pid:pid},
				success: function(data) {
				if(data==1){
			   // window.location="cart.php";
				$.ajax({
				url: "ajax-cart.php?action=get_temp_items_details",
				type: 'POST',
				data: {},
				success: function(data) {
				$("#getcartdetails").fadeOut().html(data).fadeIn('slow');
				}
				});
				$("#loading").hide(); 
				return false;
				}else{
				$("#productoutoffstock").show();
				$("#productoutoffstock").html('Product quantity is out of stock');  
				$(document).ready(function(){ setTimeout(function(){ $("#productoutoffstock").fadeOut('slow'); }, 5000); }); 
				return false;
				}
			}
		});
	   
	   }else{$("#loading").hide(); 
	    alert("only 10 quantity add at time");
	   }
	}
	function minusvalues(id){
	  var qty = $("#carta_count"+id).text();
			//alert(qty);
			//return false;
			 var pid = $("#productid"+id).val();
			 $("#loading").show(); 
	//  plusvalues=$("#carta_count"+id).text();
	   newVal = parseInt($("#carta_count"+id).text(), 10)-1;
	   if(newVal>=1){
	   $("#carta_count"+id).text(newVal);
	    $.ajax({
				url: "post.php?action=cart_update_yours",
				type: 'POST',
				data: {id:id,qty:newVal,pid:pid},
				success: function(data) {
				if(data==1){
			   // window.location="cart.php";
				$.ajax({
				url: "ajax-cart.php?action=get_temp_items_details",
				type: 'POST',
				data: {},
				success: function(data) {
				$("#getcartdetails").fadeOut().html(data).fadeIn('slow');
				}
				});
				$("#loading").hide(); 
				return false;
				}else{
				$("#productoutoffstock").show();
				$("#productoutoffstock").html('Product quantity is out of stock');  
				$(document).ready(function(){ setTimeout(function(){ $("#productoutoffstock").fadeOut('slow'); }, 5000); }); 
				return false;
				}
			}
		});
	   }
	   $("#loading").hide(); 
	}
   /* $('.value-plus').on('click', function(){
    	var divUpd = $(this).parent().find('.value'), newVal = parseInt(divUpd.text(), 10)+1;
    	divUpd.text(newVal);
    });

    $('.value-minus').on('click', function(){
    	var divUpd = $(this).parent().find('.value'), newVal = parseInt(divUpd.text(), 10)-1;
    	if(newVal>=1) divUpd.text(newVal);
    });
	*/
	function deletecartitems(id) {
		var r=confirm('Are you sure you want to delete this item?');
		if(r==true)
		{   
		$("#loading").show(); 
		$.ajax({
		url: "ajax-cart.php?action=delete_cart_items",
		type: 'POST',
		data: {id: id},
		success: function(data) {
		if(data){
	    $("#getcartdetails").fadeOut().html(data).fadeIn('slow');
			$.ajax({
			url: "post.php?action=view_itmes_counts",
			type: 'POST',
			data: {},
			success: function(data) {
			$("#cartitmes").fadeOut().html(data).fadeIn('slow');
			}
			});
		$("#loading").hide(); 
		}else{
		alert("not deleted your products"); 
		}
		}
		});
		return false;
		} else
		{
		return false;	
		}
		}
		
		function update_cart_yours(id){
		    var qty = $("#carta_count"+id).text();
			//alert(qty);
			//return false;
			 var pid = $("#productid"+id).val();
			// alert(pid);
			// return false;
				$("#loading").show(); 
				$.ajax({
				url: "post.php?action=cart_update_yours",
				type: 'POST',
				data: {id:id,qty:qty,pid:pid},
				success: function(data) {
				if(data==1){
			   // window.location="cart.php";
				$.ajax({
				url: "ajax-cart.php?action=get_temp_items_details",
				type: 'POST',
				data: {},
				success: function(data) {
				$("#getcartdetails").fadeOut().html(data).fadeIn('slow');
				}
				});
				$("#loading").hide(); 
				return false;
				}else{
				$("#productoutoffstock").show();
				$("#productoutoffstock").html('Product quantity is out of stock');  
				$(document).ready(function(){ setTimeout(function(){ $("#productoutoffstock").fadeOut('slow'); }, 5000); }); 
				return false;
				}
			}
		});
	}

$(document).ready(function(){ setTimeout(function(){ $(".alert-success").fadeOut('slow'); }, 5000); }); 
	</script>
	<script>
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

function namer(){if($('#name').val()==''){}else{ $('#namer').html(' '); }}
function mobile_nor(){if($('#mobile_no').val()==''){}else{ $('#mobile_nor').html(' '); }}
function pincoder(){if($('#pincode').val()==''){}else{ $('#pincoder').html(' '); }}
function stater(){if($('#state').val()==''){}else{ $('#stater').html(' '); }}
function cityr(){if($('#state').val()==''){}else{ $('#cityr').html(' '); }}
function address_detailsr(){if($('#address_details').val()==''){}else{ $('#address_detailsr').html(' '); }}
function place_your_orders(){
 
		var mobilenovalidation=/^\d{10}$/;
		var pat1=/^\d{6}$/;
	
			var   	name=document.getElementById('name').value.trim();
			var  mobile_no=document.getElementById('mobile_no').value.trim();
			var  pincode=document.getElementById('pincode').value.trim();
			var  state=document.getElementById('state').value.trim();
			var  city=document.getElementById('city').value.trim();
			var  address_details=document.getElementById('address_details').value.trim();

				
			if(name==''){
			$("#namer").html('Please enter name');
			return false;
			}
			if(mobile_no==''){
			$("#mobile_nor").html('Please enter contact number');
			return false;
			}
			if (!(mobile_no.match(mobilenovalidation))) {
			$("#mobile_nor").html("Please enter valid contact number");	
			return false;
			}
			if(pincode==''){
			$("#pincoder").html('Please enter pincode');
			return false;
			}
			if (!(pincode.match(pat1))) {
			$("#pincoder").html("Please 6 digit pincode");	
			return false;
			}
			if(state==''){
			$("#stater").html('Please enter state name');
			return false;
			}
			if(city==''){
			$("#cityr").html('Please enter city name');
			return false;
			}
			if(address_details==''){
			$("#address_detailsr").html('Please enter address details');
			return false;
			}
			
			var formData = new FormData($("#addtocardforms")[0]);
			$.ajax({   
				url: "post.php?action=placemyordersdetails",
				data : formData,
				processData: false,
				contentType: false,
				type: 'POST',
				success: function(data){
				if(data==5){
				 $("#productoutoffstock12").show();
				$("#productoutoffstock12").html('This Address Not Available On To The Delivery Product');  
				$(document).ready(function(){ setTimeout(function(){ $("#productoutoffstock12").fadeOut('slow'); }, 10000); }); 
				return false;
				}else if(data==2){
					window.location="my-orders.php";
				     	}else {
                        alert('order is out of stock');
					 return false;
					}
			}
			});
			return false;
				
	}
	
	
</script>
	
	<!--//content-->
	<!--//footer-->
	
	