

/**
 * Interaction for the galleria module
 *
 * @author Waldo Cosman <waldo@comsa.be>
 */
jsBackend.galleria =
{
    // constructor
    init: function()
    {
        // do meta
        if($('#title').length > 0) $('#title').doMeta();
    }
};

$(jsBackend.galleria.init);
