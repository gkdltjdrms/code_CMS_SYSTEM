<?= $this->include('templates/header') ?>

<div class="mypage-posts-container">
    <a href="/mypage">마이 페이지로</a>
    <h2>내가 작성한 글</h2>

    <!-- 검색 폼 -->
    <form method="GET" action="/mypage/posts">
        <div class="row mb-3">
            <div class="col-md-3">
                <select name="searchType" class="form-select">
                    <option value="subject" <?= isset($searchType) && $searchType === 'subject' ? 'selected' : '' ?>>제목</option>
                    <option value="content" <?= isset($searchType) && $searchType === 'content' ? 'selected' : '' ?>>내용</option>
                </select>
            </div>
            <div class="col-md-7">
                <input type="text" name="search" class="form-control" placeholder="검색어를 입력하세요"
                       value="<?= esc($searchKeyword ?? '') ?>">
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100" type="submit">검색</button>
            </div>
        </div>
    </form>

    <?php if (!empty($posts)): ?>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>번호</th>
                <th>제목</th>
                <th>게시판</th>
                <th>작성일</th>
                <th>관리</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?= esc($post['id']) ?></td>
                    <td>
                        <a href="/board/<?= esc($post['table_name']) ?>/view/<?= esc($post['id']) ?>">
                            <?= esc($post['subject']) ?>
                        </a>
                    </td>
                    <td><?= esc($post['board_name']) ?></td>
                    <td><?= esc($post['created_at']) ?></td>
                    <td>
                        <!-- 삭제 버튼 -->
                        <form action="/mypage/deletePost" method="post" style="display:inline;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="table_name" value="<?= esc($post['table_name']) ?>">
                            <input type="hidden" name="id" value="<?= esc($post['id']) ?>">
                            <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('정말 삭제하시겠습니까?');">
                                삭제
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <!-- 페이지네이션 -->
        <div class="d-flex justify-content-center mt-3">
            <?= $pagerLinks ?>
        </div>
    <?php else: ?>
        <p>작성한 글이 없습니다.</p>
    <?php endif; ?>
</div>

<?= $this->include('templates/footer') ?>
