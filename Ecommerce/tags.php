
<?php
session_start(); 
include 'init.php'; ?> 


    <?php 
       
    if (isset($_GET['name'])) {
        $tag = $_GET['name'];
        echo "<h1 class='text-center' >". $tag."</h1>";
        
    $tagitem= getallfrom("*","items","item_ID","where Tags like '%$tag%'","AND Approve =1","ASC");  
    foreach ($tagitem as $item){
        echo '<div class="col-sm-6 col-md-3">';
            echo '<div class="thumbnail item-box">';
            echo '<span>'.$item['Price'].'</span>';
            
            echo '<image class="img-responsive" src="img.png" alt="" />';
                echo '<div class"caption" >';
                    echo '<h3><a href="items.php?itemid='.$item['item_ID'].'">'.$item['Name'].'</a></h3>';
                    echo '<p>'.$item['Description'].'</p>';
                    echo '<div class="date">'.$item['Add_Date'].'</div>';
                echo '</div>';
                echo '</div>';
        echo '</div>';
    }
}else{
    echo '<div class="alert alert-danger"> You Must Enter Tag Name</div>'; 
}
   
    ?>
    </div>
</div>
<?php include $tpl . 'footer.php'; ?>  