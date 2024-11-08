<?php


    //if condition 
    // $do=isset($_GET['do'])?$_GET['do']:'manage';
    $do='';
    if(isset($_GET['do'])){
        $do=$_GET['do'];
    }else{
        $do='manage';
    }
 
    //if the page is the main page 
    if($do=='manage'){
       echo 'welcome you are in manage page' .'<br>' ;
       echo '<a href="?do=insert">add new categry+</a>';  

    }elseif($do=='add'){
       echo 'welcome you are in add page ';


    }elseif($do=='insert'){
       echo 'welcome you are in insert page' ;

    }else{
        echo 'error';
    }