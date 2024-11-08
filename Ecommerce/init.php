<?php
    //erroe reporting
    ini_set('display_errors','On');
    error_reporting(E_ALL);

    include 'admin/connect.php';

    $sessionuser='';
    if(isset($_SESSION['user'])){
        $sessionuser=$_SESSION['user'];
    }

    $tpl='includes/templates/';
    $css='layout/css/';
    $langg='includes/language/';
    $func='includes/functions/';

    //include important files 

    include $langg . 'english.php';
    include  'includes/functions/functions.php';
    include 'includes/templates/header.php';
    // include $css .'frontend.css';
    // include 'function.php';



    //include navbar on all badge expect the one with $nonavbar varible
    