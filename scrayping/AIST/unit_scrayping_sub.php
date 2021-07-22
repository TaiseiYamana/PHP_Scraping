<?php
// Open this url on browger http://localhost:8888/PHP_Scraping/scrayping/AIST/unit_scrayping.php
require_once("../../phpQuery-onefile.php");

$root = "https://www.aist.go.jp";

$url_list = array("エネルギー・環境領域" => "https://www.aist.go.jp/aist_j/dept/denvene.html",
                  "生命工学領域" => "https://www.aist.go.jp/aist_j/dept/dlsbt.html",
                  "情報・人間工学領域" => "https://www.aist.go.jp/aist_j/dept/dithf.html",
                  "材料・化学領域" => "https://www.aist.go.jp/aist_j/dept/dmc.html",
                  "エレクトロニクス・製造領域" => "https://www.aist.go.jp/aist_j/dept/delma.html",
                  "地質調査総合センター" => "https://www.aist.go.jp/aist_j/dept/gsj.html",
                  "計量標準総合センター" => "https://www.aist.go.jp/aist_j/dept/nmij.html");


$csv_path = "../../csv/AIST/unit.csv";
$f = fopen($csv_path , "w");

$item = array("研究分野", "ユニット名", "研究拠点", "参画する技術研究組合","所在地", "WebサイトURL");
fputcsv($f, $item);

foreach($url_list as $key => $value){
  $filed_url = $value;
  $filed_name = $key;

  $html = file_get_contents($filed_url);
  $dom = phpQuery::newDocument($html);

  $box = $dom->find(".defaultList");

  #コメントアウトの削除
  preg_match('@<!--[\s\S]*?-->@', $box, $array);
  $box = str_replace($array[0], '', $box);

  $box = $dom->find(".title")->text();
  $unit = str_replace(array("\r\n", "\r", "\n"), "*", $box);
  $unit = str_replace("*", ",", $unit);
  $unit = rtrim($unit, ",");
  //各ユニットを配列に入れる
  $unit_array = explode(",", $unit);

  $n = 0;

  foreach ($dom->find('.detail') as $detail){
    $detail = pq($detail);

    preg_match('@<a(.*?)WEBサイト</a>@', $detail, $array);
    $web_url = pq($array[0])->attr("href");
    $array = array();

    $detail = str_replace(array("\r\n", "\r", "\n"), '', $detail);

    #コメントアウトの削除
    preg_match('@<!--[\s\S]*?-->@', $detail,$array);
    $detail= str_replace($array[0], '', $detail);

    preg_match('@拠点</h3><p>(.*?)<@', $detail, $array);
    $base = $array[1];
    $array = array();

    preg_match('@参画する技術研究組合</h3>(.*?)<h3>@', $detail, $array);
    $participater = phpQuery::newDocument($array[1])->text();
    $array = array();

    preg_match('@所在地</h3><p>(.*?)</p>@', $detail, $array);
    $location = phpQuery::newDocument($array[1]);
    $location['a']->remove();
    $location = $location->text();
    //$location = $location->text();
    $array = array();

    echo $detail;

    fputcsv($f, array($filed_name, $unit_array[$n++], $base, $participater, $location, $web_url));
    $base =  $participater = $location = $web_url = "";
    //break;
  }

}


?>
