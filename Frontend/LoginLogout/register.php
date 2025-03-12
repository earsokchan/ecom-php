<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="../src/style.css">
</head>

<body>
  
<div class="form-container" id="loginForm">
    <p class="title">Welcome back</p>
    <form class="form" method="POST" action="signin.php">
        <input type="email" name="email" class="input" placeholder="Email" required>
        <input type="password" name="password" class="input" placeholder="Password" required>
        <p class="page-link">
            <span class="page-link-label">Forgot Password?</span>
        </p>
        <button type="submit" class="form-btn">Log in</button>
    </form>
    <p class="sign-up-label">
        Don't have an account? <span class="sign-up-link" onclick="showSignupForm()">Sign up</span>
    </p>
    <!-- <div class="buttons-container">
        <div class="apple-login-button">
           
        </div>
        <div class="google-login-button">
            
        </div>
    </div> -->
</div>

<div class="form-container" id="signupForm" style="display: none;">
    <p class="title">Create Account</p>
    <form class="form" method="POST" action="signup.php">
        <input type="text" name="name" class="input" placeholder="Full Name" required>
        <input type="email" name="email" class="input" placeholder="Email" required>
        <input type="password" name="password" class="input" placeholder="Password" required>
        <input type="password" name="confirm_password" class="input" placeholder="Confirm Password" required>
        <button type="submit" class="form-btn">Sign up</button>
    </form>
    <p class="sign-up-label">
        Already have an account? <span class="sign-up-link" onclick="showLoginForm()">Log in</span>
    </p>
</div>

<script>
    function showSignupForm() {
        document.getElementById('loginForm').style.display = 'none';
        document.getElementById('signupForm').style.display = 'block';
    }

    function showLoginForm() {
        document.getElementById('signupForm').style.display = 'none';
        document.getElementById('loginForm').style.display = 'block';
    }
</script>

</body>
</html>
