<?PHP
include('config.php');
$searchTerm = $_GET['term'];
    $query = mysql_query("SELECT * FROM product_details WHERE title LIKE '%".$searchTerm."%' ORDER BY title ASC");
    $array = array();
    while ($row = mysql_fetch_array($query)) {
        $array[] = array (
            'id' => $row['pid'],
            'value' => $row['title'],
        );
    }
    echo json_encode($array);
 ?>