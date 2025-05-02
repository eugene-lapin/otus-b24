<?php
/** @var CMain $APPLICATION */

use Bitrix\Main\Localization\Loc;

if (!check_bitrix_sessid()) {
    return;
}

if (isset($GLOBALS["ERRORS"])) {
    CAdminMessage::ShowMessage([
        'TYPE' => 'ERROR',
        'MESSAGE' => Loc::getMessage('ERRORS_TITLE'),
        'DETAILS' => $GLOBALS["ERRORS"],
        'HTML' => true
    ]);
}
?>

<form action="<?= $APPLICATION->GetCurPage() ?>" method="post">
    <?= bitrix_sessid_post() ?>
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID ?>">
    <input type="hidden" name="id" value="sysp.crmcustomtab">
    <input type="hidden" name="install" value="Y">
    <input type="hidden" name="step" value="2">

    <p><label for="entity_type"><?= Loc::getMessage('LABEL_ENTITY_TYPE')?>:</label></p>
    <p>
        <select id="entity_type" name="ENTITY_TYPE">
            <option value="LEAD" <?= ($GLOBALS["VALID_VALUES"]["ENTITY_TYPE"] ?? '') === 'LEAD' ? 'selected' : '' ?>><?= Loc::getMessage(
                    'LABEL_ENTITY_TYPE_LEAD'
                ) ?></option>
            <option value="DEAL" <?= ($GLOBALS["VALID_VALUES"]["ENTITY_TYPE"] ?? '') === 'DEAL' ? 'selected' : '' ?>><?= Loc::getMessage(
                    'LABEL_ENTITY_TYPE_DEAL'
                ) ?></option>
            <option value="CONTACT" <?= ($GLOBALS["VALID_VALUES"]["ENTITY_TYPE"] ?? '') === 'CONTACT' ? 'selected' : '' ?>><?= Loc::getMessage(
                    'LABEL_ENTITY_TYPE_CONTACT'
                ) ?></option>
            <option value="COMPANY" <?= ($GLOBALS["VALID_VALUES"]["ENTITY_TYPE"] ?? '') === 'COMPANY' ? 'selected' : '' ?>><?= Loc::getMessage(
                    'LABEL_ENTITY_TYPE_COMPANY'
                ) ?></option>
        </select>
    </p>

    <p><label for="doctors_iblock_id"><?= Loc::getMessage('LABEL_DOCTORS_IBLOCK_ID')?>:</label></p>
    <p>
        <input type="text" name="DOCTORS_IBLOCK_ID" id="doctors_iblock_id" size="2" value="<?= $GLOBALS["VALID_VALUES"]["DOCTORS_IBLOCK_ID"]?>" required />
    </p>

    <p><label for="procedures_iblock_id"><?= Loc::getMessage('LABEL_PROCEDURES_IBLOCK_ID')?>:</label></p>
    <p>
        <input type="text" name="PROCEDURES_IBLOCK_ID" id="procedures_iblock_id" size="2" value="<?= $GLOBALS["VALID_VALUES"]["PROCEDURES_IBLOCK_ID"]?>" required />
    </p>

    <p>
        <input type="checkbox" name="DEMO_DATA" id="demo_data" value="Y" <?= ($GLOBALS["VALID_VALUES"]["DEMO_DATA"] ?? '') === 'Y' ? 'checked' : '' ?>>
        <label for="demo_data"><?= Loc::getMessage("DEMO_DATA") ?></label>
    </p>

    <input type="submit" name="" value="<?= Loc::getMessage('SUBMIT_TEXT')?>">
</form>