<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wiskuy</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    {{-- CSS LOGIN --}}
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

<div class="main-container">
    <div class="hero-content">
        <span class="brand-tag">WisKuyy!</span>
        <h1 class="hero-title">EXPLORE<br>HORIZONS</h1>
        <p class="hero-desc">
          Kelola lebih cerdas. Pantau lebih mudah. Semua dalam satu tempat.
Kendali penuh atas destinasi wisata CIAYUMAJAKUNING, kapan saja dan di mana saja.
        </p>
    </div>

    <div class="login-card">
        <div class="login-header">
            <h4>Welcome Back!</h4>
            <p>Silakan login untuk mengakses dashboard</p>
        </div>

        <form action="{{ route('login.proses') }}" method="POST">
            @csrf
            
            <div class="input-wrapper">
                <div class="custom-input-group">
                    <div class="icon-box"><i class="bi bi-person"></i></div>
                    <input type="text" name="username" placeholder="Username" required>
                    <div style="width: 50px;"></div> 
                </div>
            </div>

            <div class="input-wrapper">
                <div class="custom-input-group">
                    <div class="icon-box"><i class="bi bi-lock"></i></div>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <div class="password-toggle" onclick="togglePassword()">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-submit">Sign In</button>
        </form>
    </div>
</div>

<script>
function togglePassword() {
    const passInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    if (passInput.type === 'password') {
        passInput.type = 'text';
        eyeIcon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        passInput.type = 'password';
        eyeIcon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}
</script>

</body>
</html>