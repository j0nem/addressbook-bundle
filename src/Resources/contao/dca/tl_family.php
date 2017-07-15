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
 * Table tl_family
 */
$GLOBALS['TL_DCA']['tl_family'] = array
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
			'fields'                  => array('lastname','firstname'),
			'flag'                    => 1,
			'panelLayout'             => 'filter;search,limit'
		),
		'label' => array
		(
			'fields'                  => array('lastname','firstname','dateOfBirth','nameOfBirth','account_id'),
			'showColumns'			  => true,
			'label_callback'		  => array('tl_family','generateLabels'),
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
				'label'               => &$GLOBALS['TL_LANG']['tl_family']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.svg'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_family']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.svg',
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_family']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.svg',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_family']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.svg'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_family']['toggle'],
				'icon'                => 'visible.svg',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_family', 'toggleIcon')
			),
			'edit_account' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_family']['edit_account'],
				'href'                => 'do=member',
				'icon'                => 'member.svg',
				'button_callback'     => array('tl_family', 'editAccount')
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array(),
		'default'                     => '{personal_legend},firstname,lastname,nameOfBirth,gender,dateOfBirth;{account_legend},account_id;{address_legend},street,postal,city,country;{contact_legend},phone,mobile,fax;{family_legend},mother,father,partner;{visible_legend},visible'
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
		'firstname' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_family']['firstname'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'feEditable'=>true, 'feViewable'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'lastname' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_family']['lastname'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'feEditable'=>true, 'feViewable'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'nameOfBirth' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_family']['nameOfBirth'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'feEditable'=>true, 'feViewable'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'dateOfBirth' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_family']['dateOfBirth'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'date', 'datepicker'=>true, 'feEditable'=>true, 'feViewable'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(11) NOT NULL default ''"
		),
		'gender' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_family']['gender'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('male', 'female'),
			'reference'               => &$GLOBALS['TL_LANG']['MSC'],
			'eval'                    => array('includeBlankOption'=>true, 'feEditable'=>true, 'feViewable'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'street' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_family']['street'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'feEditable'=>true, 'feViewable'=>true,'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'postal' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_family']['postal'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>32, 'feEditable'=>true, 'feViewable'=>true,'tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'city' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_family']['city'],
			'exclude'                 => true,
			'filter'                  => true,
			'search'                  => true,
			'sorting'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'feEditable'=>true, 'feViewable'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'country' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_family']['country'],
			'exclude'                 => true,
			'filter'                  => true,
			'sorting'                 => true,
			'inputType'               => 'select',
			'eval'                    => array('includeBlankOption'=>true, 'chosen'=>true, 'feEditable'=>true, 'feViewable'=>true, 'tl_class'=>'w50'),
			'options_callback' => function ()
			{
				return System::getCountries();
			},
			'sql'                     => "varchar(2) NOT NULL default ''"
		),
		'phone' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_family']['phone'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>64, 'rgxp'=>'phone', 'decodeEntities'=>true, 'feEditable'=>true, 'feViewable'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'mobile' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_family']['mobile'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>64, 'rgxp'=>'phone', 'decodeEntities'=>true, 'feEditable'=>true, 'feViewable'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'fax' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_family']['fax'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>64, 'rgxp'=>'phone', 'decodeEntities'=>true, 'feEditable'=>true, 'feViewable'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'account_id' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_family']['account_id'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'select',
			'eval'                    => array('tl_class'=>'w50', 'chosen' => true, 'includeBlankOption' => true),
			'foreignKey'			  => 'tl_member.email',
			'sql'                     => "int(10) NOT NULL default '0'"
		),
		'mother' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_family']['mother'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'select',
			'options_callback'		  => array('Family','nameList'),
			'eval'                    => array('tl_class'=>'w50','includeBlankOption' => true),
			'foreignKey'			  => 'tl_family.firstname',
			'sql'                     => "int(10) NOT NULL default '0'"
		),
		'father' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_family']['father'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'select',
			'options_callback'		  => array('Family','nameList'),
			'eval'                    => array('tl_class'=>'w50','includeBlankOption' => true),
			'foreignKey'			  => 'tl_family.firstname',
			'sql'                     => "int(10) NOT NULL default '0'"
		),
		'partner' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_family']['partner'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'select',
			'options_callback'		  => array('Family','nameList'),
			'eval'                    => array('tl_class'=>'w50','includeBlankOption' => true),
			'foreignKey'			  => 'tl_family.firstname',
			'sql'                     => "int(10) NOT NULL default '0'"
		),
		'partner_relation' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_family']['partner_relation'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'select',
			'options_callback'		  => array('engaged','married'),
			'reference'				  => &$GLOBALS['TL_LANG']['tl_family']['partner_relation_options'],
			'eval'                    => array('tl_class'=>'w50'),
			'foreignKey'			  => 'tl_family.firstname',
			'sql'                     => "int(10) NOT NULL default '0'"
		),
		'visible' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_family']['visible'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'sql'                     => "char(1) NOT NULL default ''"
		),
	)
);

class tl_family extends Backend {
	
	/**
	 * Import the back end user object
	 */
	public function __construct() {
		parent::__construct();
		$this->import('BackendUser', 'User');
	}
	
	/**
	 * Switch to Member Account, if possible
	 */
	public function editAccount($row, $href, $label, $title, $icon) {
		if (!$this->User->hasAccess('member', 'modules')) {
			return '';
		}
		
		$objMember = FrontendUser::getInstance();

		if($row['account_id'] == 0 || !$objMember->findBy('id',$row['account_id']))  {
			return Image::getHtml(preg_replace('/\.svg$/i', '_.svg', $icon)).' ';
		}
		
		return '<a href="' . $this->addToUrl($href.'&amp;act=edit&id='.$row['account_id']) . '" title="'.StringUtil::specialchars($title).'">'.Image::getHtml($icon, $label).'</a> ';
	}
	
	
	/**
	 * Generate Labels
	 */
	 public function generateLabels($row, $label, DataContainer $dc, $args) {
		 
		if($row['nameOfBirth']) 						
			$args[3] = '(geb. ' . $row['nameOfBirth'] .')';

		if($row['dateOfBirth'] != '') 					
			$args[2] = \Family::formatDate($row);
		
		$objMember = FrontendUser::getInstance();
		
		if($objMember->findBy('id',$row['account_id']))	
			$args[4] = $objMember->email;
		 
		return $args;
	 }
	 
	 
	/**
	 * Return the "toggle visibility" button
	 *
	 * @param array  $row
	 * @param string $href
	 * @param string $label
	 * @param string $title
	 * @param string $icon
	 * @param string $attributes
	 *
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes) {
		if (strlen(Input::get('tid'))) {
			$this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->hasAccess('tl_family::visible', 'alexf')) {
			return '';
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['visible'] ? '' : 1);

		if (!$row['visible']) {
			$icon = 'invisible.svg';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.StringUtil::specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label, 'data-state="' . ($row['visible'] ? 1 : 0) . '"').'</a> ';
	}
	
	/**
	 * Toggle Visibility
	 *
	 * @param integer       $intId
	 * @param boolean       $blnVisible
	 * @param DataContainer $dc
	 */
	public function toggleVisibility($intId, $blnVisible, DataContainer $dc = null) {
		$objVersions = new Versions('tl_family', $intId);
		$objVersions->initialize();

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_family']['fields']['visible']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_family']['fields']['visible']['save_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, ($dc ?: $this));
				}
				elseif (is_callable($callback))
				{
					$blnVisible = $callback($blnVisible, ($dc ?: $this));
				}
			}
		}

		$time = time();

		// Update the database
		$this->Database->prepare("UPDATE tl_family SET tstamp=$time, visible='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);

		$objVersions->create();
		$this->log('A new version of record "tl_family.id='.$intId.'" has been created'.$this->getParentEntries('tl_family', $intId), __METHOD__, TL_GENERAL);

	}
}
