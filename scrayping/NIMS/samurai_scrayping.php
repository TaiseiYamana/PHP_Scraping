<?php
// Open this url on browger http://localhost:8888/PHP_Scraping/scrayping/NIMS/samurai_scrayping.php
require_once("../../phpQuery-onefile.php");

$root = "https://samurai.nims.go.jp/";
$url = "https://samurai.nims.go.jp/directories";


$csv_path = "../../csv/NIMS/sumrai_researcher.csv";
$f = fopen($csv_path , "w");
fputcsv($f, array('名前', '個人URL', 'ORCID', '所属、役職' , '所属、役職URL', '住所', 'アクセス方法', '研究キーワード'));

$html = file_get_contents($url);
$dom = phpQuery::newDocument($html);

$file_link = $dom->find('.filelink');

foreach($file_link->find('.charlist') as $list){
  $list = pq($list);
  foreach($list->find('a') as $character){
    $character = pq($character);
    $character_url = $root . $character->attr('href');

    //文字ごとのURLの展開
    $character_html = file_get_contents($character_url);
    $character_dom = phpQuery::newDocument($character_html);

    foreach($character_dom->find('.name_txt') as $name_box){ //名前事のURLをだす。
      $name_box = pq($name_box);
      foreach($name_box->find('a') as $name_a){
        $name_a = pq($name_a);
        $individual_url = $root . $name_a->attr('href');

        $individual_html = file_get_contents($individual_url);
        $individual_dom = phpQuery::newDocument($individual_html);

        //////////////////個人ページのスクレイピングコード
        $info1 = $individual_dom->find('.txt_area.col-md-9');
        $info1 = pq($info1);

        $name = $info1->find('.name_txt')->text();


        $counter = 0;
        foreach($info1->find('.name_txt')->find('a') as $name_a){
          if($counter++ == 2){
            $name_a = pq($name_a);
            $orc_id = $name_a->attr('href');
          }
        }


        //$orc_id = "";

        $unit = $info1->find('.unit')->text();
        $unit = str_replace(" ", ",", $unit);
        $unit = str_replace(",,", ",", $unit);

        $unit_urls = "";
        $counter = 0;
        foreach($info1->find('.unit')->find('a') as $unit_a){
          $unit_a = pq($unit_a);
          $unit_url = $root . $unit_a->attr('href');

          if($counter++ == 0)
            $unit_urls = $unit_url;
          else
            $unit_urls = $unit_urls . ',' . $unit_url;
        }

        $address = $info1->find('.address')->text();
        $address = str_replace(array("[","]","アクセス","Address","\r\n","\r","\n"),"",$address);

        $access = $info1->find('.address')->find('a')->attr('href');

        //研究分野
        $info2 = $individual_dom->find('.box.res_description');

        $info2 = pq($info2);

        $keywords = $info2->find('.keywords_txt')->text();
        $keywords = str_replace("、",",",$keywords);

        fputcsv($f, array($name, $individual_url, $orc_id, $unit , $unit_urls, $address, $access, $keywords));
        //////////////////個人ページのスクレイピングコード
        break; //これは残す
      }
    }
  }
  break; //これは残す

}







?>
