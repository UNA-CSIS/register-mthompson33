<?php
// start session
session_start();

$user = $_POST["user"];
$pwd = $_POST["pwd"];
//$pwdHash = password_hash($pwd, PASSWORD_DEFAULT);

// login to the softball database
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

// select password from users where username = <what the user typed in>
$sql = "SELECT password FROM users WHERE username = '$user'";
$result = $conn->query($sql);

// if no rows, then username is not valid (but don't tell Mallory) just send
// her back to the login
if ($result->num_rows === 0) {
    header("Location: index.php");
}

// otherwise, password_verify(password from form, password from db)
// if good, put username in session, otherwise send back to login
else {
    $row = $result->fetch_assoc();
    if (password_verify($pwd, $row["password"])) {
        $_SESSION['username'] = $user;
        header("Location: games.php");
    }
    else {
        header("Location: index.php");
    }
}
