<?php

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * This is the add-action, it will display a form to ad images to an album
 *
 * @author John Poelman <john.poelman@bloobz.be>
 */
class BackendGalleriaAdd extends BackendBaseActionAdd
{
	/**
	 * Execute the actions
	 */
	public function execute()
	{
		parent::execute();
		$this->getData();
		$this->loadForm();
		$this->validateForm();
		$this->parse();
		$this->display();
	}
	
	/**
	 * Get the data 
	 *
	 * @return void
	 */
	private function getData()
	{	
		// check for an album's id
		$this->id = $this->getParameter('id', 'int');
		
		// get categories
		$this->albums = BackendGalleriaModel::getAlbumsForDropdown();

		if(empty($this->albums))
		{
			$this->redirect(BackendModel::createURLForAction('add_album'));
		}		
	}	
	
	/**
	 * Load the form
	 */
	protected function loadForm()
	{
		// create form
		$this->frm = new BackendForm('add');
		
		// add the formfields
		$this->frm->addDropdown('album',$this->albums,$this->id);
		$this->frm->addHidden('dummy_images');
		$this->frm->addImages('images');
	}

	/**
	 * Validate the form
	 */
	protected function validateForm()
	{
	

	}
}