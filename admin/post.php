<?php
date_default_timezone_set('Asia/Kolkata');
include('config.php');
error_reporting(0);
 $action=$_GET['action'];
if($action=='LoginAdmin'){
$loginid =  $_POST['email'];
$password = $_POST['password'];

$login = mysql_query("SELECT * FROM admin WHERE email = '$loginid' and password = '$password' and status = 'active'");
// Check username and password match
if (mysql_num_rows($login)>=1) 
{
 $_SESSION['JOBPORTALADMIN'] = $loginid;
 header('Location:index.php');
 echo 1;
}else{
$_SESSION['ERRORMSG']='Email ID OR Password Incorrect';
echo 2;
}
}
if($action=='AddCategory'){
  $query=mysql_query("select *from category");
  $counts=mysql_num_rows($query);
 if($counts<100){
$categoryname=$_POST['categoryname'];
    $date=date('Y-m-d H:i:s');
           $string=explode('.',$_FILES["image"]["name"]);
	   $image =  $string[0].'_'. rand(0, 10000) . '.' . end(explode(".", $_FILES["image"]["name"]));
		move_uploaded_file($_FILES["image"]["tmp_name"], "../images/category/" . $image);   
$query=mysql_query("insert into category(category_name,date,status,image)values('$categoryname','$date','active','$image')");
if($query==true){
$_SESSION['SUCESSMSG']='Category Added Successfully..';
header('Location:category.php');
}else{
header('Location:category.php');
}
}else{
$_SESSION['ErrorMsg']='Your category add limit excced';
header('Location:category.php'); 
}
}
if($action=='inactivecategory'){
$id=$_GET['inactive'];
$query=mysql_query("update category set status='inactive' where cid='$id'");
if($query==true){
$_SESSION['SUCESSMSG']='Status Changed Successfully..';
header('Location:category.php');
}else{
header('Location:category.php');
}
}
if($action=='activecategory'){
$id=$_GET['active'];
$query=mysql_query("update category set status='active' where cid='$id'");
if($query==true){
$_SESSION['SUCESSMSG']='Status Changed Successfully..';
header('Location:category.php');
}else{
header('Location:category.php');
}
}
if($action=='deletecategory'){
$id=$_GET['delete'];
$query=mysql_query("delete from category where cid='$id'");
if($query==true){
$_SESSION['SUCESSMSG']='Category Deleted Successfully..';
header('Location:category.php');
}else{
header('Location:category.php');
}
}
if($action=='UpdateCategory'){
$categoryname=$_POST['categoryname'];
$cid=$_POST['cid'];
 if(!empty($_FILES["image"]["name"])){
	 $string=explode('.',$_FILES["image"]["name"]);
	   $profile_images =  $string[0].'_'. rand(0, 10000) . '.' . end(explode(".", $_FILES["image"]["name"]));
		move_uploaded_file($_FILES["image"]["tmp_name"], "../images/category/" . $profile_images);
		}else{$profile_images=mysql_real_escape_string($_POST['dimage']); }

$query=mysql_query("update category set category_name='$categoryname',image='$profile_images' where cid='$cid'");
if($query==true){
$_SESSION['SUCESSMSG']='Category Update Successfully..';
header('Location:category.php');
}else{
header('Location:category.php');
}
}

/*sub category code*/
if($action=='InsertSubCategory'){
 $date=date('Y-m-d H:i:s');
 $category_name=$_POST['category_name'];
  $sub_category_name=$_POST['sub_category_name'];
 $query=mysql_query("insert into sub_category(mid,category_name,date,status)values('$category_name','$sub_category_name','$date','active')");
 if($query==true){
 $_SESSION['SUCESSMSG']='Sub Category Added Successfully..';
header('Location:subcategory.php');
 }else{
  header('Location:subcategory.php');
 }
}


if($action=='subcategoryinactive'){
$id=$_GET['inactive'];
$query=mysql_query("update sub_category set status='inactive' where scid='$id'");
if($query==true){
//$_SESSION['SUCESSMSG']='Status Changed Successfully..';
//header('Location:subcategory.php');
if((isset($_GET['page'])) && (isset($_GET['searchkeyowords']))) { 
  $_SESSION['SUCESSMSG']='Status Changed Successfully..';
  header("Location:subcategory.php?searchkeyowords=".$_GET['searchkeyowords'].'&page='.$_GET['page']);
  }elseif(isset($_GET['searchkeyowords'])) {
   $_SESSION['SUCESSMSG']='Status Changed Successfully..';
  header("Location:subcategory.php?searchkeyowords=".$_GET['searchkeyowords']);
  }elseif(isset($_GET['page'])) {
   $_SESSION['SUCESSMSG']='Status Changed Successfully..';
  header("Location:subcategory.php?page=".$_GET['page']);
  }else{
  $_SESSION['SUCESSMSG']='Status Changed Successfully..';
  header("Location:subcategory.php");
   }
}else{
header('Location:subcategory.php');
}
}
if($action=='subcategoryactive'){
$id=$_GET['active'];
$query=mysql_query("update sub_category set status='active' where scid='$id'");
if($query==true){
//$_SESSION['SUCESSMSG']='Status Changed Successfully..';
//header('Location:subcategory.php');
if((isset($_GET['page'])) && (isset($_GET['searchkeyowords']))) { 
  $_SESSION['SUCESSMSG']='Status Changed Successfully..';
  header("Location:subcategory.php?searchkeyowords=".$_GET['searchkeyowords'].'&page='.$_GET['page']);
  }elseif(isset($_GET['searchkeyowords'])) {
   $_SESSION['SUCESSMSG']='Status Changed Successfully..';
  header("Location:subcategory.php?searchkeyowords=".$_GET['searchkeyowords']);
  }elseif(isset($_GET['page'])) {
   $_SESSION['SUCESSMSG']='Status Changed Successfully..';
  header("Location:subcategory.php?page=".$_GET['page']);
  }else{
  $_SESSION['SUCESSMSG']='Status Changed Successfully..';
  header("Location:subcategory.php");
   }
}else{
header('Location:subcategory.php');
}
}
if($action=='subcategorydelete'){
$id=$_GET['delete'];
$query=mysql_query("delete from sub_category where scid='$id'");
if($query==true){
//$_SESSION['SUCESSMSG']='Sub Category Deleted Successfully..';
//header('Location:subcategory.php');

if((isset($_GET['page'])) && (isset($_GET['searchkeyowords']))) { 
  $_SESSION['SUCESSMSG']='Deleted Successfully..';
  header("Location:subcategory.php?searchkeyowords=".$_GET['searchkeyowords'].'&page='.$_GET['page']);
  }elseif(isset($_GET['searchkeyowords'])) {
   $_SESSION['SUCESSMSG']='Deleted Successfully..';
  header("Location:subcategory.php?searchkeyowords=".$_GET['searchkeyowords']);
  }elseif(isset($_GET['page'])) {
   $_SESSION['SUCESSMSG']='Deleted Successfully..';
  header("Location:subcategory.php?page=".$_GET['page']);
  }else{
  $_SESSION['SUCESSMSG']='Deleted Successfully..';
  header("Location:subcategory.php");
   }
}else{
header('Location:subcategory.php');
}
}
if($action=='UpdateSubCategory'){
$category_name=$_POST['category_name'];
$hidecategoryid=$_POST['hidecategoryid'];
 $sub_category_name=$_POST['sub_category_name'];
$query=mysql_query("update sub_category set mid='$category_name',category_name='$sub_category_name' where scid='$hidecategoryid'");
if($query==true){

//header('Location:subcategory.php');
 if((isset($_GET['page'])) && (isset($_GET['searchkeyowords']))) { 
 $_SESSION['SUCESSMSG']='Sub Category Updated Successfully..';
  header("Location:subcategory.php?searchkeyowords=".$_GET['searchkeyowords'].'&page='.$_GET['page']);
  }elseif(isset($_GET['searchkeyowords'])) {
$_SESSION['SUCESSMSG']='Sub Category Updated Successfully..';
  header("Location:subcategory.php?searchkeyowords=".$_GET['searchkeyowords']);
  }elseif(isset($_GET['page'])) {
 $_SESSION['SUCESSMSG']='Sub Category Updated Successfully..';
  header("Location:subcategory.php?page=".$_GET['page']);
  }else{
$_SESSION['SUCESSMSG']='Sub Category Updated Successfully..';
  header("Location:subcategory.php");
   }
 }else{
 header('Location:subcategory.php');
 }
}
/*End sub category code*/
/*start registered users list*/

if($action=='registeredinactive'){
$id=$_GET['inactive'];
$query=mysql_query("update registration set status='inactive' where id='$id'");
if($query==true){
$_SESSION['SUCESSMSG']='Status Changed Successfully..';
header('Location:registeredlist.php');
}else{
header('Location:registeredlist.php');
}
}
if($action=='registeredactive'){
$id=$_GET['active'];
$query=mysql_query("update registration set status='active' where id='$id'");
if($query==true){
$_SESSION['SUCESSMSG']='Status Changed Successfully..';
header('Location:registeredlist.php');
}else{
header('Location:registeredlist.php');
}
}
if($action=='registereddelete'){
$id=$_GET['delete'];
$query=mysql_query("delete from registration where id='$id'");
if($query==true){
$_SESSION['SUCESSMSG']='Registered User Deleted Successfully..';
header('Location:registeredlist.php');
}else{
header('Location:registeredlist.php');
}
}
/* Faq action start*/
if($action=='AddFaq'){
$categoryname=$_POST['categoryname'];
$answer=$_POST['answer'];
 $date=date('Y-m-d');
 $query=mysql_query("insert into faq(question,answer,date,status)values('$categoryname','$answer','$date','active')");
 if($query==true){
$_SESSION['SUCESSMSG']='Question Added Successfully..';
header('Location:faq.php');
}else{
header('Location:faq.php');
}
}
if($action=='UpdateFaq'){
$categoryname=$_POST['categoryname'];
$answer=$_POST['answer'];
$faqid=$_POST['faqid'];
$query=mysql_query("update faq set question='$categoryname',answer='$answer' where id='$faqid'");
if($query==true){
$_SESSION['SUCESSMSG']='Question Update Successfully..';
header('Location:faq.php');
}else{
header('Location:faq.php');
}
}

if($action=='faqinactive'){
$id=$_GET['inactive'];
$query=mysql_query("update faq set status='inactive' where id='$id'");
if($query==true){
$_SESSION['SUCESSMSG']='Status Changed Successfully..';
header('Location:faq.php');
}else{
header('Location:faq.php');
}
}
if($action=='faqactive'){
$id=$_GET['active'];
$query=mysql_query("update faq set status='active' where id='$id'");
if($query==true){
$_SESSION['SUCESSMSG']='Status Changed Successfully..';
header('Location:faq.php');
}else{
header('Location:faq.php');
}
}
if($action=='faqdelete'){
$id=$_GET['delete'];
$query=mysql_query("delete from faq where id='$id'");
if($query==true){
$_SESSION['SUCESSMSG']='Question Deleted Successfully..';
header('Location:faq.php');
}else{
header('Location:faq.php');
}
}
/*Faq action end*/
if($action=='getsubcategory'){
$mid=$_POST['category'];
$data='';
$maincategories=mysql_query("select *from sub_category where mid='$mid' order by scid desc");
						
						$data.='<label>Sub Category Name</label>
						   <select type="text" id="sub_category_name" name="sub_category_name" onchange="sub_category_namer()" maxlength="50" required="required" class="form-control">
						 <option value="">Select Sub Category</option>';
						  while($main=mysql_fetch_array($maincategories)){
						 $data.='<option value='.$main['scid'].'>'.$main['category_name'].'</option>';
						  }
						$data.='</select><span id="sub_category_namer" style="color:red;"></span></div>'; 
						
			echo $data;
}


if($action=='getsubcategory1234'){
$mid=$_POST['category'];
$data='';
$maincategories=mysql_query("select *from sub_category where mid='$mid' order by scid desc");
						if(mysql_num_rows($maincategories)>=1){
						$data.='<label>Sub Category Name</label>
						   <select type="text" id="sub_category_name" name="sub_category_name" onchange="sub_category_namer()" maxlength="50" class="form-control">
						 <option value="">Select Sub Category</option>';
						  while($main=mysql_fetch_array($maincategories)){
						 $data.='<option value='.$main['scid'].'>'.$main['category_name'].'</option>';
						  }
						$data.='</select><span id="sub_category_namer" style="color:red;"></span></div>'; 
						
			echo $data;
			}else{
		echo 4;
		exit;
	 }
}
/*get Sub Sub Category*/
if($action=='Subcategorylist'){
$sub=$_POST['category'];
$data='';
$maincategories=mysql_query("select *from sub_sub_category where scid='$sub' order by sscid desc");
$mysql_num='';
//echo $mysql_num;
						if(mysql_num_rows($maincategories)>=1){
						$data.='<label>Sub Sub Category Name</label>
						  <select type="text" id="sub_sub_category" name="sub_sub_category" value="" onchange="sub_sub_categoryr()" maxlength="50"  class="form-control">
						 <option value="">Select Sub Category</option>';
						  while($main=mysql_fetch_array($maincategories)){
						 $data.='<option value='.$main['sscid'].'>'.$main['category_name'].'</option>';
						  }
						$data.='  <span id="sub_sub_categoryr" style="color:red;"></span></div>'; 
						
			    echo $data;
			
         }else{
		echo 4;
		exit;
	 }
   }
/*get Sub Sub Category End*/

/*my product inserted*/
if($action=='InsertOnlyProduct'){

$title=mysql_real_escape_string($_POST['title']);
$description=mysql_real_escape_string($_POST['description']);
$product_additional_description=mysql_real_escape_string($_POST['product_additional_description']);
$price=$_POST['price'];
$discount=$_POST['discount'];
$category_name=$_POST['category_name'];
$sub_category_name=$_POST['sub_category_name'];
$sub_sub_category=$_POST['sub_sub_category'];
$quntity=$_POST['quntity'];
$vendor_id=$_POST['vendor_id'];
$added_id= $_SESSION['ADMIN_USERNAME'];
$userid=$_SESSION['ADMIN_ID'];
	    $date=date('Y-m-d H:i:s');
	$mysql_query=mysql_query("select *from image where amid='$userid'");
	mysql_query("insert into product_details(title,price,discount_price,description,category_id,sub_category_id,sub_sub_category_id,date,status,product_quantity,product_additional_description,vendor_id,added_by,userid)values('$title','$price','$discount','$description','$category_name',
	'$sub_category_name','$sub_sub_category','$date','active','$quntity','$product_additional_description','$vendor_id','$added_id','$userid')");
		$id=mysql_insert_id();
		while($image=mysql_fetch_array($mysql_query)){
	    mysql_query("insert into product_images(product_path,pid)values('".$image['image_path']."','$id')");
	}	if($id){
	      mysql_query("delete from image where amid='$userid'");
		$_SESSION['SUCESSMSG']="Product Added Successfully";
		header('Location:product.php');
		//echo 1;
		exit;
		}else{
		  echo 2;
		  exit;
		}
   }

/*only my product inserted*/



/*my product inserted*/
if($action=='InsertedServiesImages'){

$title=$_POST['title'];
$description=mysql_real_escape_string($_POST['description']);
	    $date=date('Y-m-d H:i:s');
	$mysql_query=mysql_query("select *from image1 where amid='1'");
	mysql_query("insert into services(title,description,date,status)values('$title','$description','$date','active')");
		$id=mysql_insert_id();
		while($image=mysql_fetch_array($mysql_query)){
	    mysql_query("insert into product_images1(product_path,pid)values('".$image['image_path']."','$id')");
	}	if($id){
	   mysql_query("delete from image1 where amid='1'");
		$_SESSION['SUCESSMSG']="Services Added Successfully";
		echo 1;
		exit;
		}else{
		  echo 2;
		  exit;
		}
		

}

/*only my product inserted*/

if($action=='InsertSubSubCategory'){
 $date=date('Y-m-d H:i:s');
 $category_name=$_POST['category_name'];
  $sub_category_name=$_POST['sub_category_name'];
    $sub_sub_category=$_POST['sub_sub_category'];
 $query=mysql_query("insert into sub_sub_category(scid,cid,category_name,date,status)values('$sub_category_name','$category_name','$sub_sub_category','$date','active')");
 //echo "insert into sub_sub_category(scid,cid,category_name,date,status)values('$sub_category_name','$category_name','$sub_sub_category','$date','active')";
 //exit;
 if($query==true){
 $_SESSION['SUCESSMSG']='Sub Sub Category Added Successfully..';
header('Location:sub-sub-category.php');
 }else{
  header('Location:sub-sub-category.php');
 }
}

if($action=='UpdateSubSubCategory'){
$category_name=$_POST['category_name'];
$hidecategoryid=$_POST['hidecategoryid'];
 $sub_category_name=$_POST['sub_category_name'];
     $sub_sub_category=$_POST['sub_sub_category'];
$query=mysql_query("update sub_sub_category set cid='$category_name',scid='$sub_category_name',category_name='$sub_sub_category' where sscid='$hidecategoryid'");
if($query==true){
$_SESSION['SUCESSMSG']='Sub Sub Category Updated Successfully..';
//header('Location:sub-sub-category.php');
 if((isset($_GET['page'])) && (isset($_GET['searchkeyowords']))) { 
  header("Location:sub-sub-category.php?searchkeyowords=".$_GET['searchkeyowords'].'&page='.$_GET['page']);
  }elseif(isset($_GET['searchkeyowords'])) {
  header("Location:sub-sub-category.php?searchkeyowords=".$_GET['searchkeyowords']);
  }elseif(isset($_GET['page'])) {
  header("Location:sub-sub-category.php?page=".$_GET['page']);
  }else{
   header("Location:sub-sub-category.php");
   }
 }else{
 header('Location:sub-sub-category.php');
 }
}

/*Sub Sub Category Action Start*/

if($action=='subsubcategoryinactive'){
$id=$_GET['inactive'];
$query=mysql_query("update sub_sub_category set status='inactive' where sscid='$id'");
if($query==true){
$_SESSION['SUCESSMSG']='Status Changed Successfully..';
//header('Location:sub-sub-category.php');
if((isset($_GET['page'])) && (isset($_GET['searchkeyowords']))) { 
  header("Location:sub-sub-category.php?searchkeyowords=".$_GET['searchkeyowords'].'&page='.$_GET['page']);
  }elseif(isset($_GET['searchkeyowords'])) {
  header("Location:sub-sub-category.php?searchkeyowords=".$_GET['searchkeyowords']);
  }elseif(isset($_GET['page'])) {
  header("Location:sub-sub-category.php?page=".$_GET['page']);
  }else{
   header("Location:sub-sub-category.php");
   }
}else{
header('Location:sub-sub-category.php');
}
}
if($action=='subsubcategoryactive'){
$id=$_GET['active'];
$query=mysql_query("update sub_sub_category set status='active' where sscid='$id'");
if($query==true){
$_SESSION['SUCESSMSG']='Status Changed Successfully..';
//header('Location:sub-sub-category.php');
if((isset($_GET['page'])) && (isset($_GET['searchkeyowords']))) { 
  header("Location:sub-sub-category.php?searchkeyowords=".$_GET['searchkeyowords'].'&page='.$_GET['page']);
  }elseif(isset($_GET['searchkeyowords'])) {
  header("Location:sub-sub-category.php?searchkeyowords=".$_GET['searchkeyowords']);
  }elseif(isset($_GET['page'])) {
  header("Location:sub-sub-category.php?page=".$_GET['page']);
  }else{
   header("Location:sub-sub-category.php");
   }
}else{
header('Location:sub-sub-category.php');
}
}
if($action=='subsubcategorydelete'){
$id=$_GET['delete'];
$query=mysql_query("delete from sub_sub_category where sscid='$id'");
if($query==true){
$_SESSION['SUCESSMSG']='Deleted Successfully..';
//header('Location:sub-sub-category.php');
if((isset($_GET['page'])) && (isset($_GET['searchkeyowords']))) { 
  header("Location:sub-sub-category.php?searchkeyowords=".$_GET['searchkeyowords'].'&page='.$_GET['page']);
  }elseif(isset($_GET['searchkeyowords'])) {
  header("Location:sub-sub-category.php?searchkeyowords=".$_GET['searchkeyowords']);
  }elseif(isset($_GET['page'])) {
  header("Location:sub-sub-category.php?page=".$_GET['page']);
  }else{
   header("Location:sub-sub-category.php");
   }
}else{
header('Location:sub-sub-category.php');
}
}


/*End Sub Sub Category Action*/
if($action=='InsertMyImages'){
    $userid=$_SESSION['ADMIN_ID'];
$maincategories=mysql_query("select *from image where amid='$userid' order by pid desc");
$countrows=mysql_num_rows($maincategories);

  $query='';
    $date=date('Y-m-d H:i:s');
	  $images_arr = array();
	  // $totalcount=$usercount+$filecount;
	  $mysql=mysql_query("select *from image");
	  $count=mysql_num_rows($mysql);
	  
   $filecount=count($_FILES['uploadfileone']['name']);
    $totalcount=$count+$filecount;
	if($totalcount<=5){
	  if(!empty($_FILES['uploadfileone']['name'])){
    foreach($_FILES['uploadfileone']['name'] as $key=>$val){
        $target_dir = "images/product/";
         $target_file = $target_dir.$_FILES['uploadfileone']['name'][$key];
             move_uploaded_file($_FILES['uploadfileone']['tmp_name'][$key],$target_file);
			$images_arr[] = $target_file;
        $query=mysql_query("insert into image(image_path,status,amid)values('$val','active',$userid)");
		}
		}
  echo 1;
  exit;
 }else{
  echo 2;
  exit;
 }
}



if($action=='InserServicesTempImages'){
$maincategories=mysql_query("select *from image1 order by pid desc");
$countrows=mysql_num_rows($maincategories);
$userid=$_SESSION['ADMIN_ID'];
  $query='';
    $date=date('Y-m-d H:i:s');
	  $images_arr = array();
	  $mysql=mysql_query("select *from image");
	  $count=mysql_num_rows($mysql);
   $filecount=count($_FILES['uploadfileone']['name']);
    $totalcount=$count+$filecount;
	if($totalcount<=5){
	  if(!empty($_FILES['uploadfileone']['name'])){
    foreach($_FILES['uploadfileone']['name'] as $key=>$val){
        $target_dir = "images/product/";
         $target_file = $target_dir.$_FILES['uploadfileone']['name'][$key];
             move_uploaded_file($_FILES['uploadfileone']['tmp_name'][$key],$target_file);
			$images_arr[] = $target_file;
        $query=mysql_query("insert into image1(image_path,status,amid)values('$val','active',$userid)");
		}
		}
  echo 1;
  exit;
 }else{
  echo 2;
  exit;
 }
}
if($action=="UpdateProductImages"){
//print_r($_POST);
//exit;
$catid=$_POST['productid'];
$title=$_POST['title'];
$description=mysql_real_escape_string($_POST['description']);
$product_additional_description=mysql_real_escape_string($_POST['product_additional_description']);
$price=$_POST['price'];
$discount=$_POST['discount'];
$quntity=$_POST['quntity'];
$vendor_id=$_POST['vendor_id'];
$added_id= $_SESSION['ADMIN_USERNAME'];
$query12= mysql_query("update product_details set title='$title',description='$description',price='$price',product_quantity='$quntity',discount_price='$discount',product_additional_description='$product_additional_description',vendor_id='$vendor_id',added_by='$added_id' where pid='$catid'");
//echo "update product_details set title='$title',description='$description',price='$price',discount_price='$discount' where pid='$catid'";
  $getdata=mysql_query("select *from product_images where pid='".$catid."'"); 
  $i=1; while($rows=mysql_fetch_array($getdata)){ 
     if(!empty($_FILES["myinsertedimages$i"]["name"])){
	    $img1=$_FILES["myinsertedimages$i"]["name"];
    move_uploaded_file($_FILES["myinsertedimages$i"]["tmp_name"], "images/product/" . $_FILES["myinsertedimages$i"]["name"]);
  }else{
    $img1=$rows['product_path'];
  }
  mysql_query("update product_images set product_path='$img1' where piid='".$rows['piid']."'");
  $i++;
  }
  if($query12==true){
   if((isset($_GET['page'])) && (isset($_GET['searchkeyowords']))) { 
  $_SESSION['SUCESSMSG']='Updated Successfully..';
  header("Location:product.php?searchkeyowords=".$_GET['searchkeyowords'].'&page='.$_GET['page']);
  }elseif(isset($_GET['searchkeyowords'])) {
   $_SESSION['SUCESSMSG']='Updated Successfully..';
  header("Location:product.php?searchkeyowords=".$_GET['searchkeyowords']);
  }elseif(isset($_GET['page'])) {
   $_SESSION['SUCESSMSG']='Updated Successfully..';
  header("Location:product.php?page=".$_GET['page']);
  }else{
  $_SESSION['SUCESSMSG']='Updated Successfully..';
  header("Location:product.php");
   }
  }else{
  echo "Not Update";
  }
}

/*Services Pages action*/

if($action=="UpdateServicesImages"){
//print_r($_POST);
//exit;
$catid=$_POST['productid'];
$title=$_POST['title'];
$description=mysql_real_escape_string($_POST['description']);


$query12= mysql_query("update services set title='$title',description='$description' where pid='$catid'");
//echo "update product_details set title='$title',description='$description',price='$price',discount_price='$discount' where pid='$catid'";
  $getdata=mysql_query("select *from product_images1 where pid='".$catid."'"); 
  $i=1; while($rows=mysql_fetch_array($getdata)){ 
     if(!empty($_FILES["myinsertedimages$i"]["name"])){
	    $img1=$_FILES["myinsertedimages$i"]["name"];
    move_uploaded_file($_FILES["myinsertedimages$i"]["tmp_name"], "images/product/" . $_FILES["myinsertedimages$i"]["name"]);
  }else{
    $img1=$rows['product_path'];
  }
  mysql_query("update product_images1 set product_path='$img1' where piid='".$rows['piid']."'");
  $i++;
  }
  if($query12==true){
  $_SESSION['SUCESSMSG']='Updated Successfully..';
  echo 1;
  }else{
  echo "Not Update";
  }
}

/*services pages action end*/

if($action=='InsertTempImages'){
$data='';
$userid=$_SESSION['ADMIN_ID'];
$maincategories=mysql_query("select *from image  where amid='$userid' order by pid desc");
$countrows=mysql_num_rows($maincategories);
                          $data.='';
						    $i=1;
				
						  while($main=mysql_fetch_array($maincategories)){
						 
						 $data.='<div class="col-md-2 img-wrap" style="margin-right:10px;">
						   <span class="close"  onclick="temimagesdelete('.$main['pid'].')">&times;</span>
						 <img src="images/product/'.$main['image_path'].'" class="fileUpload"></div>';
						  $i++; }  
				  $data.='';
			echo $data;
}


if($action=='ViewServiesTempImages'){
$data='';
$maincategories=mysql_query("select *from image1 order by pid desc");
$countrows=mysql_num_rows($maincategories);
                          $data.='';
						    $i=1;
				
						  while($main=mysql_fetch_array($maincategories)){
						 
						 $data.='<div class="col-md-2 img-wrap" style="margin-right:10px;">
						   <span class="close"  onclick="temimagesdelete('.$main['pid'].')">&times;</span>
						 <img src="images/product/'.$main['image_path'].'" class="fileUpload"></div>';
						  $i++; }  
				  $data.='';
			echo $data;
}
if($action=='DeleteImages'){
$id=$_POST['id'];
$query=mysql_query("delete from image where pid='$id'");
if($query==true){
echo 1;exit;
 }else{
 echo 2;exit;
 }
}

if($action=='DeleteImages12'){
$id=$_POST['id'];
$query=mysql_query("delete from image1 where pid='$id'");
if($query==true){
echo 1;exit;
 }else{
 echo 2;exit;
 }
}




/*Sub Sub Serivces Action Start*/

if($action=='servicesactive'){
$id=$_GET['inactive'];
$query=mysql_query("update services set status='inactive' where pid='$id'");
if($query==true){
$_SESSION['SUCESSMSG']='Status Changed Successfully..';
header('Location:services.php');
}else{
header('Location:services.php');
}
}
if($action=='servicesinactive'){
$id=$_GET['active'];
$query=mysql_query("update services set status='active' where pid='$id'");
if($query==true){
$_SESSION['SUCESSMSG']='Status Changed Successfully..';
header('Location:services.php');
}else{
header('Location:services.php');
}
}
if($action=='deleteserviess'){
$id=$_GET['delete'];
$query=mysql_query("delete from services where pid='$id'");
if($query==true){
$_SESSION['SUCESSMSG']='Deleted Successfully..';
header('Location:services.php');
}else{
header('Location:services.php');
}
}



if($action=='servicesactive'){
$id=$_GET['inactive'];
$query=mysql_query("update services set status='inactive' where pid='$id'");
if($query==true){
$_SESSION['SUCESSMSG']='Status Changed Successfully..';
header('Location:services.php');
}else{
header('Location:services.php');
}
}
if($action=='servicesinactive'){
$id=$_GET['active'];
$query=mysql_query("update services set status='active' where pid='$id'");
if($query==true){
$_SESSION['SUCESSMSG']='Status Changed Successfully..';
header('Location:services.php');
}else{
header('Location:services.php');
}
}
if($action=='deleteserviess'){
$id=$_GET['delete'];
$query=mysql_query("delete from services where pid='$id'");
$query=mysql_query("delete from product_images1 where pid='$id'");
if($query==true){
$_SESSION['SUCESSMSG']='Deleted Successfully..';
header('Location:services.php');
}else{
header('Location:services.php');
}
}


/*End Serivces Action*/


if($action=='productinactives'){
$id=$_GET['inactive'];
$query=mysql_query("update product_details set status='inactive' where pid='$id'");
if($query==true){
if((isset($_GET['page'])) && (isset($_GET['searchkeyowords']))) { 
  $_SESSION['SUCESSMSG']='Status Changed Successfully..';
  header("Location:product.php?searchkeyowords=".$_GET['searchkeyowords'].'&page='.$_GET['page']);
  }elseif(isset($_GET['searchkeyowords'])) {
   $_SESSION['SUCESSMSG']='Status Changed Successfully..';
  header("Location:product.php?searchkeyowords=".$_GET['searchkeyowords']);
  }elseif(isset($_GET['page'])) {
   $_SESSION['SUCESSMSG']='Status Changed Successfully..';
  header("Location:product.php?page=".$_GET['page']);
  }else{
  $_SESSION['SUCESSMSG']='Status Changed Successfully..';
  header("Location:product.php");
   }
}else{
header('Location:product.php');
}
}
if($action=='productactivestatus'){
$id=$_GET['active'];
$query=mysql_query("update product_details set status='active' where pid='$id'");
if($query==true){
//$_SESSION['SUCESSMSG']='Status Changed Successfully..';
//header('Location:product.php');
if((isset($_GET['page'])) && (isset($_GET['searchkeyowords']))) { 
  $_SESSION['SUCESSMSG']='Status Changed Successfully..';
  header("Location:product.php?searchkeyowords=".$_GET['searchkeyowords'].'&page='.$_GET['page']);
  }elseif(isset($_GET['searchkeyowords'])) {
   $_SESSION['SUCESSMSG']='Status Changed Successfully..';
  header("Location:product.php?searchkeyowords=".$_GET['searchkeyowords']);
  }elseif(isset($_GET['page'])) {
   $_SESSION['SUCESSMSG']='Status Changed Successfully..';
  header("Location:product.php?page=".$_GET['page']);
  }else{
  $_SESSION['SUCESSMSG']='Status Changed Successfully..';
  header("Location:product.php");
   }
}else{
header('Location:product.php');
}
}
if($action=='deletemyproducts'){
$id=$_GET['delete'];
$query=mysql_query("delete from product_details where pid='$id'");
$query=mysql_query("delete from product_images where pid='$id'");
if($query==true){
//$_SESSION['SUCESSMSG']='Deleted Successfully..';
//header('Location:product.php');
if((isset($_GET['page'])) && (isset($_GET['searchkeyowords']))) { 
  $_SESSION['SUCESSMSG']='Deleted Successfully..';
  header("Location:product.php?searchkeyowords=".$_GET['searchkeyowords'].'&page='.$_GET['page']);
  }elseif(isset($_GET['searchkeyowords'])) {
   $_SESSION['SUCESSMSG']='Deleted Successfully..';
  header("Location:product.php?searchkeyowords=".$_GET['searchkeyowords']);
  }elseif(isset($_GET['page'])) {
   $_SESSION['SUCESSMSG']='Deleted Successfully..';
  header("Location:product.php?page=".$_GET['page']);
  }else{
  $_SESSION['SUCESSMSG']='Deleted Successfully..';
  header("Location:product.php");
   }
}else{
header('Location:product.php');
}
}
if($action=='confirmorders'){
$id=$_GET['confirm'];
$date=date('Y-m-d H:i:s');
$query=mysql_query("update orders_details set status='confirm',cofirm_date='$date' where oid='$id'");
if($query==true){
//$_SESSION['SUCESSMSG']='Order Is Confirm Successfully..';
//header('Location:new-orders.php');
if(isset($_GET['searchkeyowords'])){
$_SESSION['SUCESSMSG']='Order Is Delivery Successfully..';
header('Location:new-orders.php?searchkeyowords='.$_GET['searchkeyowords']);
}else{
$_SESSION['SUCESSMSG']='Order Is Delivery Successfully..';
header('Location:new-orders.php');
}
}else{
header('Location:new-orders.php');
}
}

if($action=='deliveryorders'){
$id=$_GET['confirm'];
$date=date('Y-m-d H:i:s');
$query=mysql_query("update orders_details set status='delivered',delivery_date='$date' where oid='$id'");
if($query==true){
if(isset($_GET['searchkeyowords'])){
$_SESSION['SUCESSMSG']='Order Is Delivery Successfully..';
header('Location:confirm-orders.php?searchkeyowords='.$_GET['searchkeyowords']);
}else{
$_SESSION['SUCESSMSG']='Order Is Delivery Successfully..';
header('Location:confirm-orders.php');
}
}else{
header('Location:confirm-orders.php');
}
}
if($action=='deletedeliveryorders'){
$oid=$_GET['delete'];
$query=mysql_query("delete from orders_details where oid='$oid'");
mysql_query("delete from order_items_details where oid='$oid'");
if($query==true){
$_SESSION['SUCESSMSG']='Order Deleted Successfully';
header('Location:new-orders.php');
}else{
header('Location:new-orders.php');
}
}



if($action=='deletecanelorders'){
$oid=$_GET['delete'];
$query=mysql_query("delete from orders_details where oid='$oid'");
mysql_query("delete from order_items_details where oid='$oid'");
if($query==true){
$_SESSION['SUCESSMSG']='Order Deleted Successfully';
header('Location:cancel-orders.php');
}else{
header('Location:cancel-orders.php');
}
}
if($action=='deliveryorderedorders'){
 $id=$_GET['confirm'];
$date=date('Y-m-d H:i:s');
$query=mysql_query("update orders_details set status='recived',recived_date='$date' where oid='$id'");
if($query==true){
//$_SESSION['SUCESSMSG']='Order Is Delivery Successfully..';
//header('Location:delivery-orders.php');
if(isset($_GET['searchkeyowords'])){
$_SESSION['SUCESSMSG']='Order Is Delivery Successfully..';
header('Location:delivery-orders.php?searchkeyowords='.$_GET['searchkeyowords']);
}else{
$_SESSION['SUCESSMSG']='Order Is Delivery Successfully..';
header('Location:delivery-orders.php');
}
}else{
header('Location:delivery-orders.php');
}
 }
 if($action=='addpincodemasters'){
		$pincode=mysql_real_escape_string($_POST['pincode']);
		$area_city=mysql_real_escape_string($_POST['area_city']);
		$area_state=mysql_real_escape_string($_POST['area_state']);
		$area_country=mysql_real_escape_string($_POST['area_country']);
		$area=mysql_real_escape_string($_POST['area']);
		$date=date('Y-m-d H:i:s');
     $duplicatepin=mysql_query("select pincode from pincode_master where pincode='$pincode' limit 0,1");
	 if(mysql_num_rows($duplicatepin)==0){
	 mysql_query("insert into pincode_master(pincode,area_city,area_state,area_country,area,status,date)values('$pincode','$area_city','$area_state','$area_country','$area','active','$date')");
	   $_SESSION['SUCESSMSG']='Pincode Added Successfully..';
			 echo 2;
			 exit;
	    }else{
		echo 3;
		exit;
	 }
 }
 if($action=='updatepincodes'){
        
		$pincode=mysql_real_escape_string($_POST['pincode']);
		$area_city=mysql_real_escape_string($_POST['area_city']);
		if(!empty($_POST['area_state'])){
		$area_state=mysql_real_escape_string($_POST['area_state']);
		}else{ 	$area_state=mysql_real_escape_string($_POST['defaultselectstate']); }
		$area_country=mysql_real_escape_string($_POST['area_country']);
		$area=mysql_real_escape_string($_POST['area']);
		$pmid=$_POST['pmid'];
		$duplicatepin1=mysql_query("select pincode from pincode_master where pmid='$pmid' and pincode='$pincode' limit 0,1");
		  $duplicatepin=mysql_query("select pincode from pincode_master where pincode='$pincode' limit 0,1");
		$rows=mysql_fetch_array($duplicatepin1);
		if($rows['pincode']==$pincode){
		mysql_query("update pincode_master set pincode='$pincode',area_city='$area_city',area_state='$area_state',area='$area',area_country='$area_country' where pmid='$pmid'");
		echo 1;
		 $_SESSION['SUCESSMSG']='Pincode Updated Successfully..';
		exit;
		}elseif(mysql_num_rows($duplicatepin)==0){
		mysql_query("update pincode_master set pincode='$pincode',area_city='$area_city',area_state='$area_state',area='$area',area_country='$area_country' where pmid='$pmid'");
		echo 2;
		 $_SESSION['SUCESSMSG']='Pincode Updated Successfully..';
		exit;
		}else{
		echo 3;
		exit;
		}
 }
if($action=='removepincodemasters'){
$id=$_POST['id'];
$query=mysql_query("delete from pincode_master where pmid='$id'");
if($query==true){
echo 1;
 $_SESSION['SUCESSMSG']='Pincode Deleted Successfully..';
exit;
}else{ echo 2; }
}

if($action=='activeusersstatus'){
$id=$_POST['id'];
$query=mysql_query("update registration set status='active' where rid='$id'");
if($query==true){
echo 1;
 $_SESSION['SUCESSMSG']='Status Updated Successfully..';
exit;
}else{ echo 2; }
}

if($action=='inactivestatus'){
$id=$_POST['id'];
$query=mysql_query("update registration set status='inactive' where rid='$id'");
if($query==true){
echo 1;
 $_SESSION['SUCESSMSG']='Status Updated Successfully..';
exit;
}else{ echo 2; }
}

if($action=='removeusersstatus'){
$id=$_POST['id'];
$query=mysql_query("delete from registration where rid='$id'");
if($query==true){
echo 1;
 $_SESSION['SUCESSMSG']='Pincode Deleted Successfully..';
exit;
}else{ echo 2; }
}


if($action=='multipledeletecategory'){
  foreach($_POST['checked_id'] as $val){
  $images=mysql_query("select product_path from product_images where pid='$val'");
  while($img=mysql_fetch_array($images)){
  unlink('images/product/'.$img['product_path']);
  }
  $query=mysql_query("delete from product_details where pid='$val'");
  mysql_query("delete from product_images where pid='$val'");
  }
  if($query==true){
  $_SESSION['SUCESSMSG']='Category Deleted Successfully.';
  //header("Location:product.php");
  if((isset($_GET['page'])) && (isset($_GET['searchkeyowords']))) { 
  header("Location:product.php?searchkeyowords=".$_GET['searchkeyowords'].'&page='.$_GET['page']);
  }elseif(isset($_GET['searchkeyowords'])) {
  header("Location:product.php?searchkeyowords=".$_GET['searchkeyowords']);
  }elseif(isset($_GET['page'])) {
  header("Location:product.php?page=".$_GET['page']);
  }else{
  header("Location:product.php");
   }
  }else{
  echo 'Not Deleted';
  }
}


if($action=='activesubscribeusers'){
$id=$_POST['id'];
$query=mysql_query("update subscribes set status='active' where sid='$id'");
if($query==true){
echo 1;
 $_SESSION['SUCESSMSG']='Status Updated Successfully..';
exit;
}else{ echo 2; }
}

if($action=='inactivesubscribesuser'){
$id=$_POST['id'];
$query=mysql_query("update subscribes set status='inactive' where sid='$id'");
if($query==true){
echo 1;
 $_SESSION['SUCESSMSG']='Status Updated Successfully..';
exit;
}else{ echo 2; }
}
if($action=='removesubscribesuser'){
$id=$_POST['id'];
$query=mysql_query("delete from subscribes where sid='$id'");
if($query==true){
echo 1;
 $_SESSION['SUCESSMSG']='Pincode Deleted Successfully..';
exit;
}else{ echo 2; }
}

if($action=="EmailsSubscriptions"){
$body='';
$message=$_POST['messages'];
$body='<!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>BUYMART TRADE INDIA</title>
    <style>
      img {
        border: none;
        -ms-interpolation-mode: bicubic;
        max-width: 100%; }
      body {
        font-family: sans-serif;
        -webkit-font-smoothing: antialiased;
        font-size: 14px;
        line-height: 1.4;
        margin: 0;
        padding: 0; 
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%; }
      table {
        border-collapse: separate;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
        width: 100%; }
        table td {
          font-family: sans-serif;
          font-size: 14px;
          vertical-align: top; }
      .body {
        width: 100%; }
      .container {
        display: block;
        Margin: 0 auto !important;
        padding: 10px;
        /*width: 1000px;*/
		    width: 90%; }
      .content {
        box-sizing: border-box;
        display: block;
        Margin: 0 auto;
        width: 90%;
        padding: 10px;
		border: 1px solid #0400f3;
		border-radius: 14px; }
      .main {
        background: #fff;
        border-radius: 3px;
        width: 100%; }
      .wrapper {
        box-sizing: border-box;
        padding: 20px; }
      .footer {
        clear: both;
        padding-top: 10px;
        width: 100%; }
        .footer td,
        .footer p,
        .footer span,
        .footer a {
          color:#1d1c1c;
          font-size: 12px;
          text-align: center; }
      h1, h2, h3, h4 {
        color: #000000;
        font-family: sans-serif;
        font-weight: 400;
        line-height: 1.4;
        margin: 0;
        Margin-bottom: 30px; }
      h1 {
        font-size: 35px;
        font-weight: 300;
        text-align: center;
        text-transform: capitalize; }
      p,
      ul,
      ol {
        font-family: sans-serif;
        font-size: 14px;
        font-weight: normal;
        margin: 0;
        Margin-bottom: 15px; }
        p li,
        ul li,
        ol li {
          list-style-position: inside;
          margin-left: 5px; }
      a {
        color: #3498db;
        text-decoration: underline; }
      .btn {
        box-sizing: border-box;
        width: 100%; }
        .btn > tbody > tr > td {
          padding-bottom: 15px; }
        .btn table {
          width: auto; }
        .btn table td {
          background-color: #ffffff;
          border-radius: 5px;
          }
        .btn a {
          background-color: #ffffff;
          border: solid 1px #3498db;
          border-radius: 5px;
          box-sizing: border-box;
          color: #3498db;
          cursor: pointer;
          display: inline-block;
          font-size: 14px;
          font-weight: bold;
          margin: 0;
          padding: 12px 25px;
          text-decoration: none;
          text-transform: capitalize; }
      .btn-primary a {
        background-color: #3498db;
        border-color: #3498db;
        color: #ffffff; }
      .last {
        margin-bottom: 0; }
      .first {
        margin-top: 0; }
      .align-center {
        text-align: center; }
      .align-right {
        text-align: right; }
      .align-left {
        text-align: left; }
      .clear {
        clear: both; }
      .mt0 {
        margin-top: 0; }
      .mb0 {
        margin-bottom: 0; }
      .preheader {
        color: transparent;
        display: none;
        height: 0;
        max-height: 0;
        max-width: 0;
        opacity: 0;
        overflow: hidden;
        mso-hide: all;
        visibility: hidden;
        width: 0; }
      .powered-by a {
        text-decoration: none; }
      hr {
        border: 0;
        border-bottom: 1px solid #f6f6f6;
        Margin: 20px 0; }
      @media only screen and (max-width: 620px) {
        table[class=body] h1 {
          font-size: 28px !important;
          margin-bottom: 10px !important; }
        table[class=body] p,
        table[class=body] ul,
        table[class=body] ol,
        table[class=body] td,
        table[class=body] span,
        table[class=body] a {
          font-size: 16px !important; }
        table[class=body] .wrapper,
        table[class=body] .article {
          padding: 10px !important; }
        table[class=body] .content {
          padding: 0 !important; }
        table[class=body] .container {
          padding: 0 !important;
          width: 100% !important; }
        table[class=body] .main {
          border-left-width: 0 !important;
          border-radius: 0 !important;
          border-right-width: 0 !important; }
        table[class=body] .btn table {
          width: 100% !important; }
        table[class=body] .btn a {
          width: 100% !important; }
        table[class=body] .img-responsive {
          height: auto !important;
          max-width: 100% !important;
          width: auto !important; }}
      @media all {
        .ExternalClass {
          width: 100%; }
        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
          line-height: 100%; }
        .apple-link a {
          color: inherit !important;
          font-family: inherit !important;
          font-size: inherit !important;
          font-weight: inherit !important;
          line-height: inherit !important;
          text-decoration: none !important; } 
        .btn-primary a:hover {
          background-color: #34495e !important;
          border-color: #34495e !important; } }
		  th{text-align: left;}
    </style>
  </head>
  <body class="">
    <table border="0" cellpadding="0" cellspacing="0" class="body">
      <tr>
        <td>&nbsp;</td>
        <td class="container">
          <div class="content">
            <table class="main">

            
              <tr>
                <td class="wrapper">
                  <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>
                        <p><strong>Welcome BUY MART TRADE INDIA,</strong></p>
                        <p>'.$message.'</p>
						<a href="http://buymarttradeindia.com/" class="btn btn-primary">More Details..</a>
						<hr style=" border-bottom: 1px solid #0400f3;">
						<p><strong>Following Users Details,</strong></p>
                       
                      </td>
                      
                  </table>
                </td>
              </tr>
              </table>
            <div class="footer">
              <table border="0" cellpadding="0" cellspacing="0">
                <tr>
				 <td class="content-block">
				 <span class="content-block">
				<h1 style="color: #043971;font-size: 3em;font-weight: 400;font-family:cursive;">BUY MART TRADE INDIA</h1></span>
				 </td>
				    <td> <span class="content-block">
				<strong>BUY MART TRADE INDIA<br>
					Mobile No.: +91 9823336898 / <br />
					E-mail: sales@indiamarttradeindia.com 
			        <br>
				<a href="http://buymarttradeindia.com/">http://buymarttradeindia.com/</a>
				 </span></td>
                  <td class="content-block">
                    <span class="apple-link"><br><br>
					<a href="#" target="_blank"><img src="http://www.wiu.edu/cas/images/facebook_circle_color-512.png" style="height:30px; width:30px;"></a>
					<a  target="_blank" href="#"><img src="http://yolna.com/wp-content/uploads/2015/12/twitter-circle-logo.png" style="height:30px;width:30px;"></a>&nbsp;
					<a href="#" target="_blank"><img src="http://icons.iconarchive.com/icons/martz90/circle/512/google-plus-icon.png" style="height:26px; width:26px;"></a>&nbsp;&nbsp;
					<a href="#" target="_blank"><img src="https://www.stuorg.iastate.edu/uploads/org-site/ckuploads/1419/Social%20Media%20Icons/youtube.png" style="width:27px; height:27px;"></a>
					</span>
                 
                  </td>
                </tr>
               
              </table>
            </div></div>
        </td>
        <td>&nbsp;</td>
      </tr>
    </table>
  </body>
</html>';

           $headers = "MIME-Version: 1.0" . "\r\n";
          $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
          $headers .= 'From:BUYMTI<info@buymarttradeindia.com>' . "\r\n";
		  $mail_subject=mysql_real_escape_string($_POST['subject']); 
		$query=mysql_query("select email from subscribes");
	//	echo "select email from subscribes";
		$emailssends='';
			while($rows=mysql_fetch_array($query)){ 
			$emails=$rows['email'];
			$emailssends=mail($emails,$mail_subject,$body,$headers);
			}
		  if($emailssends==true){
		  $_SESSION['SUCESSMSG']='Subscribe users news letter mail send successfully..';
		}else{
		 echo 'Not Sends Mail';
		}
}

/*Review action page backend functionaltiy start */
if($action=='activereviewusers'){
$id=$_POST['id'];
$query=mysql_query("update product_review set status='active' where prid='$id'");
if($query==true){
echo 1;
 $_SESSION['SUCESSMSG']='Status Updated Successfully..';
exit;
}else{ echo 2; }
}

if($action=='inactivereviewusers'){
$id=$_POST['id'];
$query=mysql_query("update product_review set status='inactive' where prid='$id'");
if($query==true){
echo 1;
 $_SESSION['SUCESSMSG']='Status Updated Successfully..';
exit;
}else{ echo 2; }
}
if($action=='removereviewusers'){
$id=$_POST['id'];
$query=mysql_query("delete from product_review where prid='$id'");
if($query==true){
echo 1;
 $_SESSION['SUCESSMSG']='Pincode Deleted Successfully..';
exit;
}else{ echo 2; }
}
/*Review action page backend functionaltiy end */
if($action=='update_mynotifications'){
if($_SESSION['ADMIN_TYPE']=='Employee'){
 $emp_id=$_SESSION['ADMIN_ID'];
mysql_query("update notification_master set status='inactive' where status='active' and emp_id='$emp_id'");
}elseif($_SESSION['ADMIN_TYPE']=='Admin'){
mysql_query("update notification_master set status='inactive' where status='active' and admin_id='Admin'");    
}elseif($_SESSION['ADMIN_TYPE']=='Vendors'){
$emp_id=$_SESSION['ADMIN_ID'];
mysql_query("update notification_master set status='inactive' where status='active' and vendor_id='$emp_id'");    
 }
}

if($action=='returnspaymentprocess'){
$date=date('Y-m-d H:i:s');
$id=$_POST['id'];
$query=mysql_query("update product_return set status='active',retrun_confrim_date='$date' where oid='$id'");
if($query==true){
$_SESSION['SUCESSMSG']='Pincode Deleted Successfully..';
echo 1;
exit;
}else{
echo 2;
exit;
}
}
if($action=='removenotifications'){
$id=$_POST['id'];
$query=mysql_query("delete from notification_master where nid='$id'");
if($query==true){
echo 1;
$_SESSION['SUCESSMSG']='Notification Deleted Successfully..';
exit;
}else{ echo 2; }
}

 if($action=='change-password'){
$oldPassword=$_POST['oldPassword'];
$newPassword=$_POST['newPassword'];
 $tid= $_SESSION['ADMIN_ID'];
 
 $checkpassword=mysql_query("select password from registration where rid='$tid'");
 $get_rows=mysql_fetch_array($checkpassword);
 if($oldPassword==$get_rows['password']){
 mysql_query("update registration set password='$newPassword' where rid='$tid'");
 $_SESSION['SUCESSMSG']='Password Changed Successfully';
 echo 1;
 exit;
 }else{
 echo 2;
 exit;
 }
 }
 
 if($action=='assign_orders_to_employee_details'){
   $employee_assign=$_POST['employee_assign'];  
  if(!empty($_POST['checked_id'])){ 
  foreach($_POST['checked_id'] as $val){
    $enquiry_id=$val;
   $id=mysql_query("select *from product_enquiry where id='$val'");
   $qu=mysql_fetch_array($id);
   $userid=$qu['userid'];
   $notification_message='This customer enquiry assign for you';
   mysql_query("insert into notification_master(notification_message,oid,uid,status,notification_date,emp_id)values('$notification_message','$val','$userid','active','".date('Y-m-d H:i:s')."','$employee_assign')");
   $images=mysql_query("update product_enquiry set status='Inprogress',employee_id='$employee_assign' where id='$val'");
  }
  $_SESSION['SUCESSMSG']='Enquiry assign to employee successfully..';
  header('Location:product_enquiry.php');
  }else{
    $_SESSION['ERRORMSG']='Please select atleast one enquiry order';
  header('Location:product_enquiry.php');  
  }
 }
 if($action=='cancel_enquiry_orders'){
   $cancel_orders=base64_decode($_GET['cancel_orders']);
   $query=mysql_query("update product_enquiry set cancel_date='".date('Y-m-d H:i:s')."',status='Cancel' where id='$cancel_orders'");
   if($query==true){
    $_SESSION['SUCESSMSG']='Orders Enquiry canceled successfully';
  header('Location:product_enquiry_inprogress.php');    
   }else{
   header('Location:product_enquiry_inprogress.php');     
   }
   
 }
 if($action=='send_enquiry_for_seller'){
   $send_orders=base64_decode($_GET['send_orders']);
   $vendor_id= base64_decode($_GET['seller_name']);
   $userid=$_SESSION['ADMIN_ID'];
   $order_assign_seller_by= $_SESSION['ADMIN_USERNAME'];
   $product=mysql_query("select userid from product_enquiry where id='$send_orders'");
   $customer_id=mysql_fetch_array($product);
   
   $u_id=$customer_id['userid'];
   $query=mysql_query("update product_enquiry set assign_vendor_id='$vendor_id',employee_id='$userid',order_assign_seller_by='$order_assign_seller_by' where id='$send_orders'");
   mysql_query("insert into notification_master(notification_message,oid,uid,status,notification_date,vendor_id)values('New Enquiry Orders','$send_orders','$u_id','active','".date('Y-m-d H:i:s')."','$vendor_id')");
   if($query==true){
    $_SESSION['SUCESSMSG']='Order Enquiry has been send to seller successfully.';
   header('Location:product_enquiry_inprogress.php');    
   }else{
   header('Location:product_enquiry_inprogress.php');     
   }   
 }
 if($action=='delivered_orders_for_customer'){
    $product_id= base64_decode($_GET['product_id']);
    $delivers= base64_decode($_GET['delivers']);
    $product_qty=mysql_query("select product_quantity from product_details where pid='$product_id'");
    $product_enq=mysql_query("select quantity from product_enquiry where id='$delivers'");
    $enq=mysql_fetch_array($product_enq);
    $fetch_row=mysql_fetch_array($product_qty);
    if($enq['quantity']<=$fetch_row['product_quantity']){
        $qty=$enq['quantity'];
     mysql_query("update product_details set product_quantity=product_quantity-$qty where pid='$product_id'");
      $query=mysql_query("update product_enquiry set delivery_date='".date('Y-m-d H:i:s')."',status='Completed' where id='$delivers'");
     $_SESSION['SUCESSMSG']='Order Enquiry has been send to seller successfully.';
     header('Location:product_enquiry_inprogress.php');    
    }else{
      $_SESSION['ERRORMSG']='Currently This Product not available quantity. Please update this product quantity';   
    }
  }
  if($action=='userregistration'){
$name=mysql_real_escape_string($_POST['name']);
$mobile=mysql_real_escape_string($_POST['mobile']);
$email=mysql_real_escape_string($_POST['email']);
$password=mysql_real_escape_string($_POST['password']);
//$refferal_id=mysql_real_escape_string($_POST['refferal_id']);
$random = mt_rand(100000, 999999);
   $date=date('Y-m-d H:i:s');
$query=mysql_query("insert into registration(name,email,mobile,password,status,date,user_type)values('$name','$email','$mobile','$password','active','$date','Employee')");
$uid=mysql_insert_id();
$otp = mt_rand(100000, 999999);

if($query==true){
$_SESSION['SUCESSMSG']='Your Registration Successfully and Please enter OTP Here';
header("Location:employee.php");
}else{
echo 'not insert';
}
  }
if($action=='update_registration_employee_data'){
    $name=mysql_real_escape_string($_POST['name']);
$mobile=mysql_real_escape_string($_POST['mobile']);
$email=mysql_real_escape_string($_POST['email']);
$password=mysql_real_escape_string($_POST['password']);
$rid=$_POST['rid'];
$query=mysql_query("update registration set name='$name',email='$email',mobile='$mobile',password='$password' where rid='$rid'");
if($query==true){
$_SESSION['SUCESSMSG']='Employee data updated successfully..';
header("Location:employee.php");
}else{
echo 'not insert';
}
}
if($action=='mobile_vendors_duplication_duplication'){
$mobile=mysql_real_escape_string($_POST['mobile']);
$rid=$_POST['rid'];
$query=mysql_query("select mobile from registration where rid<>'$rid' and mobile='$mobile'");
$count=mysql_num_rows($query);
if($count==0){
 echo 1;
 }else{
 echo 2;
 }
}

if($action=='duplicateemailaddress1'){
$email=mysql_real_escape_string($_POST['email']);
$rid=$_POST['rid'];
$query=mysql_query("select email from registration where rid<>'$rid' and email='$email'");
$count=mysql_num_rows($query);
if($count==0){
 echo 1;
 }else{
 echo 2;
 }
}
if($action=='UpdateMyProfiles'){
    $userid=$_SESSION['ADMIN_ID'];
$shop_licence_no=mysql_real_escape_string($_POST['shop_licence_no']);
$name=mysql_real_escape_string($_POST['name']);
$mobile=mysql_real_escape_string($_POST['mobile']);
$vendor_store_name=mysql_real_escape_string($_POST['vendor_store_name']);
$city_name=mysql_real_escape_string($_POST['city_name']);
$cst_no=mysql_real_escape_string($_POST['cst_no']);
$state_name=mysql_real_escape_string($_POST['state_name']);
$Address=mysql_real_escape_string($_POST['Address']);
$duplicate=mysql_query("select mobile from registration where mobile='$mobile' and rid<>'$userid'");
$count=mysql_num_rows($duplicate);

if($count==0){
    mysql_query("update registration set shop_licence_no='$shop_licence_no', name='$name',mobile='$mobile',cst_no='$cst_no',vendor_store_name='$vendor_store_name',city_name='$city_name',state_name='$state_name',Address='$Address' where rid='$userid'");
    $_SESSION['SUCESSMSG']='Profile Updated Successfully..';
  echo 1; exit;  
}else{
 echo 2; exit;   
}
}
?>
