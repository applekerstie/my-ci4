<!DOCTYPE html>
<html>
<head>
    <title>회원가입</title>
</head>
<body>
    <h1>회원가입</h1>
    <form action="/register/create" method="post">
        <label for="username">사용자명</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">비밀번호</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="email">이메일</label>
        <input type="email" id="email" name="email" required><br><br>

        <button type="submit">가입하기</button>
    </form>
</body>
</html>