{option:items}
    <ul class="unstlyed">
        {iteration:items}
            <li>
                <h3><a href="{$items.full_url}" title="{$items.title}">{$items.title}</a></h3>
                {option:items.image}
                    <a href="{$items.full_url}" title="{$items.title}">
                        <img src="{$items.image.image_128x128}" alt="{$items.title}" title="{$items.title}"/>
                    </a>
                {/option:items.image}
            </li>
        {/iteration:items}
    </ul>
{/option:items}