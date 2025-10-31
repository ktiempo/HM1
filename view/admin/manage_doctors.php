<?php
session_start();
include('../../db/config.php');

// ✅ Protect Admin Session
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
    <h1>Manage Doctors</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
        <li class="breadcrumb-item active">Doctors</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <h5 class="card-title">Doctor List</h5>

        <div class="table-responsive">
          <table class="table table-striped align-middle">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Specialization</th>
                <th>Contact</th>
                <th>Clinic Address</th>
                <th>Schedule</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $result = $conn->query("SELECT * FROM doctors ORDER BY doctor_id DESC");
              if ($result->num_rows > 0):
                $count = 1;
                while ($row = $result->fetch_assoc()):
              ?>
              <tr>
                <td><?= $count++; ?></td>
                <td><?= htmlspecialchars($row['full_name']); ?></td>
                <td><?= htmlspecialchars($row['email']); ?></td>
                <td><?= htmlspecialchars($row['specialization']); ?></td>
                <td><?= htmlspecialchars($row['contact_number']); ?></td>
                <td><?= htmlspecialchars($row['clinic_address']); ?></td>
                <td><?= htmlspecialchars($row['clinic_schedule']); ?></td>
                <td>
                  <!-- Edit Button -->
                  <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editDoctorModal<?= $row['doctor_id']; ?>">
                    <i class="bi bi-pencil-square"></i>
                  </button>

                  <!-- Delete Button -->
                  <a href="/HM1/controller/admin/delete_doctor.php?id=<?= $row['doctor_id']; ?>"
                     onclick="return confirmDelete(event)"
                     class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash"></i>
                  </a>
                </td>
              </tr>

              <!-- Edit Modal -->
              <div class="modal fade" id="editDoctorModal<?= $row['doctor_id']; ?>" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                  <div class="modal-content">
                    <form method="POST" action="/HM1/controller/admin/update_doctor.php">
                      <div class="modal-header" style="background:#0088a9; color:white;">
                        <h5 class="modal-title">Edit Doctor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body row g-3 p-4">
                        <input type="hidden" name="doctor_id" value="<?= $row['doctor_id']; ?>">

                        <div class="col-md-6">
                          <label class="form-label">Full Name</label>
                          <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($row['full_name']); ?>" required>
                        </div>

                        <div class="col-md-6">
                          <label class="form-label">Email</label>
                          <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($row['email']); ?>" required>
                        </div>

                        <div class="col-md-6">
                          <label class="form-label">Password (leave blank if unchanged)</label>
                          <input type="password" name="password" class="form-control" placeholder="Enter new password">
                        </div>

                        <div class="col-md-6">
                          <label class="form-label">Specialization</label>
                          <input type="text" name="specialization" class="form-control" value="<?= htmlspecialchars($row['specialization']); ?>" required>
                        </div>

                        <div class="col-md-6">
                          <label class="form-label">Contact Number</label>
                          <input type="text" name="contact" class="form-control" value="<?= htmlspecialchars($row['contact_number']); ?>">
                        </div>

                        <div class="col-md-6">
                          <label class="form-label">Clinic Address</label>
                          <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($row['clinic_address']); ?>">
                        </div>

                        <div class="col-md-12">
                          <label class="form-label">Clinic Schedule</label>
                          <textarea name="schedule" rows="2" class="form-control"><?= htmlspecialchars($row['clinic_schedule']); ?></textarea>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" name="update_doctor" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <!-- End Edit Modal -->

              <?php
                endwhile;
              else:
                echo "<tr><td colspan='8' class='text-center text-muted'>No doctors found.</td></tr>";
              endif;
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>

</main><!-- End #main -->

<?php include('includes/footer.php'); ?>

<!-- ✅ SweetAlert Notifications -->
<?php if (isset($_GET['success'])): ?>
<script>
Swal.fire({
  icon: 'success',
  title: 'Success!',
  text: '<?php echo htmlspecialchars($_GET['success']); ?>',
  confirmButtonColor: '#0088a9'
});
</script>
<?php elseif (isset($_GET['error'])): ?>
<script>
Swal.fire({
  icon: 'error',
  title: 'Error!',
  text: '<?php echo htmlspecialchars($_GET['error']); ?>',
  confirmButtonColor: '#0088a9'
});
</script>
<?php endif; ?>

<!-- ✅ Delete Confirmation -->
<script>
function confirmDelete(e) {
  e.preventDefault();
  const link = e.currentTarget.getAttribute('href');

  Swal.fire({
    title: 'Are you sure?',
    text: "This doctor will be permanently deleted.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = link;
    }
  });
}
</script>

<style>
  .card-title {
    color: #004b63;
    font-weight: 600;
  }
  .table th {
    color: #004b63;
  }
  .btn-outline-primary:hover {
    background-color: #0088a9;
    color: white;
  }
  .btn-outline-danger:hover {
    background-color: #d33;
    color: white;
  }
</style>
