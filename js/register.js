var countdownInterval;

function register() {
    var action = 'register'
    var Username = $('#name').val();
    var Useremail = $('#email').val();  
    var Userphonenumber = $('#phonenumber').val();
    var Userpassword = $('#password').val();
    var Userconfirmpassword = $('#confirmpassword').val();
    var Userotp = $('#otp').val();
    var Termscond = $('#termsconditions').prop('checked') ? 'yes' : 'no';
    console.log("terms: " + Termscond);
    $.ajax({
        type:'post',
        url:'register.php',
        data: {action: action, Uname: Username, Uemail: Useremail, Uphno: Userphonenumber, Upassword: Userpassword, Uconfirmpassword: Userconfirmpassword, Uotp: Userotp, Utercond: Termscond},
        success: function(data) {
            var response = JSON.parse(data);
            if(response.success) {
                $("#registerbtn").html('<img src="loader.gif"/> &nbsp; Signing Up..');
                setTimeout('$(".vh-100").fadeOut(200, function(){loginModal()}); ',300);
            }
            else if(response.error) {
				$('#registermessage').html('<div style="color: red !important;">' + response.error +'</div>');
            }
        }
    });
}

function loginModal() {
    $.ajax({
        url: 'welcome.html',
        type: 'GET',
        success: function(response) {
            $('body').append(response);
            $("#Loginmodal").modal('show');
        },
    }); 
}

function registerotpsend() {	
	$('#registermessage').html('');				
	$.ajax({				
		type: 'POST',
		url: 'register.php',
		data: 'action=sendotp&email='+$("#email").val(),
		beforeSend: function() {
            $("#otpbtn").html('<img src="loader.gif" width="15" height="15"/> &nbsp; Sending..');
        },
		success: function(data) {	
			var response = JSON.parse(data);					
			if(response.success) {
				$("#otptextbox").show();
                $("#registerbtn").prop('disabled', false);
                $('#email').prop("readonly", true);
                $('#editemail').css("display", "block");
				var countdown = 60;
				$("#otpbtn").prop('disabled', true); 
				$('#otpbtn').html('Resend Otp (' + countdown + 's)');
				countdownInterval = setInterval(function () {
					countdown--;
					if (countdown > 0) {
						$('#otpbtn').html('Resend Otp (' + countdown + 's)');
					} else {
						clearInterval(countdownInterval);
						$('#otpbtn').prop('disabled', false);
                        $('#email').prop("readonly", false);
                        $("#email").attr("style", "background-color: white !important");
						$('#otpbtn').html('Resend Otp');
					}
				}, 1000);
				$('#registermessage').html('<P style="color: green !important;">' + response.success +'</p>');							
			} else if(response.error) {
				$('#otpbtn').html('Send Otp');
				$('#registermessage').html('<P style="color: red !important;">' + response.error +'</p>');		
                setTimeout(function() {
                    $("#registermessage").empty();
                },  3000);											
			}
		}
	});
}

function editEmail() {
    $('#email').prop("readonly", false);
    $("#otptextbox").attr("style", "display: none !important");
    $('#editemail').css("display", "none");
    $('#otpbtn').html('Send Otp').prop('disabled', false);
    $('#registermessage').html('');		
    clearInterval(countdownInterval);
}