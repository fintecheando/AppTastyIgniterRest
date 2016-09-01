<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$conn = new mysqli("localhost", "root", "ZlHF6A7KEGWN", "lozcar");

	$till=10;
	
	if(isset($_GET["till"]) && !empty($_GET["till"]) ){
		$till = $_GET["till"];
		$till = $conn->real_escape_string($till);
	}

	//$query="SELECT p_id,p_name,p_description,p_image_id,p_price FROM products where p_id<=".$till." and p_available=1 ";
        $query="SELECT menu_id AS p_id,menu_name AS p_name,menu_description AS p_description,CONCAT('http://lozcar.bitnamiapp.com/assets/images/',menu_photo) AS p_image_id,menu_price AS p_price FROM sqfnjcd9v_menus where menu_status=1  ";


	if(isset($_GET["category"]) && !empty($_GET["category"]) ){
		
		$cat = $_GET["category"];
		$cat = stripslashes($cat);
		$cat = $conn->real_escape_string($cat);
		$query=$query."and menu_category_id like ".$cat." ";
		
	}
	
	if( isset($_GET["sort"]) && !empty($_GET["sort"]) ){
		
		$s = $_GET["sort"];
		if($s=="n"){	$query.="order by menu_name";}
		else if($s=="plh"){	$query.="order by menu_price";}
		else if($s=="phl"){	$query.="order by menu_price desc";}
	}

	
	

$result = $conn->query($query);
$outp = "";
while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "") {$outp .= ",";}
    $outp .= '{"p_id":"'  . $rs["p_id"] . '",';
    $outp .= '"p_name":"'   . $rs["p_name"]        . '",';
	$outp .= '"p_description":"'   . $rs["p_description"]        . '",';
	$outp .= '"p_image_id":"'   . $rs["p_image_id"]        . '",';
	$outp .= '"p_price":"'. $rs["p_price"]     . '"}';
}


// Adding has more
$result=$conn->query("SELECT count(*) as total from sqfnjcd9v_menus");
$data=$result->fetch_array(MYSQLI_ASSOC);
$total = $data['total'];

if(($total-$till)>0){$has_more=$total-$till;}
			    else{$has_more=0;}
			
				
	$outp ='{"has_more":'.$has_more.',"records":['.$outp.']}';

	
$conn->close();

echo($outp);
?> 