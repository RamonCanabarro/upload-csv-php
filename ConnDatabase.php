<?php
Class ConnDatabase {
    public function save($sql) {
    $servername = "";
    $database = "";
    $username = "";
    $password = "";
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $database);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    echo "Connected successfully";
    
    if (mysqli_query($conn, $sql)) {
        echo "New record created successfully";
    } else {
        echo '<pre>';
          print_r($sql);
          print_r("aquiii");
        echo '</pre>';
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    mysqli_close($conn);
}
}
?>