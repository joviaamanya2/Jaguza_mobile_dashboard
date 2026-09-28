<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin - Jaguza</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .admin-card { background: #fff; padding: 32px; border-radius: 14px; box-shadow: 0 8px 28px rgba(0,0,0,.08); width: 100%; max-width: 480px; }
        .admin-card h1 { color: #2e7d32; font-size: 24px; margin-bottom: 6px; }
        .admin-card p { color: #6a7a8a; }
        .btn-primary { background: #2e7d32; border-color: #2e7d32; }
        .btn-primary:hover { background: #1b5e20; border-color: #1b5e20; }
        .form-control:focus { border-color: #2e7d32; box-shadow: 0 0 0 .2rem rgba(46,125,50,.2); }
    </style>
</head>
<body>
    <main class="admin-card">
        <h1>Create Admin Account</h1>
        <p>Register another administrator for the Jaguza dashboard.</p>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.admins.store') }}">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input id="name" name="name" type="text" class="form-control" value="{{ old('name') }}" required autofocus>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input id="email" name="email" type="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" name="password" type="password" class="form-control" minlength="8" required autocomplete="new-password">
            </div>
            <div class="mb-4">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" minlength="8" required autocomplete="new-password">
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">Create Admin</button>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </main>
</body>
</html>
