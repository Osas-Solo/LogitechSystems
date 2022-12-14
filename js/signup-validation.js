let firstNameErrorMessage = document.getElementById("first-name-error-message");
let lastNameErrorMessage = document.getElementById("last-name-error-message");
let userNameErrorMessage = document.getElementById("username-error-message");
let passwordErrorMessage = document.getElementById("password-error-message");
let confirmPasswordErrorMessage = document.getElementById("confirm-password-error-message");
let emailAddressErrorMessage = document.getElementById("email-address-error-message");
let phoneNumberErrorMessage = document.getElementById("phone-number-error-message");

function checkPasswordValidity() {
    let password = document.getElementById("password").value;

    if (!isPasswordValid(password)) {
        showPasswordErrorMessage();
    } else {
        hidePasswordErrorMessage();
    }
}

function checkPasswordConfirmation() {
    let password = document.getElementById("password").value;
    let passwordConfirmer = document.getElementById("confirm-password").value;

    if (!isPasswordConfirmed(password, passwordConfirmer)) {
        showConfirmPasswordErrorMessage();
    } else {
        hideConfirmPasswordErrorMessage();
    }
}

function isPasswordValid(password) {
    return isPasswordRequiredLength(password) && doesPasswordContainLowerCase(password) &&
            doesPasswordContainUpperCase(password) && doesPasswordContainDigit(password);
}   //  end of isPasswordValid()

function isPasswordRequiredLength(password) {
    return password.length >= 8;
}   //  end of isPasswordRequiredLength()

function doesPasswordContainLowerCase(password) {
    for (let i = 0; i < password.length; i++) {
        if (password.charAt(i) == password.charAt(i).toLowerCase()) {
            return true;
        }
    }

    return false;
}   //  end of containsLowerCase()

function doesPasswordContainUpperCase(password) {
    for (let i = 0; i < password.length; i++) {
        if (password.charAt(i) == password.charAt(i).toUpperCase()) {
            return true;
        }
    }

    return false;
}   //  end of containsUpperCase()

function doesPasswordContainDigit(password) {
    for (let i = 0; i < password.length; i++) {
        if (!isNaN(password.charAt(i))) {
            return true;
        }
    }

    return false;
}   //  end of containsDigit()

function isPasswordConfirmed(password, passwordConfirmer) {
    return password == passwordConfirmer;
}

function hideFirstNameErrorMessage() {
    firstNameErrorMessage.style.display = "none";
}

function hideLastNameErrorMessage() {
    lastNameErrorMessage.style.display = "none";
}

function hideUserNameErrorMessage() {
    userNameErrorMessage.style.display = "none";
}

function showPasswordErrorMessage() {
    passwordErrorMessage.style.display = "";
}

function hidePasswordErrorMessage() {
    passwordErrorMessage.style.display = "none";
}

function showConfirmPasswordErrorMessage() {
    confirmPasswordErrorMessage.style.display = "";
}

function hideConfirmPasswordErrorMessage() {
    confirmPasswordErrorMessage.style.display = "none";
}

function hideEmailAddressErrorMessage() {
    emailAddressErrorMessage.style.display = "none";
}

function hidePhoneNumberErrorMessage() {
    phoneNumberErrorMessage.style.display = "none";
}