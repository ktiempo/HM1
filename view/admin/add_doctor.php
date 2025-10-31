<?php
session_start();
include('../../db/config.php');

// ✅ Session protection
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
    <h1>Add Doctor</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
        <li class="breadcrumb-item">Doctors</li>
        <li class="breadcrumb-item active">Add Doctor</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <h5 class="card-title">Doctor Information</h5>

        <!-- ✅ Action now points to controller/admin/add_doctor.php -->
        <form method="POST" action="/HM1/controller/admin/add_doctor.php" class="row g-3">

          <div class="col-md-6">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" placeholder="Dr. Juan Dela Cruz" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" placeholder="doctor@email.com" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter password" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Specialization</label>
            <input type="text" name="specialization" class="form-control" placeholder="e.g., Cardiology" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Contact Number / Landline</label>
            <input type="text" name="contact" class="form-control" placeholder="e.g., 09123456789">
          </div>

          <div class="col-md-6">
            <label class="form-label">Clinic Address</label>
            <input type="text" name="address" class="form-control" placeholder="e.g., HealthMate Clinic, Cebu City">
          </div>

          <div class="col-md-12">
            <label class="form-label">Clinic Schedule</label>
            <textarea name="schedule" rows="2" class="form-control" placeholder="e.g., Mon-Fri 9AM–5PM"></textarea>
          </div>

          <div class="text-center mt-4">
            <button type="submit" name="add_doctor" class="btn btn-primary px-4" style="background:#0088a9; border:none;">Add Doctor</button>
            <a href="manage_doctors.php" class="btn btn-secondary px-4">Cancel</a>
          </div>

        </form><!-- End Add Doctor Form -->
      </div>
    </div>
  </section>

</main><!-- End #main -->

<?php include('includes/footer.php'); ?>

<!-- SweetAlert for feedback -->
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

<style>
  .card {
    margin-top: 20px;
    border-radius: 12px;
  }
  .card-title {
    color: #004b63;
    font-weight: 600;
  }
  label.form-label {
    font-weight: 500;
    color: #004b63;
  }
  .form-control {
    border-radius: 8px;
  }
  .btn-primary:hover {
    background-color: #00748a;
  }
</style>
