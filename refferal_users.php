
<?php
include('header.php');
if(!$_SESSION['ID']){ 
?>
<script>
window.location="index.php";
</script>
<?PHP 
//echo 'Hi';
//exit;
}else{

 
?>
<style>
.profilesimages{height:150px; width:150px;}
@media(max-width:500px){
.profilesimages{height:120px; width:150px;}
}
input[type='file'] {
  color: transparent;
}
</style>

<!--banner-->
<div class="banner-top">
	<div class="container">
		<h1>My Profile</h1>
		<em></em>
		<h2><a href="index.php">Home</a><label>/</label>My Refferal Users</a></h2>
	</div>
</div>
	<!--content-->
		<div class="product">
			<div class="container">
			<div class="col-md-9">
			<?php if(isset($_SESSION['Success'])) { ?><div class="alert alert-success"><?php echo $_SESSION['Success']; ?></div><?php unset($_SESSION['Success']); } 
			  $users=mysql_query("select referral_id from registration where rid='".$_SESSION['ID']."'");
			  $rows=mysql_fetch_array($users);
                          $referral_id=mysql_query("select u.name,u.email,u.mobile,r.refferral_id from referral_users r inner join registration u on u.rid=r.userid where r.refferral_id='".$rows['referral_id']."'");
			?>
			<div class="mid-popular">
				<div class="panel panel-default">
			<div class="panel-heading">MY Refferal Customer</div>
			<div class="panel-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>Referral ID</th>
                                        <th>Name</th>
                                        <th>Email ID</th>
                                        <th>Contact No</th>
                                        
                                    </tr>   
                                </thead>   
                                <tbody>
                                    <?php if(mysql_num_rows($referral_id)>=1){ $i=1; while($row=mysql_fetch_array($referral_id)){ ?>
                                    <tr>
                                     <td><?php echo $i++; ?></td>
                                     <td><?php echo $row['refferral_id']; ?></td>
                                     <td><?php echo $row['name']; ?></td>
                                     <td><?php echo $row['email']; ?></td>
                                     <td><?php echo $row['mobile']; ?></td>
                                    </tr>  
<?php } }else{?>
                                    <tr><td colspan="5"><div class="alert alert-danger">No Any Available your refferal users</div></td></tr>                                 
<?php } ?>                                  
                                </tbody>
                            </table> 
					
                                   </div>           
                                </div>
				</div>

			</div>
		
			<div class="col-md-3 product-bottom"> 
			<?php include('my-right-side.php'); ?>
		</div>
			</div class="clearfix"></div>
			
			</div>
			
		</div>
	<!--//content-->
		<!--//footer-->
	<?php include('footer.php'); ?>
	
	<?php } ?>