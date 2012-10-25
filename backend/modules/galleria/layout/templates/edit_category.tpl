{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}
<div class="pageTitle">
	<h2>{$lblGalleria|ucfirst}: {$lblEditCategory}</h2>
</div>
{form:edit_category}
<div class="ui-tabs">
	<div class="ui-tabs-panel">
		<div class="options">
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td id="leftColumn">
						<div class="box">
							<div class="optionsRTE">
								<p>
									<label for="title">{$lblTitle|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
									{$txtTitle} {$txtTitleError}
								</p>
							</div>
						</div>
					</td>
					<td id="sidebar">
						<div id="publishOptions" class="box">
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
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>
<div class="fullwidthOptions">
		{option:showGalleriaDeleteCategory}
			<a href="{$var|geturl:'delete_category'}&amp;id={$category.id}" data-message-id="confirmDelete" class="askConfirmation button linkButton icon iconDelete">
				<span>{$lblDelete|ucfirst}</span>
			</a>
			<div id="confirmDelete" title="{$lblDelete|ucfirst}?" style="display: none;">
				<p>
					{$msgConfirmDeleteCategory|sprintf:{$category.title}|ucfirst}
				</p>
			</div>
		{/option:showGalleriaDeleteCategory}
		
		<div class="buttonHolderRight">
			<input id="editButton" class="inputButton button mainButton" type="submit" name="edit" value="{$lblSave|ucfirst}" />
		</div>
	</div>
{/form:edit_category}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}