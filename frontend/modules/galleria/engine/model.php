<?php

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * In this file we store all generic functions that we will be using in the galleria module
 *
 * @author John Poelman <john.poelman@bloobz.be>
 * @author Waldo Cosman <waldo@comsa.be>
 */
class FrontendGalleriaModel
{

	/**
	 * Get the images for an album
	 *
	 * @param int $id
	 * @return bool
	 */
	public static function getImagesForAlbum($id)
	{
		$records = (array) FrontendModel::getDB()->getRecords(
			'SELECT i.*
			 FROM galleria_images AS i
			 WHERE i.language = ? AND i.album_id = ?
			 ORDER BY sequence',
			array(FRONTEND_LANGUAGE,(int) $id));

		//--Loop records
		if(!empty($records))
		{
			//--Get the thumbnail-folders
			$folders = FrontendModel::getThumbnailFolders(FRONTEND_FILES_PATH . '/galleria/images', true);

			//--Create the image-links to the thumbnail folders
			foreach($records as &$row)
			{
				foreach($folders as $folder) $row['image_' . $folder['dirname']] = $folder['url'] .  '/' . $row['filename'];
			}
			//--Destroy the last $image (because of the reference) -- sugested by http://php.net/manual/en/control-structures.foreach.php
			unset($row);
		}

		return $records;
	}


}
