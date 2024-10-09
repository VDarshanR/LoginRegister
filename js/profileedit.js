$(document).ready(function() {
    $.validator.addMethod("pattern", function(value, element, regexp) {
        return this.optional(element) || regexp.test(value);
    }, "Invalid formats");
    var updateNamePattern = /^[A-Za-z]+(?:\s[A-Za-z]+)*$/;
    var updateEmailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    var updatePhoneNumberPattern  = /^[6-9]\d{9}$/;
    var updateOtpPattern = /^[0-9]\d/;

    $("#updateform").validate({
        rules:
        {
            updateusername: {
                required: true,
                minlength: 3,
                pattern: updateNamePattern
            },
            updateuseremail: {
                required: true,
                pattern: updateEmailPattern
            },
            updateuserphonenumber: {
                required: true,
                pattern: updatePhoneNumberPattern
            },
            updateuserotp: {
                required: true,
                pattern: updateOtpPattern
            },
        },
        messages:
        {
            updateusername: {
                required: "Please provide your valid name",
                minlength: "Name should be atleast 3 char length",
                pattern: "Name should not consist of digits and extra spaces"
            },

            updateuseremail: {
                required: "Please provide your valid email",
                pattern: " Email address is invalid",
            },

            updateuserphonenumber: {
                required:"Please provide your valid phone number",
                pattern: "Phone number must have 10 digits and should start with 6, 7, 8, or 9"
            },

            updateuserotp: {
                required: "Please enter the OTP sent to your email",
                pattern: "OTP should be in digit"
            }

        },
        submitHandler: updatesubmitform,

        errorPlacement: function(error, element) {
            error.insertAfter(element);
            setTimeout(function() { 
                error.fadeOut();
            }, 2000);
        },
    });

    function updatesubmitform() {
        $.ajax({
            type:'post',
            url:'editprofile.php',
            data: 'action=update&id='+$("#id").val()+'&updateprofile='+$("#updateform").serialize(),
            success: function(data) {
                var response = JSON.parse(data);
                if(response.success) {
                    $('#updateResultMessage').html('<P class="text-success" style="color: ##e9e9ed">' + response.success +'</p>');
                    setTimeout('location.href = "index.html"; ',800);
                }
                else if(response.error) {
                    $('#updateResultMessage').html('<P class="text-success" style="color: #FF0000">' + response.error +'</p>');
                    setTimeout(function() {
                        $("#updateResultMessage").html('<P style="color: green !important;">Email sent successfully</p>');
                    }, 3000);	
                }
            }
        });
    }
});

var countdownInterval;
var countdown = 60;

function profileEditOtpSend() {	
	$('#updateResultMessage').html('');				
	$.ajax({				
		type: 'POST',
		url: 'editprofile.php',
		data: 'action=editsendotp&id='+$("#id").val()+'&email='+$("#updateemail").val(),
		beforeSend: function() {
            $("#sendotpbtn").html('<img src="loader.gif" width="15" height="15"/> &nbsp; Sending..');
        },
		success: function(data) {	
			var response = JSON.parse(data);					
			if(response.success) {
				$("#otptextbox").show();
                $('#updatebtn').prop('disabled', false);
                $('#updateemail').prop("readonly", true);
                $('#editemail').css("display", "block");
				$("#sendotpbtn").prop('disabled', true); 
				$('#sendotpbtn').html('Resend Otp (' + countdown + 's)');
				countdownInterval = setInterval(function () {
					countdown--;
					if (countdown > 0) {
						$('#sendotpbtn').html('Resend Otp (' + countdown + 's)');
					} else {
						clearInterval(countdownInterval);
						$('#sendotpbtn').prop('disabled', false).html('Resend Otp');
					}
				}, 1000);
				$('#updateResultMessage').html('<P style="color: green !important;">' + response.success +'</p>');							
			} else if(response.error) {
				$('#sendotpbtn').html('Send Otp');	
				$('#updateResultMessage').html('<P style="color: red !important;">' + response.error +'</p>');		
                setTimeout(function() {
                    $("#updateResultMessage").empty();
                }, 3000);											
			}
		}
	});
}

function editEmail() {
    $('#updateemail').prop("readonly", false);
    $("#otptextbox").attr("style", "display: none !important");
    $('#editemail').css("display", "none");
    $('#sendotpbtn').html('Send Otp').prop('disabled', false);
    $('#updatebtn').prop('disabled', true);
    $('#updateResultMessage').html('');		
    clearInterval(countdownInterval);
    countdown = 60;
}
