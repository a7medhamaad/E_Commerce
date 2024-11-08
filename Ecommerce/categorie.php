<?php include 'init.php'; ?> 

<div class="container" >
    <h1 class="text-center" >Show Categorie</h1>
    <div class="row">
    <?php 
       
    if (isset($_GET['pageid']) && is_numeric($_GET['pageid'])) {
        $categorie = intval($_GET['pageid']);
    $items= getallfrom("*","items","item_ID","where Cat_ID={$categorie}","AND Approve =1","ASC");  
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
}else{
    echo '<div class="alert alert-danger"> You Must Add Pageid</div>'; 
}
   
    ?>
    </div>
</div>
<?php include $tpl . 'footer.php'; ?>  