<?php include('header.php'); 
		if(isset($_SESSION['JOBPORTALADMIN'])) { ?>
		
		<style>
.fileUpload {
	position: relative;
	overflow: hidden;
	margin: 10px;
	background-color:#D7D7D7;
	border: 1px solid red;
	height: 100px;
	width: 100px;
	text-align: center;
	border-width: 1px;
border-style: dotted;
border-color: #9b0d08;
}
.fileUpload input.upload{
	position: absolute;
	top: 0;
	right: 0;
	margin: 0;
	padding: 0;
	font-size: 20px;
	cursor: pointer;
	opacity: 0;
	filter: alpha(opacity=0);
	height: 100%;
	text-align: center;
}
.custom-span{ font-family: Arial;
font-size: 29px;
color: #FBF4F3;
background-color: #0a7fb6;
border-radius: 50%;
padding-left: 10px;
padding-right: 10px;
position: absolute;
left: 27px;
top: 27px;
bottom: 31px;}
#uploadFile{border: none;margin-left: 10px; width: 200px;}
.custom-para{font-family: arial;font-weight: bold;font-size: 24px; color:#585858;}
.loaderimages{
position:absolute;
z-index: 9999;
height: 200px;
width: 200px;
}
 #loading {
  width: 100%;
  height: 100%;
  top: 0px;
  left: 0px;
  position: fixed;
  display: block;
  opacity: 0.7;
  z-index: 99;
  text-align: center;
}

#loading-image {
  position: absolute;
  top: 20%;
  left: 50%;
  z-index: 100;
}
.img-wrap {
    font-size: 0;

}
.img-wrap .close {
 position: absolute;
top: 0px;
right: -22px;
z-index: 9999;
background-color: #16A823;
padding: 3px 3px 6px;
color: #030303;
font-weight: bold;
cursor: pointer;
opacity: .6;
text-align: center;
font-size: 25px;
line-height: 10px;
border-radius: 50%;
}
.img-wrap:hover .close {
    opacity: 2;
}
</style>
<script>

 function insertempdata(){
			  
			     var lblError = document.getElementById("uploadfileoner");
				
				var myfile= document.getElementById('uploadfileone').value;
				var ext = myfile.split('.').pop();
				if(ext=="png" || ext=="jpg" || ext=="jpeg" || ext=="gif"){
				// alert('Valid');
				lblError.innerHTML='';
				} else{
				lblError.innerHTML = "Please upload files having extensions: <b> only png,jpg,jpeg,gif</b> only.";
				document.getElementById("temponefilesss").value='';
				return false;
				}
				$("#loading").show(); 
	       var formData = new FormData($("#temponefilesss")[0]);
			 //alert(formData); return false;
			$.ajax({   
				url: "post.php?action=InserServicesTempImages",
				data : formData,
				processData: false,
				contentType: false,
				type: 'POST',
				success: function(data){
					if(data==1){
					     $.ajax({
					  	url: "post.php?action=ViewServiesTempImages",
						type: 'POST',
						data: {},
						success: function(data) {
						$("#getimagessss").fadeOut().html(data).fadeIn('slow');
						$("#loading").fadeOut("slow");
						}
						});
						return false;
				     	}else {
                        alert('uploaded images limit only 5 images upload at time')
						$("#loading").fadeOut("slow");
					 return false;
					}
					
				}
			});
			return false;  
		
        }
	function temimagesdelete(id) {
        var r=confirm('Are you sure you want to delete this image?');
		if(r==true)
		{
		$("#loading").show(); 
        $.ajax({
            url: "post.php?action=DeleteImages12",
            type: 'POST',
            data: {id: id},
            success: function(data) {
			if(data==1){
                        $.ajax({
					  	url: "post.php?action=InsertTempImages",
						type: 'POST',
						data: {},
						success: function(data) {
						$("#getimagessss").fadeOut().html(data).fadeIn('slow');
						$("#loading").fadeOut("slow");
						}
						});
		   }else{
		   alert("not deleted")
		   }
          }
        });
        return false;
	} else
	{
	   return false;	
     }
    }
	
	
	
	  function myimagesvalidation(id) {
			  var lblError = document.getElementById("lblErrorinserted"+id);
			    var file_size = $('#myinsertedimages'+id)[0].files[0].size;
              myfile= $('#myinsertedimages'+id).val();
				if(file_size>2097152) {
				$("#lblErrorinserted"+id).html("File size must not be more than 2 MB");
				return false;
				$('#myinsertedimages'+id).val('');
				} 
 
    var fileUpload = document.getElementById("myinsertedimages"+id);
                if (typeof (FileReader) != "undefined") {
                    var dvPreview = document.getElementById("tempemptyimage"+id);
                    dvPreview.innerHTML = "";
                    var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/;
                    for (var i = 0; i < fileUpload.files.length; i++) {
                        var file = fileUpload.files[i];
                        if (regex.test(file.name.toLowerCase())) {
                            var reader = new FileReader();
                            reader.onload = function (e) {
                                var img = document.createElement("IMG");
                                img.height = "144";
                                img.width = "150";
                                img.src = e.target.result;
								img.class="img-thumbnail";
                                dvPreview.appendChild(img);
								
                            }
                            reader.readAsDataURL(file);
							
							$("#emptyopenimages"+id).hide();
                        } 
						
						else {
                            alert(file.name + " is not a valid image file.");
                            dvPreview.innerHTML = "";
								$('#myinsertedimages'+id).val('');
                            return false;
                        }
                    }
                } else {
                    alert("This browser does not support HTML5 FileReader.");
                }
         
   var ext = myfile.split('.').pop();
   if(ext=="png" || ext=="jpg" || ext=="jpeg" || ext=="gif"){
      // alert('Valid');
	    lblError.innerHTML='';
   } else{
         lblError.innerHTML = "Please upload files having extensions: <b> only png,jpg,jpeg,gif</b> only.";
			document.getElementById('myinsertedimages'+id).value='';
   }
    }
function descriptionr(){ if($("#description").val()==""){}else{$("#descriptionr").html("");}}
function titler(){ if($("#title").val()==""){}else{$("#titler").html("");}}
function pricer(){ if($("#price").val()==""){}else{$("#pricer").html("");}}
function addarticals(){
        
			  var uploadfileone=document.getElementById('uploadfileone').value.trim();
			  var title=document.getElementById('title').value.trim();
			  var description=document.getElementById('description').value.trim();
			  
			  if(uploadfileone==''){
			     $('#uploadfileoner').html("Please upload services images");
				 $('#uploadfileone').focus();
				 return false;
			  }
			   if(title==''){
			     $('#titler').html("Please enter services name");
				 $('#title').focus();
				 return false;
			  }
			
			   if(description==''){
			     $('#descriptionr').html("Please enter description");
				 $('#description').focus();
				 return false;
			  }
			   $("#loading").show(); 
	       var formData = new FormData($("#insertmyproducts")[0]);
			 //alert(formData); return false;
			$.ajax({   
				url: "post.php?action=InsertedServiesImages",
				data : formData,
				processData: false,
				contentType: false,
				type: 'POST',
				success: function(data){
					if(data==1){
					    window.location='services.php';
						return false;
				     	}else {
                        alert('uploaded images limit only 4 images upload at time')
					 return false;
					}
					
				}
			});
			return false;

	}
	
	
	
	function updateimages(){
           
			  var title=document.getElementById('title').value.trim();
			  var description=document.getElementById('description').value.trim();
			 
			
			   if(title==''){
			     $('#titler').html("Please enter services name");
				 $('#title').focus();
				 return false;
			  }
			   if(description==''){
			     $('#descriptionr').html("Please enter description");
				 $('#description').focus();
				 return false;
			  }
			   $("#loading").show(); 
	       var formData = new FormData($("#myproductupdatedd")[0]);
			 //alert(formData); return false;
			$.ajax({   
				url: "post.php?action=UpdateServicesImages",
				data : formData,
				processData: false,
				contentType: false,
				type: 'POST',
				success: function(data){
					if(data==1){
					    window.location='services.php';
						return false;
				     	}else {
                        alert('uploaded images limit only 4 images upload at time')
					 return false;
					}
					
				}
			});
			return false;

	}


</script>
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
		<div id="loading" style="display:none;">
        <img id="loading-image" src="images/show_loader.gif" alt="Loading..." />
              </div>

			<ol class="breadcrumb">
				<li><a href="index.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
				<li class="active">Services</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Services</h1>
				<?php if(isset($_SESSION['SUCESSMSG'])){ ?>
				<div class="alert bg-success" role="alert">
			<svg class="glyph stroked checkmark"><use xlink:href="#stroked-checkmark"></use></svg><?php echo $_SESSION['SUCESSMSG']; ?><a href="#" class="pull-right"><span class="glyphicon glyphicon-remove"></span></a>
				</div>
				<?php unset($_SESSION['SUCESSMSG']); } ?>
			</div>
		</div><!--/.row-->
				
		<?php if(isset($_GET['product'])){ ?>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">Add Serivces</div>
					<div class="panel-body">
						<div  class="col-md-6">
						<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left" id="temponefilesss">
						<label class="control-label" for="first-name" style="text-align:left ">Upload Services Images:<span class="required">*</span></label>
						<div class="row">
						<div id="getimagessss"></div>
						<div class="col-md-2" style="padding-right:10px;">
						<div class="fileUpload">
						<span class="custom-span">+</span>
						<input id="uploadfileone" name="uploadfileone[]" type="file" class="upload" multiple onChange="insertempdata();"  />
						
						</div>
						</div>
						</div>
						<span id="uploadfileoner" style="color:red;"></span>
						</form>
							<form role="form" method="post" id="insertmyproducts">
							<div class="form-group">
									<label>Services Name<b style="color:red;"> *</b></label>
									<input type="text" id="title" name="title"  onChange="titler();" placeholder="Services Name" class="form-control">
									<span id="titler" style="color:red;"></span>
									</div>
								
								
								
								<div class="form-group">
									<label>Description<b style="color:red;"> *</b></label>
									<textarea type="text" id="description" name="description" onChange="descriptionr()" placeholder="Description" style="resize:none " rows="8" class="form-control"></textarea>
									<span id="descriptionr" style="color:red;"></span>
									</div>
					
								
															
							<div class="form-group"><input type="submit" class="btn btn-primary" onClick="return addarticals();" value="Add Product"></div>
							</div>
							
						</form>
					</div>
				</div>
			</div><!-- /.col-->
		</div><!-- /.row -->
		
		
		<?php }elseif(isset($_GET['action'])){ 
		 $query=mysql_query("select *from services where pid='".$_GET['catid']."'"); $product=mysql_fetch_array($query);
		  ?>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">Sub Sub Category Form</div>
					<div class="panel-body">
						<div class="col-md-6">
						
                    <form  data-parsley-validate class="form-horizontal form-label-left" id="myproductupdatedd" method="post"  enctype="multipart/form-data">
					<input type="hidden" id="productid" name="productid" value="<?php echo $_GET['catid']; ?>">
					<div class="form-group">
					  <label style="text-align:left ">Product Images:<span class="required" style="color:red;">*</span></label>
					  <div class="row">
					<?php $multipleimages=mysql_query("select *from product_images1 where pid='".$_GET['catid']."'"); ?>
					   <?php $i=1; 
					   while($rows=mysql_fetch_array($multipleimages)){ ?>
					<div class="col-md-3" align="left">
		  <img src="images/product/<?php echo $rows['product_path']; ?>" class="img-thumbnail" style="width:100%;height: 146px;" id="emptyopenimages<?php echo $i; ?>" />
		
		    <div id="tempemptyimage<?php echo $i; ?>"></div>
          <input type="file" id="myinsertedimages<?php echo $i; ?>" name="myinsertedimages<?php echo $i; ?>" onchange="myimagesvalidation(<?php echo $i; ?>)" >
		  <input type="hidden" id="mydefaultimages<?php echo $i; ?>"  name="mydefaultimages<?php echo $i; ?>" value="<?php echo $rows['product_path']; ?>"/>
		  <span style="color:red;" id="lblErrorinserted<?php echo $i; ?>"></span>
        </div>
		<?php $i++;
		 } ?>
		 </div>
					</div>
					<div class="form-group">
									<label>Product Name<b style="color:red;"> *</b></label>
									<input type="text" id="title" name="title"  value="<?php echo $product['title']; ?>" onChange="titler();" value="" placeholder="Product Name" class="form-control">
									<span id="titler" style="color:red;"></span>
									</div>
					            <div class="form-group">
									<label>Product Description<b style="color:red;"> *</b></label>
									<textarea type="text" id="description" name="description" onChange="descriptionr()" placeholder="Description" style="resize:none " rows="8" class="form-control"><?php echo trim($product['description']); ?></textarea>
									<span id="descriptionr" style="color:red;"></span>
									</div>
				
                  
					    <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
						  <button type="submit" name="updatecategory" class="btn btn-success" onClick="return updateimages()">Update Product</button>
                        </div>
                      </div>
                    </form>
					</div>
				</div>
			</div><!-- /.col-->
		</div>
		<?php }elseif(isset($_GET['opendata'])){ ?><!-- /.row -->
		
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">View Product &nbsp;&nbsp;&nbsp;<a href="services.php" class="btn btn-primary">Back
					</a></div>
					<div class="panel-body">
					<?php $query=mysql_query("select *from services where pid='".$_GET['catid']."'"); $row=mysql_fetch_array($query) ?>
					<table class="table table-striped table-bordered">
					<tbody>
					<tr><th>Services Name:</th><td><?php echo $row['title'] ?></td></tr>
					
					<tr><th>Services Images</th><td>  <?php $mobilegloballianz=mysql_query("select *from product_images1 where pid=".$row['pid'].""); while($myimages=mysql_fetch_array($mobilegloballianz)){ 
					if(!empty( $myimages['product_path'])) {?>
						  <img src="images/product/<?php echo $myimages['product_path']; ?>" class="img-thumbnail" style="height:100px; width:150px;" />&nbsp;
						  <?php }  }?></td></tr>
					<tr><th>Services Description</th><td><?php echo $row['description']; ?></td></tr>
					<tr><th>Product Date</th><td><?php echo $row['date']; ?></td></tr>
					</tbody>
					</table>
					</div>
				</div>
			</div><!-- /.col-->
		</div>
		
		<?php }else{ ?>
		<div class="row">
		<div class="col-lg-12">
		 <a href="services.php?product=AddproductNew" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Add</a>
		<div class="table-responsive">
		    <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Sr No</th>
                          <th>Services Name</th>
						  <th>Date</th>
                          <th>Status</th>
                          <th>Action </th>
                       
                        </tr>
                      </thead>

                      <tbody>
					  <?php $query=mysql_query("select *from services order by pid desc");
					//  echo "select *from sub_sub_category order by sscid desc";
					    $i=1;
						while($row=mysql_fetch_array($query)){
					   ?>
                        <tr>
                          <td><?php echo $i++; ?></td>
						  <td><?php echo $row['title'] ?></td>
                          <td><?php echo $row['date']; ?></td>
						   <td><?php if($row['status']=='active') { ?><a href="post.php?action=servicesactive&inactive=<?php echo $row['pid']; ?>" onclick="return confirm('are you sure to this update status');"  class="btn btn-success" title="<?php echo $row['status']; ?>"><?php echo $row['status']; ?></a><?php }else { ?>
						   <a href="post.php?action=servicesinactive&active=<?php echo $row['pid']; ?>" onclick="return confirm('are you sure to this update status');"  class="btn btn-danger" title="<?php echo $row['status']; ?>"><?php echo $row['status']; ?></a><?php } ?>
						  </td>
                          <td>
						  <a href="services.php?opendata=opendata&catid=<?php echo $row['pid']; ?>"  class="btn btn-primary" title="Edit"><span class="glyphicon glyphicon-eye-open"></span></a>&nbsp;&nbsp;
						  <a href="services.php?action=subsubcategory&catid=<?php echo $row['pid']; ?>"  class="btn btn-primary" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
						  <a href="post.php?action=deleteserviess&delete=<?php echo $row['pid']; ?>" onclick="return confirm('are you sure to This Remove Records !'); " class="btn btn-danger" title="Delete" ><span class="glyphicon glyphicon-trash"></span></a></td>
                        </tr>
						<?php } ?>
                      </tbody>
                    </table>
		</div></div>
		</div>
		<?php } ?>
	</div>
	<?php include('footer.php'); ?>
	<script>
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
   header('Location:login.php');
?>
<?php } ?>


