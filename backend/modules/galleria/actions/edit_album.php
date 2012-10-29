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
class BackendGalleriaEditAlbum extends BackendBaseActionEdit
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
		if($this->id !== null && BackendGalleriaModel::existsAlbum($this->id))
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
		else $this->redirect(BackendModel::createURLForAction('albums') . '&error=non-existing');
	}

	/**
	 * Get the data
	 *
	 * @return void
	 */
	private function getData()
	{
		$this->record 	= BackendGalleriaModel::getAlbumFromId($this->id);
	}

	/**
	 * Load the form
	 *
	 * @return void
	 */
	private function loadForm()
	{
		// create form
		$this->frm = new BackendForm('edit_album');

		// get values for the form
		$rbtHiddenValues[] = array('label' => BL::lbl('Hidden'), 'value' => 'Y');
		$rbtHiddenValues[] = array('label' => BL::lbl('Published'), 'value' => 'N');

		// create elements
		$this->frm->addText('title', $this->record['title']);
		$this->frm->getField('title')->setAttribute('class', 'title ' . $this->frm->getField('title')->getAttribute('class'));
		$this->frm->addEditor('description', $this->record['description']);
		$this->frm->addText('tags', BackendTagsModel::getTags($this->URL->getModule(), $this->id), null, 'inputText tagBox', 'inputTextError tagBox');
		$this->frm->addRadiobutton('hidden', $rbtHiddenValues, $this->record['hidden']);
		$this->frm->addDropdown('category', BackendGalleriaModel::getCategoriesForDropdown(), $this->record['category_id']);
		
		// meta object
		$this->meta = new BackendMeta($this->frm, $this->record['meta_id'], 'title', true);
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
		$this->tpl->assign('album', $this->record);

		// can the category be deleted?
		if(BackendGalleriaModel::deleteAlbumAllowed($this->id)) $this->tpl->assign('showDelete', true);
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
				// first, build the album array
				$album['id'] = (int) $this->id;
				$album['extra_id'] = $this->record['extra_id'];
				$album['title'] = (string) $this->frm->getField('title')->getValue();
				$album['description'] = (string) $this->frm->getField('description')->getValue();
				$album['category_id'] = (int) $this->frm->getField('category')->getValue();
				$album['meta_id'] = $this->meta->save();
				$album['language'] = (string) BL::getWorkingLanguage();
				$album['hidden'] = (string) $this->frm->getField('hidden')->getValue();

				// ... then, update the album
				BackendGalleriaModel::updateAlbum($album);
							
				// trigger event
				BackendModel::triggerEvent($this->getModule(), 'after_edit_album', array('item' => $album));
				
				// save the tags
				BackendTagsModel::saveTags($album['id'], $this->frm->getField('tags')->getValue(), $this->URL->getModule());

				// everything is saved, so redirect to the overview
				$this->redirect(BackendModel::createURLForAction('albums') . '&report=edited-album&var=' . urlencode($album['title']) . '&highlight=row-' . $album['id']);			
			}	
		}
	}
}