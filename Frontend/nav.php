<!-- Start Header/Navigation -->
<nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">
    <div class="container">
        <a class="navbar-brand" href="index.php">Dior<span>.</span></a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni" aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsFurni">
            <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li><a class="nav-link" href="shop.php">Shop</a></li>
                <li><a class="nav-link" href="about.php">About us</a></li>
                <li><a class="nav-link" href="services.php">Services</a></li>
                <li><a class="nav-link" href="blog.php">Blog</a></li>
                <li><a class="nav-link" href="contact.php">Contact us</a></li>
            </ul>

            <style>
        /* Style for the cart count */
        #cart-count {
             /* Red background */
            color:rgb(0, 0, 0); /* White text */
            font-size: 20px; /* Small font size */
            padding: 3px 10px; /* Padding for the badge */
            border-radius: 50%; /* Make it circular */
            position: absolute; /* Position it absolutely relative to the cart icon */
            top: -5px; /* Adjust vertical position */
            right: -10px; /* Adjust horizontal position */
            font-weight: bold; /* Bold text */
        }

        /* Ensure the cart link is positioned relatively */
        .custom-navbar-cta .nav-link {
            position: relative;
            display: inline-block;
        }

        /* Optional: Add hover effect to the cart icon */
        .custom-navbar-cta .nav-link:hover img {
            opacity: 0.8;
        }
    </style>
</head>
					<ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
    <li><a class="nav-link" href="http://localhost/ecommercephp/Frontend/LoginLogout/register.php"><img src="images/user.svg"></a></li>
    <li><a class="nav-link" href="cart.php">
        <img src="images/cart.svg">
		
        <span id="cart-count"></span>
		<!-- Add this line -->
    </a></li>
	
</ul>
        </div>
    </div>
</nav>

<!-- End Header/Navigation -->

<script>
    // Function to handle adding a product to the cart
    function addToCart(event) {
            const productId = event.currentTarget.getAttribute('data-product-id');
            const productName = event.currentTarget.getAttribute('data-product-name');
            const productPrice = event.currentTarget.getAttribute('data-product-price');
            const productImage = event.currentTarget.getAttribute('data-product-image');

            // Create a product object
            const product = {
                id: productId,
                name: productName,
                price: productPrice,
                image: productImage,
                quantity: 1
            };

            // Retrieve the existing cart from localStorage or initialize an empty array
            let cart = JSON.parse(localStorage.getItem('cart')) || [];

            // Check if the product is already in the cart
            const existingProduct = cart.find(item => item.id === productId);

            if (existingProduct) {
                // If the product is already in the cart, increase the quantity
                existingProduct.quantity += 1;
            } else {
                // If the product is not in the cart, add it
                cart.push(product);
            }

            // Save the updated cart back to localStorage
            localStorage.setItem('cart', JSON.stringify(cart));

            // Update the cart count in the navigation bar
            updateCartCount();

            // Optionally, you can show a notification or update the cart icon
            alert(`${productName} has been added to the cart!`);
        }

        // Function to update the cart count in the navigation bar
        function updateCartCount() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const cartCountElement = document.getElementById('cart-count');

            // Calculate the total number of items in the cart
            const totalItems = cart.reduce((total, item) => total + item.quantity, 0);

            // Update the cart count
            cartCountElement.textContent = totalItems;
        }
</script>