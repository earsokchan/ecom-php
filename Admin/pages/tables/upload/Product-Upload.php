<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product Upload</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
  <div class="container mt-5">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Product Upload</h4>
        <form id="uploadProductForm" enctype="multipart/form-data">
          <div class="form-group">
            <label for="productName">Product Name</label>
            <input type="text" class="form-control" id="productName" name="productName" placeholder="Enter product name" required>
          </div>
          <div class="form-group">
            <label for="vatNumber">VAT Number</label>
            <input type="text" class="form-control" id="vatNumber" name="vatNumber" placeholder="Enter VAT number" required>
          </div>
          <div class="form-group">
            <label for="createdDate">Created Date</label>
            <input type="date" class="form-control" id="createdDate" name="createdDate" required>
          </div>
          <div class="form-group">
            <label for="price">Price</label>
            <input type="number" class="form-control" id="price" name="price" placeholder="Enter price" required>
          </div>
          <div class="form-group">
            <label for="imageFile">Product Image</label>
            <input type="file" class="form-control" id="imageFile" name="imageFile" accept="image/*">
          </div>
          <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" id="status" name="status" required>
              <option value="Pending">Pending</option>
              <option value="In progress">In progress</option>
              <option value="Fixed">Fixed</option>
              <option value="Completed">Completed</option>
            </select>
          </div>
          <input type="hidden" id="productId" name="productId">
          <button type="submit" class="btn btn-primary" id="submitButton">Upload Product</button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#uploadProductForm').on('submit', function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        var productId = $('#productId').val();
        var url = productId ? 'upload_product.php?id=' + productId : 'upload_product.php';
        var type = productId ? 'POST' : 'POST';

        if (productId) {
          formData.append('_method', 'PUT');
        }

        $.ajax({
          url: url,
          type: type,
          data: formData,
          processData: false,
          contentType: false,
          success: function (response) {
            var result = JSON.parse(response);
            if (result.message) {
              alert(result.message);
              $('#uploadProductForm')[0].reset();
              $('#productId').val('');
              $('#imageFile').attr('required', 'required');
              $('#submitButton').text('Upload Product'); // Reset button text
              window.location.href = 'http://localhost/ecommercephp/Admin/pages/tables/basic-table.php';
            } else if (result.error) {
              alert(result.error);
            }
          },
          error: function () {
            alert('Error occurred while uploading the product!');
          }
        });
      });

      // Handle edit button click
      $(document).on('click', '.edit-product', function () {
        var productId = $(this).data('id');
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
            $('#imageFile').removeAttr('required');
            $('#productId').val(productId);
            $('#submitButton').text('Save Product'); // Change button text to "Save Product"
          }
        });
      });
    });
  </script>
</body>
</html>