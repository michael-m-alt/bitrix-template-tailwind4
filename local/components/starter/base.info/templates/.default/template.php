<?php
/**
 * Шаблон компонента starter:base.info
 * Демонстрация работы с AJAX и кешированием
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Page\Asset;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$this->setFrameMode(true);
?>

<div class="starter-base-info bg-white rounded-lg shadow-md p-6 mb-6" data-component="starter:base.info">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">
        <?= Loc::getMessage('KBNET_STARTER_COMPONENT_TITLE') ?>
    </h2>
    
    <div class="space-y-3">
        <div class="flex items-start space-x-3">
            <svg class="w-5 h-5 text-blue-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="text-sm font-medium text-gray-700"><?= Loc::getMessage('KBNET_STARTER_SITE_NAME') ?>:</p>
                <p class="text-gray-900"><?= htmlspecialcharsbx($arResult['SITE_NAME']) ?></p>
            </div>
        </div>
        
        <div class="flex items-start space-x-3">
            <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="text-sm font-medium text-gray-700"><?= Loc::getMessage('KBNET_STARTER_SERVER_TIME') ?>:</p>
                <p class="text-gray-900"><?= htmlspecialcharsbx($arResult['TIMESTAMP']) ?></p>
            </div>
        </div>
        
        <?php if ($arResult['AJAX_TEST_ENABLED']): ?>
        <div class="border-t border-gray-200 pt-4 mt-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">
                <?= Loc::getMessage('KBNET_STARTER_AJAX_TEST_TITLE') ?>
            </h3>
            
            <button type="button" 
                    id="starter-ajax-test-btn"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    data-action="test">
                <span class="btn-text"><?= Loc::getMessage('KBNET_STARTER_AJAX_TEST_BUTTON') ?></span>
                <span class="btn-loader hidden ml-2">
                    <svg class="animate-spin h-4 w-4 inline" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
            </button>
            
            <div id="starter-ajax-result" class="mt-3 hidden">
                <div class="p-3 bg-gray-50 rounded-md border border-gray-200">
                    <pre class="text-xs text-gray-700 overflow-auto"></pre>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
(function() {
    'use strict';
    
    const testBtn = document.getElementById('starter-ajax-test-btn');
    const resultContainer = document.getElementById('starter-ajax-result');
    const resultPre = resultContainer ? resultContainer.querySelector('pre') : null;
    
    if (testBtn && resultContainer && resultPre) {
        testBtn.addEventListener('click', function() {
            const btnText = testBtn.querySelector('.btn-text');
            const btnLoader = testBtn.querySelector('.btn-loader');
            const action = testBtn.dataset.action || 'test';
            
            // Блокировка кнопки
            testBtn.disabled = true;
            btnText.textContent = '<?= Loc::getMessage('KBNET_STARTER_AJAX_TEST_LOADING') ?>';
            btnLoader.classList.remove('hidden');
            resultContainer.classList.add('hidden');
            
            // AJAX запрос к контроллеру модуля
            fetch('/local/ajax/index.php?action=' + encodeURIComponent(action), {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'sessid=' + encodeURIComponent(BX.bitrix_sessid())
            })
            .then(response => response.json())
            .then(data => {
                resultPre.textContent = JSON.stringify(data, null, 2);
                resultContainer.classList.remove('hidden');
                
                if (data.success) {
                    if (window.KbnetStarter && typeof window.KbnetStarter.showToast === 'function') {
                        window.KbnetStarter.showToast(data.message, 'success');
                    }
                } else {
                    if (window.KbnetStarter && typeof window.KbnetStarter.showToast === 'function') {
                        window.KbnetStarter.showToast(data.message || 'Ошибка', 'error');
                    }
                }
            })
            .catch(error => {
                console.error('AJAX Error:', error);
                resultPre.textContent = 'Error: ' + error.message;
                resultContainer.classList.remove('hidden');
                
                if (window.KbnetStarter && typeof window.KbnetStarter.showToast === 'function') {
                    window.KbnetStarter.showToast('Ошибка сети', 'error');
                }
            })
            .finally(() => {
                // Разблокировка кнопки
                testBtn.disabled = false;
                btnText.textContent = '<?= Loc::getMessage('KBNET_STARTER_AJAX_TEST_BUTTON') ?>';
                btnLoader.classList.add('hidden');
            });
        });
    }
})();
</script>
