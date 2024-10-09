<?php
session_start();
include_once("connection.php");
$address = $_POST['useraddress'];
$password = $_POST['userpassword'];
$response = array();

if (filter_var($address, FILTER_VALIDATE_EMAIL)) {
    $query = "SELECT * FROM user_records WHERE useremail='$address' and registrationcomplete='complete'";
    $error_message = "Email does not exist";
} elseif (preg_match('/^[6-9]\d{9}$/', $address)) {
    $query = "SELECT * FROM user_records WHERE userphonenumber='$address' and registrationcomplete='complete'";
    $error_message = "Phonenumber does not exist";
}

$loginResult = mysqli_query($conn, $query);
$loginRowCount = mysqli_num_rows($loginResult);

if ($loginRowCount > 0) {
    $user = mysqli_fetch_assoc($loginResult);
    $storedPassword = $user['userpassword'];
    if ($password == $storedPassword) {
        $_SESSION['user_session'] = $user['id'];
        $response['success'] = "Logged in successfully";
    } else {
        $response['error'] = "Incorrect password";
    }
} else {
    $response['error'] = $error_message;
}
echo json_encode($response);
?>
