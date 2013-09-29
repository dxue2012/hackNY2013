<?php 

$num_of_articles = $_REQUEST['num_of_articles'];

function getnews($nofarticle) {
    $appkey = "74d2a5411e162025164e146920413e92b8b65b45";
	$hv_api = 'https://api-ssl.bitly.com/v3/highvalue?access_token='.$appkey.'&limit='.$nofarticle;
    $response = file_get_contents($hv_api);
	$data = json_decode($response, TRUE);
	foreach($data['data']['values'] as $item) {
		print $item."<br>";
		$info_api = 'https://api-ssl.bitly.com/v3/link/info?access_token='.$appkey.'&link='.urlencode($item);
		$i = file_get_contents($info_api);
		$info = json_decode($i, TRUE);
		
		print $info['data']['canonical_url']."<br>";
		print $info['data']['html_title']."<br>";
		
		$content_api = 'https://api-ssl.bitly.com/v3/link/content?access_token='.$appkey.'&link='.urlencode($item);
		$c = file_get_contents($content_api);
		$content = json_decode($c, TRUE);
		print $content['data']['content']."<br>";
		
		print_r( $info);
		print_r( $content);
		print "<br>_______________________________________________________<br>";
	}
	
	return $response;
}

echo getnews($num_of_articles);

?>	