<?php
//server side business logic again again
if (isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];
    switch ($action) {
        case 'openStandard':
            openStandard($_POST['info']);
            break;
    }
}

$standard_name = '';
if (isset($_POST['standard_name'])) {
    $standard_name = $_POST['standard_name'];
    //echo "Standards found for word: $standard_name <br>";

    $link = mysqli_connect("localhost", "root", "", "test");
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
