<?php


// Database Connection
$db_connect = mysql_connect("XXX.XXX.XXX.XXX","XXX","XXX");
if (!$db_connect)
  {
  die('Could not connect: ' . mysql_error());
  }

$db_select = mysql_select_db('geonamesearch', $db_connect);
if (!$db_select) {
	    die ('Could not use: ' . mysql_error());
	}
	
$search_str = mysql_real_escape_string($_POST["query"]);
$search_str_t = str_replace(',', '', $search_str);
$search_arr = explode(' ',$search_str_t);

$s_query = 0;
if($search_arr[count($search_arr)-1] == ''){
	 	array_pop($search_arr);
		if(count($search_arr) == 1)$s_query = 1;
	}
$search_city_end = count($search_arr);

$search_postal="";
$search_state="";
$search_city="";

if(count($search_arr) > 0){
	if(is_numeric($search_arr[count($search_arr)-1])){
		$search_postal = $search_arr[count($search_arr)-1];
		$search_city_end--;
			if(count($search_arr) > 1 && strlen($search_arr[count($search_arr)-2]) <= 2 && strlen($search_arr[count($search_arr)-2]) > 0){
				$search_state = $search_arr[count($search_arr)-2];
				$search_city_end--;
			}
		}
	else if(count($search_arr) > 1 && strlen($search_arr[count($search_arr)-1]) <= 2 && strlen($search_arr[count($search_arr)-1]) > 0){
		$search_state = $search_arr[count($search_arr)-1];
		$search_city_end--;
	}
	if($search_city_end > 0){
		for($i=0; $i<$search_city_end;$i++){
			$search_city .= $search_arr[$i];
			if($i<$search_city_end-1) $search_city .= " ";
		}
	}
}

if(!$s_query) $search_city .= "%";


//$sql_string = "SELECT * FROM geozip WHERE place_name LIKE '$search_city%' AND admin_code1 LIKE '$search_state%' AND postal_code LIKE '$search_postal%' AND country_code='US' LIMIT 5";

$sql_string = "SELECT * FROM geozip WHERE ((place_name LIKE '".$search_city." ".$search_state."%') OR (place_name LIKE '$search_city' AND admin_code1 LIKE '$search_state%')) AND postal_code LIKE '$search_postal%' AND country_code='US' ORDER BY place_name ASC LIMIT 5";
	
$result = mysql_query($sql_string);

$places = array();

if (!result){
	echo "No results.";
}
else{
	while($row=mysql_fetch_assoc($result)){
		array_push($places,$row);
	}
	echo json_encode($places);
}



mysql_close($db_connect);
?>