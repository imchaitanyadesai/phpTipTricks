/**
 * Generate an array of string dates between 2 dates
 *
 * @param string $start Start date
 * @param string $end End date
 * @param string $format Output format (Default: Y-m-d)
 *
 * @return array
 */

function getDatesFromRange($start, $end, $format = 'Y-m-d') {
    $array = array();
    $interval = new DateInterval('P1D');

    $realEnd = new DateTime($end);
    $realEnd->add($interval);

    $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

    foreach($period as $key => $date) { 
        $array[$key]['date'] = $date->format($format);
        $array[$key]['day']  = date('D', strtotime($date->format($format))); 
    }

    return $array;
}

getDatesFromRange('2018-05-16', '2018-05-25');

/*
Array
(
    [0] => 2010-10-01
    [1] => 2010-10-02
    [2] => 2010-10-03
    [3] => 2010-10-04
    [4] => 2010-10-05
)
*/
