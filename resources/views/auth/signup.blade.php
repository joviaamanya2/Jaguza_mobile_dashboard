<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign Up - Jaguza</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #bec2be, #e7f0e7);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .signup-card {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 420px;
        }
        .logo { text-align: center; margin-bottom: 30px; }
        .logo img { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; margin-bottom: 12px; }
        .logo h1 { color: #2e7d32; font-weight: 700; margin: 0; }
        .logo span { color: #6c757d; font-size: 14px; display: block; margin-top: 4px; }
        .btn-primary { background: #2e7d32; border-color: #2e7d32; }
        .btn-primary:hover { background: #1b5e20; border-color: #1b5e20; }
        .form-control:focus { border-color: #2e7d32; box-shadow: 0 0 0 0.2rem rgba(46,125,50,0.25); }
    </style>
</head>
<body>
    <div class="signup-card">
        <div class="logo">
            <img src="{{ asset('/images/logo.png') }}" alt="Jaguza Logo">
            <h1>Jaguza</h1>
            <span>Create an admin account</span>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p class="mb-0">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.signup.store') }}">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required autofocus>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" minlength="8" required autocomplete="new-password">
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" minlength="8" required autocomplete="new-password">
            </div>
            <button type="submit" class="btn btn-primary w-100">Create Admin Account</button>
            <div class="mt-3 text-center text-muted">
                Already have an account?
                <a href="{{ route('login') }}" class="text-decoration-none" style="color: #2e7d32;">Log in</a>
            </div>
        </form>
    </div>
</body>
</html>
