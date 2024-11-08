<?php
session_start();  
$pagetitle='Show Item';
include 'init.php'; 

if (isset($_GET['itemid']) && is_numeric($_GET['itemid'])) {
    $itemid = intval($_GET['itemid']);
} else {
    echo 0;
}

$stmt = $con->prepare("SELECT
                            items.* ,categorie.Name AS categorie_name,users.Username 
                        FROM
                            items
                        INNER JOIN 
                            categorie
                        ON
                            categorie.Id=items.Cat_ID 
                        INNER JOIN
                            users
                        ON
                            users.UseriD=items.Member_ID
                        WHERE 
                            item_ID  = ? 
                        AND 
                            Approve=1
                            ");
$stmt->execute(array($itemid));
$count=$stmt->rowCount();
if($count>0){
    $item = $stmt->fetch();

?>
 <h1 class="text-center"><?php echo $item['Name']; ?></h1>
 <div class="container">
     <div class="row">
     <div class="col-md-2">
     <image class="img-responsive img-thumbnail center-block" src="img.png"  alt="" />
    </div>
    <div class="col-md-10 item-info">
        <h2><?php echo $item['Name'] ?></h2>
        <p><?php echo $item['Description'] ?></p>
        <ul class="list-unstyled "></ul>
        <li>Added Date: <?php echo $item['Add_Date'] ?></li>
        <li>Price: <?php echo $item['Price'] ?></li>
        <li>Made in: <?php echo $item['Countery_Made'] ?></li>
        <li>Categorie Name: <a href="categorie.php?pageid=<?php echo $item['Cat_ID'] ?>"> <?php echo $item['categorie_name'] ?></a> </li>
        <li>Added By: <a href="#"><?php echo $item['Username'] ?></a> </li>
        <li class="tags-item">Tags:<?php $alltags=explode(",",$item['Tags']);
                    foreach($alltags as $tag){
                        $tag=str_replace(' ','',$tag);
                        $tag=strtolower($tag);
                        echo "<a href='tags.php?name={$tag}'>".$tag.'</a> |';
                    } 
        ?> </li>
        </ul>

      </div>
    </div>
    <hr class="custom-hr">
    <?php if(isset($_SESSION['user'])){ ?>
    <!-- start add comment -->
    <div class="row">
        <div class="col-md-offset-3">
            <div class="add-comment">
            <h3>add your comment</h3>
            <form action ="<?php echo $_SERVER['PHP_SELF'].'?itemid='.$item['item_ID'] ?> " method="POST">
                <textarea name="comment" required></textarea>
                <input class="btn btn-primary" type="submit" value="add comment">
            </form>
            <?php 
            if($_SERVER['REQUEST_METHOD']=='POST'){
                $comment= filter_var($_POST['comment'],FILTER_SANITIZE_SPECIAL_CHARS);
                $userid=$_SESSION['uid'];
                $itemid=$item['item_ID'];

                if(!empty($comment)){
                    $stmt=$con->prepare("INSERT INTO 
                                                comments(Comment,Status,Comment_date,Item_id,User_id )
                                        VALUES (:zcomment,0,now(),:zitem,:zuser)
                                        ");

                                        $stmt->execute(array(
                                            'zcomment'=>$comment,
                                            'zitem'=>$itemid,
                                            'zuser'=>$userid
                                        ));
                                    if($stmt){
                                        echo '<div class="alert alert-success ">Comment Added</div>';
                                    }
                }
            }
            ?>
            </div>
        </div>
    </div>
    <!-- end add comment -->
     <?php } else{
        echo '<a href="login.php">Login</a> or <a href="login.php">Register</a> to add comment';
        }?>
    <hr class="custom-hr">
    <?php    
             // Select all users except admin
            $stmt = $con->prepare("SELECT 
                                    comments.*,users.Username AS member
                                    FROM 
                                        comments
                                    INNER JOIN 
                                        users
                                    ON 
                                        users.UseriD=comments.User_id
                                    WHERE 
                                    Item_id= ?
                                    AND 
                                        Status=1 
                                    ORDER BY 
                                        C_id DESC "
                                        
                                    );
            $stmt->execute(array($item['item_ID']));  
            // Assign to variable  
            $comments = $stmt->fetchAll();
        ?>
       <?php  
           foreach($comments as $comment){ ?>
      <div class="comment-box">
      <div class="row">
                <div class="col-sm-2 text-center">
                    <image class="img-responsive img-thumbnail img-circle center-block"  src="img.png" alt="" />
                    <?php echo $comment['member'] ?>
                </div>
                <div class="col-md-9">
                    <p class="lead"><?php echo $comment['Comment']?></p>
                </div>
              
            </div>
      </div>
      <hr class ="custom-hr">
           <?php } ?>
       
</div>

<?php 
}else{
    echo '<div class="alert alert-danger text-center">there is no such id or this item waiting to approve</div>';
}
include $tpl . 'footer.php'; 
 ob_end_flush();
 ?> 