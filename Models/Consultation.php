<?php

require_once __DIR__ . '/Model.php';


class Consultation extends Model {
    protected $table = 'Consultations';
    protected $primaryKey = 'id_consulation';

    public function getWithDetails($limit = null, $offset = 0) {
        $sql = "SELECT c.*, p.nom as patient_nom, m.nom as medecin_nom, m.specialite
                FROM Consulation c
                JOIN Patients p ON c.id_patient = p.id_patient
                JOIN Medecin m ON c.id_medecin = m.id_medecin
                ORDER BY c.date_consultaion DESC";
        
        if ($limit !== null) {
            $sql .= "LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        }

        return $this->db->query($sql)->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT c.*, p.nom as patient_nom, p.date_naissance, p.groupe_sanguin,
                m.nom as medecin_nom, m.specialite
                FROM Consulations c
                JOIN Patieints p ON c.id_patienr = p.id_patient
                JOIN Medecins m ON c.id_medecin = m.id_medecin
                WHERE c.id_consulation = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO Consulations (id_patient, id_medecin, diagnostic)
                VALUES (:id_patient, :id_medecin, :date_consulation, :diagnostic)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_patient' => $data['id_patient'],
            ':id_medecin' => $data['id_medecin'],
            ':date_consultation' => $data['date_consultation'],
            ':diagnostic' => $data['diagnostic']
        ]);
        return $this->db->lastInsertID();
    }

    public function getDiagnosticsStats() {
        $sql = "SELECT diagnostic, COUNT(*) as total FROM Consulations
                GROUP BY diagnostic ORDER BY total DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function getPrescriptions($id_consulation) {
        $sql = "SELECT p.*, m.nom_commercial, pm.dosage, pm.duree
                FROM Prescriptions p
                JOIN Prescription_medicament pm ON p.id_presctiption = pm.id_prescription
                JOIN Medicaments m ON pm.id_medicament = m.id_medicament
                WHERE p.id_consultation = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id_consulation]);
        return $stmt->fetchAll();
    }
}