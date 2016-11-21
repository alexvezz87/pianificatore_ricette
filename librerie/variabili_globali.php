<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

//TABELLE DATABASE
global $DB_PREFIX;
global $DB_TABLE_INGREDIENTI, $DB_TABLE_PREPARAZIONI, $DB_TABLE_RICETTE, $DB_TABLE_TIPOLOGIA_RICETTE, $DB_TABLE_INGREDIENTI_RICETTE;
global $DB_TABLE_AGENDE, $DB_TABLE_GIORNI, $DB_TABLE_PASTI, $DB_TABLE_GIORNI_PASTI, $DB_TABLE_PASTI_RICETTE, $DB_TABLE_TIPOLOGIA_PASTI;

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
global $FORM_R_NOME, $FORM_R_TIPOLOGIA, $FORM_R_INGREDIENTE, $FORM_R_QT_INGREDIENTE, $FORM_R_UM_INGREDIENTE, $FORM_R_PREPARAZIONE, $FORM_R_DURATA, $FORM_R_SUBMIT, $FORM_R_DOSE, $FORM_R_FOTO;
global $LABEL_R_NOME, $LABEL_R_TIPOLOGIA, $LABEL_R_INGREDIENTE, $LABEL_R_QT_INGREDIENTE, $LABEL_R_UM_INGREDIENTE, $LABEL_R_PREPARAZIONE, $LABEL_R_DURATA, $LABEL_R_DOSE, $LABEL_R_FOTO;


global $LABEL_SUBMIT;

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


$LABEL_SUBMIT = 'Salva';

?>