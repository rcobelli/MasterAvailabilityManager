<?php

include_once("../init.php");

// Purposfully not authenticating with the cookie for this page

$config['type'] = Rybel\backbone\LogStream::api;

// Make it actually be an ics file
header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename=feed.ics');

// the iCal date format. Note the Z on the end indicates a UTC timestamp.
define('DATE_ICAL', 'Ymd');

// Boilerplate
$output = "BEGIN:VCALENDAR
METHOD:PUBLISH
VERSION:2.0\n";

$helper = new ShiftHelper($config);
$shifts = $helper->getShifts(false);

// loop over events
foreach ($shifts as $shift) {
    if (empty($shift['StartTime'])) {
        $summary = $shift['JobTitle'] . ": " . $shift['EventTitle'];
        if ($shift['EventHours'] != 0) {
            $summary .=  " (" . $shift['EventHours'] . " hrs)";
        }
        $startTime = "DTSTART;VALUE=DATE:" . date(DATE_ICAL, strtotime($shift['EventDate']));
        $endTime = "DTEND;VALUE=DATE:" . date(DATE_ICAL, strtotime($shift['EventDate']));
    } else {
        $summary = $shift['JobTitle'] . ": " . $shift['EventTitle'];
        $startTime = "DTSTART;TZID=America/New_York:" . date('Ymd', strtotime($shift['EventDate'])) . 'T' . date('His', strtotime($shift['StartTime']));

        // Check to see if the shift will go after midnight
        if (date('H', strtotime($shift['StartTime'])) + $shift['EventHours'] >= 23) {
            $endTime = "DTEND;TZID=America/New_York:" . date('Ymd', strtotime($shift['EventDate'])) . 'T' . date('His', strtotime('23:59:59'));
        } else {
            $endTime = "DTEND;TZID=America/New_York:" . date('Ymd', strtotime($shift['EventDate'])) . 'T' . date('His', strtotime($shift['StartTime'] . '+' . $shift['EventHours'] .' hours'));
        }
    }

    $output .=
"BEGIN:VEVENT
SUMMARY:" . $summary . "
UID:" . $shift['ShiftID'] . "
" . $startTime . "
" . $endTime . "
END:VEVENT\n";
}

// close calendar
$output .= "END:VCALENDAR";

echo $output;
