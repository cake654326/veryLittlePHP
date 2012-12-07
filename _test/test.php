<?php

$include_path[] = get_include_path();
//$currDir = str_replace('index.php', '', $_SERVER['REQUEST_URI']);
$currDir =  $_SERVER['REQUEST_URI'];
print_r($currDir);

