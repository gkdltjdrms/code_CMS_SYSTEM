<?= $this->include('templates/header') ?>

<?php
// 로그인된 사용자 ID 가져오기
$login_id = session()->get('user_id'); // 로그인한 사용자 ID
$role = session()->get('role'); // 사용자 권한 (admin 또는 user)
?>

<!-- 에러 메시지 출력 -->
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= esc(session()->getFlashdata('error')) ?>
    </div>
<?php endif; ?>

<form method="POST" action="/board/<?= esc($tableName) ?>/update/<?= esc($post['id']) ?>" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="subject" class="form-label">제목</label>
        <input type="text" name="subject" class="form-control" value="<?= esc($post['subject']) ?>" required>
    </div>

    <div class="mb-3">
        <label for="content" class="form-label">내용</label>
        <textarea name="content" class="form-control" rows="5"><?= esc($post['content']) ?></textarea>
    </div>

    <!-- 기존 이미지 -->
    <?php if (!empty($post['image_path'])): ?>
        <div class="mb-3">
            <label>기존 이미지:</label><br>
            <img src="/image/<?= esc($post['table_name']) ?>/<?= esc($post['id']) ?>/images/<?= esc(basename($post['image_path'])) ?>" alt="이미지" style="max-width: 200px;">

            <!-- 권한 체크: 작성자 본인 또는 관리자만 삭제 버튼 표시 -->
            <?php if ($post['user_id'] == $login_id || $role == 'admin'): ?>
                <button type="submit" name="delete_image" value="1" class="btn btn-danger">이미지 삭제</button>
            <?php endif; ?>
        </div>

    <?php endif; ?>


    <!-- 권한 체크: 작성자 본인 또는 관리자만 삭제 버튼 표시 -->
    <?php if ($post['user_id'] == $login_id || $role == 'admin'): ?>
        <div class="mb-3">
            <label for="image" class="form-label">새 이미지 업로드</label>
            <input type="file" name="image" class="form-control">
        </div>
    <?php endif; ?>


 <!-- 기존 파일 -->
    <?php if (!empty($post['file_path'])): ?>
        <div class="mb-3">
            <label>기존 파일:</label><br>
            <a href="/download/<?= esc($post['table_name']) ?>/<?= esc($post['id']) ?>/files/<?= esc(basename($post['file_path'])) ?>" class="btn btn-info" download>첨부 파일 다운로드</a>

            <!-- 권한 체크: 작성자 본인 또는 관리자만 삭제 버튼 표시 -->
            <?php if ($post['user_id'] == $login_id || $role == 'admin'): ?>
                <button type="submit" name="delete_file" value="1" class="btn btn-danger">파일 삭제</button>
            <?php endif; ?>
        </div>
    <?php endif; ?>


    <!-- 권한 체크: 작성자 본인 또는 관리자만 삭제 버튼 표시 -->
    <?php if ($post['user_id'] == $login_id || $role == 'admin'): ?>
        <div class="mb-3">
            <label for="file" class="form-label">새 파일 업로드</label>
            <input type="file" name="file" class="form-control">
        </div>
    <?php endif; ?>


    <?php
    if ($post['user_id'] == $login_id || $role == 'admin'){?>
        <button type="submit" class="btn btn-primary">수정</button>
    <?php }?>
</form>

<a href="/board/<?= esc($tableName) ?>" class="btn btn-secondary mt-3">리스트로 돌아가기</a>


<?= $this->include('templates/footer') ?>
