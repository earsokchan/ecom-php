<!-- /*
* Bootstrap 5
* Template Name: Furni
* Template Author: Untree.co
* Template URI: https://untree.co/
* License: https://creativecommons.org/licenses/by/3.0/
*/ -->
<?php include"component/head.php"?>

	<body>

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
						<li class="active"><a class="nav-link" href="shop.php">Shop</a></li>
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
            background-color: rgb(255, 255, 255); /* Red background */
            font-size: 14px; /* Small font size */
            padding: 0px 10px; /* Padding for the badge */
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
    <li><a class="nav-link" href="./LoginLogout/register.php"><img src="images/user.svg"></a></li>
    <li><a class="nav-link" href="cart.php">
        <img src="images/cart.svg">
		
        <span id="cart-count">0</span>
		<!-- Add this line -->
    </a></li>
	
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
								<h1>Shop</h1>
							</div>
						</div>
						<div class="col-lg-7">
							
						</div>
					</div>
				</div>
			</div>
		<!-- End Hero Section -->

		

		<div class="untree_co-section product-section before-footer-section">
		    <div class="container">
		      	<div class="row">

		      		<!-- Container for Products -->
<div class="row" id="product-container">
    <!-- Product items will be inserted here by JavaScript -->
</div>

<script>
        // Fetch product data from the API
        fetch('http://localhost/ecommercephp/Admin/pages/tables/upload_product.php')
            .then(response => response.json())
            .then(products => {
                const productContainer = document.getElementById('product-container');
                productContainer.innerHTML = '';  // Clear existing content

                if (products.error) {
                    console.error('Error fetching products:', products.error);
                    return;
                }

                // Loop through each product and create the HTML structure
                products.forEach(product => {
                    const productItem = document.createElement('div');
                    productItem.classList.add('col-12', 'col-md-4', 'col-lg-3', 'mb-5');

                    // Fixing the image URL by replacing escaped backslashes
                    const imageUrl = 'http://localhost/ecommercephp/Admin/pages/tables/' + product.image_url.replace(/\\/g, '/');

                    productItem.innerHTML = `
                        <div class="product-item">
                            <img src="${imageUrl}" class="img-fluid product-thumbnail" alt="${product.product_name}">
                            <h3 class="product-title">${product.product_name}</h3>
                            <strong class="product-price">$${product.price}</strong>
                            <span class="icon-cross" data-product-id="${product.id}" data-product-name="${product.product_name}" data-product-price="${product.price}" data-product-image="${imageUrl}">
                                <img src="images/cross.svg" class="img-fluid" alt="Add to Cart">
                            </span>
                        </div>
                    `;

                    productContainer.appendChild(productItem);
                });

                // Add event listeners to all "icon-cross" elements for "Add to Cart"
                document.querySelectorAll('.icon-cross').forEach(icon => {
                    icon.addEventListener('click', addToCart);
                });

                // Initialize the cart count on page load
                updateCartCount();
            })
            .catch(error => {
                console.error('Error fetching product data:', error);
            });

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
            showNotification(`${productName} has been added to the cart!`);
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
         // Function to show a custom notification with animation
    function showNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'custom-alert';
        notification.textContent = message;

        document.body.appendChild(notification);

        // Add fade-in animation
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);

        // Remove notification after 3 seconds with fade-out animation
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 100);
        }, 1000);
    }
    </script>
    <style>
    /* Custom notification styles */
    .custom-alert {
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: #60f70f;
        color: #fff;
        padding: 15px 20px;
        border-radius: 8px;
        opacity: 0;
        transform: translateY(-20px);
        transition: opacity 0.1s ease, transform 0.1s ease;
        z-index: 500;
        box-shadow: 0 4px 6px rgba(19, 238, 77, 0.1);
    }

    .custom-alert.show {
        opacity: 1;
        transform: translateY(0);
    }
</style>


		      	</div>
		    </div>
		</div>


		<?php include"component/footer.php"?>