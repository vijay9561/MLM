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
$query="select e.product_id,e.id,e.status,e.created_date,r.name as c_name,r.mobile as c_mobile,v.name as v_name,v.mobile as v_mobile,v.vendor_store_name,p.title,p.discount_price from product_enquiry e inner join registration r on r.rid=e.userid inner join registration v on v.rid=e.vendor_id inner join product_details p on e.product_id=p.pid  where (p.title LIKE '%".$searchid."%' OR v.name LIKE '%".$searchid."%' OR v.mobile LIKE '%".$searchid."%' OR r.name LIKE '%".$searchid."%' OR r.mobile LIKE '%".$searchid."%') and e.status='New Enquiry' order by e.id desc LIMIT ".$pageLimit." , ".$setLimit;

$mysqluery=mysql_query($query);
}else{
$query="select e.product_id,e.id,e.status,e.created_date,r.name as c_name,r.mobile as c_mobile,v.name as v_name,v.mobile as v_mobile,v.vendor_store_name,p.title,p.discount_price from product_enquiry e inner join registration r on r.rid=e.userid inner join registration v on v.rid=e.vendor_id inner join product_details p on e.product_id=p.pid where e.status='New Enquiry' order by e.id desc LIMIT ".$pageLimit." , ".$setLimit;
$mysqluery=mysql_query($query);
}
}
		if(isset($_SESSION['JOBPORTALADMIN'])) { ?>
		<script src="ckeditor/ckeditor.js" type="text/javascript"></script>
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
				url: "post.php?action=InsertMyImages",
				data : formData,
				processData: false,
				contentType: false,
				type: 'POST',
				success: function(data){
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
            url: "post.php?action=DeleteImages",
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
function quntityr(){ if($("#quntity").val()==""){}else{$("#quntityr").html("");}}
function vendor_idr(){ if($("#vendor_id").val()==""){ }else{ $("#vendor_idr").html(" "); } }

function addarticals(){
              var category_name=document.getElementById('category_name').value.trim();
			  var uploadfileone=document.getElementById('uploadfileone').value.trim();
			  var title=document.getElementById('title').value.trim();
			  var description=document.getElementById('description').value.trim();
			  var price=document.getElementById('price').value.trim();
		          var quntity=document.getElementById('quntity').value.trim();
                          var vendor_id=document.getElementById('vendor_id').value.trim();  
                          
			  if(uploadfileone==''){
			     $('#uploadfileoner').html("Please upload product images");
				 $('#uploadfileone').focus();
				 return false;
			  }
			   if(title==''){
			     $('#titler').html("Please enter product name");
				 $('#title').focus();
				 return false;
			  }
			    if(category_name==''){
			     $('#category_namer').html("Please select category");
				 $('#category_name').focus();
				 return false;
			  }
			   if(price==''){
			     $('#pricer').html("Please enter price");
				 $('#price').focus();
				 return false;
			  }
			  if(quntity==''){
			    $('#quntityr').html("please enter quantity");
				$("#quntity").focus();
				 return false;
			    }
                            if(vendor_id==''){
                             $("#vendor_idr").html("Please select vendor name");
                             $("#vendor_id").focus();
                             return false;
                            }
			   $("#loading").show(); 
	       var formData = new FormData($("#insertmyproducts")[0]);
			 //alert(formData); return false;
			/*$.ajax({   
				url: "post.php?action=InsertOnlyProduct",
				data : formData,
				processData: false,
				contentType: false,
				type: 'POST',
				success: function(data){
					if(data==1){
					    window.location='product.php';
						return false;
				     	}else {
                        alert('uploaded images limit only 4 images upload at time')
					 return false;
					}
					
				}
			});
			return false;
*/
	}
	
	
	
	function updateimages(){
           
			  var title=document.getElementById('title').value.trim();
			  var description=document.getElementById('description').value.trim();
			    var price=document.getElementById('price').value.trim();
							var quntity=document.getElementById('quntity').value.trim();
			   if(title==''){
			     $('#titler').html("Please enter product name");
				 $('#title').focus();
				 return false;
			  }
			  
			   if(price==''){
			     $('#pricer').html("Please enter price");
				 $('#price').focus();
				 return false;
			  }
			   if(quntity==''){
			    $('#quntityr').html("please enter quantity");
				$("#quntity").focus();
				 return false;
			    }
			   $("#loading").show(); 
	       //var formData = new FormData($("#myproductupdatedd")[0]);
			 //alert(formData); return false;
			/*$.ajax({   
				url: "post.php?action=UpdateProductImages",
				data : formData,
				processData: false,
				contentType: false,
				type: 'POST',
				success: function(data){
					if(data==1){
					    window.location='product.php';
						return false;
				     	}else {
                        alert('uploaded images limit only 4 images upload at time')
					 return false;
					}
					
				}
			});
			return false;
			*/

	}


</script>
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
		<div id="loading" style="display:none;">
        <img id="loading-image" src="images/show_loader.gif" alt="Loading..." />
              </div>

			<ol class="breadcrumb">
				<li><a href="index.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
				<li class="active">New Product Enquiry</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<!--<h1 class="page-header">New Product Enquiry</h1>-->
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
				
		<?php if(isset($_GET['product'])){ ?>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">New Product Enquiry</div>
					<div class="panel-body">
						<div class="col-md-6">
						<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  id="temponefilesss">
						<label class="control-label" for="first-name" style="text-align:left ">Upload Product Images:<span class="required">*</span></label>
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
							<form role="form" method="post" id="insertmyproducts" action="post.php?action=InsertOnlyProduct">
							<div class="form-group">
									<label>Product Name<b style="color:red;"> *</b></label>
									<input type="text" id="title" name="title"  onChange="titler();" placeholder="Product Name" class="form-control">
									<span id="titler" style="color:red;"></span>
									</div>
								<div class="form-group">
									<label>Select Category</label>
									 <select type="text" id="category_name" name="category_name" onchange="category_namer()" maxlength="50" required="required" class="form-control">
						 <option value="">Select Category</option> 
						 <?php $maincategories=mysql_query("select category_name,cid from category order by cid desc");
                                                       $registration=mysql_query("select vendor_store_name,city_name,state_name,rid from registration where user_type='Vendors'");
						 while($main=mysql_fetch_array($maincategories)){ ?>
						 <option value="<?php echo $main['cid']; ?>"><?php echo $main['category_name']; ?></option>
						 <?php } ?>
						  </select>
						  <span id="category_namer" style="color:red;"></span>
								</div>
								<div class="form-group" id="SubcategoryName">
								</div>
								
								<div class="form-group" id="GetSubSubCategory">
								
								</div>
								
								<div class="form-group">
                                                                    <br><br>
								<label> Product Description<b style="color:red;"> *</b></label>
									<textarea type="text" id="description" name="description" onChange="descriptionr()" placeholder="Description" style="resize:none " rows="8" class="form-control ckeditor"></textarea>
									<span id="descriptionr" style="color:red;"></span>
									</div>
									
									
							</div>
							<div class="col-md-6" style="margin-top:10px;">
							<br><br><br>
							<div class="form-group">
									<label>Product Price<b style="color:red;"> *</b></label>
									<input type="text" id="price" name="price" placeholder="Price"  onChange="pricer()" class="form-control">
									
									<span id="pricer" style="color:red;"></span>
									</div>
									
									<div class="form-group">
									<label>Product Discount Price</label>
									<input type="text" id="discount" name="discount" placeholder="Discount" class="form-control">
									
									</div>
								
								<div class="form-group">
									<label>Product Quntity <b style="color:red;"> *</b></label>
<input type="text" id="quntity" name="quntity" placeholder="Quantity" maxlength="10" onChange="quntityr()" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" outocomplete="off" class="form-control">
									<span id="quntityr" style="color:red;"></span>
									</div>
                                                        
                                                               <?php if($_SESSION['ADMIN_TYPE']=='Admin' || $_SESSION['ADMIN_TYPE']=='Employee'){ ?>
								<div class="form-group  col-md-12">
									<label>Select Vendor Name <b style="color:red;"> *</b></label>
                                                                        <select type="text" id="vendor_id" name="vendor_id" placeholder="Quantity" maxlength="10" onChange="vendor_idr()" outocomplete="off" class="form-control">
                                                                            <option value="">Select Vendor Name</option>    
                                                                            <?php while($row=mysql_fetch_array($registration)){ ?>
                                                                            <option value="<?php echo $row['rid']; ?>"><?php echo $row['vendor_store_name'].'('.$row['city_name'].' '.$row['state_name'].')';  ?></option> 
                                                                            <?php } ?>
                                                                        </select>
									<span id="vendor_idr" style="color:red;"></span>
								</div>
                                                               <?php }else{ ?>
                                                        <input type="hidden" name="vendor_id" id="vendor_id" value="<?php echo $_SESSION['ADMIN_ID']; ?>" onchange="vendor_idr()">
                                                        <span id="vendor_idr" style="color:red;"></span>
                                                               <?php } ?>								
							
							<div class="form-group">
									<label>Product Additional Information (optional)</label>
									<textarea type="text" id="product_additional_description" name="product_additional_description"  placeholder="Product Additional Information (optional)" style="resize:none " rows="8" class="form-control ckeditor"></textarea>
									
									</div>
							
							</div>
                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                <input type="submit" class="btn btn-primary" onClick="return addarticals();" value="Add Product"></div>
                                            </div>
							
						</form>
					</div>
				</div>
			</div><!-- /.col-->
		</div><!-- /.row -->
		
		
		<?php }elseif(isset($_GET['action'])){ 
		 $query=mysql_query("select *from product_details where pid='".$_GET['catid']."'"); $product=mysql_fetch_array($query);
                  $registration=mysql_query("select vendor_store_name,city_name,state_name,rid from registration where user_type='Vendors'");
		  ?>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">Sub Sub Category Form</div>
					<div class="panel-body">
						<div class="col-md-6">
					<?php if((isset($_GET['page'])) && (isset($_GET['searchkeyowords']))) { ?>
                    <form  data-parsley-validate class="form-horizontal form-label-left"  action="post.php?action=UpdateProductImages&searchkeyowords=<?php echo $_GET['searchkeyowords']; ?>&page=<?php echo $_GET['page'];?>" method="post"  enctype="multipart/form-data">
					<?php }elseif(isset($_GET['searchkeyowords'])) { ?>
		<form  data-parsley-validate class="form-horizontal form-label-left"  action="post.php?action=UpdateProductImages&searchkeyowords=<?php echo $_GET['searchkeyowords']; ?>" method="post"  enctype="multipart/form-data">
					<?php }elseif(isset($_GET['page'])) { ?>
					<form  data-parsley-validate class="form-horizontal form-label-left"  action="post.php?action=UpdateProductImages&page=<?php echo $_GET['page']; ?>" method="post"  enctype="multipart/form-data">
					<?php }else{ ?>
					<form  data-parsley-validate class="form-horizontal form-label-left"  action="post.php?action=UpdateProductImages" method="post"  enctype="multipart/form-data">
					<?php } ?>
					<input type="hidden" id="productid" name="productid" value="<?php echo $_GET['catid']; ?>">
					<div class="form-group">
					  <label style="text-align:left ">Product Images:<span class="required" style="color:red;">*</span></label>
					  <div class="row">
					<?php $multipleimages=mysql_query("select *from product_images where pid='".$_GET['catid']."'"); ?>
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
									<textarea type="text" id="description" name="description" onChange="descriptionr()" placeholder="Description" style="resize:none " rows="8" class="form-control ckeditor"><?php echo trim($product['description']); ?></textarea>
									<span id="descriptionr" style="color:red;"></span>
									</div>
									
									
                  
					    <div class="ln_solid"></div>
                     
					  </div>
					  <div class="col-md-6">
					  <br><br>
					  <div class="form-group">
									<label>Product Price<b style="color:red;"> *</b></label>
									<input type="text" id="price" name="price" placeholder="Price" value="<?php echo $product['price']; ?>"  onChange="pricer()" class="form-control">
									
									<span id="pricer" style="color:red;"></span>
									</div>
									
									<div class="form-group">
									<label>Product Discount</label>
									<input type="text" id="discount" name="discount" value="<?php echo $product['discount_price']; ?>" placeholder="Discount" class="form-control">
									</div>
                  <div class="form-group">
									<label>Product Quntity <b style="color:red;"> *</b></label>
<input type="text" id="quntity" name="quntity" placeholder="Quantity" maxlength="10" value="<?php echo $product['product_quantity']; ?>" onChange="quntityr()" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" outocomplete="off" class="form-control">
									<span id="quntityr" style="color:red;"></span>
									</div>
                                          <?php if($_SESSION['ADMIN_TYPE']=='Admin' || $_SESSION['ADMIN_TYPE']=='Employee'){ ?>
                                                                <div class="form-group  col-md-12">
									<label>Select Vendor Name <b style="color:red;"> *</b></label>
                                                                        <select type="text" id="vendor_id" name="vendor_id" placeholder="Quantity" maxlength="10" onChange="vendor_idr()" outocomplete="off" class="form-control">
                                                                                
                                                                            <?php while($row=mysql_fetch_array($registration)){ ?>
                                                                            <option <?php if($product['vendor_id']==$row['rid']){ echo 'selected'; } ?> value="<?php echo $row['rid']; ?>"><?php echo $row['vendor_store_name'].'('.$row['city_name'].' '.$row['state_name'].')';  ?></option> 
                                                                            <?php } ?>
                                                                        </select>
									<span id="vendor_idr" style="color:red;"></span>
									</div>
                                          <?php }else{ ?>
                                          <input type="hidden" value="<?php echo $_SESSION['ADMIN_ID']; ?>" id="vendor_id" name="vendor_id" onchange="vendor_idr();">
                                          <span id="vendor_idr" style="color:red;"></span>
                                          <?php } ?>
									<div class="form-group">
									<label>Product Additional Information (optional)</label>
									<textarea type="text" id="product_additional_description" name="product_additional_description"  placeholder="Product Additional Information (optional)" style="resize:none " rows="8" class="form-control ckeditor"><?php echo $product['product_additional_description']; ?></textarea>
									
									</div>
					   <div class="form-group">
                     
						  <button type="submit" name="updatecategory" class="btn btn-primary" onClick="return updateimages()">Update Product</button>
                     
                      </div>
					  </div>
                    </form>
					
				</div>
			</div><!-- /.col-->
		</div>
		<?php }elseif(isset($_GET['opendata'])){
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
					 
					<a href="product_enquiry.php" class="btn btn-primary">Back</a>
					
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
		<!-- <a href="product.php?product=AddproductNew" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Add</a> -->
		<form name="bulk_action_form" action="post.php?action=assign_orders_to_employee_details" method="post">
		 <div class="row">
		<div class="col-md-6">
                       <?php   $registration=mysql_query("select name,city_name,state_name,rid from registration where user_type='Employee' and status='active'"); ?>     
		<!--<a href="product.php?product=AddproductNew" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Add New</a>-->
                    <div id="bulk_delete_submit" style="display:none;">
                        <div class="row">
                            
                            <div class="col-md-8">
                        
<div class="form-group">
                                          <label>Assign Order To Employee<b style="color:red;"> *</b></label>
                                          <select type="text" required="" id="employee_assign" name="employee_assign" placeholder="Quantity" maxlength="10" outocomplete="off" class="form-control">
                                              <option value="">Select Employee Name</option>    
                                              <?php while($row=mysql_fetch_array($registration)){ ?>
                                              <option value="<?php echo $row['rid']; ?>"><?php echo $row['name'];  ?></option> 
                                              <?php } ?>
                                          </select>
                                          <span id="employee_assignr" style="color:red;"></span>
                                  </div>  
                            </div>
                            <div class="col-md-4"><br>
		 <input type="submit" class="btn btn-danger" onclick="return deleteConfirmcheckbox_employee();" value="Assign Enquiry" /><br><br>
                            </div> </div></div>
	<span>Select All<input style="margin: -17px 33px 0px;" type="checkbox" name="select_all" id="select_all" value=""/></span>
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
                          <th>Seller Name</th>
                          <th>Seller Mobile</th> 
                          <th>Seller Store Name</th>
                          <th>Eqnuiry Date</th>
       
                          <th style="width:10%;">Action </th>
                       
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
                            <td><span><?php echo $j++; ?> &nbsp;<input type="checkbox" style="display: inherit;margin: -17px 33px 0px;" name="checked_id[]" class="checkbox" onclick="check_checkboxes()" value="<?php echo $row['id']; ?>"/></span></td>
                            <td><?php echo $row['title']; ?></td>   
                            <td><i class="fa fa-inr"></i> <?php echo $row['discount_price']; ?></td>
                            <td><?php echo $row['c_name']; ?></td>
                            <td><?php echo $row['c_mobile']; ?></td>
                            <td><?php echo $row['v_name']; ?></td>
                            <td><?php echo $row['v_mobile']; ?></td>
                            <td><?php echo $row['vendor_store_name']; ?></td> 
                            <td><?php echo date('d-m-Y', strtotime($row['created_date'])); ?></td>
                            <td><a href="product_enquiry.php?opendata=<?php echo $row['id']; ?>" title="View Order" class="btn btn-primary"><i class="fa fa-eye"></i> </a></td>  
                          </tr> 
                       
						<?php $i++; } ?>
                      </tbody>
                    </table>
		</form>
					<?php
                        
                                     $searchid='';
                                       if(isset($_GET['searchkeyowords'])){
                                           $searchid=$_GET['searchkeyowords'];
                                         $query="SELECT COUNT(*) as totalCount,e.product_id,e.id,e.status,e.created_date,r.name as c_name,r.mobile as c_mobile,v.name as v_name,v.mobile as v_mobile,v.vendor_store_name,p.title,p.discount_price from product_enquiry e inner join registration r on r.rid=e.userid inner join registration v on v.rid=e.vendor_id inner join product_details p on e.product_id=p.pid  where (p.title LIKE '%".$searchid."%' OR v.name LIKE '%".$searchid."%' OR v.mobile LIKE '%".$searchid."%' OR r.name LIKE '%".$searchid."%' OR r.mobile LIKE '%".$searchid."%') and e.status='New Enquiry'";  
                                         echo standard_paging_function($setLimit,$page,$query,$searchid); } else{  
                                           $query="SELECT COUNT(*) as totalCount,e.product_id,e.id,e.status,e.created_date,r.name as c_name,r.mobile as c_mobile,v.name as v_name,v.mobile as v_mobile,v.vendor_store_name,p.title,p.discount_price from product_enquiry e inner join registration r on r.rid=e.userid inner join registration v on v.rid=e.vendor_id inner join product_details p on e.product_id=p.pid order by e.id desc";
                                         echo standard_paging_function($setLimit,$page,$query,$searchid);  } 
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
window.location='product_enquiry.php?searchkeyowords='+search_id2;
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


