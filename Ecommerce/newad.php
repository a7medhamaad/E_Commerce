<?php
session_start();  
$pagetitle='Create New Item';
include 'init.php'; 
if(isset($_SESSION['user'])){
    // print_r($_SESSION);
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $name=  filter_var( $_POST['name'],FILTER_SANITIZE_SPECIAL_CHARS);
        $desc= filter_var( $_POST['description'],FILTER_SANITIZE_SPECIAL_CHARS);
        $price= filter_var( $_POST['price'],FILTER_SANITIZE_NUMBER_INT);
        $countery= filter_var( $_POST['counterymade'],FILTER_SANITIZE_SPECIAL_CHARS);
        $status=  $_POST['status'];
        $rating= $_POST['rating'];
        $catg= filter_var( $_POST['categorie'],FILTER_SANITIZE_NUMBER_INT);
        $tags= filter_var( $_POST['tags'],FILTER_SANITIZE_SPECIAL_CHARS);


        // Validate the form
        $formerror = array();
        if(strlen($name)<4){
            $formerror[]='item name must be at least 4 char';
        }
        if(strlen($desc)<10){
            $formerror[]='item description must be at least 10 char';
        }
        if(strlen($countery)<2){
            $formerror[]='item countery must be at least 2 char';
        }
        if(empty($price)){
            $formerror[]='item price can not be empty';
        }
        if(empty($status)){
            $formerror[]='item status can not be empty';
        }
        if(empty($catg)){
            $formerror[]='item categorie can not be empty';
        }

        if (empty($formerror)) {
            

                 

            // Insert into the database 
            $stmt = $con->prepare("INSERT INTO 
            items(Name, Description, Price, Countery_Made,status ,Add_Date,Rating,Cat_ID,Member_ID,Tags)
             VALUES(:zname, :zdesc, :zprice, :zcountery,:zstaus ,now(),:zrate,:zcat,:zmember,:ztags  )");
               $stmt->execute(array(
                'zname'     => $name,
                'zdesc'     => $desc,
                'zprice'    => $price,
                'zcountery' => $countery,
                'zstaus'    => $status,
                'zrate'     => $rating,
                'zmember'   => $_SESSION['uid'],
                'zcat'      => $catg,
                'ztags'      => $tags,
            ));
           if($stmt){

            $successmsg='Item has been added';
           }
    }
      

 
    }   
    
?>
 <h1 class="text-center"><?php echo $pagetitle ?></h1>
    <div class="create-ad block ">
        <div class="container">
             <div class="panel panel-primary">
                <div class="panel-heading"><h3><?php echo $pagetitle ?></h3></div>
                <div class="panel-body">
                    <div class="row">
                    <div class="col-md-8">
                    <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <!-- Start name field -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-md-10">
                        <input required pattern=".{4,}" title="this field requiered at least 4 char" type="text" name="name" class="form-control live-name" placeholder="Write Name of item"  >
                    </div>
                </div>
                <!-- End name field -->
                <!-- Start Description field -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-10">
                        <input required pattern=".{10,}" title="this field requiered at least 10 char"  type="text" name="description" class="form-control"  placeholder="Write Description of item" >
                    </div>
                </div>
                <!-- End Description field -->
                <!-- Start price field -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Price</label>
                    <div class="col-sm-10 col-md-10">
                        <input required type="text" name="price" class="form-control"  placeholder="Write price of item">
                    </div>
                </div>
                <!-- End price field -->
                <!-- Start countery_made field -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Countery Made</label>
                    <div class="col-sm-10 col-md-10">
                        <input requiered type="text" name="counterymade" class="form-control"   placeholder="Write countery made of item">
                    </div>
                </div>
                <!-- End countery_made field -->
                <!-- Start status field -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-10 col-md-10">
                        <select class="form-control" name="status" required>
                            <option value="0">Select</option>
                            <option value="New">New</option>
                            <option value="Like New">Like New</option>
                            <option value="Used">Used</option>
                            <option value="Old">Old</option>
                        </select>
                    </div>
                </div>
                <!-- End status field -->
                <!-- Start categorie field -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Categorie</label>
                    <div class="col-sm-10 col-md-10">
                        <select class="form-control" name="categorie" required>
                            <option value="0">Select</option>
                           <?php
                          $categories= getallfrom('*','categorie','Id','','');
                        //    $stmt=$con->prepare("SELECT *FROM categorie");
                        //    $stmt->execute();
                        //    $categories=$stmt->fetchAll();
                           foreach($categories as $categorie){
                             echo "<option value='".$categorie['Id']."'>".$categorie['Name']."</option> ";
                           }
                           ?>
                        </select>
                    </div>
                </div>
                <!-- End categorie field -->
                 <!-- Start tags field -->
                <div class="form-group">
                    <label class="col-sm-3 control-label">Tags</label>
                    <div class="col-sm-10 col-md-10">
                        <input type="text" name="tags" class="form-control"   placeholder="seperate tags with comma (,)">
                    </div>
                </div>
                <!-- Start rating field -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Rating</label>
                    <div class="col-sm-10 col-md-10">
                        <select class="form-control" name="rating" required>
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
                        <input type="submit" value="Add Ads" class="btn btn-primary btn-lg">
                    </div>
                </div>
                <!-- End submit field -->
            </form>
                    </div>
                    <div class="col-md-4">
                    <div class="thumbnail item-box live-preview">
                             <span>0</span>
                             <br>
                             <image class="img-responsive" src="img.png" alt="" />
                                 <div class="caption" >
                                     <h4>title</h4>
                                     <p>Description</p>
                                 </div>
                             </div>
                    </div>
                    </div>  
                        <!--start looping through errors--> 
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
                        <!--end looping through errors-->                
                                      
                </div>
             </div>
        </div>
        <hr>
    </div>

    
   
<?php 

}else{
    header('Location:login.php');
    exit();
}
include $tpl . 'footer.php'; 
 
 ?> 