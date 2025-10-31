<?php
session_start();
include('../../db/config.php');

// ✅ Secure session check
if (!isset($_SESSION['admin_id'])) {
  header("Location: ../admin_login.php");
  exit;
}

include('includes/header.php');
include('includes/topbar.php');
include('includes/sidebar.php');

// ✅ Handle delete request
if (isset($_GET['delete'])) {
  $doctor_id = intval($_GET['delete']);
  $conn->query("DELETE FROM doctors WHERE doctor_id = $doctor_id");
  $deleted = true;
}
?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Manage Doctors</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
        <li class="breadcrumb-item">Doctors</li>
        <li class="breadcrumb-item active">Manage Doctors</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <h5 class="card-title">Registered Doctors</h5>

        <div class="table-responsive">
          <table class="table table-striped table-hover align-middle">
            <thead class="table-primary text-center">
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
                <td class="text-center"><?php echo $count++; ?></td>
                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['specialization']); ?></td>
                <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                <td><?php echo htmlspecialchars($row['clinic_address']); ?></td>
                <td><?php echo htmlspecialchars($row['clinic_schedule']); ?></td>
                <td class="text-center">
                  <!-- Edit button -->
                  <a href="edit_doctor.php?id=<?php echo $row['doctor_id']; ?>" 
                     class="btn btn-sm btn-outline-primary me-1">
                    <i class="bi bi-pencil-square"></i>
                  </a>
                  
                  <!-- Delete button -->
                  <a href="?delete=<?php echo $row['doctor_id']; ?>" 
                     onclick="return confirmDelete(event)"
                     class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash"></i>
                  </a>
                </td>
              </tr>
              <?php
                endwhile;
              else:
              ?>
              <tr>
                <td colspan="8" class="text-center text-muted">No doctors found.</td>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>

</main><!-- End #main -->

<?php include('includes/footer.php'); ?>

<!-- SweetAlert feedback -->
<?php if (isset($deleted)): ?>
<script>
Swal.fire({
  icon: 'success',
  title: 'Deleted!',
  text: 'Doctor has been removed successfully.',
  confirmButtonColor: '#0088a9'
});
</script>
<?php endif; ?>

<script>
function confirmDelete(e) {
  e.preventDefault();
  const url = e.currentTarget.getAttribute('href');
  Swal.fire({
    title: "Are you sure?",
    text: "This doctor record will be permanently deleted.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#0088a9",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!"
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = url;
    }
  });
}
</script>

<style>
  .card {
    margin-top: 20px;
    border-radius: 10px;
  }
  .table {
    background-color: #fff;
    border-radius: 10px;
    overflow: hidden;
  }
  .table thead {
    background-color: #0088a9;
    color: #fff;
  }
  .btn-outline-primary {
    color: #0088a9;
    border-color: #0088a9;
  }
  .btn-outline-primary:hover {
    background-color: #0088a9;
    color: #fff;
  }
  .btn-outline-danger:hover {
    background-color: #d33;
    color: #fff;
  }
</style>
