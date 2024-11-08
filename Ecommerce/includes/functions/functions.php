<?php
//##################################################################################################################################################
 //print title if exit and print defult if not 
 function gettitle(){
      global $pagetitle;
      if(isset($pagetitle)){
        echo $pagetitle;

      }else{
        echo 'defult';
      }
 }
 /*
 // function v1.0
 // redirect function this function accept parameter 
 //$errormsg = echo the error message 
 //$seconds=second before redirect
  function redirecthome($errormsg ,$second=3){
    echo "<div class='alert alert-danger'>$errormsg</div> ";
    echo "<div class='alert alert-info '>You will be redireted to home page after $second seconds </div>";

      header("refresh:$second; url=index.php");
      exit();
    }
    */
//##################################################################################################################################################
//  function v2.0
//  redirect function this function accept parameter 
//  $themsg = echo the message[error |succses | waring] 
//  $seconds=second before redirect
//  $url=the link you eant to redirect to   
  function redirecthome($themsg ,$url=null,$second=3){
    if($url===null){
      $url='index.php';
      $link='homepage';
    }else{
      $url=isset($_SERVER['HTTP_REFERER'])&& $_SERVER['HTTP_REFERER']!==''?$_SERVER['HTTP_REFERER']:'index.php';
      $link='previous page ';
      // if(isset($_SERVER['HTTP_REFERER'])&& $_SERVER['HTTP_REFERER']!==''){
        
      //   $url = $_SERVER['HTTP_REFERER']; 
      // }else{
      //   $url='index.php';
      // }
    }
    echo $themsg;
    echo "<div class='alert alert-info '>You will be redireted to $link after $second seconds </div>";

      header("refresh:$second; url=$url");
      exit();
    }
  //##################################################################################################################################################
    //function to check iteam in datebase 
    //function accept parameter
    //$select=the item to select
    //$from =the tabel to select from 
    //$value=the value of select
    function checkitem($select,$from,$value){
      global $con;
      $statment=$con->prepare("SELECT $select FROM $from WHERE $select=? ");
      $statment->execute(array($value));
      $count=$statment->rowCount();
      return $count;
    }
//##################################################################################################################################################
//count number of items function v1.0
// function to count num of items rows 
//$item=the item to count
//$table=the tanle to choose from  
function countitems($item,$table){
  global $con;
  $stmt2=$con->prepare("SELECT COUNT($item) FROM $table");
  $stmt2->execute();
  return $stmt2->fetchColumn();
}
//##################################################################################################################################################
//get latest record function v1.0
//function to get latest items from database
// $select=field to select
//$table=the table to choose from 

function getlatest($select,$table,$order,$limit=5){
  global $con;
  $getstmt=$con->prepare("SELECT $select FROM $table ORDER BY $order DESC  LIMIT $limit ");
  $getstmt->execute();
  $rows=$getstmt->fetchAll();
  return $rows;
}

// ###########################################################33
/*
**get allfunction 
**function to get all records from any table 
*/
function getallfrom($field,$table,$orderfield,$where=null,$and='null',$ordering="DESC") {
  global $con;
  $getall = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering ");
  $getall->execute();
  $all=$getall->fetchAll(); 
  return $all;
}

// this function declare in header.php beacuse when am declare it on this file am get error and i can not solve it 
// ##########################################################
      function getcat() {
        global $con;
        $getstmt = $con->prepare("SELECT * FROM categorie ORDER BY Id DESC");
        $getstmt->execute();
        $rows=$getstmt->fetchAll(); 
        return $rows;
      }
// ##########################################################
// ##########################################################
// // chcek if user is not activate
function checkuserstatus($user){
  global $con;
  $stmtx=$con->prepare("SELECT Username,RegStatus  FROM users where Username=? AND RegStatus=0  "  );
  $stmtx->execute(array($user));  
  $status=$stmtx->rowCount();
  return $status;
}
// ########################################################

//this function not work here but work and declare in header.php
       //get items function
       function getitem($where ,$value,$approve=null){
        global $con;
        if($approve== null){
          $sql='AND Approve =1';
        }else{
          $sql=null;
        }
        $getstmt = $con->prepare("SELECT * FROM items WHERE $where=? $sql ORDER BY item_ID DESC ");
        $getstmt->execute(array($value));
        $rows=$getstmt->fetchAll(); 
        return $rows;
      }
//#####################################################################