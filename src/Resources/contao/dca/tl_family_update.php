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

/**
 * Table tl_family_update
 */
$GLOBALS['TL_DCA']['tl_family_update'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('field'),
			'flag'                    => 1,
			'panelLayout'             => 'filter;search,limit'
		),
		'label' => array
		(
			'fields'                  => array('field','before','after'),
			'showColumns'			  => true,
			'label_callback'		  => array('tl_family_update','generateLabels'),
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_family_update']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.svg'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_family_update']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.svg',
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_family_update']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.svg',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_family_update']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.svg'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array(),
		'default'                     => '{update_legend},field,before,after'
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'field' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_family_update']['field'],
			'exclude'                 => true,
			'search'                  => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'feEditable'=>false, 'feViewable'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'before' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_family_update']['before'],
			'exclude'                 => true,
			'search'                  => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'feEditable'=>true, 'feViewable'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'after' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_family_update']['nameOfBirth'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'feEditable' => false, 'feViewable'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'member' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_family_update']['member'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'foreignKey'			  => 'tl_member.username',
			'eval'                    => array('feEditable' => false, 'feViewable'=>true, 'tl_class'=> 'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'reviewed' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_family_update']['reviewed'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'sql'                     => "char(1) NOT NULL default ''"
		),
	)
);

class tl_family_update extends Backend {

	/**
	 * Import the back end user object
	 */
	public function __construct() {
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	/**
	 * Generate Labels
	 */
	 public function generateLabels($row, $label, DataContainer $dc, $args) {

		if($row['nameOfBirth'])
			$args[3] = '(geb. ' . $row['nameOfBirth'] .')';

		if($row['dateOfBirth'] != '')
			$args[2] = \Jmedia\Family::formatDate($row);

		$objMember = FrontendUser::getInstance();

		if($objMember->findBy('id',$row['account_id']))
			$args[4] = $objMember->email;

		return $args;
	 }
}
