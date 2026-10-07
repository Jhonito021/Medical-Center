<?php
require_once __DIR__ . '/../models/Patient.php';
require_once __DIR__ . '/../models/Medecin.php';
require_once __DIR__ . '/../models/Consultation.php';
require_once __DIR__ . '/../models/RendezVous.php';
require_once __DIR__ . '/../models/Medicament.php';

class HomeController {
    public function index() {
        $patientModel = new Patient();
        $medecinModel = new Medecin();
        $consultationModel = new Consultation();
        $medicamentModel = new Medicament();
        $rdvModel = new RendezVous();

        $stats = [
            'total_patients' => $patientModel->count(),
            'total_medecins' => $medecinModel->count(),
            'total_consultations' => $consultationModel->count(),
            'consultations_recentes' => $consultationModel->getWithDetails(5),
            'diagnostics_stats' => $consultationModel->getDiagnosticsStats(),
            'medicaments_populaires' => $medicamentModel->getPlusPrescrits(5),
            'prochains_rdv' => $rdvModel->getUpcoming(5),
            'groupes_sanguins' => $patientModel->getStatistiquesGroupesSanguins()
        ];

        require_once __DIR__ . '/../views/home/index.php';
    }
}