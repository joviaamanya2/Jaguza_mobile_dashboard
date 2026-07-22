<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Jaguza Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #bec2be, #e7f0e7);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 420px;
        }
        .login-card .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        /* logo image style – keep it contained and responsive */
        .login-card .logo img {
            max-width: 80px;        /* adjust size as needed */
            height: auto;
            display: block;
            margin: 0 auto 12px;    /* center and add spacing below */
        }
        .login-card .logo h1 {
            color: #2e7d32;
            font-weight: 700;
            margin: 0;
        }
        .login-card .logo span {
            color: #6c757d;
            font-size: 14px;
            display: block;
            margin-top: 4px;
        }
        .btn-primary {
            background: #2e7d32;
            border-color: #2e7d32;
        }
        .btn-primary:hover {
            background: #1b5e20;
            border-color: #1b5e20;
        }
        .form-control:focus {
            border-color: #2e7d32;
            box-shadow: 0 0 0 0.2rem rgba(46,125,50,0.25);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo">
            
            <img src="{{ asset('/images/logo.png') }}" alt="Jaguza Logo" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
            <h1>Jaguza</h1>
            <span>Admin Dashboard Login</span>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p class="mb-0">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>

            <button type="submit" class="btn btn-primary w-100">Login</button>

            <div class="mt-3 text-center">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-decoration-none" style="color: #2e7d32;">
                        Forgot your password?
                    </a>
                @endif
            </div>

            <div class="mt-3 text-center text-muted">
                Need an admin account?
                <a href="{{ route('admin.signup') }}" class="text-decoration-none" style="color: #2e7d32;">Sign up</a>
            </div>
        </form>
    </div>
</body>
</html>
