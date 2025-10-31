<?php
include('../../db/config.php');

if (isset($_POST['add_doctor'])) {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);
  $specialization = trim($_POST['specialization']);
  $contact = trim($_POST['contact']);
  $address = trim($_POST['address']);
  $schedule = trim($_POST['schedule']);

  $stmt = $conn->prepare("INSERT INTO doctors (full_name, email, password, specialization, contact_number, clinic_address, clinic_schedule) VALUES (?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sssssss", $name, $email, $password, $specialization, $contact, $address, $schedule);

  if ($stmt->execute()) {
    header("Location: /HM1/view/admin/manage_doctors.php?success=Doctor Added Successfully");
  } else {
    header("Location: /HM1/view/admin/add_doctor.php?error=Add Doctor Failed");
  }
  exit;
}
?>
