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
    <h1>Inactive Patients</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
        <li class="breadcrumb-item"><a href="patients.php">Patients</a></li>
        <li class="breadcrumb-item active">Inactive</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <h5 class="card-title">Archived / Inactive Patients</h5>

        <div class="table-responsive">
          <table class="table table-striped align-middle">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Patient Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Mobile</th>
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
                WHERE p.status = 'Inactive'
                ORDER BY p.patient_id DESC
              ");
              if ($result->num_rows > 0):
                $i = 1;
                while ($row = $result->fetch_assoc()):
              ?>
              <tr>
                <td><?= $i++; ?></td>
                <td><?= htmlspecialchars($row['name']); ?></td>
                <td><?= htmlspecialchars($row['age']); ?></td>
                <td><?= htmlspecialchars($row['gender']); ?></td>
                <td><?= htmlspecialchars($row['mobile']); ?></td>
                <td><?= htmlspecialchars($row['doctor_name']); ?></td>
                <td><?= htmlspecialchars(date("M d, Y", strtotime($row['created_at']))); ?></td>
                <td>
                  <a href="/HM1/controller/admin/reactivate_patient.php?id=<?= $row['patient_id']; ?>"
                     onclick="return confirmReactivate(event)"
                     class="btn btn-sm btn-outline-success" title="Reactivate">
                    <i class="bi bi-arrow-counterclockwise"></i>
                  </a>
                </td>
              </tr>
              <?php endwhile; else: ?>
              <tr><td colspan="8" class="text-center text-muted">No inactive patients found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </section>
</main>

<?php include('includes/footer.php'); ?>

<script>
function confirmReactivate(e) {
  e.preventDefault();
  const link = e.currentTarget.getAttribute('href');
  Swal.fire({
    title: 'Reactivate this patient?',
    text: "They will appear again in the active list.",
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#198754',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Yes, reactivate'
  }).then((result) => {
    if (result.isConfirmed) window.location.href = link;
  });
}
</script>
