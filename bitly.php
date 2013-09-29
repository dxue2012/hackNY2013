<?php 

$num_of_articles = $_REQUEST['num_of_articles'];

function getnews($nofarticle) {
    $appkey = "74d2a5411e162025164e146920413e92b8b65b45";
	$bitly_api = 'https://api-ssl.bitly.com/v3/highvalue?access_token='.$appkey.'&limit='.$nofarticle;
    $response = file_get_contents($bitly_api);
	$data = json_decode($response, TRUE);
	foreach($data['data']['values'] as $item) {
		print $item;
	}
	
	return $response;
}

echo getnews($num_of_articles);

?>	