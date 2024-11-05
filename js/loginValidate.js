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
                minlength: 8
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

/* FOR UPDATE STUDENT PROFILE (ADMIN) */
$(document).ready(function(){

    // Custom validation method for contact number
    $.validator.addMethod("validContact", function(value, element) {
        // Check if the value starts with '09' and has a total of 11 characters
        return this.optional(element) || (value.length === 11 && value.startsWith("09"));
    }, "Contact number must be valid");

    $("#updateForm").validate({
        rules:{
            contact_num:{
                required: true,
                validContact: true // Only use the custom validation method
            },
            email:{
                required: true,
                email: true
            }
        },
        messages:{
            contact_num:{
                required: "Please enter your contact number",
                validContact: "Contact number must be valid and please remove any special characters (e.g . '-')"
            },
            email:{
                required: "Please enter your email address",
                email: "Please provide a valid email"
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

/* FOR FORGOT PASSWORD */
$(document).ready(function(){

    $("#forgotForm").validate({
        rules:{
            email:{
                required: true
            },
            password:{
                required: true,
                minlength: 8,
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

/* FOR UPDATE STUDENT PROFILE (STUDENT) */
$(document).ready(function() {
    $.validator.addMethod("validContact", function(value, element) {
        return this.optional(element) || (value.length === 11 && value.startsWith("09"));
    }, "Contact number must be valid");

    $("#editProfileForm").validate({
        rules: {
            firstName: { required: true },
            lastName: { required: true },
            contact_num: { required: true, validContact: true },
            email: { required: true, email: true },
            program: { required: true },
            department: { required: true }
        },
        messages: {
            firstName: { required: "Please enter your first name" },
            lastName: { required: "Please enter your last name" },
            contact_num: { required: "Please enter your contact number", validContact: "Contact number must be valid." },
            email: { required: "Please enter your email address", email: "Please provide a valid email" },
            program: { required: "Please enter your program" },
            department: { required: "Please enter your department" }
        },
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) { 
            $(element).removeClass('is-invalid');
        },
        submitHandler: function(form) {
            console.log("Form is valid and ready to be submitted.");
            form.submit();
        }
    });
});

/* FOR FORGOT PASSWORD (STUDENT HOME) */
$(document).ready(function() {
    $("#sUpdatePass").validate({
        rules: {
            old_password: {
                required: true // Ensure the old password is required
            },
            new_password: {
                required: true,
                minlength: 8 // Password must be at least 8 characters long
            },
            confirm_password: {
                required: true,
                equalTo: "#newPassword" // Ensure the confirm password matches the new password
            }
        },
        messages: {
            old_password: {
                required: "Please enter your old password"
            },
            new_password: {
                required: "Please enter a new password",
                minlength: "Your password must be at least 8 characters long"
            },
            confirm_password: {
                required: "Please confirm your new password",
                equalTo: "Passwords do not match"
            }
        },
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function(form) {
            // jQuery validation passed, now submit the form
            form.submit(); // This will trigger PHP execution
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