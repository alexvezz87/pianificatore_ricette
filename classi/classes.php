<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

//CLASSI MODEL
require_once 'model/Ingrediente.php';
require_once 'model/Preparazione.php';
require_once 'model/Tipologia.php';
require_once 'model/Ricetta.php';
require_once 'model/IngredienteRicetta.php';
require_once 'model/Agenda.php';

//CLASSI DAO
require_once 'DAO/ObjectDAO.php';
require_once 'DAO/IngredienteDAO.php';
require_once 'DAO/PreparazioneDAO.php';
require_once 'DAO/TipologiaDAO.php';
require_once 'DAO/RicettaDAO.php';
require_once 'DAO/IngredienteRicettaDAO.php';

//CLASSI CONTROLLER
require_once 'controller/IngredienteController.php';
require_once 'controller/RicettaController.php';

//CLASSI VIEW

?>