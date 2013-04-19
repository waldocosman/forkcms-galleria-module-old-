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

        //--Initialise uploadify
        jsBackend.galleria.uploadify();
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
    },
    uploadify: function()
    {

        $('#images').uploadify({
            'swf'      : '/backend/modules/galleria/swf/uploadify.swf',
            'buttonText': jsBackend.locale.lbl('ChooseImages'),
            'width': 218,
            'height': 24,
            'uploader' : '/backend/ajax.php',
            'formData' : {'fork[module]': "galleria", 'fork[action]': "uploadify",'fork[language]': jsBackend.current.language, 'id': jsData.galleria.id},
            'fileObjName': 'images',
            'onQueueComplete': function(queueData)
            {
                var randomNumber = Math.floor(Math.random()*11)

                window.location.replace("/private/"+ jsBackend.current.language + "/galleria/edit_album?token=true&id=" + jsData.galleria.id + "&report=added-images&random=" + randomNumber + "#tabImages");
            }
        });
    }
}

$(jsBackend.galleria.init);