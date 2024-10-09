<?php
include("connection.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'C:\xampp\htdocs\Register\phpmail\vendor\autoload.php';

if ($_POST["action"] == "update") {
    $userId = $_POST["id"];
    $UserName = $_POST['updateusername'];
    $UserEmail = $_POST['updateuseremail'];
    $UserPhoneNumber = $_POST['updateuserphonenumber'];
    $UserOTP = $_POST['updateuserotp'];
    $currentTimestamp = time();
    $response = array();

    $emailQuery = "SELECT * FROM user_records WHERE useremail='$UserEmail' AND id!=$userId";
    $emailResult = mysqli_query($conn, $emailQuery);
    $emailRowCount = mysqli_num_rows($emailResult);

    $phoneQuery = "SELECT * FROM user_records WHERE userphonenumber='$UserPhoneNumber' AND id!=$userId";
    $phoneResult = mysqli_query($conn, $phoneQuery);
    $phoneRowCount = mysqli_num_rows($phoneResult);

    $otpquery = "SELECT * FROM user_records WHERE id='$userId'";
    $otpresult = mysqli_query($conn, $otpquery); {
        while ($row = mysqli_fetch_assoc($otpresult)) {
            $dbotp = $row['editprofileotp'];
            $dbtimestamp = $row['editprofiletimestamp'];
        }
    }

    if($emailRowCount > 0 && $phoneRowCount > 0) {
        $response['error'] = "Both email and phone number is already taken";
    } else if ($emailRowCount > 0 && $phoneRowCount == 0) {
        $response['error'] = "Email is already taken";
    } else if ($phoneRowCount > 0 && $emailRowCount == 0) {
        $response['error'] = "Phone number is already taken";
    }
    
    if (empty($response) && !empty($UserOTP)) {
        $timeDifference = $currentTimestamp - $dbtimestamp;
        $otpValidityPeriod = 300;
        if($UserOTP == $dbotp) {
            if($timeDifference <= $otpValidityPeriod) { 
                $query = "UPDATE user_records SET username='$UserName', useremail='$UserEmail', userphonenumber='$UserPhoneNumber' WHERE id=$userId";
                $result = mysqli_query($conn, $query);
                if ($result) {
                    $response['success'] = "Record has been updated successfully";
                } else {
                    $response['error'] = "Failed to update a record";
                }
            } else{
                $response['error'] = "OTP has expired";
            }
        } else {
            $response['error'] = "Invalid OTP";
        }
    }
    echo json_encode($response);
}

else if($_POST["action"] == "editsendotp") {
    $response = array();
    $id = $_POST['id'];
    $email = $_POST['email'];
    $emailquery = "SELECT * FROM user_records WHERE useremail='$email' and Id!='$id'";
    $emailResult = mysqli_query($conn, $emailquery); 
    {
        while ($row = mysqli_fetch_assoc($emailResult)) {
            $dbregistrationcomplete = $row['registrationcomplete'];
        }
    }
    $emailRowCount = mysqli_num_rows($emailResult);

    if(!empty($email)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if ($emailRowCount == 0 || $dbregistrationcomplete == 'incomplete') {
                $sendotp =  mt_rand(1, 999999);
                $sendtimestamp = time();
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'developerdarshan04@gmail.com';
                $mail->Password = 'ddoe mhzv hlcx vnen';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
                $mail->setFrom('developerdarshan04@gmail.com', 'DarshanV');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Account Verify OTP';
                $mail->Body = 'To complete your profile updating and to verify your identity, please use this OTP <strong>' . $sendotp . '</strong>
                    which is valid for only 5 minutes and keep this confidential. If you did not initiate this updating, please ignore this email.<br><br>
                    Best regards,<br>
                    DeveloperDarshan';
                $mail->send();
                $query = "DELETE FROM user_records where useremail = '$email' and Id!='$id' ";
                if(mysqli_query($conn, $query)) {
                    $query = "UPDATE user_records SET editprofileotp = '$sendotp', editprofiletimestamp = '$sendtimestamp' WHERE id = '$id'";
                    mysqli_query($conn, $query);
                    $response['success'] = "Email sent successfully";
                }
            } else {
                $response['error'] = "Email already exist";
            }
        } else {
            $response['error'] = "Please enter a valid email address";
        }
    } else {
        $response['error'] = "Please enter your email";
    }
    echo json_encode($response);
}
?>