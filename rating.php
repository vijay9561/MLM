<?php
include_once 'dbConfig.php';
if(!empty($_POST['ratingPoints'])){
    $postID = $_POST['postID'];
    $ratingNum = 1;
    $ratingPoints = $_POST['ratingPoints'];
	 $ratingPoints12 = $_POST['ratingPoints'];
    
    //Check the rating row with same post ID
    $prevRatingQuery = "SELECT * FROM feedback_rating WHERE uid = ".$postID;
    $prevRatingResult = $db->query($prevRatingQuery);
    if($prevRatingResult->num_rows > 0):
        $prevRatingRow = $prevRatingResult->fetch_assoc();
        $ratingNum = $prevRatingRow['rating_number'] + $ratingNum;
        $ratingPoints = $prevRatingRow['total_points'] + $ratingPoints;
        //Update rating data into the database
        $query = "UPDATE feedback_rating SET rating_number = '".$ratingNum."', total_points = '".$ratingPoints."', modified = '".date("Y-m-d H:i:s")."' WHERE uid = ".$postID;
        $update = $db->query($query);
		 $ratings="INSERT INTO rating_star(uid,rating_star)VALUES('".$postID."','".$ratingPoints12."')";
		 $db->query($ratings);
    else:
        //Insert rating data into the database
        $query = "INSERT INTO feedback_rating (uid,rating_number,total_points,created,modified) VALUES(".$postID.",'".$ratingNum."','".$ratingPoints."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
        $insert = $db->query($query);
		$ratings="INSERT INTO rating_star(uid,rating_star)VALUES('".$postID."','".$ratingPoints12."')";
		 $db->query($ratings);
    endif;
    
    //Fetch rating deatails from database
    $query2 = "SELECT rating_number, FORMAT((total_points / rating_number),1) as average_rating FROM feedback_rating WHERE uid = ".$postID." AND status = 1";
    $result = $db->query($query2);
    $ratingRow = $result->fetch_assoc();
    
    if($ratingRow){
        $ratingRow['status'] = 'ok';
    }else{
        $ratingRow['status'] = 'err';
    }
    
    //Return json formatted rating data
    echo json_encode($ratingRow);
}
?>