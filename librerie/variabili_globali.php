<?php
namespace pianificatore_ricette;
//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

//TABELLE DATABASE
global $DB_PREFIX;
global $DB_TABLE_INGREDIENTI, $DB_TABLE_PREPARAZIONI, $DB_TABLE_RICETTE, $DB_TABLE_TIPOLOGIA_RICETTE, $DB_TABLE_INGREDIENTI_RICETTE;
global $DB_TABLE_AGENDE, $DB_TABLE_GIORNI, $DB_TABLE_PASTI, $DB_TABLE_GIORNI_PASTI, $DB_TABLE_PASTI_RICETTE, $DB_TABLE_TIPOLOGIA_PASTI;
global $DB_TABLE_RICETTE_TIPOLOGIE, $DB_TABLE_PREFERITE, $DB_TABLE_TEMPLATE_AGENDE;

//INGREDIENTI
global $FORM_ING_NOME, $FORM_ING_GIORNI_ANTICIPO, $FORM_ING_DESCRIZIONE, $FORM_ING_SUBMIT;
global $LABEL_ING_NOME, $LABEL_ING_GIORNI_ANTICIPO, $LABEL_ING_DESCRIZIONE;

//TIPOLOGIA PASTO
global $FORM_TP_NOME, $FORM_TP_DESCRIZIONE, $FORM_TP_SUBMIT;
global $LABEL_TP_NOME, $LABEL_TP_DESCRIZIONE;

//TIPOLOGIA RICETTA
global $FORM_TR_NOME, $FORM_TR_DESCRIZIONE, $FORM_TR_SUBMIT;
global $LABEL_TR_NOME, $LABEL_TR_DESCRIZIONE;

//RICETTA
global $FORM_R_NOME, $FORM_R_TIPOLOGIA, $FORM_R_INGREDIENTE, $FORM_R_QT_INGREDIENTE, $FORM_R_UM_INGREDIENTE, $FORM_R_PREPARAZIONE, $FORM_R_DURATA, $FORM_R_SUBMIT, $FORM_R_DOSE, $FORM_R_FOTO, $FORM_R_APPROVATA;
global $LABEL_R_NOME, $LABEL_R_TIPOLOGIA, $LABEL_R_INGREDIENTE, $LABEL_R_QT_INGREDIENTE, $LABEL_R_UM_INGREDIENTE, $LABEL_R_PREPARAZIONE, $LABEL_R_DURATA, $LABEL_R_DOSE, $LABEL_R_FOTO, $LABEL_R_APPROVATA;

//AGENDA
global $FORM_A_SETTIMANA, $FORM_A_SUBMIT,$FORM_A_NOME;
global $LABEL_A_NOME;

//GIORNO
global $FORM_G_NOME, $FORM_G_DATA;
global $LABEL_G_NOME;

//TEMPLATE AGENDA
global $FORM_TA_NOME, $FORM_TA_DESCRIZIONE, $FORM_TA_IDAGENDA, $FORM_TA_INIZIO, $FORM_TA_FINE;
global $LABEL_TA_NOME, $LABEL_TA_DESCRIZIONE, $LABEL_TA_IDAGENDA, $LABEL_TA_INIZIO, $LABEL_TA_FINE;

global $LABEL_SUBMIT;

//URL E PATH
global $PR_URL_PDF, $PR_URL_IMG;
global $IMG_NOT_FOUND;

//ADMIN
global $ADMIN_ID;


//DATABASE
$DB_PREFIX = 'pr_';

$DB_TABLE_INGREDIENTI = 'ingredienti';
$DB_TABLE_PREPARAZIONI = 'preparazioni';
$DB_TABLE_RICETTE = 'ricette';
$DB_TABLE_TIPOLOGIA_RICETTE = 'tipologia_ricette';
$DB_TABLE_INGREDIENTI_RICETTE = 'ingredienti_ricette';
$DB_TABLE_AGENDE = 'agende';
$DB_TABLE_GIORNI = 'giorni';
$DB_TABLE_TIPOLOGIA_PASTI = 'tipologia_pasti';
$DB_TABLE_PASTI = 'pasti';
$DB_TABLE_GIORNI_PASTI = 'giorni_pasti';
$DB_TABLE_PASTI_RICETTE = 'pasti_ricette';
$DB_TABLE_RICETTE_TIPOLOGIE = 'ricette_tipologie';
$DB_TABLE_PREFERITE = 'preferite';
$DB_TABLE_TEMPLATE_AGENDE = 'template_agende';

//FORM & LABEL

//ingredienti
$FORM_ING_NOME = 'ingrediente-nome';
$FORM_ING_GIORNI_ANTICIPO = 'ingrediente-anticipo';
$FORM_ING_DESCRIZIONE = 'ingrediente-descrizione';
$FORM_ING_SUBMIT = 'ingrediente-salva';

$LABEL_ING_NOME = 'Nome ingrediente';
$LABEL_ING_GIORNI_ANTICIPO = 'Giorni di anticipo';
$LABEL_ING_DESCRIZIONE = 'Descrizione';


//tipologia pasto
$FORM_TP_NOME = 'tp-nome';
$FORM_TP_DESCRIZIONE = 'tp-descrizione';
$FORM_TP_SUBMIT = 'tp-salva';

$LABEL_TP_NOME = 'Nome';
$LABEL_TP_DESCRIZIONE = 'Descrizione';


//tipologia ricetta
$FORM_TR_NOME = 'tr-nome';
$FORM_TR_DESCRIZIONE = 'tr-descrizione';
$FORM_TR_SUBMIT = 'tr-salva';

$LABEL_TR_NOME = 'Nome';
$LABEL_TR_DESCRIZIONE = 'Descrizione';


//ricetta
$FORM_R_NOME = 'r-nome';
$FORM_R_TIPOLOGIA = 'r-tipologia';
$FORM_R_INGREDIENTE = 'r-ingrediente-nome';
$FORM_R_QT_INGREDIENTE = 'r-ingrediente-qt';
$FORM_R_UM_INGREDIENTE = 'r-ingrediente-um';
$FORM_R_PREPARAZIONE = 'r-preparazione';
$FORM_R_DURATA = 'r-durata';
$FORM_R_DOSE = 'r-dose';
$FORM_R_FOTO = 'r-foto';
$FORM_R_APPROVATA = 'r-approvata';
$FORM_R_SUBMIT = 'r-salva';

$LABEL_R_NOME = 'Nome ricetta';
$LABEL_R_TIPOLOGIA = 'Tipologia';
$LABEL_R_INGREDIENTE = 'Ingrediente';
$LABEL_R_QT_INGREDIENTE = 'Quantità';
$LABEL_R_UM_INGREDIENTE = 'Unità di misura';
$LABEL_R_PREPARAZIONE = 'Preparazione';
$LABEL_R_DURATA = 'Durata (min)';
$LABEL_R_DOSE = 'Dose (per persona)';
$LABEL_R_FOTO = 'Foto';
$LABEL_R_APPROVATA = 'Approvata';

//agenda
$FORM_A_SUBMIT = 'a-submit';
$FORM_A_NOME = 'a-nome';

$LABEL_A_NOME = 'Nome (facoltativo)';

//giorno
$FORM_G_NOME = 'g-nome';
$FORM_G_DATA = 'g-data';


//template agenda
$FORM_TA_NOME = 'ta-nome';
$FORM_TA_DESCRIZIONE = 'ta-descrizione';
$FORM_TA_IDAGENDA = 'ta-idagenda';
$FORM_TA_SUBMIT = 'ta-submit';
$FORM_TA_INIZIO = 'ta-inizio';
$FORM_TA_FINE = 'ta-fine';

$LABEL_TA_NOME = 'Nome';
$LABEL_TA_DESCRIZIONE = 'Descrizione';
$LABEL_TA_IDAGENDA = 'Agenda';
$LABEL_TA_INIZIO = 'Mese inzio';
$LABEL_TA_FINE = 'Mese fine';

$LABEL_SUBMIT = 'Salva';


//URL E PATH
$PR_URL_PDF = plugins_url().'/pianificatore_ricette/pdf/';
$PR_URL_IMG = plugins_url().'/pianificatore_ricette/images/';
$ADMIN_ID = 1;

$IMG_NOT_FOUND = $PR_URL_IMG.'no-image-found.gif';

?>