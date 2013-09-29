<?php 

function gethvnews($nofarticle) {
    $appkey = "c2633a3d944ea8e2372ba7d40389d8a80b0bae86";
	$hv_api = 'https://api-ssl.bitly.com/v3/highvalue?access_token='.$appkey.'&limit='.$nofarticle;
    //echo "<br>HV API: ".$hv_api."<br>";
	$hv = file_get_contents($hv_api);
	$data = json_decode($hv, TRUE);
	$response = "{ \"data\" : [ ";
	
	foreach($data['data']['values'] as $item) {
		
		$content_api = 'https://api-ssl.bitly.com/v3/link/content?access_token='.$appkey.'&link='.urlencode($item);
		$c = file_get_contents($content_api);
		$content = json_decode($c, TRUE);
		//print_r($content);
		if( strcmp( $content['status_txt'], 'OK' )  == 0 ){
				
			$response = $response."{ \"bitly_link\" : \"".$item."\", ";
			
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

$file = fopen("top.txt", "r");
if(!file)
    {
      echo gethvnews(15);
    }
else
    {
      if (!feof($file)) {
			$cat = trim(fgets($file));
			if(strlen($cat) == 0){
				echo gethvnews(15);
			}
			else{
				echo getnews(urlencode($cat));
			}
		}
		else{
			echo gethvnews(15);
		}
    }
fclose($file);

?>	