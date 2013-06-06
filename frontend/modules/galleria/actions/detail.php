<?php

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * This is the Detail-action, it will display the overview of galleria posts
 *
 * @author Waldo Cosman <waldo@comsa.be>
 */
class FrontendGalleriaDetail extends FrontendBaseBlock
{
	/**
	 * The record data
	 *
	 * @var array
	 */
	private $record;

	/**
	 * Execute the action
	 */
	public function execute()
	{
		parent::execute();
		$this->loadTemplate();
		$this->loadData();

		//--Add css
		$this->header->addCSS('/frontend/modules/' . $this->getModule() . '/layout/css/galleria.css');
		$this->header->addCSS('/frontend/modules/' . $this->getModule() . '/layout/css/colorbox.css');

		//--Add javascript
		$this->header->addJS('/frontend/modules/' . $this->getModule() . '/js/jquery.colorbox-min.js');
		$this->header->addJS('/frontend/modules/' . $this->getModule() . '/js/jquery.cycle.all.js');

		$this->parse();
	}

	/**
	 * Load the data
	 */
	protected function loadData()
	{

		//--Check the params
		if($this->URL->getParameter(1) === null) $this->redirect(FrontendNavigation::getURL(404));

		//--Get record
		$this->record = FrontendGalleriaModel::getAlbum($this->URL->getParameter(1));

		//--Redirect if empty
		if(empty($this->record))
		{
			$this->redirect(FrontendNavigation::getURL(404));
		}
	}

	/**
	 * Parse the page
	 */
	protected function parse()
	{
		$this->tpl->assign('item', $this->record);
	}
}
