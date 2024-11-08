<?php 
    session_start();
    $nonavbar='';
    $pagetitle='Login';
    if(isset($_SESSION['Username'])){
        header('Location:dashboard.php');
    }
    include 'init.php';
    include 'includes/templates/header.php';
    include_once $langg. 'english.php'; 
    // include 'layout/css/backend.css';
    // include 'includes/language/arabic.php';
    if($_SERVER['REQUEST_METHOD']=="POST"){
        $username=$_POST['user'];
        $password=$_POST['pass'];
        $hashedpass= sha1($password);

        // echo $username . ' '. $password;
        $stmt=$con->prepare("SELECT UseriD,Username,Password  FROM users where Username=? AND Password=? AND GroupiD=1 LIMIT 1"  );
        $stmt->execute(array($username,$hashedpass));  
        $row=$stmt->fetch(); 
        $count=$stmt->rowCount();
        //if count >0 this mean database contanin recored about this username 
        // echo $count;
        if($count>0){
         $_SESSION['Username']=$username;//register session name 
         $_SESSION['UseriD']=$row['UseriD']; //register session id 
          header('Location:dashboard.php');//rediredt to dashborad 
          exit();  
        }

    }
?>
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" >
    <h3 class="text-center" >Admin Login</h3>
        <input class="form-control" type="text" name="user" placeholder="Username" autocomplete="off" />
        <input class="form-control" type="password" name="pass" placeholder="password" autocomplete="off" />
       <div class="button">
       <input class="btn btn-primary" type="submit" value="login" />
       </div> 
    </form>
 
<?php 
    include $tpl . 'footer.php';
?>