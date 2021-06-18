<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="welcome_style.css">
  <script type="text/javascript" src="client.js"></script>
  <script src="jquery.js"></script>
  <title>Jazz Standards</title>
</head>

<body>
  <div class="topnav">
    <a class="active">Jazz standards</a>
    <form name="form" action="" method="post">
      <input type="text" placeholder="Search.." name="standard_name" id="standard_name">
    </form>
  </div>

  <?php include_once("server_side.php") ?>

</body>

</html>