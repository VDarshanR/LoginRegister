$('document').ready(function() {
	$.validator.addMethod("pattern", function (value) {
		var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
		var phonePattern = /^[6-9]\d{9}$/;
		return emailPattern.test(value) || phonePattern.test(value);
	}, "Invalid format");

	$("#loginform").validate({
		rules: {
			useraddress: {
				required: true,
				pattern: true
			},
			userpassword: {
				required: true,
			}
		},
		messages: {
			useraddress: {
				required: "Please enter your email or phone number",
				pattern: "Please enter a valid email address or phone number"
			},
			userpassword:{
			  required: "Please enter your Password" 
			}
		},

		submitHandler: submitForm,

		errorPlacement: function(error, element) {
			error.insertAfter(element);
			setTimeout(function() {
				error.fadeOut();
			}, 2000);
		}, 	
	});
	
	function submitForm() {		
		var logindata = $("#loginform").serialize();				
		$.ajax({				
			type : 'POST',
			url  : 'login.php',
			data : logindata,
			success : function(data){	
				var response = JSON.parse(data);					
				if(response.success){									
					$("#loginbtn").html('<img src="loader.gif" /> &nbsp; Signing In ...');
					setTimeout('location.href = "dashboard.php"; ',400);
				} else if(response.error) {														
					$("#loginerror").html('<P style="color: red !important;">' + response.error +'</p>');
					setTimeout(function() {
						$("#loginerror").empty();
					}, 3000);
				}
			}
		});
	}   
});