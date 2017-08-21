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

 class ModuleFamilyEdit extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	 protected $strTemplate = 'fm_edit';

	 /**
	 * Not editable tl_family fields
	 * @var array
	 */
	 protected $arrNotEditableFields = [ 'id','tstamp','account_id','visible' ];

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

			$objTemplate->wildcard = '### ' . Utf8::strtoupper($GLOBALS['TL_LANG']['FMD']['fm_edit'][0]) . ' ###';
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
		$this->import('Database');
		\System::loadLanguageFile('tl_family');
		\System::loadLanguageFile('tl_member');

		$this->Template->data = $this->getFormData();
	}

	/**
	* Get form fields with data for the template
	*
	* @return array
	*/
	protected function getFormData() {
		//add tl_member data
		$arrData['email'] = [ 'value' => $this->User->email, 'type' => 'email', 'label' => $this->getLabel('email') ];
		$arrData['about_me'] = [ 'value' => $this->User->about_me, 'type' => 'textarea', 'label' => $this->getLabel('about_me') ];

		//add tl_family data
		$arrFamilyEntry = Family::getAddressEntryOfMember($this->User->id);
		foreach ($arrFamilyEntry as $clm => $val) {
			if(!in_array($clm,$this->arrNotEditableFields)) {
				//=== DATE OF BIRTH ===
				if($clm == 'dateOfBirth') {
					$val = date('d.m.Y',$val);
				}
				//=== POSTAL ===
				elseif($clm == 'postal') {
					$strType = 'number';
				}
				//=== TEL NUMBERS ===
				elseif(in_array($clm,['phone','mobile','fax'])) {
					$strType = 'tel';
				}
				//=== SELECT FIELDS ===
				elseif(in_array($clm,['gender','country','mother','father','partner','partner_relation'])) {
					$strType = 'select';
					$arrOptions = $this->getSelectOptions($clm);
				}
				//=== ALL OTHER FIELDS ===
				else {
					$strType = 'text';
				}
				$arrData[$clm] = [ 'value' =>$val, 'type' => $strType, 'label' => $this->getLabel($clm) ];
				$arrData[$clm]['options'] = $arrOptions;
			}
		}
		return $arrData;
	}

	/**
	* Get Options with label for select-fields
	*
	* @param $strClm
	* @return array
	*/
	protected function getSelectOptions($strClm) {
		//=== GENDER FIELD ===
		if($strClm == 'gender') {
			return [
				'' => '-',
				'male' => &$GLOBALS['TL_LANG']['MSC']['male'],
				'female' => &$GLOBALS['TL_LANG']['MSC']['female']
			];
		}
		//=== COUNTRY FIELD ===
		elseif($strClm == 'country') {
			return \System::getCountries();
		}
		//=== FAMILY FIELD ===
		elseif(in_array($strClm,['mother','father','partner'])){
			$arrOptions =  Family::nameList();
			//add empty option
			$arrOptions[0] = '-';
			//unset own entry from select options
			unset($arrOptions[Family::getAddressEntryOfMember($this->User->id)['id']]);
			return $arrOptions;
		}
		//=== PARTNER RELATION FIELD ===
		elseif($strClm == 'partner_relation')  {
			return [
				'' => '-',
				'engaged' => &$GLOBALS['TL_LANG']['tl_family']['partner_relation_options']['engaged'],
				'married' => &$GLOBALS['TL_LANG']['tl_family']['partner_relation_options']['married'],
			];
		}
		//=== UNKNOWN FIELDS ===
		else return [];
	}

	/**
	* Get Label from tl_family or tl_family language array
	*
	* @param $strClm
	* @return string
	*/
	protected function getLabel($strClm){
		if($strClm == 'email' || $strClm == 'about_me') {
			return $GLOBALS['TL_LANG']['tl_member'][$strClm][0];
		}
		else {
			return $GLOBALS['TL_LANG']['tl_family'][$strClm][0];
		}
	}

}
