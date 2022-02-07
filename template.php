<!DOCTYPE html>
<html>
<head>
<title>Video Account</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://drvic10k.github.io/bootstrap-sortable/Contents/bootstrap-sortable.css" />
<link rel="stylesheet" href=" https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="http://d3js.org/d3.v3.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://drvic10k.github.io/bootstrap-sortable/Scripts/bootstrap-sortable.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="style.css" type="text/css" media="screen" />


</head>
<body>

<title>Video Account</title>

<nav class="navbar navbar-dark justify-content-between" style="background-color:green;">
      <!-- Navbar content -->
    <ul id="menu" class="nav nav-pills">

    <a class="navbar-brand" href="#">Video Account <span class="box">Beta<span></a>

			<!--  if logged -->
  
		  <?php if (isset($var['ID'])): ?>  

    <li class="nav-item"><a class="nav-link" href="index.php?mode=AddVideo">Ajouter des videos</a></li>
    <li class="nav-item"><a class="nav-link" href="index.php?mode=ViewVideo">Voir les videos des autres</a></li>
    <?php if (isset($var['ID']) && ($_SESSION['username']=="admin"||$_SESSION['username']=="Demo")): ?>
    	<li class="nav-item"><a class="nav-link" href="index.php?mode=restart">Reset DB</a></li>
    <?php endif ?>	
    </ul>
    <ul id="menu-left" class="nav nav-pills ml-auto">
    
    <?php
    if (isset($var['ID'])): ?>
        <li class="nav-item"><span class="navbar-text">Logged as <?php echo $var['ID']; ?></span></li>
        <li class="nav-item"><a class="nav-link" href="index.php?mode=logout">Logout</a></li>
    <?php endif ?>


    <?php endif ?>
    

    </ul>
</nav>

<main id="content" class="container" role="main">
<?= $VIEW['MAIN'] ?>
</main>

</body>
</html>