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
		// get parameters
		$this->id = $this->getParameter('id', 'int');

		// does the item exist
		if($this->id !== null && BackendGalleriaModel::existsAlbum($this->id))
		{
			// call parent, this will probably add some general CSS/JS or other required files
			parent::execute();

			// get album
			$this->album = BackendGalleriaModel::getAlbumFromId($this->id);

			// is this album allowed to be deleted?
			if(!BackendGalleriaModel::deleteAlbumAllowed($this->id))
			{
				$this->redirect(BackendModel::createURLForAction('albums') . '&error=album-not-deletable');
			}
			else
			{
				// delete the item
				BackendGalleriaModel::deleteAlbumById($this->id);

				// trigger event
				BackendModel::triggerEvent($this->getModule(), 'after_delete_category', array('id' => $this->id));

				// item was deleted, so redirect
				$this->redirect(BackendModel::createURLForAction('albums') . '&report=album-deleted&var=' . urlencode($this->album['title']));
			}
		}
		else $this->redirect(BackendModel::createURLForAction('albums') . '&error=non-existing');
	}
}
