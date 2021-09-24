<?php
// Open this url on browger http://localhost:8888/PHP_Scraping/scrayping/JAXA/departments_scrayping.php
require_once("../../phpQuery-onefile.php");

$root = "https://www.jaxa.jp/";
$url = "https://www.jaxa.jp/projects/index_j.html";


$csv_path = "../../csv/JAXA/departments_url.csv";
$f = fopen($csv_path , "w");

fputcsv($f, array("部署名", "WEBサイト", "Youtubeチャンネル、再生リスト", "SNSアカウントサイト"));

$html = file_get_contents($url);
$dom = phpQuery::newDocument($html);

$counter = 0;
foreach($dom["table:eq(0) tr"] as $row){
  $row = pq($row);
  if($counter %2 == 1){

  $website = "";
  $first_flag = False;
  $website_html = $row->find("td:eq(1)");
  foreach ($website_html->find('li') as $html_box){
  $html_box = pq($html_box);
  $website_name = $html_box->text();
  $website_url = $html_box->find("a")->attr("href");
  if (!$first_flag){
    $website =  $website_name . "(" . $website_url . ")";
    $first_flag = True;
    }else{
      $website = $website . "," . $website_name . "(" . $website_url . ")";
    }
  }

  $youtube = "";
  $first_flag = False;
  $youtube_html = $row->find("td:eq(2)");
  foreach ($youtube_html->find('li') as $html_box){
  $html_box = pq($html_box);
  $youtube_name = $html_box->text();
  $youtube_url = $html_box->find("a")->attr("href");
  if (!$first_flag){
    $youtube =  $youtube_name . "(" . $youtube_url . ")";
    $first_flag = True;
    }else{
      $youtube = $youtube . "," . $youtube_name . "(" . $youtube_url . ")";
    }
  }


  $sns = "";
  $first_flag = False;
  $sns_html = $row->find("td:eq(3)");
  foreach ($sns_html->find('li') as $html_box){
  $html_box = pq($html_box);
  $sns_name = $html_box->text();
  $sns_url = $html_box->find("a")->attr("href");
  if (!$first_flag){
    $sns =  $sns_name . "(" . $sns_url . ")";
    $first_flag = True;
    }else{
      $sns = $sns . "," . $sns_name . "(" . $sns_url . ")";
    }
  }

  $website = str_replace(array("\r\n", "\r", "\n"," ","　","\t","\0","\v"), '', $website);
  $youtube = str_replace(array("\r\n", "\r", "\n"," ","　","\t","\0","\v"), '', $youtube);
  $sns = str_replace(array("\r\n", "\r", "\n"," ","　","\t","\0","\v"), '', $sns);
  fputcsv($f, array($department_name, $website, $youtube, $sns));
  echo $department_name ."<br>";
  echo $website ."<br>";
  echo $youtube ."<br>";
  echo $sns . "<br>";

}else{
$department_name = $row->find("td:eq(0)")->text();
}


  $counter++;

}



?>
