$('document').ready(function() {
    $.validator.addMethod("pattern", function(value, element, regexp) {
        return this.optional(element) || regexp.test(value);
    }, "Invalid Pattern");
    var passwordPattern = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[(|){}[#?!@$%^&*+*/=_`~:;"<,>.-]).[^\s]{6,}$/;
    $("#forgotform").validate ({
        rules: {
            currentpassword: {
                required: true,
            },
            newpassword: {
                required: true, 
                pattern: passwordPattern,
            },
            confirmnewpassword: {
                required: true,
                equalTo: '#passwordnew'
            }
        },
        messages:{
            currentpassword: {
                required: "Please provide your current password",
            },
            newpassword: {
                required: "Please provide your password",
                pattern: "Password should be atleast 6 chars, 1 Uppercase, 1 Lowercase, 1 Digit, 1 Special Char"
            },
            confirmnewpassword: {
                required: "Please provide your confirm password",
                equalTo: "Confirm Password didn't match with password"
            }
        },

        submitHandler: forgotSubmitForm,

        errorPlacement: function(error, element) {
			error.insertAfter(element);
			setTimeout(function() {
				error.fadeOut();
			}, 2000);
		},
    });
    function forgotSubmitForm() {
        var forgotdata = $("#forgotform").serialize();
        $.ajax({
        type:'POST',
        url:'resetpassword.php',
        data: forgotdata,
        success: function(data) {
            var response = JSON.parse(data);
            if(response.success) {
                $("#forgotpasswordmessage").html('<div style="color: #008000;"> '+response.success+' </div>');
                setTimeout('location.href = "index.html"; ',800);
            } 
            else if(response.error) {
                $("#forgotpasswordmessage").html('<div style="color: #FF0000;"> '+response.error+' </div>');
                setTimeout(function() {
                    $("#forgotpasswordmessage").empty();
                }, 3000);
            }
        }
    });
    }	
});