<?php
session_start();
include('../../db/config.php');

if (!isset($_SESSION['admin_id'])) {
  header("Location: ../admin_login.php");
  exit;
}

include('includes/header.php');
include('includes/topbar.php');
include('includes/sidebar.php');
?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Appointments</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
        <li class="breadcrumb-item active">Appointments</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <h5 class="card-title">All Appointments</h5>

        <div class="table-responsive">
          <table class="table table-striped align-middle">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $result = $conn->query("
                SELECT a.*, p.full_name AS patient_name, d.full_name AS doctor_name
                FROM appointments a
                LEFT JOIN patients p ON a.patient_id = p.patient_id
                LEFT JOIN doctors d ON a.doctor_id = d.doctor_id
                ORDER BY a.appointment_id DESC
              ");
              if ($result->num_rows > 0):
                $count = 1;
                while ($row = $result->fetch_assoc()):
              ?>
              <tr>
                <td><?= $count++; ?></td>
                <td><?= htmlspecialchars($row['patient_name']); ?></td>
                <td><?= htmlspecialchars($row['doctor_name']); ?></td>
                <td><?= htmlspecialchars(date("M d, Y", strtotime($row['appointment_date']))); ?></td>
                <td><?= htmlspecialchars($row['appointment_time']); ?></td>
                <td>
                  <span class="badge bg-<?php
                    echo match($row['status']) {
                      'Pending' => 'warning',
                      'Approved' => 'success',
                      'Completed' => 'info',
                      default => 'secondary'
                    };
                  ?>">
                    <?= htmlspecialchars($row['status']); ?>
                  </span>
                </td>
                <td>
                  <form method="POST" action="/HM1/controller/admin/update_appointment_status.php" class="d-inline">
                    <input type="hidden" name="appointment_id" value="<?= $row['appointment_id']; ?>">
                    <select name="status" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()">
                      <option value="Pending" <?= $row['status']=='Pending'?'selected':''; ?>>Pending</option>
                      <option value="Approved" <?= $row['status']=='Approved'?'selected':''; ?>>Approved</option>
                      <option value="Completed" <?= $row['status']=='Completed'?'selected':''; ?>>Completed</option>
                    </select>
                  </form>

                  <a href="/HM1/controller/admin/delete_appointment.php?id=<?= $row['appointment_id']; ?>"
                     onclick="return confirmDelete(event)"
                     class="btn btn-sm btn-outline-danger" title="Delete">
                    <i class="bi bi-trash"></i>
                  </a>
                </td>
              </tr>
              <?php endwhile; else: ?>
              <tr><td colspan="7" class="text-center text-muted">No appointments found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </section>
</main>

<?php include('includes/footer.php'); ?>

<!-- SweetAlert -->
<?php if (isset($_GET['success'])): ?>
<script>Swal.fire({icon:'success',title:'Success!',text:'<?=htmlspecialchars($_GET['success']);?>',confirmButtonColor:'#0088a9'});</script>
<?php elseif (isset($_GET['error'])): ?>
<script>Swal.fire({icon:'error',title:'Error!',text:'<?=htmlspecialchars($_GET['error']);?>',confirmButtonColor:'#0088a9'});</script>
<?php endif; ?>

<script>
function confirmDelete(e){
  e.preventDefault();
  const link = e.currentTarget.getAttribute('href');
  Swal.fire({
    title:'Are you sure?',
    text:"This appointment will be permanently deleted.",
    icon:'warning',
    showCancelButton:true,
    confirmButtonColor:'#d33',
    cancelButtonColor:'#3085d6',
    confirmButtonText:'Yes, delete it!'
  }).then((result)=>{
    if(result.isConfirmed) window.location.href=link;
  });
}
</script>
