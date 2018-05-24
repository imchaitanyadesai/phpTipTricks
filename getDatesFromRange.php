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

getDatesFromRange('2018-05-19', '2018-05-25');

/*
Array
(
    [0] => Array
        (
            [date] => 2018-05-19
            [day] => Sat
        )

    [1] => Array
        (
            [date] => 2018-05-20
            [day] => Sun
        )

    [2] => Array
        (
            [date] => 2018-05-21
            [day] => Mon
        )

    [3] => Array
        (
            [date] => 2018-05-22
            [day] => Tue
        )

    [4] => Array
        (
            [date] => 2018-05-23
            [day] => Wed
        )

    [5] => Array
        (
            [date] => 2018-05-24
            [day] => Thu
        )

    [6] => Array
        (
            [date] => 2018-05-25
            [day] => Fri
        )

)

*/
