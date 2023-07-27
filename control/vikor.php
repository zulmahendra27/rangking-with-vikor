<?php
function normalisasi($array, $nilai)
{
  return round((max($array) - $nilai) / (max($array) - min($array)), 5);
}

function normalisasiXBobot($normalisasi, $bobot)
{
  return round(($normalisasi * $bobot), 5);
}

function nilaiQ($nilaiS, $nilaiR, $maxMinS, $maxMinR)
{
  $value = (($nilaiS - min($maxMinS)) / (max($maxMinS) - min($maxMinS)) * 0.5) + (($nilaiR - min($maxMinR)) / (max($maxMinR) - min($maxMinR) * 0.5));
  return round($value, 5);
}
