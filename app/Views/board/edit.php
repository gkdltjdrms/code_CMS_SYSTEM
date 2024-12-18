<?= $this->include('templates/header') ?>

<link rel="stylesheet" href="/css/board.css">

<h2>글 수정</h2>

<form method="POST" action="/board/<?= esc($tableName) ?>/update/<?= esc($post['id']) ?>">
    <label for="subject">제목:</label>
    <input type="text" name="subject" value="<?= esc($post['subject']) ?>" required>

    <label for="content">내용:</label>
    <textarea name="content" rows="5" required><?= esc($post['content']) ?></textarea>

    <button type="submit">수정</button>
</form>

<?= $this->include('templates/footer') ?>
