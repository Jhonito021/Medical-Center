<?php

require_once __DIR__ . '/../models/Consultation.php';
require_once __DIR__ . '/../models/Patient.php';
require_once __DIR__ . '/../models/Medecin.php';

class ConsulationController {
    private $consultaionModel;
    private $patientModel;
    private $medecinModel;

    public function _construct() {
        $this->consultationModel = new Consultation();
        $this->patientModel = new Patient();
        $this->medecinModel = new Medecin();
    }

    public function index() {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        $consultaions = $this->consultationModel->getWithDetails($limit, $offset);
        $total = $this->consultationModel->count();
        $totalPages = (int)ceil($total / $limit);

        require_once __DIR__ . '/../views/consultations/index.php';
    }

    public function show ($id) {
        $consultaion = $this->consultationModel->getById($id);

        if (!$consultaion) {
            header('Location: /consultaions');
            exit;
        }

        $prescription = $this->consultationModel->getPrescriptions($id);

        require_once __DIR__ . '/../views/consultations/show.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id_patient' => $_POST['id_patient'],
                'id_medecin' => $_POST['id_medecin'],
                'date_consultation' => $_POST['fate_consultation'],
                'diagnostic' => $_POST['diagnostic']
            ];

            $id = $this->consultationModel->create($data);
            if ($id) {
                header('Locatoin: /consultaions/' . $id . '?succes=1');
                exit;
            }
        }

        $patients = $this->patienModel->findAll();
        $medecins = $this->medecinModel->findAll();

        require_once __DIR__ . '/../views/consultations/create.php';
    }
}