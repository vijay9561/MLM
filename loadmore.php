<?php include('config.php'); ?>
<?php
    if(isset($_POST['page'])):
    $paged=$_POST['page'];
	$details=$_POST['productdetailsid'];
	$resultsPerPage=20;
    $sql="SELECT * FROM `product_review` where pid='".$details."' and status='active' ORDER BY `uid` ASC";
    if($paged>0){
           $page_limit=$resultsPerPage*($paged-1);
           $pagination_sql=" LIMIT  $page_limit, $resultsPerPage";
           }
    else{
    $pagination_sql=" LIMIT 0 , $resultsPerPage";
    }
    $result=mysql_query($sql.$pagination_sql);
    $num_rows = mysql_num_rows($result);
    if($num_rows>0){
    while($data=mysql_fetch_array($result)){
	   $current=mysql_query("select name from registration where rid='".$data['uid']."'"); 
	   $u=mysql_fetch_array($current);
   // $title=$data['name'];
  //  $content=$data['comment'];
	?>
<strong style="color:#000000;"><?php echo $u['name']; ?></strong>
<p> <?php echo $data['description']; ?></p>
<p>Reviews On <?php echo $recived_date=date('D-F-j-Y', strtotime($data['date'])); ?> </p>
	<?php 
    }
    }
    if($num_rows == $resultsPerPage){?>
     <li class="loadbutton"><button class="loadmore hvr-skew-backward" data-page="<?php echo  $paged+1 ;?>">Load More</button></li>
 <?php
  }else{
    echo "<li>No Found More Review</li>";
 }
 endif;
 ?>