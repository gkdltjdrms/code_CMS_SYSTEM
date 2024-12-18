<?= $this->include('templates/header') ?>

<div class="container mt-5">
    <h2>게시글 작성</h2>

    <form method="POST" action="/board/<?= esc($tableName) ?>/store" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="subject" class="form-label">제목</label>
            <input type="text" name="subject" class="form-control" id="subject" required>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">내용</label>
            <textarea name="content" class="form-control" id="content" rows="5" required></textarea>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">이미지 업로드</label>
            <input type="file" name="image" class="form-control" id="image" accept="image/*">
        </div>

        <div class="mb-3">
            <label for="file" class="form-label">첨부 파일</label>
            <input type="file" name="file" class="form-control" id="file">
        </div>

        <button type="submit" class="btn btn-primary">등록</button>
    </form>
</div>

<?= $this->include('templates/footer') ?>
