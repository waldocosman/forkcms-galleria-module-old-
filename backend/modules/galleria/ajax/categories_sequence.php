<?php

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * Reorder categories
 *
 * @author Lester Lievens <lester.lievens@netlash.com>
 * @author John Poelman <john.poelman@bloobz.be>
 */
class BackendGalleriaAjaxSequence extends BackendBaseAJAXAction
{
	/**
	 * Execute the action
	 */
	public function execute()
	{
		parent::execute();
		
		// get parameters
		$newIdSequence = trim(SpoonFilter::getPostValue('new_id_sequence', null, '', 'string'));

		// list id
		$ids = (array) explode(',', rtrim($newIdSequence, ','));

		// loop id's and set new sequence
		foreach($ids as $i => $id)
		{
			// build item
			$item['id'] = (int) $id;

			// change sequence
			$item['sequence'] = $i + 1;

			// update sequence
			if(BackendGalleriaModel::existsCategory($item['id'])) BackendGalleriaModel::updateCategory($item);
		}

		// success output
		$this->output(self::OK, null, 'sequence updated');
	}
}
