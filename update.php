<?php 

$link_clicked = $_REQUEST['link_clicked'];


function update($link_clicked) {
    $appkey = "c2633a3d944ea8e2372ba7d40389d8a80b0bae86";
	$cat_api = 'https://api-ssl.bitly.com/v3/link/category?access_token='.$appkey.'&link='.urlencode($link_clicked);
    $cat = file_get_contents($cat_api);
	$cat_data = json_decode($cat, TRUE);
	
	if( strcmp( $cat_data['status_txt'], 'OK' )  == 0 ){
	
		foreach($cat_data['data']['categories'] as $item) {
			print $item."<br>";
		/*
		if( strcmp( $content['status_txt'], 'OK' )  == 0 ){
				
			$response = $response."{ \"bitly_link\" : \"".$item."\", ";
			
			$info_api = 'https://api-ssl.bitly.com/v3/link/info?access_token='.$appkey.'&link='.urlencode($item);
			$i = file_get_contents($info_api);
			$info = json_decode($i, TRUE);
			
			
			$response = $response."\"title\" : \"".$info['data']['html_title']."\", ";
			//print $content['data']['content']."<br>";
			
			$notag = strip_tags($content['data']['content']);
			$s = preg_replace("/\s+/", " ", $notag);
			
			
			}
			*/
		}
	}	
	
}

update($link_clicked);


?>	