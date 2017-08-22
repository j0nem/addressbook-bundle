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
	 * Setup displayed fields and sorting/grouping
	 * @var array
	 */
	 protected $arrFields = [
		 'personal_legend' => [
			 'firstname' => [],
			 'lastname' => [],
			 'nameOfBirth' => [],
			 'gender' => [],
			 'dateOfBirth' => [],
			 'about_me' => []
		],
		 'address_legend' => [
			 'street' => [],
			 'postal' => [],
			 'city' => [],
			 'country' => []
		],
		 'contact_legend' => [
			 'email' => [],
			 'phone' => [],
			 'mobile' => [],
			 'fax'  => []
		],
		 'family_legend' => [
			 'mother' => [],
			 'father' => [],
			 'partner' => [],
			 'partner_relation' => []
		]
	 ];

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
		$this->import('Database');
		$this->import('FrontendUser','User');
		\System::loadLanguageFile('tl_family');
		\System::loadLanguageFile('tl_member');

		//save new data
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$res = $this->saveData();
			if(is_array($res)) {
				$this->Template->error = $res['error'];
			}
			else {
				$this->Template->success = true;
			}
		}

		//fetch existing data
		$this->Template->data = $this->getFormData();
		$this->Template->legendLabels = $this->getLegendLabels();
	}

	/**
	* Get form fields with data for the template
	*
	* @return array
	*/
	protected function getFormData() {
		//copy field structure into $arrData
		$arrData = $this->arrFields;

		//reload tl_member data
		$arrMember = $this->Database->prepare("SELECT about_me,email FROM tl_member WHERE id = ?")->execute($this->User->id)->fetchAssoc();

		//add tl_member data
		$arrData[$this->getFieldGroup('email')]['email'] = [ 'value' => $arrMember['email'], 'type' => 'email', 'label' => $this->getLabel('email') ];
		$arrData[$this->getFieldGroup('about_me')]['about_me'] = [ 'value' => $arrMember['about_me'], 'type' => 'textarea', 'label' => $this->getLabel('about_me') ];

		//add tl_family data
		$arrFamilyEntry = Family::getAddressEntryOfMember($this->User->id);

		//prepare data
		foreach ($arrFamilyEntry as $clm => $val) {
			if($this->getFieldGroup($clm)) {
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
				$arrData[$this->getFieldGroup($clm)][$clm] = [ 'value' =>$val, 'type' => $strType, 'label' => $this->getLabel($clm) ];
				$arrData[$this->getFieldGroup($clm)][$clm]['options'] = $arrOptions;
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
	* Save form data
	*
	* @return mixed
	*/
	protected function saveData() {
		//check dateOfBirth
		if($this->Input->post('dateOfBirth')) {
			$time = strtotime($this->Input->post('dateOfBirth'));
			if($time == false || $time > time()){
				return [ 'error' => 'dateOfBirth' ];
			}
		}
		//check postal
		if($this->Input->post('postal') && !is_numeric($this->Input->post('postal'))) {
			return [ 'error' => 'postal' ];
		}
		//check phone/fax numbers
		$arrPhones = [ 'phone','mobile','fax' ];
		foreach($arrPhones as $elem) {
			if($this->Input->post($elem) && !preg_match('/^\+?([\d\s]+)$/', $this->Input->post($elem))){
				return [ 'error' => $elem];
			}
		}
		//check email
		if(!preg_match('/^\S+@\S+\.\w{2,}$/',$this->Input->post('email'))) {
			return [ 'error' => 'email' ];
		}

		//update tl_member
		$this->Database->prepare("UPDATE tl_member SET about_me = ?,email = ? WHERE id = ?")
			->execute($this->Input->post('about_me'),$this->Input->post('email'),$this->User->id);

		//trigger save_callback on tl_member.email
		$this->loadDataContainer('tl_member');
		$varValue = $this->Input->post('email');
		if (is_array($GLOBALS['TL_DCA']['tl_member']['fields']['email']['save_callback'])) {
			foreach ($GLOBALS['TL_DCA']['tl_member']['fields']['email']['save_callback'] as $callback) {
				if (is_array($callback)) {
					$this->import($callback[0]);
					$varValue = $this->{$callback[0]}->{$callback[1]}($varValue, $this->User, $this);
				}
				elseif (is_callable($callback)) {
					$varValue = $callback($varValue, $this->User, $this);
				}
			}
		}

		//update tl_family
		$arrFields = [];
		foreach($_POST as $key => $value) {
			if($this->getFieldGroup($key) && $key != 'email' && $key != 'about_me' && $key != 'dateOfBirth') {
				$arrFields[$key] = $this->Input->post($key);
			}
			elseif($this->getFieldGroup($key) && $key == 'dateOfBirth') {
				$arrFields[$key] = strtotime($this->Input->post($key));
			}
		}
		$this->Database->prepare("UPDATE tl_family %s WHERE account_id = ?")
			->set($arrFields)->execute($this->User->id);
		return 'success';
	}

	/**
	* Get group name of field
	*
	* @param $strClm
	* @return string
	*/
	protected function getFieldGroup($strClm) {
		foreach($this->arrFields as $strGroup => $arrFields) {
			foreach($arrFields as $strField => $stuff) {
				if($strField == $strClm) {
					return $strGroup;
				}
			}
		}
		return false;
	}

	/**
	* Get field label from tl_family or tl_family language array
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

	/**
	* Get labels for group headings
	*
	* @return array
	*/
	protected function getLegendLabels() {
		foreach($this->arrFields as $strGroup => $fields) {
			$arrLabels[$strGroup] = $GLOBALS['TL_LANG']['tl_family'][$strGroup];
		}
		return $arrLabels;
	}

}
