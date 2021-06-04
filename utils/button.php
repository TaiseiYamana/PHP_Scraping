<?php
// Open this url on browger http://localhost:8888/PHP_Scraping/utils/button.php
if(isset($_POST["scv"])){
  $alert = "<script type='text/javascript'>alert('SCVファイルをダウンロードしました。');</script>";
  echo $alert;
}

 ?>

<form action="button.php" method="post">
<!--
    <p><input type = "radio">男性</p>
    <p><input type = "radio">女性</p>
    <select>
      <option>東京都</option><br>
      <option>大阪</option>
    </select>
-->
    <button type="submit" name = "scv" value="1"><img src = "../img/download.gif" alt = "zip" /> Download SCV</button>
</form>
