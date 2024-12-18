<section class="user-management">
    <h2>사용자 관리</h2>

    <!-- 성공/실패 메시지 -->
    <?php if (session()->getFlashdata('message')): ?>
        <p class="success"><?= session()->getFlashdata('message') ?></p>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <p class="error"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <table>
        <thead>
        <tr>
            <th>아이디</th>
            <th>이메일</th>
            <th>가입일</th>
            <th>관리</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['created_at']) ?></td>
                    <td>
                        <a href="#" class="delete-btn" onclick="alert('삭제 기능 추가 예정'); return false;">삭제</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">등록된 사용자가 없습니다.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</section>
