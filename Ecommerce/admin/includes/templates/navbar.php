<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"> 
          <a class="nav-link active" aria-current="page" href="dashboard.php"><?php echo lang('HOME_ADMIN') ?></a>
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link" href="#">Link</a>
        </li> -->
        <li class="nav-item">
          <a class="nav-link" href="members.php?do=manage">Members</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            hamaad
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="members.php?do=edit&userid=<?php echo $_SESSION['UseriD']?>">Edit profile</a></li>
            <li><a class="dropdown-item" href="members.php?do=manage&userid=<?php echo $_SESSION['UseriD']?>">Mange member</a></li>
            <li><a class="dropdown-item" href="members.php?do=add&userid=<?php echo $_SESSION['UseriD']?>">Add member</a></li>
            <li><a class="dropdown-item" href="#">setting</a></li>
            <li><a class="dropdown-item" href="logout.php">logout</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="categorie.php">Categorie</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="items.php">Items</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="comments.php">Comment</a>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled">Disabled</a>
        </li>
      </ul>
      <form class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>