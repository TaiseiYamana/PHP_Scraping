<?php
require_once("../phpQuery-onefile.php");

$list_url = "https://www.nii.ac.jp/faculty/list/professors/";

$list_html = file_get_contents($list_url);
$list_dom = phpQuery::newDocument($list_html);

?>
