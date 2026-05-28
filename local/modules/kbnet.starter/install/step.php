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

    <table class="adm-detail-content-table edit-table" style="width: 100%;">
        <tr>
            <td colspan="2" style="padding: 20px;">
                <h3 style="margin-bottom: 15px;"><?= Loc::getMessage('KBNET_STARTER_STEP_2') ?></h3>
                <p><?= Loc::getMessage('KBNET_STARTER_INSTALL_SUCCESS') ?></p>
                <div style="background: #f5f5f5; padding: 15px; border-radius: 4px; margin-top: 15px;">
                    <strong><?= Loc::getMessage('KBNET_STARTER_INSTALL_INFO_TITLE') ?>:</strong>
                    <ul style="margin-top: 10px; padding-left: 20px;">
                        <li><?= Loc::getMessage('KBNET_STARTER_INSTALL_INFO_MODULE') ?></li>
                        <li><?= Loc::getMessage('KBNET_STARTER_INSTALL_INFO_TEMPLATE') ?></li>
                        <li><?= Loc::getMessage('KBNET_STARTER_INSTALL_INFO_COMPONENT') ?></li>
                        <li><?= Loc::getMessage('KBNET_STARTER_INSTALL_INFO_AJAX') ?></li>
                    </ul>
                </div>
            </td>
        </tr>
    </table>

    <input type="submit" name="inst" value="<?= GetMessage('MOD_INSTALL') ?>" class="adm-btn-save">
</form>
