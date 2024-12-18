<?= $this->include('templates/admin_header') ?>

<!-- 게시판 관리 전용 CSS -->
<link rel="stylesheet" href="/css/admin/boards/style.css">

<div class="board-list-container">
    <h2>게시판 관리</h2>
    <a href="/admin/boards/create" class="button">새 게시판 생성</a>

    <div class="board-list">
        <ul>
            <?php foreach ($boards as $board): ?>
                <li>
                    <a href="/board/<?= esc($board['board_table']) ?>">
                        <?= esc($board['board_name']) ?> (테이블명: <?= esc($board['board_table']) ?>)
                    </a>
                    <a href="/admin/boards/delete/<?= esc($board['id']) ?>"
                       onclick="return confirm('정말 삭제하시겠습니까?');"
                       class="delete">삭제</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<?= $this->include('templates/admin_footer') ?>
