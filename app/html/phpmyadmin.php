<?php

$link = mysqli_connect("localhost", "root", "nghia@tv", null);
if (mysqli_connect_errno()) {
  $link = mysqli_connect("localhost", "root", "", null);
  if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL user root: " . mysqli_connect_error();
    exit();
  }
}

// $query = "SELECT CURRENT_USER();";
// $query .= "SHOW DATABASES";

$query = "ALTER USER 'root'@'localhost' IDENTIFIED BY 'nghia@tv';GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost'; FLUSH PRIVILEGES; CREATE DATABASE IF NOT EXISTS ketoan_upload";
/* execute multi query */
mysqli_multi_query($link, $query);
do {
    /* store the result set in PHP */
    if ($result = mysqli_store_result($link)) {
        while ($row = mysqli_fetch_row($result)) {
            printf("%s\n", $row[0]);
        }
    }
    /* print divider */
    if (mysqli_more_results($link)) {
        // printf("results -----\n");
    }
} while (mysqli_next_result($link));

mysqli_close($link);
$url = "http://localhost/phpmyadmin";
print '<a href="'.$url.'" >New Password root & Database created.. Click here to Return phpMyadmin.</a>'


?>
