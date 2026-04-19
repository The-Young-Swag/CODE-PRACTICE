<?php
date_default_timezone_set("Asia/Manila");

$year  = $_GET['year']  ?? date("Y");
$month = $_GET['month'] ?? date("n");

function buildCalendar($year, $month) {

    $firstDay    = date("N", strtotime("$year-$month-01")); // 1–7 (Mon–Sun)
    $daysInMonth = date("t", strtotime("$year-$month-01"));

    $html = "<tr>";

    // Empty cells before start
    for ($i = 1; $i < $firstDay; $i++) {
        $html .= "<td></td>";
    }

    for ($day = 1; $day <= $daysInMonth; $day++) {

        $weekDay = date("N", strtotime("$year-$month-$day"));
        $class   = ($weekDay >= 6) ? "bg-gray-300" : "bg-green-200";

        $html .= "<td class='p-2 border cursor-pointer $class'>$day</td>";

        // Break row on Sunday
        if ((($day + $firstDay - 1) % 7) === 0) {
            $html .= "</tr><tr>";
        }
    }

    return $html . "</tr>";
}

echo buildCalendar($year, $month);