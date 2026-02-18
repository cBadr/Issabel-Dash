<?php
require_once __DIR__ . '/../includes/Auth.php';
require_once __DIR__ . '/../includes/QueueManager.php';
require_once __DIR__ . '/../config/config.php';

Auth::require_login();
$qManager = new QueueManager();
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $qManager->add($_POST);
        $message = "تم إضافة الطابور بنجاح";
    } catch (Exception $e) {
        $error = "خطأ: " . $e->getMessage();
    }
}

$queues = $qManager->getAll();

include __DIR__ . '/../templates/header.php';
include __DIR__ . '/../templates/navbar.php';
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">إدارة الطوابير (Queues)</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQueueModal">
            <i class="fas fa-plus"></i> إضافة طابور جديد
        </button>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>رقم الطابور</th>
                    <th>الاسم</th>
                    <th>الاستراتيجية</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($queues as $q): ?>
                <tr>
                    <td><?php echo $q['extension']; ?></td>
                    <td><?php echo $q['descr']; ?></td>
                    <td><?php echo $q['strategy']; ?></td>
                    <td>
                        <button class="btn btn-sm btn-info"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<!-- Modal -->
<div class="modal fade" id="addQueueModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة طابور جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>رقم الطابور</label>
                            <input type="number" name="extension" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>اسم الطابور</label>
                            <input type="text" name="descr" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label>استراتيجية الرنين (Ring Strategy)</label>
                        <select name="strategy" class="form-select">
                            <option value="ringall">Ring All</option>
                            <option value="roundrobin">Round Robin</option>
                            <option value="leastrecent">Least Recent</option>
                            <option value="fewestcalls">Fewest Calls</option>
                            <option value="random">Random</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>تخطي العملاء المشغولين (Skip Busy Agents)</label>
                            <select name="skip_busy_agents" class="form-select">
                                <option value="yes">نعم</option>
                                <option value="no">لا</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>نوع التسجيل (Recording Mode)</label>
                            <select name="recording_mode" class="form-select">
                                <option value="adloc">Adhoc</option>
                                <option value="always">Always</option>
                                <option value="never">Never</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>أعضاء الطابور (Static Agents) - مفصولة بفواصل</label>
                        <input type="text" name="members" class="form-control" placeholder="100,101,102">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
