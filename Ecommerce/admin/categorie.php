<?php

//Categorie page
ob_start();
session_start();
$pagetitle = 'Categorie';

if (isset($_SESSION['Username'])) {
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'manage';
    if($do=='manage'){
        $sort='ASC';
        $sort_array=array('ASC','DESC');
        if(isset($_GET['sort'])&&in_array($_GET['sort'],$sort_array)){
            $sort=$_GET['sort'];
        }
        // $methodorder='Ordering';

        $stmt2= $con->prepare("SELECT * FROM categorie where Parent=0 ORDER BY Id $sort");
        $stmt2->execute();
        $categ=$stmt2->fetchAll(); ?>
           <h1 class="text-center">Manage Categorie</h1> 
           <div class="container categorie">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Manage Categorie
                        <div class="ordering pull-right">
                            Ordering=>
                            <a href="?sort=ASC">ASC</a>
                            OR
                            <a href="?sort=DESC">DESC</a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <?php
                            foreach($categ as $cat){

                                 echo "<div class='cat'>";
                                    echo "<div class='hidden-buttons'>";
                                        echo "<a href='categorie.php?do=edit&catid=". $cat['Id']."' class='btn btn-xs btn-primary'>Edit </a>";
                                        echo "<a href='categorie.php?do=delete&catid=". $cat['Id']."' class='btn btn-xs btn-danger'>Delete </a>";
                                echo "</div>";
                                echo "<h3>" .$cat['Name']. '</h3>';
                                echo $cat['Description']. '<br>';
                                echo 'ID is '. $cat['Id']. '<br>';
                                echo 'Ordering is '. $cat['Ordering']. '<br>';
                                echo 'Visibility is ' .$cat['Visibility']. '<br>';
                                echo 'Allow_comment is '. $cat['Allow_comment']. '<br>';
                                echo 'Allow_ads is ' .$cat['Allow_ads']. '<br>';
                                

                            echo "</div>";
                            echo "<hr>";

                            //get child cat
                            $childcats=getallfrom("*","categorie","Id","where Parent={$cat['Id']}","","ASC"); 
                            if(!empty($childcats)){
                                echo "<h4 class='child-head'>Child categorie</h4>";
                                echo "<ul class='list-unstyled child-cats'>";
                            foreach($childcats as $childcat){
                                // echo '<li class="nav-item"><a href="categorie.php?pageid='.$cat['Id'].' " class="nav-link">
                                //     '. $childcat['Name'].'
                                //   </a>
                                // </li>';
                                echo "<li>
                                <a href='categorie.php?do=edit&catid=". $childcat['Id']."' class='child-link' >".$childcat['Name']."</a>
                                <a href='categorie.php?do=delete&catid=". $childcat['Id']."' class='show-delete confirm'>Delete </a>
                                </li>";
                            }
                            echo "</ul>";
                            echo "<hr>";
                            

                            }}
                        
                        ?>
                    </div>
                </div>
                <a class="btn btn-primary" href="categorie.php?do=add">Add categorie</a>
           </div>
        <?php
    }elseif($do=='add'){ ?>
    
          <h1 class="text-center">Add new Category</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=insert" method="POST">
                <!-- Start name field -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="name" class="form-control" required="required" placeholder="Write Name of category">
                    </div>
                </div>
                <!-- End name field -->
                <!-- Start description field -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="description" class="form-control" placeholder="Write description of catogery">
                    </div>
                </div>
                <!-- End description field -->
                <!-- Start ordering field -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Ordering</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="ordering" class="form-control" placeholder="number to arrange category" />
                    </div>
                </div>
                <!-- End ordering field -->
                <!-- Start Categorie type -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Parent?</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="parent">
                            <option value="0">None</option>
                            <?php
                          $categories= getallfrom('*','categorie','Id','where Parent=0','','DESC');
                           foreach($categories as $categorie){
                             echo "<option value='".$categorie['Id']."'>".$categorie['Name']."</option> ";
                           }
                           ?>
                        </select>
                    </div>
                </div>
                <!-- end Categorie type -->
                <!-- Start visibility field -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Visibility</label>
                    <div class="col-sm-10 col-md-6">
                    <div>
                            <input id="vis-yes" type="radio" name="visibility" value="0" checked />
                            <label for="vis-yes" >Yes</label>
                        </div>
                        <div>
                            <input id="vis-no" type="radio" name="visibility" value="1"  />
                            <label for="vis-no" >No</label>
                        </div>
                    </div>
                </div>
                <!-- End visibility field -->
               <!-- Start comment field -->
               <div class="form-group">
                    <label class="col-sm-2 control-label">Commenting</label>
                    <div class="col-sm-10 col-md-6">
                    <div>
                            <input id="com-yes" type="radio" name="comment" value="0" checked />
                            <label for="com-yes" >Yes</label>
                        </div>
                        <div>
                            <input id="com-no" type="radio" name="comment" value="1"  />
                            <label for="com-no" >No</label>
                        </div>
                    </div>
                </div>
                <!-- End comment field -->
               <!-- Start ads field -->
               <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Allow Ads</label>
                    <div class="col-sm-10 col-md-6">
                    <div>
                            <input id="ads-yes" type="radio" name="ads" value="0" checked />
                            <label for="ads-yes" >Yes</label>
                        </div>
                        <div>
                            <input id="ads-no" type="radio" name="ads" value="1"  />
                            <label for="ads-no" >No</label>
                        </div>
                    </div>
                </div>
                <!-- End ads field -->
                <!-- Start submit field -->
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Categorie" class="btn btn-primary btn-lg">
                    </div>
                </div>
                <!-- End submit field -->
            </form>
        </div>

        <?php
    }elseif($do=='insert'){

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Insert Category</h1>";
            echo "<div class='container'>";
            // Get variables from the form 
            $name = $_POST['name'];
            $desc = $_POST['description'];
            $parent=$_POST['parent'];
            $order = $_POST['ordering'];
            $visi = $_POST['visibility'];
            $comment = $_POST['comment'];
            $ads = $_POST['ads'];

            

            
            //check if user exit in database
                $check=checkitem("Name","categorie",$name); 
                if($check==1){
                $themsg ="<div class='alert alert-danger'>this categorie is exist.</div>";
                    redirecthome($themsg,'back');//back can change to anything except null beacuse null redirect to homepage 

                }else{

                // Insert into the database 
                $stmt = $con->prepare("INSERT INTO categorie(Name, Description,Parent, Ordering, Visibility,Allow_comment,Allow_ads) VALUES(:zname, :zdesc,:zparent ,:zorder, :zvisi, :zcomment,:zallow)");
                $stmt->execute(array(
                    'zname' => $name,
                    'zdesc' => $desc,
                    'zparent'=>$parent,
                    'zorder' => $order,
                    'zvisi' => $visi,
                    'zcomment' => $comment,
                    'zallow' => $ads
                ));
                $themsg= "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Inserted</div>";
                redirecthome($themsg, 'back',3);
        }
            
        } else {
            echo "<div class='container'>";
            $themsg= "<div class='alert alert-danger'>Sorry, you cannot browse this page directly.</div>";
            redirecthome($themsg, 'back',3);
            echo "</div>";  

        }  
        echo "</div>";  

    }elseif($do=='edit'){
        // if (isset($_GET['catid']) && is_numeric($_GET['catid'])) {
        //     $catid = intval($_GET['catid']);
        // } else {
        //     echo 0;
        // }

        $catid=isset($_GET['catid'])&& is_numeric($_GET['catid'])? intval($_GET['catid']):0;

        $stmt = $con->prepare("SELECT * FROM categorie WHERE Id = ? ");
        $stmt->execute(array($catid));
        $cat = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($count > 0) { ?>
            <h1 class="text-center">Edit Category</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=update" method="POST">
                <!--the next line use in update page -->
                <input type="hidden" name="catid" value="<?php echo $catid; ?>" />
                    <!-- Start name field -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="name" class="form-control" required="required" placeholder="Write Name of category" value=<?php echo $cat['Name'] ?>>
                        </div>
                    </div>
                    <!-- End name field -->
                    <!-- Start description field -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="description" class="form-control" placeholder="Write description of catogery" value=<?php echo $cat['Description'] ?>>
                        </div>
                    </div>
                    <!-- End description field -->
                    <!-- Start ordering field -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Ordering</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="ordering" class="form-control" placeholder="number to arrange category" value=<?php echo $cat['Ordering'] ?> />
                        </div>
                    </div>
                    <!-- End ordering field -->
                    <!-- Start Categorie type -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Parent?</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="parent">
                            <option value="0">None</option>
                            <?php
                          $categories= getallfrom('*','categorie','Id','where Parent=0','','DESC');
                           foreach($categories as $categorie){
                             echo "<option value='".$categorie['Id']."'";
                             if($cat['Parent']==$categorie['Id']){
                                echo 'selected';
                             }
                             echo ">".$categorie['Name']."</option> ";
                           }
                           ?>
                        </select>
                    </div>
                </div>
                    <!-- end Categorie type -->
                    <!-- Start visibility field -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Visibility</label>
                        <div class="col-sm-10 col-md-6">
                        <div>
                                <input id="vis-yes" type="radio" name="visibility" value="0"  <?php if($cat['Visibility']==0){echo 'checked';} ?>/>
                                <label for="vis-yes" >Yes</label>
                            </div>
                            <div>
                                <input id="vis-no" type="radio" name="visibility" value="1" <?php if($cat['Visibility']==1){echo 'checked';} ?> />
                                <label for="vis-no" >No</label>
                            </div>
                        </div>
                    </div>
                    <!-- End visibility field -->
                <!-- Start comment field -->
                <div class="form-group">
                        <label class="col-sm-2 control-label">Commenting</label>
                        <div class="col-sm-10 col-md-6">
                        <div>
                                <input id="com-yes" type="radio" name="comment" value="0" <?php if($cat['Visibility']==0){echo 'checked';} ?> />
                                <label for="com-yes" >Yes</label>
                            </div>
                            <div>
                                <input id="com-no" type="radio" name="comment" value="1" <?php if($cat['Visibility']==1){echo 'checked';} ?> />
                                <label for="com-no" >No</label>
                            </div>
                        </div>
                    </div>
                    <!-- End comment field -->
                <!-- Start ads field -->
                <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Allow Ads</label>
                        <div class="col-sm-10 col-md-6">
                        <div>
                                <input id="ads-yes" type="radio" name="ads" value="0" <?php if($cat['Visibility']==0){echo 'checked';} ?> />
                                <label for="ads-yes" >Yes</label>
                            </div>
                            <div>
                                <input id="ads-no" type="radio" name="ads" value="1" <?php if($cat['Visibility']==1){echo 'checked';} ?> />
                                <label for="ads-no" >No</label>
                            </div>
                        </div>
                    </div>
                    <!-- End ads field -->
                    <!-- Start submit field -->
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value=" Edit Categorie" class="btn btn-primary btn-lg">
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
    

    }elseif($do=='update'){
        echo "<h1 class='text-center'>Update Categorie</h1>";
        echo "<div class='container'>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id= $_POST['catid'];
            $name = $_POST['name'];
            $desc = $_POST['description'];
            $order = $_POST['ordering'];
            $parent = $_POST['parent'];
            $visi = $_POST['visibility'];
            $comment = $_POST['comment'];
            $ads = $_POST['ads'];

                // Update the database with this info
                $stmt = $con->prepare("UPDATE
                                           categorie 
                                        SET
                                            Name = ?,
                                            Description = ?, 
                                            Ordering = ?,
                                            Parent=?,
                                            Visibility = ?,
                                            Allow_comment = ?, 
                                            Allow_ads = ?
                                          WHERE 
                                             Id = ?");
                $stmt->execute(array($name, $desc, $order,$parent, $visi, $comment,$ads,$id));
                $themsg= "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Updated</div>";
                redirecthome($themsg, 'back');

            
        } else {
            echo "<div class='container' >";
            $themsg= '<div class="alert alert-danger">Sorry, you cannot browse this page directly.</div>';
            redirecthome($themsg, 'back',3);
            echo "</div>";
        }
        echo "</div>";  
    }elseif($do=='delete'){

        // Delete member
        echo "<h1 class='text-center'>Delete Categorie</h1>";
        echo "<div class='container'>";
        if (isset($_GET['catid']) && is_numeric($_GET['catid'])) {
            $catid = intval($_GET['catid']);

            // Check if user exists in the database
                $check= checkitem('Id','categorie',$catid);
    

            if ($check > 0) {
                //delete user
                $stmt = $con->prepare("DELETE FROM categorie WHERE Id = :zid");
                $stmt->bindParam(":zid", $catid);
                $stmt->execute();

                $themsg ="<div class='alert alert-success'>" . $stmt->rowCount() . " Member  Deleted</div>";
                redirecthome($themsg,'previous');
            } else {
                $themsg= "<div class='alert alert-danger'>No such user found.</div>"; 
                redirecthome($themsg);
            }
        } else {
                echo "<div class='alert alert-danger'>Invalid or missing user ID.</div>";
            
        echo '</div>';
                } 
    }
    include $tpl. 'footer.php';

}else{
    header('Location:index.php');
    exit();
}

ob_end_flush();

?>