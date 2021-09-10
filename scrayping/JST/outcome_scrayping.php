<?php
// Open this url on browger http://localhost:8888/PHP_Scraping/scrayping/JST/outcome_scrayping.php
require_once("../../phpQuery-onefile.php");

$root = "https://www.jst.go.jp/seika/";

$Research_list = array("環境エネルギー" => "https://www.jst.go.jp/seika/seika1.html",
                  "ライフサイエンス" => "https://www.jst.go.jp/seika/seika2.html",
                  "ナノテクノロジー・材料" => "https://www.jst.go.jp/seika/seika3.html",
                  "情報通信" => "https://www.jst.go.jp/seika/seika4.html",
                  "社会技術・社会基盤" => "https://www.jst.go.jp/seika/seika5.html");
$Infrastructure_list = array("研究開発戦略の立案・提言" => "https://www.jst.go.jp/seika/seika10.html",
                            "国際共同研究" => "https://www.jst.go.jp/seika/bt95-96.html",
                            "データベースの構築" => "https://www.jst.go.jp/seika/seika12.html",
                            "未来共創と人材育成" => "https://www.jst.go.jp/seika/seika13.html",
                            "ダイバーシティ推進" => "https://www.jst.go.jp/seika/bt2018-12.html",
                            "SDGsへの貢献" => "https://www.jst.go.jp/seika/bt2019-15.html");


$csv_path = "../../csv/JST/outcome.csv";
$f = fopen($csv_path , "w");

$item = array("事業成果", "年度", "分野", "url","研究者");
fputcsv($f, $item);

foreach($Research_list as $key => $value){
  $filed_name = $key;
  $filed_url = $value;

  $html = file_get_contents($filed_url);
  $dom = phpQuery::newDocument($html);

  $outcome_box = $dom->find(".leftcol_seika");

  #echo $outcome_box . "</br>";


  foreach ($outcome_box->find(".leftcol_inr") as $sub_box){
    $sub_box = pq($sub_box);
    $year = $sub_box->find("h3")->text();
    #echo $sub_box . "</br>";

    foreach ($sub_box->find(".listBoxDesign1_bg.cf") as $list_box ){
        $list_box = pq($list_box);
        $project_name = $list_box->find(".main_ttl")->text();
        $project_url = $root . $list_box->find(".main_ttl")->find("a")->attr("href");

        $project_html = file_get_contents($project_url);
        $project_dom = phpQuery::newDocument($project_html);

        $resercher = $project_dom->find(".leftcol_inr.box_profile.cf")->find("dt")->text();
        fputcsv($f, array("研究開発",$year,$project_name,$project_url, $resercher));
    }
  }


}
foreach($Infrastructure_list as $key => $value){
  $filed_name = $key;
  $filed_url = $value;

  $html = file_get_contents($filed_url);
  $dom = phpQuery::newDocument($html);

  $outcome_box = $dom->find(".leftcol_seika");

  #echo $outcome_box . "</br>";


  foreach ($outcome_box->find(".leftcol_inr") as $sub_box){
    $sub_box = pq($sub_box);
    $year = $sub_box->find("h3")->text();
    #echo $sub_box . "</br>";

    foreach ($sub_box->find(".listBoxDesign1_bg.cf") as $list_box ){
        $list_box = pq($list_box);
        $project_name = $list_box->find(".main_ttl")->text();
        $project_url = $root . $list_box->find(".main_ttl")->find("a")->attr("href");

        $project_html = file_get_contents($project_url);
        $project_dom = phpQuery::newDocument($project_html);

        $resercher = $project_dom->find(".leftcol_inr.box_profile.cf")->find("dt")->text();
        $resercher = str_replace(array("\r\n", "\r", "\n"), ',', $resercher);
        fputcsv($f, array("基盤事業の成果",$year,$project_name,$project_url, $resercher));
    }
  }


}


?>
