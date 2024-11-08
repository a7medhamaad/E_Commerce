<?php

// Manage comment page
//you can edit,delete and approve
session_start();
$pagetitle = 'Comments  ';

if (isset($_SESSION['Username'])) {
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'manage';

    // Start manage page
    if($do == 'manage') { 
        $query='';
        if(isset($_GET['page'])&&$_GET['page']=='pending'){
            $query='AND Approve =0 ';
        }
      
        // Select all users except admin
        $stmt = $con->prepare("SELECT 
                                   comments.*, items.Name AS Item_Name ,users.Username AS User_Name 
                                 FROM 
                                    comments
                                INNER JOIN 
                                    items
                                ON 
                                     items.item_ID= comments.Item_id 
                                INNER JOIN 
                                    users
                                ON 
                                    users.UseriD=comments.User_id
                                ORDER BY 
                                    C_id DESC "
                                    
                                );
        $stmt->execute();  
        // Assign to variable  
        $comments = $stmt->fetchAll();
        if(!empty($comments)){

        ?>

        <h1 class="text-center">Manage Member</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="table text-center table-bordered">
                    <tr>
                        <td>ID</td>   
                        <td> Comment</td>   
                        <td>Item Name</td>  
                        <td>User Name </td> 
                        <td>Added date</td> 
                        <td>Control</td> 
                    </tr>
                    <?php foreach($comments as $comment) {
                        echo "<tr>";
                            echo "<td>" . $comment['C_id'] . "</td>";
                            echo "<td>" . $comment['Comment'] . "</td>";
                            echo "<td>" . $comment['Item_Name'] . "</td>";// we assign it after make inner join 
                            echo "<td>" . $comment['User_Name'] . "</td>";// we assign it after make inner join 
                            echo "<td>" . $comment['Comment_date'] . "</td>";

                            echo "<td>
                                 <a href='comments.php?do=edit&comid=". $comment['C_id']."' class='btn btn-success'>Edit</a>
                                 <a href='comments.php?do=delete&comid=". $comment['C_id']."' class='btn btn-danger confirm'>Delete </a>";

                                 if($comment['Status']==0){
                                    echo "<a href='comments.php?do=approve&comid=". $comment['C_id']."' class='btn btn-info'> Approve</a>";
                                 }
                             
                                 echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
        <?php }else {
            echo '<div class="container">';
                echo '<div class="alert alert-info">there is no recored to show </div>'; 
                
            echo '</div>';
           }?>
    
    <?php 
    } elseif ($do == 'edit') {
        if (isset($_GET['comid']) && is_numeric($_GET['comid'])) {
            $comid = intval($_GET['comid']);
        } else {
            echo 0;
        }

        $stmt = $con->prepare("SELECT * FROM comments WHERE C_id = ?");
        $stmt->execute(array($comid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($count > 0) { ?>
            <h1 class="text-center">Edit Comment</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=update" method="POST">
                    <input type="hidden" name="comid" value="<?php echo $comid; ?>">
                    <!-- Start comment field -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Comment</label>
                        <div class="col-sm-10">
                         <textarea class="form-control" name="comment" ><?php echo $row['Comment'] ?></textarea>
                        </div>
                    </div>
                    <!-- End comment field -->
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
        echo "<h1 class='text-center'>Update Comment</h1>";
        echo "<div class='container'>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['comid'];
            $comment = $_POST['comment'];
           
           
            // Validate the form
            $formerror = array();
            if (empty($comment)) {
                $formerror[] = 'comment can/`t  <strong>empty</strong>';
            }
            foreach ($formerror as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }

            if (empty($formerror)) {  
                // Update the database with this info
                $stmt = $con->prepare("UPDATE comments SET Comment = ? WHERE C_id = ?");
                $stmt->execute(array($comment ,$id));
                $themsg= "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Updated</div>";
                redirecthome($themsg, 'back');

            }
        } else {
            echo "<div class='container' >";
            $themsg= '<div class="alert alert-danger">Sorry, you cannot browse this page directly.</div>';
            redirecthome($themsg, 'back',3);
            echo "</div>";
        }
        echo "</div>";  
    } elseif ($do == 'delete') {

        // Delete member
        echo "<h1 class='text-center'>Delete Comment</h1>";
        echo "<div class='container'>";
        if (isset($_GET['comid']) && is_numeric($_GET['comid'])) {
            $comid = intval($_GET['comid']);

            // Check if comnebt exists in the database
             $check= checkitem('C_id','comments',$comid);
    

            if ($check > 0) {
                //delete comment
                $stmt = $con->prepare("DELETE FROM comments WHERE C_id = :zcomment");
                $stmt->bindParam(":zcomment", $comid);
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
          }elseif($do=='approve'){

            echo "<h1 class='text-center'>Approve Comment</h1>";
            echo "<div class='container'>";
            if (isset($_GET['comid']) && is_numeric($_GET['comid'])) {
                $comid = intval($_GET['comid']);
    
                // Check if comment exists in the database
                 $check= checkitem('C_id','comments',$comid);
        
    
                if ($check > 0) {
                    //delete user
                    $stmt = $con->prepare("UPDATE comments set Status=1 WHERE C_id=?");
                    $stmt->execute(array($comid));
    
                    $themsg ="<div class='alert alert-success'>" . $stmt->rowCount() . " Comment Approve</div>";
                    redirecthome($themsg,'back');
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
