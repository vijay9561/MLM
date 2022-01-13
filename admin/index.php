<?php if(isset($_GET['username'])){ header("Location:index.php"); ?>

<?php } ?>
		<?php include('header.php'); 
		if(isset($_SESSION['JOBPORTALADMIN'])) { ?>
		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="index.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
				<li class="active">Dashboard</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<!--<h1 class="page-header">Dashboard</h1>-->
                                <br>
			</div>
		</div><!--/.row-->
<?php if($_SESSION['ADMIN_TYPE']=='Admin'){ ?>
		<div class="row">
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-blue panel-widget ">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<span style="font-size:3.5em;" class="glyphicon glyphicon-list-alt"></span>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php $category=mysql_query("select *from category"); echo mysql_num_rows($category); ?></div>
							<a href="category.php"><div class="text-muted">Main Category</div></a>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-orange panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<span style="font-size:3.5em;" class="glyphicon glyphicon-th"></span>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php $sub_category=mysql_query("select *from sub_category"); echo mysql_num_rows($sub_category); ?></div>
							<a href="subcategory.php"><div class="text-muted"> Sub Category</div></a>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-teal panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<span style="font-size:3.5em;" class="glyphicon glyphicon-th"></span>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php $registration=mysql_query("select *from sub_sub_category"); echo mysql_num_rows($registration); ?></div>
							<a href="sub-sub-category.php"><div class="text-muted">Sub Sub Category</div></a>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-red panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<span style="font-size:3.5em;" class="fa fa-shopping-cart"></span>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php $faq=mysql_query("select *from product_details"); echo mysql_num_rows($faq); ?></div>
								<a href="product.php"><div class="text-muted">Product</div></a>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-red panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
                                                    <i class="fa fa-users fa-4x"></i>    
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php $users=mysql_query("select *from registration where user_type='Users'"); echo mysql_num_rows($users); ?></div>
							<a href="users.php"><div class="text-muted">All Customer</div></a>
						</div>
					</div>
				</div>
			</div>
			
                    
                    <div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-red panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
                                                    <i class="fa fa-users fa-4x"></i>    
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php $users=mysql_query("select *from registration where user_type='Vendors'"); echo mysql_num_rows($users); ?></div>
							<a href="vendor.php"><div class="text-muted">All Seller</div></a>
						</div>
					</div>
				</div>
			</div>
                    
                     <div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-red panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
                                                    <i class="fa fa-users fa-4x"></i>    
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php $users=mysql_query("select *from registration where user_type='Employee'"); echo mysql_num_rows($users); ?></div>
                                                        <a href="employee.php"><div class="text-muted">All Employee</div></a>
						</div>
					</div>
				</div>
			</div>
			
			
			<!--<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-blue panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
						
							<i style="font-size:3.5em;" class="fa fa-truck" aria-hidden="true"></i>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php $send=mysql_query("select *from orders_details where status='send'"); echo mysql_num_rows($send); ?></div>
								<a href="new-orders.php"><div class="text-muted">New Orders</div></a>
						</div>
					</div>
				</div>
			</div>
			
			
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-orange panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
						
							<i style="font-size:3.5em;" class="fa fa-truck" aria-hidden="true"></i>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php $confirm=mysql_query("select *from orders_details where status='confirm'"); echo mysql_num_rows($confirm); ?></div>
								<a href="confirm-orders.php"><div class="text-muted">Confirm Orders</div></a>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-teal panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
						<i style="font-size:3.5em;" class="fa fa-truck" aria-hidden="true"></i>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php $delivered=mysql_query("select *from orders_details where status='delivered'"); echo mysql_num_rows($delivered); ?></div>
								<a href="delivery-orders.php"><div class="text-muted">Delivery Orders</div></a>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-teal panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
						<i style="font-size:3.5em;" class="fa fa-truck" aria-hidden="true"></i>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php $cancel=mysql_query("select *from orders_details where status='cancel'"); echo mysql_num_rows($cancel); ?></div>
							<a href="cancel-orders.php"><div class="text-muted">Cancel Orders</div></a>
						</div>
					</div>
				</div>
			</div>
			
			
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-red panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
						<i style="font-size:3.5em;" class="fa fa-truck" aria-hidden="true"></i>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php $recived=mysql_query("select *from orders_details where status='recived'"); echo mysql_num_rows($recived); ?></div>
							<a href="recived-orders.php"><div class="text-muted">Recived Orders</div></a>
						</div>
					</div>
				</div>
			</div>
			
			
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-blue panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
						
   
							<i style="font-size:3.5em;" class="fa fa-truck" aria-hidden="true"></i>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php $return_orders=mysql_query("select *from product_return"); echo mysql_num_rows($return_orders); ?></div>
							<a href="return-orders.php"><div class="text-muted">Return Orders</div></a>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-orange panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
						
   
							<span style="font-size:3.5em;" class="glyphicon glyphicon-map-marker"></span>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php $pincode_master=mysql_query("select *from pincode_master"); echo mysql_num_rows($pincode_master); ?></div>
								<a href="pin-code-master.php"><div class="text-muted">Delivery Location</div></a>
						</div>
					</div>
				</div>
			</div>-->
			
			
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-orange panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
						<!--	<span style="font-size:3.5em;" class="glyphicon glyphicon-user"></span> -->
							<span style="font-size:3.5em;" class="glyphicon glyphicon-envelope"></span>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php $Active=mysql_query("select *from subscribes"); echo mysql_num_rows($Active); ?></div>
								<a href="subscribe-users.php"><div class="text-muted">Subscribe Users</div></a>
						</div>
					</div>
				</div>
			</div>
			
			<!--<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-teal panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
						<i style="font-size:3.5em;" class="fa fa-globe" aria-hidden="true"></i>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php $notification_master=mysql_query("select *from notification_master"); echo mysql_num_rows($notification_master); ?></div>
							<a href="notification-details.php"><div class="text-muted">Notification</div></a>
						</div>
					</div>
				</div>
			</div>-->
                        <div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-orange panel-widget ">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<span style="font-size:3.5em;" class="fa fa-truck fa-4x"></span>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php $category=mysql_query("select *from product_enquiry where status='New Enquiry'"); echo mysql_num_rows($category); ?></div>
                                                        <a href="product_enquiry_inprogress.php"><div class="text-muted">New Enquiry Order</div></a>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-blue panel-widget ">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<span style="font-size:3.5em;" class="fa fa-truck fa-4x"></span>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php $category=mysql_query("select *from product_enquiry where status='Inprogress'"); echo mysql_num_rows($category); ?></div>
                                                        <a href="product_enquiry_inprogress.php"><div class="text-muted">Inprogress Enquiry Order</div></a>
						</div>
					</div>
				</div>
			</div>

                   <div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-teal panel-widget ">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<span style="font-size:3.5em;" class="fa fa-truck fa-4x"></span>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php $category=mysql_query("select *from product_enquiry where status='Completed'"); echo mysql_num_rows($category); ?></div>
                                                        <a href="product_enquiry_deliver.php"><div class="text-muted">Delivered Enquiry Order</div></a>
						</div>
					</div>
				</div>
			</div>
                   
                   
                   <div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-red panel-widget ">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<span style="font-size:3.5em;" class="fa fa-truck fa-4x"></span>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php $category=mysql_query("select *from product_enquiry  where status='Cancel'"); echo mysql_num_rows($category); ?></div>
                                                        <a href="product_enquiry_cancel.php"><div class="text-muted">Cancel Enquiry Order</div></a>
						</div>
					</div>
				</div>
			</div>
                        
			<!--<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-red panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
						<i style="font-size:3.5em;" class="fa fa-comment-o" aria-hidden="true"></i>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php $product_review=mysql_query("select *from product_review"); echo mysql_num_rows($product_review); ?></div>
							<a href="product-review.php"><div class="text-muted">Product Review</div></a>
						</div>
					</div>
				</div>
			</div>-->
		</div><!--/.row-->
<?php }elseif($_SESSION['ADMIN_TYPE']=='Employee'){   $userid=$_SESSION['ADMIN_ID'];  ?>
                
               <div class="row">
			<div class="col-xs-12 col-md-6 col-lg-4">
				<div class="panel panel-blue panel-widget ">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<span style="font-size:3.5em;" class="fa fa-truck fa-4x"></span>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php $category=mysql_query("select *from product_enquiry where employee_id='$userid' and status='Inprogress'"); echo mysql_num_rows($category); ?></div>
                                                        <a href="product_enquiry_inprogress.php"><div class="text-muted">Inprogress Enquiry Order</div></a>
						</div>
					</div>
				</div>
			</div>

                   <div class="col-xs-12 col-md-6 col-lg-4">
				<div class="panel panel-teal panel-widget ">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<span style="font-size:3.5em;" class="fa fa-truck fa-4x"></span>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php $category=mysql_query("select *from product_enquiry where employee_id='$userid' and status='Completed'"); echo mysql_num_rows($category); ?></div>
                                                        <a href="product_enquiry_deliver.php"><div class="text-muted">Delivered Enquiry Order</div></a>
						</div>
					</div>
				</div>
			</div>
                   
                   
                   <div class="col-xs-12 col-md-6 col-lg-4">
				<div class="panel panel-red panel-widget ">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<span style="font-size:3.5em;" class="fa fa-truck fa-4x"></span>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php $category=mysql_query("select *from product_enquiry where employee_id='$userid' and status='Cancel'"); echo mysql_num_rows($category); ?></div>
                                                        <a href="product_enquiry_cancel.php"><div class="text-muted">Cancel Enquiry Order</div></a>
						</div>
					</div>
				</div>
			</div>
		</div>
           <?php }elseif($_SESSION['ADMIN_TYPE']=='Vendors'){   $userid=$_SESSION['ADMIN_ID'];  ?>
                  
               <div class="row">
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-blue panel-widget ">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<span style="font-size:3.5em;" class="fa fa-truck fa-4x"></span>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php $category=mysql_query("select *from product_enquiry where assign_vendor_id='$userid' and status='Inprogress'"); echo mysql_num_rows($category); ?></div>
                                                        <a href="product_enquiry_inprogress.php"><div class="text-muted">Inprogress Enquiry Order</div></a>
						</div>
					</div>
				</div>
			</div>

                   <div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-teal panel-widget ">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<span style="font-size:3.5em;" class="fa fa-truck fa-4x"></span>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php $category=mysql_query("select *from product_enquiry where assign_vendor_id='$userid' and status='Completed'"); echo mysql_num_rows($category); ?></div>
                                                        <a href="product_enquiry_deliver.php"><div class="text-muted">Delivered Enquiry Order</div></a>
						</div>
					</div>
				</div>
			</div>
                   
                   
                   <div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-red panel-widget ">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<span style="font-size:3.5em;" class="fa fa-truck fa-4x"></span>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php $category=mysql_query("select *from product_enquiry where assign_vendor_id='$userid' and status='Cancel'"); echo mysql_num_rows($category); ?></div>
                                                        <a href="product_enquiry_cancel.php"><div class="text-muted">Cancel Enquiry Order</div></a>
						</div>
					</div>
				</div>
			</div>
                   
                     <div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-orange panel-widget ">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<span style="font-size:3.5em;" class="fa fa-info fa-4x"></span>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php $category=mysql_query("select *from product_details where vendor_id='$userid'"); echo mysql_num_rows($category); ?></div>
                                                        <a href="product.php"><div class="text-muted">Total Your Product</div></a>
						</div>
					</div>
				</div>
			</div>
		</div> 
           <?php } ?>
	</div>	<!--/.main-->
<?php include('footer.php'); ?>
<?php }else{  
   header('Location:login.php');
?>
<?php } ?>
