<?php

require_once __DIR__ . '/Model.php';

class Mediacament extends Model {
    protected $table = 'Medicaments';
    protected $primaryKey = 'id_medicament';

    public function search($term) {
        $sql = "SELECT * FROM Medicaments WHERE nom_commercial LIKE :term ORDER BY nom_commercial";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':term' => "%$term%"]);
        return $stmt->fetchAll();
    }

    public function getPlusPrescripts($limit = 10) {
        $sql = "SELECT m.nom_commercial, COUNT(*) as total_prescriptions
                FROM Medicaments m
                JOIN Prescription_mediacament pm ON m.id_mediacament = pm.id_medicament
                GROUP BY m.id_medicament
                ORDER BY total_presciptions DESC
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}