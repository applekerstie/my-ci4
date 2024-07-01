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

        
        // 240625 kerstie
        /*
        $userModel = new \App\Models\UserModel();
        $userData = $userModel->verifyJWT();
        if (!$userData) {
            return redirect()->to('/login')->with('error', '로그인이 필요합니다.');
        }
        */

        $session = session();
        echo "Session ID: " . session_id() ."<br>";
        echo "session user_id: ". $session->get('user_id') ."<br>";
        echo "session username: ". $session->get('username') ."<br>";
  

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