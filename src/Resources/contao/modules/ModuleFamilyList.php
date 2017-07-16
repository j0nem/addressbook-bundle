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

 namespace Jmedia;

 use Patchwork\Utf8;

 class ModuleFamilyList extends \Module
{
 	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'fm_list';


	/**
	 * Display a wildcard in the back end
	 *
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			/** @var BackendTemplate|object $objTemplate */
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . Utf8::strtoupper($GLOBALS['TL_LANG']['FMD']['family'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		//Redirect if not logged in
		if(!FE_USER_LOGGED_IN){
			/* FUNKTIONIERT in Contao 4 schinbar NICHT MEHR
			*
			* $objHandler = new $GLOBALS['TL_PTY']['error_403']();
			* $objHandler->generate($objPage->id);
			*/
			return false;
		}

		$arrList = Family::fullList();

		//DETAIL
		if($this->Input->get('id') != '' && $arrList[$this->Input->get('id')]) {
			$this->Template->mode = 'detail';
			$this->Template->activeRecord = $arrList[$this->Input->get('id')];

			global $objPage;
			$objPage->pageTitle = Family::formatName($arrList[$this->Input->get('id')]);
		}

		//LIST
		else {

			$this->Template->mode = 'list';
			foreach($arrList as $elem) {
				$elem['name_string'] = Family::formatName($elem,true);
				$elem['date_string'] = Family::formatDate($elem);
				$elem['detail_href'] = $this->addToUrl('id=' . $elem['id'],['_locale']);
				$arrFamily[$elem['id']] = $elem;
			}

			$this->Template->arrFamily = $arrFamily;
		}

		return true;
	}
}
