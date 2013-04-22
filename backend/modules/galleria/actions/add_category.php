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
class BackendGalleriaAddCategory extends BackendBaseActionAdd
{
	/**
	 * Execute the action
	 */
	public function execute()
	{
		parent::execute();
		$this->loadForm();
		$this->validateForm();
		$this->parse();
		$this->display();
	}

	/**
	 * Load the form
	 */
	private function loadForm()
	{
		// set hidden values
		$rbtHiddenValues[] = array('label' => BL::lbl('Hidden', $this->URL->getModule()), 'value' => 'Y');
		$rbtHiddenValues[] = array('label' => BL::lbl('Published'), 'value' => 'N');

		// create form
		$this->frm = new BackendForm('add_category');
		$this->frm->addText('title', null, 255, 'inputText title', 'inputTextError title');
		$this->frm->addRadiobutton('hidden', $rbtHiddenValues, 'N');

        // meta
        $this->meta = new BackendMeta($this->frm, null, 'title', true);
	}

	/**
	 * Validate the form
	 */
	private function validateForm()
	{
		if($this->frm->isSubmitted())
		{
			// cleanup the submitted fields, ignore fields that were added by hackers
			$this->frm->cleanupFields();

			// validate fields
			$this->frm->getField('title')->isFilled(BL::err('TitleIsRequired'));

			// no errors?
			if($this->frm->isCorrect())
			{
				// build item
				$item['title'] = $this->frm->getField('title')->getValue();
				$item['language'] = BL::getWorkingLanguage();
				$item['publish_on'] = BackendModel::getUTCDate('Y-m-d H:i:s');
				$item['hidden'] = $this->frm->getField('hidden')->getValue();
                $item['meta_id'] = $this->meta->save();

				// get the highest sequence available
				$item['sequence'] = BackendGalleriaModel::getMaximumCategorySequence() +1;

				// insert the item
				$item['id'] = BackendGalleriaModel::insertCategory($item);

				// trigger event
				BackendModel::triggerEvent($this->getModule(), 'after_add_category', array('item' => $item));

				// everything is saved, so redirect to the overview
				$this->redirect(BackendModel::createURLForAction('categories') . '&report=added-category&var=' . urlencode($item['title']) . '&highlight=row-' . $item['id']);
			}
		}
	}
}