document.addEventListener('DOMContentLoaded', function() {
    var passwordInput = document.getElementById("password");
    var passwordRepeatInput = document.getElementById("passwordRepeat");

    passwordInput.addEventListener('focusout', function() {
        checkMatchingPasswords(passwordInput, passwordRepeatInput);
    });
    passwordRepeatInput.addEventListener('focusout', function() {
        checkMatchingPasswords(passwordInput, passwordRepeatInput);
    });
});

function checkMatchingPasswords(password, passwordRepeat) {
    if (password.value !== passwordRepeat.value) {
        if (passwordRepeat.value !== "") {
            password.classList.add("notMatching");
            passwordRepeat.classList.add("notMatching");
            return false;
        }
    } else {
        password.classList.remove("notMatching");
        passwordRepeat.classList.remove("notMatching");
        return true;
    }
}

function validateForm() {
    var emailInput = document.getElementById("email");
    var usernameInput = document.getElementById("username");
    var passwordInput = document.getElementById("password");
    var passwordRepeatInput = document.getElementById("passwordRepeat");

    var reEmail = new RegExp("[a-z0-9._%+-]+@[a-z0-9.-]+\\.[a-z]{2,4}$");
    var reUsername = new RegExp("[a-zA-Z]{3,27}\\.[0-9]{4}");

    if (reEmail.test(emailInput.value) && reUsername.test(usernameInput.value) && passwordInput.value.length >= 6 && checkMatchingPasswords(passwordInput, passwordRepeatInput)) {
        return true;
    } else {
        alert(reUsername.test(usernameInput.value) + "Input validation failed. If you're sure that you did everything right, contact us please.");
        return false;
    }
}