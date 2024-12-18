<section class="dashboard">
    <h2>관리자 대시보드</h2>
    <p>관리자 대시보드에 오신 것을 환영합니다.</p>

    <div class="stats">
        <div class="stat-item">
            <h3>사용자 수</h3>
            <p><?= $totalUsers ?>명</p>
        </div>
        <div class="stat-item">
            <h3>상품 수</h3>
            <p><?= $totalProducts ?>개</p>
        </div>
    </div>

    <div class="links">
        <a href="/admin/users" class="admin-link">사용자 관리</a>
        <a href="/admin/products" class="admin-link">상품 관리</a>
    </div>
</section>
