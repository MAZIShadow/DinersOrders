<?php

require_once("MySQLDBConnection.class.php");
require_once("Consts.php");

class OrderRepository {

    public function getOrderByClientNameAndMenuIdAndBetweenDates($clientName, $menuId, $startDate, $endDate) {
        $db_handle = new MySQLDBConnection();
        $query = sprintf('SELECT O.* FROM %1$s.order O LEFT JOIN %1$s.client C ON O.CLIENT_ID = C.ID WHERE O.MENU_ID = ? AND C.NAME = ? AND O.DATE BETWEEN ? AND ?', MySQLDBConnection::DB_NAME);
        $params = array("isss", $menuId, $clientName, $startDate, $endDate);

        return $db_handle->runPreparedQuery($query, $params);
    }

    public function saveOrder($userName, $menuId, $mealAmount, $clientId) {
        $db_handle = new MySQLDBConnection();
        $query = sprintf('UPDATE %1$s.menu SET NUMBER_OF_ORDERS_LEFT = NUMBER_OF_ORDERS_LEFT - ? WHERE ID = ? AND NUMBER_OF_ORDERS_LEFT - ? >= 0', MySQLDBConnection::DB_NAME);
        $stmt = $db_handle->prepareQuery($query);
        $stmt->bind_param("iii", $mealAmount, $menuId, $mealAmount);
        $result = $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $stmt->close();
            $query = sprintf('INSERT INTO %1$s.order (NAME, DATE, AMOUNT, MENU_ID, CLIENT_ID) VALUES (?,?,?,?,?)', MySQLDBConnection::DB_NAME);
            $stmt = $db_handle->prepareQuery($query);
            $mealdate = date(Consts::FULL_DATE_FORMAT);
            $stmt->bind_param("ssiii", $userName, $mealdate, $mealAmount, $menuId, $clientId);
            $result = $stmt->execute();

            if ($result) {
                $db_handle->commit();
                $stmt->close();
                return true;
            } else {
                $db_handle->rollback();
            }
        } else {
            $db_handle->rollback();
        }
        
        $stmt->close();

        return false;
    }
}

?>
