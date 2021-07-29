<?php
// Open this url on browger http://localhost:8888/PHP_Scraping/scrayping/AIST/coordinator_scrayping.php
require_once("../../phpQuery-onefile.php");

$root = "https://www.aist.go.jp";

$url_list = array("つくばセンター" => "https://www.aist.go.jp/aist_j/collab/coordinator/tsukuba.html",
                  "地域センター" => "https://www.aist.go.jp/aist_j/collab/coordinator/chiiki.html",
                  "中小企業連携" => "https://www.aist.go.jp/aist_j/collab/scet/index.html");


$csv_path = "../../csv/AIST/coordinator.csv";
$f = fopen($csv_path , "w");

$item = array("分類", "部門","役職","氏名", "専門分野");
fputcsv($f, $item);

foreach($url_list as $key => $value){
  $counter = 0;
  $class = $key;
  $url = $value;

  $html = file_get_contents($url);
  $dom = phpQuery::newDocument($html);

  $contentbox = $dom->find('#contentWrap');

  //コメントアウトの削除
  preg_match_all('@<!--[\s\S]*?-->@', $contentbox, $array);
  $contentbox = str_replace($array[0], '', $contentbox);
  $contentbox = pq($contentbox);

  foreach($contentbox->find(".contents") as $content){
    $content = pq($content);
    if($content->find('h2') != ""){
      // 部門　取得
      $division = $content->find('h2')->text();
      $division  = str_replace(array("\r\n", "\r", "\n"), '', $division);

      // 個人情報　取得
      foreach($content->find(".coordinatorProfile") as $individual_box){
        $individual_box = pq($individual_box);

       //　名前　取得
       $name = $individual_box->find('h3')->text();

       //echo $individual_box;

       preg_match('@<h4>専門分野</h4>[\s\S]*?<div@', $individual_box, $array);
       $specialty = $array[0];
       $specialty = str_replace(array("<div","</div>"), '', $specialty);
       $specialty = phpQuery::newDocument($specialty);
       $specialty = $specialty->find('li')->text();
       $specialty = str_replace(array("\r\n", "\r", "\n"), ',', $specialty);
       $specialty = rtrim($specialty, ",");
       echo $specialty . '<br>';
       fputcsv($f, array($class, $division, "", $name, $specialty));
      }
    }

  }
}

?>
