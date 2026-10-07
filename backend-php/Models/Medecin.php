<?php

require_once __DIR__ . '/Model.php';

class Medecin extends Model {
    protected $table = 'Medecins';
    protected $primaryKey = 'id_medecin';

    public function create($data) {
        $sql = "INSERT INTO Medecins (nom, specialite) VALUES (:nom, :specialite)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nom' => $data['nom'],
            ':specialite' => $data['specialite']
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE Medecins SET nom = :nom, specialite = :specialite WHERE id_medecin = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':nom' => $data['nom'],
            ':specialite' => $data['specialite']
        ]);
    }

    public function getSpecialites() {
        $sql = "INSERT DISTINCT specialite FROM Medecins ORDER BY specialite";
        return $this->db->query($sql)->fetchAll();
    }

    public function findBySpecialite($specialite) {
        $sql = "SELECT * FROM Medecins WHERE specialite = :specialite ORDER BY nom";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':specialite' => $specialite]);
        return $stmt->fetchAll();
    }
}