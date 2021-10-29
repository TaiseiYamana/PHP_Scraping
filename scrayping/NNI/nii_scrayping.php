
<form action="nni_scrayping.php" method="post"><!--//ボタン配置-->
    <button type="submit" name = "csv" value="1"><img src = "../img/download.gif" alt = "zip" /> Download CSV</button>
</form>

<head>
  <style>
  table{
    width: 100%;
    border-collapse:separate;
    border-spacing: 0;
  }

  table th:first-child{
    border-radius: 5px 0 0 0;
  }

  table th:last-child{
    border-radius: 0 5px 0 0;
    border-right: 1px solid #3c6690;
  }

  table th{
    text-align: center;
    color:white;
    background: linear-gradient(#829ebc,#225588);
    border-left: 1px solid #3c6690;
    border-top: 1px solid #3c6690;
    border-bottom: 1px solid #3c6690;
    box-shadow: 0px 1px 1px rgba(255,255,255,0.3) inset;
    width: 25%;
    padding: 10px 0;
  }

  table td{
    text-align: center;
    border-left: 1px solid #a8b7c5;
    border-bottom: 1px solid #a8b7c5;
    border-top:none;
    box-shadow: 0px -3px 5px 1px #eee inset;
    width: 25%;
    padding: 10px 0;
  }

  table td:last-child{
    border-right: 1px solid #a8b7c5;
  }

  table tr:last-child td:first-child {
    border-radius: 0 0 0 5px;
  }

  table tr:last-child td:last-child {
    border-radius: 0 0 5px 0;
  }
  </style>
</head>

<?php
// Open this url on browger http://localhost:8888/PHP_Scraping/scrayping/nii_scrayping.php
require_once("../../phpQuery-onefile.php");

$item = array("名前", "個人ページURL","研究系", "教授職", "役職", "学位", "専門分野", "研究室Web");

$csv_path = "../../csv/NNI/list.csv";
$f = fopen($csv_path , "w");

$position1 = array("所長","副署長");
$position2 = array("教授", "准教授", "講師", "助教", "助手");

//$_POST["csv"] = 0;

if($_POST["csv"] == 1){
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
        $degree = "";
        $major = "";
        $web_url = "";

        //研究者名と個人ページurlの取得
        $name = $a->textContent;
        $person_url = $top_url . $a->getAttribute('href');

        //echo $name . "<br>\n";

        $person_html = file_get_contents($person_url);
        $person_dom = phpQuery::newDocument($person_html);

        $b = $person_dom->find('.bg-white.padding-all-md.bd-gray-bottom.margin-bottom-default.text-default.text-sm-sp.cf');
        //研究系と役職を取得
        $belongs = $b->find('.fontB.text-md-sp')->text();
        $belongs = str_replace("／", " ", $belongs); //スラッシュを空白に置き換え
        $belongs = str_replace('	', '', $belongs); //TABを削除
        $belongs = str_replace(array("\r\n", "\r", "\n"), '', $belongs); //改行コードを削除
        $belongs = explode(" ", $belongs); //空白で区切りそれぞれ配列に格納をする

        //学位と専門分野とURLの取得
        $status = $b->find('.margin-top-default');
        preg_match('@学位：</span>(.*?)<br>@s', $status, $array);
        $degree = $array[1];
        preg_match('@専門分野：</span>(.*?)<br>@s', $status, $array);
        $major = $array[1];

        if(preg_match('/研究室WEB/',$status)){
          preg_match('@<a href="([^"]*)" target="_blank" class="fontB">研究室WEB</a>@', $status, $array);
          $web_url =   $array[1];
        }

        for($i = 0; $i < count($belongs); $i++){
          if(preg_match('/研究系/',$belongs[$i]))
            $research = $belongs[$i];
          elseif(preg_match(create_pattern($position2), $belongs[$i]))
            $teacher =  $belongs[$i];
          //elseif(preg_match(create_pattern($position1), $belongs[$i]))
          else
            $position = $belongs[$i];
        }

        fputcsv($f,array($name, $person_url, $research, $teacher, $position, $degree, $major,$web_url));

      }

      //$alert = "<script type='text/javascript'>alert('CSVファイルをダウンロードしました。');</script>";
      //echo $alert;
  }

  //$alert = "<script type='text/javascript'>alert('CSVファイルをダウンロードしました。');</script>";
  //echo $alert;
}

fclose($f);

$f = fopen ($csv_path, "r" );

$flag = false;
$counter = 0;

echo "<table border=\"1\">\n";
  while ( ( $data = fgetcsv ( $f, 1000, ",", '"' ) ) !== FALSE ) {
    echo "\t<tr>\n";
    for ($i = 0; $i < count( $data ); $i++ ) {
      if($flag && ($i == 1 || $i == 7))  //ulrのある列はリンクを埋め込む 個人ページURL:1 LabwebURL:7
        echo "\t\t<td> <a href=\"" . $data[$i] . "\">" . $data[$i] . "</a></td>\n";
      else
        echo "\t\t<td>{$data[$i]}</td>\n";
    }
    echo "\t</tr>\n";
    $flag = true;
    $counter += 1;
  }
echo "</table>\n";
fclose ( $f );


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
