<?php

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * This is the edit-action, it will display a form with the item data to edit
 *
 * @author John Poelman <john.poelman@bloobz.be>
 */
class BackendGalleriaEditCategory extends BackendBaseActionEdit
{
	/**
	 * Execute the action
	 *
	 * @return void
	 */
	public function execute()
	{
		// get parameters
		$this->id = $this->getParameter('id', 'int');

		// does the item exists?
		if($this->id !== null && BackendGalleriaModel::existsCategory($this->id))
		{
			// call parent, this will probably add some general CSS/JS or other required files
			parent::execute();

			// get all data for the item we want to edit
			$this->getData();

			// load the form
			$this->loadForm();

			// validate the form
			$this->validateForm();

			// parse the form
			$this->parse();

			// display the page
			$this->display();
		}

		// no item found, throw an exceptions, because somebody is fucking with our URL
		else $this->redirect(BackendModel::createURLForAction('categories') . '&error=non-existing');
	}

	/**
	 * Get the data
	 *
	 * @return void
	 */
	private function getData()
	{
		$this->record 	= BackendGalleriaModel::getCategoryFromId($this->id);
	}

	/**
	 * Load the form
	 *
	 * @return void
	 */
	private function loadForm()
	{
		// create form
		$this->frm = new BackendForm('edit_category');

		// get values for the form
		$rbtHiddenValues[] = array('label' => BL::lbl('Hidden'), 'value' => 'Y');
		$rbtHiddenValues[] = array('label' => BL::lbl('Published'), 'value' => 'N');

		// create elements
		$this->frm->addText('title', $this->record['title']);
		$this->frm->addRadiobutton('hidden', $rbtHiddenValues, $this->record['hidden']);
	}

	/**
	 * Parse the form
	 *
	 * @return void
	 */
	protected function parse()
	{
		// call parent
		parent::parse();

		// assign the category
		$this->tpl->assign('category', $this->record);

		// can the category be deleted?
		if(BackendGalleriaModel::deleteCategoryAllowed($this->id)) $this->tpl->assign('showDelete', true);
	}

	/**
	 * Validate the form
	 *
	 * @return void
	 */
	private function validateForm()
	{
		// is the form submitted?
		if($this->frm->isSubmitted())
		{
			// cleanup the submitted fields, ignore fields that were added by hackers
			$this->frm->cleanupFields();

			// validate fields
			$this->frm->getField('title')->isFilled(BL::err('TitleIsRequired'));

			// no errors?
			if($this->frm->isCorrect())
			{
				// first, build the category array
				$category['id'] = (int) $this->id;
				$category['title'] = (string) $this->frm->getField('title')->getValue();
				$category['language'] = (string) BL::getWorkingLanguage();
				$category['hidden'] = (string) $this->frm->getField('hidden')->getValue();

				// ... then, update the category
				$category_update = BackendGalleriaModel::updateCategory($category);
				
				// delete old meta
				//BackendGalleriaModel::deleteMeta($this->record['meta_id']);
				
				// trigger event
				BackendModel::triggerEvent($this->getModule(), 'after_edit_category', array('item' => $category));

				// everything is saved, so redirect to the overview
				$this->redirect(BackendModel::createURLForAction('categories') . '&report=edited-category&var=' . urlencode($category['title']) . '&highlight=row-' . $category['id']);			
			}	
		}
	}
}