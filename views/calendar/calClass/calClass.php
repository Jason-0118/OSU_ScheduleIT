<?php

/**
 * @class Calendar
 * A class representing a calendar.
*/
class Calendar {
    /** @brief
     * Initialize vars for current date and event array.
     * 
     * @var int $activeYear The active year of the calendar.
     * @var int $activeMonth The active month of the calendar.
     * @var int $activeDay The active day of the calendar.
     * @var array $eventArr An array to store events in the calendar.
    */
    private $activeYear, $activeMonth, $activeDay;
    private $eventArr = [];

    /** @brief
     * Initializes a new instance of the Calendar class.
     * @param string|null $date The date to set as the active date (optional). If not provided, the current date will be used.
    */
    public function __construct($date = null) {
        $this->activeDay = $date != null ? date('d', strtotime($date)) : date('d');
        $this->activeMonth = $date != null ? date('m', strtotime($date)) : date('m');
        $this->activeYear = $date != null ? date('Y', strtotime($date)) : date('Y');
    }

    /** @brief
     * Adds an event to the calendar. By default, the event lasts for 1 day and has the color "amber".
     * @param string $evName The name of the event.
     * @param string $evDate The date of the event in the format "YYYY-MM-DD".
     * @param int $evDays The duration of the event in days (optional).
     * @param string $evColor The color of the event (optional).
    */
    public function addEvent($evName, $evDate, $evDays = 1, $evColor = '') {
        $evColor = $evColor ? ' ' . $evColor : $evColor;
        $this->eventArr[] = [$evName, $evDate, $evDays, $evColor];
    }

    /** @brief
     * Returns the string content of the calendar element.
     * @return string The HTML representation of the calendar.
    */
    public function __toString() {
        // get number of days in given month
        $totalDaysInMonth = date('t', strtotime($this->activeDay . '-' . $this->activeMonth . '-' . $this->activeYear));
        // get last day of last month
        $lastDayPrevMonth = date('j', strtotime('last day of previous month', strtotime($this->activeDay . '-' . $this->activeMonth . '-' . $this->activeYear)));
        // create arr of short names for days of the week
        $days = [0 => 'Sun', 1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat'];
        // get first day of the week (short day names, e.g. 'Sun' 'Mon' etc)
        $firstDayOfWeek = array_search(date('D', strtotime($this->activeYear . '-' . $this->activeMonth . '-1')), $days);
        
        // create html div classes for calendar elements
        $html = '<div class="calendar">';
        // append html div class for calendar header
        $html .= '<div class="header">';
        // get full textual representation of month and year (e.g. 'September 2022' or 'February 2023') -- displays right above generated cal
        $html .= '<div class="month-year">';
        $html .= date('F Y', strtotime($this->activeYear . '-' . $this->activeMonth . '-' . $this->activeDay));
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="days">';
        
        // create div class for each day
        foreach ($days as $day) {
            $html .= '
                <div class="day_name">
                    ' . $day . '
                </div>
            ';
        }
        // create div class for shading out last few days of the previous month
        for ($i = $firstDayOfWeek; $i > 0; $i--) {
            $html .= '
                <div class="day_num ignore">
                    ' . ($lastDayPrevMonth - $i+1) . '
                </div>
            ';
        }
        // div class for populating calendar with days in current month
        for ($i = 1; $i <= $totalDaysInMonth; $i++) {
            // highlight current day (day box will be highlighted by greyed background color instead of standard white background color)
            $selected = '';
            if ($i == $this->activeDay) {
                $selected = ' selected';
            }
            // handle remaining days 
            $html .= '<div class="day_num' . $selected . '">';
            $html .= '<span>' . $i . '</span>';
            foreach ($this->eventArr as $event) {
                // traverse thru event arr
                for ($d = 0; $d <= ($event[2]-1); $d++) {
                    // check whether this current date is in the events array
                    if (date('y-m-d', strtotime($this->activeYear . '-' . $this->activeMonth . '-' . $i . ' -' . $d . ' day')) == date('y-m-d', strtotime($event[1]))) {
                        // if event is scheduled for current date, change its class to event-specific day 
                        $html .= '<div class="event' . $event[3] . '">';
                        $html .= $event[0];
                        $html .= '</div>';
                    }
                    // else, it's a normal day
                }
            }
            $html .= '</div>';
        }
        // create div class for shading out first few days of the next month
        for ($i = 1; $i <= (42 - $totalDaysInMonth - max($firstDayOfWeek, 0)); $i++) {
            $html .= '
                <div class="day_num ignore">
                    ' . $i . '
                </div>
            ';
        }
        // close day and calendar divisions
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

}
?>
