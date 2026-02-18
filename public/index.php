<?php
require_once __DIR__ . '/../includes/Auth.php';
require_once __DIR__ . '/../config/config.php';

// التحقق من تسجيل الدخول
Auth::require_login();

// تضمين الهيدر
include __DIR__ . '/../templates/header.php';
?>

<!-- القائمة الجانبية -->
<?php include __DIR__ . '/../templates/navbar.php'; ?>

<!-- المحتوى الرئيسي -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">لوحة التحكم</h1>
    </div>

    <div class="row">
        <!-- إحصائيات سريعة (مثال) -->
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">التحويلات</div>
                <div class="card-body">
                    <h5 class="card-title">نشطة: 10</h5>
                    <p class="card-text">إجمالي: 25</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">المكالمات الحالية</div>
                <div class="card-body">
                    <h5 class="card-title">5</h5>
                    <p class="card-text">قنوات مشغولة</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">الطوابير</div>
                <div class="card-body">
                    <h5 class="card-title">3</h5>
                    <p class="card-text">طوابير نشطة</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger mb-3">
                <div class="card-header">حالة النظام</div>
                <div class="card-body">
                    <h5 class="card-title">ممتازة</h5>
                    <p class="card-text">Uptime: 10 days</p>
                </div>
            </div>
        </div>
    </div>

    <!-- مكان للمزيد من المعلومات أو الرسوم البيانية -->
    <div class="card">
        <div class="card-header">
            آخر الأحداث
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>الحدث</th>
                        <th>التفاصيل</th>
                        <th>الوقت</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>تسجيل دخول</td>
                        <td>المستخدم admin سجل الدخول</td>
                        <td>2023-10-27 10:00:00</td>
                    </tr>
                    <!-- يمكن جلب البيانات الحقيقية هنا -->
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../templates/footer.php'; ?>
