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
 */
class BackendGalleriaModel
{
	/**
	 * Define constants
	 */	

	const QRY_DATAGRID_CAT =
		 'SELECT i.*
		  FROM galleria_categories AS i
		  WHERE i.language = ? ORDER BY i.sequence ASC';
	
	const QRY_DATAGRID_ALBUMS =
		 'SELECT i.*
		  FROM galleria_albums AS i
		  WHERE i.language = ? ORDER BY i.sequence ASC';
	
	/**
	 * Is the deletion of this album allowed?
	 *
	 * @param int $id
	 * @return bool
	 */
	public static function deleteAlbumAllowed($id)
	{
		return (bool) (BackendModel::getDB()->getVar(
		'SELECT COUNT(id)
		 FROM galleria_images AS i
		 WHERE i.album_id = ? AND i.language = ?',
		 array((int) $id, BL::getWorkingLanguage())) == 0);
	}
	
	/**
	 * Is the deletion of a category allowed?
	 *
	 * @param int $id
	 * @return bool
	 */
	public static function deleteCategoryAllowed($id)
	{
		return (bool) (BackendModel::getDB()->getVar(
		'SELECT COUNT(id)
		 FROM galleria_albums AS i
		 WHERE i.category_id = ? AND i.language = ?',
		 array((int) $id, BL::getWorkingLanguage())) == 0);
	}


	
	/**
	 * Delete an album
	 *
	 * @param int $id
	 * @return bool
	 */
	public static function deleteAlbumById($id)
	{
		$id = (int) $id;
		$db = BackendModel::getDB(true);

		// get item
		$item = self::getAlbumFromId($id);

		// build extra
		$extra = array(
			'id' => $item['extra_id'], 
			'module' => 'galleria', 
			'type' => 'widget', 
			'action' => 'gallery'
		);

		// delete extra
		$db->delete('modules_extras', 'id = ? AND module = ? AND type = ? AND action = ?', 
			array(
				$extra['id'], 
				$extra['module'], 
				$extra['type'], 
				$extra['action']
		));

		// update blocks with this item linked
		$db->update('pages_blocks',
		array(
			'extra_id' => null,
			'html' => ''),
			'extra_id = ?',
			array(
				$item['extra_id']
			)
		);

		// delete all records
		$db->delete('galleria_albums', 'id = ? AND language = ?', array($id, BL::getWorkingLanguage()));
	}
	
	/**
	 * Delete a category
	 *
	 * @param int $id		The id of the category to be deleted.
	 * @return bool
	 */
	public static function deleteCategoryById($id)
	{
		// delete the widget
		return (bool) BackendModel::getDB(true)->delete('galleria_categories', 'id = ?', array((int) $id));
	}
	
	/**
	 * Delete a widget
	 *
	 * @param int $id The id of the widget to be deleted.
	 * @return bool
	 */
	public static function deleteWidgetById($id)
	{
		// delete the widget
		return (bool) BackendModel::getDB(true)->delete('modules_extras', 'id = ?', array((int) $id));
	}

	/**
	 * Does the image exist?
	 *
	 * @param int $id
	 * @return bool
	 */
	public static function exists($id)
	{
		return (bool) BackendModel::getDB()->getVar(
			'SELECT COUNT(i.id)
		 FROM galleria_images AS i
		 WHERE i.id = ? AND i.language = ?',
			array((int) $id, BL::getWorkingLanguage()));
	}
	
	/**
	 * Does the album exist?
	 *
	 * @param int $id
	 * @return bool
	 */
	public static function existsAlbum($id)
	{
		return (bool) BackendModel::getDB()->getVar(
		'SELECT COUNT(i.id)
		 FROM galleria_albums AS i
		 WHERE i.id = ? AND i.language = ?',
		 array((int) $id, BL::getWorkingLanguage()));
	}
	
	/**
	 * Does the category exist?
	 *
	 * @param int $id
	 * @return bool
	 */
	public static function existsCategory($id)
	{
		return (bool) BackendModel::getDB()->getVar(
		'SELECT COUNT(i.id)
		 FROM galleria_categories AS i
		 WHERE i.id = ? AND i.language = ?',
		 array((int) $id, BL::getWorkingLanguage()));
	}

	/**
	 * Get image by id
	 *
	 * @param int $id
	 * @return array
	 */
	public static function get($id)
	{
		return (array) BackendModel::getDB()->getRecord(
			'SELECT i.*
		 FROM galleria_images AS i
		 WHERE i.language = ? AND i.id = ?',
			array(BL::getWorkingLanguage(),(int) $id));
	}

	/**
	 * Get album by id
	 *
	 * @param int $id
	 * @return array
	 */
	public static function getAlbumFromId($id)
	{
		return (array) BackendModel::getDB()->getRecord(
		'SELECT i.*
		 FROM galleria_albums AS i
		 WHERE i.language = ? AND i.id = ?',
		 array(BL::getWorkingLanguage(),(int) $id));
	}
	
	 /**
	 * Get all albumnames for dropdown
	 *
	 * @return array
	 */
	public static function getAlbumsForDropdown()
	{
		return (array) BackendModel::getDB()->getPairs(
		'SELECT i.id, i.title
		FROM galleria_albums AS i
		WHERE i.language = ?
		ORDER BY i.id ASC',
		array(BL::getWorkingLanguage()));
	}
	
	 /**
	 * Get all category names for dropdown
	 *
	 * @return array
	 */
	public static function getCategoriesForDropdown()
	{
		return (array) BackendModel::getDB()->getPairs(
		'SELECT i.id, i.title
		FROM galleria_categories AS i
		WHERE i.language = ?
		ORDER BY i.id ASC',
		array(BL::getWorkingLanguage()));
	}
	
	/**
	 * Get category by id
	 *
	 * @param int $id
	 * @return array
	 */
	public static function getCategoryFromId($id)
	{
		return (array) BackendModel::getDB()->getRecord(
		'SELECT i.*
		 FROM galleria_categories AS i
		 WHERE i.language = ? AND i.id = ?',
		 array(BL::getWorkingLanguage(),(int) $id));
	}

	/**
	 * Get the maximum sequence for an album
	 *
	 * @param int $album_id
	 * @return int
	 */
	public static function getMaximumImageSequence($album_id)
	{
		return (int) BackendModel::getDB()->getVar(
			'SELECT MAX(i.sequence)
			 FROM galleria_images AS i
			 WHERE i.language = ? AND album_id = ?',
			array(BL::getWorkingLanguage(), $album_id));
	}

	/**
	 * Get the maximum sequence for an album
	 *
	 * @return int
	 */
	public static function getMaximumAlbumSequence()
	{
		return (int) BackendModel::getDB()->getVar(
			'SELECT MAX(i.sequence)
			 FROM galleria_albums AS i
			 WHERE i.language = ? AND hidden = ?',
			 array(BL::getWorkingLanguage(), 'N'));
	}
	
	/**
	 * Get the maximum sequence for a category
	 *
	 * @return int
	 */
	public static function getMaximumCategorySequence()
	{
		return (int) BackendModel::getDB()->getVar(
			'SELECT MAX(i.sequence)
			 FROM galleria_categories AS i
			 WHERE i.language = ? AND hidden = ?',
			 array(BL::getWorkingLanguage(), 'N'));
	}

	/**
	 * Get the images for an album
	 *
	 * @param int $id
	 * @return bool
	 */
	public static function getImagesForAlbum($id)
	{
		$records = (array) BackendModel::getDB()->getRecords(
			'SELECT i.*
			 FROM galleria_images AS i
			 WHERE i.language = ? AND i.album_id = ?
			 ORDER BY sequence',
			array(BL::getWorkingLanguage(),(int) $id));

		//--Loop records
		if(!empty($records))
		{
			//--Get the thumbnail-folders
			$folders = BackendModel::getThumbnailFolders(FRONTEND_FILES_PATH . '/galleria/images', true);

			//--Create the image-links to the thumbnail folders
			foreach($records as &$row)
			{
				foreach($folders as $folder) $row['image_' . $folder['dirname']] = $folder['url'] .  '/' . $folder['dirname'] . '/'  . $row['filename'];
			}
			//--Destroy the last $image (because of the reference) -- sugested by http://php.net/manual/en/control-structures.foreach.php
			unset($row);
		}

		return $records;
	}

	/**
	 * Insert an album in the database
	 *
	 * @param array $item
	 * @return int
	 */
	public static function insertAlbum(array $item)
	{
		$db = BackendModel::getDB(true);

		// insert and return the id
		$item['id'] = $db->insert('galleria_albums', $item);

		// build extra for the gallery-widget
		$extra = array(
			'module' => 'galleria', 
			'type' => 'widget', 
			'label' => 'galleria', 
			'action' => 'gallery',
			'data' => serialize(array(
				'id' => $item['id'],
				'extra_label' => "Gallery " . $item['title'],
				'language' => $item['language'],
				'edit_url' => BackendModel::createURLForAction('edit_album') . '&id=' . $item['id']
			)),
			'hidden' => 'N', 
			'sequence' => $db->getVar(
				'SELECT MAX(i.sequence) + 1
				 FROM modules_extras AS i
				 WHERE i.module = ?',
				array('links')
		));

		if(is_null($extra['sequence'])) $extra['sequence'] = $db->getVar(
			'SELECT CEILING(MAX(i.sequence) / 1000) * 1000
			 FROM modules_extras AS i'
		);

		// insert extra gallery-widget
		$item['extra_id'] = $db->insert('modules_extras', $extra);


		// build extra for the slideshow-widget
		$extra = array(
			'module' => 'galleria',
			'type' => 'widget',
			'label' => 'galleria',
			'action' => 'slideshow',
			'data' => serialize(array(
				'id' => $item['id'],
				'extra_label' => "Slideshow " . $item['title'],
				'language' => $item['language'],
				'edit_url' => BackendModel::createURLForAction('edit_album') . '&id=' . $item['id']
			)),
			'hidden' => 'N',
			'sequence' => $db->getVar(
				'SELECT MAX(i.sequence) + 1
				 FROM modules_extras AS i
				 WHERE i.module = ?',
				array('links')
			));

		if(is_null($extra['sequence'])) $extra['sequence'] = $db->getVar(
			'SELECT CEILING(MAX(i.sequence) / 1000) * 1000
			 FROM modules_extras AS i'
		);

		// insert extra slideshow-widget
		$item['extra_id'] = $db->insert('modules_extras', $extra);

		return $item['id'];

	}

	/**
	 * Insert an item in the database
	 *
	 * @param array $data
	 * @return int
	 */
	public static function insertCategory(array $data)
	{
		return (int) BackendModel::getDB(true)->insert('galleria_categories', $data);
	}
	
	/**
	 * Save the widget
	 *
	 * @param array $widget
	 * @return int The id
	 */
	public static function insertWidget(array $widget)
	{
		$db = BackendModel::getDB(true);

		// get widget sequence
		$widget['sequence'] =  $db->getVar('SELECT MAX(i.sequence) + 1 FROM modules_extras AS i WHERE i.module = ?',
		array($widget['module']));

		if(is_null($widget['sequence']))
		{
			$widget['sequence'] = $db->getVar('SELECT CEILING(MAX(i.sequence) 
			/ 1000) * 1000 FROM modules_extras AS i');
		}

		// Save widget
		return (int) $db->insert('modules_extras', $widget);
	}

	/**
	 * Update a certain album
	 *
	 * @param array $item
	 * @return bool
	 */
	public static function updateAlbum(array $item)
	{
		$db = BackendModel::getDB(true);

		// build extra
		$extra = array(
			'id' => $item['extra_id'], 
			'module' => 'galleria', 
			'type' => 'widget', 
			'label' => 'galleria', 
			'action' => 'widget', 
			'data' => serialize(array(
									'id' => $item['id'], 
									'extra_label' => $item['title'], 
									'language' => $item['language'], 
									'edit_url' => BackendModel::createURLForAction('edit_album') . '&id=' . $item['id'])), 
									'hidden' => 'N'
		);

		// update extra
		$db->update('modules_extras', $extra, 'id = ? ', array($item['extra_id']));

		// update the category
		$update = $db->update('galleria_albums', $item, 'id = ?', array($item['id']));

		// return the id
		return $update;
	}

	/**
	 * Update an image
	 *
	 * @param array $item
	 * @return bool
	 */
	public static function update(array $item)
	{
		return (bool) BackendModel::getDB(true)->update('galleria_images',(array) $item, 'id = ?', array($item['id']));
		BackendModel::invalidateFrontendCache('galleria', BL::getWorkingLanguage());
	}

	/**
	 * Update a certain category
	 *
	 * @param array $item
	 * @return bool
	 */
	public static function updateCategory(array $item)
	{
		return (bool) BackendModel::getDB(true)->update('galleria_categories',(array) $item, 'id = ?', array($item['id']));
		BackendModel::invalidateFrontendCache('galleria', BL::getWorkingLanguage());
	}
	
	/**
	 * update a widget
	 *
	 * @param int $id The id of the widget to be deleted.
	 * @return bool
	 */
	public static function updateWidget($widget)
	{
		// delete the widget
		return (bool) BackendModel::getDB(true)->update('modules_extras',(array) $widget,'id = ?', array((int) $widget['id']));
	}

	/**
	 * Retrieve the unique URL for an item
	 *
	 * @param string $URL The URL to base on.
	 * @param int[optional] $id The id of the item to ignore.
	 * @return string
	 */
	public static function getURL($URL, $id = null)
	{
		$URL = (string) $URL;

		// get db
		$db = BackendModel::getDB();

		// new item
		if($id === null)
		{
			// already exists
			if((bool) $db->getVar(
				'SELECT 1
				 FROM galleria_albums AS i
				 INNER JOIN meta AS m ON i.meta_id = m.id
				 WHERE i.language = ? AND m.url = ?
				 LIMIT 1',
				array(BL::getWorkingLanguage(), $URL)))
			{
				$URL = BackendModel::addNumber($URL);
				return self::getURL($URL);
			}
		}

		// current category should be excluded
		else
		{
			// already exists
			if((bool) $db->getVar(
				'SELECT 1
				 FROM galleria_albums AS i
				 INNER JOIN meta AS m ON i.meta_id = m.id
				 WHERE i.language = ? AND m.url = ? AND i.id != ?
				 LIMIT 1',
				array(BL::getWorkingLanguage(), $URL, $id)))
			{

				$URL = BackendModel::addNumber($URL);
				return self::getURL($URL, $id);
			}
		}

		return $URL;
	}

	/**
	 * Insert an item in the database
	 *
	 * @param array $data
	 * @return int
	 */
	public static function insert(array $data)
	{

		$insertId = (int) BackendModel::getDB(true)->insert('galleria_images', $data);

		return $insertId;
	}

	/**
	 *
	 * Delete image from an album
	 *
	 * @param $id
	 */
	public static function delete($id)
	{

		//--Get the image
		$image = BackendGalleriaModel::get((int) $id);

		if(!empty($image))
		{
			//--Get folders
			$folders = BackendModel::getThumbnailFolders(FRONTEND_FILES_PATH . '/galleria/images', true);

			//--Loop the folders
			foreach($folders as $folder)
			{
				//--Delete the image
				SpoonFile::delete($folder['url'] .  '/' . $folder['dirname'] . '/'  . $image['filename']);
			}

			//--Delete images from the database
			BackendModel::getDB()->delete("galleria_images", "id=?", array($id));
		}
	}

	/**
	 * Build the filename
	 *
	 * @param $filename
	 * @param $extension
	 * @param $try
	 *
	 */
	public static function checkFilename($filename = "", $extension = "", $try = 0)
	{
		if($try > 0)
		{
			$filename_full = $filename . $try . "." . $extension;
		}
		else
		{
			//--Get filename
			$filename_full = $filename . "." . $extension;
		}

		$db = BackendModel::getDB();
		$record = $db->getRecord("SELECT filename FROM galleria_images WHERE filename = ?", array($filename_full));
		if(is_null($record))
		{
			return $filename_full;
		}
		else
		{
			//--Get new filename
			return self::checkFilename($filename, $extension, $try +1);
		}
	}

}
