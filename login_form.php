<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="bootstrap-4.6.2/css/bootstrap.min.css">
  <!-- Custom CSS -->
  <style>
    body {
      background: linear-gradient(135deg, #e0f7fa, #f1f8e9);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .login-card {
      background: #ffffff;
      padding: 2.5rem;
      border-radius: 16px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.12);
      width: 100%;
      max-width: 380px;
      animation: fadeIn 0.6s ease-in-out;
      text-align: center; /* Biar semua isi form ketengah */
    }
    .login-card h3 {
      margin-bottom: 2rem;
      font-weight: 600;
      color: #37474f;
    }
    .form-control {
      border-radius: 10px;
      padding: 0.75rem 1rem;
      border: 1px solid #cfd8dc;
      transition: all 0.3s ease;
      margin-bottom: 1rem; /* kasih jarak antar input */
      text-align: center;  /* teks di input ke tengah */
    }
    .form-control:focus {
      border-color: #ba68c8;
      box-shadow: 0 0 8px rgba(186, 104, 200, 0.5);
    }
    .btn-custom {
      background-color: #ba68c8;
      color: #fff;
      font-weight: 600;
      border-radius: 10px;
      padding: 0.75rem;
      width: 100%;
      margin-top: 0.5rem;
      transition: 0.3s;
    }
    .btn-custom:hover {
      background-color: #ab47bc;

    }
    .register-link {
      display: block;
      text-align: center;
      margin-top: 1.5rem;
      font-size: 0.95rem;
      color: #546e7a;
    }
    .register-link:hover {
      color: #6a1b9a;
      text-decoration: none;
    }
    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(-15px);}
      to {opacity: 1; transform: translateY(0);}
    }
  </style>
</head>
<body>
  <div class="login-card">
    <h3>Welcome Back 👋</h3>
    <form action="login.php" method="POST">
      <input type="text" class="form-control" name="username" placeholder="Username" required>
      <input type="password" class="form-control" name="password" placeholder="Password" required>
      <button type="submit" class="btn btn-custom">Login</button>
    </form>
    <a href="register_form.php" class="register-link">Don’t have an account? Register</a>
  </div>

  <!-- Bootstrap JS -->
  <script src="bootstrap-4.6.2/js/jquery.min.js"></script>
  <script src="bootstrap-4.6.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
