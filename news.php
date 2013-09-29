<?php 

$cat = $_REQUEST['cat'];

function getnews($cat = "usa") {
    $appkey = "c2633a3d944ea8e2372ba7d40389d8a80b0bae86";
	$ncat = 10;
	
	$search_api = 'https://api-ssl.bitly.com/v3/search?access_token='.$appkey.'&query='.$cat.'&limit='.$ncat.'&fields=aggregate_link%2Ctitle%2Ccontent';
    echo "<br>Search API: ".$search_api."<br>";
	$sr = file_get_contents($search_api);
	$sr_data = json_decode($sr, TRUE);
	$response = "{ \"data\" : [ ";
	if( strcmp( $sr_data['status_txt'], 'OK' )  == 0 ){
		foreach($sr_data['data']['results'] as $item) {
			$response = $response."{ \"aggregate_link\" : \"".$item['aggregate_link']."\", \"title\" : \"".$item['title']."\", \"content\" : \"".$item['content']."\" }, ";
		}	
	}
		
	$response = substr($response, 0, -2);
	$response = $response." ] }";
	return $response;
}

echo getnews($cat);

?>	