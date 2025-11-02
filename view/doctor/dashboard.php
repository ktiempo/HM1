<?php
session_start();

// 🚧 TEMPORARY: Disable redirect so you can preview UI without login
// if (!isset($_SESSION['doctor_id'])) {
//   header("Location: doctor_login.php");
//   exit;
// }

include('../../db/config.php');
include('includes/header.php');
include('includes/topbar.php');
include('includes/sidebar.php');

// 💡 Mock doctor ID for testing if not logged in
$doctor_id = $_SESSION['doctor_id'] ?? 1;
?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Doctor Dashboard</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <!-- Dashboard Section -->
  <section class="section dashboard">
    <div class="container-fluid">
      <div class="row g-4">

        <!-- Patients Card -->
        <div class="col-12 col-sm-6 col-lg-4">
          <a href="patients.php" class="text-decoration-none">
            <div class="card info-card h-100 shadow-sm border-0">
              <div class="card-body">
                <h5 class="card-title">Patients <span>| My Patients</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-people" style="color:#0088a9;"></i>
                  </div>
                  <div class="ps-3">
                    <h6>
                      <?php
                        // Safe DB query (or sample data)
                        try {
                          $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM patients WHERE doctor_id = ? AND status = 'active'");
                          $stmt->bind_param("i", $doctor_id);
                          $stmt->execute();
                          $result = $stmt->get_result();
                          $row = $result->fetch_assoc();
                          echo $row ? $row['total'] : '0';
                        } catch (Exception $e) {
                          echo '3'; // mock value if DB not connected
                        }
                      ?>
                    </h6>
                    <span class="text-muted small pt-2 ps-1">Active Patients</span>
                  </div>
                </div>
              </div>
            </div>
          </a>
        </div><!-- End Patients Card -->

        <!-- Appointments Card -->
        <div class="col-12 col-sm-6 col-lg-4">
          <a href="appointments.php" class="text-decoration-none">
            <div class="card info-card h-100 shadow-sm border-0">
              <div class="card-body">
                <h5 class="card-title">Appointments <span>| Upcoming</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-calendar-check" style="color:#0088a9;"></i>
                  </div>
                  <div class="ps-3">
                    <h6>
                      <?php
                        try {
                          $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM appointments WHERE doctor_id = ? AND status = 'pending'");
                          $stmt->bind_param("i", $doctor_id);
                          $stmt->execute();
                          $result = $stmt->get_result();
                          $row = $result->fetch_assoc();
                          echo $row ? $row['total'] : '0';
                        } catch (Exception $e) {
                          echo '5'; // mock value if DB not connected
                        }
                      ?>
                    </h6>
                    <span class="text-muted small pt-2 ps-1">Pending Appointments</span>
                  </div>
                </div>
              </div>
            </div>
          </a>
        </div><!-- End Appointments Card -->

        <!-- Unavailable Dates Card -->
        <div class="col-12 col-sm-6 col-lg-4">
          <a href="schedule.php" class="text-decoration-none">
            <div class="card info-card h-100 shadow-sm border-0">
              <div class="card-body">
                <h5 class="card-title">Schedule <span>| Unavailable Days</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-calendar-x" style="color:#0088a9;"></i>
                  </div>
                  <div class="ps-3">
                    <h6>
                      <?php
                        try {
                          $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM doctor_unavailable WHERE doctor_id = ?");
                          $stmt->bind_param("i", $doctor_id);
                          $stmt->execute();
                          $result = $stmt->get_result();
                          $row = $result->fetch_assoc();
                          echo $row ? $row['total'] : '0';
                        } catch (Exception $e) {
                          echo '2'; // mock value if DB not connected
                        }
                      ?>
                    </h6>
                    <span class="text-muted small pt-2 ps-1">Marked as Unavailable</span>
                  </div>
                </div>
              </div>
            </div>
          </a>
        </div><!-- End Unavailable Card -->

      </div><!-- End Row -->
    </div><!-- End Container -->
  </section>

</main><!-- End #main -->

<?php include('includes/footer.php'); ?>

<!-- ✅ Custom CSS for cards -->
<style>
  body {
    padding-top: 70px;
  }

  .info-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    background: #fff;
    border-radius: 12px;
    cursor: pointer;
  }

  .info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    background-color: #f7fcfd;
  }

  a.text-decoration-none {
    text-decoration: none !important;
    color: inherit !important;
  }

  .card-title {
    font-weight: 600;
    color: #004b63;
    font-size: 1.1rem;
  }

  .card-icon {
    background: #e6f4f7;
    width: 55px;
    height: 55px;
    font-size: 1.6rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .info-card:hover .card-icon {
    background: #0088a9;
    color: #fff;
    transform: scale(1.05);
  }

  @media (max-width: 767px) {
    .pagetitle h1 {
      font-size: 1.25rem;
    }

    .info-card {
      text-align: center;
    }

    .card-icon {
      margin: 0 auto 10px;
    }

    .d-flex.align-items-center {
      flex-direction: column;
    }
  }
</style>
