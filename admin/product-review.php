<?php include('header.php'); 
		if(isset($_SESSION['JOBPORTALADMIN'])) {
		
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
$query="select pr.prid,pr.uid,pr.pid,pr.description,pr.status,pr.date,p.title from product_review pr inner join product_details p on p.pid=pr.pid where p.title LIKE '%".$searchid."%' order by pr.prid desc LIMIT ".$pageLimit." , ".$setLimit;
$mysqluery=mysql_query($query);
}else{
$query="select pr.prid,pr.uid,pr.pid,pr.description,pr.status,pr.date,p.title from product_review pr inner join product_details p on p.pid=pr.pid order by pr.prid desc LIMIT ".$pageLimit." , ".$setLimit;
$mysqluery=mysql_query($query);
}
		 ?>

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
  <div class="row">
    <ol class="breadcrumb">
      <li><a href="index.php">
        <svg class="glyph stroked home">
          <use xlink:href="#stroked-home"></use>
        </svg>
        </a></li>
      <li class="active">Product Review</li>
    </ol>
  </div>
  <!--/.row-->
  <div class="row">
    <div class="col-lg-12">
     <!-- <h1 class="page-header">Product Review</h1>-->
      <?php if(isset($_SESSION['SUCESSMSG'])){ ?>
      <div class="alert bg-success" role="alert">
        <svg class="glyph stroked checkmark">
          <use xlink:href="#stroked-checkmark"></use>
        </svg>
        <?php echo $_SESSION['SUCESSMSG']; ?><a href="#" class="pull-right"><span class="glyphicon glyphicon-remove"></span></a> </div>
      <?php unset($_SESSION['SUCESSMSG']); } ?>
    </div>
  </div>
  <!--/.row-->
  <?php if(isset($_GET['add-new'])){ ?>
  <!-- /.row -->
  <?php }elseif(isset($_GET['action'])){ 
		$query=mysql_query("select  *from pincode_master where pmid='".$_GET['pincodeid']."'"); $pin=mysql_fetch_array($query);
		  ?>
  <!-- /.row -->
  <?php }else{ ?>
  <div class="row">
    <div class="col-lg-12">
      <div class="row">
        <div class="col-md-6">
         	<!--<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#sendnewletters"><span class="glyphicon glyphicon-envelope"></span> Send News Letter</a> -->
        </div>
        <div class="col-md-6">
          <div class="form-group pull-right">
            <form method="post">
              <input type="text" id="search_id" placeholder="Search" value="<?php if(isset($_GET['searchkeyowords'])) { echo $_GET['searchkeyowords']; } ?>" required style="display: initial; width:auto;" title="Type Here" class="form-control">
              &nbsp;&nbsp;
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
              <th>Product Name</th>
              <th>Review Desccription</th>
              <th>Users Name</th>
			   <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if(mysql_num_rows($mysqluery)>=1){ $serial = ($pageLimit * $setLimit) + 1;
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
              <td><?php echo $row['title']; ?></td>
              <td><?php echo $row['description'];  $users=mysql_query("select name from registration where rid='".$row['uid']."'"); $u=mysql_fetch_array($users);?></td>
			  <td><?php echo $u['name']; ?></td>
              <td><?php if($row['status']=='active') { ?>
                <a href="#" onclick="return inactivestatus(<?php echo $row['prid']; ?>)" class="btn btn-success" title="Change Status" ><?php echo $row['status']; ?></a>
                <?php }else{ ?>
                <a href="#" onclick="return activeusersstatus(<?php echo $row['prid']; ?>)" class="btn btn-danger" title="Change Status" ><?php echo $row['status']; ?></a>
                <?php } ?>
              </td>
              <td><a href="#" onclick="return deletepincodes(<?php echo $row['prid']; ?>)" class="btn btn-danger" title="Delete" ><span class="glyphicon glyphicon-trash"></span></a></td>
            </tr>
            <?php $i++; } }else{ ?>
            <tr>
              <td colspan="6"><div class="alert alert-danger">No Record Founds</div></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        <div class="row">
          <div class="col-md-12">
            <?php if(isset($_GET['searchkeyowords'])){ echo reviewsearchmasters($setLimit,$page,$_GET['searchkeyowords']); } else{  echo reviewpagings($setLimit,$page);  } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php } ?>
</div>
<?php include('footer.php'); ?>
<script>
	function deletepincodes(id){
	var con=confirm('are you sure to this remove records !');
	if(con==true){
	$.ajax({
		url: "post.php?action=removereviewusers",
	type: 'POST',
	data: {id:id},
	success: function(data) {
	if(data==1){
	location.reload();
	}else{ alert("Not Deleted")}
	}
	});
	}else{
	}
	}
	function inactivestatus(id){
	var con=confirm('are you sure to the update status !');
	if(con==true){
	$.ajax({
	url: "post.php?action=inactivereviewusers",
	type: 'POST',
	data: {id:id},
	success: function(data) {
	if(data==1){
	location.reload();
	}else{ alert("Not Deleted")}
	}
	});
	}else{
	}
	}
	function activeusersstatus(id){
	var con=confirm('are you sure to the update status !');
	if(con==true){
	$.ajax({
	url: "post.php?action=activereviewusers",
	type: 'POST',
	data: {id:id},
	success: function(data) {
	if(data==1){
	location.reload();
	}else{ alert("Not Deleted")}
	}
	});
	}else{
	}
	}
	function search_result(){
	var search_id=$("#search_id").val();
	var search_id2=search_id.trim();
	if(search_id==""){
	alert("Please enter keywords");
	return false;
	}
	window.location='product-review.php?searchkeyowords='+search_id2;
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
