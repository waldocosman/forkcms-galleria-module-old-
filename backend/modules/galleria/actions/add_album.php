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

		// create elements
		$this->frm->addText('title');
		$this->frm->getField('title')->setAttribute('class', 'title ' . $this->frm->getField('title')->getAttribute('class'));
		$this->frm->addEditor('description');
		$this->frm->addRadiobutton('hidden', $rbtHiddenValues, 'N');
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
				$album['category_id'] = (int) $this->frm->getField('category')->getValue();
				$album['publish_on'] = BackendModel::getUTCDate();
				$album['description'] = (string) $this->frm->getField('description')->getValue();
				
				// first, insert the album
				$album_id = BackendGalleriaModel::insertAlbum($album);
				
				// save index to the search module
				BackendSearchModel::saveIndex(
				$this->getModule(),
				$album['id'],
				array('title' => $album['title'], 'text' => $item['title']));

				// trigger event
				BackendModel::triggerEvent($this->getModule(), 'after_add_album', array('item' => $album));

				if($album_id)
				{
					// then build the widget array...
					$widget['module'] = (string) $this->getModule();
					$widget['type'] = (string) 'widget';
					$widget['label'] = (string) BackendGalleriaModel::createWidgetLabel($album['title']);
					$widget['action'] = (string) 'widget';
					$widget['hidden'] = (string) 'N';
					$widget['data'] = (string) serialize(array('id' => $album_id));

					// ...to save it in the database
					$widgetID	= BackendGalleriaModel::insertWidget($widget);

					if($widgetID)
					{	
						// then build the locale array ...
						$locale['user_id'] = (int) '1';
						$locale['language'] = (string) BL::getWorkingLanguage();
						$locale['application'] = (string) 'backend';
						$locale['module'] = (string) 'pages';
						$locale['type'] = (string) 'lbl';
						$locale['name'] = (string) BackendGalleriaModel::createWidgetLabel($album['title']);
						$locale['value'] = (string) $album['title'];
						$locale['edited_on'] = BackendModel::getUTCDate();

						// ... and store it
						$localeID = BackendLocaleModel::insert($locale);

						// build the ids array...
						$ids['album_id'] = (int) $album_id;
						$ids['widget_id'] = (int) $widgetID;
						$ids['locale_id'] = (int) $localeID;

						// ... and store it
						$stored = BackendGalleriaModel::storeAllIds($ids);

						// everything is saved, so redirect to the overview
						$this->redirect(BackendModel::createURLForAction('albums') . '&report=added-category&var=' . urlencode($album['title']) . '&highlight=row-' . $album['id']);
					}
				}
			}
		}
	}
}

