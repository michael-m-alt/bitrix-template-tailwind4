<?php
/**
 * Шаг 1 удаления модуля kbnet.starter (подтверждение)
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

global $APPLICATION;
?>

<form action="<?= $APPLICATION->GetCurPage() ?>">
    <?= bitrix_sessid_post() ?>
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID ?>">
    <input type="hidden" name="id" value="kbnet.starter">
    <input type="hidden" name="uninstall" value="Y">
    <input type="hidden" name="step" value="2">
    
    <table class="adm-detail-content-table edit-table">
        <tr>
            <td colspan="2">
                <?= Loc::getMessage('KBNET_STARTER_UNINSTALL_WARNING') ?>
            </td>
        </tr>
        <tr>
            <td>
                <label for="delete_tables">
                    <input type="checkbox" name="delete_tables" id="delete_tables" value="Y" checked>
                    <?= Loc::getMessage('KBNET_STARTER_DELETE_TABLES') ?>
                </label>
                <br><small><?= Loc::getMessage('KBNET_STARTER_DELETE_TABLES_DESC') ?></small>
            </td>
        </tr>
    </table>
    
    <input type="submit" name="inst" value="<?= Loc::getMessage('KBNET_STARTER_DELETE_COMPLETE') ?>">
</form>
