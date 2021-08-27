<?php
// Open this url on browger http://localhost:8888/PHP_Scraping/scrayping/RIKEN/member_scrayping.php
require_once("../../phpQuery-onefile.php");

$read_csv_path = "../../csv/RIKEN/unit.csv";
$rf = fopen($read_csv_path , "r");

$write_csv_path = "../../csv/RIKEN/member.csv";
$wf = fopen($write_csv_path , "w");

$counter = 0;
while($line = fgetcsv($rf)){
  $unit_name = "";
  $unit_url = "";
  #$main_filed = "";
  #$related_filed = "";

  $unit_name = $line[0];
  $unit_url = $line[1];

  //echo $unit_url . "<br>";

  $unit_html = file_get_contents($unit_url);
  $unit_dom = phpQuery::newDocument($unit_html);

  //echo $unit_dom->find('.listMember01');


  foreach($unit_dom->find('.listMember01') as $memberbox){
    $memberbox = pq($memberbox);
    //主催者とメンバーの検索
    if(preg_match("/主宰者|メンバー/",$memberbox)){
      $memberbox = $memberbox->find('.list');
      $name_array = array();
      $post_array = array();
      foreach($memberbox->find('dt') as $name){
        $name = pq($name);
        array_push($name_array,$name->text());
      }
      foreach($memberbox->find('dd') as $post){
        $post = pq($post);
        array_push($post_array,$post->text());
      }

      for($i = 0; $i < count($name_array); $i++){
        $name_array[$i] = str_replace(array("\r\n", "\r", "\n","	"), '', $name_array[$i]);
        $post_array[$i] = str_replace(array("\r\n", "\r", "\n","	"), '', $post_array[$i]);
        fputcsv($wf, array($unit_name,$name_array[$i],$post_array[$i]));

      }
    }//主催者とメンバーの検索
  }//memberbox foreach
}

?>
