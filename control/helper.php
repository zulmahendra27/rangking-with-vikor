<?php
// Fungsi DML
function select($c, $table, $opt = [])
{
  $select = array_key_exists('select', $opt) ? $opt['select'] : '*';
  $where = array_key_exists('where', $opt) ? ("WHERE " . $opt['where']) : '';
  $group = array_key_exists('group', $opt) ? ('GROUP BY ' . $opt['group']) : '';
  $order = array_key_exists('order', $opt) ? ('ORDER BY ' . $opt['order']) : '';
  $limit = array_key_exists('limit', $opt) ? ('LIMIT ' . $opt['limit']) : '';

  if (array_key_exists('join', $opt)) {
    return $c->query("SELECT $select FROM $table $opt[join] $where $order $group $limit");
  }

  return $c->query("SELECT $select FROM $table $where $order $group $limit");
}

function insert($c, $data, $table)
{
  $key = array();
  $value = array();

  foreach ($data as $k => $v) {
    array_push($key, $k);
    array_push($value, "'" . $v . "'");
  }

  $column = implode(',', $key);
  $values = implode(',', $value);

  return $c->query("INSERT INTO $table($column) VALUES ($values)");
}

function insert_nilai($c, $data, $table)
{
  $key = array();
  $value = array();

  foreach ($data as $k => $v) {
    array_push($key, $k);
  }

  for ($i = 0; $i < count($data['id_siswa']); $i++) {
    array_push($value, "(" . $data['id_siswa'][$i] . "," . $data['id_mapel'] . "," . $data['nilai'][$i] . ")");
  }

  $column = implode(',', $key);
  $values = implode(',', $value);

  return $c->query("INSERT INTO $table($column) VALUES $values");
}

function insert_absensi($c, $data, $table)
{
  $key = array();
  $value = array();

  foreach ($data as $k => $v) {
    array_push($key, $k);
  }

  for ($i = 0; $i < count($data['id_siswa']); $i++) {
    array_push($value, "(" . $data['id_siswa'][$i] . "," . $data['id_mapel'] . ",'" . $data['tanggal'] . "','" . $data['status'][$i] . "')");
  }

  $column = implode(',', $key);
  $values = implode(',', $value);

  return $c->query("INSERT INTO $table($column) VALUES $values");
}

function insert_batch($c, $data, $table)
{
  $column = implode(',', $data['key']);
  $values = implode(',', $data['values']);

  return $c->query("INSERT INTO $table($column) VALUES $values");
}

function update($c, $data, $table, $where)
{
  // $key = array();
  $value = array();

  foreach ($data as $k => $v) {
    // array_push($key, $k);
    array_push($value, $k . "='" . $v . "'");
  }

  $values = implode(',', $value);

  return $c->query("UPDATE $table SET $values WHERE $where");
}

function delete($c, $table, $where)
{
  return $c->query("DELETE FROM $table WHERE $where");
}

// =========================================================================================


// Fungsi Alert
function alert($text, $type = 'success')
{
  $type = 'show' . ucfirst($type) . 'Toast';

  return html_entity_decode("<script>$type('$text')</script>");
}


// =========================================================================================

// Fungsi Sort
function sortMultiDimensionalArray(&$array, $index)
{
  usort($array, function ($a, $b) use ($index) {
    return $a[$index] - $b[$index];
  });
}

function sortMultiArray($array)
{
  array_multisort(array_map(function ($element) {
    return $element[1];
  }, $array), SORT_ASC, $array);

  return $array;
}

// =========================================================================================
function bulanIndo($date)
{
  $bulanIndo = [
    1 => "Januari",
    "Februari",
    "Maret",
    "April",
    "Mei",
    "Juni",
    "Juli",
    "Agustus",
    "September",
    "Oktober",
    "November",
    "Desember",
  ];

  $split = explode("-", $date);

  return $split[2] . ' ' . $bulanIndo[intval($split[1])] . ' ' . $split[0];
}
