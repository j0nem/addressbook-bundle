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
 
 //Make Firstname and Lastname in tl_member not mandatory
 $GLOBALS['TL_DCA']['tl_member']['fields']['firstname']['eval']['mandatory'] = false;
 $GLOBALS['TL_DCA']['tl_member']['fields']['lastname']['eval']['mandatory'] = false;
 
 //Remove everything except mandatory account fields in the palettes
 $GLOBALS['TL_DCA']['tl_member']['palettes'] = str_replace('{personal_legend},firstname,lastname,dateOfBirth,gender;{address_legend:hide},company,street,postal,city,state,country;','',$GLOBALS['TL_DCA']['tl_member']['palettes']); 
 $GLOBALS['TL_DCA']['tl_member']['palettes'] = str_replace('phone,mobile,fax,','',$GLOBALS['TL_DCA']['tl_member']['palettes']);
 $GLOBALS['TL_DCA']['tl_member']['palettes'] = str_replace(',website,language','',$GLOBALS['TL_DCA']['tl_member']['palettes']);
 
 //Remove firstname and lastname from list view
 $GLOBALS['TL_DCA']['tl_member']['list']['label']['fields'] = array('icon','email','dateAdded');
 
 //Add about_me in palettes
 $GLOBALS['TL_DCA']['tl_member']['palettes'] = str_replace('email','email;{verify_legend},about_me',$GLOBALS['TL_DCA']['tl_member']['palettes']);
 
 
 //Add "Edit Adressbook" action button
 $GLOBALS['TL_DCA']['tl_member']['list']['operations']['edit_addressbook'] = array(
	'label'               => &$GLOBALS['TL_LANG']['tl_member']['edit_account'],
	'href'                => 'do=family_list',
	'icon'                => 'bundles/jmediafamily/phone.svg',
	'button_callback'     => array('tl_member_custom', 'editAdressbook')
 );
 
 $GLOBALS['TL_DCA']['tl_member']['fields']['about_me'] = array(
	'label'			=> $GLOBALS['TL_LANG']['tl_member']['about_me'],
	'exclude'		=> true,
	'inputType'		=> 'textarea',
	'eval'			=> array('mandatory'=>true, 'maxlength'=>500, 'feEditable'=>true, 'feViewable'=>true,'rte'=>'tinyMCE'),
	'sql'			=> "varchar(255) NOT NULL default ''"
 );
 
 class tl_member_custom extends \Backend {
	 
	public function editAdressbook($row, $href, $label, $title, $icon) {
		$this->import('BackendUser','User');
		if (!$this->User->hasAccess('family_list', 'modules')) {
			return '';
		}
		
		$familyList = Family::fullList();

		foreach($familyList as $entry) {
			if($entry['account_id'] == $row['id']) {
				return '<a href="' . $this->addToUrl($href.'&amp;act=edit&id='.$entry['id']) . '" title="'.\StringUtil::specialchars($title).'">'.\Image::getHtml($icon, $label).'</a> ';
			}
		}
		return \Image::getHtml(preg_replace('/\.svg$/i', '_.svg', $icon)).' ';
	}
 }