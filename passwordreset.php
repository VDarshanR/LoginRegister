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
	<script src="js/resetpassword.js"></script>
	<style>

		body {
			background-color: #f4f4f9;
		}

		.passwordreset-container {
			background-color: white;
			padding: 20px;
			border-radius: 8px;
			box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
			margin: 50px auto;
		}

		hr{
			height: 3px !important;
			margin: 20px -20px;
			background-color: #000000;
			border: none;
		}

		#heading {
			margin: 0px;
		}

		.btn-container {
			text-align: right;
		}

		#showbtn {
			background-color: #008000;
		}

		#cancelbtn {
			background-color: #e9e9ed;
			color: #000000;
			border-color: #d5d5da;
		}

		#showbtn:focus, #cancelbtn:focus, #submitbtn:focus {
			outline: none;
			box-shadow: none; 
		}

		#cancelbtn:hover {
			background-color: #B0B3B8;
		}
		
		#forgotpasswordmessage {
			font-size: 17px;
			font-weight: bold;
			margin-bottom: 1%;
			padding-left: 34px;
		}

	</style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
		var currentPassword = $("#passworcurrent")
        var newPassword = $("#passwordnew");
        var confirmNewPassword = $("#confirmpasswordnew");
        var showNewPassword = $("#showbtn");
		showNewPassword.on("click", function() {
			var isPasswordHidden = currentPassword.attr("type") === "password";
			currentPassword.add(newPassword).add(confirmNewPassword).attr("type", isPasswordHidden ? "text" : "password");
			showNewPassword.text(isPasswordHidden ? "Hide Password" : "Show Password").css({
				"background-color": isPasswordHidden ? "#c70000" : "#008000",
				"border-color": isPasswordHidden ? "#c70000" : "#008000"
			});
		});
	});
    </script>
	<title>Forgot Password</title>
</head>
<body>
	<div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
				<div class="passwordreset-container">
					<form id="forgotform" class="form-login" method="post">
						<h3 id="heading" style="font-weight:bolder;" align="center">Reset Password</h3><hr>
						<p style="padding-left: 34px">
							Resetting your password associated with
							<span style="font-weight: bold; font-family: math; color: #FF0000">
								<?php echo $row['useremail']; ?>
							</span>
							and
							<span style="font-weight: bold; font-family: math; color: #FF0000">
								<?php echo $row['userphonenumber']; ?>
							</span>
						</p>
						<p id="forgotpasswordmessage"></p>
						<input type="hidden" name="id" id="id" value="<?php echo $row['id']; ?>" ></input>
						<input type="hidden" name="oldpassword" id="passwordold" value="<?php echo $row['userpassword']; ?>" ></input>
						<div class="d-flex flex-row align-items-center mb-4">
							<i class="fas fa-lock fa-lg me-3 fa-fw" style="color: #337ab7;"></i>
							<div class="form-outline flex-fill mb-0">
							<input type="password" id="passworcurrent" name="currentpassword" class="form-control" placeholder="Old Password" />
							</div>
						</div>

						<div class="d-flex flex-row align-items-center mb-4">
							<i class="fas fa-key fa-lg me-3 fa-fw" style="color: #337ab7;"></i>
							<div class="form-outline flex-fill mb-0">
							<input type="password" id="passwordnew" name="newpassword" class="form-control" placeholder="New Password" />
							</div>
						</div>

						<div class="d-flex flex-row align-items-center mb-4">
							<i class="fas fa-check-circle fa-lg me-3 fa-fw" style="color: #337ab7;"></i>
							<div class="form-outline flex-fill mb-0">
							<input type="password" id="confirmpasswordnew" name="confirmnewpassword" class="form-control" placeholder="Confirm Password" />
							</div>
						</div>

						<div class="btn-container">
							<button id="showbtn" type="button" class="btn btn-primary" style="margin-left: 33px; float: inline-start;">Show Password</button>
							<button id="cancelbtn" type="button" class="btn btn-primary" onclick="history.back()">Cancel</button>
							<button id="submitbtn" type="submit" class="btn btn-primary">Reset</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
</html>