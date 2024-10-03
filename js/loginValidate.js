

$(document).ready(function(){

    $("#myForm").validate({
        rules:{
            name:{
                required: true,
                minlength: 4
            },
            email:{
                required: true
            },
            password:{
                required: true,
                minlength: 8,
                maxlength: 20
            }
        },
        messages:{
            email:{
                required: "This field is required!"
            },
            password:{
                required: "This field is required!"
            }
        },
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
        }
    });

});

$(document).ready(function(){

    $("#myForm2").validate({
        rules:{
            name:{
                required: true,
                minlength: 4
            },
            email:{
                required: true
            },
            password:{
                required: true,
                minlength: 8,
                maxlength: 20
            }
        },
        messages:{
            email:{
                required: "This field is required!"
            },
            password:{
                required: "This field is required!"
            }
        },
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function(form) {
            // Form is valid, redirect to login.html
            window.alert("SUCCESS!");
            window.location.href = "login.html";
        }
    });
});


function register(){
    const name = document.getElementById("name").value;
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    console.log(name);
    console.log(email);
    console.log(password);
}

function login(){
    event.preventDefault();
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    const failedDiv = document.getElementById("failed");

    console.log(email);
    console.log(password);

    if(email === "testing@gmail.com" && password === "testing123"){
        window.alert("SUCCESS LOGIN!");
        document.getElementById("failed").style.display = "none";
        window.location.href = "home.html";
    }
    else{
        failedDiv.style.display = "block";
        failedDiv.style.color = "red";
    }
}

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