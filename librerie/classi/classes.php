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
require_once 'model/Giorno.php';
require_once 'model/TipologiaPasto.php';
require_once 'model/Pasto.php';
require_once 'model/GiornoAgenda.php';
require_once 'model/IngredienteAgenda.php';

//CLASSI DAO
require_once 'DAO/ObjectDAO.php';
require_once 'DAO/IngredienteDAO.php';
require_once 'DAO/PreparazioneDAO.php';
require_once 'DAO/TipologiaDAO.php';
require_once 'DAO/RicettaDAO.php';
require_once 'DAO/IngredienteRicettaDAO.php';
require_once 'DAO/AgendaDAO.php';
require_once 'DAO/GiornoDAO.php';
require_once 'DAO/TipologiaPastoDAO.php';
require_once 'DAO/PastoDAO.php';
require_once 'DAO/GiornoPastoDAO.php';
require_once 'DAO/PastoRicettaDAO.php';

//CLASSI CONTROLLER
require_once 'controller/IngredienteController.php';
require_once 'controller/RicettaController.php';
require_once 'controller/AgendaController.php';
require_once 'controller/TipologiaPastoController.php';
require_once 'controller/PdfController.php';

//CLASSI VIEW
require_once 'view/PrinterView.php';
require_once 'view/IngredienteView.php';
require_once 'view/TipologiaPastoView.php';
require_once 'view/RicettaView.php';
require_once 'view/AgendaView.php';


?>