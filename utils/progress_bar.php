<?php

 // http://localhost:8888/PHP_Scraping/utils/progress_bar.php
require_once('../ProgressBar/Manager.php');
require_once('../ProgressBar/Registry.php');

$progressBar = new \ProgressBar\Manager(0, 10);

for ($i = 0; $i <= 10; $i++)
{
    $progressBar->update($i);
    sleep(1);
}

?>
