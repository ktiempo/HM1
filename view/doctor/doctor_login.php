<?php
session_start();
include('../../db/config.php');

// Handle login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  $stmt = $conn->prepare("SELECT * FROM doctors WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $doctor = $result->fetch_assoc();
    if (password_verify($password, $doctor['password'])) {
      // Save doctor session
      $_SESSION['doctor_id'] = $doctor['id'];
      $_SESSION['doctor_name'] = $doctor['name'];
      header("Location: dashboard.php");
      exit;
    } else {
      $error = "Invalid password.";
    }
  } else {
    $error = "No doctor found with that email.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Doctor Login | HealthMate</title>

  <!-- Favicons -->
  <link href="../../assets/img/healthmate-logo.png" rel="icon">
  <link href="../../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <!-- Bootstrap -->
  <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <!-- SweetAlert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #004b63, #0088a9);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0;
    }

    .login-card {
      background: #ffffff;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 420px;
      padding: 40px 35px;
      text-align: center;
    }

    .login-card img {
      width: 70px;
      margin-bottom: 15px;
    }

    .login-card h3 {
      font-weight: 600;
      color: #004b63;
      margin-bottom: 25px;
    }

    .form-control {
      border-radius: 10px;
      padding: 10px 15px;
      border: 1px solid #ccc;
      transition: border-color 0.3s;
    }

    .form-control:focus {
      border-color: #0088a9;
      box-shadow: 0 0 0 0.2rem rgba(0, 136, 169, 0.25);
    }

    .btn-login {
      background-color: #0088a9;
      border: none;
      color: white;
      font-weight: 600;
      padding: 10px 0;
      border-radius: 10px;
      width: 100%;
      transition: background-color 0.3s;
    }

    .btn-login:hover {
      background-color: #006d87;
    }

    .footer-text {
      margin-top: 25px;
      font-size: 0.9rem;
      color: #777;
    }

    .footer-text a {
      color: #0088a9;
      text-decoration: none;
      font-weight: 500;
    }

    .footer-text a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>

  <div class="login-card">
    <img src="../../assets/img/healmate_banner.png" alt="HealthMate Logo">
    <h3>Doctor Login</h3>
    <form method="POST">
      <div class="mb-3 text-start">
        <label for="email" class="form-label fw-semibold">Email</label>
        <input type="email" name="email" class="form-control" id="email" required placeholder="Enter your email">
      </div>
      <div class="mb-3 text-start">
        <label for="password" class="form-label fw-semibold">Password</label>
        <input type="password" name="password" class="form-control" id="password" required placeholder="Enter your password">
      </div>
      <button type="submit" class="btn btn-login">Login</button>
    </form>
    <p class="footer-text mt-4">© 2025 <strong>HealthMate</strong></p>
  </div>

  <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <?php if (isset($error)): ?>
  <script>
  Swal.fire({
    icon: 'error',
    title: 'Login Failed',
    text: '<?php echo $error; ?>',
    confirmButtonColor: '#0088a9'
  });
  </script>
  <?php endif; ?>

  <?php if (isset($_GET['logout']) && $_GET['logout'] === 'success'): ?>
  <script>
  Swal.fire({
    icon: 'success',
    title: 'Signed Out',
    text: 'You have successfully logged out.',
    confirmButtonColor: '#0088a9'
  });
  </script>
  <?php endif; ?>

</body>
</html>
