<!DOCTYPE html>
<html>
<head>
    <title>Posts</title>
</head>
<body>
    <h1>Posts</h1>
    <a href="/posts/create">Create Post</a>
    <!-- // 240623 kerstie -->
    <!--<a href="/logout">logout</a>-->
    <button id="logoutButton">Logout</button>

    <ul>
        <?php foreach ($posts as $post): ?>
            <li>
                <h2><?= $post['title'] ?></h2>
                <p><?= $post['content'] ?></p>
                <p>
                    <a href="/posts/edit/<?= $post['id'] ?>">Edit</a> |
                    <a href="/posts/delete/<?= $post['id'] ?>" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                </p>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>

<!-- // 240623 kerstie -->
<script>
    document.getElementById('logoutButton').addEventListener('click', function() {
    // 로그아웃 요청을 서버로 전송
    fetch('/logout', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.message === 'Logged out successfully.') {
            // 로컬 스토리지 또는 세션 스토리지에서 토큰 삭제
            localStorage.removeItem('jwt_token');
            sessionStorage.removeItem('jwt_token');
            
            // 로그인 페이지로 리다이렉트
            window.location.href = '/login';
        } else {
            alert('Logout failed: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred during logout.');
    });
});
</script>