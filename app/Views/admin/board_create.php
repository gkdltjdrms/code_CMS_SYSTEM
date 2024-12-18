<?= $this->include('templates/admin_header') ?>

    <!-- 에러 메시지 출력 -->
<?php if (session()->getFlashdata('error')): ?>
    <div class="error-message">
        <?= esc(session()->getFlashdata('error')) ?>
    </div>
<?php endif; ?>

    <h2>새 게시판 생성</h2>

    <form method="POST" action="/admin/boards/store">
        <label for="board_name">게시판 이름:</label>
        <input type="text" id="board_name" name="board_name" required placeholder="예: 공지사항">

        <label for="board_table">테이블명:</label>
        <input type="text" id="board_table" name="board_table" required placeholder="예: notice">

        <button type="submit" class="button">생성</button>
    </form>


<?= $this->include('templates/admin_footer') ?>