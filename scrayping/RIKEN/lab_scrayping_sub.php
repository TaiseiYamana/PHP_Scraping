<?php
// Open this url on browger http://localhost:8888/PHP_Scraping/scrayping/RIKEN/lab_scrayping.php
require_once("../../phpQuery-onefile.php");

// Todoタスク
// 1:体系名　部門名 取得, 部門URLの取得

$root = "https://www.riken.jp";

$url_list = array("研究室紹介" => "https://www.riken.jp/research/labs/index.html");


$csv_path = "../../csv/RIKEN/lab.csv";
$f = fopen($csv_path , "w");

$item = array("体系","部門","部署","url");
fputcsv($f, $item);

foreach($url_list as $key => $value){
  $title = $key;
  $url = $value;

  $html = file_get_contents($url);
  $dom = phpQuery::newDocument($html);

  $counter = 0;

  $organaization_array = array();
  foreach($dom->find(".container01")->find("h2") as $organization){
    $organization = pq($organization);
    array_push($organaization_array, $organization->text());
  }//foreach($dom->find(".container01")->find("h2") as $organization)

  //var_dump($organaization_array);
  foreach($dom->find(".container01")->find(".lytCol01.col2.sticky") as $organization_box){ //体系ごとのbox抽出
    $organization_box = pq($organization_box);
    foreach($organization_box->find(".col") as $department_box){ //部門ごとのbox抽出
      $unit_name_array = array();
      $unit_url_array = array();

      $department_box = pq($department_box);
      $department_name = $department_box->find(".title")->text();
      $department_url = $root . $department_box->find('a')->attr("href");
      $organization_name = $organaization_array[$counter];
      //echo $department_box;
      // 部署のhtml取得
      $department_html = file_get_contents($department_url);
      $department_dom = phpQuery::newDocument($department_html);

      echo $department_dom->find(".listMember01")->find('div');

      // 組織があるかどうかを判断
      $h2 = $department_dom->find("h2"); // h2タグの取得
      if( preg_match( '/組織/', $h2) ){

        foreach($department_dom->find(".listMember01")->find('div')->find('dt') as $unit_name){ //名前 urlの取得
          $unit_name = pq($unit_name);
          $unit_url = $root . $unit_name->find('a')->attr("href");
          $unit_name = $unit_name->text();
          $unit_name = str_replace(array("\r\n", "\r", "\n","	"), '', $unit_name);
          fputcsv($f, array($department_name, $department_name, $unit_name, $unit_url));
          //array_push($unit_name_array, $unit_name);
          //array_push($unit_url_array, $unit_url);
        }
      }

    } // 体系/部署のループ
  } //体系のループ
  $counter++;
}

?>
