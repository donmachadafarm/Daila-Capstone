<?php

include 'includes/sections/header.php';



/*
 *    T E S T I N G
 *
 *    A R E A
 *
 *    B E L O W
 *
 */
    // secondsdatetime -> time diff of two times turned into seconds
// echo seconds_datetime(60*check_time_diff_mins("10:28:00","12:23:00"));

// print_p(get_productprocess($conn,10));
// $m = get_machine($conn,1);
// $random_keys=array_rand($m,2);
// echo $m[$random_keys]."<br>";

$dates = array
(
    '0'=> "2013-02-18 05:14:54",
    '1'=> "2013-02-12 01:44:03",
    '2'=> "2013-02-05 16:25:07",
    '3'=> "2013-01-29 02:00:15",
    '4'=> "2013-01-27 18:33:45"
);

echo find_closest_today($dates,"2018-11-20");

?>
