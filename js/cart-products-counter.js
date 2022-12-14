function getNumberOfCartProducts() {
    const cartProductsCountRequest = new XMLHttpRequest();

    cartProductsCountRequest.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("header-quantity").innerHTML = this.responseText;
        }
    };

    cartProductsCountRequest.open("POST", "cart-products-counter.php", true);
    cartProductsCountRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    cartProductsCountRequest.send();
}