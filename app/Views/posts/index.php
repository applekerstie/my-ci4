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