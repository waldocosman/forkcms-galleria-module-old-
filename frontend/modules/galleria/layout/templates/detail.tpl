{option:item}
    <div id="galleriaIndex">
        <article class="mod">
            <h3>{$item.title}</h3>
            {$item.description}
        </article>
    </div>
{option:item.images}
    <ul class="galleria-gallery unstyled">
        {iteration:item.images}
            <li>
                <a href="{$item.images.image_source}"><img src="{$item.images.image_128x128}" alt="{$item.images.filename}" title="{$item.images.filename}"></a>
            </li>
        {/iteration:item.images}
    </ul>
    <div class="clear">&nbsp;</div>
{/option:item.images}
{/option:item}

