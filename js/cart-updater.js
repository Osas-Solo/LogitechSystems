function updateCart(productID, quantity) {
    const cartUpdateRequest = new XMLHttpRequest();

    cartUpdateRequest.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText.includes("Sorry")) {
                if (confirm(this.responseText)) {
                    let loginURL = "login.php";
                    window.location.replace(loginURL);
                }
            } else {
                alert(this.responseText);
                getNumberOfCartProducts();
            }
        }
    };

    cartUpdateRequest.open("POST", "cart-updater.php", true);
    cartUpdateRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    cartUpdateRequest.send("product-id=" + productID + "&quantity=" + quantity);
}

function updateCartProductTotalAmount(productPrice, productQuantityInput, productTotalAmountCell) {
    const productTotalAmount = productPrice * Math.ceil(productQuantityInput.value);
    productTotalAmountCell.innerHTML = "&#8358;" + addCommaToNumber(productTotalAmount.toFixed(2));

    updateCartTotalPrice();
}

function updateCartTotalPrice() {
    const cartProductPriceInputs = document.getElementsByClassName("cart-product-price");
    const cartProductQuantityInputs = document.getElementsByClassName("cart-product-quantity");

    const totalNumberOfProducts = cartProductPriceInputs.length;
    let totalPriceOfProducts = 0;

    for (let i = 0; i < totalNumberOfProducts; i++) {
        totalPriceOfProducts += cartProductPriceInputs[i].value * cartProductQuantityInputs[i].value;
    }

    const totalPriceCell = document.getElementById("total-price");

    totalPriceCell.innerHTML = "&#8358;" + addCommaToNumber(totalPriceOfProducts.toFixed(2));
}

function removeProductFromCart(event) {
    const cartProductsTableBody = document.getElementById("cart-products-table-body");

    let productRow = event.target.parentElement.parentElement.parentElement;

    cartProductsTableBody.removeChild(productRow);

    updateCartTotalPrice();
}

function addCommaToNumber(number) {
    let commaAddedNumber = number.charAt(number.length - 1) + number.charAt(number.length - 2) + '.';

    number = Math.round(number);

    let numberOfDigits = String(number).length;
    let digitCount = 0;

    for (let i = numberOfDigits - 1; i >= 0; i--) {
        digitCount++;
        commaAddedNumber += String(number).charAt(i);

        if (digitCount % 3 == 0 && i != 0) {
            commaAddedNumber += ",";
        }
    }

    commaAddedNumber = reverseString(commaAddedNumber);

    return commaAddedNumber;
}

function reverseString (regularString) {
    let reversedString = "";

    let stringLength = regularString.length;

    for (let i = stringLength - 1; i >= 0; i--) {
        reversedString += regularString.charAt(i);
    }

    return reversedString;
}

function setUpdateFormAttributes() {
    const cartUpdateForm = document.getElementById("cart-update-form");
    const updateCartButton = document.getElementById("update-button");

    cartUpdateForm.removeEventListener("submit", payWithPaystack, false);

    cartUpdateForm.setAttribute("method", "post");
    cartUpdateForm.setAttribute("action", "cart.php");
    updateCartButton.click();
}