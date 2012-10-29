<?php

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * This is the Albums action
 *
 * @author John Poelman <john.poelman@bloobz.be>
 */
class BackendGalleriaAlbums extends BackendBaseAction
{
	/**
	 * Execute the action
	 *
	 * @return void
	 */
	public function execute()
	{
		// call parent, this will probably add some general CSS/JS or other required files
		parent::execute();

		// load datagrids
		$this->loadDataGrid();

		// parse page
		$this->parse();

		// display the page
		$this->display();
	}

	/**
	 * Loads the datagrid
	 *
	 * @return void
	 */
	private function loadDataGrid()
	{
		// create datagrid
		$this->dataGrid = new BackendDataGridDB(BackendGalleriaModel::QRY_DATAGRID_ALBUMS, BL::getWorkingLanguage());

		// disable paging
		$this->dataGrid->setPaging(false);

		// set hidden columns
		$this->dataGrid->setColumnsHidden(array('language','sequence','meta_id','id','category_id','publish_on', 'extra_id'));
		
		// set column URLs
		$this->dataGrid->setColumnURL('title', BackendModel::createURLForAction('add') . '&amp;id=[id]');
		
		// add drag and dropp stuff
		$this->dataGrid->enableSequenceByDragAndDrop();
		$this->dataGrid->setAttributes(array('class' => 'dataGrid sequenceByDragAndDrop'));
		$this->dataGrid->setColumnsSequence('dragAndDropHandle');
		$this->dataGrid->setAttributes(array('data-action' => "album_sequence"));

		// add edit column
		$this->dataGrid->addColumn('add', null, BL::lbl('Add'), BackendModel::createURLForAction('add') . '&amp;id=[id]');
		$this->dataGrid->setHeaderLabels(array('add' => SpoonFilter::ucfirst(BL::lbl('AddImages'))));
		$this->dataGrid->addColumn('edit', null, BL::lbl('Edit'), BackendModel::createURLForAction('edit_album') . '&amp;id=[id]');
		$this->dataGrid->setHeaderLabels(array('edit' => SpoonFilter::ucfirst(BL::lbl('EditAlbum'))));
	}

	/**
	 * Parse & display the page
	 *
	 * @return void
	 */
	protected function parse()
	{
		$this->tpl->assign('dataGrid', ($this->dataGrid->getNumResults() != 0) ? $this->dataGrid->getContent() : false);
	}
}