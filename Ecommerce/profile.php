<?php
session_start();  
$pagetitle='Profile';
include 'init.php'; 
if(isset($_SESSION['user'])){
    global $con;
    $getuser=$con->prepare("SELECT * FROM users WHERE Username=?");
    $getuser->execute(array ($sessionuser));
    $info=$getuser->fetch();
    $userid=$info['UseriD'];
?>
 <h1 class="text-center">My Profile</h1>
    <div class="information block">
        <div class="container">
             <div class="panel panel-primary">
                <div class="panel-heading"><h3>My Information</h3></div>
                <div class="panel-body">
                    <ul class="list-unstyled">
                       <li><span>Name</span>:<?php echo $info['Username']?></li> 
                        <li><span>Email</span>:<?php echo $info['Email']?></li> 
                        <li> <span>Full Name</span>:<?php echo $info['FullName']?></li> 
                        <li><span>Register date</span>:<?php echo $info['Date']?></li> 
                        <li><span> Favourit Categorie</span>:<?php echo $info['UseriD'] ?> </li> 
                    </ul>
                <a href="#" class="btn btn-default">Edit Information</a> 
                </div>
             </div>
        </div>
        <hr>
    </div>

    <div id="my-ads" class="my-ads block">
        <div class="container">
             <div class="panel panel-primary">
                <div class="panel-heading"><h3>My Ads</h3></div>
                <div class="panel-body">
                    <?php 
                    //we can use function "getitem" or function "getallfrom"
                    // $items=getitem('Member_ID',$info['UseriD'],1);
                    // if we want to show all ads approve and not approve we use no use approve in $item
                    // $items= getallfrom("*","items","item_ID","where Member_ID={$info['UseriD']}","AND Approve=1","ASC"); 
                    $items= getallfrom("*","items","item_ID","where Member_ID={$info['UseriD']}","","ASC"); 
                    if(!empty($items)){
                    foreach ($items as $item){
                        echo '<div class="col-sm-6 col-md-3">';
                            echo '<div class="thumbnail item-box">';
                            if($item['Approve']==0){ echo '<div class="alert alert-danger" >Need To Approve with admin</div>'; }
                            echo '<span class="price-tag">'.$item['Price'].'</span>';
                            echo '<image class="img-responsive" src="img.png" alt="" />';
                                echo '<div class="caption" >';
                                    echo '<h3><a href="items.php?itemid='.$item['item_ID'].'">'.$item['Name'].'</a></h3>';
                                    echo '<p>'.$item['Description'].'</p>';
                                    echo '<div class="date">'.$item['Add_Date'].'</div>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                        echo '<hr>';
                    }
                    echo '<a href="newad.php">new ads</a> ';
                }else{
                    echo 'here is no ads to show, create <a href="newad.php">new ads</a> ';

                }
                ?>
                </div>
             </div>
        </div>
    </div>
<hr>
    <div class="my-comment">
        <div class="container">
             <div class="panel panel-primary">
                <div class="panel-heading"><h3>Latest Comments</h3></div>
                <div class="panel-body">
            <?php
            //     $stmt = $con->prepare("SELECT 
            //                             Comment
            //                         FROM 
            //                             comments
            //                         WHERE 
            //                             User_id = ?"
                                        
            //                         );
            // $stmt->execute(array($info['UseriD']));  
            // // Assign to variable  
            // $comments = $stmt->fetchAll();
            $comments=getallfrom("Comment","comments","C_id","where User_id={$info['UseriD']}","","DESC"); 
            if(!empty($comments)){
                foreach($comments as $comment){
                    echo '<p>'.$comment['Comment'];
                }
                }else{
                    echo 'here is no comment to show';
            }
            ?>

                </div>
             </div>
        </div>
    </div>

   
<?php 

}else{
    header('Location:login.php');
    exit();
}
include $tpl . 'footer.php'; 
 
 ?> 