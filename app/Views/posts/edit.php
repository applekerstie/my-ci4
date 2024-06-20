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