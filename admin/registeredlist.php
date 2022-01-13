<?php include('header.php'); 
		if(isset($_SESSION['JOBPORTALADMIN'])) { ?>
		
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
		
			<ol class="breadcrumb">
				<li><a href="index.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
				<li class="active">Registered List</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Registered List</h1>
				<?php if(isset($_SESSION['SUCESSMSG'])){ ?>
				<div class="alert bg-success" role="alert">
			<svg class="glyph stroked checkmark"><use xlink:href="#stroked-checkmark"></use></svg><?php echo $_SESSION['SUCESSMSG']; ?><a href="#" class="pull-right"><span class="glyphicon glyphicon-remove"></span></a>
				</div>
				<?php unset($_SESSION['SUCESSMSG']); } ?>
			</div>
		</div><!--/.row-->
				
		<?php if(isset($_GET['viecategory'])){ ?>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">Sub Category Form</div>
					<div class="panel-body">
						<div  class="col-md-6">
							<form role="form" method="post" action="post.php?action=InsertSubCategory">
							
								<div class="form-group">
									<label>Category Name</label>
									 <select type="text" id="category_name" name="category_name" onchange="category_namer()" maxlength="50" required="required" class="form-control">
						 <option value="">Select Category</option> 
						 <?php $maincategories=mysql_query("select category_name,cid from category order by cid desc");
						 while($main=mysql_fetch_array($maincategories)){ ?>
						 <option value="<?php echo $main['cid']; ?>"><?php echo $main['category_name']; ?></option>
						 <?php } ?>
						  </select>
						  <span id="category_namer" style="color:red;"></span>
								</div>
								
								<div class="form-group">
									<label>Sub Category Name</label>
						 <input type="text" id="sub_category_name" name="sub_category_name" onchange="sub_category_namer()" maxlength="50" required="required" class="form-control">
						  <span id="sub_category_namer" style="color:red;"></span>
								</div>
															
							<div class="form-group"><input type="submit" class="btn btn-primary" onClick="return addarticals();" value="Add Category"></div>
							</div>
							
						</form>
					</div>
				</div>
			</div><!-- /.col-->
		</div><!-- /.row -->
		
		
		<?php }elseif(isset($_GET['action'])){ 
		$query=mysql_query("select  *from registration where id='".$_GET['catid']."'"); $category=mysql_fetch_array($query);?>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">View List</div>
					<div class="panel-body">
						<div  class="col-md-6">
						<table class="table">
						<tbody><tr><th>Adhar No</th><td><?php echo $category['adhar_no'];  ?></td></tr></tbody>
						<tbody><tr><th>Name Of Applicant</th><td><?php echo $category['name_of_applicant'];  ?></td></tr></tbody>
						<tbody><tr><th>Social Category</th><td><?php echo $category['social_category'];  ?></td></tr></tbody>
						<tbody><tr><th>Name Of Business</th><td><?php echo $category['name_of_business'];  ?></td></tr></tbody>
						<tbody><tr><th>Type Of Oragnisation</th><td><?php echo $category['type_of_organisation'];  ?></td></tr></tbody>
						<tbody><tr><th>State</th><td><?php echo $category['state'];  ?></td></tr></tbody>
						<tbody><tr><th>District</th><td><?php echo $category['district'];  ?></td></tr></tbody>
						<tbody><tr><th>Address</th><td><?php echo $category['address'];  ?></td></tr></tbody>
						<tbody><tr><th>Pincode</th><td><?php echo $category['pincode'];  ?></td></tr></tbody>
						<tbody><tr><th>Mobile Number</th><td><?php echo $category['mobile'];  ?></td></tr></tbody>
						<tbody><tr><th>Email Address</th><td><?php echo $category['email'];  ?></td></tr></tbody>
						</table>
					</div>
					<div  class="col-md-6">
						<table class="table">
						<tbody><tr><th>Business Commencement Date</th><td><?php echo $category['date_od_comm_business'];  ?></td></tr></tbody>
						<tbody><tr><th>Bank Account Numbers</th><td><?php echo $category['bank_account_no'];  ?></td></tr></tbody>
						<tbody><tr><th>IFSC Code</th><td><?php echo $category['ifsc_code'];  ?></td></tr></tbody> <?php $cateid=mysql_query("select *from category where cid='".$category['business_category']."'");
						$cat=mysql_fetch_array($cateid); ?>
						<tbody><tr><th>Nature Of Business Category</th><td><?php echo $cat['category_name'];  ?> 
						<?php $Category=mysql_query("select *from sub_category where scid='".$category['sub_category']."'");
						$sub=mysql_fetch_array($Category); ?></td></tr></tbody>
						<tbody><tr><th>Sub Category</th><td><?php echo $sub['category_name'];  ?></td></tr></tbody>
						<tbody><tr><th>Additional Details About Business</th><td><?php echo $category['additional_details'];  ?></td></tr></tbody>
						<tbody><tr><th>No. Of Employees</th><td><?php echo $category['no_of_employee'];  ?></td></tr></tbody>
						<tbody><tr><th>Investment In Plan & Machinery (Ammount In Lac)</th><td><?php echo $category['instent_in_plan'];  ?></td></tr></tbody>
						<tbody><tr><th>Date</th><td><?php echo $category['date'];  ?></td></tr></tbody>
						<tbody><tr><th>Status</th><td><?php echo $category['status'];  ?></td></tr></tbody>
						</table>
					</div>
				</div>
			</div><!-- /.col-->
		</div><!-- /.row -->
		<?php }else{ ?>
		<div class="row">
		<div class="col-lg-12">
		<div class="table-responsive">
		<?php $query=mysql_query("select *from registration order by id desc"); if(mysql_num_rows($query)>=1){ ?>
		    <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Sr No</th>
                          <th>Mobile Name</th>
                          <th>Email ID</th>
						  <th>Date</th>
                          <th>Status</th>
                          <th>Action</th>
                       
                        </tr>
                      </thead>

                      <tbody>
					  <?php 
					    $i=1;
						while($row=mysql_fetch_array($query)){
					   ?>
                        <tr>
                          <td><?php echo $i++; ?></td>
						  <td><?php
						  echo $row['mobile']; ?></td>
                          <td><?php echo $row['email']; ?></td>
                         
                          <td><?php echo $row['date']; ?></td>
						   <td><?php if($row['status']=='active') { ?><a href="post.php?action=registeredinactive&inactive=<?php echo $row['id']; ?>" onclick="return confirm('sure to This Update Status');"  class="btn btn-success" title="<?php echo $row['status']; ?>"><?php echo $row['status']; ?></a><?php }else { ?>
						   <a href="post.php?action=registeredactive&active=<?php echo $row['id']; ?>" onclick="return confirm('are you sure to This Update Status');"  class="btn btn-danger" title="<?php echo $row['status']; ?>"><?php echo $row['status']; ?></a><?php } ?>
						  </td>
                          <td><a href="registeredlist.php?action=viecategory&catid=<?php echo $row['id']; ?>"  class="btn btn-primary" title="Edit"><span class="glyphicon glyphicon-eye-open"></span></a>&nbsp;&nbsp;
						  <a href="post.php?action=registereddelete&delete=<?php echo $row['id']; ?>" onclick="return confirm('are you sure to This Remove Records !'); " class="btn btn-danger" title="Delete" ><span class="glyphicon glyphicon-trash"></span></a></td>
                        </tr>
						<?php } ?>
                      </tbody>
                    </table>
					<?php }else{ ?>
					<div class="alert bg-danger" role="alert">
					<svg class="glyph stroked cancel"><use xlink:href="#stroked-cancel"></use></svg>Not Found
				</div>
					<?php } ?>
		</div></div>
		</div>
		<?php } ?>
	</div>
	<?php include('footer.php'); ?>
	<script>
	function category_namer(){if($('#category_name').val()==""){  }else{
 $('#category_namer').html(' ') }}
 function sub_category_namer(){if($('#sub_category_name').val()==""){  }else{
 $('#sub_category_namer').html(' ')
 }}
function addarticals(){
              var category_name=document.getElementById('category_name').value.trim();
			  var sub_category_name=document.getElementById('sub_category_name').value.trim();
			  if(category_name==''){
			     $('#category_namer').html("Please select category name");
				 $('#category_name').focus();
				 return false;
			  }
			   if(sub_category_name==''){
			     $('#sub_category_namer').html("Please enter sub category");
				 $('#sub_category_name').focus();
				 return false;
			  }
	}
		$(document).ready(function(){
		$('.pull-right').click(function(){
		$('.bg-success').hide();
		});
		});
</script>
	
<?php }else{  
   header('Location:login.php');
?>
<?php } ?>


