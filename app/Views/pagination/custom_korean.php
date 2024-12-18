<style>
    .pagination {
        display: flex;
        justify-content: center;
        gap: 10px;
        padding: 10px;
    }

    .pagination a {
        text-decoration: none;
        color: #333;
        background: #f1f1f1;
        padding: 8px 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        transition: background 0.3s ease;
    }

    .pagination a:hover {
        background: #ddd;
    }

    .pagination .active {
        font-weight: bold;
        color: #fff;
        background: #333;
        border-color: #333;
    }

    .pagination .disabled {
        color: #aaa;
        background: #f1f1f1;
        pointer-events: none;
    }
</style>
<?php
$pager->setSurroundCount(2);
?>
<div class="pagination">
    <!-- 맨 처음 -->
    <?php if ($pager->hasPreviousPage()): ?>
        <a href="<?= $pager->getFirst() ?>" class="page-link">처음</a>
        <a href="<?= $pager->getPreviousPage() ?>" class="page-link">이전</a>
    <?php else: ?>
        <span class="page-link disabled">처음</span>
        <span class="page-link disabled">이전</span>
    <?php endif; ?>

    <!-- 번호 -->
    <?php foreach ($pager->links() as $link): ?>
        <a href="<?= $link['uri'] ?>" class="page-link <?= $link['active'] ? 'active' : '' ?>">
            <?= $link['title'] ?>
        </a>
    <?php endforeach; ?>

    <!-- 다음 -->
    <?php if ($pager->hasNextPage()): ?>
        <a href="<?= $pager->getNextPage() ?>" class="page-link">다음</a>
        <a href="<?= $pager->getLast() ?>" class="page-link">끝</a>
    <?php else: ?>
        <span class="page-link disabled">다음</span>
        <span class="page-link disabled">끝</span>
    <?php endif; ?>
</div>
