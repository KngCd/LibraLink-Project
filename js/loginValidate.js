/* FOR ADMIN LOGIN */
$(document).ready(function(){

    $("#adminForm").validate({
        rules:{ 
            username:{
                required: true,
                minlength: 4
            },
            password:{
                required: true,
                minlength: 5,
                maxlength: 20
            }
        },
        messages:{
            username:{
                required: "Please enter your username"
            },
            password:{
                required: "Please enter your password"
            }
        },
        highlight: function(element) { // this is when the form rules is not met
            $(element).addClass('is-invalid'); // it will add this built-in class for invalid class in bootstrap
        },
        unhighlight: function(element) { 
            $(element).removeClass('is-invalid');
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

});

/* FOR STUDENT LOGIN */
$(document).ready(function(){

    $("#studentForm").validate({
        rules:{
            email:{
                required: true
            },
            password:{
                required: true,
                minlength: 5,
                maxlength: 20
            }
        },
        messages:{
            email:{
                required: "Please enter your email address"
            },
            password:{
                required: "Please enter your password"
            }
        },
        highlight: function(element) { // this is when the form rules is not met
            $(element).addClass('is-invalid'); // it will add this built-in class for invalid class in bootstrap
        },
        unhighlight: function(element) { 
            $(element).removeClass('is-invalid');
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

});

/* FOR STUDENT REGISTER */
$(document).ready(function(){

    // Custom validation method for contact number
    $.validator.addMethod("validContact", function(value, element) {
        // Check if the value starts with '09' and has a total of 11 characters
        return this.optional(element) || (value.length === 11 && value.startsWith("09"));
    }, "Contact number must be valid");

    $("#registerForm").validate({
        rules:{
            firstName:{
                required: true
            },
            lastName:{
                required: true
            },
            contact:{
                required: true,
                validContact: true // Only use the custom validation method
            },
            email:{
                required: true,
                email: true
            },
            password:{
                required: true,
                minlength: 5,
                maxlength: 20
            },
            confirmPassword:{
                required: true,
                equalTo: "#password"
            },
            program:{
                required: true
            },
            department:{
                required: true
            },
            cor:{
                required: true
            },
            id:{
                required: true
            },
            pic:{
                required: true
            }
        },
        messages:{
            firstName:{
                required: "Please enter your first name"
            },
            lastName:{
                required: "Please enter your last name"
            },
            contact:{
                required: "Please enter your contact number",
                validContact: "Contact number must be valid and please remove any special characters (e.g . '-')"
            },
            email:{
                required: "Please enter your email address",
                email: "Please provide a valid email"
            },
            password:{
                required: "Please enter your password"
            },
            confirmPassword:{
                required: "Please confirm your password",
                equalTo: "Passwords do not match"
            },
            program:{
                required: "Please enter your program"
            },
            department:{
                required: "Please enter your department"
            },
            cor:{
                required: "Please upload your COR"
            },
            id:{
                required: "Please upload your ID"
            },
            pic:{
                required: "Please upload your Profile Picture"
            }
        },
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) { 
            $(element).removeClass('is-invalid');
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

});

/* FOR FORGET PASSWORD */
$(document).ready(function(){

    $("#forgotForm").validate({
        rules:{
            email:{
                required: true
            },
            password:{
                required: true,
                minlength: 5,
                maxlength: 20
            },
            confirmPassword:{
                required: true,
                equalTo: "#password"
            }
        },
        messages:{
            email:{
                required: "Please enter your email address"
            },
            password:{
                required: "Please enter your password"
            },
            confirmPassword:{
                required: "Please provide a password",
                equalTo: "Passwords do not match"
            }
        },
        highlight: function(element) { // this is when the form rules is not met
            $(element).addClass('is-invalid'); // it will add this built-in class for invalid class in bootstrap
        },
        unhighlight: function(element) { 
            $(element).removeClass('is-invalid');
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

});

function togglePassword() {
    const password = document.getElementById("password");
    const passwordIcon = document.getElementById("password-icon");
    if (password.type === "password") {
        password.type = "text";
        passwordIcon.classList.toggle("bi-eye-fill");
        passwordIcon.classList.toggle("bi-eye-slash-fill");
    } else {
        password.type = "password";
        passwordIcon.classList.toggle("bi-eye-fill");
        passwordIcon.classList.toggle("bi-eye-slash-fill");
    }
}

function toggle() {
    var confirmPassword = document.getElementById("confirmPassword");
    var passwordIcon = document.getElementById("password-icon2");
    if (confirmPassword.type === "password") {
        confirmPassword.type = "text";
        passwordIcon.classList.toggle("bi-eye-fill");
        passwordIcon.classList.toggle("bi-eye-slash-fill");
    } else {
        confirmPassword.type = "password";
        passwordIcon.classList.toggle("bi-eye-fill");
        passwordIcon.classList.toggle("bi-eye-slash-fill");
    }
}