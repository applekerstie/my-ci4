
## 1.
```
mkdir dockerfile
cd dockerfile
vi dockerfile
docker build -t apache2-php8-ubuntu .
```

## 2.
```
docker images
docker save -o apache2-php8-ubuntu.tar apache2-php8-ubuntu:latest
```

## 3.
```
docker run --name my-ci4-container -d -p 80:80 -p 443:443 -v ~/my-ci4/:/var/www/html apache2-php8-ubuntu
```

## 4.
```
ubuntu@DESKTOP-QRPDHRI:~$ cd my-ci4/
ubuntu@DESKTOP-QRPDHRI:~/my-ci4$ composer create-project codeigniter4/appstarter project-root
```

## 5.
```
docker exec -it my-ci4-container /bin/bash

vi /etc/apache2/sites-available/000-default.conf

<VirtualHost *:80>
    DocumentRoot /var/www/html/project-root/public
    <Directory /var/www/html/project-root/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

## 6.
```
root@58d030c74d06:/etc/apache2/sites-available# a2enmod rewrite
Module rewrite already enabled
```

## 7.
```
cd /var/www/html/project-root
root@58d030c74d06:/var/www/html/project-root# cp env .env
root@58d030c74d06:/var/www/html/project-root# vi .env

CI_ENVIRONMENT = development
```

## 8.
```
docker restart my-ci4-container
http://localhost/
http://dev.myapp.com/

```

## --------------------------------------------------------------------------------
## 9.
```
docker exec -it my-ci4-container /bin/bash
cd /var/www/html/project-root
chown -R www-data:www-data writable
chmod -R 775 writable
service apache2 restart
```

## --------------------------------------------------------------------------------
## 10.
```
docker exec -it my-ci4-container /bin/bash
apt-get update
apt-get install -y php8.1-intl
service apache2 restart
```

## --------------------------------------------------------------------------------
## 11.
```
http://localhost/
http://dev.myapp.com/
Welcome to CodeIgniter 4.5.2
```

## 12.
```
docker inspect -f "{{ .NetworkSettings.IPAddress }}" 0cd4971d4dd2

app/Config/Database.php

    public array $default = [
        'DSN'          => '',
        'hostname'     => '172.17.0.3',
        'username'     => 'dev01',
        'password'     => 'dev01!@',
        'database'     => 'dev',
        'DBDriver'     => 'MySQLi',
        'DBPrefix'     => '',
        'pConnect'     => false,
        'DBDebug'      => (ENVIRONMENT !== 'production'),

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
ALTER TABLE posts ADD updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
```

## 13.
```
.htaccess
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php/$1 [L]
</IfModule>
```

## 14.
```
app/Config/App.php
아래와 같이 수정
public string $baseURL = 'http://dev.myapp.com/';
public string $indexPage = '';
```

## 15.
```
git --version
git init
git config --global --list
git remote add origin https://github.com/applekerstie/my-ci4.git
```

## -------------------------------------------------------------------------------- 게시판구현
## 16.
```
app/Models/PostModel.php

<?php
namespace App\Models;

use CodeIgniter\Model;

class PostModel extends Model
{
    protected $table = 'posts';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'content'];
    protected $useTimestamps = true;
}

?>
```

## 17.
```
app/Controllers/PostController.php

<?php

namespace App\Controllers;

use App\Models\PostModel;
use CodeIgniter\Controller;

class PostController extends Controller
{
    protected $db;

    public function __construct()
    {
        // 기본 데이터베이스 서비스 인스턴스를 로드
        $this->db = \Config\Database::connect();
    }

    public function index()
    { 
        /*
        $model = new PostModel();
        $data['posts'] = $model->findAll();
        */

        /*
        $db = db_connect();
        $query = $db->query('SELECT * FROM posts');
        */

        $query = $this->db->query('SELECT * FROM posts');

        $data['posts'] = $query->getResultArray();
        return view('posts/index', $data);
    }

    public function create()
    {
        return view('posts/create');
    }

    public function store()
    {   
        /*
        $model = new PostModel();
        $data = [
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
        ];
        $model->save($data);
        */

        $title = $this->request->getPost('title');
        $content = $this->request->getPost('content');

        /*
        $this->db->query("INSERT INTO posts (title, content) VALUES ('$title', '$content')");
        */
        $query = "INSERT INTO posts (title, content) VALUES (?, ?)";
        $params = [$title, $content];
        $this->db->query($query, $params);

        return redirect()->to('/posts');
    }


    /* 다음과 같이 create() 와 store() 를 하나의 메서드로 구현할 수 있음
    public function create()
    {
        // POST 데이터 처리
        if ($this->request->getMethod() === 'post') {
            // 쿼리 작성 및 실행
            $title = $this->request->getPost('title');
            $content = $this->request->getPost('content');
            $this->db->query("INSERT INTO posts (title, content) VALUES ('$title', '$content')");

            // 리다이렉트 등의 로직 추가
            return redirect()->to('/posts');
        }

        return view('posts/create');
    }
    */


    public function edit($id)
    {   
        /*
        $model = new PostModel();
        $data['post'] = $model->find($id);
        */

        /*
        echo "<pre>";
        print_r( $data['post']);
        echo "</pre>";
        Array
        (
            [id] => 3
            [title] => 제목2
            [content] => 내용2
            [created_at] => 2024-06-20 17:42:19
            [updated_at] => 2024-06-20 17:42:19
        )
        */

        /*
        $query = $this->db->query("SELECT * FROM posts WHERE id = $id");
        */
        /*
        $data['post'] = $query->getResultArray()[0];
        return view('posts/edit', $data);
        */
        /*
        $data['post'] = $query->getRow(); // stdClass Object
        return view('posts/edit2', $data);
        */

        $query = "SELECT * FROM posts WHERE id = ?";
        $params = [$id];
        $data['post'] = $this->db->query($query, $params)->getRow();
        return view('posts/edit2', $data);
    }

    public function update($id)
    {   
        /*
        $model = new PostModel();
        $data = [
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
        ];
        $model->update($id, $data);
        */

        $title = $this->request->getPost('title');
        $content = $this->request->getPost('content');
        /*
        $query = $this->db->query("UPDATE posts SET title = '$title', content = '$content' WHERE id = $id");
        */

        $query = "UPDATE posts SET title = ?, content = ? WHERE id = ?";
        $params = [$title, $content, $id];
        $this->db->query($query, $params);

        return redirect()->to('/posts');
    }

    public function delete($id)
    { 
        /*
        $model = new PostModel();
        $model->delete($id);
        */
        /*
        $query = $this->db->query("DELETE FROM posts WHERE id = $id");
        */
        $query = "DELETE FROM posts WHERE id = ?";
        $params = [$id];
        $this->db->query($query, $params);
        
        return redirect()->to('/posts');
    }

}

?>
```

## 18.
```
app/Views/posts/index.php

<!DOCTYPE html>
<html>
<head>
    <title>Posts</title>
</head>
<body>
    <h1>Posts</h1>
    <a href="/posts/create">Create Post</a>
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
```

## 19.
```
app/Views/posts/create.php

<!DOCTYPE html>
<html>
<head>
    <title>Create Post</title>
</head>
<body>
    <h1>Create Post</h1>
    <form action="/posts/store" method="post">
        <label for="title">Title</label>
        <input type="text" name="title" id="title">
        <label for="content">Content</label>
        <textarea name="content" id="content"></textarea>
        <button type="submit">Create</button>
    </form>
</body>
</html>
```

## 20.
```
app/Views/posts/edit.php

<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
</head>
<body>
    <h1>Edit Post</h1>
    <form action="/posts/update/<?= $post['id'] ?>" method="post">
        <label for="title">Title</label>
        <input type="text" name="title" id="title" value="<?= $post['title'] ?>">
        <label for="content">Content</label>
        <textarea name="content" id="content"><?= $post['content'] ?></textarea>
        <button type="submit">Update</button>
    </form>
</body>
</html>
```

## 21.
```
app/Views/posts/edit2.php

<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
</head>
<body>
    <h1>Edit Post</h1>
    <form action="/posts/update/<?= $post->id ?>" method="post">
        <label for="title">Title</label>
        <input type="text" name="title" id="title" value="<?= $post->title ?>">
        <label for="content">Content</label>
        <textarea name="content" id="content"><?= $post->content ?></textarea>
        <button type="submit">Update</button>
    </form>
</body>
</html>
```

## 22.
```
app/Config/Routes.php

<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');


$routes->get('/posts', 'PostController::index');
$routes->get('/posts/create', 'PostController::create');
$routes->post('/posts/store', 'PostController::store');

$routes->get('/posts/edit/(:num)', 'PostController::edit/$1');
$routes->post('/posts/update/(:num)', 'PostController::update/$1');
$routes->get('/posts/delete/(:num)', 'PostController::delete/$1');
```

## -------------------------------------------------------------------------------- 로그인구현
## 23.
```
CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## 24.
```
CREATE TABLE `ci_sessions` (
        `id` varchar(128) NOT NULL,
        `ip_address` varchar(45) NOT NULL,
        `timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
        `data` blob NOT NULL,
        PRIMARY KEY (`id`),
        KEY `ci_sessions_timestamp` (`timestamp`)
    );

   use CodeIgniter\Session\Handlers\DatabaseHandler;

    //public string $driver = FileHandler::class;
    public string $driver = DatabaseHandler::class;

    //public string $savePath = WRITEPATH . 'session';
    public string $savePath = 'ci_sessions';
```

## 25. Redis 설치
```
docker pull redis
docker run --name my-redis-container -p 6379:6379 -d redis

ubuntu@DESKTOP-QRPDHRI:~$ docker inspect -f "{{ .NetworkSettings.IPAddress }}" ee580bd558ab
172.17.0.4
```

## 26. session 설정파일 변경
```
public string $driver = RedisHandler::class; 
public string $cookieName = 'ci_session';
public int $expiration = 7200;
public $savePath = 'tcp://172.17.0.4:6379'; 
```

## 27. UserModel.php
```
<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users'; 

    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'password', 'email', 'created_at', 'updated_at'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function login($username, $password)
    {
        $user = $this->where('username', $username)->first();


        if ($user) {
          if (password_verify($password, substr( $user['password'], 0, 60 ))) {
            return $user;
          }
        }

        return null;
    }
}
```

## 28. AuthController.php
```
<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Models\UserModel;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth/login');
    }

    public function login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
 
        $user = $userModel->login($username, $password);

        if ($user) {
            $session = session();
            $session->set('user_id', $user['id']);
            $session->set('username', $user['username']);

            return redirect()->to('/posts');
        } else {
            return redirect()->back()->with('error', '로그인 실패: 사용자 이름 또는 비밀번호가 잘못되었습니다.');
        }

    }

    public function logout()
    {
        $session = session();
        $session->destroy();

        return redirect()->to('/');
    }
}
```

## 29. login.php
```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
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
```

## 30. PostController.php
```
<?php

namespace App\Controllers;

use App\Models\PostModel;
use CodeIgniter\Controller;

class PostController extends Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    { 


        $session = session();
        echo "Session ID: " . session_id() ."<br>";
        echo "session user_id: ". $session->get('user_id') ."<br>";
        echo "session username: ". $session->get('username') ."<br>";
  

        $query = $this->db->query('SELECT * FROM posts');

        $data['posts'] = $query->getResultArray();
        return view('posts/index', $data);
    }
}
```

## --- 화면출력결과
```
Session ID: 4cnf3pd7m17qrhpgjmgee9kmbqjvficq
session user_id: 3
session username: bbbb
```

## --- Redis에 저장된 세션 데이터를 확인
```
docker exec -it my-redis-container redis-cli

127.0.0.1:6379> keys *
1) "mykey"
2) "ci_session:ci_session:4cnf3pd7m17qrhpgjmgee9kmbqjvficq"
127.0.0.1:6379> hgetall ci_session:ci_session:4cnf3pd7m17qrhpgjmgee9kmbqjvficq
(error) WRONGTYPE Operation against a key holding the wrong kind of value
127.0.0.1:6379> TYPE ci_session:ci_session:4cnf3pd7m17qrhpgjmgee9kmbqjvficq
string
127.0.0.1:6379> GET ci_session:ci_session:4cnf3pd7m17qrhpgjmgee9kmbqjvficq
"__ci_last_regenerate|i:1719795671;_ci_previous_url|s:26:\"http://dev.myapp.com/posts\";user_id|s:1:\"3\";username|s:4:\"bbbb\";"
127.0.0.1:6379>
```



