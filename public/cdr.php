<?php
require_once __DIR__ . '/../includes/Auth.php';
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../config/config.php';

Auth::require_login();

$db = Database::getInstance();
$search = $_GET['search'] ?? '';

// استعلام CDR (Call Detail Records)
$sql = "SELECT * FROM cdr";
$params = [];

if (!empty($search)) {
    $sql .= " WHERE src LIKE :search OR dst LIKE :search";
    $params[':search'] = "%$search%";
}

$sql .= " ORDER BY calldate DESC LIMIT 100";

try {
    $calls = $db->query($sql, $params)->fetchAll();
} catch (Exception $e) {
    $calls = [];
}

include __DIR__ . '/../templates/header.php';
include __DIR__ . '/../templates/navbar.php';
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">سجل المكالمات (Call Recordings)</h1>
    </div>

    <!-- بحث -->
    <form class="row mb-3" method="GET">
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="بحث برقم المتصل أو المستقبل..." value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-outline-secondary" type="submit">بحث</button>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>التاريخ</th>
                    <th>المتصل (Source)</th>
                    <th>المستقبل (Destination)</th>
                    <th>المدة (Duration)</th>
                    <th>الحالة (Status)</th>
                    <th>التسجيل</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($calls)): ?>
                    <tr>
                        <td colspan="6" class="text-center">لا توجد سجلات مكالمات</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($calls as $call): ?>
                        <tr>
                            <td><?php echo $call['calldate']; ?></td>
                            <td><?php echo $call['src']; ?></td>
                            <td><?php echo $call['dst']; ?></td>
                            <td><?php echo $call['duration']; ?> ثانية</td>
                            <td><?php echo $call['disposition']; ?></td>
                            <td>
                                <?php if (!empty($call['recordingfile'])): ?>
                                    <!-- رابط افتراضي للتسجيل، يجب تعديله ليشير إلى المسار الحقيقي -->
                                    <a href="#" class="btn btn-sm btn-primary"><i class="fas fa-play"></i></a>
                                    <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-download"></i></a>
                                <?php else: ?>
                                    <span class="text-muted">لا يوجد</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include __DIR__ . '/../templates/footer.php'; ?>
