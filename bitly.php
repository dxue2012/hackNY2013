<?php 

$num_of_articles = $_REQUEST['num_of_articles'];

function getnews($nofarticle) {
    $appkey = "74d2a5411e162025164e146920413e92b8b65b45";
	$hv_api = 'https://api-ssl.bitly.com/v3/highvalue?access_token='.$appkey.'&limit='.$nofarticle;
    $hv = file_get_contents($hv_api);
	$data = json_decode($hv, TRUE);
	$response = "{ \"data\" : [ ";
	
	foreach($data['data']['values'] as $item) {
		
		$content_api = 'https://api-ssl.bitly.com/v3/link/content?access_token='.$appkey.'&link='.urlencode($item);
		$c = file_get_contents($content_api);
		$content = json_decode($c, TRUE);
		if( strcmp( $content['status_txt'], 'OK' )  == 0 ){
				
			$response = $response."{ \"bitly_link\" : \"".$item."\", ";
			
			$info_api = 'https://api-ssl.bitly.com/v3/link/info?access_token='.$appkey.'&link='.urlencode($item);
			$i = file_get_contents($info_api);
			$info = json_decode($i, TRUE);
			
			
			$response = $response."\"title\" : \"".$info['data']['html_title']."\", ";
			//print $content['data']['content']."<br>";
			
			$notag = strip_tags($content['data']['content']);
			$s = preg_replace("/\s+/", " ", $notag);
			
			$response = $response."\"content\" : \"".$s."\"";
			
			//echo $info_api;
			//echo $content_api;
			print_r( $info);
			print_r( $content);
			//print "<br>_______________________________________________________<br>";
			$response = $response." },";
		}
	}
	
	$response = substr($response, 0, -1);
	$response = $response." ] }";
	return $response;
}

echo getnews($num_of_articles);

?>	