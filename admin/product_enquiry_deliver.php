 <?php include('header.php'); 
	include_once "function.php";
        $userid=$_SESSION['ADMIN_ID'];
 mysql_query("delete from image where amid='$userid'");
 if(isset($_GET["page"]))
	$page = (int)$_GET["page"];
	else
	$page = 1;

	$setLimit = 100;
	$pageLimit = ($page * $setLimit) - $setLimit;
$query='';
if($_SESSION['ADMIN_TYPE']=='Admin'){
if(isset($_GET['searchkeyowords'])){
$searchid=$_GET['searchkeyowords'];
$query="select e.delivery_date,e.product_id,e.order_assign_seller_by,e.vendor_id,e.assign_vendor_id,e.employee_id,e.product_id,e.id,e.status,e.created_date,r.name as c_name,r.mobile as c_mobile,v.name as v_name,v.mobile as v_mobile,v.vendor_store_name,p.title,p.discount_price from product_enquiry e inner join registration r on r.rid=e.userid inner join registration v on v.rid=e.vendor_id inner join product_details p on e.product_id=p.pid  where (p.title LIKE '%".$searchid."%' OR v.name LIKE '%".$searchid."%' OR v.mobile LIKE '%".$searchid."%' OR r.name LIKE '%".$searchid."%' OR r.mobile LIKE '%".$searchid."%') and e.status='Completed' order by e.id desc LIMIT ".$pageLimit." , ".$setLimit;

$mysqluery=mysql_query($query);
}else{
$query="select e.delivery_date,e.product_id,e.order_assign_seller_by,e.vendor_id,e.assign_vendor_id,e.employee_id,e.product_id,e.id,e.status,e.created_date,r.name as c_name,r.mobile as c_mobile,v.name as v_name,v.mobile as v_mobile,v.vendor_store_name,p.title,p.discount_price from product_enquiry e inner join registration r on r.rid=e.userid inner join registration v on v.rid=e.vendor_id inner join product_details p on e.product_id=p.pid where e.status='Completed' order by e.id desc LIMIT ".$pageLimit." , ".$setLimit;
$mysqluery=mysql_query($query);
}
}elseif($_SESSION['ADMIN_TYPE']=='Employee'){
  if(isset($_GET['searchkeyowords'])){
$searchid=$_GET['searchkeyowords'];
$query="select e.delivery_date,e.product_id,e.order_assign_seller_by,e.vendor_id,e.assign_vendor_id,e.employee_id,e.product_id,e.id,e.status,e.created_date,r.name as c_name,r.mobile as c_mobile,v.name as v_name,v.mobile as v_mobile,v.vendor_store_name,p.title,p.discount_price from product_enquiry e inner join registration r on r.rid=e.userid inner join registration v on v.rid=e.vendor_id inner join product_details p on e.product_id=p.pid  where (p.title LIKE '%".$searchid."%' OR v.name LIKE '%".$searchid."%' OR v.mobile LIKE '%".$searchid."%' OR r.name LIKE '%".$searchid."%' OR r.mobile LIKE '%".$searchid."%') and e.status='Completed' and e.employee_id='$userid' order by e.id desc LIMIT ".$pageLimit." , ".$setLimit;

$mysqluery=mysql_query($query);
}else{
$query="select e.delivery_date,e.product_id,e.order_assign_seller_by,e.vendor_id,e.assign_vendor_id,e.employee_id,e.product_id,e.id,e.status,e.created_date,r.name as c_name,r.mobile as c_mobile,v.name as v_name,v.mobile as v_mobile,v.vendor_store_name,p.title,p.discount_price from product_enquiry e inner join registration r on r.rid=e.userid inner join registration v on v.rid=e.vendor_id inner join product_details p on e.product_id=p.pid where e.status='Completed' and e.employee_id='$userid' order by e.id desc LIMIT ".$pageLimit." , ".$setLimit;
$mysqluery=mysql_query($query);
}  
}elseif($_SESSION['ADMIN_TYPE']=='Vendors'){ 
 if(isset($_GET['searchkeyowords'])){
$searchid=$_GET['searchkeyowords'];
$query="select e.delivery_date,e.product_id,e.order_assign_seller_by,e.vendor_id,e.assign_vendor_id,e.employee_id,e.product_id,e.id,e.status,e.created_date,r.name as c_name,r.mobile as c_mobile,v.name as v_name,v.mobile as v_mobile,v.vendor_store_name,p.title,p.discount_price from product_enquiry e inner join registration r on r.rid=e.userid inner join registration v on v.rid=e.vendor_id inner join product_details p on e.product_id=p.pid  where (p.title LIKE '%".$searchid."%' OR v.name LIKE '%".$searchid."%' OR v.mobile LIKE '%".$searchid."%' OR r.name LIKE '%".$searchid."%' OR r.mobile LIKE '%".$searchid."%') and e.status='Completed' and e.assign_vendor_id='$userid' order by e.id desc LIMIT ".$pageLimit." , ".$setLimit;

$mysqluery=mysql_query($query);
}else{
$query="select e.delivery_date,e.product_id,e.order_assign_seller_by,e.vendor_id,e.assign_vendor_id,e.employee_id,e.product_id,e.id,e.status,e.created_date,r.name as c_name,r.mobile as c_mobile,v.name as v_name,v.mobile as v_mobile,v.vendor_store_name,p.title,p.discount_price from product_enquiry e inner join registration r on r.rid=e.userid inner join registration v on v.rid=e.vendor_id inner join product_details p on e.product_id=p.pid where e.status='Completed' and e.assign_vendor_id='$userid' order by e.id desc LIMIT ".$pageLimit." , ".$setLimit;
$mysqluery=mysql_query($query);
}         
}
if(isset($_SESSION['JOBPORTALADMIN'])) { ?>
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
		<div id="loading" style="display:none;">
        <img id="loading-image" src="images/show_loader.gif" alt="Loading..." />
              </div>

			<ol class="breadcrumb">
				<li><a href="index.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
				<li class="active">Product Enquiry Delivered Order</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				
				<?php if(isset($_SESSION['SUCESSMSG'])){ ?>
				<div class="alert bg-success" role="alert">
			<svg class="glyph stroked checkmark"><use xlink:href="#stroked-checkmark"></use></svg><?php echo $_SESSION['SUCESSMSG']; ?><a href="#" class="pull-right"><span class="glyphicon glyphicon-remove"></span></a>
				</div>
				<?php unset($_SESSION['SUCESSMSG']); } ?>
                                
                                <?php if(isset($_SESSION['ERRORMSG'])){ ?>
				<div class="alert bg-danger" role="alert">
			<svg class="glyph stroked checkmark"><use xlink:href="#stroked-checkmark"></use></svg><?php echo $_SESSION['ERRORMSG']; ?><a href="#" class="pull-right"><span class="glyphicon glyphicon-remove"></span></a>
				</div>
				<?php unset($_SESSION['ERRORMSG']); } ?>
			</div>
		</div><!--/.row-->
		<div class="row">
			
                    
		<?php if(isset($_GET['opendata'])){ ?><!-- /.row -->
		<?php
		  $product=mysql_query("select *from product_enquiry where id='".$_GET['opendata']."'"); $en=mysql_fetch_array($product);
                      $product_id=$en['product_id'];
                      $userid=$en['userid'];
                      $vendor_id=$en['vendor_id'];
                      $customer_name=mysql_query("select *from registration where rid='".$userid."'"); $re=mysql_fetch_array($customer_name);
                      $vendors_d=mysql_query("select *from registration where rid='".$vendor_id."'"); $de=mysql_fetch_array($vendors_d);
                    ?><!-- /.row -->
		
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">View Product Enquiry &nbsp;&nbsp;&nbsp;
					 
					<a href="product_enquiry_deliver.php" class="btn btn-primary">Back</a>
					
					</div>
					<div class="panel-body">
					<?php $query=mysql_query("select *from product_details where pid='".$product_id."'"); $row=mysql_fetch_array($query); ?>
					<table class="table table-striped table-bordered">
					<tbody>
                                        <tr><th>Customer Name:</th><td><?php echo $re['name'] ?></td></tr>
                                        <tr><th>Customer Mobile No:</th><td><?php echo $re['mobile'] ?></td></tr>
                                        <tr><th>Customer Expectation:</th><td><?php echo $en['customer_needs'] ?></td></tr>
                                        <tr><th>Seller Name:</th><td><?php echo $de['name'] ?></td></tr>
                                        <tr><th>Seller Mobile No:</th><td><?php echo $de['mobile'] ?></td></tr>
                                        
					<tr><th>Product Name:</th><td><?php echo $row['title'] ?></td></tr>
					<tr><th>Product Category</th>
					<td><?php $maincategory=mysql_query("select *from category where cid='".$row['category_id']."'"); $maincategory12=mysql_fetch_array($maincategory);
						  echo $maincategory12['category_name']; ?>
                         <?php $syb=mysql_query("select *from sub_category where scid='".$row['sub_category_id']."'"); $subcategory=mysql_fetch_array($syb); 
						   if(!empty($subcategory['category_name'])){ echo '>>'.$subcategory['category_name']; } ?>
						   <?php $subsub=mysql_query("select *from sub_sub_category where sscid='".$row['sub_sub_category_id']."'"); $subsub1=mysql_fetch_array($subsub); 
						   if(!empty($subsub1['category_name'])){ echo '>>'.$subsub1['category_name']; } ?>
						   </td>
						  
					</tr>
					<!--<tr><th>Product Price</th><td><?php echo $row['price']; ?></td></tr>-->
                                      <tr><th>Product Quantity:</th><td><?php echo $en['quantity'] ?></td></tr> 
                                        <tr><th>Per Quantity Price</th><td><i class="fa fa-inr"></i> <?php echo number_format($row['discount_price'],2,'.',''); ?></td></tr>
                                        <tr><th>Total Price</th><td><i class="fa fa-inr"></i> <?php echo number_format($row['discount_price'],2,'.',''); ?> <i class="fa fa-times"></i> <?php echo $en['quantity']; ?> <b>=</b> <?php echo number_format($row['discount_price']*$en['quantity'],2,'.',''); ?> </td></tr> 
                
					<tr><th>Product Images</th><td>  <?php $mobilegloballianz=mysql_query("select *from product_images where pid=".$row['pid'].""); while($myimages=mysql_fetch_array($mobilegloballianz)){ 
					if(!empty( $myimages['product_path'])) {?>
						  <img src="images/product/<?php echo $myimages['product_path']; ?>" class="img-thumbnail" style="height:100px; width:150px;" />&nbsp;
						  <?php }  }?></td></tr>
					<tr><th>Product Description</th><td><?php echo $row['description']; ?></td></tr>
					<?php if(!empty($row['product_additional_description'])) { ?>
					<tr><th>Product Additional Information</th><td><?php echo $row['product_additional_description']; ?></td></tr>
					<?php } ?>
					<!--<tr><th>Product Date</th><td><?php echo $row['date']; ?></td></tr>-->
                                        <tr><th>Product Added By</th><td><?php echo $row['added_by']; ?></td></tr>
					</tbody>
					</table>
			
					</div>
				</div>
			</div><!-- /.col-->
		</div>
		
		<?php }else{ ?>
		<div class="row">
		<div class="col-lg-12">
		<form name="bulk_action_form" action="#" method="post">
		 <div class="row">
		<div class="col-md-6">
                       
		</div>
<div class="col-md-6">
<div class="form-group pull-right">
<input type="text" id="search_id" placeholder="Search" value="<?php if(isset($_GET['searchkeyowords'])) { echo $_GET['searchkeyowords']; } ?>"  style="display: initial; width:auto;" title="Type Here" class="form-control">&nbsp;&nbsp;
<a href="#" class="btn btn-primary" value="Search" onclick="return search_result()">Search</a>

</div>
</div>		
		</div>
		<div class="table-responsive">
		    <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Sr No</th>
                          <th>Product Name</th>
                          <th>Product Price</th>
                          <th>Customer Name</th>
                          <th>Customer Mobile No</th>
                       <?php if($_SESSION['ADMIN_TYPE']=='Admin' || $_SESSION['ADMIN_TYPE']=='Employee'){ ?>
                          <th>Seller Name</th>
                          <th>Seller Mobile</th> 
                          <th>Seller Store Name</th>
                          <th>Assign Employee Name</th>
                          <th>Assign Seller By</th>
                       <?php } ?>
                           <th>Enquiry Date</th>
       
                          <th style="width:15%;">Action </th>
                       
                        </tr>
                      </thead>

                      <tbody>
					  <?php  //$query=mysql_query("select *from product_details order by pid desc");
					//  echo "select *from sub_sub_category order by sscid desc";
					  $serial = ($pageLimit * $setLimit) + 1;
									//  $sn = ($pageLimit * $limit) + 1;
									  $sn = ($page * $setLimit) + 1;
									  $page_num   =   (int) (!isset($_GET['page']) ? 1 : $_GET['page']);
                                      $start_num =((($page_num*$setLimit)-$setLimit)+1);
									   $i=1;
									   $j= (($page-1) * $setLimit) + $i; 
									   while($row=mysql_fetch_array($mysqluery)){  
								//$slNo = $i+$start_num;
								    
							       $num = $sn ++;

					   ?>
                          <tr>
                            <td><span><?php echo $j++; ?> &nbsp;<!--<input type="checkbox" style="display: inherit;margin: -17px 33px 0px;" name="checked_id[]" class="checkbox" onclick="check_checkboxes()" value="<?php echo $row['id']; ?>"/></span>-->
</td>
                            <td><?php echo $row['title']; ?></td>   
                            <td><i class="fa fa-inr"></i> <?php echo $row['discount_price']; ?></td>
                            <td><?php echo $row['c_name']; ?></td>
                            <td><?php echo $row['c_mobile']; ?></td>
                            <?php if($_SESSION['ADMIN_TYPE']=='Admin' || $_SESSION['ADMIN_TYPE']=='Employee'){ ?>
                            <td><?php echo $row['v_name']; ?></td>
                            <td><?php echo $row['v_mobile']; ?></td>
                            <td><?php echo $row['vendor_store_name']; ?></td>
                            <?php $assign_employee=mysql_query("select name from registration where rid='".$row['employee_id']."'"); 
                               $emp=mysql_fetch_array($assign_employee); ?>
                            <td><?php echo $emp['name']; ?></td>
                            <td><?php if(!empty($row['assign_vendor_id'])){ ?>
                                  <?php echo $row['order_assign_seller_by']; ?>
                            <?php }else{ ?>
                                 <b>Not Assign</b> 
                            <?php } ?>
                            </td>
                            <?php } ?>
                            <td><?php echo date('d-m-Y', strtotime($row['delivery_date'])); ?></td>
                            <td>
                           <div class="dropdown"> 
                               <a href="product_enquiry_deliver.php?opendata=<?php echo $row['id']; ?>" title="View Order" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> </a>
                            </td>  
                          </tr> 
                       <?php $i++; } ?>
                      </tbody>
                    </table>
		</form>
					<?php
                                     $searchid='';
                                    if($_SESSION['ADMIN_TYPE']=='Admin'){
                                       if(isset($_GET['searchkeyowords'])){
                                           $searchid=$_GET['searchkeyowords'];
                                         $query="SELECT COUNT(*) as totalCount,e.product_id,e.id,e.status,e.created_date,r.name as c_name,r.mobile as c_mobile,v.name as v_name,v.mobile as v_mobile,v.vendor_store_name,p.title,p.discount_price from product_enquiry e inner join registration r on r.rid=e.userid inner join registration v on v.rid=e.vendor_id inner join product_details p on e.product_id=p.pid  where (p.title LIKE '%".$searchid."%' OR v.name LIKE '%".$searchid."%' OR v.mobile LIKE '%".$searchid."%' OR r.name LIKE '%".$searchid."%' OR r.mobile LIKE '%".$searchid."%') and e.status='Completed'";  
                                         echo standard_paging_function($setLimit,$page,$query,$searchid); } else{  
                                           $query="SELECT COUNT(*) as totalCount,e.product_id,e.id,e.status,e.created_date,r.name as c_name,r.mobile as c_mobile,v.name as v_name,v.mobile as v_mobile,v.vendor_store_name,p.title,p.discount_price from product_enquiry e inner join registration r on r.rid=e.userid inner join registration v on v.rid=e.vendor_id inner join product_details p on e.product_id=p.pid where e.employee_id='Completed' order by e.id desc";
                                         echo standard_paging_function($setLimit,$page,$query,$searchid);  }  
                                    }elseif($_SESSION['ADMIN_TYPE']=='Employee'){
                                      if(isset($_GET['searchkeyowords'])){
                                           $searchid=$_GET['searchkeyowords'];
                                         $query="SELECT COUNT(*) as totalCount,e.product_id,e.id,e.status,e.created_date,r.name as c_name,r.mobile as c_mobile,v.name as v_name,v.mobile as v_mobile,v.vendor_store_name,p.title,p.discount_price from product_enquiry e inner join registration r on r.rid=e.userid inner join registration v on v.rid=e.vendor_id inner join product_details p on e.product_id=p.pid  where (p.title LIKE '%".$searchid."%' OR v.name LIKE '%".$searchid."%' OR v.mobile LIKE '%".$searchid."%' OR r.name LIKE '%".$searchid."%' OR r.mobile LIKE '%".$searchid."%') and e.status='Completed' and e.employee_id='$userid'";  
                                         echo standard_paging_function($setLimit,$page,$query,$searchid); } else{  
                                           $query="SELECT COUNT(*) as totalCount,e.product_id,e.id,e.status,e.created_date,r.name as c_name,r.mobile as c_mobile,v.name as v_name,v.mobile as v_mobile,v.vendor_store_name,p.title,p.discount_price from product_enquiry e inner join registration r on r.rid=e.userid inner join registration v on v.rid=e.vendor_id inner join product_details p on e.product_id=p.pid where  e.status='Completed' and e.employee_id='$userid' order by e.id desc";
                                         echo standard_paging_function($setLimit,$page,$query,$searchid);  }    
                                       }elseif($_SESSION['ADMIN_TYPE']=='Vendors'){
                                        if(isset($_GET['searchkeyowords'])){
                                           $searchid=$_GET['searchkeyowords'];
                                         $query="SELECT COUNT(*) as totalCount,e.product_id,e.id,e.status,e.created_date,r.name as c_name,r.mobile as c_mobile,v.name as v_name,v.mobile as v_mobile,v.vendor_store_name,p.title,p.discount_price from product_enquiry e inner join registration r on r.rid=e.userid inner join registration v on v.rid=e.vendor_id inner join product_details p on e.product_id=p.pid  where (p.title LIKE '%".$searchid."%' OR v.name LIKE '%".$searchid."%' OR v.mobile LIKE '%".$searchid."%' OR r.name LIKE '%".$searchid."%' OR r.mobile LIKE '%".$searchid."%') and e.status='Completed' and e.assign_vendor_id='$userid'";  
                                         echo standard_paging_function($setLimit,$page,$query,$searchid); } else{  
                                           $query="SELECT COUNT(*) as totalCount,e.product_id,e.id,e.status,e.created_date,r.name as c_name,r.mobile as c_mobile,v.name as v_name,v.mobile as v_mobile,v.vendor_store_name,p.title,p.discount_price from product_enquiry e inner join registration r on r.rid=e.userid inner join registration v on v.rid=e.vendor_id inner join product_details p on e.product_id=p.pid where  e.status='Completed' and e.assign_vendor_id='$userid' order by e.id desc";
                                         echo standard_paging_function($setLimit,$page,$query,$searchid);  }  
                                    }
                                   ?>
		</div></div>
		</div>
		<?php } ?>
                  <br><br><br>
                    <br><br><br>
	</div>
              
	<?php include('footer.php'); ?>
	<script>
	function search_result(){
var search_id=$("#search_id").val();
var search_id2=search_id.trim();
if(search_id==""){
 alert("Please enter keywords");
 return false;
}
window.location='product_enquiry_deliver.php?searchkeyowords='+search_id2;
return false;
}
	function category_namer(){if($('#category_name').val()==""){  }else{
	///var category_name=document.getElementById('category_name').value;
		 var category=document.getElementById('category_name').value.trim();
                       $.ajax({
					  	url: "post.php?action=getsubcategory1234",
						type: 'POST',
						data: {category:category},
						success: function(data) {
						if(data==4){
						$("#SubcategoryName").hide();
						}else{
						$("#SubcategoryName").fadeOut().html(data).fadeIn('slow');
						}
						}
						});
	
 $('#category_namer').html(' ') }}
 function sub_category_namer(){if($('#sub_category_name').val()==""){  }else{
 
  var category=document.getElementById('sub_category_name').value.trim();
                       $.ajax({
					  	url: "post.php?action=Subcategorylist",
						type: 'POST',
						data: {category:category},
						success: function(data) {
						if(data==4){
						$("#GetSubSubCategory").hide();
						}else{
						$("#GetSubSubCategory").fadeOut().html(data).fadeIn('slow');
						}
					}
						});
 
 $('#sub_category_namer').html(' ')
 }}

		$(document).ready(function(){
		$('.pull-right').click(function(){
		$('.bg-success').hide();
		});
		});
</script>
	
<?php }else{  
   header('Location:../login.php');
?>
<?php } ?>


