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

 class ModuleFamilyAdd extends \Module
{
	/**
	 * Template
	 * @var string
	 */
	 protected $strTemplate = 'fm_add';

	 /**
	 * Current Page
	 * @var string
	 */
	 protected $strPage;

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

			$objTemplate->wildcard = '### ' . $GLOBALS['TL_LANG']['FMD']['fm_add'][0] . ' ###';
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
		global $objPage;

		//import user object
		$this->import('FrontendUser','User');

		//Start page
		if(!\Input::get('action') && !\Input::post('FORM_SUBMIT')) {
			$this->strPage = 'invitation-form';
		}
		//After invitation POST request
		elseif(\Input::post('FORM_SUBMIT') == 'invitation-form') {
			$res = $this->sendInvitation();
			if($res === true) {
				$this->Template->email = \Input::post('email');
				$this->strPage = 'invitation-success';
			}
			else {
				$this->Template->invite_error = $GLOBALS['TL_LANG']['Family']['error'][$res];
				$this->strPage = 'invitation-form';
			}
		}
		//Add form request
		elseif(\Input::get('action') == 'add-form') {

			$objForm = new FamilyForm('new');

			if(\Input::post('FORM_SUBMIT') == 'add-form') {
				foreach($_POST as $key => $val) {
					$arrPost[$key] = \Input::post($key);
				}
				$objForm->setData($arrPost);
				$res = $objForm->save();

				if(is_array($res)) {
					$this->Template->add_error = $res;
					$this->strPage = 'add-form';
				}
				else {
					$this->strPage = 'add-success';
					$this->sendVerificationEmail($objForm);
				}
			}
			else {
				$this->strPage = 'add-form';
			}
			$this->Template->fields = $objForm->getFormData();
			$this->Template->legendLabels = $objForm->getLegendLabels();
		}

		//Set the template hrefs
		if($this->strPage == 'invitation-form'){
			$this->Template->addHref = $objPage->getFrontendUrl('/action/add-form');
		}
		else {
			$this->Template->backHref = $objPage->getFrontendUrl();
		}

		$this->Template->page = $this->strPage;
	}

	/**
	* Validate email and send invitation
	*
	* @return mixed
	*/
	protected function sendInvitation() {
		if(\MemberModel::findByEmail(\Input::post('email')) !== null) {
			return 'email_already_registered';
		}
		elseif(!preg_match('/^\S+@\S+\.\w{2,}$/',\Input::post('email'))) {
			return 'email';
		}

		$arrFamily = Family::getAddressEntryOfMember($this->User->id);
		$objMail = new \Email();
		$objMail->from = \Config::get('adminEmail');
		$objMail->fromName = $arrFamily['firstname'] . ' '. $arrFamily['lastname'] . ' via Familienadressbuch';
		$objMail->subject = $arrFamily['firstname'] . ' '. $arrFamily['lastname'] . ' möchte Sie zum Familienadressbuch einladen!';
		$objMail->html = '<h2>Sie haben eine Einladung erhalten!</h2>'
.'<p>'.$objMail->subject.'</p>
<p>Das Familienadressbuch ist eine gute Möglichkeit, um mit Familienmitgliedern in Kontakt zu bleiben. Die Seite wurde von Familienmitgliedern ins Leben gerufen und ist für eine moderne Kommnikation innerhalb der Großfamilie geeignet.</p>
<p>Unter folgendem Link können Sie sich registrieren:</p>
<p><a href="http://'.\Environment::get('httpHost').'">http://'.\Environment::get('httpHost').'</a></p>
<p>Ihr Team vom Familienadressbuch</p>';
		$objMail->sendTo(\Input::post('email'));
		return true;
	}

	/**
	* Send admin notification email
	*
	* @param object $objForm
	* @return boolean
	*/
	protected function sendVerificationEmail($objForm){
		//get addressbook entry of member who is currently logged in
		$arrEntry = Family::getAddressEntryOfMember($this->User->id);

		$objMail = new \Email();
		$objMail->subject = 'Neuer Adressbucheintrag von ' . $arrEntry['firstname'] . ' ' . $arrEntry['lastname'];
		$strDateOfBirth = $objForm->dateOfBirth ? date('d.m.Y',$objForm->dateOfBirth) : '';
		$objMail->html = '<h2>'.$arrEntry['firstname'].' '.$arrEntry['lastname'].' hat '.$objForm->firstname.' '.$objForm->lastname.' hinzugefügt</h2>
<p>Folgende Angaben wurden gemacht:</p>
<p>Geburtsname: '.$objForm->nameOfBirth.'<br />
Geschlecht: '.$objForm->gender.'<br />
Geburtsdatum: '.$strDateOfBirth.'<br />
Straße: '.$objForm->street.'<br />
PLZ: '.$objForm->postal.'<br />
Ort: '.$objForm->city.'<br />
Land: '.$objForm->country.'<br />
Telefon: '.$objForm->phone.'<br />
Handy: '.$objForm->mobile.'<br />
Fax: '.$objForm->fax.'<br />
Mutter: '.Family::formatName(Family::getAddressEntry($objForm->mother)).'<br />
Vater: '.Family::formatName(Family::getAddressEntry($objForm->father)).'<br />
Partner: '.Family::formatName(Family::getAddressEntry($objForm->partner)).'<br />
Beziehung zum Partner: '.$objForm->partner_relation.'</p>
<p><a href="http://'.\Environment::get('httpHost').'/contao?do=fm_verification">Jetzt verifizieren</a></p>';

		//send notification mails to admins who subscribed
		$admins = $this->Database->query("SELECT email FROM tl_user WHERE family_notifications = 1");
		while($arrAdmin = $admins->fetchAssoc()) {
			$objMail->sendTo($arrAdmin['email']);
		}
		return true;
	}
}
