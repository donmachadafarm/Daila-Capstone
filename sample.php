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

// print_p(get_productprocess($conn,2));
check_available_machines_production($conn,5);
// print_p(check_available_machine($conn,1));
// echo check_for_inventory_match(5,$conn);
// print_p(get_machine($conn,1));
// print_p(get_need_inventory(5,$conn));
// if (count(get_need_inventory(5,$conn)) > 0) {
  // echo "may kulang";
  // print_p(get_need_inventory(5,$conn));
// }else {
  // echo "Walang kulang";
// }
// echo check_for_inventory_match(5,$conn);

// echo check_for_inventory_match(3,$conn);

// reduce_inventory_rawmats_production($conn,5);

?>
