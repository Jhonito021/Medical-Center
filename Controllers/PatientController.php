<?php

require_once __DIR__ . '/../models/Patient.php';
require_once __DIR__ . '/../models/Consultation.php';
require_once __DIR__ . '/../models/RendezVous.php';

class PatientController {
    private $patientMode;
    private $consultationModel;
    private $rdvModel;

    public function _construct() {
        $this->patientModel =new Patient();
        $this->consultationModel = new Consultation();
        $this->rdvModel = new RendeVous();
    }

    public function index() {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 20;
        $offset = ($page - 1) * limit;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        if ($search) {
            $patients = $this->patientModel->search($search);
            $total = count($patients);
            $totalPages = (int)ceil($total / $limit);
        }

        require_once __DIR__ . '/../views/patients/index.php';
    }

    public function show($id) {
        $patient = $this->patientModel->findById($id);

        if (!$patient) {
            header('Location: /patients');
            exit;
        }

        $consultations = $this->patientModel->getConsultations($id);
        $rendezVous = $this->rdvModel->getBtPatienr($id);

        require_once __DIR__ . '/../views/patients/show.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'nom' => $_POST['nom'],
                'date_naissance' => $_POST['date_naissance'],
                'groupe_sanguin' => $_POST['goupe_sanguin']
            ];

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = [
                    'nom' => $_POST['nom'],
                    'data_naissance' => $_POST['date_naissance'],
                    'groupe_sanguin' => $_POST['groupe_sanguin']
                ];

                if ($this->patientModel->update($id, $data)) {
                    header('Location: /patients/' . $id . '?succes=1');
                    exit;
                }
            }

            require_once __DIR__ . '/../views/patienrs/edit.php';
        }
    }
    public function delete($id) {
        if ($this->patientModel->delete($id)) {
            header('Location: /patients?deleted=1');
        }
        exit;
    }
}