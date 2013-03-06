{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

<div class="pageTitle">
	<h2>{$lblGalleria|ucfirst}: {$lblAddCategory}</h2>
</div>
{form:add_category}
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
			{option:showGalleriaAddCategory}
			<div class="buttonHolderRight">
				<input id="addButton" class="inputButton button mainButton" type="submit" name="add" value="{$lblSave|ucfirst}" />
			</div>
			{/option:showGalleriaAddCategory}
		</div>
{/form:add_category}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}