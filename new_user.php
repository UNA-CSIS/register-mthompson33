<?php

// session start here...
session_start();
include "validate.php";

// get all 3 strings from the form (and scrub w/ validation function)
$user = test_input($_POST["user"]);
$pwd = test_input($_POST["pwd"]);
$pwdRepeat = test_input($_POST["repeat"]);

// make sure that the two password values match!
if ($pwd != $pwdRepeat) {
    echo "passwords do not match.";
}
else {
    // create the password_hash using the PASSWORD_DEFAULT argument
    $pwdHashed = password_hash($pwd, PASSWORD_DEFAULT);

    // login to the database
    // make sure that the new user is not already in the database
    // insert username and password hash into db (put the username in the session
    // or make them login)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "softball";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT id, username, password FROM users";
    $result = $conn->query($sql);
    $i = 1;

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            if ($row["username"] === $user) {
                echo "username taken";
            } else {
                $i = $i + 1;
            }
        }
    } else {
        echo "0 results";
    }

    $add = "INSERT INTO users (id, username, password) VALUES ('$i', '$user', '$pwdHashed')";

    if ($conn->query($add) === TRUE) {
        $_SESSION['username'] = $user;
        header("Location: games.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
