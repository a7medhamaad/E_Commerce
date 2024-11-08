<?php
  //ob_gzhandler=> this parameter mean stress content untile it flush it 
  // ob_start("ob_gzhandler"); 
  ob_start(); //output buffering start must but it befor session 
  session_start();
  if(isset($_SESSION['Username'])){
    $pagetitle='Dashboard';
    include 'init.php';
    //start dashboard page
    $latestusers=5 ;
    $thelatest= getlatest('*','users','UseriD',$latestusers);

    $thelatestitem=4;
    $latestitems= getlatest('*','items','item_ID',$thelatestitem);
    
    $numcomments=4;
      ?>
        <div class='container home-stats text-center'>
          <h1>Dashboard</h1>  
          <div class='row'>
            <div class='col-md-2'>
              <div class='stat st-members'> 
                Total Members 
                <span><a href="members.php?do=manage">
                <?php echo countitems('Username' ,'users') ?>
                  </a></span>
              </div>
            </div>
            <div class='col-md-2'>
              <div class='stat st-pending'>
                 Pending Members
                 <span><a href="members.php?do=manage&page=pending">
                  <?php echo  checkitem("RegStatus ","users",0) ?> 
                  </a></span>
                </div>
            </div>
            <div class='col-md-2'>
              <div class='stat st-pending'>
                 Pending Items
                 <span><a href="items.php?do=manage&page=pending">
                  <?php echo  checkitem("Approve ","items",0) ?> 
                  </a></span>
                </div>
            </div>
            <div class='col-md-2'>
              <div class='stat st-items'>
                 Total items
                 <span><a href="items.php?do=manage">
                <?php echo countitems('item_ID' ,'items') ?>
                  </a></span>
                </div>
            </div>
            <div class='col-md-2'>
              <div class='stat st-pending'>
                 Pending comment
                 <span><a href="comments.php?do=manage&page=pending">
                  <?php echo  checkitem("Status ","comments",0) ?> 
                  </a></span>
                </div>
            </div>
            <div class='col-md-2'>
              <div class='stat st-comment'>
                 Total comment
                 <span><a href="comments.php?do=manage">
                <?php echo countitems('C_id' ,'comments') ?>
                  </a></span>
                </div>
            </div>
          </div>
        </div>

        <div class="container latest">
             <div class="row">
                <div class="col-sm-6">
                  <div class="panel panel-default">
                    <div class="panel heading">
                      <i class="fa fa-users"></i> <h2>Latest <?php echo $latestusers ?> Register Users</h2>
                    </div>
                    <div class="panel-body">
                      <?php 
                      if(!empty($latestusers)){
                         foreach($thelatest as $user){
                           echo $user['Username'].'<br>';
                         }
                        } 
                        
                       ?>
                    </div>
                  </div>
                </div>
             </div>
        </div>

        <div class="container latest">
             <div class="row">
                <div class="col-sm-6">
                  <div class="panel panel-default">
                    <div class="panel heading">
                      <i class="fa fa-users"></i> <h2>Latest <?php echo $thelatestitem ?> Register items</h2>
                    </div>
                    <div class="panel-body">
                      <?php 
                      if(!empty($latestitems)){
                         foreach($latestitems as $user){
                           echo $user['Name'].'<br>';
                         }
                        } 
                        
                       ?>
                    </div>
                  </div>
                </div>
             </div>
        </div>

      <?php
    //end dashboard page
    include $tpl . 'footer.php';

  }else{
    echo 'you are not found in this page ';
    header('Location:index.php');
    exit();
  }
    ob_end_flush();
  ?>