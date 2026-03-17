<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | FurnishPro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: rgb(249, 250, 251);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
            padding: 15px; /* Prevent edge touching on mobile */
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 14px;
            padding: 30px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .login-title {
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 1.5rem;
        }

        .form-control {
            padding: 12px;
            border-radius: 8px;
            font-size: 15px;
        }

        .btn-login {
            background: rgb(0, 110, 255);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            font-size: 16px;
            color: hsl(257, 54%, 97%);
        }

        .btn-login:hover {
            opacity: 0.9;
        }

        .forgot-link {
            text-decoration: none;
            font-size: 14px;
            color: #487fe6;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        /* 📱 Mobile tweaks */
        @media (max-width: 576px) {
            .login-card {
                padding: 22px;
                border-radius: 12px;
            }

            .login-title {
                font-size: 1.3rem;
            }

            .forgot-link {
                font-size: 13px;
            }
        }
    </style>
</head>
<body>

<div class="login-card">
    <h3 class="text-center login-title">Login to Your Account</h3>

    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email"
                   name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}"
                   placeholder="admin@example.com"
                   required>
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password"
                   name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="••••••••"
                   required>
        </div>

        <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">
                    Remember Me
                </label>
            </div>

            <a href="#" class="forgot-link">
                Forgot your password?
            </a>
        </div>

        <button type="submit" class="btn btn-login w-100">
            Log In
        </button>
    </form>
</div>

</body>
</html>
