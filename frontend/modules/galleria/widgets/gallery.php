<?php

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * This is a frontend widget
 *
 * @author Waldo Cosman <waldo@comsa.be>
 */
class FrontendGalleriaWidgetGallery extends FrontendBaseWidget
{
	/**
	 * @var array
	 */
	private $record;

	/**
	 * Exceute the action
	 */
	public function execute()
	{
		parent::execute();

		//--Add css
		$this->header->addCSS('/frontend/modules/' . $this->getModule() . '/layout/css/galleria.css');
		$this->header->addCSS('/frontend/modules/' . $this->getModule() . '/layout/css/colorbox.css');

		//--Add javascript
		$this->header->addJS('/frontend/modules/' . $this->getModule() . '/js/jquery.colorbox-min.js');
		$this->header->addJS('/frontend/modules/' . $this->getModule() . '/js/jquery.cycle.all.js', false);

		$this->loadTemplate();
		$this->loadData();

		$this->parse();
	}

	/**
	 * Load the data
	 */
	private function loadData()
	{
		$this->record = FrontendGalleriaModel::getImagesForAlbum($this->data['id']);
	}

	/**
	 * Parse the widget
	 */
	protected function parse()
	{
		$this->tpl->assign('widgetGallery', $this->record);
	}
}
