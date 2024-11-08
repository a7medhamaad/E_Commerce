<?php
    //items page
ob_start();
session_start();
$pagetitle = 'Items';

if (isset($_SESSION['Username'])) {
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'manage';
    if($do=='manage'){

        $query='';
        if(isset($_GET['page'])&&$_GET['page']=='pending'){
            $query='AND Approve =0 ';
        }
        $stmt = $con->prepare("SELECT
                                         items.* ,categorie.Name
                                        AS categorie_name,users.Username 
                                FROM
                                         items
                                INNER JOIN 
                                        categorie
                                ON
                                         categorie.Id=items.Cat_ID 
                                INNER JOIN
                                         users
                                ON
                                    users.UseriD=items.Member_ID");
        $stmt->execute();  
        // Assign to variable  
        $items = $stmt->fetchAll();
        ?>

        <h1 class="text-center">Manage Items</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="table text-center table-bordered">
                    <tr>
                        <td>#ID</td>   
                        <td>Name</td>   
                        <td>Description</td>  
                        <td>Price</td> 
                        <td>Adding date</td> 
                        <td>Categorie</td> 
                        <td>User_additem</td> 
                        <td>Control</td> 
                    </tr>
                    <?php foreach($items as $item) {
                        echo "<tr>";
                            echo "<td>" . $item['item_ID'] . "</td>";
                            echo "<td>" . $item['Name'] . "</td>";
                            echo "<td>" . $item['Description'] . "</td>";
                            echo "<td>" . $item['Price'] . "</td>";
                            echo "<td>" . $item['Add_Date'] . "</td>";
                            echo "<td>" . $item['categorie_name'] . "</td>";
                            echo "<td>" . $item['Username'] . "</td>";

                            echo "<td>
                                 <a href='items.php?do=edit&itemid=". $item['item_ID']."' class='btn btn-success'>Edit</a>
                                 <a href='items.php?do=delete&itemid=". $item['item_ID']."' class='btn btn-danger confirm'>Delete </a>";
                                 if($item['Approve']==0){
                                    echo "<a href='items.php?do=approve&itemid=". $item['item_ID']."' class='btn btn-info'> Approve</a>";
                                 }
                                 echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
            <a href="items.php?do=add" class ="btn btn-primary"><i class="fa fa-plus"></i> Add new item </a>
        </div>
    
    <?php 
    }elseif($do=='add'){ ?>
        <h1 class="text-center">Add new item</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=insert" method="POST">
                <!-- Start name field -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="name" class="form-control" placeholder="Write Name of item">
                    </div>
                </div>
                <!-- End name field -->
                <!-- Start Description field -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="description" class="form-control"  placeholder="Write Description of item">
                    </div>
                </div>
                <!-- End Description field -->
                <!-- Start price field -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Price</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="price" class="form-control"  placeholder="Write price of item">
                    </div>
                </div>
                <!-- End price field -->
                <!-- Start countery_made field -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Countery Made</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="counterymade" class="form-control"   placeholder="Write countery made of item">
                    </div>
                </div>
                <!-- End countery_made field -->
                <!-- Start status field -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-10 col-md-6">
                        <select class="form-control" name="status">
                            <option value="0">Select</option>
                            <option value="New">New</option>
                            <option value="Like New">Like New</option>
                            <option value="Used">Used</option>
                            <option value="Old">Old</option>
                        </select>
                    </div>
                </div>
                <!-- End status field -->
                <!-- Start member field -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Member</label>
                    <div class="col-sm-10 col-md-6">
                        <select class="form-control" name="member">
                            <option value="0">Select</option>
                           <?php
                           $getallmember=getallfrom("*","users","UseriD","","","DESC");
                           //the line previous make the same jop to the next lines
                        //    $stmt=$con->prepare("SELECT *FROM users");
                        //    $stmt->execute();
                        //    $users=$stmt->fetchAll();
                           foreach($getallmember as $user){
                             echo "<option value='".$user['UseriD']."'>".$user['Username']."</option> ";
                           }
                           ?>
                        </select>
                    </div>
                </div>
                <!-- End member field -->
                <!-- Start categorie field -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Categorie</label>
                    <div class="col-sm-10 col-md-6">
                        <select class="form-control" name="categorie">
                            <option value="0">Select</option>
                           <?php
                           $getallcat=getallfrom("*","categorie","Id","where Parent=0","","DESC");
                        //    $stmt=$con->prepare("SELECT *FROM categorie");
                        //    $stmt->execute();
                        //    $categories=$stmt->fetchAll();
                           foreach($getallcat as $categorie){
                             echo "<option value='".$categorie['Id']."'>".$categorie['Name']."</option> ";
                           $getchildcat=getallfrom("*","categorie","Id","where Parent={$categorie['Id']}","","DESC");
                           foreach($getchildcat as $child){
                            echo "<option value='".$child['Id']."'>----".$child['Name']."</option> ";
                           }
                           }
                           ?>
                        </select>
                    </div>
                </div>
                <!-- End categorie field -->
                <!-- Start tags field -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Tags</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="tags" class="form-control"   placeholder="seperate tags with comma (,)">
                    </div>
                </div>
                <!-- End tags field -->
                <!-- Start rating field -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Rating</label>
                    <div class="col-sm-10 col-md-6">
                        <select class="form-control" name="rating">
                            <option value="0">Select</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                        </select>
                    </div>
                </div>
                <!-- End rating  field -->
                    
                <!-- Start submit field -->
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add item" class="btn btn-primary btn-lg">
                    </div>
                </div>
                <!-- End submit field -->
            </form>
        </div>

        <?php
    }elseif($do=='insert'){

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Insert Items</h1>";
            echo "<div class='container'>";
            // Get variables from the form 
            $name= $_POST['name'];
            $desc= $_POST['description'];
            $price= $_POST['price'];
            $countery= $_POST['counterymade'];
            $status= $_POST['status'];
            $rating= $_POST['rating'];
            $member= $_POST['member'];
            $catg= $_POST['categorie'];
            $tags= $_POST['tags'];


            // Validate the form
            $formerror = array();
            if (empty($name) ) {
                $formerror[] = 'name can\'t be <strong> empty</strong>';
            }
            if (empty($desc)) {
                $formerror[] = 'Description can\'t be <strong> empty</strong>';
            }
            if (empty($price)) {
                $formerror[] = 'price can\'t be <strong> empty</strong>';
            }
            if (empty($countery)) {
                $formerror[] = 'countery can\'t be <strong> empty</strong>';
            }
            if ($status==0) {
                $formerror[] = 'you must choose the <strong> status</strong>';
            }
            if ($member==0) {
                $formerror[] = 'you must choose the <strong> member</strong>';
            }
            if ($catg==0) {
                $formerror[] = 'you must choose the <strong> categorie</strong>';
            }

            foreach ($formerror as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }

            if (empty($formerror)) {
            

                 

                    // Insert into the database 
                    $stmt = $con->prepare("INSERT INTO 
                    items(Name, Description, Price, Countery_Made,status ,Add_Date,Rating,Cat_ID,Member_ID,Tags)
                     VALUES(:zname, :zdesc, :zprice, :zcountery,:zstaus ,now(),:zrate,:zcat,:zmember ,:ztags  )");
                       $stmt->execute(array(
                        'zname'     => $name,
                        'zdesc'     => $desc,
                        'zprice'    => $price,
                        'zcountery' => $countery,
                        'zstaus'    => $status,
                        'zrate'     => $rating,
                        'zmember'   => $member,
                        'zcat'      => $catg,
                        'ztags'      => $tags,
                    ));
                    $themsg= "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Inserted</div>";
                    redirecthome($themsg);
            
            }
        } else {
            //if it come without use request method 
            echo "<div class='container'>";
            $themsg= "<div class='alert alert-danger'>Sorry, you cannot browse this page directly.</div>";
            redirecthome($themsg);
            echo "</div>";  

        }  
        echo "</div>";
    }elseif($do=='edit'){
        if (isset($_GET['itemid']) && is_numeric($_GET['itemid'])) {
            $itemid = intval($_GET['itemid']);
        } else {
            echo 0;
        }

        $stmt = $con->prepare("SELECT * FROM items WHERE item_ID  = ? ");
        $stmt->execute(array($itemid));
        $item = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($count > 0) { ?>
            <h1 class="text-center">Edit Item</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=update" method="POST">
                    <!--we need this hidden beacuse we need it in updat page -->
                    <input type="hidden" name="itemid" value="<?php echo $itemid; ?>">
                    <!-- Start Name field -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" name="name" class="form-control" value="<?php echo $item['Name']; ?>" autocomplete="off" required="required">
                        </div>
                    </div>
                    <!-- End Name field -->
                    <!-- Start Description field -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10">
                            <input type="text" name="description" class="form-control" autocomplete="off" value="<?php echo $item['Description']; ?>" placeholder="Leave blank if you don't want to change">
                        </div>
                    </div>
                    <!-- End Description field -->
                    <!-- Start Price field -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-10">
                            <input type="text" name="price" class="form-control" value="<?php echo $item['Price']; ?>" >
                        </div>
                    </div>
                    <!-- End Price field -->
                    <!-- Start Countery Made field -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Countery Made</label>
                        <div class="col-sm-10">
                            <input type="text" name="counterymade" class="form-control" required="required" value="<?php echo $item['Countery_Made']; ?>">
                        </div>
                    </div>
                    <!-- End Countery made field -->
                    <!-- Start status field -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-10 col-md-6">
                        <select class="form-control" name="status" >
                            <option value="0">Select</option>
                            <option value="New" <?php if($item['status']=='New'){echo 'selected';} ?>>New</option>
                            <option value="Like New" <?php if($item['status']=='Like New'){echo 'selected';} ?>>Like New</option>
                            <option value="Used" <?php if($item['status']=='Used'){echo 'selected';} ?>>Used</option>
                            <option value="Old" <?php if($item['status']=='Old'){echo 'selected';} ?>>Old</option>
                        </select>
                    </div>
                </div>
                <!-- End status field -->
                <!-- Start member field -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Member</label>
                    <div class="col-sm-10 col-md-6">
                        <select class="form-control" name="member" value="<?php echo $item['Member']; ?>">
                            <option value="0">Select</option>
                           <?php
                           $users=getallfrom("*", "users", "UseriD", "","","DESC");
                           //we can use perviou line or next line inforeach for get user from date base  
                        //    $stmt=$con->prepare("SELECT *FROM users");
                        //    $stmt->execute();
                        //    $users=$stmt->fetchAll();
                           foreach($users as $user){
                             echo "<option value='".$user['UseriD']."'";
                             if($item['Member_ID']== $user['UseriD']){echo 'selected';}
                             echo ">".$user['Username']."</option>";
                           }
                           ?>
                        </select>
                    </div>
                </div>
                <!-- End member field -->
                <!-- Start categorie field -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Categorie</label>
                    <div class="col-sm-10 col-md-6">
                        <select class="form-control" name="categorie" value="<?php echo $item['Categorie']; ?>">
                            <option value="0">Select</option>
                           <?php
                           $categories=getallfrom("*", "categorie", "Id", "","","DESC");
                        //    $stmt=$con->prepare("SELECT *FROM categorie");
                        //    $stmt->execute();
                        //    $categories=$stmt->fetchAll();
                           foreach($categories as $categorie){
                             echo "<option value='".$categorie['Id']."'";
                             if($item['Cat_ID']== $categorie['Id']){echo 'selected';}
                             echo ">".$categorie['Name']."</option> ";
                           }
                           ?>
                        </select>
                    </div>
                </div>
                <!-- End categorie field -->
                <!-- Start tags field -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Tags</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="tags" class="form-control"   placeholder="seperate tags with comma (,)" value="<?php echo $item['Tags']; ?>">
                    </div>
                </div>
                <!-- End tags field -->
                <!-- Start rating field -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Rating</label>
                    <div class="col-sm-10 col-md-6">
                        <select class="form-control" name="rating">
                            <option value="0">Select</option>
                            <option value="1"  <?php if($item['Rating']=='1'){echo 'selected';} ?>>1</option>
                            <option value="2"  <?php if($item['Rating']=='2'){echo 'selected';} ?>>2</option>
                            <option value="3"  <?php if($item['Rating']=='3'){echo 'selected';} ?>>3</option>
                            <option value="4"  <?php if($item['Rating']=='4'){echo 'selected';} ?>>4</option>
                            <option value="5"  <?php if($item['Rating']=='5'){echo 'selected';} ?>>5</option>
                            <option value="6"  <?php if($item['Rating']=='6'){echo 'selected';} ?>>6</option>
                            <option value="7"  <?php if($item['Rating']=='7'){echo 'selected';} ?>>7</option>
                            <option value="8"  <?php if($item['Rating']=='8'){echo 'selected';} ?>>8</option>
                        </select>
                    </div>
                </div>
                <!-- End rating  field -->
                    
                <!-- Start submit field -->
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="update item" class="btn btn-primary btn-lg">
                    </div>
                </div>
                <!-- End submit field -->
            </form>

            <?php
            $stmt = $con->prepare("SELECT 
                                   comments.* ,users.Username AS User_Name 
                                 FROM 
                                    comments
                                INNER JOIN 
                                    users
                                ON 
                                    users.UseriD=comments.User_id
                                WHERE 
                                    Item_id= ?"
                                    
                                );
        $stmt->execute(array($itemid));  
        // Assign to variable  
        $rows = $stmt->fetchAll();
        if(! empty($rows)){
        ?>

        <h1 class="text-center">Manage [ <?php echo $item['Name']; ?>] Comment</h1>
            <div class="table-responsive">
                <table class="table text-center table-bordered">
                    <tr>
                        <td> Comment</td>   
                        <td>User Name </td> 
                        <td>Added date</td> 
                        <td>Control</td> 
                    </tr>
                    <?php foreach($rows as $row) {
                        echo "<tr>";
                            echo "<td>" . $row['Comment'] . "</td>";
                            echo "<td>" . $row['User_Name'] . "</td>";// we assign it after make inner join 
                            echo "<td>" . $row['Comment_date'] . "</td>";

                            echo "<td>
                                 <a href='comments.php?do=edit&comid=". $row['C_id']."' class='btn btn-success'>Edit</a>
                                 <a href='comments.php?do=delete&comid=". $row['C_id']."' class='btn btn-danger confirm'>Delete </a>";

                                 if($row['Status']==0){
                                    echo "<a href='comments.php?do=approve&comid=". $row['C_id']."' class='btn btn-info'> Approve</a>";
                                 }
                             
                                 echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
            <?php  }?>
        </div>
<?php 
        } else {
            echo "<div class='container' >"; 
            $themsg= '<div class="alert alert-danger">No matching ID found.</div>';
            redirecthome($themsg);
            echo "</div>";
        }
    } elseif($do=='update'){
        echo "<h1 class='text-center'>Update Item</h1>";
        echo "<div class='container'>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                       // Get variables from the form 
                       $id=$_POST['itemid'];//this is the use of hidden class
                       $name= $_POST['name'];
                       $desc= $_POST['description'];
                       $price= $_POST['price'];
                       $countery= $_POST['counterymade'];
                       $status= $_POST['status'];
                       $rating= $_POST['rating'];
                       $member= $_POST['member'];
                       $catg= $_POST['categorie'];
                       $tags= $_POST['tags'];
           
           
                       // Validate the form
                       $formerror = array();
                       if (empty($name) ) {
                           $formerror[] = 'name can\'t be <strong> empty</strong>';
                       }
                       if (empty($desc)) {
                           $formerror[] = 'Description can\'t be <strong> empty</strong>';
                       }
                       if (empty($price)) {
                           $formerror[] = 'price can\'t be <strong> empty</strong>';
                       }
                       if (empty($countery)) {
                           $formerror[] = 'countery can\'t be <strong> empty</strong>';
                       }
                       if ($status==0) {
                           $formerror[] = 'you must choose the <strong> status</strong>';
                       }
                       if ($member==0) {
                           $formerror[] = 'you must choose the <strong> member</strong>';
                       }
                       if ($catg==0) {
                           $formerror[] = 'you must choose the <strong> categorie</strong>';
                       }
           
                       foreach ($formerror as $error) {
                           echo '<div class="alert alert-danger">' . $error . '</div>';
                       }

                if (empty($formerror)) {
                        // Update the database with this info
                        $stmt = $con->prepare("UPDATE
                                                   items 
                                                SET
                                                    Name = ?,
                                                    Description = ?, 
                                                    Price = ?,
                                                    Countery_Made = ?,
                                                    status = ?, 
                                                    Cat_ID = ?,
                                                    Member_ID = ?,
                                                    Rating = ?,
                                                    Tags=?
                                                WHERE
                                                    item_ID = ?");
                        $stmt->execute(array($name, $desc, $price, $countery, $status,$catg,$member,$rating,$tags,$id));
                        $themsg= "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Updated</div>";
                        redirecthome($themsg, 'back');
        } else {
            echo "<div class='container' >";
            $themsg= '<div class="alert alert-danger">Sorry, you cannot browse this page directly.</div>';
            redirecthome($themsg, 'back',3);
            echo "</div>";
        }
        echo "</div>";  
    }
    } elseif ($do == 'delete') {

        // Delete member
        echo "<h1 class='text-center'>Delete item</h1>";
        echo "<div class='container'>";
        if (isset($_GET['itemid']) && is_numeric($_GET['itemid'])) {
            $itemid = intval($_GET['itemid']);

            // Check if user exists in the database
             $check= checkitem('item_ID','items',$itemid);
    

            if ($check > 0) {
                //delete item
                $stmt = $con->prepare("DELETE FROM items WHERE item_ID = :zid");
                $stmt->bindParam(":zid", $itemid);
                $stmt->execute();

                $themsg ="<div class='alert alert-success'>" . $stmt->rowCount() . " Member  Deleted</div>";
                redirecthome($themsg,'back');
            } else {
                $themsg= "<div class='alert alert-danger'>No such user found.</div>"; 
                redirecthome($themsg);
            }
        } else {
                echo "<div class='alert alert-danger'>Invalid or missing user ID.</div>";
            
        echo '</div>';
              } 
          }elseif($do=='approve'){
            echo "<h1 class='text-center'>Approve Item</h1>";
            echo "<div class='container'>";
            if (isset($_GET['itemid']) && is_numeric($_GET['itemid'])) {
                $itemid = intval($_GET['itemid']);
    
                // Check if item exists in the database
                 $check= checkitem('item_ID','items',$itemid);
        
    
                if ($check > 0) {
                    //delete user
                    $stmt = $con->prepare("UPDATE items set Approve=1 where item_ID=?");
                    $stmt->execute(array($itemid));
    
                    $themsg ="<div class='alert alert-success'>" . $stmt->rowCount() . " Item Approve</div>";
                    redirecthome($themsg);
                } else {
                    $themsg= "<div class='alert alert-danger'>No such item found.</div>"; 
                    redirecthome($themsg);
                }
            } else {
                    echo "<div class='alert alert-danger'>Invalid or missing item ID.</div>";
                
            echo '</div>';
          }
          }

    include $tpl . 'footer.php'; // Include footer
 }else {
    header('Location: index.php'); // Redirect to login page if user is not logged in
    exit();
}

ob_end_flush(); // End output buffering
?>