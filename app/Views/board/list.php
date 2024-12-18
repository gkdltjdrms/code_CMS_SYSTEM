<?= $this->include('templates/header') ?>

<link rel="stylesheet" href="/css/board.css">
<div class="board-container">
    <h2 class="board-title"><?= esc($boardName) ?> 게시판</h2>

    <!-- 검색 폼 -->
    <form method="GET" action="/board/<?= esc($tableName) ?>">
        <div class="row mb-3">
            <div class="col-md-3">
                <select name="searchType" class="form-select">
                    <option value="subject" <?= isset($searchType) && $searchType === 'subject' ? 'selected' : '' ?>>제목</option>
                    <option value="content" <?= isset($searchType) && $searchType === 'content' ? 'selected' : '' ?>>내용</option>
                </select>
            </div>
            <div class="col-md-7">
                <input type="text" name="search" class="form-control" placeholder="검색어를 입력하세요"
                       value="<?= esc($searchKeyword ?? '') ?>"> <!-- 검색어 유지 -->
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100" type="submit">검색</button>
            </div>
        </div>
    </form>

    <div class="d-flex justify-content-end mb-3">
        <?php if (session()->get('username') != ''): ?>
            <a href="/board/<?= esc($tableName) ?>/create" class="btn btn-primary">글 작성</a>
        <?php endif; ?>
    </div>

    <?php if (!empty($posts)): ?>
        <form method="POST" action="/board/<?= esc($tableName) ?>/delete-selected">
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <?php if (session()->get('role') == 'admin'): ?>
                        <th><input type="checkbox" id="selectAll"></th>
                    <?php endif; ?>
                    <th>제목</th>
                    <th>작성자 (id)</th>
                    <th>작성일</th>
                    <th>관리</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <?php if (session()->get('role') == 'admin'): ?>
                            <td>
                                <input type="checkbox" name="selected_ids[]" value="<?= esc($post['id']) ?>">
                            </td>
                        <?php endif; ?>
                        <td>
                            <a href="/board/<?= esc($tableName) ?>/view/<?= esc($post['id']) ?>">
                                <?= esc($post['subject'] ?? '제목 없음') ?>
                            </a>
                        </td>
                        <td><?= esc($post['user_id']) ?></td>
                        <td><?= esc($post['created_at'] ?? '날짜 없음') ?></td>
                        <td>
                            <?php
                            $login_id = session()->get('user_id');
                            $role = session()->get('role');
                            ?>
                            <?php if ($post['user_id'] == $login_id || $role == 'admin'): ?>
                                <a href="/board/<?= esc($tableName) ?>/delete/<?= esc($post['id']) ?>"
                                   onclick="return confirm('정말 삭제하시겠습니까?');"
                                   class="btn btn-sm btn-danger">삭제</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <!-- 관리자만 선택 삭제 버튼 표시 -->
            <?php if (session()->get('role') == 'admin'): ?>
                <button type="submit" class="btn btn-danger">선택 삭제</button>
            <?php endif; ?>
        </form>
    <?php else: ?>
        <div class="alert alert-info">게시글이 없습니다.</div>
    <?php endif; ?>

    <!-- 페이지네이션 -->
    <div class="d-flex justify-content-center mt-3">
        <?= $pagerLinks ?>
    </div>
</div>



<!-- 전체 선택 체크박스 기능 (관리자만 실행) -->
<?php if (session()->get('role') == 'admin'): ?>
    <script>
        document.getElementById('selectAll').addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    </script>
<?php endif; ?>

<?= $this->include('templates/footer') ?>
