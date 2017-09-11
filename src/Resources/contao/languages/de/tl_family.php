<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */

/**
 * Jmedia/FamilyBundle
 *
 * @author Johannes Cram <johannes@jonesmedia.de>
 * @package FamilyBundle
 * @license GPL-3.0+
 */

 /**
  * Operations
  */
 $GLOBALS['TL_LANG']['tl_family']['edit'] = array('Bearbeiten','Person ID %s bearbeiten');
 $GLOBALS['TL_LANG']['tl_family']['copy'] = array('Kopieren','Person ID %s kopieren');
 $GLOBALS['TL_LANG']['tl_family']['delete'] = array('Löschen','Person ID %s löschen');
 $GLOBALS['TL_LANG']['tl_family']['show'] = array('Anzeigen','Person ID %s anzeigen');
 $GLOBALS['TL_LANG']['tl_family']['edit_account'] = array('Account bearbeiten','Account der Person ID %s bearbeiten');

 $GLOBALS['TL_LANG']['tl_family']['new'] = array('Person hinzufügen','Eine Person zum Adressbuch hinzufügen');

 /**
  * Legends
	*/
	$GLOBALS['TL_LANG']['tl_family']['deceased_legend'] = 'Person ist verstorben?';
  $GLOBALS['TL_LANG']['tl_family']['personal_legend'] = 'Persönliche Angaben';
  $GLOBALS['TL_LANG']['tl_family']['address_legend'] = 'Adresse und Wohnort';
  $GLOBALS['TL_LANG']['tl_family']['contact_legend'] = 'Kontaktdaten';
  $GLOBALS['TL_LANG']['tl_family']['account_legend'] = 'Account-Einstellungen';
  $GLOBALS['TL_LANG']['tl_family']['family_legend'] = 'Angaben zur Familie';

 /**
  * Fields
  */
	$GLOBALS['TL_LANG']['tl_family']['title'] = array('Titel','Bitte geben Sie, falls vorhanden, den Titel der Person an.');
	$GLOBALS['TL_LANG']['tl_family']['title_options'] = array('dr' => 'Dr.','prof' => 'Prof.');
	$GLOBALS['TL_LANG']['tl_family']['firstname'] = array('Vorname','Bitte geben Sie den Vornamen der Person an.');
  $GLOBALS['TL_LANG']['tl_family']['lastname'] = array('Nachname','Bitte geben Sie den Nachnamen der Person an.');
  $GLOBALS['TL_LANG']['tl_family']['nameOfBirth'] = array('Geburtsname','Bitte geben Sie den Geburtsnamen der Person an (falls abweichend).');
	$GLOBALS['TL_LANG']['tl_family']['dateOfBirth'] = array('Geburtsdatum','Bitte geben Sie das Geburtsdatum der Person an.');
	$GLOBALS['TL_LANG']['tl_family']['dateOfDeath'] = array('Sterbedatum','Bitte geben Sie das Sterbedatum der Person an.');
	$GLOBALS['TL_LANG']['tl_family']['isDeceased'] = array('Ist bereits verstorben','Bitte geben Sie an, ob die Person bereits verstorben ist.');
  $GLOBALS['TL_LANG']['tl_family']['gender'] = array('Geschlecht','Bitte geben Sie das Geschlecht der Person an.');
  $GLOBALS['TL_LANG']['tl_family']['street'] = array('Straße','Bitte geben Sie die Straße der Adresse an.');
  $GLOBALS['TL_LANG']['tl_family']['postal'] = array('PLZ','Bitte geben Sie die Postleitzahl des Wohnortes an.');
  $GLOBALS['TL_LANG']['tl_family']['city'] = array('Ort','Bitte geben Sie den Wohnort an.');
  $GLOBALS['TL_LANG']['tl_family']['country'] = array('Land','Bitte geben Sie das Land des Wohnortes an.');
  $GLOBALS['TL_LANG']['tl_family']['mobile'] = array('Handy','Bitte geben Sie eine Handynummer an.');
  $GLOBALS['TL_LANG']['tl_family']['phone'] = array('Telefon','Bitte geben Sie eine Telefonnummer an.');
  $GLOBALS['TL_LANG']['tl_family']['fax'] = array('Fax','Bitte geben Sie eine Faxnummer an.');
  $GLOBALS['TL_LANG']['tl_family']['account_id'] = array('Verbundener Frontend-Account','Bitte geben Sie die E-Mail des verbundenen E-Mail-Accounts an (falls möglich).');

  $GLOBALS['TL_LANG']['tl_family']['mother'] = array('Mutter','Bitte geben Sie den Account der Mutter der Person an, falls vorhanden');
  $GLOBALS['TL_LANG']['tl_family']['father'] = array('Vater','Bitte geben Sie den Account des Vaters der Person an, falls vorhanden');
  $GLOBALS['TL_LANG']['tl_family']['partner'] = array('Partner','Bitte geben Sie den Account des Partners der Person an, falls vorhanden');
  $GLOBALS['TL_LANG']['tl_family']['partner_relation'] = array('Beziehung zum Partner','Bitte geben Sie die Beziehung zum Partner an, falls verlobt oder verheratet.');
  $GLOBALS['TL_LANG']['tl_family']['partner_relation_options'] = array('relationship'=>'In einer Beziehung','engaged' => 'verlobt', 'married' => 'verheiratet');

  $GLOBALS['TL_LANG']['tl_family']['visible_legend'] = 'Sichtbarkeit';
  $GLOBALS['TL_LANG']['tl_family']['visible'] = array('Sichtbar','Bitte geben Sie an, ob der Eintrag in der Adressliste öffentlich sichtbar gemacht werden soll.');
