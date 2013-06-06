<?php

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * This is the add-action, it will display a form to create a new item
 *
 * @author John Poelman <john.poelman@bloobz.be>
 */
class BackendGalleriaAddAlbum extends BackendBaseActionAdd
{
	/**
	 * Execute the action
	 *
	 * @return void
	 */
	public function execute()
	{
		// call parent, this will probably add some general CSS/JS or other required files
		parent::execute();
		
		// get the data
		$this->getData();
		
		// load the form
		$this->loadForm();

		// validate the form
		$this->validateForm();

		// parse
		$this->parse();

		// display the page
		$this->display();
	}

	protected function parse()
	{
		parent::parse();

		// get url
		$url = BackendModel::getURLForBlock($this->URL->getModule(), 'detail');
		$url404 = BackendModel::getURL(404);

		// parse additional variables
		if($url404 != $url) $this->tpl->assign('detailURL', SITE_URL . $url);
	}

	/**
	 * Load the form
	 *
	 * @return void
	 */
	private function loadForm()
	{
		// create form
		$this->frm = new BackendForm('add_album');

		// set hidden values
		$rbtHiddenValues[] = array('label' => BL::lbl('Hidden', $this->URL->getModule()), 'value' => 'Y');
		$rbtHiddenValues[] = array('label' => BL::lbl('Published'), 'value' => 'N');

		// set show-in-overview values
		$rbtShowOverviewValues[] = array('label' => BL::lbl('Yes'), 'value' => 'Y');
		$rbtShowOverviewValues[] = array('label' => BL::lbl('No'), 'value' => 'N');

		// create elements
		$this->frm->addText('title');
		$this->frm->getField('title')->setAttribute('class', 'title ' . $this->frm->getField('title')->getAttribute('class'));
		$this->frm->addEditor('description');
		$this->frm->addText('tags', null, null, 'inputText tagBox', 'inputTextError tagBox');
		$this->frm->addRadiobutton('hidden', $rbtHiddenValues, 'N');
		$this->frm->addRadiobutton('show_in_overview', $rbtShowOverviewValues, 'Y');
		$this->frm->addDropdown('category', BackendGalleriaModel::getCategoriesForDropdown(),$this->id);
		
		// meta
		$this->meta = new BackendMeta($this->frm, null, 'title', true);
	}
	
	/**
	 * Get the data for an album
	 *
	 * @return void
	 */
	private function getData()
	{	
		// check for a category's id
		$this->id = $this->getParameter('id', 'int');
		
		// get categories
		$this->categories = BackendGalleriaModel::getCategoriesForDropdown();

		if(empty($this->categories))
		{
			$this->redirect(BackendModel::createURLForAction('add_category'));
		}		
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
				// build album array
				$album['language'] = BL::getWorkingLanguage();
				$album['meta_id'] = $this->meta->save();
				$album['title'] = (string) $this->frm->getField('title')->getValue();
				$album['sequence'] = (int) BackendGalleriaModel::getMaximumAlbumSequence() + 1;
				$album['hidden'] = (string) $this->frm->getField('hidden')->getValue();
				$album['show_in_overview'] = (string) $this->frm->getField('show_in_overview')->getValue();
				$album['category_id'] = (int) $this->frm->getField('category')->getValue();
				$album['publish_on'] = BackendModel::getUTCDate();
				$album['description'] = (string) $this->frm->getField('description')->getValue();
				
				// first, insert the album
				$album['id'] = BackendGalleriaModel::insertAlbum($album);
				
				// save the tags
				BackendTagsModel::saveTags($album['id'], $this->frm->getField('tags')->getValue(), $this->URL->getModule());
				
				// trigger event
				BackendModel::triggerEvent($this->getModule(), 'after_add_album', array('item' => $album));
				
				// everything is saved, so redirect to the overview
				$this->redirect(BackendModel::createURLForAction('albums') . '&report=added-album&var=' . urlencode($album['title']) . '&highlight=row-' . $album['id']);
			}
		}
	}
}

