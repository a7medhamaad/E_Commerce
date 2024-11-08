<?php

// Manage member page
session_start();
$pagetitle = 'Members';

if (isset($_SESSION['Username'])) {
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'manage';

    // Start manage page
    if($do == 'manage') { 
        $query='';
        if(isset($_GET['page'])&&$_GET['page']=='pending'){
            $query='AND RegStatus=0 ';
        }
        // Select all users except admin
        $stmt = $con->prepare("SELECT * FROM users WHERE GroupiD != 1 $query");
        $stmt->execute();  
        // Assign to variable  
        $rows = $stmt->fetchAll();
        //the next line mean if pending tabel is empty it will not appeer
        if(! empty($rows)){
        ?>

        <h1 class="text-center">Manage Member</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="table manage-member text-center table-bordered">
                    <tr>
                        <td>#ID</td>   
                        <td>Avatar</td>   
                        <td>UserName</td>   
                        <td>Email</td>  
                        <td>Fullname</td> 
                        <td>Register date</td> 
                        <td>Control</td> 
                    </tr>
                    <?php foreach($rows as $row) {
                        echo "<tr>";
                            echo "<td>" . $row['UseriD'] . "</td>";
                            echo "<td><img src='uploads/avatars/" . $row['avatar'] . "' alt='noimage' /></td>";
                            echo "<td>" . $row['Username'] . "</td>";
                            echo "<td>" . $row['Email'] . "</td>";
                            echo "<td>" . $row['FullName'] . "</td>";
                            echo "<td>" . $row['Date'] . "</td>";

                            echo "<td>
                                 <a href='members.php?do=edit&userid=". $row['UseriD']."' class='btn btn-success'>Edit</a>
                                 <a href='members.php?do=delete&userid=". $row['UseriD']."' class='btn btn-danger confirm'>Delete </a>";

                                 if($row['RegStatus']==0){
                                    echo "<a href='members.php?do=activate&userid=". $row['UseriD']."' class='btn btn-info'> Activate</a>";
                                 }
                             
                                 echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
            <a href="members.php?do=add" class ="btn btn-primary"><i class="fa fa-plus"></i> Add new member </a>
        </div>
        <?php }else {
            echo '<div class="container">';
                echo '<div class="alert alert-info">there is no recored to show </div>'; 
                echo '<a href="members.php?do=add" class="btn btn-success">ADD Member</a>';  

            echo '</div>';
           }?>
    
    <?php 
    } elseif ($do == 'edit') {
        if (isset($_GET['userid']) && is_numeric($_GET['userid'])) {
            $userid = intval($_GET['userid']);
        } else {
            echo 0;
        }

        $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
        $stmt->execute(array($userid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($count > 0) { ?>
            <h1 class="text-center">Edit Member</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=update" method="POST">
                    <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                    <!-- Start username field -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10">
                            <input type="text" name="username" class="form-control" value="<?php echo $row['Username']; ?>" autocomplete="off" required="required">
                        </div>
                    </div>
                    <!-- End username field -->
                    <!-- Start password field -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="oldpass" value="<?php echo $row['Password']; ?>">
                            <input type="password" name="newpass" class="form-control" autocomplete="new-password" placeholder="Leave blank if you don't want to change">
                        </div>
                    </div>
                    <!-- End password field -->
                    <!-- Start fullname field -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Full Name</label>
                        <div class="col-sm-10">
                            <input type="text" name="fullname" class="form-control" value="<?php echo $row['FullName']; ?>">
                        </div>
                    </div>
                    <!-- End fullname field -->
                    <!-- Start email field -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" name="email" class="form-control" required="required" value="<?php echo $row['Email']; ?>">
                        </div>
                    </div>
                    <!-- End email field -->
                    <!-- Start submit field -->
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Save" class="btn btn-primary btn-lg">
                        </div>
                    </div>
                    <!-- End submit field -->
                </form>
            </div>
        <?php 
        } else {
            echo "<div class='container' >"; 
            $themsg= '<div class="alert alert-danger">No matching ID found.</div>';
            redirecthome($themsg);
            echo "</div>";
        }
    } elseif ($do == 'update') {
        echo "<h1 class='text-center'>Update Member</h1>";
        echo "<div class='container'>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['userid'];
            $user = $_POST['username'];
            $email = $_POST['email'];
            $name = $_POST['fullname'];

            // Password check
            $pass = empty($_POST['newpass']) ? $_POST['oldpass'] : sha1($_POST['newpass']);

            // Validate the form
            $formerror = array();
            if (strlen($user) < 4) {
                $formerror[] = 'Username must be greater than <strong>3 characters</strong>';
            }
            if (strlen($user) > 20) {
                $formerror[] = 'Username must be less than <strong>20 characters</strong>';
            }
            if (empty($user)) {
                $formerror[] = 'Username cannot be <strong>empty</strong>';
            }
            if (empty($name)) {
                $formerror[] = 'Full Name cannot be <strong>empty</strong>';
            }
            if (empty($email)) {
                $formerror[] = 'Email cannot be <strong>empty</strong>';
            }

            foreach ($formerror as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }

            if (empty($formerror)) {   
                 
                $stmt2=$con->prepare("SELECT * FROM users WHERE Username=? AND UserID !=?");
                $stmt2->execute(array($user,$id));
                $count=$stmt2->rowCount();
                if($count==1){
                    $themsg ="<div class='alert alert-danger'>this user is exist.</div>";
                    redirecthome($themsg,'back');//back can change to anything except null beacuse null redirect to homepage 

                }else{
                    //Update the database with this info
                    $stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ?");
                    $stmt->execute(array($user, $email, $name, $pass, $id));
                    $themsg= "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Updated</div>";
                    redirecthome($themsg, 'back');
                }

            }
        } else {
            echo "<div class='container' >";
            $themsg= '<div class="alert alert-danger">Sorry, you cannot browse this page directly.</div>';
            redirecthome($themsg, 'back',3);
            echo "</div>";
        }
        echo "</div>";  
    } elseif ($do == 'add') { ?>
        <h1 class="text-center">Add New Member</h1>
        <div class="container">
            <!-- defult to =>enctype is "application/x-www-form-urlencoded" -->
            <form class="form-horizontal" action="?do=insert" method="POST" enctype="multipart/form-data">
                <!-- Start username field -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-10">
                        <input type="text" name="username" class="form-control" minlength="4" maxlength="20" autocomplete="off" required="required" placeholder="Write username">
                    </div>
                </div>
                <!-- End username field -->
                <!-- Start password field -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" name="newpass" class="form-control" autocomplete="new-password" required="required" placeholder="Write a strong password">
                    </div>
                </div>
                <!-- End password field -->
                <!-- Start fullname field -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Full Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="fullname" class="form-control" required="required" placeholder="Write full name">
                    </div>
                </div>
                <!-- End fullname field -->
                <!-- Start email field -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" name="email" class="form-control" required="required" placeholder="Write a valid email">
                    </div>
                </div>
                <!-- End email field -->
                <!-- Start Avatar field -->
                <div class="form-group">
                <label class="col-sm-2 control-label">User Avatar</label>
                <div class="col-sm-10">
                    <input type="file" name="avatar" class="form-control" required="required">
                </div>
                </div>
                <!-- End Avatar field -->
                <!-- Start submit field -->
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Member" class="btn btn-primary btn-lg">
                    </div>
                </div>
                <!-- End submit field -->
            </form>
        </div> 

    <?php
    } elseif ($do == 'insert') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Insert Member</h1>";
            echo "<div class='container'>";
            //upload varibel
            // $avatar=$_FILES['avatar'];
            $avatarname=$_FILES['avatar']['name'];
            $avatarsie=$_FILES['avatar']['size'];
            $avatartmp=$_FILES['avatar']['tmp_name'];
            $avatartype=$_FILES['avatar']['type'];
            //list of allow file type upload
            $avatarallowextension=array("jpeg","jpg","png","gif");
            //get avvatar extenstion
            $x=explode('.',$avatarname);
            $y=end($x);
            $avatarextenstion=strtolower($y);
            // if(! in_array($avatarextenstion,$avatarallowextension)){
            //     echo 'good';
            // }
            // Get variables from the form 
            $user = $_POST['username'];
            $pass = sha1($_POST['newpass']);
            $email = $_POST['email'];
            $name = $_POST['fullname'];

            // Validate the form
            $formerror = array();
            if (strlen($user) < 4) {
                $formerror[] = 'Username must be greater than <strong>3 characters</strong>';
            }
            if (strlen($user) > 20) {
                $formerror[] = 'Username must be less than <strong>20 characters</strong>';
            }
            if (empty($user)) {
                $formerror[] = 'Username cannot be <strong>empty</strong>';
            }
            if (empty($name)) {
                $formerror[] = 'Full Name cannot be <strong>empty</strong>';
            }
            if (empty($email)) {
                $formerror[] = 'Email cannot be <strong>empty</strong>';
            }
            if(!empty($avatarname) && ! in_array($avatarextenstion,$avatarallowextension)){
                $formerror[]='This extenstion is not allowed';
            }
            if(empty($avatarname)){
                $formerror[]='avatar is required';
            }
            if($avatarsie>4194304){
                $formerror[]='avatar can not be larger than 4 MB';
            }

            foreach ($formerror as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }

            if (empty($formerror)) {

                // echo rand(0,1000000);
                $avatar=rand(0,100000000).'_'.$avatarname ;
                move_uploaded_file($avatartmp,"uploads\avatars\\".$avatar );

                //check if user exit in database
                 $check=checkitem("Username","users",$user); 
                 if($check==1){
                    $themsg ="<div class='alert alert-danger'>this user is exist.</div>";
                     redirecthome($themsg,'back');//back can change to anything except null beacuse null redirect to homepage 

                 }else{

                    // Insert into the database 
                    $stmt = $con->prepare("INSERT INTO users(Username, Password, Email, FullName,RegStatus,Date,avatar) VALUES(:zuser, :zpass, :zmail, :zname,1 ,now(),:zava)");
                    $stmt->execute(array(
                        'zuser' => $user,
                        'zpass' => $pass,
                        'zmail' => $email,
                        'zname' => $name,
                        'zava' => $avatar,
                    ));
                    $themsg= "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Inserted</div>";
                    redirecthome($themsg, 'back',3);
            }
            }
        } else {
            echo "<div class='container'>";
            $themsg= "<div class='alert alert-danger'>Sorry, you cannot browse this page directly.</div>";
            redirecthome($themsg);
            echo "</div>";  

        }  
        echo "</div>";  
    } elseif ($do == 'delete') {

        // Delete member
        echo "<h1 class='text-center'>Delete Member</h1>";
        echo "<div class='container'>";
        if (isset($_GET['userid']) && is_numeric($_GET['userid'])) {
            $userid = intval($_GET['userid']);

            // Check if user exists in the database
             $check= checkitem('userid','users',$userid);
    

            if ($check > 0) {
                //delete user
                $stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");
                $stmt->bindParam(":zuser", $userid);
                $stmt->execute();

                $themsg ="<div class='alert alert-success'>" . $stmt->rowCount() . " Member  Deleted</div>";
                redirecthome($themsg);
            } else {
                $themsg= "<div class='alert alert-danger'>No such user found.</div>"; 
                redirecthome($themsg);
            }
        } else {
                echo "<div class='alert alert-danger'>Invalid or missing user ID.</div>";
            
        echo '</div>';
              } 
          }elseif($do=='activate'){

            echo "<h1 class='text-center'>Activate Member</h1>";
            echo "<div class='container'>";
            if (isset($_GET['userid']) && is_numeric($_GET['userid'])) {
                $userid = intval($_GET['userid']);
    
                // Check if user exists in the database
                 $check= checkitem('userid','users',$userid);
        
    
                if ($check > 0) {
                    //delete user
                    $stmt = $con->prepare("UPDATE users set RegStatus=1 WHERE UseriD=?");
                    $stmt->execute(array($userid));
    
                    $themsg ="<div class='alert alert-success'>" . $stmt->rowCount() . " Member  Activated</div>";
                    redirecthome($themsg);
                } else {
                    $themsg= "<div class='alert alert-danger'>No such user found.</div>"; 
                    redirecthome($themsg);
                }
            } else {
                    echo "<div class='alert alert-danger'>Invalid or missing user ID.</div>";
                
            echo '</div>';
          }
        }
    include $tpl . 'footer.php';
} else {
    header('Location: index.php');
    exit();
}

?>
