<?php
class bin extends apiBaseClass{
    function get(){
        $retJson = $this->createJson();
        if(CO::AUTH()->user()){
            $returnRequest = CO::SQL()->query("SELECT price, COUNT(set_id) as count
                                              FROM orders inner join order_sets
                                              WHERE user_id = ? and orders.state = ? GROUP BY price
                                              ",[['i',CO::AUTH()->who()['id_user']],['s', 'bin']]);
            if (empty($returnRequest)) return null;
            $retJson = $this->fillJson($returnRequest, $retJson);
        }
        if(CO::AUTH()->unknown()){
            $status =ApiConstants::$STATUS;
            $retJson->$status = ApiConstants::$ERROR_AUTH;
        }

        return $retJson;
    }

    function getList(){
        $retJson = $this->createJson();
        if (CO::AUTH()->user()) {
            $returnRequest = CO::SQL()->query("SELECT
                                                order_sets.id_order_set, id_table, CONCAT(tables.position,';',sets.position) as position , tables.price
                                                FROM
                                                order_sets inner join orders on orders.id_order = order_sets.order_id
                                                inner join sets on sets.id_set = order_sets.set_id
                                                inner join tables on tables.id_table = sets.table_id
                                                WHERE orders.user_id = ? and orders.state =?
                                              ", [['i', CO::AUTH()->who()['id_user']], ['s', 'bin']]);
            if (empty($returnRequest)) return null;
            $retJson = $this->fillJson($returnRequest, $retJson);
        }
        if (CO::AUTH()->unknown()) {
            $status = ApiConstants::$STATUS;
            $retJson->$status = ApiConstants::$ERROR_AUTH;
        }
        return $retJson;
    }

    function add($params){
        $retJson = $this->createJson();
        $status = ApiConstants::$STATUS;
        if (!isset($params->id_set)){

            $retJson->$status = ApiConstants::$ERROR_PARAMS;
            return $retJson;
        }
        if (CO::AUTH()->user()) {
            $bin = CO::SQL()->query("SELECT *
                                    FROM orders
                                    WHERE user_id =?
                                    AND state = ?
                                      ", [['i', CO::AUTH()->who()['id_user']], ['s', 'bin']]);
            if (count($bin) == 0){
                CO::SQL()->query("INSERT INTO orders
                                    (user_id,state, price)
                                    VALUES
                                    (?,?,?)
                                      ", [['i', CO::AUTH()->who()['id_user']], ['s', 'bin'],['i',0]]);

                $bin = CO::SQL()->query("SELECT *
                                    FROM orders
                                    WHERE user_id =?
                                    AND state = ?
                                      ", [['i', CO::AUTH()->who()['id_user']], ['s', 'bin']]);
            }
            $max = CO::SQL()->query("SELECT MAX(sort_id)+1 as m FROM order_sets");
            CO::SQL()->query("INSERT INTO order_sets
                                    (order_id,set_id, sort_id,state)
                                    VALUES
                                    (?,?,?,?)
                                      ",['i',$bin['id_order'],['i',$params->id_set],['i',$max[0]['m']],['s','bin']]);

            CO::SQL()->query("UPDATE orders
                            SET price = price + (SELECT price
                                                  FROM tables inner join sets on tables.id_table = sets.table_id
                                                  WHERE id_set = ?)
                            WHERE id_order = ?
                            ",[['i',$params->id_set],['i',$bin['id_order']]]);
            $retJson->$status = ApiConstants::$ERROR_NO;
        }
        if (CO::AUTH()->unknown()) {
            $status = ApiConstants::$STATUS;
            $retJson->$status = ApiConstants::$ERROR_AUTH;
        }
        return $retJson;
    }

    function remove($params){
        $retJson = $this->createJson();
        $status = ApiConstants::$STATUS;
        return $retJson;
    }

    function pay($params){

    }
}