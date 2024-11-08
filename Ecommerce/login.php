<?php
    ob_start();
    session_start();
    $pagetitle='Login';
    if(isset($_SESSION['user'])){
        header('Location:index.php');
         
    }
     include 'init.php'; 
     //check if user come from http request
    if($_SERVER['REQUEST_METHOD']=="POST"){
          if(isset($_POST['login'])){
                $user=$_POST['username'];
                $pass=$_POST['password'];      
                $hashedpass= sha1($pass);

                // echo $username . ' '. $password;
                $stmt=$con->prepare("SELECT UseriD,Username,Password  FROM users where Username=? AND Password=? "  );
                $stmt->execute(array($user,$hashedpass));
                $get=$stmt->fetch();  
                $count=$stmt->rowCount();
                //if count >0 this mean database contanin recored about this username 
                // echo $count;
                if($count>0){  
                $_SESSION['user']=$user;//register session name 
                //   print_r($_SESSION);// just check
                $_SESSION['uid']=$get['UseriD'];//register user id
                header('Location:index.php');//rediredt to dashborad 
                exit();  
                }
            }else{
                // $filter_user=filter_var($_POST['username'],FILTER_SANITIZE_STRING) ;
                // $pass=sha1($_POST['password']); 
                // $email=$_POST['email'];
                // $pass2=sha1($_POST['password2']);
                    // Validate the form
                    $formerror = array();
                     $username=$_POST['username'];
                     $password=$_POST['password']; 
                     $password2=$_POST['password2']; 
                     $email=$_POST['email'];
                    if(isset($username)){
                        $filter_user=filter_var($username,FILTER_SANITIZE_STRING);
                        if (strlen($filter_user) < 4) {
                                $formerror[] = 'Username must be greater than <strong>3 characters</strong>';
                        }
                    }

                    if(isset($password) && isset($password2)){
                        if(empty($password)){
                            $formerror[]='pass can not be <strong>empty</strong>';
                            }
                        $pass=sha1($password);
                        $pass2=sha1($password2);
                        if($pass != $pass2){
                        $formerror[]='please write <strong>same pass</strong>';
                        }
                    }
                    if(isset($email)){
                        $filter_email=filter_var($email,FILTER_SANITIZE_EMAIL);
                        if (filter_var($filter_email,FILTER_VALIDATE_EMAIL)!=true) {
                                $formerror[] = 'this email <strong>not valid</strong>';
                        }
                    }

                     if (empty($formerror)) {
                        //check if user exit in database
                        $check=checkitem("Username","users",$username); 
                        if($check==1){
                            $formerror[]='this user is <strong>exist</strong>';

                        }else{

                            // Insert into the database 
                            $stmt = $con->prepare("INSERT INTO users(Username, Password, Email,RegStatus,Date) VALUES(:zuser, :zpass, :zmail,0,now())");
                            $stmt->execute(array(
                                'zuser' => $username,
                                'zpass' => sha1($password),
                                'zmail' => $email
                            ));
                            $successmsg='congrats you are now registed user';
                        }
                    }
                }
        
            }
     
    ?>
   <div class="container login-page">
    <h1 class="text-center">
        <span class="selected" data-class="login">Login</span> |
        <span data-class="signup">Sign Up</span>
    </h1>

    <!-- Login Form -->
    <form class="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Enter Username" required />
        <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Enter Password" required />
        <input class="btn btn-primary btn-block" name="login" type="submit" value="Login" />
    </form>

    <!-- Sign Up Form -->
    <form class="signup" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <input
            class="form-control"
            pattern=".{4,}"
            title="username must be 4 chars"
            type="text"
            name="username"
            autocomplete="off"
            placeholder="Enter Username" 
            required />

        <input 
            minlength="4"
            class="form-control" 
            type="password" 
            name="password" 
            autocomplete="new-password" 
            placeholder="Enter Password"  
            required />
        <input
           minlength="4"
           class="form-control"
           type="password" 
           name="password2" 
           autocomplete="new-password"
           placeholder="Confirm Password" 
           required />
        <input class="form-control"
          type="email"
          name="email"
          autocomplete="off"
          placeholder="Enter Email" 
             />
        <input class="btn btn-success btn-block" name="signup" type="submit" value="Sign Up" />
    </form>
    <div class="theerror text-center">
        <?php
        if(!empty($formerror)){
            foreach($formerror as $error){
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }
        } 
        if(isset($successmsg)){
            echo '<div class="alert alert-success">' . $successmsg . '</div>';
        }
         ?>
    </div>
</div>   
        <!-- end signup form -->

<?php include $tpl . 'footer.php'; ?>