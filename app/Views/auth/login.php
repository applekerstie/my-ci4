<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    
    <?php /*
    // 240623 kerstie
    <?php if(session()->getFlashdata('error')): ?>
        <div><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <form action="/login/auth" method="post">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Login</button>
    </form>
    */ ?>

    <?php /*
    // 240625 kerstie
    <form id="loginForm" action="/login/auth" method="post">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Login</button>
    </form>
    <div id="token"></div>
    <script>
        document.getElementById('loginForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.token) {
                    document.getElementById('token').textContent = 'Token: ' + data.token;
                    localStorage.setItem('jwt_token', data.token); // 토큰을 로컬 스토리지에 저장
                } else {
                    alert(data.error);
                }
            });
        });
    </script>
    */ ?>

    <?php if(session()->getFlashdata('error')): ?>
        <div><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <form action="/login/auth" method="post">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Login</button>
    </form>

</body>
</html>
