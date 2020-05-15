<?php
class Settings extends Database
{
    public function getSettings($id)
    {
        $pdo = $this->Connect();
        $sql = "SELECT * FROM settings WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $data = $stmt->fetch();
        return $data;
    }

    public function updateSettings($id, $recaptchaprivate, $recaptchapublic, $recaptchastatus, $panel_status)
    {
        $pdo = $this->Connect();
        $sql = "UPDATE settings SET
        recaptchaprivate = :private,
        recaptchapublic = :public,
        recaptchastatus = :status,
        panel_status = :pstatus
        WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            "private" => $recaptchaprivate,
            "public" => $recaptchapublic,
            "status" => $recaptchastatus,
            "pstatus" => $panel_status,
            "id" => $id
        ]);
        return 'Settings Updated';
    }
}
