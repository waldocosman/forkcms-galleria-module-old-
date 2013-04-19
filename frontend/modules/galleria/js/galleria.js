/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * Interaction for the galleria module
 *
 * @author John Poelman <john.poelman@bloobz.be>
 * @author Waldo Cosman <waldo@comsa.be>
 */
jsFrontend.galleria =
{
	// constructor
	init: function()
	{
        //--Initialize colorbox to the gallery
        $('ul.galleria-gallery li a').colorbox({rel:'group'});


        //--Initialize slidehow
        $('ul.galleria-slideshow').cycle();
	}
}

$(jsFrontend.galleria.init);
