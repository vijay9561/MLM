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


	include_once "function.php";
if(isset($_GET["page"]))
	$page = (int)$_GET["page"];
	else
	$page = 1;

	$setLimit = 20;
	$pageLimit = ($page * $setLimit) - $setLimit;
$query='';
$query="select *from product_enquiry where userid='".$_SESSION['ID']."' order by id desc LIMIT ".$pageLimit." , ".$setLimit;
$mysqluery=mysql_query($query)
?>
<!--banner-->
<style>
p,span{ font-size:14px;word-break: break-all;}
</style>
<div class="banner-top">
  <div class="container" style="text-align: center;margin-top: 102px;">
    <h1>My Orders</h1>
    <em></em>
    <h2><a href="index.php">Home</a>
      <label>/</label>
      My Orders</a></h2>
  </div>
</div>
<script>
function cancelation_reasonr(id){ if($("#cancelation_reason"+id).val()==""){ }else{ $("#cancelation_reasonr"+id).html(' ')} }



function canelation_orders(id){
var  cancelation_reason=$("#cancelation_reason"+id).val();
  if(cancelation_reason==""){
   $("#cancelation_reasonr"+id).html("Please select cancelation reason");
   $("#cancelation_reason"+id).focus();
   return false;
    }
       $("#loading").show(); 
	       var formData = new FormData($("#cancelationrequest"+id)[0]);
			$.ajax({   
				url: "post.php?action=cancel_eqnuiry_details",
				data : formData,
				processData: false,
				contentType: false,
				type: 'POST',
				success: function(data){
					if(data==1){
					    window.location='product_enquiry.php';
						return false;
				     	 }else {
						 $("#loading").hide(); 
						 $("#errormessages"+id).show();
                       $("#errormessages"+id).html("Your Ordered Did'nt Cancelation Because Your Orders Delivered Successfully..");
					 return false;
					}
				}
			});
	return false;

}
</script>
<!--content-->
<div class="product">
  <div class="container">
    <div class="col-md-9">
      <?php if(isset($_SESSION['SUCESSMSG'])) { ?>
      <div class="alert alert-danger"><?php echo $_SESSION['SUCESSMSG']; ?></div>
      <?php unset($_SESSION['SUCESSMSG']); } ?>
	   <?php if(isset($_SESSION['SUCESSMSG1'])) { ?>
      <div class="alert alert-success"><?php echo $_SESSION['SUCESSMSG1']; ?></div>
      <?php unset($_SESSION['SUCESSMSG1']); } ?>
      <div class="row">
        <?php
			 if(mysql_num_rows($mysqluery)>=1){ 
			 $count=1; 
			while($orders=mysql_fetch_array($mysqluery)) { 
                            
                         $order=mysql_query("select *from product_details where pid='".$orders['product_id']."'");
                         $get=mysql_fetch_array($order);
			 ?>
        <div class="row" style="width:100%;">
          <div class="material-card" style="    margin: 12px;">
            <div class="panel-heading">
              <div class="row">
                <div class="col-md-6 col-xs-6"></div>
                <div class="col-md-3"></div>
				<?php  $datedifference=strtotime(date('Y-m-d H:i:s'))-strtotime($orders['delivery_date']);
				            $days  = round($datedifference / 86400);
				          ?>
						
                <?php if($orders['status']=='Inprogress' || $orders['status']=='New Enquiry') { ?>
                <div class="col-md-3 col-xs-6"><a class="hvr-skew-backward" href="#" data-toggle="modal" data-target="#myModal<?php echo $orders['id']; ?>">Cancel  Enquiry<i class="glyphicon glyphicon-remove"></i></a> </div>
                <?php } ?>
              </div>
            </div>
            <div class="panel-body">
			<div class="table-responsive">
			<table class="table table-bordered">
			<thead>
			<tr><th>Images</th><th>Product Name</th><th>Quantity</th><th>Product Status</th><th>Price</th></tr>
			</thead>
			<tbody>
              <?php $totalprices='';  $princcounting=''; 
	
			$product_images=mysql_query("select *from product_images where pid='".$get['pid']."'"); $images=mysql_fetch_array($product_images);
			 ?>
              <tr><td> <img src="admin/images/product/<?php echo $images['product_path']; ?>"  style="height:80px;width:100%" alt="">  </td>
			  <td>
                  <p><strong><?php echo $get['title']; ?></strong></p>
                  <p style="font-size:12px;text-align:justify;">
                    <?php  $string1 = (strlen($get['description'])>40) ? substr($get['description'],0,30).'
  <a href="product-details.php?details='.$get['pid'].'"><p>Read More...</p></a>' : $get['description']; 
						echo $string1;?>
                 </p></td>
				 <td>
             
                  <p><?php echo 1; ?></p>
                </td>
               <td>
                  <?php if($orders['status']=='Cancel'){ ?>
                  <p style="">Caneclation on <i class="glyphicon glyphicon-calendar"></i>&nbsp;&nbsp;<?php echo $deliverydate1=date('d-F-Y', strtotime($orders['cancel_date'])); ?></p>
                  <?php }elseif($orders['status']=='Completed'){
				  ?>
                  <p>Received on <i class="glyphicon glyphicon-calendar"></i>&nbsp;&nbsp;<?php echo $recived_date=date('d-F-Y', strtotime($orders['delivery_date'])); ?></p>
                  <?php  }else{ ?>
                  <p>Delivered Expectation on <i class="glyphicon glyphicon-calendar"></i>&nbsp;&nbsp;<?php echo $deliverydate1=date('d-F-Y', strtotime($orders['created_date']."+1 week")); ?></p>
                  <?php } ?>
                  <?php if($orders['status']=='New Enquiry'){ ?>
                  <p style="font-size:12px; color:#760075;">Your Product enquiry  has been placed </p>
                  <?php }elseif($orders['status']=='Inprogress'){ $cofirm_date=date('d-F-Y', strtotime($orders['created_date'])); ?>	 
                  <p style="font-size:12px; color:#760075;">Your Product enquiry has been  
                    Confirm On <i class="glyphicon glyphicon-calendar"></i>&nbsp;&nbsp;<?php echo $cofirm_date; ?></p>
                  <?php  }elseif($orders['status']=='Cancel'){  $cancelation_date=date('d-F-Y', strtotime($orders['cancel_date']));  ?>
                  <p style="font-size:12px;color:#FF0000;">Your product enquiry has been Canceled <br>
                    Cancelation On <?php echo $cancelation_date; ?></p>
                  <?php }elseif($orders['status']=='Completed'){ 
				    
				  $recived_date=date('d-F-Y', strtotime($orders['delivery_date']));  ?>
                  <p style="font-size:12px; color:#006600;">Your item has been Received <br>
                    Received On <i class="glyphicon glyphicon-calendar"></i>&nbsp;&nbsp;<?php echo $recived_date; ?></p>
					
                  <?php }  ?>
		 
               
              </td>
              <td>
                  <p>Rs.&nbsp;<?php echo $get['discount_price']; ?></p>
              </td></tr>
          
			  </tbody>
			  </table>
			  </div>
            </div>
            <div class="panel-footer">
              <div  class="row">
                <div class="col-md-6  col-xs-6">
                  <?php  $created =new DateTime($orders['created_date']);  //echo $d->format('d-M-Y');?>
                  <span style="color:#999999;">Product Enquiry On:&nbsp;&nbsp;<i class="glyphicon glyphicon-calendar"></i>&nbsp;&nbsp;</span> &nbsp;
                  <?php  echo date('d-F-Y', strtotime($orders['created_date'])); ?>
                </div>
                <div class="col-md-3"></div>
                <div class="col-md-3  col-xs-6"> <span><span style="color:#999999;">Order Total:&nbsp;&nbsp;</span> <?php echo $get['discount_price']; ?></span> </div>
              </div>
            </div>
          </div>
        </div>
        <div id="myModal<?php echo $orders['id']; ?>" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Orders Cancellation Request</h4>
              </div>
              <div class="modal-body">
                <div class="alert alert-danger" id="errormessages<?php echo $orders['id']; ?>" style="display:none;"></div>
                <form method="post" action="#" id="cancelationrequest<?php echo $orders['id']; ?>" enctype="multipart/form-data">
                  <input type="hidden" value="<?php echo $orders['id']; ?>" id="oid<?php echo $orders['id']; ?>" name="oid">
                  <div class="form-group">
                    <label>Reason for cancellation <b style="color:red;">*</b></label>
                    <select class="form-control"  id="cancelation_reason<?php echo $orders['id']; ?>" name="cancelation_reason" onChange="cancelation_reasonr(<?php echo $orders['id']; ?>)">
                      <option value="">--Select Reason--</option>
                      <option value="Expected delivery time is too long">Expected delivery time is too long</option>
                      <option value="Order placed by mistake">Order placed by mistake</option>
                      <option value="Bought it from somewhere else">Bought it from somewhere else</option>
                      <option value="Item Price/shipping cost is too high">Item Price/shipping cost is too high</option>
                      <option value="The delivery is delayed">The delivery is delayed</option>
                      <option value="Need to change shipping address">Need to change shipping address</option>
                      <option value="others">Other</option>
                    </select>
                    <span id="cancelation_reasonr<?php echo $orders['id']; ?>" style="color:red;"></span> </div>
                  <div class="form-group">
                    <label>Comments</label>
                    <textarea id="comment<?php echo $orders['id']; ?>" name="comment" class="form-control" style="resize:none;"></textarea>
                  </div>
                  <input type="submit" class="hvr-skew-backward" value="Submit" onClick="return canelation_orders(<?php echo $orders['id']; ?>)">
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
<?php $count++; } ?>
        <div class="row">
          <div class="col-md-12"> <?php echo product_enquiry_details($setLimit,$page,$_SESSION['ID']); ?> </div>
        </div>
        <?php }else{ ?>
        <div class="col-md-12">
          <div class="alert alert-danger">No Orders Founds</div>
        </div>
        <?php } ?>
      </div class="clearfix">
    </div>
    <div class="col-md-3 product-bottom" style="padding-top:0px;">
      <?php include('my-right-side.php'); ?>
    </div>
    <!--products-->
    <!--//products-->
    <!--brand-->
  </div>
</div>
<script>
function orderreturnprocess(id){
cnt = $("input[name='itemsname[]']:checked").length;

if(cnt>=1){
$("#checkboxid"+id).html(" ");
}else{
$("#checkboxid"+id).html("please select at least one item(s)");
return false;
}
}
function returnordersmess(id){
var  rcomment=$("#rcomment"+id).val();
  if(rcomment==""){
   $("#rcancelation_reason"+id).html("Please enter return reason");
   $("#rcomment"+id).focus();
   return false;
    }else{
	$("#rcancelation_reason"+id).html(" ");
	}
}
function rcanelation_orders(id){
cnt = $("input[name='itemsname[]']:checked").length;
if(cnt>=1){
}else{
$("#checkboxid"+id).html("please select at least one item(s)");
return false;
}
var  rcomment=$("#rcomment"+id).val();
  if(rcomment==""){
   $("#rcancelation_reason"+id).html("Please enter return reason");
   $("#rcomment"+id).focus();
   return false;
    }
       $("#loading").show(); 
	       var formData = new FormData($("#rcancelationrequest"+id)[0]);
			$.ajax({   
				url: "post.php?action=returnordersprocess",
				data : formData,
				processData: false,
				contentType: false,
				type: 'POST',
				success: function(data){
					if(data==1){
					    window.location='my-orders.php';
						return false;
				     	 }else {
						 $("#loading").hide(); 
						 $("#rerrormessages"+id).show();
                       $("#rerrormessages"+id).html("You Have retrun orders within 4 days");
					 return false;
					}
				}
			});
	return false;

}
</script>
<!--<div class="container">
			<div class="brand">
				<div class="col-md-3 brand-grid">
					<img src="images/ic.png" class="img-responsive" alt="">
				</div>
				<div class="col-md-3 brand-grid">
					<img src="images/ic1.png" class="img-responsive" alt="">
				</div>
				<div class="col-md-3 brand-grid">
					<img src="images/ic2.png" class="img-responsive" alt="">
				</div>
				<div class="col-md-3 brand-grid">
					<img src="images/ic3.png" class="img-responsive" alt="">
				</div>
				<div class="clearfix"></div>
			</div>
			</div>-->
<!--//brand-->
<!--//content-->
<!--//footer-->
<?php include('footer.php'); ?>
<?PHP } ?>
