<?php
include_once 'fk_calendar.php';

$fkCalendar = new FakeCalendar("01.01.1990");

print "\n";
print $fkCalendar->get_date_day();
print "\n";
print $fkCalendar->get_date_day("17.11.2013");

