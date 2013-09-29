<?php 

function getnews($nofarticle) {
    $appkey = "74d2a5411e162025164e146920413e92b8b65b45";
	$bitly_api = 'https://api-ssl.bitly.com/v3/highvalue?access_token='.$appkey.'&limit='.$nofarticle;
    $response = file_get_contents($bitly_api);
    return $response;
}

echo getnews(5);

?>	
