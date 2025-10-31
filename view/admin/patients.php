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

  <div class="pagetitle d-flex justify-content-between align-items-center">
    <div>
      <h1>Patients</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
          <li class="breadcrumb-item active">Patients</li>
        </ol>
      </nav>
    </div>
    <a href="patients_inactive.php" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-archive"></i> Inactive Patients
    </a>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <h5 class="card-title">Active Patients</h5>

        <div class="table-responsive">
          <table class="table table-striped align-middle">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Patient Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Mobile Number</th>
                <th>Doctor</th>
                <th>Date Registered</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $result = $conn->query("
                SELECT p.*, d.full_name AS doctor_name
                FROM patients p
                LEFT JOIN doctors d ON p.doctor_id = d.doctor_id
                WHERE p.status = 'Active'
                ORDER BY p.patient_id DESC
              ");
              if ($result->num_rows > 0):
                $count = 1;
                while ($row = $result->fetch_assoc()):
              ?>
              <tr>
                <td><?= $count++; ?></td>
                <td><?= htmlspecialchars($row['name']); ?></td>
                <td><?= htmlspecialchars($row['age']); ?></td>
                <td><?= htmlspecialchars($row['gender']); ?></td>
                <td><?= htmlspecialchars($row['mobile']); ?></td>
                <td><?= htmlspecialchars($row['doctor_name']); ?></td>
                <td><?= htmlspecialchars(date("M d, Y", strtotime($row['created_at']))); ?></td>
                <td>
                  <a href="/HM1/controller/admin/deactivate_patient.php?id=<?= $row['patient_id']; ?>"
                     onclick="return confirmDeactivate(event)"
                     class="btn btn-sm btn-outline-warning" title="Mark as Inactive">
                    <i class="bi bi-person-dash"></i>
                  </a>
                </td>
              </tr>
              <?php endwhile; else: ?>
              <tr><td colspan="8" class="text-center text-muted">No active patients found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include('includes/footer.php'); ?>

<!-- ✅ SweetAlert Notifications -->
<?php if (isset($_GET['success'])): ?>
<script>
Swal.fire({ icon: 'success', title: 'Success!', text: '<?= htmlspecialchars($_GET['success']); ?>', confirmButtonColor: '#0088a9' });
</script>
<?php elseif (isset($_GET['error'])): ?>
<script>
Swal.fire({ icon: 'error', title: 'Error!', text: '<?= htmlspecialchars($_GET['error']); ?>', confirmButtonColor: '#0088a9' });
</script>
<?php endif; ?>

<!-- ✅ Confirm Deactivate -->
<script>
function confirmDeactivate(e) {
  e.preventDefault();
  const link = e.currentTarget.getAttribute('href');
  Swal.fire({
    title: 'Mark patient as inactive?',
    text: "They will be hidden from the active list but kept in records.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#f0ad4e',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Yes, deactivate'
  }).then((result) => {
    if (result.isConfirmed) window.location.href = link;
  });
}
</script>

<style>
  .card-title { color: #004b63; font-weight: 600; }
  .btn-outline-warning:hover { background-color: #f0ad4e; color: white; }
</style>
