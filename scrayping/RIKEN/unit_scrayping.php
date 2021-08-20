<?php
// Open this url on browger http://localhost:8888/PHP_Scraping/scrayping/RIKEN/unit.php
require_once("../../phpQuery-onefile.php");

$read_csv_path = "../../csv/RIKEN/lab.csv";
$rf = fopen($read_csv_path , "r");

$write_csv_path = "../../csv/RIKEN/unit.csv";
$wf = fopen($write_csv_path , "w");

$counter = 0;
while($line = fgetcsv($rf)){
  $unit_name = "";
  $unit_url = "";
  $main_filed = "";
  $related_filed = "";

  if ($counter != 0){
    $unit_name = $line[2];
    $unit_url = $line[3];

    if ($unit_url != ""){
      $unit_html = file_get_contents($unit_url);
      $unit_dom = phpQuery::newDocument($unit_html);

      $pattern = "@研究主分野</h2>[\s\S]*?</ul>@";
      preg_match($pattern, $unit_dom, $array);
      $main_filed = str_replace("研究主分野</h2>", '', $array[0]);
      $main_filed = phpQuery::newDocument($main_filed);
      $main_filed =  $main_filed->find('li')->text();

      $pattern = "@研究関連分野</h2>[\s\S]*?</ul>@";
      preg_match($pattern, $unit_dom, $array);
      $related_filed = str_replace("研究関連分野</h2>", '', $array[0]);
      $related_filed = phpQuery::newDocument($related_filed);
      $filed_array = array();
      foreach($related_filed->find('li') as $filed){
        $filed = pq($filed);
        array_push($filed_array,$filed->text());
      }//関連研究分野のtext抽出のloop
      $related_filed = implode(",",$filed_array);

      $pattern = "@キーワード</h2>[\s\S]*?</ul>@";
      preg_match($pattern, $unit_dom, $array);
      $keywords = str_replace("キーワード</h2>", '', $array[0]);
      $keywords = phpQuery::newDocument($keywords);
      $keyword_array = array();
      foreach($keywords->find('li') as $keyword){
        $keyword = pq($keyword);
        array_push($keyword_array,$keyword->text());
      }//kewordのtext抽出のloop
      $keywords = implode(",",$keyword_array);

      $pattern = "@お問い合わせ先</h2>[\s\S]*?</p>@";
      preg_match($pattern, $unit_dom, $array);
      $contact = str_replace("お問い合わせ先</h2>", '', $array[0]);
      $contact = str_replace("※[at]は@に置き換えてください。", '', $contact);
      $contact = str_replace(" [at] ","@", $contact);
      $contact = str_replace("Tel", ",Tel", $contact);
      $contact = str_replace("Email", ",Email", $contact);
      $contact = str_replace("Fax", ",Fax", $contact);
      $contact = phpQuery::newDocument($contact);
      $contact = $contact->text();
      $contact = str_replace(array("\r\n", "\r", "\n","	"), '', $contact);

      fputcsv($wf, array($unit_name,$unit_url,$main_filed,$related_filed,$keywords,$contact));
      //if ($counter == 50)
      //break;

    }// unit urlがあるときのループ
  }//counter ０以外のときのループ
  $counter++;
}

?>
