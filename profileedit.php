<?php
session_start();
include("connection.php");
if(!isset($_SESSION['user_session'])) {
	header("Location: index.html");
}
$sql = "SELECT * FROM user_records WHERE id='".$_SESSION['user_session']."'";
$resultset = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($resultset);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<link rel="stylesheet" href="style1.css">
	<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
    <link rel="stylesheet" href="import/bootstrap.css">
    <link rel="stylesheet" href="import/fonts.css">
    <script src="import/bootstrap.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script src="js/profileedit.js"></script>
    <title>Profile Edit</title>
</head>
<style>
	body {
        background-color: #f4f4f9;
    }

	.profile-container {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        margin: 50px auto;
    }

	#heading {
		margin: 0px;
	}

	hr{
		height: 3px !important;
		margin: 20px -20px;
		background-color: #000000;
		border: none;
	}

	input:-webkit-autofill, input:-webkit-autofill:hover, input:-webkit-autofill:focus, input:-webkit-autofill:active {
		transition: background-color 5000s ease-in-out 0s;
	}

	.btn-container {
        text-align: right;
        padding-top: 20px;
    }

	#sendotpbtn:focus, #editemail:focus, #cancelbtn:focus, #updatebtn:focus {
		outline: none;
		box-shadow: none; 
	}

	#cancelbtn {
		background-color: #e9e9ed;
		color: #000000;
		border-color: #d5d5da;
	}

	#cancelbtn:hover {
		background-color: #B0B3B8;
	}
		
</style>
<body>
	<div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
				<div class="profile-container">
					<form id="updateform" class="form-login" method="post">
						<h3 id="heading" style="font-weight:bolder;" align="center">Profile Update</h3><hr>
						<p id="updateResultMessage" style="padding-left: 33px; font-weight: bold;"></p>
						<input type="hidden" name="id" id="id" value="<?php echo $row['id']; ?>" ></input>
						<div class="d-flex flex-row align-items-center mb-4">
							<i class="fas fa-user fa-lg me-3 fa-fw" style="color: #337ab7;"></i>
							<div class="form-outline flex-fill mb-1">
							<input type="text" id="updatename" name="updateusername"  value="<?php echo $row['username'];?>" placeholder="Name" class="form-control" />
							</div>
						</div>

						<div class="d-flex flex-row align-items-center mb-4">
							<i class="fas fa-envelope fa-lg me-3 fa-fw" style="color: #337ab7;"></i>
							<div class="form-outline flex-fill mb-0" style="margin-right: 5px;">
							<input type="email" id="updateemail" name="updateuseremail"  value="<?php echo $row['useremail'];?>" class="form-control" placeholder="Email" />
							</div>
							<button type="button" name="editemail" id="editemail" class="btn btn-warning" style="margin-bottom: 0px; margin-right: 5px; display: none;" onclick="editEmail()">
								Edit
							</button>
							<button id="sendotpbtn" type="button" class="btn btn-success" style="margin-bottom: 0px;" onclick="profileEditOtpSend()">
								Send Otp
							</button>
						</div>

						<div class="d-flex flex-row align-items-center mb-4">
							<i class="fas fa-phone fa-lg me-3 fa-fw" style="color: #337ab7;"></i>
							<div class="form-outline flex-fill mb-0">
							<input type="number" id="updatephonenumber" name="updateuserphonenumber" value="<?php echo $row['userphonenumber'];?>" class="form-control" placeholder="Phonenumber" />
							</div>
						</div>

						<div class="d-flex flex-row align-items-center mb-4" id="otptextbox" style="display: none !important">
							<i class="fas fa-paper-plane fa-lg me-3 fa-fw" style="color: #337ab7;"></i>
							<div class="form-outline flex-fill mb-1">
								<input type="number" id="updateotp" name="updateuserotp" placeholder="Otp" class="form-control"/>
							</div>
						</div>

						<div class="btn-container">
							<button id="cancelbtn" type="button" class="btn btn-primary" onclick="history.back()">Cancel</button>
							<button id="updatebtn" type="submit" disabled class="btn btn-primary">Update</button>
						</div>
						
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
 