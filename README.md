# fkcalendar

Write PHP function, which returns day of standard seven days week of imaginary calendar, assuming we know how often a leap year occurs, how many months it has and how many days it has in each month. Use function to find the day of date 17.11.2013.

Definition of calendar:

- each year has 13 months
- each even month has 21 days, each odd month has 22 days
- in leap year last month has less one day
- leap year is each year dividable by five without rest
- every week has 7 days: Sunday, Monday, Tuesday, Wednesday, Thursday, Friday, Saturday
- first day of year 1990 was Monday  

Usage example

include_once 'fk_calendar.php';

$fkCalendar = new FakeCalendar("17.11.2013");

$fkCalendar->get_date_day(); // Returns Wednesday

$fkCalendar->get_date_day("01.01.1990"); // Return Monday

$fkCalendar->get_date_day("31.11.1900"); // throws exception


$fkCalendar->get_date_day("22.13.1900"); // throws exception

print $fkCalendar->get_date_day("21.13.1900"); //Returns "Tuesday"
