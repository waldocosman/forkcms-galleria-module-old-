<?php

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * This is the delete-action, it deletes an item
 *
 * @author John Poelman <john.poelman@bloobz.be>
 */
class BackendGalleriaDeleteCategory extends BackendBaseActionDelete
{
	/**
	 * Execute the action
	 */
	public function execute()
	{
		$this->id = $this->getParameter('id', 'int');
		
		// does the item exist
		if($this->id !== null && BackendGalleriaModel::existsCategory($this->id))
		{
			parent::execute();
			
			// is this category allowed to be deleted?
			if(!BackendGalleriaModel::deleteCategoryAllowed($this->id))
			{
				$this->redirect(BackendModel::createURLForAction('categories') . '&error=category-not-deletable');
			}

			else
			{
				// get category
				$this->record = BackendGalleriaModel::getCategoryFromId($this->id);

				// delete category
				BackendGalleriaModel::deleteCategoryById($this->id);
			
				// delete meta
				BackendGalleriaModel::deleteMeta($this->record['meta_id']);
			
				// perform extra stuff
				BackendSearchModel::removeIndex($this->getModule(), $this->id);
				BackendModel::triggerEvent($this->getModule(), 'after_delete', array('id' => $this->id));

				$this->redirect(
					BackendModel::createURLForAction('categories') . '&report=category-deleted&var=' . urlencode($this->record['title'])
				);
			}
		}
		else $this->redirect(BackendModel::createURLForAction('categories') . '&error=non-existing');
	}
}
