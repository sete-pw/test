<?php
namespace Application\Test\Model;

use Application\Test\Controller\ApiConstants;

class Bin extends Order{

    function get(){
        if(\CO::AUTH()->user()){
            $returnRequest = \CO::SQL()->query("SELECT SUM(price) as price, COUNT(set_id) as count
                                              FROM orders inner join order_sets
                                              WHERE user_id = ? and orders.state = ?
                                              ",[['i',\CO::AUTH()->who()->ID()],['s', 'bin']]);
            return $returnRequest[0];
        }
        if(\CO::AUTH()->unknown()){
            return [
                ApiConstants::$STATUS => ApiConstants::$ERROR,
                ApiConstants::$ERROR_MESSAGE => ApiConstants::$ERROR_AUTH__STRING,
                ApiConstants::$ERROR_CODE => ApiConstants::$ERROR_AUTH_CODE
            ];
        }
    }

    function getList(){
        if (\CO::AUTH()->user()) {
            $returnRequest = \CO::SQL()->query("SELECT
                                                order_sets.id_order_set, id_table, CONCAT(tables.position,';',sets.position) as position , tables.price
                                                FROM
                                                order_sets inner join orders on orders.id_order = order_sets.order_id
                                                inner join sets on sets.id_set = order_sets.set_id
                                                inner join tables on tables.id_table = sets.table_id
                                                WHERE orders.user_id = ? and orders.state =?
                                              ", [['i', \CO::AUTH()->who()->ID()], ['s', 'bin']]);
            return $returnRequest;
        }
        if (\CO::AUTH()->unknown()) {
            return [
                ApiConstants::$STATUS => ApiConstants::$ERROR,
                ApiConstants::$ERROR_MESSAGE => ApiConstants::$ERROR_AUTH__STRING,
                ApiConstants::$ERROR_CODE => ApiConstants::$ERROR_AUTH_CODE
            ];
        }
    }

    /*
     * Добавляет set в корзину
     */
    function add($params){
        if (!isset($params['id_set'])){
            return [
                ApiConstants::$STATUS => ApiConstants::$ERROR,
                ApiConstants::$ERROR_MESSAGE => ApiConstants::$ERROR_PARAMS_STRING,
                ApiConstants::$ERROR_CODE => ApiConstants::$ERROR_PARAMS_CODE];
        }
        if (\CO::AUTH()->user()) {
            $bin = $this->QUERY("SELECT *
                                    FROM orders
                                    WHERE user_id =?
                                    AND state = ?
                                      ", [['i', \CO::AUTH()->who()->ID()], ['s', 'bin']]);
            if (count($bin) == 0){
                $this->QUERY("INSERT INTO orders
                                    (user_id,state, price)
                                    VALUES
                                    (?,?,?)
                                      ", [['i', \CO::AUTH()->who()->ID()], ['s', 'bin'],['i',0]]);

                $bin = $this->QUERY("SELECT *
                                    FROM orders
                                    WHERE user_id =?
                                    AND state = ?
                                      ", [['i', \CO::AUTH()->who()->ID()], ['s', 'bin']]);
            }
            $maxArr = $this->QUERY("SELECT MAX(sort_id)+1 as m FROM order_sets");
            $orderSet = new OrderSet();
            $order->findBy_id_order_set();
            if (isset($maxArr[0]['m'])) $max = $maxArr[0]['m']; else $max = 1;
            $this->QUERY("INSERT INTO order_sets
                                    (order_id,set_id, sort_id,state)
                                    VALUES
                                    (?,?,?,?)
                                      ",[
                ['i',$bin[0]['id_order']],
                ['i',$params['id_set']],
                ['i', $max],
                ['s','add']
            ]);

            /**
                                    ВОЗВРАЩАЕМОЕ ЗНАЧЕНИЕ (insert id)
             */
            $returnRequest = [
                'id_order_set' => \CO::SQL()->iid()
            ];

            $this->QUERY("UPDATE orders
                            SET price = price + (SELECT price
                                                  FROM tables inner join sets on tables.id_table = sets.table_id
                                                  WHERE id_set = ?)
                            WHERE id_order = ?
                            ",[
                ['i',$params['id_set']],
                ['i',$bin[0]['id_order']]
            ]);
            return $returnRequest;
        }
        if (\CO::AUTH()->unknown()) {
            return [
                ApiConstants::$STATUS => ApiConstants::$ERROR,
                ApiConstants::$ERROR_MESSAGE => ApiConstants::$ERROR_AUTH__STRING,
                ApiConstants::$ERROR_CODE => ApiConstants::$ERROR_AUTH_CODE
            ];
        }
    }

    function remove($params){
        if (!isset($params['id_order_set'])){
            return [
                ApiConstants::$STATUS => ApiConstants::$ERROR,
                ApiConstants::$ERROR_MESSAGE => ApiConstants::$ERROR_PARAMS_STRING,
                ApiConstants::$ERROR_CODE => ApiConstants::$ERROR_PARAMS_CODE];
        }
        if (\CO::AUTH()->user()){
            $this->QUERY("UPDATE orders
                             SET price = price - (SELECT tables.price
                                                  FROM tables inner join sets on tables.id_table = sets.table_id
                                                  inner join order_sets on sets.id_set = order_sets.set_id
                                                  WHERE id_order_set = ?)
                             WHERE user_id = ? and state = ?
                             ",[
                ['i',$params['id_order_set']],
                ['i',\CO::AUTH()->who()->ID()],
                ['s','bin']
            ]);

            $this->QUERY("DELETE FROM order_sets
                              WHERE order_sets.state = ? and id_order_set in (
                                  SELECT id_order_set
                                  FROM (SELECT id_order_set FROM order_sets inner join orders on order_sets.order_id = orders.id_order
                                  WHERE user_id = ? and id_order_set = ?) as tmp
                              )
                             ",[
                ['s','add'],
                ['i',\CO::AUTH()->who()->ID()],
                ['i',$params['id_order_set']]
            ]);

            $countSets = $this->QUERY("SELECT price, COUNT(set_id) as count
                                              FROM orders inner join order_sets
                                              WHERE user_id = ? and orders.state = ? GROUP BY price
                                              ",[
                    ['i',\CO::AUTH()->who()->ID()],
                    ['s', 'bin']]
            );
            if (!isset($countSets[0]['count'])){
                $this->QUERY("DELETE FROM orders WHERE user_id =? and state = ?",[['i',\CO::AUTH()->who()->ID()],['s','bin']]);
            }

            /**
                                    TODO:   Должен возвращать ошибку, если такой позиции нет!!!
             */

            return [
                ApiConstants::$STATUS => ApiConstants::$SUCCESS
            ];
        }
        if (\CO::AUTH()->unknown()) {
            return [
                ApiConstants::$STATUS => ApiConstants::$ERROR,
                ApiConstants::$ERROR_MESSAGE => ApiConstants::$ERROR_AUTH__STRING,
                ApiConstants::$ERROR_CODE => ApiConstants::$ERROR_AUTH_CODE
            ];
        }
    }

}