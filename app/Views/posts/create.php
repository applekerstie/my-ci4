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