<?php
/**
 * Шаг 2 удаления модуля kbnet.starter (завершение)
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
    
    <table class="adm-detail-content-table edit-table">
        <tr>
            <td colspan="2">
                <h3><?= Loc::getMessage('KBNET_STARTER_UNINSTALL_COMPLETE') ?></h3>
                <p>Модуль "Студия K.B.Net - Стартовый" успешно удален из системы.</p>
                <?php if (isset($_REQUEST['delete_tables']) && $_REQUEST['delete_tables'] === 'Y'): ?>
                    <p class="errortext">Таблицы базы данных были удалены.</p>
                <?php else: ?>
                    <p>Таблицы базы данных сохранены.</p>
                <?php endif; ?>
            </td>
        </tr>
    </table>
    
    <input type="submit" name="inst" value="<?= GetMessage('MOD_BACK') ?>">
</form>
