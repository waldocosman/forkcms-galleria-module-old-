<?php

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * Installer for the galleria module
 *
 * @author John Poelman <john.poelman@bloobz.be>
 */
class GalleriaInstaller extends ModuleInstaller
{
	public function install()
	{
		// import the sql
		$this->importSQL(dirname(__FILE__) . '/data/install.sql');

		// install the module in the database
		$this->addModule('galleria');

		// install the locale, this is set here beceause we need the module for this
		$this->importLocale(dirname(__FILE__) . '/data/locale.xml');
		
		// modulerights
		$this->setModuleRights(1, 'galleria');

		// actionrights
		$this->setActionRights(1, 'galleria', 'albums');
		$this->setActionRights(1, 'galleria', 'add_album');
		$this->setActionRights(1, 'galleria', 'edit_album');
		$this->setActionRights(1, 'galleria', 'delete_album');
		$this->setActionRights(1, 'galleria', 'categories');
		$this->setActionRights(1, 'galleria', 'add_category');
		$this->setActionRights(1, 'galleria', 'edit_category');
		$this->setActionRights(1, 'galleria', 'delete_category');
		$this->setActionRights(1, 'galleria', 'add');
		$this->setActionRights(1, 'galleria', 'edit');
		$this->setActionRights(1, 'galleria', 'delete');
		$this->setActionRights(1, 'galleria', 'settings');

		// add extra's
		$this->insertExtra('galleria', 'widget', 'Slideshow', 'slideshow');
		$this->insertExtra('galleria', 'widget', 'Gallery', 'gallery');
		$galleriaID = $this->insertExtra('galleria', 'block', 'Galleria', null, null, 'N', 1000);
				
		// module navigation
		$navigationModulesId = $this->setNavigation(null, 'Modules');
		$navigationGalleriaId = $this->setNavigation($navigationModulesId, 'Galleria', 'galleria/albums');
		
		$this->setNavigation($navigationGalleriaId, 'Albums', 'galleria/albums', array(
				'galleria/add_album',
				'galleria/edit_album',
				'galleria/delete_album',
				'galleria/add',
				'galleria/edit',
				'galleria/delete'
		));
		
		$this->setNavigation($navigationGalleriaId, 'Categories', 'galleria/categories', array(
				'galleria/add_category',
				'galleria/edit_category',
				'galleria/delete_category'
		));
		
		// settings navigation
		$navigationSettingsId = $this->setNavigation(null, 'Settings');
		$navigationModulesId = $this->setNavigation($navigationSettingsId, 'Modules');
		$this->setNavigation($navigationModulesId, 'Galleria', 'galleria/settings');
		
		// loop languages
		foreach($this->getLanguages() as $language)
		{
			// check if a page for galleria already exists in this language
			// @todo refactor this if statement
			if((int) $this->getDB()->getVar('SELECT COUNT(id)
					FROM pages AS p
					INNER JOIN pages_blocks AS b ON b.revision_id = p.revision_id
					WHERE b.extra_id = ? AND p.language = ?',
					array($galleriaID, $language)) == 0)
			{
				// insert galleria page
				$this->insertPage(
						array(
								'title' => 'Galleria',
								'type' => 'root',
								'language' => $language
						),
						null,
						array('extra_id' => $galleriaID, 'position' => 'main'));
			}
		}
	}
}
