<?php
ob_start();
session_start();  
$pagetitle='Home Page';
include 'init.php'; 
?>
<div class="container" >
    <div class="row">
    <?php 
        $items=getallfrom('*','items','item_ID','where Approve=1','','ASC');
        foreach ($items as $item){
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
    ?>
    </div>
</div>
<?php 

include $tpl . 'footer.php'; 
 ob_end_flush();
 ?> 