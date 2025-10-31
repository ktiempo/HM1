<?php
include('../../db/config.php');

if (isset($_POST['update_doctor'])) {
  $doctor_id = intval($_POST['doctor_id']);
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);
  $specialization = trim($_POST['specialization']);
  $contact = trim($_POST['contact']);
  $address = trim($_POST['address']);
  $schedule = trim($_POST['schedule']);

  // 🧠 If password is empty, don't update it
  if (empty($password)) {
    $stmt = $conn->prepare("UPDATE doctors 
                            SET full_name=?, email=?, specialization=?, contact_number=?, clinic_address=?, clinic_schedule=? 
                            WHERE doctor_id=?");
    $stmt->bind_param("ssssssi", $name, $email, $specialization, $contact, $address, $schedule, $doctor_id);
  } else {
    $stmt = $conn->prepare("UPDATE doctors 
                            SET full_name=?, email=?, password=?, specialization=?, contact_number=?, clinic_address=?, clinic_schedule=? 
                            WHERE doctor_id=?");
    $stmt->bind_param("sssssssi", $name, $email, $password, $specialization, $contact, $address, $schedule, $doctor_id);
  }

  if ($stmt->execute()) {
    header("Location: /HM1/view/admin/manage_doctors.php?success=Doctor Updated Successfully");
  } else {
    header("Location: /HM1/view/admin/manage_doctors.php?error=Update Failed");
  }
  exit;
} else {
  header("Location: /HM1/view/admin/manage_doctors.php?error=Invalid Request");
  exit;
}
?>
