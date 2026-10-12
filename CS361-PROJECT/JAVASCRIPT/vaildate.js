
// checks the register form before submitting
function checkRegister() {
    var name     = document.getElementById("name").value.trim();
    var email    = document.getElementById("email").value.trim();
    var password = document.getElementById("password").value;

    if (name == "") {
        alert("Please enter your name.");
        return false;
    }

    if (email == "") {
        alert("Please enter your email.");
        return false;
    }

    if (email.indexOf("@") == -1) {
        alert("Please enter a valid email address.");
        return false;
    }

    if (password.length < 6) {
        alert("Password must be at least 6 characters long.");
        return false;
    }

    return true;
}

// checks the login form before submitting
function checkLogin() {
    var email    = document.getElementById("email").value.trim();
    var password = document.getElementById("password").value;

    if (email == "") {
        alert("Please enter your email.");
        return false;
    }

    if (password == "") {
        alert("Please enter your password.");
        return false;
    }

    return true;
}

// checks the edit profile form before submitting
function checkEdit() {
    var name = document.getElementById("name").value.trim();

    if (name == "") {
        alert("Name cannot be empty.");
        return false;
    }

    return true;
}