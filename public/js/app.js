'use strict';

/***    Password Eye functions   ***/

if(document.querySelector("#eye") !== null) {
    document.querySelector("#eye").addEventListener("click", eyeOpen);
}

const eye = document.querySelector('#eye');

const password = document.querySelector('#password');

function eyeOpen(e) {
    e.preventDefault();
    if(password.type === 'password') {
        
        password.setAttribute('type', 'text');
        eye.classList.remove('fa-eye-slash');
        eye.classList.add('fa-eye');
        eye.setAttribute('style', 'color: #f5d34c');
    } else {
        password.setAttribute('type', 'password');
        eye.classList.add('fa-eye-slash');
        eye.classList.remove('fa-eye');
        eye.removeAttribute('style');
    }
    window.setTimeout(closeEye, 9000);
}

function closeEye() {
    password.setAttribute('type', 'password');
    eye.classList.remove('fa-eye');
    eye.classList.add('fa-eye-slash');
    eye.removeAttribute('style');
}

const eye2 = document.getElementById("eye2");
eye2.addEventListener("click", toggleOpenEye);

function toggleOpenEye() {
    const passwordInput = document.getElementById("password_verify");
    const icon_fa = document.getElementById("eye2");
    if(passwordInput.type === "password") {
        passwordInput.type = "text";
        icon_fa.classList.remove('fa-eye-slash');
        icon_fa.classList.add('fa-eye');
        icon_fa.setAttribute('style', 'color: #f5d34c');
    } else {
        passwordInput.type = "password";
         icon_fa.classList.add('fa-eye-slash');
         icon_fa.classList.remove('fa-eye');
         icon_fa.removeAttribute('style');
    }
}


