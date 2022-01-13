<?php include('header.php'); 
		if(isset($_SESSION['JOBPORTALADMIN'])) { ?>
		
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="index.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
				<li class="active">Category</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
                    
			<div class="col-lg-12">
				<h1 class="page-header">Category</h1>
				<?php if(isset($_SESSION['SUCESSMSG'])){ ?>
				<div class="alert bg-success" role="alert">
			<svg class="glyph stroked checkmark"><use xlink:href="#stroked-checkmark"></use></svg><?php echo $_SESSION['SUCESSMSG']; ?><a href="#" class="pull-right"><span class="glyphicon glyphicon-remove"></span></a>
				</div>
				<?php unset($_SESSION['SUCESSMSG']); } ?>
                                <?php if(isset($_SESSION['ErrorMsg'])){ ?>
				<div class="alert bg-danger" role="alert">
			<svg class="glyph stroked checkmark"><use xlink:href="#stroked-checkmark"></use></svg><?php echo $_SESSION['ErrorMsg']; ?><a href="#" class="pull-right"><span class="glyphicon glyphicon-remove"></span></a>
				</div>
				<?php unset($_SESSION['ErrorMsg']); } ?>
			</div>
		</div><!--/.row-->
				
		<?php if(isset($_GET['addcategory'])){ ?>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">Category Form</div>
					<div class="panel-body">
						<div  class="col-md-6">
							<form role="form" method="post" enctype="multipart/form-data" action="post.php?action=AddCategory">
							
								<div class="form-group">
									<label>Category Name</label>
									<input class="form-control" placeholder="Category Name" name="categoryname" onChange="categorynamer();" id="categoryname">
									<span id="categorynamer" style="color:red;"></span>
								</div>
                                                            <div class="form-group">
                                                                <label>Category Image</label> 
                                                                <input type="file" name="image" id="image" onchange="imager()">
                                                                <span id="imager" style="color:red;"><span>
                                                            </div>
															
							<div class="form-group"><input type="submit" class="btn btn-primary" onClick="return addcategory();" value="Add Category"></div>
                                                        </form>
							</div>
							
						
					</div>
				</div>
			</div><!-- /.col-->
		</div><!-- /.row -->
		<?php } ?>
		
		<?php if(isset($_GET['action'])){ 
		 $query=mysql_query("select category_name,status,date,cid,image from category where cid='".$_GET['catid']."'"); $category=mysql_fetch_array($query); ?>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">Category Form</div>
					<div class="panel-body">
						<div  class="col-md-6">
							<form role="form" method="post" enctype="multipart/form-data" action="post.php?action=UpdateCategory">
							
								<div class="form-group">
									<label>Category Name</label>
									<input class="form-control" placeholder="Category Name" name="categoryname" value="<?php echo $category['category_name']; ?>" onChange="categorynamer();" id="categoryname">
									<span id="categorynamer" style="color:red;"></span>
									<input type="hidden" value="<?php echo $_GET['catid']; ?>" id="cid" name="cid">
								</div>
									
                                                             <div class="form-group">
                                                                <label>Category Image</label> 
                                                                <input type="file" name="image" id="image" onchange="imager()">
                                                                <span id="imager" style="color:red;"><span>
                                                                <input type="hidden" value="<?php echo $category['image']; ?>" id="dimage" name="dimage">
                                                            </div>
							<div class="form-group"><input type="submit" class="btn btn-primary" onClick="return uaddcategory();" value="Add Category"></div>
							</div>
							
						</form>
					</div>
				</div>
			</div><!-- /.col-->
		</div><!-- /.row -->
		<?php } ?>
		<div class="row">
		<div class="col-lg-12">
		<div class="table-responsive">
		    <table class="table">
						    <thead>
						    <tr>
						        <th> #</th>
						        <th>Category Name</th>
                                                        <th>Category Image</th>
						        <th>Date</th>
								 <th>Status</th>
								 <th>Action  <a href="category.php?addcategory=addcategory" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Add</a></th>
						    </tr>
						   </thead>
						 <tbody>
					  <?php $query=mysql_query("select category_name,status,date,cid,image from category order by cid desc");
					    $i=1;
						while($row=mysql_fetch_array($query)){
					   ?>
                        <tr>
                          <td><?php echo $i++; ?></td>
                          <td><?php echo $row['category_name']; ?></td>
                          <td><?php if(!empty($row['image'])){ ?>
                          <img src="../images/category/<?php echo $row['image']; ?>" style="width:150px;height:100px;">
                          <?php }else{ ?>
                          <b style="color:red;">Not Upload Image</b>
                          <?php } ?>
                          </td>
                          <td><?php echo $row['date']; ?></td>
						   <td><?php if($row['status']=='active') { ?><a href="post.php?action=inactivecategory&inactive=<?php echo $row['cid']; ?>" onclick="return confirm('sure to This Update Status');"  class="btn btn-success" title="<?php echo $row['status']; ?>"><?php echo $row['status']; ?></a><?php }else { ?>
						   <a href="post.php?action=activecategory&active=<?php echo $row['cid']; ?>" onclick="return confirm('are you sure to This Update Status');"  class="btn btn-danger" title="<?php echo $row['status']; ?>"><?php echo $row['status']; ?></a><?php } ?>
						  </td>
                          <td><a href="category.php?action=updatecategory&catid=<?php echo $row['cid']; ?>"  class="btn btn-primary" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
						  <a href="post.php?action=deletecategory&delete=<?php echo $row['cid']; ?>" onclick="return confirm('are you sure to This Remove Records !'); " class="btn btn-danger" title="Delete" ><span class="glyphicon glyphicon-trash"></span></a></td>
                        </tr>
						<?php } ?>
                      </tbody>
						</table>
		</div></div>
		</div>
                <br><br><br>	
	</div>
	<?php include('footer.php'); ?>
	<script>
	function categorynamer(){if($('#categoryname').val()==''){}else{ $('#categorynamer').html(' '); }}
        function imager(){
            	  
			     var lblError = document.getElementById("imager");
				
				var myfile= document.getElementById('image').value;
				var ext = myfile.split('.').pop();
				if(ext=="png" || ext=="jpg" || ext=="jpeg" || ext=="gif"){
				// alert('Valid');
				lblError.innerHTML='';
				} else{
				lblError.innerHTML = "Please upload files having extensions: <b> only png,jpg,jpeg,gif</b>";
				document.getElementById("image").value='';
				return false;
				}
        }
 function addcategory(){
		var emailpattern = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/;
		
		var categoryname=document.getElementById('categoryname').value.trim();
                var image=$("#image").val();
				if(categoryname==''){
				$("#categorynamer").html('Please enter category Name');
				$("#categoryname").focus();
				return false;
				}
                  if(image==""){
                   $("#imager").html("Please upload category image");
                   return false;
                  }
		}
                
                 function uaddcategory(){
		var emailpattern = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/;
		
		var categoryname=document.getElementById('categoryname').value.trim();
                var image=$("#image").val();
				if(categoryname==''){
				$("#categorynamer").html('Please enter category Name');
				$("#categoryname").focus();
				return false;
		
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


