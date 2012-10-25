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
class BackendGalleriaDeleteAlbum extends BackendBaseActionDelete
{
	/**
	 * Execute the action
	 */
	public function execute()
	{
		$this->id = $this->getParameter('id', 'int');
		
		// does the item exist
		if($this->id !== null && BackendGalleriaModel::existsAlbum($this->id))
		{
			parent::execute();
			
			// is this album allowed to be deleted?
			if(!BackendGalleriaModel::deleteAlbumAllowed($this->id))
			{
				$this->redirect(BackendModel::createURLForAction('albums') . '&error=album-not-deletable');
			}

			else
			{
				// get album
				$this->record = BackendGalleriaModel::getAlbumFromId($this->id);
				
				// get id from the locale and widget
				$ids = BackendGalleriaModel::getExtraIdsForAlbum($this->id);

				// BackendLocaleModel::delete needs an array to function
				$localeID = array($ids['locale_id']);
				
				// delete category
				BackendGalleriaModel::deleteAlbumById($this->id);
			
				// delete meta
				BackendGalleriaModel::deleteMeta($this->record['meta_id']);
				
				// delete the widget
				BackendGalleriaModel::deleteWidgetById($ids['widget_id']);

				// delete the locale
				BackendLocaleModel::delete($localeID);

				// delete the id's
				BackendGalleriaModel::deleteIdsByAlbumId($this->id);

				
				// perform extra stuff
				BackendSearchModel::removeIndex($this->getModule(), $this->id);
				BackendModel::triggerEvent($this->getModule(), 'after_delete', array('id' => $this->id));

				$this->redirect(
					BackendModel::createURLForAction('albums') . '&report=album-deleted&var=' . urlencode($this->record['title'])
				);
			}
		}
		else $this->redirect(BackendModel::createURLForAction('albums') . '&error=non-existing');
	}
}
