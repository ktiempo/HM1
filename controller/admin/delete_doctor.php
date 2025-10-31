<?php
include('../../db/config.php');

if (isset($_GET['id'])) {
  $doctor_id = intval($_GET['id']);

  $stmt = $conn->prepare("DELETE FROM doctors WHERE doctor_id=?");
  $stmt->bind_param("i", $doctor_id);

  if ($stmt->execute()) {
    header("Location: /HM1/view/admin/manage_doctors.php?success=Doctor Deleted Successfully");
  } else {
    header("Location: /HM1/view/admin/manage_doctors.php?error=Failed to Delete Doctor");
  }
  exit;
} else {
  header("Location: /HM1/view/admin/manage_doctors.php?error=Invalid Request");
  exit;
}
?>
