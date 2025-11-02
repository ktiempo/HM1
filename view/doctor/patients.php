<?php
session_start();

// 🚧 TEMPORARY: disable redirect for UI preview
// if (!isset($_SESSION['doctor_id'])) {
//   header("Location: doctor_login.php");
//   exit;
// }

include('../../db/config.php');
include('includes/header.php');
include('includes/topbar.php');
include('includes/sidebar.php');

// Use mock doctor ID for now if not logged in
$doctor_id = $_SESSION['doctor_id'] ?? 1;

// Fetch all patients under this doctor
try {
  $stmt = $conn->prepare("SELECT * FROM patients WHERE doctor_id = ? AND status = 'active' ORDER BY created_at DESC");
  $stmt->bind_param("i", $doctor_id);
  $stmt->execute();
  $patients = $stmt->get_result();
} catch (Exception $e) {
  $patients = [];
}
?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>My Patients</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
        <li class="breadcrumb-item active">Patients</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
          <h5 class="card-title mb-0">Registered Patients</h5>
          <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPatientModal">
            <i class="bi bi-person-plus"></i> Add Patient
          </button>
        </div>

        <div class="table-responsive">
          <table class="table table-striped table-hover align-middle">
            <thead class="table-primary">
              <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Gender</th>
                <th>Age</th>
                <th>Mobile No.</th>
                <th>Date Registered</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($patients && $patients->num_rows > 0):
                $count = 1;
                while ($row = $patients->fetch_assoc()):
              ?>
              <tr>
                <td><?= $count++ ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars(ucfirst($row['gender'])) ?></td>
                <td><?= htmlspecialchars($row['age']) ?></td>
                <td><?= htmlspecialchars($row['mobile_number']) ?></td>
                <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                <td>
                  <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                    data-bs-target="#editPatientModal<?= $row['id'] ?>">
                    <i class="bi bi-pencil-square"></i>
                  </button>

                  <button class="btn btn-sm btn-outline-danger inactive-btn" data-id="<?= $row['id'] ?>">
                    <i class="bi bi-person-dash"></i>
                  </button>
                </td>
              </tr>

              <!-- Edit Patient Modal -->
              <div class="modal fade" id="editPatientModal<?= $row['id'] ?>" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                      <h5 class="modal-title">Edit Patient</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="../../controller/doctor/update_patient.php">
                      <div class="modal-body">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <div class="mb-3">
                          <label class="form-label">Full Name</label>
                          <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($row['name']) ?>" required>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Age</label>
                          <input type="number" name="age" class="form-control" value="<?= htmlspecialchars($row['age']) ?>" required>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Gender</label>
                          <select name="gender" class="form-select" required>
                            <option value="male" <?= $row['gender'] == 'male' ? 'selected' : '' ?>>Male</option>
                            <option value="female" <?= $row['gender'] == 'female' ? 'selected' : '' ?>>Female</option>
                          </select>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Mobile Number</label>
                          <input type="text" name="mobile_number" class="form-control" value="<?= htmlspecialchars($row['mobile_number']) ?>" required>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update" class="btn btn-primary">Save Changes</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <!-- End Edit Modal -->

              <?php endwhile; else: ?>
              <tr>
                <td colspan="7" class="text-center text-muted py-4">No patients registered yet.</td>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </section>

</main>

<?php include('includes/footer.php'); ?>

<!-- Add Patient Modal -->
<div class="modal fade" id="addPatientModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Add Patient</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="../../controller/doctor/add_patient.php">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Age</label>
            <input type="number" name="age" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Gender</label>
            <select name="gender" class="form-select" required>
              <option value="" disabled selected>Select gender</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Mobile Number</label>
            <input type="text" name="mobile_number" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" name="add" class="btn btn-success">Add Patient</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Add Patient Modal -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  // Mark patient as inactive (AJAX simulation for now)
  document.querySelectorAll('.inactive-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.id;
      Swal.fire({
        title: 'Mark Patient as Inactive?',
        text: "They will be hidden from your list but kept in the database.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, mark inactive'
      }).then((result) => {
        if (result.isConfirmed) {
          // TODO: send to backend (controller/doctor/inactive_patient.php)
          Swal.fire(
            'Marked!',
            'The patient is now inactive.',
            'success'
          )
        }
      })
    });
  });
</script>

<style>
  .table > :not(caption) > * > * {
    vertical-align: middle;
  }

  .table thead th {
    background-color: #0088a9 !important;
    color: white;
  }

  .btn-outline-primary:hover {
    color: #fff !important;
    background-color: #0088a9;
  }

  .btn-outline-danger:hover {
    color: #fff !important;
    background-color: #d9534f;
  }

  .modal-header.bg-success {
    background-color: #198754 !important;
  }

  .modal-header.bg-primary {
    background-color: #0088a9 !important;
  }
</style>
