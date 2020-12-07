<?php
function file_get_contents_curl($url){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

  $data = curl_exec($ch);
  curl_close($ch);

  return $data;
}

function getStringBetween($str,$from,$to){
  $sub = substr($str, $from,strlen($str));
  return substr($sub,0,strpos($sub,$to));
}

function DOMinnerHTML($element){ 
  $innerHTML = ""; 
  $children = $element->childNodes; 
  foreach ($children as $child) 
  { 
    $tmp_dom = new DOMDocument(); 
    $tmp_dom->appendChild($tmp_dom->importNode($child, true)); 
    $innerHTML.=trim($tmp_dom->saveHTML()); 
  } 
  return $innerHTML; 
}

$url  = 'https://www.tripadvisor.it/Hotel_Review-g194914-d1494150-Reviews-Hotel_Villa_del_Mare-Senigallia_Province_of_Ancona_Marche.html';
$html = file_get_contents_curl($url);

//parsing begins here:
$doc = new DOMDocument();
@$doc->loadHTML($html);
$div_elements = $doc->getElementsByTagName('div');

if ($div_elements->length <> 0){
  foreach ($div_elements as $div_element) {
    if ($div_element->getAttribute('class') == '_2fxQ4TOx'){
        //$reviews[] = DOMinnerHTML($div_element);
		$reviews_ut[] = getStringBetween($div_element->nodeValue,0,' ');
		$reviews_dt[] = substr($div_element->nodeValue,strpos($div_element->nodeValue,' a ')+2);
    }
    if ($div_element->getAttribute('class') == '_1EpRX7o3'){
		$localita[] = explode(" ",$div_element->nodeValue);
		$reviews_lt[] = $localita[0][0]." ".preg_replace('/[0-9]+/', '', $localita[0][1]);
    }
    if ($div_element->getAttribute('class') == 'glasR4aX'){
		$reviews_tt[] = $div_element->nodeValue;
    }
    if ($div_element->getAttribute('class') == 'cPQsENeY'){
		$reviews_rt[] = $div_element->nodeValue;
    }
    if ($div_element->getAttribute('class') == 'nf9vGX55'){
		$spans=$div_element->getElementsByTagName('span');
		foreach($spans as $span) {
		      $reviews_vt[] = substr($span->getAttribute('class'),24,1);
		    }
    }
  }
}
//print_r($reviews);
echo $reviews_ut[0].$reviews_dt[0].$reviews_lt[0].$reviews_tt[0].$reviews_rt[0].$reviews_vt[0];

?>
