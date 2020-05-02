<?php

include '../init.php';

// Site/page boilerplate
$site = new site('MAM | Dashboard', $errors, $actionSuccess);
init_site($site);

$page = new page();
$site->setPage($page);
$_GET['sidebar-page'] = 1;
$site->addHeader("../includes/navbar.php");

// Start rendering the content
ob_start();

if (!isset($_GET['month']) || !isset($_GET['year'])) {
    $_GET['day'] = date("j");
    $_GET['month'] = date("m");
    $_GET['year'] = date("Y");
} else {
    $_GET['day'] = 1;
}

$day_num        = $_GET['day'];
$month_num      = $_GET['month'];
$year           = $_GET['year'];

if ($month_num == 12) {
    $next_year = $year + 1;
    $next_month = 1;
    $prev_year = $year;
    $prev_month = 11;
} elseif ($month_num == 1) {
    $next_year = $year;
    $next_month = 2;
    $prev_year = $year - 1;
    $prev_month = 12;
} else {
    $next_year = $year;
    $next_month = $month_num + 1;
    $prev_year = $year;
    $prev_month = $month_num - 1;
}


$date_today     = getdate(mktime(0, 0, 0, $month_num, 1, $year));
$month_name     = $date_today["month"];
$first_week_day = $date_today["wday"];

$cont  = true;
$today = 27;
while (($today <= 32) && ($cont)) {
    $date_today = getdate(mktime(0, 0, 0, $month_num, $today, $year));
    if ($date_today["mon"] != $month_num) {
        $lastday = $today - 1;
        $cont    = false;
    }
    $today++;
}
echo "<p style='float: right;'><a href='?month=$prev_month&year=$prev_year'>Previous Month</a> | <a href='?month=$next_month&year=$next_year'>Next Month</a>";
echo "<h1>$month_name $year</h1>";
echo "<table cellspacing=0 cellpadding=5 frame='all' rules='all' class='cal'>";
echo "<tr><th>Su</th><th>M</th><th>Tu</th><th>W</th><th>Th</th><th>F</th><th>Sa</th></tr>";

$eventHelper = new EventHelper($config);
$shiftHelper = new ShiftHelper($config);

$day       = 1;
$wday      = $first_week_day;
$firstweek = true;
while ($day <= $lastday) {
    if ($firstweek) {
        echo "<tr align=left>";
        for ($i = 1; $i <= $first_week_day; $i++) {
            echo "<td> </td>";
        }
        $firstweek = false;
    }
    if ($wday == 0) {
        echo "<tr align=left>";
    }
    $shifts = $shiftHelper->getShiftsByDate($year . '-' . $month_num . '-' . $day);
    $events = $eventHelper->getEventsByDate($year . '-' . $month_num . '-' . $day);

    echo "<td";
    if (empty($shifts) && !empty($events)) {
        echo " class='orange' title='Missed Opportunity'";
    } elseif (count($shifts) > 1) {
        echo " class='red' title='Conflicting Shifts'";
    } elseif (empty($shifts) && empty($events)) {
        echo " class='white'";
    } elseif ($shifts[0]['ShiftConfirmed'] == 1) {
        echo " class='green' title='Shift Confirmed'";
    } elseif ($shifts[0]['ShiftConfirmed'] == 0) {
        echo " class='yellow' title='Shift Pending'";
    }

    if ($year . '-' . $month_num . '-' . $day == date('Y-m-j')) {
        echo " style='border: 3px solid black;'";
    }

    echo ">";

    if (empty($shifts) && empty($events)) {
        echo "<a href='events.php?action=add&date=" . $year . '-' . $month_num . '-' . $day . "'><h5>$day</h5></a>";
    } else {
        echo "<h5>$day</h5>";
    }

    if (empty($shifts)) {
        foreach ($events as $event) {
            echo '<a href="shifts.php?action=add&eventID=' . $event['EventID'] . '">' . $event['EventTitle'] . '</a></br>';
        }
    } else {
        foreach ($shifts as $shift) {
            echo '<a href="shifts.php?action=edit&item=' . $shift['ShiftID'] . '">' . $shift['EventTitle'];

            if ($shift['EventHours'] != 0) {
                if ($shift['ShiftConfirmed'] == 1) {
                    echo ' ($' . round($shift['EventHours'] * $shift['JobWage']) . ')';
                } else {
                    echo ' (' . $shift['EventHours'] . ' hrs)';
                }
            }
            echo '</a></br>';
        }
    }

    echo "</td>";
    if ($wday == 6) {
        echo "</tr>";
    }

    $wday++;
    $wday = $wday % 7;
    $day++;
}

while ($wday <= 6 && $wday > 0) { // Fill in the last row
    echo "<td>&nbsp;</td>";
    $wday++;
}
echo "</tr></table>";

$shifts = $shiftHelper->getShiftsbyMonth(date('Y-m-d', mktime(0, 0, 0, $month_num, 1, $year)));
$personal = 0;
$work = 0;
foreach ($shifts as $shift) {
    if ($shift['JobTitle'] == 'Personal') {
        $personal++;
    } else {
        $work++;
    }
}

echo "<h5>Shift Breakdown</h5>";
echo "<b>Personal:</b> " . $personal . " - <b>Work:</b> " . $work;

// End content rendering

$content = ob_get_clean();
$page->setContent($content);

$site->render();
