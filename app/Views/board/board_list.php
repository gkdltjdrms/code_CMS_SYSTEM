<?= $this->include('templates/header') ?>

<div class="container mt-5">
    <h2>게시판 목록</h2>
    <?php if (!empty($boards)): ?>
        <ul class="list-group">
            <?php foreach ($boards as $board): ?>
                <li class="list-group-item">
                    <a href="/board/<?= esc($board['board_table']) ?>">
                        <?= esc($board['board_name']) ?> 게시판
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="text-muted">생성된 게시판이 없습니다.</p>
    <?php endif; ?>
</div>

<?= $this->include('templates/footer') ?>
