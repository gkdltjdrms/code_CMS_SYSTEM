<?= $this->include('templates/admin_header') ?>

<div class="container mt-5">
    <h2>기본 환경 설정</h2>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
    <?php endif; ?>

    <form method="POST" action="/admin/settings/update">
        <div class="mb-3">
            <label for="solution_name" class="form-label">솔루션 이름</label>
            <input type="text" class="form-control" id="solution_name" name="solution_name"
                   value="<?= esc($solution_name) ?>" >
        </div>

        <div class="mb-3">
            <label for="business_number" class="form-label">사업자 번호</label>
            <input type="text" class="form-control" id="business_number" name="business_number"
                   value="<?= esc($business_number) ?>" >
        </div>

        <div class="mb-3">
            <label for="company_info" class="form-label">회사 소개</label>
            <textarea class="form-control" id="company_info" name="company_info" rows="5"><?= esc($company_info) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">저장</button>
    </form>
</div>

<?= $this->include('templates/admin_footer') ?>
