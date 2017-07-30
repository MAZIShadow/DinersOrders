<?php

require_once("MySQLDBConnection.class.php");

class MenuRepository {

    public function getMenuById($clientId) {
        $db_handle = new MySQLDBConnection();
        $query = sprintf('SELECT * FROM %1$s.menu M where M.ID = ?', MySQLDBConnection::DB_NAME);
        $params = array("i", $clientId);

        return $db_handle->runPreparedQuery($query, $params);
    }

    public function getDinnersForDate($dateTime) {
        $db_handle = new MySQLDBConnection();
        $query = sprintf('SELECT M.ID id, D.ID dinner_id, D.NAME dinner_name, M.NUMBER_OF_PORTIONS number_of_portions, M.DATE FROM %1$s.menu M LEFT JOIN %1$s.dinner D ON D.ID = M.DINNER_ID WHERE M.DATE = ? GROUP BY M.ID, D.NAME', MySQLDBConnection::DB_NAME);
        $params = array("s", $dateTime);

        return $db_handle->runPreparedQuery($query, $params);
    }

    public function saveMenu($dinnerId, $menu_date, $numberOfPortions) {
        $db_handle = new MySQLDBConnection();
        $query = sprintf('INSERT INTO %1$s.menu (DINNER_ID, DATE, NUMBER_OF_PORTIONS) VALUES (?,?,?)', MySQLDBConnection::DB_NAME);
        $stmt = $db_handle->prepareQuery($query);
        $stmt->bind_param("isi", $dinnerId, $menu_date, $numberOfPortions);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            $db_handle->commit();
        } else {
            $db_handle->rollback();
        }

        return $result;
    }

    public function updateMenu($dinnerId, $menu_date, $numberOfPortions, $menuId) {
        $db_handle = new MySQLDBConnection();
        $query = sprintf('UPDATE %1$s.menu SET DINNER_ID = ?, DATE = ?, NUMBER_OF_PORTIONS = ? WHERE ID = ?', MySQLDBConnection::DB_NAME);
        $stmt = $db_handle->prepareQuery($query);
        $stmt->bind_param("isii", $dinnerId, $menu_date, $numberOfPortions, $menuId);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            $db_handle->commit();
        } else {
            $db_handle->rollback();
        }

        return $result;
    }

    public function deleteMenu($menuId) {
        $db_handle = new MySQLDBConnection();
        $query = sprintf('DELETE FROM %1$s.menu WHERE ID = ?', MySQLDBConnection::DB_NAME);
        $stmt = $db_handle->prepareQuery($query);
        $stmt->bind_param("i", $menuId);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            $db_handle->commit();
        } else {
            $db_handle->rollback();
        }

        return $result;
    }

}

?>