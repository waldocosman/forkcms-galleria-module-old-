{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

<div class="pageTitle">
    <h2>{$lblGalleria|ucfirst}: {$lblEditAlbum}</h2>
</div>
<div id="pageUrl">
    <div class="oneLiner">
        {option:detailURL}<p><span><a href="{$detailURL}/{$album.url}">{$detailURL}
                /<span id="generatedUrl">{$album.url}</span></a></span></p>{/option:detailURL}
        {option:!detailURL}<p class="infoMessage">{$errNoModuleLinked}</p>{/option:!detailURL}
    </div>
</div>
<div class="tabs">
    <ul>
        <li><a href="#tabContent">{$lblContent|ucfirst}</a></li>
        <li><a href="#tabImages">{$lblImages|ucfirst}</a></li>
    </ul>
    <div id="tabContent">
        {form:edit_album}
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td id="leftColumn">
                        <div class="box">
                            <div class="optionsRTE">
                                <p>
                                    <label for="title">{$lblTitle|ucfirst}
                                        <abbr title="{$lblRequiredField}">*</abbr></label>
                                    {$txtTitle} {$txtTitleError}
                                </p>
                            </div>
                            <div class="heading">
                                <h3>
                                    <label for="text">{$lblDescription|ucfirst}
                                        <abbr title="{$lblRequiredField}">*</abbr></label>
                                </h3>
                            </div>
                            <div class="optionsRTE">
                                {$txtDescription} {$txtDescriptionError}
                            </div>
                        </div>
                    </td>
                    <td id="sidebar">
                        <div id="publishOptions" class="box">
                            <div class="heading">
                                <h3>{$lblCategory|ucfirst}</h3>
                            </div>
                            <div class="options">
                                {$ddmCategory}
                            </div>
                        </div>
                        <div class="box">
                            <div class="heading">
                                <h3>{$lblStatus|ucfirst}</h3>
                            </div>
                            <div class="options">
                                <ul class="inputList">
                                    {iteration:hidden}
                                        <li>
                                            {$hidden.rbtHidden}
                                            <label for="{$hidden.id}">{$hidden.label}</label>
                                        </li>
                                    {/iteration:hidden}
                                </ul>
                            </div>
                        </div>
                        <div id="publishOptions" class="box">
                            <div class="heading">
                                <h3>{$lblShowInOverview|ucfirst}</h3>
                            </div>
                            <div class="options">
                                <ul class="inputList">
                                    {iteration:show_in_overview}
                                        <li>
                                            {$show_in_overview.rbtShowInOverview}
                                            <label for="{$show_in_overview.id}">{$show_in_overview.label}</label>
                                        </li>
                                    {/iteration:show_in_overview}
                                </ul>
                            </div>
                        </div>
                        <div class="box">
                            <div class="heading">
                                <h3>{$lblTags|ucfirst}</h3>
                            </div>
                            <div class="options">
                                <label for="tags">{$lblTags|ucfirst}</label>
                                {$txtTags} {$txtTagsError}
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
            <div class="fullwidthOptions">
                {option:showGalleriaDeleteAlbum}
                    <a href="{$var|geturl:'delete_album'}&amp;id={$album.id}" data-message-id="confirmDelete" class="askConfirmation button linkButton icon iconDelete">
                        <span>{$lblDelete|ucfirst}</span>
                    </a>
                    <div id="confirmDelete" title="{$lblDelete|ucfirst}?" style="display: none;">
                        <p>
                            {$msgConfirmDeleteAlbum|sprintf:{$album.title}|ucfirst}
                        </p>
                    </div>
                {/option:showGalleriaDeleteAlbum}
                <div class="buttonHolderRight">
                    <input id="editButton" class="inputButton button mainButton" type="submit" name="edit" value="{$lblSave|ucfirst}"/>
                </div>
            </div>
        {/form:edit_album}
    </div>
    <!-- div#tabContent -->

    <div id="tabImages">
        {form:add_image}
            <div class="box" style="margin-bottom:20px;">
                <div class="heading">
                    <h3>{$lblUploadImage}</h3>
                </div>
                <div class="box">
                    {$fileImages} {$fileImagesError}
                </div>
                {*<div class="box">*}
                {*<input id="addImageButton" class="inputButton button mainButton" type="submit" name="add" value="{$lblAddImage|ucfirst}" />*}
                {*</div>*}
                <div class="clearfix">&nbsp;</div>
            </div>
        {/form:add_image}

        {form:delete_image}
            <div class="box">
                <div class="heading">
                    <h3>{$lblImages|ucfirst}</h3>
                </div>
                <!-- div.heading -->

                <ul class="galleria">
                    {iteration:images}
                        <li id="image-{$images.id}">
                            <img src="{$images.image_128x128}" alt="{$images.filename}" title="{$images.filename}"/>
                            {$images.field_delete}<label for="delete{$images.id}">{$lblDelete|ucfirst}</label>
                        </li>
                    {/iteration:images}
                </ul>
                <!-- ul.galleria -->
                <div class="clearfix">&nbsp;</div>
            </div>
            <!-- div.box -->
            <div class="buttonHolderRight">
                <input id="deleteButton" class="inputButton button mainButton" type="submit" name="delete" value="{$lblDeleteSelectedImages|ucfirst}"/>
            </div>
        {/form:delete_image}
    </div>
    <!-- div#tabImages -->

</div>
<!-- div.tabs -->


{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}