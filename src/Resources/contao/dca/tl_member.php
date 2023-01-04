<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */

/**
 * Jmedia/AddressbookBundle
 *
 * @author Johannes Cram <johannes@jonesmedia.de>
 * @package AddressbookBundle
 * @license GPL-3.0+
 */


 //Remove everything except mandatory account fields in the palettes
 $GLOBALS['TL_DCA']['tl_member']['palettes']['default'] = str_replace('{personal_legend},firstname,lastname,dateOfBirth,gender;{address_legend:hide},company,street,postal,city,state,country;','',$GLOBALS['TL_DCA']['tl_member']['palettes']['default']);
 $GLOBALS['TL_DCA']['tl_member']['palettes']['default'] = str_replace('phone,mobile,fax,','',$GLOBALS['TL_DCA']['tl_member']['palettes']['default']);
 $GLOBALS['TL_DCA']['tl_member']['palettes']['default'] = str_replace(',website,language','',$GLOBALS['TL_DCA']['tl_member']['palettes']['default']);

 //Remove firstname and lastname from list view
 $GLOBALS['TL_DCA']['tl_member']['list']['label']['fields'] = array('icon','email','dateAdded');

 //Add about_me in palettes
 $GLOBALS['TL_DCA']['tl_member']['palettes']['default'] = str_replace('email','email;{verify_legend},about_me,verified',$GLOBALS['TL_DCA']['tl_member']['palettes']['default']);

 //Add "Edit Adressbook" action button
 $GLOBALS['TL_DCA']['tl_member']['list']['operations']['edit_addressbook'] = array(
	'label'               => &$GLOBALS['TL_LANG']['tl_member']['edit_account'],
	'href'                => 'do=fm_addressbook',
	'icon'                => 'bundles/jmediaaddressbook/phone.svg',
	'button_callback'     => array('tl_member_custom', 'editAdressbook')
 );

 $GLOBALS['TL_DCA']['tl_member']['fields']['about_me'] = array(
	'label'			=> &$GLOBALS['TL_LANG']['tl_member']['about_me'],
	'exclude'		=> true,
	'inputType'		=> 'textarea',
	'eval'			=> array('mandatory'=>true, 'maxlength'=>500, 'feEditable'=>true, 'feViewable'=>true),
	'sql'			=> "varchar(255) NOT NULL default ''"
 );

 class tl_member_custom extends \Backend {

	public function editAdressbook($row, $href, $label, $title, $icon) {
		$this->import('BackendUser','User');
		if (!$this->User->hasAccess('fm_addressbook', 'modules')) {
			return '';
		}

		$familyList = \Jmedia\Family::fullList();

		foreach($familyList as $entry) {
			if($entry['account_id'] == $row['id']) {
				return '<a href="' . $this->addToUrl($href.'&amp;act=edit&id='.$entry['id']) . '" title="'.\StringUtil::specialchars($title).'">'.\Image::getHtml($icon, $label).'</a> ';
			}
		}
		return \Image::getHtml(preg_replace('/\.svg$/i', '_.svg', $icon)).' ';
	}
 }
