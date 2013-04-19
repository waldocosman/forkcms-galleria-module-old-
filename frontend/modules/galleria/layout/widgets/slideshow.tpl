{*
	variables that are available:
	- {$widgetSlideshow}: contains all the data for this widget
*}
{option:widgetSlideshow}
<ul class="galleria-slideshow unstyled">
    {iteration:widgetSlideshow}
        <li>
            <img src="{$widgetSlideshow.image_128x128}" alt="{$widgetSlideshow.filename}" title="{$widgetSlideshow.filename}">
        </li>
    {/iteration:widgetSlideshow}
</ul>
{/option:widgetSlideshow}