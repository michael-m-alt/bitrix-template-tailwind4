<?php
/**
 * Шаг установки модуля kbnet.starter
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
    <input type="hidden" name="install" value="Y">
    <input type="hidden" name="step" value="2">
    
    <table class="adm-detail-content-table edit-table">
        <tr>
            <td colspan="2">
                <h3><?= Loc::getMessage('KBNET_STARTER_STEP_2') ?></h3>
                <p><?= Loc::getMessage('KBNET_STARTER_INSTALL_SUCCESS') ?></p>
                <div><?= Loc::getMessage('KBNET_STARTER_INSTALL_INFO') ?></div>
            </td>
        </tr>
    </table>
    
    <input type="submit" name="inst" value="<?= GetMessage('MOD_INSTALL') ?>">
</form>
