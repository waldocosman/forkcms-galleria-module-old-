{*
	variables that are available:
	- {$widgetGallery}: contains all the data for this widget
*}

{option:widgetGallery}
    <ul class="galleria-gallery unstyled">
    {iteration:widgetGallery}
        <li>
            <a href="{$widgetGallery.image_source}"><img src="{$widgetGallery.image_128x128}" alt="{$widgetGallery.filename}" title="{$widgetGallery.filename}"></a>
        </li>
    {/iteration:widgetGallery}
    </ul>
    <div class="clear">&nbsp;</div>
{/option:widgetGallery}
<img src="http://distilleryimage9.s3.amazonaws.com/334e2b3e829511e28d1322000a1fb079_6.jpg" alt=""/>
<img src="http://distilleryimage3.s3.amazonaws.com/9df1d5b472e411e2992f22000a1fb823_6.jpg" alt=""/>