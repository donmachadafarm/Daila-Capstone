<?php
// $a = array('1', '0', '1');
// $b = array('50', '10', '20');
// $c = array_combine($a, $b);


function array_merge_numeric_values()
{
    $arrays = func_get_args();
    $merged = array();
    foreach ($arrays as $array)
    {
        foreach ($array as $key => $value)
        {
            if ( ! is_numeric($value))
            {
                continue;
            }
            if ( ! isset($merged[$key]))
            {
                $merged[$key] = $value;
            }
            else
            {
                $merged[$key] += $value;
            }
        }
    }
    return $merged;
}

// print_r(array_merge_recursive($a,$b));

// print_r(array_merge_numeric_values($a,$b));

// print_r($c);

// print_r(array_merge($a));

// print_r(array_unique($a));

// $levels = array('1', '0', '1');
// $attributes = array('50', '10', '20');
//
// $ret = array();
// foreach ($levels as $level) {
//   $ret[$level] = array();
//   foreach($attributes as $attribute) {
//     $ret[$level][] = $attribute.'_'.$level;
//     }
//   }
//
// print_r($ret);

// $a = array('1', '0', '1');
// $b = array('50', '10', '20');
//
// $result = array();
// for($i = 0;$i<count($a);$i++) {
//     $result[] = array(
//         'rmid' => $a[$i],
//         'qty' => $b[$i]
//     );
// }
//
// print("<pre>".print_r($result,true)."</pre>");

//first array
$k = array('1','0','1');
//second array
$v = array('50','10','20');

$result = array();

foreach($k as $index => $value) {
    if(!isset($result[$value])) {
        $result[$value] = 0;
    }
    $result[$value] += $v[$index];
}

print_r($result);



?>
