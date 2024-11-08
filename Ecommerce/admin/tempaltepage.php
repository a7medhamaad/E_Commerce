<?php
    //x page
ob_start();
session_start();
$pagetitle = 'pagename';

if (isset($_SESSION['Username'])) {
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'manage';
    if($do=='manage'){
         WELCOME TO ITEMS PAGE 
    }elseif($do='add'){

    }elseif($do='insert'){

    }elseif($do='edit'){

    } elseif($do='update'){

    } elseif($do='delete'){


    }elseif($do='activate'){

    }
    include $tpl. 'footer.php';

}else{
    header('Location:index.php');
    exit();
}

ob_end_flush();

?>