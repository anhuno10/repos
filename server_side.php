<?php
$standard_name = '';
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

if (isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'openStandard':
            openStandard($_POST['info']);
            break;
        case 'showAllStoredImages':
            showAllImages();
            break;
        case 'deleteStoredImage':
            deleteStoredImage($_POST['info']);
            break;
    }
}

if (isset($_POST['submit_image'])) {
    uploadFile();
}

if (isset($_POST['standard_name']) && !empty($_POST['standard_name'])) {
    $standard_name = $_POST['standard_name'];
    searchStandard($standard_name);
}


function searchStandard($standard_name)
{
    global $servername, $username, $password, $dbname;
    $link = mysqli_connect($servername, $username, $password, $dbname);
    if ($link === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    $sql = "SELECT * FROM index_entry WHERE entry_name LIKE '%$standard_name%'";

    if ($result = mysqli_query($link, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            echo "<table>";
            echo "<tr>";
            echo "<th>standard</th>";
            echo "<th>book</th>";
            echo "<th>page</th>";
            echo "</tr>";
            while ($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td>" . $row['entry_name'] . "</td>";
                echo "<td>" . $row['book'] . "</td>";
                echo "<td>" . $row['page'] . "</td>";
                echo '<td> <button onClick="openBook(\'' . $row['book'] . '\', \'' . $row['page'] . '\')">Go</button> </td>';
                echo "</tr>";
            }
            echo "</table>";
            // Free result set
            mysqli_free_result($result);
        } else {
            echo "No standards were found";
        }
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
    // Close connection
    mysqli_close($link);
}
function openStandard($info)
{
    $book = $info["book"];
    $page = $info["page"];

    $link = mysqli_connect("localhost", "root", "", "test");
    if ($link === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    $sql = "SELECT * FROM book_info WHERE book_name LIKE '$book'";

    if ($result = mysqli_query($link, $sql)) {
        if ($result = mysqli_query($link, $sql)) {
            if (mysqli_num_rows($result) > 0) {
                $urlProvided = "";
                $bookFound = mysqli_fetch_array($result);
                if (isset($bookFound["path"])) {
                    $urlProvided = "http://localhost/EasyRB/" . $bookFound["path"] . ".pdf#page=" . ($bookFound['leading pages'] + $page);
                    echo $urlProvided;
                    mysqli_free_result($result);
                } else {
                    echo "No book";
                }
            } else {
                echo "No records matching your query were found.";
            }
        } else {
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }
    }
}
function uploadFile()
{
    global $servername, $username, $password, $dbname;
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
            //store image entry into database
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "INSERT INTO images (image_path) VALUES ('$target_file')";

            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $conn->close();
            //give file permission 
            chmod($target_file, 0777);
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
function showAllImages()
{
    global $servername, $username, $password, $dbname;
    $link = mysqli_connect($servername, $username, $password, $dbname);
    if ($link === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    $sql = "SELECT * FROM images";

    if ($result = mysqli_query($link, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            $output_string = '';
            $output_string .=  '<table border="1">';
            while ($row = mysqli_fetch_array($result)) {
                $output_string .= '<tr>';
                $output_string .= '<td>' . $row['image_path'] . '</td>';
                $output_string .= '<td> <button onClick="openImage(\'' . $row['image_path'] . '\')">Open</button> </td>';
                $output_string .= '<td> <button onClick="deleteImage(\'' . $row['id'] . '\', \'' . $row['image_path'] . '\')">Delete</button> </td>';
                $output_string .= '</tr>';
            }
            $output_string .= '</table>';
            echo $output_string;
            mysqli_free_result($result);
        } else {
            echo "No images are uploaded";
        }
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
    // Close connection
    mysqli_close($link);
}
function deleteStoredImage($info)
{

    $id = $info["id"];
    $image_path = $info["image_path"];

    global $servername, $username, $password, $dbname;
    $link = mysqli_connect($servername, $username, $password, $dbname);
    if ($link === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    $sql = "DELETE FROM images WHERE id = $id";

    if ($link->query($sql) === TRUE) {
        echo "Record deleted successfully";
        rename($image_path, $image_path . "_old");
    } else {
        echo "Error deleting record: " . $link->error;
    }

    // Close connection
    mysqli_close($link);
}
