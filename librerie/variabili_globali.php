<?php

//Autore: Alex Vezzelli - Alex Soluzioni Web
//url: http://www.alexsoluzioniweb.it/

//TABELLE DATABASE
global $DB_PREFIX;
global $DB_TABLE_INGREDIENTI, $DB_TABLE_PREPARAZIONI, $DB_TABLE_RICETTE, $DB_TABLE_TIPOLOGIA_RICETTE, $DB_TABLE_INGREDIENTI_RICETTE;
global $DB_TABLE_AGENDE, $DB_TABLE_GIORNI, $DB_TABLE_PASTI, $DB_TABLE_GIORNI_PASTI, $DB_TABLE_PASTI_RICETTE, $DB_TABLE_TIPOLOGIA_PASTI;
global $FORM_ING_NOME, $FORM_ING_GIORNI_ANTICIPO, $FORM_ING_DESCRIZIONE, $FORM_ING_SUBMIT;
global $LABEL_ING_NOME, $LABEL_ING_GIORNI_ANTICIPO, $LABEL_ING_DESCRIZIONE;


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
$FORM_ING_NOME = 'ingrediente-nome';
$FORM_ING_GIORNI_ANTICIPO = 'ingrediente-anticipo';
$FORM_ING_DESCRIZIONE = 'ingrediente-descrizione';
$FORM_ING_SUBMIT = 'ingrediente-salva';

$LABEL_ING_NOME = 'Nome ingrediente';
$LABEL_ING_GIORNI_ANTICIPO = 'Giorni di anticipo';
$LABEL_ING_DESCRIZIONE = 'Descrizione';

$LABEL_SUBMIT = 'Salva';

?>