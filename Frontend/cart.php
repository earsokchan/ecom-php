<?php include"component/head.php"?>

<!-- Start Header/Navigation -->

<nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">

<div class="container">
    <a class="navbar-brand" href="index.php">Dior<span>.</span></a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni" aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsFurni">
        <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
            <li class="nav-item ">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <li><a class="nav-link" href="shop.php">Shop</a></li>
            <li><a class="nav-link" href="about.php">About us</a></li>
            <li><a class="nav-link" href="services.php">Services</a></li>
            <li><a class="nav-link" href="blog.php">Blog</a></li>
            <li><a class="nav-link" href="contact.php">Contact us</a></li>
        </ul>

        <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
            <li><a class="nav-link" href="#"><img src="images/user.svg"></a></li>
            <li><a class="nav-link" href="cart.php"><img src="images/cart.svg"></a></li>
        </ul>
    </div>
</div>
    
</nav>
<!-- End Header/Navigation -->

<!-- Start Hero Section -->
<div class="hero">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-5">
                <div class="intro-excerpt">
                    <h1>Cart</h1>
                </div>
            </div>
            <div class="col-lg-7">
                
            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->



<div class="untree_co-section before-footer-section">
    <div class="container">
        <div class="row mb-5">
            <form class="col-md-12" method="post">
                <div class="site-blocks-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="product-thumbnail">Image</th>
                                <th class="product-name">Product</th>
                                <th class="product-price">Price</th>
                                <th class="product-quantity">Quantity</th>
                                <th class="product-total">Total</th>
                                <th class="product-remove">Remove</th>
                            </tr>
                        </thead>
                        <tbody id="cart-items">
                            <!-- Cart items will be inserted here by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </form>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="row mb-5">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <button class="btn btn-black btn-sm btn-block" onclick="updateCart()">Update Cart</button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-outline-black btn-sm btn-block" onclick="window.location='shop.php'">Continue Shopping</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="text-black h4" for="coupon">Hi! Here are my ABA account details</label>
                        <p>Account-holder name: EA SOCHEAT <br>
                        Account number: 007 265 828 </p>
                    </div>
                    <div class="col-md-8 mb-3 mb-md-0">
                        <input type="text" class="form-control py-3" id="coupon" placeholder="Send Your Account number: ">
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-black">Submit</button>
                    </div>
                </div>
                <!-- User Info Form -->
                <div class="row mt-5">
                    <div class="col-md-12">
                        <h4 class="text-black">User Information</h4>
                        <form id="user-info-form">
                            <div class="form-group">
                                <label for="user-name">Name</label>
                                <input type="text" class="form-control" id="user-name" placeholder="Enter your name" required>
                            </div>
                            <div class="form-group">
                                <label for="user-location">Location</label>
                                <input type="text" class="form-control" id="user-location" placeholder="Enter your location" required>
                            </div>
                            <div class="form-group">
                                <label for="user-phone">Phone</label>
                                <input type="tel" class="form-control" id="user-phone" placeholder="Enter your phone number" required>
                            </div>
                            <div class="form-group">
                                <label for="user-email">Email</label>
                                <input type="email" class="form-control" id="user-email" placeholder="Enter your email" required>
                            </div>
                            <button type="submit" class="btn btn-black mt-3">Submit Info</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6 pl-5">
                <div class="row justify-content-end">
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-12 text-right border-bottom mb-5">
                                <h3 class="text-black h4 text-uppercase">Cart Totals</h3>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <span class="text-black">Subtotal</span>
                            </div>
                            <div class="col-md-6 text-right">
                                <strong class="text-black" id="cart-subtotal">$0.00</strong>
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-md-6">
                                <span class="text-black">Total</span>
                            </div>
                            <div class="col-md-6 text-right">
                                <strong class="text-black" id="cart-total">$0.00</strong>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                            <form id="checkout-form" method="POST" action="checkout.php" style="display: none;">
                                <input type="hidden" name="cart_data" id="cart-data">
                            </form>
                            <button class="btn btn-black btn-lg py-3 btn-block" onclick="proceedToCheckout()">Proceed To Checkout</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
        // Function to handle user info form submission
        document.getElementById('user-info-form').addEventListener('submit', function(event) {
            event.preventDefault();
            
            const userInfo = {
                name: document.getElementById('user-name').value,
                location: document.getElementById('user-location').value,
                phone: document.getElementById('user-phone').value,
                email: document.getElementById('user-email').value
            };

            localStorage.setItem('userInfo', JSON.stringify(userInfo));
            alert('User information saved successfully!');
        });

        // Function to proceed to checkout
        function proceedToCheckout() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const userInfo = JSON.parse(localStorage.getItem('userInfo')) || {};

            if (cart.length === 0) {
                alert("Your cart is empty!");
                return;
            }

            if (!userInfo.name || !userInfo.location || !userInfo.phone || !userInfo.email) {
                alert("Please fill in your user information before proceeding to checkout.");
                return;
            }

            fetch('./checkout.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ cart, userInfo })
            })
            .then(response => response.json())
            .then(data => {
                if (data.redirect_url) {
                    window.location.href = data.redirect_url; // Redirect to ABA Payway
                } else {
                    alert("Failed to process payment.");
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Function to display cart items
        function displayCartItems() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const cartItemsContainer = document.getElementById('cart-items');
            let subtotal = 0;

            cartItemsContainer.innerHTML = ''; // Clear existing content

            cart.forEach(item => {
                const total = item.price * item.quantity;
                subtotal += total;

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="product-thumbnail">
                        <img src="${item.image}" alt="Image" class="img-fluid">
                    </td>
                    <td class="product-name">
                        <h2 class="h5 text-black">${item.name}</h2>
                    </td>
                    <td>$${item.price}</td>
                    <td>
                        <div class="input-group mb-3 d-flex align-items-center quantity-container" style="max-width: 120px;">
                            <div class="input-group-prepend">
                                <button class="btn btn-outline-black decrease" type="button" onclick="updateQuantity(${item.id}, -1)">&minus;</button>
                            </div>
                            <input type="text" class="form-control text-center quantity-amount" value="${item.quantity}" placeholder="" aria-label="Example text with button addon" aria-describedby="button-addon1">
                            <div class="input-group-append">
                                <button class="btn btn-outline-black increase" type="button" onclick="updateQuantity(${item.id}, 1)">&plus;</button>
                            </div>
                        </div>
                    </td>
                    <td>$${total.toFixed(2)}</td>
                    <td><a href="#" class="btn btn-black btn-sm" onclick="removeFromCart(${item.id})">X</a></td>
                `;
                cartItemsContainer.appendChild(row);
            });

            // Update subtotal and total
            document.getElementById('cart-subtotal').textContent = `$${subtotal.toFixed(2)}`;
            document.getElementById('cart-total').textContent = `$${subtotal.toFixed(2)}`;

            // Update the cart count in the navigation bar
            updateCartCount();
        }

        // Function to update the quantity of a product in the cart
        function updateQuantity(productId, change) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            const product = cart.find(item => item.id == productId);

            if (product) {
                product.quantity += change;
                if (product.quantity < 1) {
                    product.quantity = 1;
                }
            }

            localStorage.setItem('cart', JSON.stringify(cart));
            displayCartItems();
        }

        // Function to remove a product from the cart
        function removeFromCart(productId) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            cart = cart.filter(item => item.id != productId);
            localStorage.setItem('cart', JSON.stringify(cart));
            displayCartItems();
        }

        // Function to update the cart (e.g., after changing quantities)
        function updateCart() {
            displayCartItems();
        }

        // Display cart items when the page loads
        displayCartItems();

        // Initialize the cart count on page load
        updateCartCount();

        


    </script>

<?php include"component/footer.php"?>