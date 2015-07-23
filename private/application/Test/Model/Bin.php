<?php
namespace Application\Test\Model;

use Application\Test\Controller\ApiConstants;

class Bin extends \ModelSql{
    protected $table = 'orders';

    function data(){
        return $this->VALUES();
    }

    function get(){
        if(\CO::AUTH()->user()){
            $returnRequest =  $this->QUERY("SELECT SUM(price) as price, COUNT(set_id) as count
                                              FROM orders inner join order_sets
                                              WHERE user_id = ? and orders.state = ?
                                              ",[['i',\CO::AUTH()->who()->ID()],['s', 'bin']]);
            return [
                ApiConstants::$STATUS =>ApiConstants::$SUCCESS,
                ApiConstants::$RESPONSE => $returnRequest[0]
        ];
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
                                                order_sets.id_order_set,CONCAT(tables.position,';',sets.position) as position , tables.price
                                                FROM
                                                order_sets inner join orders on orders.id_order = order_sets.order_id
                                                inner join sets on sets.id_set = order_sets.set_id
                                                inner join tables on tables.id_table = sets.table_id
                                                WHERE orders.user_id = ? and orders.state =?
                                              ", [['i', \CO::AUTH()->who()->ID()], ['s', 'bin']]);
            return [
                ApiConstants::$STATUS => ApiConstants::$SUCCESS,
                ApiConstants::$RESPONSE=>$returnRequest
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
            $bin = new \Application\Test\Model\Bin();
            
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
WHERE id_set = ? and id_set not in (SELECt set_id FROM order_sets WHERE state <> 'delete')
            ", [['i', (int)$params['id_set']]]);

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
                $bin->price += $this->QUERY("
SELECT price
FROM tables inner join sets on tables.id_table = sets.table_id
WHERE id_set=?",[
                    ['i',$set->set_id]
                ])[0]['price'];
                $bin->UPDATE();

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
            // Корзина
            $bin = new \Application\Test\Model\Bin();

            // Заказ
            $orderSet = new \Application\Test\Model\OrderSet();

            //Место
            $set = new \Application\Test\Model\Set();

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
                return [
                    ApiConstants::$STATUS => ApiConstants::$SUCCESS,
                    ApiConstants::$ERROR_MESSAGE => ApiConstants::$ERROR_NOT_FOUND_BIN_STRING,
                    ApiConstants::$ERROR_CODE => ApiConstants::$ERROR_NOT_FOUND_BIN_CODE
                ];
            }

            $orderSet->findBy_id_order_set($params['id_order_set']);
            if (count($orderSet->id_order_set) == 0){
                return null;
            }
            if ($orderSet->order_id == $bin->id_order){

                $set->findBy_id_set($orderSet->set_id);
                $orderSet->DELETE();
            }else{
                return [
                    ApiConstants::$STATUS => ApiConstants::$ERROR,
                    ApiConstants::$ERROR_MESSAGE => ApiConstants::$ERROR_AUTH__STRING,
                    ApiConstants::$ERROR_CODE => ApiConstants::$ERROR_AUTH_CODE
                ];
            }
            /*$this->QUERY("DELETE FROM order_sets
                              WHERE order_sets.state = ? and id_order_set in (
                                  SELECT id_order_set
                                  FROM (SELECT id_order_set FROM order_sets inner join orders on order_sets.order_id = orders.id_order
                                  WHERE user_id = ? and id_order_set = ?) as tmp
                              )
                             ",[
                ['s','add'],
                ['i',\CO::AUTH()->who()->ID()],
                ['i',$params['id_order_set']]
            ]);*/

            $countSets = $this->QUERY("SELECT price, COUNT(set_id) as count
                                              FROM orders inner join order_sets
                                              WHERE user_id = ? and orders.state = ? GROUP BY price
                                              ",[
                    ['i',\CO::AUTH()->who()->ID()],
                    ['s', 'bin']]
            );
            if (!isset($countSets[0]['count'])){
                $this->QUERY("DELETE FROM orders WHERE user_id =? and state = ?",[['i',\CO::AUTH()->who()->ID()],['s','bin']]);
            }else{

                $bin->price -= $this->QUERY("
SELECT price
FROM tables
WHERE id_table = ?
                ",[
                    ['i',$set->table_id]
                ])[0]['price'];
            }
                $bin->UPDATE();
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

    /**
     * Перводит корзину в стостояние pay
     * @param id_order
     * @return array
     */
    function pay($params){

        if (\CO::AUTH()->admin()) {
            if (!isset($params['id_order'])){
                return [
                    ApiConstants::$STATUS => ApiConstants::$ERROR,
                    ApiConstants::$ERROR_MESSAGE => ApiConstants::$ERROR_PARAMS_STRING,
                    ApiConstants::$ERROR_CODE => ApiConstants::$ERROR_PARAMS_CODE];
            }

            $bin = new \Application\Test\Model\Bin();
            // Проверяем, есть ли корзина у пользователя
            $binId = $bin->QUERY(
                "SELECT id_order
from orders
where
    id_order = ?
    and
    state = 'bin'
limit 1
            ", [
                ['i', $params['id_order']]
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
            $bin->state = 'pay';
            $bin->date_pay = $date;
            $bin->UPDATE();

            $this->QUERY("
UPDATE order_sets
SET state = 'pay'
WHERE order_id = ?",[
                ['i',$bin->ID()]
            ]);
            return [
                ApiConstants::$STATUS =>ApiConstants::$SUCCESS
            ];
        }

        if (\CO::AUTH()->unknown() || \CO::AUTH()->user()) {
            return [
                ApiConstants::$STATUS => ApiConstants::$ERROR,
                ApiConstants::$ERROR_MESSAGE => ApiConstants::$ERROR_AUTH__STRING,
                ApiConstants::$ERROR_CODE => ApiConstants::$ERROR_AUTH_CODE
            ];
        }
    }
}