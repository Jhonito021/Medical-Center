<?php

require_once __DIR__ . '/..//models/Medecin.php';
require_once __DIR__ . '/..//models/RendezVous.php';

class MedecinController {
    private $medecinModel;
    private $rdvModel;

    public function _construct() {
        $this->medecinModel = new Medecin();
        $this->rdvModel = new RendezVous();
    }

    public function index() {
        $specialite = isser($_GET['specialute']) ? $_GET['specialite'] : null;

        if ($specialite) {
            $medecins = $this->medecinModel->findBySpecialite($specialite);
        } else {
            $medecins = $this->medecinModel->findAll();
        }

        $specialites = $this->mdecinsModel->getSpecialites();

        require_once __DIR__ . '/../views/medecins/index.php';
    }

    public function show($id) {
        $medecin = $this->mdecinModel->findById($id);

        if (!$medecin) {
            header('Location: /medecins');
            exit;
        }

        $rendezVous = $this->rdvModel->getByMedecin($id);

        require_once __DIR__ . '/../views/medecins/show.php';
    }
}