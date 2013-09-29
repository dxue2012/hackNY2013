<?php 

$cat1 = $_REQUEST['cat1'];
$cat2 = $_REQUEST['cat2'];

function getnews($nofarticle) {
    $appkey = "c2633a3d944ea8e2372ba7d40389d8a80b0bae86";
	$ncat1 = 4;
	$ncat2 = 4;
	$nhv = 5;
	
	$search1_api = 'https://api-ssl.bitly.com/v3/search?access_token='.$appkey.'&query='.$cat1.'&limit='.$ncat1.'&fields=aggregate_link%2Ctitle%2Ccontent';
    echo "<br>Search API: ".$search1_api."<br>";
	$sr1 = file_get_contents($search1_api);
	$sr1_data = json_decode($sr1, TRUE);
	$response = "{ \"data\" : [ ";
	if( strcmp( $sr1_data['status_txt'], 'OK' )  == 0 ){
		foreach($sr1_data['data']['results'] as $item) {
			$response = $response."{ \"aggregate_link\" : \"".$item['aggregate_link']."\", \"title\" : \"".$item['title']."\", \"content\" : \"".$item['content']."\" }, ";
		}	
	}
	
	$search2_api = 'https://api-ssl.bitly.com/v3/search?access_token='.$appkey.'&query='.$cat2.'&limit='.$ncat2.'&fields=aggregate_link%2Ctitle%2Ccontent';
    $sr2 = file_get_contents($search2_api);
	$sr2_data = json_decode($sr2, TRUE);
	if( strcmp( $sr2_data['status_txt'], 'OK' )  == 0 ){
		foreach($sr2_data['data']['results'] as $item) {
			$response = $response."{ \"aggregate_link\" : \"".$item['aggregate_link']."\", \"title\" : \"".$item['title']."\", \"content\" : \"".$item['content']."\" }, ";
		}	
	}
	
	$hv_api = 'https://api-ssl.bitly.com/v3/highvalue?access_token='.$appkey.'&limit='.$nhv;
    $hv = file_get_contents($hv_api);
	$data = json_decode($hv, TRUE);
	
	foreach($data['data']['values'] as $item) {
		
		$content_api = 'https://api-ssl.bitly.com/v3/link/content?access_token='.$appkey.'&link='.urlencode($item);
		$c = file_get_contents($content_api);
		$content = json_decode($c, TRUE);
		//print_r($content);
		if( strcmp( $content['status_txt'], 'OK' )  == 0 ){
				
			$response = $response."{ \"aggregate_link\" : \"".$item."\", ";
			
			$info_api = 'https://api-ssl.bitly.com/v3/link/info?access_token='.$appkey.'&link='.urlencode($item);
			$i = file_get_contents($info_api);
			$info = json_decode($i, TRUE);
			
			
			$response = $response."\"title\" : \"".$info['data']['html_title']."\", ";
			//print $content['data']['content']."<br>";
			
			$notag = strip_tags($content['data']['content']);
			$s = preg_replace("/\s+/", " ", $notag);
			
			//Remove duplicate newlines
			//$s = preg_replace("/[\n]*/", "\n", $notag); 
			//Preserves newlines while replacing the other whitspaces with single space
			//$s = preg_replace("/[\t]*/", " ", $s); 
			
			$s = str_replace('"', '\"', $s);
			
			$response = $response."\"content\" : \"".$s."\"";
			
			//echo $info_api;
			//echo $content_api;
			//print_r( $info);
			//print_r( $content);
			//print "<br>_______________________________________________________<br>";
			$response = $response." },";
		}
	}
	
	$response = substr($response, 0, -1);
	$response = $response." ] }";
	return $response;
}

echo getnews(10);

?>	