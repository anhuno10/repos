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
      <input type="text" placeholder="Search real books..." name="standard_name" id="standard_name">
    </form>
  </div>
  <br>
  <form action="" method="post" enctype="multipart/form-data" id="image_upload_form">
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit_image">
    <button onClick="showAllStoredImages()">Show all</button>
    <input type="text" placeholder="Search real books..." name="standard_name" id="standard_name">
  </form>
  
  <?php include_once("server_side.php") ?>
  <div id='result_table'>
</body>

</html>