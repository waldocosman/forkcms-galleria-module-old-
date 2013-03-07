/**
 * Interaction for the galleria-module
 *
 *
 *
 */
jsBackend.galleria =
{

    init: function()
    {
        //--Initialise sortable
        jsBackend.galleria.bindSortable();
    },

    bindSortable: function()
    {
        //--Add sortable to the galleria-lists
        $('ul.galleria').sortable(
        {
            handle: 'img',
            tolerance: 'pointer',
            stop: function(e, ui)				// on stop sorting
            {
                var arrIds = Array();

                //--Loop the children
                $(this).children('li').each(function(index, element)
                {
                    //--Get the id from the element and push it into an array
                    arrIds.push($(element).attr('id').substr(6));
                });

                //--Create a string of the array with a , delimeter.
                var strIds = arrIds.join(',');

            //--Create ajax-call
                $.ajax(
                {
                    data:
                    {
                        fork: { action: 'images_sequence' },
                        ids: strIds
                    },
                    success: function(data, textStatus)
                    {
                        //--Check if the response is correct
                        if(data.code == 200)
                        {
                            jsBackend.messages.add('success', jsBackend.locale.lbl('SequenceSaved'));
                        }

                        //--If there is an error, alert the message
                        if(data.code != 200 && jsBackend.debug){ alert(data.message); }

                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown)
                    {
                        // revert
                        $(this).sortable('cancel');

                        // show message
                        jsBackend.messages.add('error', 'alter sequence failed.');

                        // alert the user
                        if(jsBackend.debug){ alert(textStatus); }
                    }
                })
            }
        });
    }
}

$(jsBackend.galleria.init);