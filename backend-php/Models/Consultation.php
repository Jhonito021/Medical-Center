<?php
require_once __DIR__ . '/Model.php';

class Consultation extends Model {
    protected $table = 'Consultations';
    protected $primaryKey = 'id_consultation';

    public function getWithDetails($limit = null, $offset = 0) {
        $sql = "SELECT c.*, p.nom as patient_nom, m.nom as medecin_nom, m.specialite 
                FROM Consultations c 
                JOIN Patients p ON c.id_patient = p.id_patient 
                JOIN Medecins m ON c.id_medecin = m.id_medecin 
                ORDER BY c.date_consultation DESC";
        
        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        
        return $this->db->query($sql)->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT c.*, p.nom as patient_nom, p.date_naissance, p.groupe_sanguin,
                m.nom as medecin_nom, m.specialite 
                FROM Consultations c 
                JOIN Patients p ON c.id_patient = p.id_patient 
                JOIN Medecins m ON c.id_medecin = m.id_medecin 
                WHERE c.id_consultation = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO Consultations (id_patient, id_medecin, date_consultation, diagnostic) 
                VALUES (:id_patient, :id_medecin, :date_consultation, :diagnostic)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_patient' => $data['id_patient'],
            ':id_medecin' => $data['id_medecin'],
            ':date_consultation' => $data['date_consultation'],
            ':diagnostic' => $data['diagnostic']
        ]);
        return $this->db->lastInsertId();
    }

    public function getDiagnosticsStats() {
        $sql = "SELECT diagnostic, COUNT(*) as total FROM Consultations 
                GROUP BY diagnostic ORDER BY total DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function getPrescriptions($id_consultation) {
        $sql = "SELECT p.*, m.nom_commercial, pm.dosage, pm.duree 
                FROM Prescriptions p 
                JOIN Prescription_medicament pm ON p.id_prescription = pm.id_prescription 
                JOIN Medicaments m ON pm.id_medicament = m.id_medicament 
                WHERE p.id_consultation = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id_consultation]);
        return $stmt->fetchAll();
    }
}