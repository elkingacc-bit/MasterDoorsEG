<?php
include_once("connection.php");

// ===== DELETE =====
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($link, $_GET['delete']);
    $sql = "DELETE FROM cash_transaction WHERE cash_transaction_id = '$id'";
    mysqli_query($link, $sql);
    header("Location: admin_cash.php?msg=deleted");
    exit();
}

// ===== UPDATE =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $id          = mysqli_real_escape_string($link, $_POST['edit_id']);
    $date        = mysqli_real_escape_string($link, $_POST['transactionDate']);
    $income      = mysqli_real_escape_string($link, $_POST['income']);
    $withdrawal  = mysqli_real_escape_string($link, $_POST['withdrawal']);
    $desc        = mysqli_real_escape_string($link, $_POST['description']);
    $account     = mysqli_real_escape_string($link, $_POST['account']);
    $poNum       = mysqli_real_escape_string($link, $_POST['poNum']);
    $invNumber   = mysqli_real_escape_string($link, $_POST['invNumber']);
    $empCode     = mysqli_real_escape_string($link, $_POST['empCode']);
    $chequNumber = mysqli_real_escape_string($link, $_POST['chequNumber']);
    $valideDate  = mysqli_real_escape_string($link, $_POST['valideDate']);

    $sql = "UPDATE cash_transaction SET
        transactionDate = '$date',
        income          = '$income',
        withdrawal      = '$withdrawal',
        description     = '$desc',
        account         = '$account',
        poNum           = '$poNum',
        invNumber       = '$invNumber',
        empCode         = '$empCode',
        chequNumber     = '$chequNumber',
        valideDate      = '$valideDate'
        WHERE cash_transaction_id = '$id'";

    mysqli_query($link, $sql);
    header("Location: admin_cash.php?msg=updated");
    exit();
}

// ===== FETCH EDIT ROW =====
$edit_row = null;
if (isset($_GET['edit'])) {
    $id = mysqli_real_escape_string($link, $_GET['edit']);
    $res = mysqli_query($link, "SELECT * FROM cash_transaction WHERE cash_transaction_id = '$id'");
    $edit_row = mysqli_fetch_assoc($res);
}

// ===== FETCH ALL =====
$result = mysqli_query($link, "SELECT `cash_transaction_id`,`transactionDate`,`income`,`withdrawal`,`description`,`account`,`poNum`,`invNumber`,`empCode`,`chequNumber`,`valideDate` FROM `cash_transaction` ORDER BY cash_transaction_id DESC");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>إدارة المعاملات النقدية</title>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --bg:       #0f1117;
    --surface:  #1a1d27;
    --surface2: #22263a;
    --accent:   #4f8ef7;
    --accent2:  #38d9a9;
    --danger:   #f46e6e;
    --warning:  #f7b955;
    --text:     #e8eaf6;
    --muted:    #7c82a8;
    --border:   #2e3352;
    --radius:   10px;
  }

  body {
    font-family: 'Cairo', sans-serif;
    background: var(--bg);
    color: var(--text);
    min-height: 100vh;
    padding: 24px 16px;
  }

  h1 {
    font-size: 22px;
    font-weight: 700;
    color: var(--accent);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  h1 span { font-size: 26px; }

  /* Alert */
  .alert {
    padding: 12px 18px;
    border-radius: var(--radius);
    margin-bottom: 20px;
    font-size: 14px;
    font-weight: 600;
  }
  .alert.success { background: #1a3a2e; color: var(--accent2); border: 1px solid var(--accent2); }
  .alert.danger  { background: #3a1a1a; color: var(--danger);  border: 1px solid var(--danger); }

  /* Edit Form */
  .edit-card {
    background: var(--surface);
    border: 1px solid var(--accent);
    border-radius: var(--radius);
    padding: 24px;
    margin-bottom: 28px;
  }
  .edit-card h2 {
    font-size: 16px;
    font-weight: 700;
    color: var(--accent);
    margin-bottom: 18px;
  }
  .form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 14px;
  }
  .form-group label {
    display: block;
    font-size: 12px;
    color: var(--muted);
    margin-bottom: 5px;
  }
  .form-group input {
    width: 100%;
    background: var(--surface2);
    border: 1px solid var(--border);
    border-radius: 6px;
    color: var(--text);
    padding: 8px 12px;
    font-family: 'Cairo', sans-serif;
    font-size: 14px;
    transition: border .2s;
  }
  .form-group input:focus {
    outline: none;
    border-color: var(--accent);
  }
  .btn-row {
    display: flex;
    gap: 10px;
    margin-top: 18px;
  }
  .btn {
    padding: 9px 22px;
    border-radius: 6px;
    font-family: 'Cairo', sans-serif;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: opacity .2s, transform .1s;
  }
  .btn:active { transform: scale(0.97); }
  .btn-primary { background: var(--accent); color: #fff; }
  .btn-secondary { background: var(--surface2); color: var(--muted); border: 1px solid var(--border); }
  .btn:hover { opacity: .85; }

  /* Table */
  .table-wrap {
    overflow-x: auto;
    border-radius: var(--radius);
    border: 1px solid var(--border);
  }
  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    min-width: 900px;
  }
  thead {
    background: var(--surface2);
  }
  thead th {
    padding: 12px 14px;
    color: var(--muted);
    font-weight: 600;
    text-align: right;
    white-space: nowrap;
    border-bottom: 1px solid var(--border);
  }
  tbody tr {
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    transition: background .15s;
  }
  tbody tr:hover { background: var(--surface2); }
  tbody td {
    padding: 10px 14px;
    color: var(--text);
    white-space: nowrap;
  }
  .badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
  }
  .badge-income    { background: #1a3a2e; color: var(--accent2); }
  .badge-withdraw  { background: #3a2a1a; color: var(--warning); }

  .action-btns { display: flex; gap: 8px; }
  .btn-edit   { background: #1e3a5f; color: var(--accent);  border: 1px solid var(--accent);  padding: 5px 14px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; text-decoration: none; transition: opacity .2s; }
  .btn-delete { background: #3a1a1a; color: var(--danger);  border: 1px solid var(--danger);  padding: 5px 14px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; text-decoration: none; transition: opacity .2s; }
  .btn-edit:hover, .btn-delete:hover { opacity: .75; }

  .empty { text-align: center; padding: 40px; color: var(--muted); font-size: 15px; }
</style>
</head>
<body>

<h1><span>💰</span> إدارة المعاملات النقدية</h1>

<?php if (isset($_GET['msg'])): ?>
  <?php if ($_GET['msg'] === 'updated'): ?>
    <div class="alert success">✅ تم تعديل السجل بنجاح</div>
  <?php elseif ($_GET['msg'] === 'deleted'): ?>
    <div class="alert danger">🗑️ تم مسح السجل بنجاح</div>
  <?php endif; ?>
<?php endif; ?>

<?php if ($edit_row): ?>
<div class="edit-card">
  <h2>✏️ تعديل السجل رقم #<?= htmlspecialchars($edit_row['cash_transaction_id']) ?></h2>
  <form method="POST" action="admin_cash.php">
    <input type="hidden" name="edit_id" value="<?= htmlspecialchars($edit_row['cash_transaction_id']) ?>">
    <div class="form-grid">
      <div class="form-group">
        <label>تاريخ المعاملة</label>
        <input type="date" name="transactionDate" value="<?= htmlspecialchars($edit_row['transactionDate']) ?>">
      </div>
      <div class="form-group">
        <label>الوارد (Income)</label>
        <input type="number" step="0.01" name="income" value="<?= htmlspecialchars($edit_row['income']) ?>">
      </div>
      <div class="form-group">
        <label>الصادر (Withdrawal)</label>
        <input type="number" step="0.01" name="withdrawal" value="<?= htmlspecialchars($edit_row['withdrawal']) ?>">
      </div>
      <div class="form-group">
        <label>الوصف</label>
        <input type="text" name="description" value="<?= htmlspecialchars($edit_row['description']) ?>">
      </div>
      <div class="form-group">
        <label>الحساب</label>
        <input type="text" name="account" value="<?= htmlspecialchars($edit_row['account']) ?>">
      </div>
      <div class="form-group">
        <label>رقم PO</label>
        <input type="text" name="poNum" value="<?= htmlspecialchars($edit_row['poNum']) ?>">
      </div>
      <div class="form-group">
        <label>رقم الفاتورة</label>
        <input type="text" name="invNumber" value="<?= htmlspecialchars($edit_row['invNumber']) ?>">
      </div>
      <div class="form-group">
        <label>كود الموظف</label>
        <input type="text" name="empCode" value="<?= htmlspecialchars($edit_row['empCode']) ?>">
      </div>
      <div class="form-group">
        <label>رقم الشيك</label>
        <input type="text" name="chequNumber" value="<?= htmlspecialchars($edit_row['chequNumber']) ?>">
      </div>
      <div class="form-group">
        <label>تاريخ الصلاحية</label>
        <input type="date" name="valideDate" value="<?= htmlspecialchars($edit_row['valideDate']) ?>">
      </div>
    </div>
    <div class="btn-row">
      <button type="submit" class="btn btn-primary">💾 حفظ التعديلات</button>
      <a href="admin_cash.php" class="btn btn-secondary">إلغاء</a>
    </div>
  </form>
</div>
<?php endif; ?>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th>#ID</th>
        <th>التاريخ</th>
        <th>الوارد</th>
        <th>الصادر</th>
        <th>الوصف</th>
        <th>الحساب</th>
        <th>PO</th>
        <th>فاتورة</th>
        <th>كود موظف</th>
        <th>رقم شيك</th>
        <th>تاريخ صلاحية</th>
        <th>إجراءات</th>
      </tr>
    </thead>
    <tbody>
    <?php if (mysqli_num_rows($result) > 0): ?>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
      <tr>
        <td><?= htmlspecialchars($row['cash_transaction_id']) ?></td>
        <td><?= htmlspecialchars($row['transactionDate']) ?></td>
        <td>
          <?php if ($row['income'] > 0): ?>
            <span class="badge badge-income">+<?= number_format($row['income'], 2) ?></span>
          <?php else: ?>—<?php endif; ?>
        </td>
        <td>
          <?php if ($row['withdrawal'] > 0): ?>
            <span class="badge badge-withdraw">-<?= number_format($row['withdrawal'], 2) ?></span>
          <?php else: ?>—<?php endif; ?>
        </td>
        <td><?= htmlspecialchars($row['description']) ?></td>
        <td><?= htmlspecialchars($row['account']) ?></td>
        <td><?= htmlspecialchars($row['poNum']) ?></td>
        <td><?= htmlspecialchars($row['invNumber']) ?></td>
        <td><?= htmlspecialchars($row['empCode']) ?></td>
        <td><?= htmlspecialchars($row['chequNumber']) ?></td>
        <td><?= htmlspecialchars($row['valideDate']) ?></td>
        <td>
          <div class="action-btns">
            <a href="admin_cash.php?edit=<?= $row['cash_transaction_id'] ?>" class="btn-edit">✏️ تعديل</a>
            <a href="admin_cash.php?delete=<?= $row['cash_transaction_id'] ?>"
               class="btn-delete"
               onclick="return confirm('متأكد إنك عاوز تمسح السجل ده؟')">🗑️ مسح</a>
          </div>
        </td>
      </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="12" class="empty">لا يوجد بيانات حالياً</td></tr>
    <?php endif; ?>
    </tbody>
  </table>
</div>

</body>
</html>
