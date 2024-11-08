<?php
    include'connect.php';

    $tpl='includes/templates/';
    $css='layout/css/';
    $langg='includes/language/';
    $func='includes/functions/';

    //include important files 

    include $langg . 'english.php';
    include  'includes/functions/functions.php';
    include 'includes/templates/header.php';

    //include navbar on all badge expect the one with $nonavbar varible
    if(!isset($nonavbar)){
    include $tpl.'navbar.php';
    } 
