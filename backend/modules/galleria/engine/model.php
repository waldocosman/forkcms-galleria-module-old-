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
	 * Convert the title to a widgetlabel
	 * 
	 * @param string $string
	 * @return string $label
	 */
	public static function createWidgetLabel($string)
	{
		// convert the item to camelcase
		$label 	= preg_replace('/\s+/', '_', $string);
		$label	= SpoonFilter::toCamelCase((string) $label);
		return (string) $label;
	}
	
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
		// delete the record
		return (bool) BackendModel::getDB(true)->delete('galleria_albums', 'id = ?', array((int) $id));
	}
	
	/**
	 * Delete a category
	 *
	 * @param int $id		The id of the category to be deleted.
	 * @return bool
	 */
	public static function deleteCategoryById($id)
	{
		// delete the record
		return (bool) BackendModel::getDB(true)->delete('galleria_categories', 'id = ?', array((int) $id));
	}
	
	/**
	 * Delete id's
	 *
	 * @param int $id The id of the link to be deleted.
	 * @return bool
	 */

	public static function deleteIdsByAlbumId($id)
	{
		// delete the record
		return (bool) BackendModel::getDB(true)->delete('galleria_extra_ids', 'album_id = ?', array((int) $id));
	}
	
	/**
	 * Delete meta
	 *
	 * @param int $id
	 * @return bool
	 */
	public static function deleteMeta($id)
	{
		// delete the record
		return (bool) BackendModel::getDB(true)->delete('meta', 'id = ?', array((int) $id));
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
	 * Get extra ids for this album
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function getExtraIdsForAlbum($id)
	{
		return (array) BackendModel::getDB()->getRecord(
		'SELECT i.* 
		FROM galleria_extra_ids AS i
		WHERE i.album_id = ?',
		array((int) $id));
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
	 * Retrieve the unique url for an item
	 *
	 * @param string $url
	 * @param int[optional] $id
	 * @return string
	 */
	public static function getUrl($url, $id = null)
	{
		// redefine Url
		$url = SpoonFilter::urlise((string) $url);

		// get db
		$db = BackendModel::getDB();

		// new item
		if($id === null)
		{
			$numberOfItems = (int) $db->getVar(
				'SELECT COUNT(i.id)
				 FROM galleria_categories AS i
				 INNER JOIN meta AS m ON i.meta_id = m.id
				 WHERE i.language = ? AND m.url = ?',
				array(BL::getWorkingLanguage(), $url));

			// already exists
			if($numberOfItems != 0)
			{
				// add number
				$url = BackendModel::addNumber($url);

				// try again
				return self::getUrl($url);
			}
		}
		// current category should be excluded
		else
		{
			$numberOfItems = (int) $db->getVar(
				'SELECT COUNT(i.id)
				 FROM galleria_categories AS i
				 INNER JOIN meta AS m ON i.meta_id = m.id
				 WHERE i.language = ? AND m.url = ? AND i.id != ?',
				array(BL::getWorkingLanguage(), $url, $id));

			// already exists
			if($numberOfItems != 0)
			{
				// add number
				$url = BackendModel::addNumber($url);

				// try again
				return self::getUrl($url, $id);
			}
		}

		// return the unique Url!
		return $url;
	}

	/**
	 * Insert an album in the database
	 *
	 * @param array $data
	 * @return int
	 */
	public static function insertAlbum(array $data)
	{
		return (int) BackendModel::getDB(true)->insert('galleria_albums', $data);
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
	 * Store all ids
	 * 
	 * @param array $ids
	 * @return bool 
	 */
	public static function storeAllIds(array $ids)
	{
		return (bool) $stored = BackendModel::getDB(true)->insert('galleria_extra_ids',(array) $ids);
	}

	/**
	 * Update a certain album
	 *
	 * @param array $item
	 * @return bool
	 */
	public static function updateAlbum(array $item)
	{
		return (bool) BackendModel::getDB(true)->update('galleria_albums',(array) $item, 'id = ?', array($item['id']));
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
}
