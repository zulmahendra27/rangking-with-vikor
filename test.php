<?php
include_once './control/helper.php';
include_once './control/connection.php';

// $data_user = [
//   'username' => 'kepsek',
//   'password' => password_hash('kepsek', PASSWORD_DEFAULT),
//   'level' => 'kepsek'
// ];

// insert($c, $data_user, 'users');

// $data_guru = [
//   'id_user' => mysqli_insert_id($c),
//   'nip' => '6217391823123',
//   'nama_guru' => 'Kepala Sekolah',
//   'pendidikan' => 'S2',
//   'email' => 'kepsek@gmail.com',
//   'nohp' => '0812356271321'
// ];

// insert($c, $data_guru, 'guru');

$data['key'] = ['id_siswa', 'id_guru', 'c31', 'c32', 'c51', 'c52'];
$data['values'] = [];

// $c->query("TRUNCATE TABLE nilaidarisiswa");

function generateRandomNumber()
{
  $randNum = rand(1, 100); // Menghasilkan bilangan acak antara 1 dan 100

  if ($randNum <= 30) {
    // 30% kemungkinan muncul angka 3
    return 5;
  } elseif ($randNum <= 60) {
    // 30% kemungkinan muncul angka 4
    return 1;
  } elseif ($randNum <= 85) {
    // 40% kemungkinan muncul angka 5
    return 3;
  } elseif ($randNum <= 95) {
    // 40% kemungkinan muncul angka 5
    return 4;
  } else {
    return 2;
  }
}

// Menghasilkan bilangan acak dengan angka 3-5 lebih sering muncul
// $randomNumber = generateRandomNumber();
// echo $randomNumber;

// $id_guru = 37;
// $date = "2023-03-10"; // Tanggal awal
// $queryGuru = select($c, 'guru');
// $queryGuru = select($c, 'guru a', ['join' => 'INNER JOIN users b ON a.id_user=b.id_user', 'where' => "b.level='guru'"]);
// for ($i = 7; $i < 57; $i++) {
//   foreach ($queryGuru as $guru) {
//     $c31 = generateRandomNumber();
//     $c32 = generateRandomNumber();
//     $c51 = generateRandomNumber();
//     $c52 = generateRandomNumber();
//     $data['values'][] = "(" . $i . "," . $guru['id_guru'] . "," . $c31 . "," . $c32 . "," . $c51 . "," . $c52 . ")";
//   }

//   // $date = date("Y-m-d", strtotime("+1 day", strtotime($date))) . " 07:30:00";

//   // $data['values'][] = "(" . $id_guru . ",'" . $date . "')";
// }

// echo json_encode($data);

// insert_batch($c, $data, 'nilaidarisiswa');

// print_r(mysqli_fetch_assoc(select($c, 'guru', ['where' => "guru.id_user=3"])));