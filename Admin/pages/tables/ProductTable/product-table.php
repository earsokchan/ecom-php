<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product Table</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
  <div class="container mt-5">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Product Table</h4>
        <p class="card-description">Dynamic table to display uploaded products</p>
        <div class="table-responsive">
          <table class="table table-hover" id="productTable">
            <thead>
              <tr>
                <th>Product Name</th>
                <th>Image</th>
                <th>VAT Number</th>
                <th>Created Date</th>
                <th>Price</th>
                <th>Progress</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <!-- Rows will be dynamically inserted here -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Add JQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function () {
      // Load products into the table
      function loadProducts() {
        $.ajax({
          url: 'upload_product.php', // The PHP file to fetch products
          type: 'GET',
          success: function (response) {
            var products = JSON.parse(response);
            var tableBody = $('#productTable tbody');
            tableBody.empty(); // Clear existing rows

            products.forEach(function (product) {
              var progressBar = `<div class="progress">
                <div class="progress-bar bg-${getProgressBarColor(product.progress)}" role="progressbar"
                  style="width: ${product.progress}%" aria-valuenow="${product.progress}" aria-valuemin="0" aria-valuemax="100"></div>
              </div>`;

              var row = `
                <tr>
                  <td>${product.product_name}</td>
                  <td><img src="${product.image_url}" width="50" alt="${product.product_name}"></td>
                  <td>${product.vat_number}</td>
                  <td>${product.created_date}</td>
                  <td>${product.price}</td>
                  <td>${progressBar}</td>
                  <td><span class="badge bg-${getStatusBadge(product.status)}">${product.status}</span></td>
                  <td>
                    <button class="btn btn-warning btn-sm edit-product" data-id="${product.id}">Edit</button>
                    <button class="btn btn-danger btn-sm delete-product" data-id="${product.id}">Delete</button>
                  </td>
                </tr>`;
              tableBody.append(row);
            });
          },
          error: function () {
            alert('Error loading products!');
          }
        });
      }

      // Function to get the appropriate badge class based on status
      function getStatusBadge(status) {
        switch (status) {
          case 'Pending':
            return 'danger'; // Red
          case 'In progress':
            return 'warning'; // Yellow
          case 'Fixed':
            return 'info'; // Blue
          case 'Completed':
            return 'success'; // Green
          default:
            return 'secondary'; // Grey
        }
      }

      // Function to get the appropriate color for the progress bar based on the progress
      function getProgressBarColor(progress) {
        if (progress < 30) {
          return 'danger'; // Red
        } else if (progress < 70) {
          return 'warning'; // Yellow
        } else {
          return 'success'; // Green
        }
      }

      // Handle edit button click
      $(document).on('click', '.edit-product', function () {
        var productId = $(this).data('id');
        // Fetch product details and populate the form for editing
        $.ajax({
          url: 'upload_product.php',
          type: 'GET',
          data: { id: productId },
          success: function (response) {
            var product = JSON.parse(response);
            $('#productName').val(product.product_name);
            $('#vatNumber').val(product.vat_number);
            $('#createdDate').val(product.created_date);
            $('#price').val(product.price);
            $('#status').val(product.status);
            $('#imageFile').removeAttr('required'); // Remove required attribute for image file
            $('#uploadProductForm').data('id', productId); // Store product ID in form data
            $('#productId').val(productId);
          }
        });
      });

      // Handle delete button click
      $(document).on('click', '.delete-product', function () {
        var productId = $(this).data('id');
        if (confirm('Are you sure you want to delete this product?')) {
          $.ajax({
            url: 'upload_product.php',
            type: 'DELETE',
            data: { id: productId },
            success: function (response) {
              var result = JSON.parse(response);
              if (result.message) {
                alert(result.message); // Show success message
                loadProducts(); // Reload the product table
              } else if (result.error) {
                alert(result.error); // Show error message
              }
            }
          });
        }
      });

      // Initial load of products
      loadProducts();
    });
  </script>
</body>
</html>