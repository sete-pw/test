<?php
namespace Application\Test\Model;

use Application\Test\Controller\ApiConstants;

class Bin extends Order{

    function get(){
        if(\CO::AUTH()->user()){
            $returnRequest = CO::SQL()->query("SELECT price, COUNT(set_id) as count
                                              FROM orders inner join order_sets
                                              WHERE user_id = ? and orders.state = ? GROUP BY price
                                              ",[['i',\CO::AUTH()->who()['id_user']],['s', 'bin']]);
            return $returnRequest;
        }
        if(\CO::AUTH()->unknown()){
            return [
                'status' => 'error',
                'errMsg' => 'Not auth user',
                'errNum' => ApiConstants::$ERROR_AUTH
            ];
        }
    }

    function getList(){
        if (CO::AUTH()->user()) {
            $returnRequest = CO::SQL()->query("SELECT
                                                order_sets.id_order_set, id_table, CONCAT(tables.position,';',sets.position) as position , tables.price
                                                FROM
                                                order_sets inner join orders on orders.id_order = order_sets.order_id
                                                inner join sets on sets.id_set = order_sets.set_id
                                                inner join tables on tables.id_table = sets.table_id
                                                WHERE orders.user_id = ? and orders.state =?
                                              ", [['i', CO::AUTH()->who()['id_user']], ['s', 'bin']]);
            return $returnRequest;
        }
        if (CO::AUTH()->unknown()) {
            return [
                'status' => 'error',
                'errMsg' => 'Not auth user',
                'errNum' => ApiConstants::$ERROR_AUTH
            ];
        }
    }
}