<?php

require_once __DIR__ . '/Model.php';

class RendezVous extends Model {
    protected $table = 'Rendez_vous';
    protected $primaryKey = 'id_patient';

    public function getUpcoming($limit = 10) {
        $sql = "SELECT r.*, p.nom as patient_nom, m.nom as medecin_nom, m.specialite
                FROM Rendez_vous r
                JOIN Patients p ON r.id_patient = p.id_patient
                JOIN Medecins m ON r.id_medecin = m.id_medecin
                WHERE r.date_rendez_vous >= CURDATE()
                ORDER BY r.date_rendez_vous ASC
                LIMIT  :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByPatient($id_patient) {
        $sql = "SELECT r.*, m.nom as medecin_nom, m_specialite
                FROM Rendez_vous r
                JOIN Medecins m ON r.id_medecin = m.id_medecin
                WHERE r.id_patient = :id
                ORDER BY r.date_rendez_vous DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id_patient]);
        return $stmt->fetchAll();
    }

    public function getByMedecin($id_medecin) {
        $sql = "SELECT r.*, p.nom as patient_nom
                FROM Rendez_vous r
                JOIN Patients p ON r.id_patient = p.id_patient
                WHERE r.id_medecin = :id
                ORDER BY r.date_rendez_vous DESC";
        $stmt = $this->db-prepare($sql);
        $stmt->execute([':id' => $id_medecin]);
        return $stmt->fetchAll();
    }
}