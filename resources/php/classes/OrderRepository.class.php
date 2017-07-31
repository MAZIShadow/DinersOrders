<?php

require_once("MySQLDBConnection.class.php");

class OrderRepository {

    public function getOrderByClientNameAndMenuIdAndBetweenDates($clientName, $menuId, $startDate, $endDate) {
        $db_handle = new MySQLDBConnection();
        $query = sprintf('SELECT O.* FROM %1$s.order O LEFT JOIN %1$s.client C ON O.CLIENT_ID = C.ID WHERE O.MENU_ID = ? AND C.NAME = ? AND O.DATE BETWEEN ? AND ?', MySQLDBConnection::DB_NAME);
        $params = array("isss", $menuId, $clientName, $startDate, $endDate);

        return $db_handle->runPreparedQuery($query, $params);
    }

}

?>
