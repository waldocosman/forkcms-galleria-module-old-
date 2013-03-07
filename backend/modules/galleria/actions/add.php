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
	private $album_id;

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
		$this->album_id = $this->getParameter('id', 'int');
		
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
		$this->frm->addDropdown('album',$this->albums,$this->album_id);
		$this->frm->addHidden('dummy_images');
		$this->frm->addImage('images');
	}

	/**
	 * Validate the form
	 */
	protected function validateForm()
	{
		// is the form submitted?
		if($this->frm->isSubmitted())
		{
			$filImage = $this->frm->getField('images');

			if($filImage->isFilled())
			{
				// image extension and mime type
				$filImage->isAllowedExtension(array('jpg', 'png', 'gif', 'jpeg'), BL::err('JPGGIFAndPNGOnly'));
				$filImage->isAllowedMimeType(array('image/jpg', 'image/png', 'image/gif', 'image/jpeg'), BL::err('JPGGIFAndPNGOnly'));
			}

			//--Fill in the item
			$item = array();
			$item["album_id"] = $this->album_id;
			$item["user_id"] = BackendAuthentication::getUser()->getUserId();
			$item["language"] = BL::getWorkingLanguage();
			$item["filename"] = $filImage->getFilename();
			$item["description"] = "";
			$item["publish_on"] = BackendModel::getUTCDate();
			$item["hidden"] = "Y";
			$item["sequence"] = 0;


			// the image path
			$imagePath = FRONTEND_FILES_PATH . '/galleria/images';

			// create folders if needed
			if(!SpoonDirectory::exists($imagePath . '/source')) SpoonDirectory::create($imagePath . '/source');
			if(!SpoonDirectory::exists($imagePath . '/128x128')) SpoonDirectory::create($imagePath . '/128x128');

			// image provided?
			if($filImage->isFilled())
			{
				// upload the image & generate thumbnails
				$filImage->generateThumbnails($imagePath, $item["filename"]);
			}

			//--Add item to the database
			BackendGalleriaModel::insert($item);

		}
	}
}