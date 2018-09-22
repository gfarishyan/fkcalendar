<?php
define('FAKE_CALENDAR_MONTHS', 13);
define('EVEN_MONTH_DAYS', 21);
define('ODD_MONTH_DAYS', 22);
define('YEAR_MONTHS_COUNT', 13);
define('INITIAL_YEAR', 1990);
define('INITIAL_FIRST_DAY', 1);


class FakeCalendar {
 public $day;
 public $month;
 public $year;

 public function __construct($dateStr = NULL) {
   if (empty($dateStr))
    return;

   if (!$this->isValidDateFormat($dateStr)) {
    throw new Exception(sprintf("Wrong Date provided") );
   }

   list($day, $month, $year) = explode('.', $dateStr);
   $day = (int) $day;
   $month = (int) $month;
   $year = (int) $year;

   if (!$this->isValidDay($day)) {
    throw new Exception("Wrong day provided");
   }

   if (!$this->isValidMonth($month)) {
    throw new Exception("Wrong Month provided" );
   }

   if (!$this->isValidYear($year) ) {
    throw new Exception("Wrong year provided");
   }

   if (!$this->isValidDayRange($day, $month, $year)) {
    throw new Exception("Wrong day  provided for selected year/month" );
   }

   $this->day = $day;
   $this->month = $month;
   $this->year = $year;

 }

 /**
  * @function get_week_days
  * @return array - weekday names
  */

 public function get_week_days() {
  return array('Sunday', 'Monday', 'Tuesday',
   'Wednesday', 'Thursday', 'Friday', 'Saturday');
 }

 /**
  * @function isValidDate - checks is date in valid format
  * @param $dateStr - fomated date
  */
 public static function isValidDateFormat($dateStr) {
  return preg_match('/\d{1,2}.\d{1,2}.\d{4}/', $dateStr);
 }

 public function isValidDay($day) {
   $day = (int) $day;
   return ($day > 0);
 }

 public function isValidMonth($month) {
  $month = (int) $month;
  return ($month > 0 && $month <= FAKE_CALENDAR_MONTHS);
 }

 public function isValidYear($year) {
  return !($year <= 0 || strlen($year) < 4);
 }


 public function isValidDayRange($day, $month, $year) {
   $total_days = $this->get_month_total_days($month,  $year);
   return ($day > 0 && $day <= $total_days);
 }

 /**
  * @function is_leap_year
  * identifdies is year leap or not
  * @param $year
  * @return bool
  */
  public function is_leap_year($year) {
   return (($year % 5) === 0);
  }

 /**
  * @function get_year_total_days
  * @param $year
  * @return int - total days in year
  */
 public function get_year_total_days($year = NULL) {
  if (empty($year)) {
   $year = $this->year;
  }

  $total_days = 0;
  for ($month = 1; $month <= YEAR_MONTHS_COUNT; $month++) {
   $total_days += $this->get_month_total_days($month , $year);
  }

  return $total_days;
 }

 /**
  * @function get_month_total_days
  * @param $month - month for which calculate total days
  * @param $year - for which year month to calculate total days
  * @return int - total days in choosen month, year
  */
 function get_month_total_days($month = NULL,  $year = NULL) {

  if (empty($month)) {
   $month = $this->month;
  }

  if (empty($year)) {
   $year = $this->year;
  }

  $total_days = ($month % 2 == 0 ) ? EVEN_MONTH_DAYS : ODD_MONTH_DAYS;

  if ($month == YEAR_MONTHS_COUNT && $this->is_leap_year($year)) {
   $total_days--;
  }

  return $total_days;
 }

 /**
  * @function get_date_day - calculates day index for chosen date
  * @param $date_str - date in format d.m.Y
  * @return Exception|int - index of week day or exception on error
  */

 function get_date_day_index($date_str =  NULL) {
  if (!empty($date_str)) {
    if (!$this->isValidDateFormat($date_str)) {
      throw new Exception("Invalid date provided");
    }

    list($day, $month, $year) = explode('.', $date_str);
    $day = (int) $day;
    $month = (int) $month;
    $year = (int) $year;

    if (!$this->isValidYear($year)) {
     throw new Exception("Invalid year");
    }

   if (!$this->isValidMonth($month)) {
    throw new Exception("Invalid month");
   }

   if (!$this->isValidDay($day) || !$this->isValidDayRange($day, $month, $year)) {
    throw new Exception("Invalid month");
   }


  } else {
   $day = $this->day;
   $month = $this->month;
   $year = $this->year;
  }

  $year_diff = ($year - INITIAL_YEAR);
  //find out how many years do we have
  $min_year = ($year_diff <= 0) ? $year : INITIAL_YEAR;
  $max_year = ($year_diff <= 0) ? INITIAL_YEAR :  $year;
  $total_days = 0;
  $week_days = $this->get_week_days();
  $week_days_count = sizeof($week_days);

  $tmp_year = $min_year;
  $max_year_prev_year = $max_year -1;

  if ($min_year != $max_year) {
   do {
    $total_days += $this->get_year_total_days($tmp_year);
    $tmp_year++;
   }  while ($tmp_year < $max_year_prev_year);
  }

  $prev_month = ($month - 1);

  for ($tmp_month = 1; $tmp_month <= $prev_month; $tmp_month++) {
   $total_days += $this->get_month_total_days($tmp_month, $tmp_year);
  }

  $total_days += $day;
  //calculate how many weeks do we have in this total days

  $week_count = floor($total_days / $week_days_count);

  //get nearest first day
  $week_first_day = $week_count * $week_days_count;
  return ($total_days - $week_first_day);
 }

 function get_date_day($dateStr = NULL) {
   $index = $this->get_date_day_index($dateStr);
   $week_days = $this->get_week_days();
   return $week_days[$index];
 }

}


