<?php
// التحقق من الصفحة الحالية لتحديد العنصر النشط
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
    <div class="position-sticky pt-3">
        <h4 class="text-center mb-4"><?php echo Config::APP_NAME; ?></h4>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">
                    <i class="fas fa-home me-2"></i> لوحة التحكم
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'extensions.php') ? 'active' : ''; ?>" href="extensions.php">
                    <i class="fas fa-phone me-2"></i> التحويلات (Extensions)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'queues.php') ? 'active' : ''; ?>" href="queues.php">
                    <i class="fas fa-users me-2"></i> الطوابير (Queues)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'ivr.php') ? 'active' : ''; ?>" href="ivr.php">
                    <i class="fas fa-sitemap me-2"></i> الرد الآلي (IVR)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'recordings.php') ? 'active' : ''; ?>" href="recordings.php">
                    <i class="fas fa-microphone me-2"></i> تسجيلات النظام
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'cdr.php') ? 'active' : ''; ?>" href="cdr.php">
                    <i class="fas fa-list-alt me-2"></i> سجل المكالمات
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'feature_codes.php') ? 'active' : ''; ?>" href="feature_codes.php">
                    <i class="fas fa-code me-2"></i> أكواد الميزات
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'settings.php') ? 'active' : ''; ?>" href="settings.php">
                    <i class="fas fa-cog me-2"></i> الإعدادات
                </a>
            </li>
            <li class="nav-item mt-5">
                <a class="nav-link text-danger" href="logout.php">
                    <i class="fas fa-sign-out-alt me-2"></i> تسجيل الخروج
                </a>
            </li>
        </ul>
    </div>
</nav>
