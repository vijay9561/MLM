<?php
 include('config.php');
		if(isset($_SESSION['JOBPORTALADMIN'])) { ?>
<!DOCTYPE html>
<?php 
$mysqyrk = $_SERVER['REQUEST_URI'];
$components12 = explode('/', $mysqyrk);
$mystring=explode('.',$components12[3]);
//print_r($mystring);
 ?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin-
<?php  echo $mystring[0]; ?>
</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/datepicker3.css" rel="stylesheet">
<link href="css/bootstrap-table.css" rel="stylesheet">
<link href="css/styles.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<!--Icons-->
<script src="js/lumino.glyphs.js"></script>
	<script src="js/jquery-1.11.1.min.js"></script>
<!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->
<style>
.nofticationicons{
position: absolute;
z-index: 99999;
background-color: #e40909;
padding: 3px;
    padding-right: 3px;
    padding-left: 3px;
font-size: 15px;
border-radius: 50%;
padding-left: 8px;
padding-right: 9px;
color: white;
top: -12px;
left: 10px;
}
</style>
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <?PHP if($_SESSION['ADMIN_TYPE']=='Vendors'){ ?>
      <a class="navbar-brand" href="index.php"><span>BUY MART</span> <?php echo $_SESSION['STORE_NAME']; ?> </a>
      <?php }else{ ?>
     <a class="navbar-brand" href="index.php"><span>BUY MART</span> TRADE INDIA</a>
      <?php } ?>
      <ul class="user-menu">
        <li class="dropdown pull-right"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">
         <i class="fa fa-user"></i>
          <?php echo $_SESSION['JOBPORTALADMIN']; ?> <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
              <?PHP if($_SESSION['ADMIN_TYPE']=='Vendors'){ ?>
              <li><a href="profiles.php"><i class="fa fa-user"></i> Seller Profile</a></li>  
              <?php } ?>
            <li><a href="change-password.php">
			<i class="fa fa-pencil"></i>
              Change Password</a></li>
            <li><a href="logout.php">
              <i class="fa fa-circle-o-notch"></i>
              Logout</a></li>
          </ul>
        </li>
       <!-- <li class="pull-right"><a href="pin-code-master.php"><i class="fa fa-map-marker"></i> Location</a>&nbsp;&nbsp;&nbsp;</li>
		<li class="dropdown pull-right" id="load_me">
		
	  </li>-->
        	<li class="dropdown pull-right" id="load_me">
		
	  </li>
    </div>
  </div>
  <!-- /.container-fluid -->
</nav>
<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
  <!-- <form role="search">
			<div class="form-group">
				<input type="text" class="form-control" placeholder="Search">
			</div>
		</form>-->
  <ul class="nav menu">
    <?php 
$directoryURI = $_SERVER['REQUEST_URI'];
$path = parse_url($directoryURI, PHP_URL_PATH);
$components = explode('/', $path);
$first_part = $components[3];
$first_part1 = $components[2];

?>
<script>
function updathidenotification(){
			$.ajax({
			url: "post.php?action=update_mynotifications",
			type: 'POST',
			data: {},
			success: function(data) {
			//$("#load_me").fadeOut().html(data).fadeIn('slow');
			}
			});
}
 $(document).ready(function() {
                function loadData() {
                    $('#load_me').load('js.php', function() {
                       if (window.reloadData != 0)
                           window.clearTimeout(window.reloadData);
                       window.reloadData = window.setTimeout(loadData,2000)
                   }).fadeIn("slow"); 
                }
             window.reloadData = 0; // store timer load data on page load, which sets timeout to reload again
               loadData();
               
  $('#search_order_id').click(function(){
  var search_id=document.getElementById("search_id").value.trim();
  if(search_id==""){
   //alert();
   $("#validationsearch").html("Please enter order id");
   $("#search_id").focus();
    return false;
  }
window.location="search_order.php?vieworders="+search_id;
});	
});
            
  
</script>
    <style>
#subordersmenu li a{
color:#30a5ff;background-color:#FFFFFF;
}
#subordersmenu li a:hover{ background-color:#0099FF; color:#FFFFFF;}
.submenuactive{ background-color:#0099FF; color:#FFFFFF;}
</style>
<script>

</script>
<!--<li><input type="text" name="search_id" id="search_id" style="margin-left: 4px;margin-top: 3px;" placeholder="Order ID" />&nbsp;
    <input id="search_order_id" type="button" name="sub" style="padding-top:3px; padding-bottom:3px;" value="Search" class="btn btn-danger btn-sm"> </li>
<li><span id="validationsearch" style="color:red;"></span></li>-->   

    <li class="<?php if($first_part=='index.php' && $first_part1=='admin'){ echo 'active';}else{ echo ''; } ?>"><a href="index.php">
     <i class="fa fa-dashboard" aria-hidden="true"></i>
      Dashboard</a></li>
    <?php /*?><li  class="<?php if($first_part=='category.php'){ echo 'active';}else{ echo ''; } ?>"><a href="category.php">
      <svg class="glyph stroked calendar">
        <use xlink:href="#stroked-calendar"></use>
      </svg>
      Category</a></li><?php */?>
	  
<?php  if($_SESSION['ADMIN_TYPE']=='Admin' || $_SESSION['ADMIN_TYPE']=='Employee'){ ?>	    
   <li class="dropdown <?php if($first_part=='category.php' ||$first_part=='subcategory.php' || $first_part=='sub-sub-category.php'){ echo 'active';}else{ echo ''; } ?>"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-list-alt" aria-hidden="true"></i> Product Category<span class="caret"></span></a>
	 <ul class="dropdown-menu" id="subordersmenu" role="menu" style="width: 100%;">
        <li><a   class="<?php if($first_part=='category.php'){ echo 'submenuactive';}else{ echo ''; } ?>" href="category.php"><i class="glyphicon glyphicon-th" aria-hidden="true"></i> Main Category</a></li>
        <li ><a class="<?php if($first_part=='subcategory.php'){ echo 'submenuactive';}else{ echo ''; } ?>" href="subcategory.php"><i class="glyphicon glyphicon-th" aria-hidden="true"></i> Sub Category</a></li>
        <li ><a class="<?php if($first_part=='sub-sub-category.php'){ echo 'submenuactive';}else{ echo ''; } ?>"  href="sub-sub-category.php"><i class="glyphicon glyphicon-th" aria-hidden="true"></i> Sub Sub Category</a></li>
      </ul>
</li>
<?php } ?>
   <?php /*?> <li  class="<?php if($first_part=='subcategory.php'){ echo 'active';}else{ echo ''; } ?>"><a href="subcategory.php">
      <svg class="glyph stroked line-graph">
        <use xlink:href="#stroked-line-graph"></use>
      </svg>
      Sub Category</a></li><?php */?>
  <?php /*?>  <li  class="<?php if($first_part=='sub-sub-category.php'){ echo 'active';}else{ echo ''; } ?>"><a href="sub-sub-category.php">
      <svg class="glyph stroked line-graph">
        <use xlink:href="#stroked-line-graph"></use>
      </svg>
      Sub Sub Category</a></li><?php */?>
   <li  class="<?php if($first_part=='product.php'){ echo 'active';}else{ echo ''; } ?>"><a href="product.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Manage Product</a></li>
   <!--  <li class="dropdown <?php if($first_part=='new-orders.php' || $first_part=='confirm-orders.php' || $first_part=='delivery-orders.php' || $first_part=='cancel-orders.php'  || $first_part=='recived-orders.php'){ echo 'active';}else{ echo ''; } ?>"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-truck" aria-hidden="true"></i> Orders<span class="caret"></span></a>
      <ul class="dropdown-menu" id="subordersmenu" role="menu" style="width: 100%;">
        <li><a   class="<?php if($first_part=='new-orders.php'){ echo 'submenuactive';}else{ echo ''; } ?>" href="new-orders.php"><i class="fa fa-truck" aria-hidden="true"></i> New Orders</a></li>
        <li ><a class="<?php if($first_part=='confirm-orders.php'){ echo 'submenuactive';}else{ echo ''; } ?>" href="confirm-orders.php"><i class="fa fa-truck" aria-hidden="true"></i> Confirm Orders</a></li>
        <li ><a class="<?php if($first_part=='delivery-orders.php'){ echo 'submenuactive';}else{ echo ''; } ?>"  href="delivery-orders.php"><i class="fa fa-truck" aria-hidden="true"></i> Delivery Orders</a></li>
        <li><a class="<?php if($first_part=='cancel-orders.php'){ echo 'submenuactive';}else{ echo ''; } ?>"  href="cancel-orders.php"><i class="fa fa-truck" aria-hidden="true"></i> Cancel Orders</a></li>
        <li><a class="<?php if($first_part=='recived-orders.php'){ echo 'submenuactive';}else{ echo ''; } ?>"  href="recived-orders.php"><i class="fa fa-truck" aria-hidden="true"></i> Received Orders</a></li>
		 <li><a class="<?php if($first_part=='return-orders.php'){ echo 'submenuactive';}else{ echo ''; } ?>"  href="return-orders.php"><i class="fa fa-truck" aria-hidden="true"></i> Return Orders</a></li>
      </ul>
    </li>-->
    <?php  if($_SESSION['ADMIN_TYPE']=='Admin'){ ?>
	<li class="dropdown <?php if($first_part=='users.php' || $first_part=='vendor.php' || $first_part=='employee.php'){ echo 'active';}else{ echo ''; } ?>"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-users" aria-hidden="true"></i> Users <span class="caret"></span></a>
            <ul class="dropdown-menu" id="subordersmenu" role="menu" style="width: 100%;">
		<li  class="<?php if($first_part=='users.php'){ echo 'active';}else{ echo ''; } ?>"><a href="users.php"><span class="fa fa-user"></span> Customer</a></li>
                <li  class="<?php if($first_part=='vendor.php'){ echo 'active';}else{ echo ''; } ?>"><a href="vendor.php"><span class="fa fa-user"></span> Seller</a></li>
                <li  class="<?php if($first_part=='employee.php'){ echo 'active';}else{ echo ''; } ?>"><a href="employee.php"><span class="fa fa-user"></span> Employee</a></li>
            </ul>
       </li> 
    <?php } ?> 
       
       <li class="dropdown <?php if($first_part=='product_enquiry.php' || $first_part=='product_enquiry_inprogress.php' || $first_part=='product_enquiry_cancel.php' || $first_part=='product_enquiry_deliver.php'){ echo 'active';}else{ echo ''; } ?>"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-truck" aria-hidden="true"></i> Order Enquiry <span class="caret"></span></a>
            <ul class="dropdown-menu" id="subordersmenu" role="menu" style="width: 100%;">
                <?php  if($_SESSION['ADMIN_TYPE']=='Admin'){ ?>
                <li  class="<?php if($first_part=='product_enquiry.php'){ echo 'active';}else{ echo ''; } ?>"><a href="product_enquiry.php"><span class="fa fa-user"></span> New Order </a></li>
                <?php } ?>
                <li  class="<?php if($first_part=='product_enquiry_inprogress.php'){ echo 'active';}else{ echo ''; } ?>"><a href="product_enquiry_inprogress.php"><span class="fa fa-user"></span> Inprogress Order </a></li>
                <li  class="<?php if($first_part=='product_enquiry_cancel.php'){ echo 'active';}else{ echo ''; } ?>"><a href="product_enquiry_cancel.php"><span class="fa fa-user"></span> Cancel Order </a></li>
                <li  class="<?php if($first_part=='product_enquiry_deliver.php'){ echo 'active';}else{ echo ''; } ?>"><a href="product_enquiry_deliver.php"><span class="fa fa-user"></span> Deliverd Order</a></li>
            </ul>
       </li> 
       <?php  if($_SESSION['ADMIN_TYPE']=='Admin' || $_SESSION['ADMIN_TYPE']=='Employee'){ ?>	 
		<!--<li  class="<?php if($first_part=='pin-code-master.php'){ echo 'active';}else{ echo ''; } ?>"><a href="pin-code-master.php"><i class="fa fa-map-marker"></i>  Locations</a></li>-->
		<li  class="<?php if($first_part=='subscribe-users.php'){ echo 'active';}else{ echo ''; } ?>"><a href="subscribe-users.php"><span class="fa fa-envelope"></span> Subscribe Users</a></li>
		<!--<li  class="<?php if($first_part=='notification-details.php'){ echo 'active';}else{ echo ''; } ?>"><a href="notification-details.php"><i class="fa fa-globe"></i> Notification</a></li>-->
		<li  class="<?php if($first_part=='product-review.php'){ echo 'active';}else{ echo ''; } ?>"><a href="product-review.php"><i class="fa fa-comment-o"></i> Product Review</a></li>
       <?php } ?>
             <li class="<?php if($first_part=='change-password.php'){ echo 'active';}else{ echo ''; } ?>"><a href="change-password.php"><i class="fa fa-pencil"></i> Change Password</a></li>    
            <li  class="<?php if($first_part=='notification-details.php'){ echo 'active';}else{ echo ''; } ?>"><a href="notification-details.php"><i class="fa fa-globe"></i> Notification</a></li>
            <?php if($_SESSION['ADMIN_TYPE']=='Vendors'){ ?>
            <li  class="<?php if($first_part=='profiles.php'){ echo 'active';}else{ echo ''; } ?>"><a href="profiles.php"><i class="fa fa-user"></i> Profile</a></li>
            <?php } ?>
  </ul>
</div>
<!--/.sidebar-->
<?php }else{  
   header('Location:login.php');
?>
<?php } ?>
