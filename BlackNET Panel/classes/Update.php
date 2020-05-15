<?php
class Update extends Database
{
    public function create_table($table_name, $arrays)
    {
        $table_syntex = sprintf("CREATE TABLE IF NOT EXISTS %s ( ", $table_name);
        if (!(empty($arrays))) {
            $column = "";
            foreach ($arrays as $array) {
                foreach ($array as $value) {
                    $column = $column . $value . " ";
                }
                $column = $column . " " . ",";
            }
        }

        $final = $table_syntex . rtrim($column, ",") . ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci " . ";";
        return $final;
    }

    public function is_primary($table_name, $column_name)
    {
        return sprintf("ALTER TABLE %s ADD PRIMARY KEY (%s);", $table_name, $column_name);
    }

    public function is_autoinc($table_name, $column_array)
    {
        return sprintf("ALTER TABLE %s MODIFY %s AUTO_INCREMENT;", $table_name, implode($column_array, " "));
    }

    public function create_column($table_name, $array, $after)
    {
        $column_syntex = "ALTER TABLE  $table_name ADD ";
        if (!(empty($array))) {
            $column = "";
            foreach ($array as $value) {
                $column = $column . $value . " ";
            }
        }
        $final = $column_syntex . $column . " AFTER  $after;";
        return $final;
    }

    public function update_value($table_name, $column_name, $value)
    {
        $sql = "UPDATE " . $table_name . " SET " . $column_name . " = '" . $value . "'";
        return $sql;
    }

    public function insert_value($table_name, $columns_array)
    {
        $sql = sprintf("INSERT INTO %s (%s) values (%s)", $table_name, implode(", ", array_keys($columns_array)), "'" . implode("', '", array_values($columns_array)) . "'");
        return $sql;
    }

    public function table_exist($table_name)
    {
        return "SHOW TABLES LIKE " . $table_name;
    }

    public function column_exist($table_name, $column_name)
    {
        return "SHOW COLUMNS FROM $table_name LIKE $column_name";
    }

    public function drop_table($table_name)
    {
        return "DROP TABLE " . $table_name . ";";
    }

    public function drop_column($table_name, $column_name)
    {
        return "ALTER TABLE " . $table_name . " DROP COLUMN " . $column_name . ";";
    }

    public function execute($sql)
    {
        try {
            $pdo = $this->Connect();
            $stmt = $pdo->prepare($sql);
            $status = $stmt->execute();
            if (strpos($sql, "SHOW") !== false) {
                if ($stmt->rowCount()) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return $status;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
