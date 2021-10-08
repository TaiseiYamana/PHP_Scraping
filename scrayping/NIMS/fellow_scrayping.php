<?php
// Open this url on browger http://localhost:8888/PHP_Scraping/scrayping/NIMS/fellow_scrayping.php
require_once("../../phpQuery-onefile.php");

$root = "https://www.nims.go.jp";
$url = "https://www.nims.go.jp/nims/fellow.html";


$csv_path = "../../csv/NIMS/fellow.csv";
$f = fopen($csv_path , "w");

$html = file_get_contents($url);
$dom = phpQuery::newDocument($html);

//echo $dom;

$content_box = $dom->find('.maincontents');

$counter1 = 0;
foreach($content_box->find(".docset") as $docset){
  if($counter1 != 0){
  $docset = pq($docset);
  if ($docset->find('h2')->text() != ""){
    $post = $docset->find('h2')->text();
  }

  //echo $docset;


  $counter2 = 0;
  //echo $docset['div'];
  foreach($docset->find('div') as $div){
    if($counter2 % 4 == 3){
      $div = pq($div);
      echo $counter2 . '</br>' . $div;
      $infomation = $div->find('.article')->text();
      $infomation = str_replace(array("\r\n", "\r", "\n"," ","　","\t","\0","\v"), '', $infomation);
      $name = $div->find('h3')->text();
      fputcsv($f, array($post, $name, $infomation));

    }
    $counter2++;
  }


  //break;

 }
 $counter1++;
}




?>
