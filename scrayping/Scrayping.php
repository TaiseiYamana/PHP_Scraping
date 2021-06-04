
<form action="Scrayping.php" method="post"><!--//ボタン配置-->
    <button type="submit" name = "scv" value="1"><img src = "../img/download.gif" alt = "zip" /> Download SCV</button>
</form>


<?php
// Open this url on browger http://localhost:8888/PHP_Scraping/scrayping/Scrayping.php
require_once("../phpQuery-onefile.php");

$item = array("名前", "研究系", "教授職", "役職", "学位", "専門分野", "研究室Web");
$f = fopen("../csv/list.csv", "w");

$position1 = array("所長","副署長");
$position2 = array("教授", "准教授", "講師", "助教", "助手");

//$_POST["scv"] = 0;

if($_POST["scv"] == 1){
  if ( $f ) {
      fputcsv($f, $item);

      $top_url = "https://www.nii.ac.jp";
      $list_url = "https://www.nii.ac.jp/faculty/list/professors/";

      $list_html = file_get_contents($list_url);
      $list_dom = phpQuery::newDocument($list_html);

      foreach ($list_dom->find('#contentBox')->find('ul > li > a') as $a){
        //変数の初期化
        $research = "";
        $teacher = "";
        $position = "";

        //研究者名と個人ページurlの取得
        $name = $a->textContent;
        $person_url = $top_url . $a->getAttribute('href');

        echo $name . "<br>\n";

        $person_html = file_get_contents($person_url);
        $person_dom = phpQuery::newDocument($person_html);

        $b = $person_dom->find('.bg-white.padding-all-md.bd-gray-bottom.margin-bottom-default.text-default.text-sm-sp.cf');
        //研究系と役職を取得
        $belongs = $b->find('.fontB.text-md-sp')->text();
        $belongs = str_replace("／", " ", $belongs);
        $belongs = explode(" ", $belongs);

        for($i = 0; $i < count($belongs); $i++){
          if(preg_match('/研究系/',$belongs[$i]))
            $research = $belongs[$i];
          elseif(preg_match(create_pattern($position2), $belongs[$i]))
            $teacher =  $belongs[$i];
          //elseif(preg_match(create_pattern($position1), $belongs[$i]))
          else
            $position = $belongs[$i];
        }

        fputcsv($f,array($name, $research, $teacher, $position,"","",""));

        //echo $belongs;
        /*
        $info = $person_dom->find('margin-top-default');
        $info->find('span')->remove();
        $info = str_replace(array("\r\n", "\r", "\n"), "\n", $info);
        $info = explode("\n", $info);

        $belongs = array_merge($name, $belongs);
        $info = array_merge($belongs, info);

        fputcsv($f, $info);


        //fputcsv($f,array($name,"","","","",""));
        */
      }

  }

  $alert = "<script type='text/javascript'>alert('SCVファイルをダウンロードしました。');</script>";
  echo $alert;
}

fclose($f);
/*
require_once("../phpQuery-onefile.php");



$top_url = "https://www.nii.ac.jp";
$list_url = "https://www.nii.ac.jp/faculty/list/professors/";

$html_1 = file_get_contents($list_url);
$dom_1 = phpQuery::newDocument($html_1);

foreach ($dom_1->find('#contentBox')->find('ul > li > a') as $a){
  $name = $a->textContent;
  $url = $a->getAttribute('href');
  $parson_url = $top_url . $url;

  echo $name .' url: <a href=' . $parson_url . '>' . $parson_url . '</a><br>';


  $html_2 = file_get_contents($parson_url);
  $dom_2 = phpQuery::newDocument($html_2);

  $alltitle = $dom_2->find('.bg-white.padding-all-md.bd-gray-bottom.margin-bottom-default.text-default.text-sm-sp.cf');
  $alltitle->find('span')->remove();

  echo $alltitle->text() . '<br>';
}
*/

function create_pattern($array){
  $pattern = '/';
  for($i = 0; $i < count($array); $i++){
    if ($i < count($array)-1){
      $pattern .= $array[$i] . '|';
    }else
      $pattern .= $array[$i] . '/';
  }
  return $pattern;
}


?>
