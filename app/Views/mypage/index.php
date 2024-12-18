<?= $this->include('templates/header') ?>

<div class="mypage-container">
    <h2>마이페이지</h2>
    <p>안녕하세요, <?= esc(session()->get('username')) ?>님!</p>

    <div class="actions">
        <a href="/mypage/posts" class="btn btn-primary">작성 글 보기</a>
    </div>
</div>

<?= $this->include('templates/footer') ?>
