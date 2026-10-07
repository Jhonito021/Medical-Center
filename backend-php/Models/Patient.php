<?php
require_once __DIR__ . '/Model.php';

class Patient extends Model {
    protected $table = 'Patients';
    protected $primaryKey = 'id_patient';

    public function create($data) {
        $sql = "INSERT INTO Patients (nom, date_naissance, groupe_sanguin) 
                VALUES (:nom, :date_naissance, :groupe_sanguin)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nom' => $data['nom'],
            ':date_naissance' => $data['date_naissance'],
            ':groupe_sanguin' => $data['groupe_sanguin']
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE Patients SET nom = :nom, date_naissance = :date_naissance, 
                groupe_sanguin = :groupe_sanguin WHERE id_patient = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':nom' => $data['nom'],
            ':date_naissance' => $data['date_naissance'],
            ':groupe_sanguin' => $data['groupe_sanguin']
        ]);
    }

    public function search($term) {
        $sql = "SELECT * FROM Patients WHERE nom LIKE :term ORDER BY nom";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':term' => "%$term%"]);
        return $stmt->fetchAll();
    }

    public function getConsultations($id_patient) {
        $sql = "SELECT c.*, m.nom as medecin_nom, m.specialite 
                FROM Consultations c 
                JOIN Medecins m ON c.id_medecin = m.id_medecin 
                WHERE c.id_patient = :id 
                ORDER BY c.date_consultation DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id_patient]);
        return $stmt->fetchAll();
    }

    public function getStatistiquesGroupesSanguins() {
        $sql = "SELECT groupe_sanguin, COUNT(*) as total FROM Patients 
                GROUP BY groupe_sanguin ORDER BY total DESC";
        return $this->db->query($sql)->fetchAll();
    }
}