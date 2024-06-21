<?php
// 테스트 비밀번호
$password = 'test123';

// 해시 생성
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// 해시 확인
if (password_verify($password, $hashed_password)) {
    echo 'Password verified successfully.';
} else {
    echo 'Password verification failed.';
}
