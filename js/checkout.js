let transactionReference = document.getElementById("transaction-reference");
const paymentForm = document.getElementById("cart-update-form");
const emailAddress = document.getElementById("email-address").value;
const totalPrice = document.getElementById("total-products-price").value;

paymentForm.addEventListener("submit", payWithPaystack, false);

function beginCheckoutProcess() {
    if (confirm("Are you sure you want to checkout cart?")) {
        paymentForm.addEventListener("submit", payWithPaystack, false);
        removePaymentFormAttributes();
        checkOrderFeasibility();
    } else {
        paymentForm.removeEventListener("submit", payWithPaystack, false);
    }
}

function checkOrderFeasibility() {
    const orderFeasibilityCheckRequest = new XMLHttpRequest();

    orderFeasibilityCheckRequest.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText.length != 0) {
                alert(this.responseText);
            } else {
                disableQuantityInputs();
                payWithPaystack();
            }
        }
    };

    orderFeasibilityCheckRequest.open("POST", "order-feasibility-check.php", true);
    orderFeasibilityCheckRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    orderFeasibilityCheckRequest.send();
}

function payWithPaystack(e) {
    e.preventDefault();

    let handler = PaystackPop.setup({
        key: "pk_test_78704267fc4e4539e7bb23d89eeb575761be73de",
        email: emailAddress,
        amount: totalPrice * 100,

        onClose: function() {
            alert("Cancel transaction?");
            enableQuantityInputs();
        },

        callback: function(response) {
            paymentForm.removeEventListener("submit", payWithPaystack, false);
            transactionReference.value = response.reference;
            checkOut();
        }   //  end of callback
    });

    handler.openIframe();
}   //  end of payWithPaystack()

function checkOut() {
    const checkoutButton = document.getElementById("checkout-button");
    checkoutButton.removeAttribute("onclick");

    paymentForm.setAttribute("action", "cart.php");
    paymentForm.setAttribute("method", "POST");
    checkoutButton.click();
}

function disableQuantityInputs () {
    const cartProductQuantityInputs = document.getElementsByClassName("cart-product-quantity");

    for (const currentCartProductQuantityInput of cartProductQuantityInputs) {
        currentCartProductQuantityInput.setAttribute("disabled", "disabled");
    }
}

function enableQuantityInputs () {
    const cartProductQuantityInputs = document.getElementsByClassName("cart-product-quantity");

    for (const currentCartProductQuantityInput of cartProductQuantityInputs) {
        currentCartProductQuantityInput.removeAttribute("disabled");
    }
}

function removePaymentFormAttributes() {
    paymentForm.removeAttribute("action");
}