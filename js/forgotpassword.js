var countdownInterval;
var countdown = 60;

function otpsend() {	
	$('#otpResultMessage').html('');				
	$.ajax({				
		type: 'POST',
		url: 'forgotpassword.php',	
		data: 'action=sendotp&email='+$("#useremail").val(),
		beforeSend: function() {
            $("#otpbtn").html('<img src="loader.gif" width="15" height="15"/> &nbsp; Sending..');
        },
		success: function(data) {	
			var response = JSON.parse(data);					
			if(response.success) {
				$("#otptextbox").show();
				$("#newpasswordtextbox").show();
				$('#editemail').css("display", "block");
				$('#useremail').prop("readonly", true);
				$("#verifybtn").prop('disabled', false);
				$('#otpbtn').html('Resend Otp (' + countdown + 's)').prop('disabled', true);
				clearInterval(countdownInterval);
				countdownInterval = setInterval(function () {
						countdown--;
					if (countdown > 0) {
						$('#otpbtn').html('Resend Otp (' + countdown + 's)');
					} else {
						clearInterval(countdownInterval);
						$('#otpbtn').prop('disabled', false).html('Resend Otp');
					}
				}, 1000);
				$('#otpResultMessage').html('<P style="color: green !important;">' + response.success +'</p>');							
			} else if(response.error) {
				$('#otpbtn').html('Send Otp');	
				$('#otpResultMessage').html('<P style="color: red !important;">' + response.error +'</p>');
				setTimeout(function() {
					$("#otpResultMessage").empty();
				}, 3000);													
			}
		}
	});
}

function verifyotp() {
	$.ajax({
		type: 'POST',
		url: 'forgotpassword.php',
		data: 'action=verifyotp&verifyemail='+$("#useremail").val()+'&verifyotp='+$("#forgototp").val()+'&verifypassword='+$("#newpassword").val(),
		success:  function(data) {
			var response = JSON.parse(data);
			if(response.success) {
				$('#otpResultMessage').html('<P style="color: green !important;">' + response.success +'</p>');
				setTimeout('location.href = "index.html"; ',800);
			}
			else if(response.error) {
				$('#otpResultMessage').html('<P style="color: red !important;">' + response.error +'</p>');	
				setTimeout(function() {
					$("#otpResultMessage").html('<P style="color: green !important;">Email sent successfully</p>');
				}, 3000);												
			}
		}

	})
}

function editEmail() {
    $('#useremail').prop("readonly", false);
	$("#otpform").find('input').val(''); 
    $('#otpbtn').html('Send Otp').prop('disabled', false);
    $("#otptextbox").attr("style", "display: none !important");
	$("#newpasswordtextbox").attr("style", "display: none !important");
    $('#editemail').css("display", "none");
	$("#verifybtn").prop('disabled', true);
    $('#otpResultMessage').html('');		
    clearInterval(countdownInterval);
	countdown = 60;
}

document.addEventListener("DOMContentLoaded", function() {
	var passwordInput = document.getElementById("newpassword");
	var passwordeyeIcon = document.getElementById("togglernewpassword");
	passwordInput.addEventListener("input", function() {
		if(passwordInput.value !== "") {
			passwordeyeIcon.style.display = "block";
		}
		else {
			passwordeyeIcon.style.display = "none";
            passwordInput.type = "password";
			passwordeyeIcon.classList.replace("fa-eye-slash", "fa-eye");
		}
	});

	passwordeyeIcon.addEventListener("click", function() {
        if (passwordInput.value !== "") {
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
				passwordeyeIcon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                passwordInput.type = "password";
				passwordeyeIcon.classList.replace("fa-eye-slash", "fa-eye");
            }
        } 
    });

});