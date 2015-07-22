<?php
namespace Application\Test\Model;

use Application\Test\Controller\ApiConstants;

class Bin extends Order{

    function get(){
        if(\CO::AUTH()->user()){
            $returnRequest =  $this->QUERY("SELECT SUM(price) as price, COUNT(set_id) as count
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
            $returnRequest = $this->QUERY("SELECT
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
            // Корзина
            $bin = new \Application\Test\Model\Order();
            
            // Проверяем, есть ли корзина у пользователя
            $binId = $bin->QUERY(
"SELECT id_order
from orders
where
    user_id = ?
    and
    state = 'bin'
limit 1;
            ", [
                ['i', \CO::AUTH()->who()->ID()]
            ]);

            if(count($binId)){
                //Если есть, то забираем ее
                $bin->findBy_id_order($binId[0]['id_order']);
            }else{
                //Если нет, то создаем
                $bin->user_id = \CO::AUTH()->who()->ID();
                $bin->state = 'bin';
                $bin->price = 0;
                $bin->CREATE();
            }


            //Позиция
            $set = new \Application\Test\Model\OrderSet();

            //Проверка существования стола и его статуса

            $setId = $set->QUERY("
SELECT id_set
FROM sets
WHERE id_set = ?
            ", [['i', $params['id_set']]]);

            if (!count($setId)){
                return [
                    ApiConstants::$STATUS => ApiConstants::$ERROR,
                    ApiConstants::$ERROR_MESSAGE => ApiConstants::$ERROR_BUSY_SET_STRING,
                    ApiConstants::$ERROR_CODE => ApiConstants::$ERROR_BUSY_SET_CODE
                ];
            }

            $setId = $set->QUERY("
SELECT id_order_set
FROM order_sets
WHERE set_id = ?  and state <> 'delete'
            ", [['i', $params['id_set']]]);

            if (count($setId)){
                return [
                    ApiConstants::$STATUS => ApiConstants::$ERROR,
                    ApiConstants::$ERROR_MESSAGE => ApiConstants::$ERROR_BUSY_SET_STRING,
                    ApiConstants::$ERROR_CODE => ApiConstants::$ERROR_BUSY_SET_CODE
                ];
            }
            //Пытаемся добавить позицию
            $set->QUERY(
"INSERT INTO order_sets(
    order_id,
    set_id,
    state
)values(
    ?, ?, 'add'
);
            ", [
                ['i', $bin->ID()],
                ['i', (int)$params['id_set']]

            ]);

            $set->findBy_id_order_set(\CO::SQL()->iid());
            if (isset($set->id_order_set)){
                $returnRequest = [
                    'id_order_set' => $set->ID()
                ];
                return $returnRequest;
            }
            return null;

            /**
                                    ВОЗВРАЩАЕМОЕ ЗНАЧЕНИЕ (insert id)
             */
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

    function pay(){
        if (\CO::AUTH()->user()) {
            $bin = new \Application\Test\Model\Order();
            // Проверяем, есть ли корзина у пользователя
            $binId = $this->QUERY(
                "SELECT id_order
from orders
where
    user_id = ?
    and
    state = 'bin'
limit 1
            ", [
                ['i', \CO::AUTH()->who()->ID()]
            ]);
            if(count($binId)){
                //Если есть, то забираем ее
                $bin->findBy_id_order($binId[0]['id_order']);
            }else{
                return [
                    ApiConstants::$STATUS => ApiConstants::$ERROR,
                    ApiConstants::$ERROR_MESSAGE => ApiConstants::$ERROR_NOT_FOUND_BIN_STRING,
                    ApiConstants::$ERROR_CODE => ApiConstants::$ERROR_NOT_FOUND_BIN_CODE
                ];
            }
            $date = date('Y-m-d H:i:s');
            $bin->date_pay = $date;
            $bin->state = 'pay';
            $bin->UPDATE();
            return [
                ApiConstants::$STATUS =>ApiConstants::$SUCCESS
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