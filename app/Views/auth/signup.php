<section class="signup">
    <div class="container">
        <h2>회원가입</h2>

        <?php if (session()->getFlashdata('error')): ?>
            <p style="color: red;"><?= session()->getFlashdata('error') ?></p>
        <?php endif; ?>

        <form action="/auth/signup" method="post">
            <div class="form-group">
                <label for="user_id">아이디</label>
                <input type="text" id="user_id" name="user_id" value="<?= old('user_id') ?>" required>
            </div>
            <div class="form-group">
                <label for="username">이름</label>
                <input type="text" id="username" name="username" value="<?= old('username') ?>" required>
            </div>
            <div class="form-group">
                <label for="email">이메일</label>
                <input type="email" id="email" name="email" value="<?= old('email') ?>" required>
            </div>
            <div class="form-group">
                <label for="password">비밀번호</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">비밀번호 확인</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit">회원가입</button>
        </form>
    </div>
</section>
