<?php
include("connection.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'C:\xampp\htdocs\Register\phpmail\vendor\autoload.php';

if ($_POST["action"] == "sendotp") {
    $response = array();
    $email = $_POST['email'];
    $emailquery = "SELECT * FROM user_records WHERE useremail='$email'";
    $emailResult = mysqli_query($conn, $emailquery); 
    {
        while ($row = mysqli_fetch_assoc($emailResult)) {
                $name = $row['username'];
                $dbregistrationcomplete = $row['registrationcomplete'];
          }
    }
    $emailRowCount = mysqli_num_rows($emailResult);

    if(!empty($email)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if ($emailRowCount > 0 && $dbregistrationcomplete == 'complete') {
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
                $mail->addAddress($email, $name);
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset OTP';
                $mail->Body = 'Dear ' . $name . ',<br>
                    you have requested to reset the password for your account so please use OTP <strong>' . $sendotp . '</strong> to verify your identity which is valid for only 5 minutes
                    and do not share this with anyone.<br><br>
                    If you did not make this request, ignore this email.<br>
                    Thank you,<br>
                    DeveloperDarshan';
                $mail->send();
                $query = "UPDATE user_records SET forgototp = '$sendotp', forgottimestamp = '$sendtimestamp' WHERE useremail = '$email'";
                mysqli_query($conn, $query);
                $response['success'] = "Email sent successfully";
            } else {
                $response['error'] = "Email does not exist";
            }
        } else {
            $response['error'] = "Please enter a valid email address";
        }
    } else {
        $response['error'] = "Please enter your email";
    }
    echo json_encode($response);
}

if($_POST["action"] == "verifyotp") {
    $response = array();
    $passwordPattern = '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/';
    $email = $_POST['verifyemail'];
    $otp = $_POST['verifyotp'];
    $password = $_POST['verifypassword'];
    $currentTimestamp = time();
    $otpquery = "SELECT * FROM user_records WHERE useremail='$email'";
    $otpresult = mysqli_query($conn, $otpquery); {
        while ($row = mysqli_fetch_assoc($otpresult)) {
            $dbotp = $row['forgototp'];
            $dbtimestamp = $row['forgottimestamp'];
        }
    }
    if(!empty($otp) && !empty($email) && !empty($password)) {
        $timeDifference = $currentTimestamp - $dbtimestamp;
        $otpValidityPeriod = 300;
        if($otp == $dbotp) {
            if($timeDifference <= $otpValidityPeriod) {
                if(preg_match($passwordPattern, $password)) {
                    $query = "UPDATE user_records SET userpassword = '$password'WHERE useremail = '$email'";
                    mysqli_query($conn, $query);
                    $response['success'] = "Password changed successfully";
                } 
                else {
                    $response['error'] = "Password must have 6 chars with uppercase, lowercase, digit and special char each";
                }
            }
            else {
                $response['error'] = "OTP has expired";
            }
        }
        else {
            $response['error'] = "Invalid OTP";
        }
    }
    else {
        $response['error'] = "Please enter all the fields";
    }
    echo json_encode($response);
}
?>
