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

 class ModuleFamilyEdit extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	 protected $strTemplate = 'fm_edit';

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

			$objTemplate->wildcard = '### ' . $GLOBALS['TL_LANG']['FMD']['fm_edit'][0] . ' ###';
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
		if(!FE_USER_LOGGED_IN) {
			return false;
		}
		$this->import('FrontendUser','User');

		$objForm = new FamilyForm('member',$this->User->id);

		//save new data
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			foreach($_POST as $key => $value) {
				$arrPost[$key] = \Input::post($key);
			}
			$objForm->setData($arrPost);
			$res = $objForm->save();

			if(is_array($res)) {
				$this->Template->error = $res;
			}
			else {
				$this->Template->success = true;
			}
		}

		//fetch existing data
		$this->Template->data = $objForm->getFormData();
		$this->Template->legendLabels = $objForm->getLegendLabels();
		$this->Template->firstLogin = $this->Input->get('message') == 'firstlogin' ? true : false;
	}
}
