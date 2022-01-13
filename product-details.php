	<?php include('header.php');  $query=mysql_query("select *from product_details where pid='".$_GET['details']."'"); $rows=mysql_fetch_array($query);
	 $productimages=mysql_query("select *from product_images where pid=".$rows['pid']." order by piid asc");
	 
	 
	?>
	<?php include_once 'dbConfig.php';
$query1="select *from product_details where pid='".$_GET['details']."'";
$result1 = $db->query($query1);
$result12 = $result1->fetch_assoc();
$query = "SELECT rating_number, FORMAT((total_points / rating_number),1) as average_rating FROM feedback_rating WHERE uid ='".$result12['pid']."' AND status = 1";
$result = $db->query($query);
$ratingRow = $result->fetch_assoc();
$currentusers="select *from  product_details where pid='".$_GET['details']."'";
$rowsS = $db->query($currentusers);
$u = $rowsS->fetch_assoc();
$currentgeted=mysql_query("select *from  product_details where pid='".$_GET['details']."'");
$countall=mysql_num_rows($currentgeted);
 ?>
  <link href="rating.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="rating.js"></script>
	<?php if(isset($_GET['details']) && (!empty($_GET['details']))) {  }else{ header("Location:index.php"); }?>
<!--banner-->

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

.btn1 {
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

.btn1:hover {
  background-color: #ddd;
  -webkit-transition: background-color 1s ease;
  -moz-transition: background-color 1s ease;
  transition: background-color 1s ease;
}

.btn1-small {
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
.modal-box .modal-body {
    padding: 2em 1.5em;
    overflow-y: scroll;
    height: 300px;
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
 .disabled {
        pointer-events: none;
        cursor: default;
        opacity: 0.6;
    }
	
.news_list {
list-style: none;
}
.loadmore {
color: #FFF;
}
.loadbutton{
text-align: center;
}
</style>

<div id="Submitmyreview" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Submit Review</h4>
              </div>
              <div class="modal-body">
			  <div class="alert alert-danger" id="errormessages" style="display:none;"></div>
                <form method="post" action="#" id="productreviews" enctype="multipart/form-data"> 
				<input type="hidden" id="productid"  name="productid" maxlength="200" value="<?php echo $_GET['details'] ?>">
				  <div class="form-group">
				  <label>Product Reviews <b style="color:red;">*</b></label>
				  <textarea id="comment" name="comment" onChange="commentr()" maxlength="200" class="form-control" style="resize:none;"></textarea>
				  <span id="commentr" style="color:red;"></span>
				  </div>
				  <input type="submit" class="hvr-skew-backward" value="Submit" onClick="return submit_review()">
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
<div class="banner-top">
	<div class="container" style="text-align: center;margin-top: 102px;">
		<h1>Single</h1>
		<em></em>
		<h2><a href="index.html">Home</a><label>/</label>Single</a></h2>
	</div>
</div>
<div class="single">
<div class="container">

<?php if(isset($_SESSION['Success'])){ ?>
 <div class="alert alert-success"><?php echo $_SESSION['Success']; ?></div>
<?php unset($_SESSION['Success']); } ?>

<?php if(isset($_SESSION['Errors'])){ ?>
<div class="alert alert-danger"><?php echo $_SESSION['Errors']; ?></div>
<?php unset($_SESSION['Errors']); } ?>

<div class="col-md-9" style="border-right:3px solid #EFEFEF;margin-top:32px;margin-bottom:51px;">
	<div class="col-md-5 grid">		
		<div class="flexslider" style="width:100%;">
			  <ul class="slides">
			  <?php while($images=mysql_fetch_array($productimages)) { ?>
			    <li data-thumb="admin/images/product/<?php echo $images['product_path']; ?>">
			        <div class="thumb-image"> <img src="admin/images/product/<?php echo $images['product_path']; ?>" data-imagezoom="true" class="img-responsive"> </div>
			    </li>
				<?php } ?>
			  </ul>
		</div>
	</div>	
<div class="col-md-7 single-top-in">
						<div class="span_2_of_a1 simpleCart_shelfItem">
				<h3><?php echo $rows['title']; ?></h3>
				<!--<p class="in-para"> There are many variations of passages of Lorem Ipsum.</p>-->
				 <input name="rating" value="0" id="rating_star" type="hidden" postID="<?php echo $result12['pid']; ?>" />
		  <input type="hidden" value="<?php echo $ratingRow['average_rating']; ?>" id="initialvalues" />
	  <div class="overall-rating" style="color: #581b56;font-size: 16px;"><span id="avgrat"> <?php  if(!empty($ratingRow['average_rating'])) { echo $ratingRow['average_rating']; ?>  Star Rating <?php } ?></span>
 <span id="totalrat"><?php // echo $ratingRow['rating_number']; ?></span> </span> </div>
			    <div class="price_single">
				  <span class="reducedfrom item_price">Rs. <?php echo $rows['discount_price']; ?> INR </span>
				  
				<!--  <a href="#">click for offer</a> -->
				 <div class="clearfix"></div>
				</div>
				<!-- <h4 class="quick">Quick Overview:</h4> -->
				<!-- <p class="quick_desc"> Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Typi non habent claritatem insitam; es</p> -->
			    <div class="wish-list">
				 	<ul>
					<?php if(isset($_SESSION['ID'])) { ?>
				 		<li class="wish"><a  style="cursor:pointer;"  onClick="return wislistaddedproducts(<?php echo $rows['pid'] ?>)"><span class="glyphicon glyphicon-heart" aria-hidden="true"></span>Add to Wishlist</a></li><?PHP }else{ ?>
	<strong><li class="wish"><a  href="login.php"><span class="glyphicon glyphicon-heart" aria-hidden="true"></span>Add to Wishlist</a></li></strong>
						<?php } ?>
						<?php if($rows['product_quantity']<=10){ ?>
						<li lass="wish"> <span style="float:right;">Left <?php echo $rows['product_quantity']; ?></span></li>
						<?php } ?>
				 	 <li class="compare"><a  style="cursor:pointer;"  name="cbox"  onClick="mycheckedboxmy(<?php echo $rows['pid']; ?>)"  id="checkid<?php echo $rows['pid']; ?>"><span class="glyphicon glyphicon-resize-horizontal" aria-hidden="true"></span>Add to Compare</a></li>
				 	</ul>
				 </div>
				<!-- <div class="quantity"> 
								<div class="quantity-select">                           
									<div class="entry value-minus">&nbsp;</div>
									<div class="entry value"><span>1</span></div>
									<div class="entry value-plus active">&nbsp;</div>
								</div>
							</div>-->
							<!--quantity-->
	<script>
    $('.value-plus').on('click', function(){
    	var divUpd = $(this).parent().find('.value'), newVal = parseInt(divUpd.text(), 10)+1;
		if(newVal<=10){
    	divUpd.text(newVal);
		}else{
		 alert("Only 10 quantity added to the at time")
		}
    });

    $('.value-minus').on('click', function(){
    	var divUpd = $(this).parent().find('.value'), newVal = parseInt(divUpd.text(), 10)-1;
    	if(newVal>=1) divUpd.text(newVal);
    });
	function commentr(){ if($("#comment").val()==''){}else{ $("#commentr").html(' '); }}
	function submit_review(){
  var  comment=$("#comment").val();
  var productid=$("#productid").val();
  if(comment==""){
   $("#commentr").html("Please enter about product");
   $("#comment").focus();
   return false;
    }    $("#loading").show(); 
	       var formData = new FormData($("#productreviews")[0]);
			$.ajax({   
				url: "post.php?action=ProductReview",
				data : formData,
				processData: false,
				contentType: false,
				type: 'POST',
				success: function(data){
					if(data==1){
					    window.location='product-details.php?details='+productid;
						return false;
				     	 }else {
						 $("#loading").hide(); 
					   $("#errormessages").show();
                       $("#errormessages").html("Your Ordered Did'nt Cancelation Because Your Orders Delivered Successfully..");
					 return false;
					}
				}
			});
	return false;

}
	</script>
	<!--quantity-->
				<?php if(isset($_SESSION['ID'])) {  if($rows['product_quantity']<=0) {?>
				    <a href="#" class="add-to item_add hvr-skew-backward disabled">Out Off Stock</a>
					<?php }else{ ?>
					 <!--  <a href="#" class="add-to item_add hvr-skew-backward" onClick="return addtotdetailscartoncart(<?php echo $rows['pid']; ?>)">Add to cart</a><a href="#" class="add-to item_add hvr-skew-backward"  style="margin-right:10px;" data-modal-id="popup1">Buy Now</a>-->
                                      <?php $product_id=$_GET['details']; $userid=$_SESSION['ID']; $date=date('Y-m-d');
                                      $interested=mysql_query("select created_date from product_enquiry where userid='$userid' and product_id='$product_id' and DATE(created_date)=CURDATE()");
                                      $count_array=mysql_num_rows($interested);
                                      if($count_array>=1){
                                      ?>
                                    <a href="#" class="add-to  btn material-btn material-btn_primary">Already Enquiry Send</a>
                                      <?php }else{ ?>
                                      <a href="#" class="add-to  btn material-btn material-btn_primary" data-toggle="modal" data-target="#product_enquiry" onClick="return product_enquiry_details_get(<?php echo $rows['pid']; ?>)">Product Enquiry</a> <!--<a href="#" class="add-to item_add hvr-skew-backward"  style="margin-right:10px;" data-modal-id="popup1">Buy Now</a>-->
                                 
                                      <?php } } ?>
				<?PHP }else{ ?>
 <a href="login.php?details=<?php echo $_GET['details']; ?>" class="add-to item_add hvr-skew-backward">Product Enquiry</a>
 <!--<a href="login.php?details=<?php echo $_GET['details']; ?>" class="add-to item_add hvr-skew-backward"  style="margin-right:10px;">Buy Now</a>-->
				<?php } ?>
			<div class="clearfix"> </div>
			</div>
		
					</div>
			<div class="clearfix"> </div>
			<!---->
		<div class="tab-head material-card">
			 <nav class="nav-sidebar">
		<ul class="nav tabs">
          <li class="active"><a href="#tab1" data-toggle="tab">Product Description</a></li>
          <li class=""><a href="#tab2" data-toggle="tab">Additional Information</a></li> 
          <li class=""><a href="#tab3" data-toggle="tab">Reviews</a></li>  
		</ul>
	</nav>
	<div class="tab-content one">
<div class="tab-pane active text-style" id="tab1">
 <div class="facts">
									  <p><?php echo $rows['description']; ?></p>
										
							        </div>

</div>
<div class="tab-pane text-style" id="tab2">
	
									<div class="facts">									
										
								<p><?php echo $rows['product_additional_description']; ?></p>
							        </div>	

</div>
<div class="tab-pane text-style" id="tab3">

									 <div class="facts" style="padding: 0 0 0em;">
									 <?php if(isset($_SESSION['ID'])) { ?>
									 <a href="#" data-toggle="modal" data-target="#Submitmyreview">Add Review</a>
									 <?PHP }else{ ?>
									 <a href="login.php?details=<?php echo $_GET['details']; ?>">Add Review</a>
									 <?php } ?>
									 <input type="hidden" value="<?php echo $_GET['details'];?>" id="productdetailsid" />
										<ul class="news_list">
										
										   <?php 
											$resultsPerPage=20;
										   $reviews1="select *from product_review where pid='".$_GET['details']."' and status='active' order by prid asc LIMIT 0 , $resultsPerPage"; 
										   $reviews=mysql_query($reviews1);
										   if(mysql_num_rows($reviews)>=1){ while($r=mysql_fetch_array($reviews)) {
										   $current=mysql_query("select name from registration where rid='".$r['uid']."'"); 
										   $u=mysql_fetch_array($current);?>
										  <li>
											<strong style="color:#000000;"><?php echo $u['name']; ?></strong>
											<p> <?php echo $r['description']; ?></p>
											<p>Reviews On <?php echo $recived_date=date('D-F-j-Y', strtotime($r['date'])); ?> </p>
											</li>
											<?php }?>
											<?PHP if(mysql_num_rows($reviews)>=$resultsPerPage){ ?>
										   <li class="loadbutton"><button style="float:inherit;" class="loadmore add-to item_add hvr-skew-backward" data-page="2" type="button">Load More</button></li>
											<?php } ?>
											<?PHP }else{ ?>
											<li><div class="alert alert-danger">No Any One Reviewed This Product</div></li>
											<?php } ?>
							 </div>

 </div>
  
  </div>
  <div class="clearfix"></div>
  </div>


  
			<!---->	
</div>
<!----->
<script type="text/javascript">
$(document).on('click','.loadmore',function () {
var productdetailsid=document.getElementById("productdetailsid").value.trim();
  $(this).text('Loading...');
    var ele = $(this).parent('li');
        $.ajax({
      url: 'loadmore.php',
      type: 'POST',
      data: {
              page:$(this).data('page'),
			  productdetailsid:productdetailsid,
            },
      success: function(response){
           if(response){
             ele.hide();
                $(".news_list").append(response);
              }
            }
   });
});
</script>
<div class="col-md-3 product-bottom product-at">
			<!--categories-->
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
 <!--<section  class="sky-form">
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
					<?php /*?> <section  class="sky-form">
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
				   </section><?php */?>
				   		<!--<section  class="sky-form">
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
				   </section>		-->
		</div>
		<div class="clearfix"> </div>
	</div>
	</div>
	<div id="popup1" class="modal-box">
  <header  style="background-color: #760075;color: white;"> <a href="#" class="js-modal-close close">&times;</a>
    <h3>Address Details</h3>
	<?php $query=mysql_query("select *from address_details where uid='".$_SESSION['ID']."'");  $Q=mysql_fetch_array($query);?>
  </header>
  <div class="modal-body">
  <div class="alert alert-danger" id="productoutoffstock12" style="display:none;"></div>
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
  <input type="text" class="form-control" name="mobile_no" id="mobile_no"  value="<?php echo $Q['mobile_no']; ?>" onChange="mobile_nor()">
    <span id="mobile_nor" style="color:red;"></span>
  </div>
   <div class="form-group">
  <label>Pin Code <b style="color:red;">*</b></label>
  <input type="text" class="form-control" name="pincode" id="pincode"  value="<?php echo $Q['pincode']; ?>"  onChange="pincoder()">
     <span id="pincoder" style="color:red;"></span>
  </div>
   <div class="form-group">
  <label>State <b style="color:red;">*</b></label>
  <input type="text" class="form-control" name="state" onChange="stater()"  value="<?php echo $Q['state']; ?>"  id="state" required>
  <span id="stater" style="color:red;"></span>
  </div>
   <div class="form-group">
  <label>City <b style="color:red;">*</b></label>
  <input type="text" class="form-control" onChange="cityr" name="city" id="city" value="<?php echo $Q['state']; ?>" required>
  <span id="cityr" style="color:red;"></span>
  </div>
   <div class="form-group">
  <label>Address Details <b style="color:red;">*</b></label>
  <textarea type="text" class="form-control" name="address_details" id="address_details" onChange="address_detailsr()" style="resize:none;" required><?php echo $Q['address']; ?></textarea>
    <span id="address_detailsr" style="color:red;"></span>
  </div>
   <div class="form-group">
   <input type="button" class="hvr-skew-backward" value="Submit" onClick="return place_your_orders(<?php echo $rows['pid']; ?>);">
   </div>
  </div>
  </div>
  </form>
  </div>
  <footer> <a href="#" class="btn btn-small js-modal-close">Close</a> </footer>
</div>
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
			<br />
		</div>
	<!--//content-->
	<?php include('footer.php'); ?>
	<script>
	
	function addtotdetailscartoncart(id){
	             var qty=$(".value").text();
				$.ajax({
				url: "post.php?action=add_to_cart_details_pages",
				type: 'POST',
				data: {id:id,qty:qty},
				success: function(data) {
				if(data==5){
				$("#addcartalreadyadded").show();
				$("#addcartalreadyadded").html('Your cart add at time only 20 product');  
				$(document).ready(function(){ setTimeout(function(){ $("#addcartalreadyadded").fadeOut('slow'); }, 5000); });
				return false;
				}else if(data==1){
				$("#addcartalreadyadded").show();
				$("#addcartalreadyadded").html('This Item Already Added On Your Cart');  
				$(document).ready(function(){ setTimeout(function(){ $("#addcartalreadyadded").fadeOut('slow'); }, 5000); });
				return false;
				}else if(data==2){
			   $.ajax({
				url: "post.php?action=view_itmes_counts",
				type: 'POST',
				data: {},
				success: function(data) {
				$("#cartitmes").fadeOut().html(data).fadeIn('slow');
				}
				});
				$("#addcartsuccess").show();
				$("#addcartsuccess").html('Item Added Successfully On Your Cart');  
				$(document).ready(function(){ setTimeout(function(){ $("#addcartsuccess").fadeOut('slow'); }, 5000); });
				return false;
				}else{
				$("#productoutoffstock").show();
				$("#productoutoffstock").html('This Items Out Off Stock');  
				$(document).ready(function(){ setTimeout(function(){ $("#productoutoffstock").fadeOut('slow'); }, 5000); }); 
				return false;
				}
			}
		});
	}
	
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
function place_your_orders(pid){
 
		var mobilenovalidation=/^\d{10}$/;
		var pat1=/^\d{6}$/;
	
			var   	name=document.getElementById('name').value.trim();
			var  mobile_no=document.getElementById('mobile_no').value.trim();
			var  pincode=document.getElementById('pincode').value.trim();
			var  state=document.getElementById('state').value.trim();
			var  city=document.getElementById('city').value.trim();
			var  address_details=document.getElementById('address_details').value.trim();
            var qty=$(".value").text();
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
				$.ajax({
				url: "post.php?action=product_details_get_orders",
				type: 'POST',
				data: {name:name,mobile_no:mobile_no,pincode:pincode,state:state,city:city,address_details:address_details,qty:qty,pid:pid},
				success: function(data) {
				if(data==5){
				 $("#productoutoffstock12").show();
				$("#productoutoffstock12").html('This Address Not Available On To The Delivery Product');  
				$(document).ready(function(){ setTimeout(function(){ $("#productoutoffstock12").fadeOut('slow'); }, 10000); }); 
				return false;
				}else if(data==1){
				 window.location="my-orders.php";
				 return false;
				}else{
				$("#productoutoffstock12").show();
				$("#productoutoffstock12").html('This Items Out Off Stock');  
				$(document).ready(function(){ setTimeout(function(){ $("#productoutoffstock12").fadeOut('slow'); }, 10000); }); 
				return false;
				 }
				}
				});
				
	}
	
	
 $(document).ready(function(){
    var intialvaluess=$("#initialvalues").val();
     $("#rating_star").codexworld_rating_widget({
        starLength: '5',
        initialValue:intialvaluess,
        callbackFunctionName: 'processRating',
        imageDirectory: 'images/',
        inputAttr: 'postID'
    });
});

function processRating(val, attrVal){
    $.ajax({
        type: 'POST',
        url: 'rating.php',
        data: 'postID='+attrVal+'&ratingPoints='+val,
        dataType: 'json',
        success : function(data) {
            if (data.status == 'ok') {
             //   alert('Thank You For '+val+' Star Rating ');
                $('#avgrat').html(val+"&nbsp;Star Rating");
               // $('#totalrat').text(data.rating_number);
				$("#ratingnumbers").val(val);
            }else{
                alert('Some problem occured, please try again.');
            }
        }
    });
}
	</script>
        
         
     