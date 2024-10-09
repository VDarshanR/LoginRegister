<?php
include("connection.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'C:\xampp\htdocs\Register\phpmail\vendor\autoload.php';    
if($_POST["action"] == "register") {
    $errors = array();
    $name = $_POST['Uname'];
    $email = $_POST["Uemail"];
    $phonenumber = $_POST["Uphno"];
    $password = $_POST["Upassword"];
    $confirmpassword = $_POST["Uconfirmpassword"];
    $otp = $_POST["Uotp"];
    $termscondtions = $_POST['Utercond'];

    $Namepattern = '/^[A-Za-z]{4,}$/';
    $Phonepattern = '/^[6-9][0-9]{9}$/';
    $Passwordpattern = '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/';
    $phonenumberQuery = "SELECT * FROM user_records WHERE userphonenumber='$phonenumber'";
    $phonenumberResult = mysqli_query($conn, $phonenumberQuery);
    $PhonenumberRowCount = mysqli_num_rows($phonenumberResult);

    $currentTimestamp = time();
    $registerotpquery = "SELECT * FROM user_records WHERE useremail='$email'";
    $registerotpresult = mysqli_query($conn, $registerotpquery); {
        while ($row = mysqli_fetch_assoc($registerotpresult)) {
            $dbotp = $row['registerotp'];
            $dbtimestamp = $row['registertimestamp'];
        }
    }

    if (!empty($name) && !empty($email) && !empty($phonenumber) && !empty($password) && !empty($confirmpassword) && !empty($otp)) {
        if (!preg_match($Namepattern, $name)) {
            $errors[] = "Username must be at least 4 characters long and contain only alphabetical characters";
        }

        if(preg_match($Phonepattern, $phonenumber)) {
            if($PhonenumberRowCount > 0) {
                $errors[] = "Phonenumber is already taken";
            }
        }
        else {
            $errors[] = "Phone number must have 10 digits and should start with 6, 7, 8, or 9";
        }

        if(preg_match($Passwordpattern, $password)) {
            if($password != $confirmpassword) {
                $errors[] = "Password and confirm password must match";
            }
        }
        else {
            $errors[] = "Password must have 6 chars with uppercase, lowercase, digit and special char each";
        }
        
        if($termscondtions != 'yes') {
            $errors[] = "Please ensure you read and check the terms and conditions";
        }
    }
    else {
        $errors[] = "Please fill all the fields";
    }

    if (!empty($errors)) {
        $response['error'] = '<ul class="error-list"><li>' . implode('</li><li>', $errors) . '</li></ul>';
    }
    
    elseif(!empty($otp)) {
        $timeDifference = $currentTimestamp - $dbtimestamp;
        $otpValidityPeriod = 300;
        if($otp == $dbotp) {
            if($timeDifference <= $otpValidityPeriod) {
                $query = "UPDATE user_records SET username = '$name', userphonenumber = '$phonenumber', userpassword = '$password', registrationcomplete = 'complete' WHERE useremail = '$email'";
                if(mysqli_query($conn, $query)) {
                    $response['success'] = "Registered Successfully";
                }
            } else {
                $response['error'] = "OTP has expired";
            }
        } else {
            $response['error'] = "Invalid OTP";
        }
    }
    echo json_encode($response);
}

else if($_POST["action"] == "sendotp") {
    $response = array();
    $email = $_POST['email'];
    $emailquery = "SELECT * FROM user_records WHERE useremail='$email'";
    $emailResult = mysqli_query($conn, $emailquery);
    $emailRowCount = mysqli_num_rows($emailResult); {
        while($row = mysqli_fetch_assoc($emailResult)) {
            $dbregistrationcomplete = $row['registrationcomplete'];
        }
    }

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
                $mail->Body = 'To complete your registration process and to verify your identity, please use OTP <strong>' . $sendotp . '</strong>
                    which is valid for only 5 minutes and keep this 
                    confidential.If you did not initiate this registration, please ignore this email.<br><br>
                    Best regards,<br>
                    DeveloperDarshan';
                $mail->send();
                    $query = "DELETE FROM user_records where useremail = '$email'";
                    if(mysqli_query($conn, $query)) {
                        $query = "INSERT INTO user_records(useremail, registerotp, registertimestamp, registrationcomplete) VALUES('$email', '$sendotp', '$sendtimestamp', 'incomplete')";
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