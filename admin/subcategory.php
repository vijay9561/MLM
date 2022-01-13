<?php include('header.php'); 
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
$query="select *from sub_category  where category_name LIKE '%".$searchid."%' order by scid desc LIMIT ".$pageLimit." , ".$setLimit;
$mysqluery=mysql_query($query);
}else{
$query="select *from sub_category order by scid desc LIMIT ".$pageLimit." , ".$setLimit;
$mysqluery=mysql_query($query);
}
		if(isset($_SESSION['JOBPORTALADMIN'])) { ?>
		
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
		
			<ol class="breadcrumb">
				<li><a href="index.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
				<li class="active">Sub Category</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Sub Category</h1>
				<?php if(isset($_SESSION['SUCESSMSG'])){ ?>
				<div class="alert bg-success" role="alert">
			<svg class="glyph stroked checkmark"><use xlink:href="#stroked-checkmark"></use></svg><?php echo $_SESSION['SUCESSMSG']; ?><a href="#" class="pull-right"><span class="glyphicon glyphicon-remove"></span></a>
				</div>
				<?php unset($_SESSION['SUCESSMSG']); } ?>
			</div>
		</div><!--/.row-->
				
		<?php if(isset($_GET['AddSubactegory'])){ ?>
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
		$query=mysql_query("select  *from sub_category where scid='".$_GET['catid']."'"); $category=mysql_fetch_array($query);
		 $maincategory=mysql_query("select *from category where cid='".$category['mid']."'"); $idddd=mysql_fetch_array($maincategory); ?>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">Category Form</div>
					<div class="panel-body">
						<div  class="col-md-6">
							<!--<form role="form" method="post" action="post.php?action=UpdateSubCategory"> -->
								<?php if((isset($_GET['page'])) && (isset($_GET['searchkeyowords']))) { ?>
                    <form  data-parsley-validate class="form-horizontal form-label-left"  action="post.php?action=UpdateSubCategory&searchkeyowords=<?php echo $_GET['searchkeyowords']; ?>&page=<?php echo $_GET['page'];?>" method="post"  enctype="multipart/form-data">
					<?php }elseif(isset($_GET['searchkeyowords'])) { ?>
		<form  data-parsley-validate class="form-horizontal form-label-left"  action="post.php?action=UpdateSubCategory&searchkeyowords=<?php echo $_GET['searchkeyowords']; ?>" method="post"  enctype="multipart/form-data">
					<?php }elseif(isset($_GET['page'])) { ?>
					<form  data-parsley-validate class="form-horizontal form-label-left"  action="post.php?action=UpdateSubCategory&page=<?php echo $_GET['page']; ?>" method="post"  enctype="multipart/form-data">
					<?php }else{ ?>
					<form  data-parsley-validate class="form-horizontal form-label-left"  action="post.php?action=UpdateSubCategory" method="post"  enctype="multipart/form-data">
					<?php } ?>
							<input type="hidden" value="<?php echo $_GET['catid']; ?>" id="hidecategoryid" name="hidecategoryid">
								<div class="form-group">
									<label>Category Name</label>
									   <select type="text" id="category_name" name="category_name" onchange="category_namer()" maxlength="50" required="required" class="form-control">
						 <option value="<?php echo  $idddd['cid']; ?>"><?php echo  $idddd['category_name']; ?></option> 
						 <?php $maincategories=mysql_query("select category_name,cid from category order by cid desc");
						 while($main=mysql_fetch_array($maincategories)){ if($maincategory['cid']!=$main['cid']){ ?>
						 <option value="<?php echo $main['cid']; ?>"><?php echo $main['category_name']; ?></option>
						 <?php } 
						 }?>
						  </select>
						 <span id="category_namer" style="color:red;"></span>
								</div>
								<div class="form-group">
								<label>Sub Category Name</label>
		<input type="text" id="sub_category_name" name="sub_category_name" value="<?php echo  $category['category_name']; ?>" onchange="sub_category_namer()" maxlength="50" required="required" class="form-control">
		  <span id="sub_category_namer" style="color:red;"></span>
								</div>
															
							<div class="form-group"><input type="submit" class="btn btn-primary" onClick="return addarticals();" value="Update Category"></div>
							</div>
							
						</form>
					</div>
				</div>
			</div><!-- /.col-->
		</div><!-- /.row -->
		<?php }else{ ?>
		<div class="row">
		<div class="col-lg-12">
		<div class="row">
		<div class="col-md-6">
		<a href="subcategory.php?AddSubactegory=AddSubactegory" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Add New</a>
		</div>
<div class="col-md-6">
<div class="form-group pull-right">
<form method="post">
<input type="text" id="search_id" placeholder="Search" value="<?php if(isset($_GET['searchkeyowords'])) { echo $_GET['searchkeyowords']; } ?>" required style="display: initial; width:auto;" title="Type Here" class="form-control">&nbsp;&nbsp;
<input type="submit" class="btn btn-primary" value="Search" onclick="return search_result()" />
</form>
</div>
</div>		
		</div>
		<div class="table-responsive">
		    <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Sr No</th>
                          <th>Category Name</th>
                          <th>Sub Category Name</th>
						  <th>Date</th>
                          <th>Status</th>
                          <th>Action </th>
                       
                        </tr>
                      </thead>

                      <tbody>
					  <?php //$query=mysql_query("select *from sub_category order by scid desc");
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
                          <td><?php echo $j++; ?></td>
						  <td><?php $maincategory=mysql_query("select *from category where cid='".$row['mid']."'"); $maincategory12=mysql_fetch_array($maincategory);
						  echo $maincategory12['category_name']; ?></td>
                          <td><?php echo $row['category_name']; ?></td>
                         
                          <td><?php echo $row['date']; ?></td>
						   <td>
						   
						 <?php /*?>  <?php if($row['status']=='active') { ?><a href="post.php?action=subcategoryinactive&inactive=<?php echo $row['scid']; ?>" onclick="return confirm('sure to This Update Status');"  class="btn btn-success" title="<?php echo $row['status']; ?>"><?php echo $row['status']; ?></a><?php }else { ?>
						   <a href="post.php?action=subcategoryactive&active=<?php echo $row['scid']; ?>" onclick="return confirm('are you sure to This Update Status');"  class="btn btn-danger" title="<?php echo $row['status']; ?>"><?php echo $row['status']; ?></a><?php } ?>
						   
						   <?php */?>
						    <?php if($row['status']=='active') { ?>
						     <?php if((isset($_GET['page'])) && (isset($_GET['searchkeyowords']))) { ?>
			 <a href="post.php?action=subcategoryinactive&inactive=<?php echo $row['scid']; ?>&page=<?php echo $_GET['page']; ?>&searchkeyowords=<?php echo $_GET['searchkeyowords']; ?>" onclick="return confirm('are you sure to this update status');"  class="btn btn-success" title="<?php echo $row['status']; ?>"><?php echo $row['status']; ?></a>
						   <?php }elseif(isset($_GET['searchkeyowords'])){ ?>
						     <a href="post.php?action=subcategoryinactive&inactive=<?php echo $row['scid']; ?>&searchkeyowords=<?php echo $_GET['searchkeyowords']; ?>" onclick="return confirm('are you sure to this update status');"  class="btn btn-success" title="<?php echo $row['status']; ?>"><?php echo $row['status']; ?></a>
							 <?php }elseif(isset($_GET['page'])){ ?>
							    <a href="post.php?action=subcategoryinactive&inactive=<?php echo $row['scid']; ?>&page=<?php echo $_GET['page']; ?>" onclick="return confirm('are you sure to this update status');"  class="btn btn-success" title="<?php echo $row['status']; ?>"><?php echo $row['status']; ?></a>
							 <?php }else{ ?>
							   <a href="post.php?action=subcategoryinactive&inactive=<?php echo $row['scid']; ?>" onclick="return confirm('are you sure to this update status');"  class="btn btn-success" title="<?php echo $row['status']; ?>"><?php echo $row['status']; ?></a> 
							 <?php } ?>	  
						   <?php }else { ?>
						    <?php if((isset($_GET['page'])) && (isset($_GET['searchkeyowords']))) { ?>
						   <a href="post.php?action=subcategoryactive&active=<?php echo $row['scid']; ?>&page=<?php echo $_GET['page']; ?>&searchkeyowords=<?php echo $_GET['searchkeyowords']; ?>" onclick="return confirm('are you sure to this update status');"  class="btn btn-success" title="<?php echo $row['status']; ?>"><?php echo $row['status']; ?></a>
						   <?php }elseif(isset($_GET['searchkeyowords'])){ ?>
						     <a href="post.php?action=subcategoryactive&active=<?php echo $row['scid']; ?>&searchkeyowords=<?php echo $_GET['searchkeyowords']; ?>" onclick="return confirm('are you sure to this update status');"  class="btn btn-success" title="<?php echo $row['status']; ?>"><?php echo $row['status']; ?></a>
							 <?php }elseif(isset($_GET['page'])){ ?>
							    <a href="post.php?action=subcategoryactive&active=<?php echo $row['scid']; ?>&page=<?php echo $_GET['page']; ?>" onclick="return confirm('are you sure to this update status');"  class="btn btn-success" title="<?php echo $row['status']; ?>"><?php echo $row['status']; ?></a>
							 <?php }else{ ?>
							   <a href="post.php?action=subcategoryactive&active=<?php echo $row['scid']; ?>" onclick="return confirm('are you sure to this update status');"  class="btn btn-success" title="<?php echo $row['status']; ?>"><?php echo $row['status']; ?></a> 
							 <?php } ?>	  
						  <?php } ?>
						  </td>
                          <td>
						  <?php if((isset($_GET['page'])) && (isset($_GET['searchkeyowords']))) { ?> 
						  <a href="subcategory.php?action=subcategory&catid=<?php echo $row['scid']; ?>&page=<?php echo $_GET['page']; ?>&searchkeyowords=<?php echo $_GET['searchkeyowords']; ?>"  class="btn btn-primary" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
						  <a href="post.php?action=subcategorydelete&delete=<?php echo $row['scid']; ?>&page=<?php echo $_GET['page']; ?>&searchkeyowords=<?php echo $_GET['searchkeyowords']; ?>" onclick="return confirm('are you sure to This Remove Records !'); " class="btn btn-danger" title="Delete" ><span class="glyphicon glyphicon-trash"></span></a>
						  
						 <?php }elseif(isset($_GET['searchkeyowords'])){ ?>
						  
						    <a href="subcategory.php?action=subcategory&catid=<?php echo $row['scid']; ?>&searchkeyowords=<?php echo $_GET['searchkeyowords']; ?>"  class="btn btn-primary" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
						  <a href="post.php?action=subcategorydelete&delete=<?php echo $row['scid']; ?>&searchkeyowords=<?php echo $_GET['searchkeyowords']; ?>" onclick="return confirm('are you sure to This Remove Records !'); " class="btn btn-danger" title="Delete" ><span class="glyphicon glyphicon-trash"></span></a>
						   <?php }elseif(isset($_GET['page'])){ ?>
						     <a href="subcategory.php?action=subcategory&catid=<?php echo $row['scid']; ?>&page=<?php echo $_GET['page']; ?>"  class="btn btn-primary" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
						  <a href="post.php?action=subcategorydelete&delete=<?php echo $row['scid']; ?>&page=<?php echo $_GET['page']; ?>" onclick="return confirm('are you sure to This Remove Records !'); " class="btn btn-danger" title="Delete" ><span class="glyphicon glyphicon-trash"></span></a>
						   <?php }else{ ?>
						    <a href="subcategory.php?action=subcategory&catid=<?php echo $row['scid']; ?>"  class="btn btn-primary" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
						  <a href="post.php?action=subcategorydelete&delete=<?php echo $row['scid']; ?>" onclick="return confirm('are you sure to This Remove Records !'); " class="btn btn-danger" title="Delete" ><span class="glyphicon glyphicon-trash"></span></a>
						   <?php } ?>
						  </td>
                        </tr>
						<?php $i++; } ?>
                      </tbody>
                    </table>
					<div class="row">
					<div class="col-md-12">
					<?php if(isset($_GET['searchkeyowords'])){ echo subcategoryseachings($setLimit,$page,$_GET['searchkeyowords']);
					 } else{  
					echo subcategory1234($setLimit,$page);  } ?>
					</div>
					</div>
					<br /><br /><br />
		</div></div>
		</div>
		<?php } ?>
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
window.location='subcategory.php?searchkeyowords='+search_id2;
return false;
}
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


