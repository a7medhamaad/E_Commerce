<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <title><?php echo isset($gettitle) ? gettitle() : "Default Title"; ?></title>
    <link rel="stylesheet" href="layout/css/frontend.css" /> 
</head>
    <body>
    <div class="upper-bar">
        <div class="container">
          <?php  
          if(isset($_SESSION['user'])) {?>
           <image class="my-image img-circle" src="img.png" alt="" />
           <div class="btn-group my-info text-left">
                <span class="btn btn-default dropdown-toggle" data-bs-toggle="dropdown">
                    <?php echo $sessionuser; ?>
                    <span class="caret"></span>
                </span>
                <ul class="dropdown-menu">
                    <li><a href="profile.php">My Profile</a></li>
                    <li><a href="newad.php">New Item</a></li>
                    <li><a href="profile.php#my-ads">My Items</a></li>
                    <li><a href="logout.php">LogOut</a></li>
                </ul>
              </div>
              <?php 
              }else{
     ?>
        <a href="login.php">
          <span class="pull-right">Login/Signup</span>
            </a>
            <?php } ?>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"> 
          <a class="nav-link active" aria-current="page" href="index.php">Home Page</a>
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link" href="#">Link</a>
        </li> -->
        <?php
        //we can use function "getcat" or function "getallfrom"
       // $cats=getcat();
        $cats=getallfrom("*","categorie","Id","where Parent=0","","ASC"); 
        foreach($cats as $cat){
            echo '<li class="nav-item"><a href="categorie.php?pageid='.$cat['Id'].' " class="nav-link">
                '. $cat['Name'].'
              </a>
            </li>';
        } 
        ?>
 
      </ul>
    </div>
  </div>
</nav>