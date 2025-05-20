<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use Bitrix\Main\Text\HtmlFilter;

/**
 * @var StringUfComponent $component
 * @var array $arResult
 */

$component = $this->getComponent();
?>

<div class="crm-entity-widget-content-block-field-container crm-entity-widget-content-block-field-container-double">
    <div class="crm-entity-widget-content-input-wrapper">
        <input
                class="crm-entity-widget-content-input"
                type="text"
                name="<?= $arResult['fieldName'] ?>_x"
                value="<?= $arResult['userField']['FORMATTED_VALUE']['x'] ?>"
        >
    </div>
    <div class="crm-entity-widget-content-input-wrapper">
        <input
                class="crm-entity-widget-content-input"
                type="text"
                name="<?= $arResult['fieldName'] ?>_y"
                value="<?= $arResult['userField']['FORMATTED_VALUE']['y'] ?>"
        >
    </div>
    <input type="hidden" name="<?= $arResult['fieldName'] ?>" value="<?= $arResult['userField']['VALUE'] ?>" />
</div>










<div class='field-wrap'>
	<?php
	foreach($arResult['fieldValues'] as $value)
	{
		?>
		<span class='field-item'>
			<?php if($value['tag'] === 'input'): ?>
				<input
					<?= $component->getHtmlBuilder()->buildTagAttributes($value['attrList']) ?>
				>
			<?php else: ?>
				<textarea
					<?= $component->getHtmlBuilder()->buildTagAttributes($value['attrList']) ?>
				><?= HtmlFilter::encode($value['attrList']['value']) ?></textarea>
			<?php endif; ?>
		</span>
		<?php
	}

	if(
		($arResult['userField']['MULTIPLE'] ?? 'N') === 'Y'
		&& ($arResult['additionalParameters']['SHOW_BUTTON'] ?? 'Y') !== 'N'
	)
	{
		print $component->getHtmlBuilder()->getCloneButton($arResult['fieldName']);
	}
	?>
</div>