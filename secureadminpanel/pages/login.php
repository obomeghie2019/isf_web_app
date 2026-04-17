<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Admin Panel::ISF Marathon 2026</title>
  <style>
    /* ===== Reset & Base Styles ===== */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #1e1e1e, #111);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      perspective: 1200px;
      overflow: hidden;
    }

    /* ===== Login Card ===== */
    .login-card {
      background: #2c2c2c;
      width: 100%;
      max-width: 400px;
      border-radius: 15px;
      box-shadow: 0 15px 35px rgba(0,0,0,0.4);
      transform-style: preserve-3d;
      transition: transform 0.5s ease, box-shadow 0.5s ease;
      animation: fadeIn 1s ease;
    }

    .login-card:hover {
      transform: rotateY(5deg) rotateX(5deg) translateZ(10px);
      box-shadow: 0 25px 50px rgba(0,0,0,0.5);
    }

    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(-20px);}
      to {opacity: 1; transform: translateY(0);}
    }

    .login-card header {
      background: #444;
      color: #fff;
      text-align: center;
      font-size: 1.5rem;
      font-weight: 600;
      padding: 1rem;
      border-radius: 15px 15px 0 0;
    }

    .login-card .card-body {
      padding: 2rem;
    }

    .login-card .form-group {
      margin-bottom: 1.5rem;
    }

    .login-card label {
      display: block;
      margin-bottom: 0.5rem;
      color: #ddd;
    }

    .login-card input {
      width: 100%;
      padding: 12px;
      border-radius: 8px;
      border: 1px solid #666;
      background: #1e1e1e;
      color: #fff;
      font-size: 1rem;
      transition: all 0.3s ease;
    }

    .login-card input:focus {
      border-color: #4CAF50;
      box-shadow: 0 0 8px rgba(76,175,80,0.6);
      background: #292929;
      color: #fff;
      outline: none;
    }

    .login-card button {
      width: 100%;
      padding: 12px;
      font-size: 1rem;
      font-weight: 600;
      color: #fff;
      background: #4CAF50;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .login-card button:hover {
      background: #2E7D32;
      box-shadow: 0 5px 15px rgba(0,0,0,0.5);
      transform: translateY(-2px);
    }

    /* Logo */
    .login-brand img {
      display: block;
      margin: 0 auto 20px auto;
      max-width: 100%;
      height: auto;
    }

    /* Footer */
    .login-footer {
      text-align: center;
      color: #ccc;
      font-size: 0.9rem;
      margin-top: 15px;
    }

    /* Container */
    .container {
      width: 100%;
      max-width: 400px;
      padding: 0 15px;
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="login-brand">
      <img src="imgs/logoisf.png" alt="Logo" title="Your logo" width="280" height="70">
    </div>

    <div class="login-card">
      <header>Admin Login</header>
      <div class="card-body">
        <form method="POST" action="logme.php" novalidate>
          <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" required autofocus>
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>
          </div>

          <div class="form-group">
            <button type="submit" id="login-btn" name="login">Login</button>
          </div>
        </form>
      </div>
    </div>

    <div class="login-footer">
      &copy; ISF Marathon 2026. All rights reserved.
    </div>
  </div>

</body>
</html>
