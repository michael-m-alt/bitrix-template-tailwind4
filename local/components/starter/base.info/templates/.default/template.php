<?php
/**
 * Шаблон компонента: Базовая информация
 */

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

Loc::loadMessages(__FILE__);

$this->setFrameMode(true);
?>

<div class="starter-base-info bg-white rounded-lg shadow-md p-6 mb-6">
    <?php if (!empty($arResult['text'])): ?>
        <div class="starter-base-info__text mb-4">
            <?= htmlspecialcharsbx($arResult['text']) ?>
        </div>
    <?php endif; ?>
    
    <div class="starter-base-info__meta text-sm text-gray-500 space-y-2">
        <div class="starter-base-info__site">
            <strong><?= Loc::getMessage('STARTER_BASE_INFO_SITE') ?>:</strong>
            <?= htmlspecialcharsbx($arResult['siteName']) ?>
        </div>
        
        <div class="starter-base-info__time">
            <strong><?= Loc::getMessage('STARTER_BASE_INFO_TIME') ?>:</strong>
            <?= htmlspecialcharsbx($arResult['serverTime']) ?>
        </div>
        
        <div class="starter-base-info__lang">
            <strong><?= Loc::getMessage('STARTER_BASE_INFO_LANG') ?>:</strong>
            <?= htmlspecialcharsbx($arResult['language']) ?>
        </div>
    </div>
    
    <!-- Пример AJAX кнопки для теста -->
    <div class="starter-base-info__ajax-test mt-4">
        <button 
            type="button" 
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition-colors"
            data-ajax-test-btn
        >
            <?= Loc::getMessage('STARTER_BASE_INFO_AJAX_TEST') ?>
        </button>
        <span class="starter-base-info__ajax-result ml-3 text-sm"></span>
    </div>
</div>

<script>
(function() {
    'use strict';
    
    const btn = document.querySelector('[data-ajax-test-btn]');
    const resultSpan = document.querySelector('.starter-base-info__ajax-result');
    
    if (btn) {
        btn.addEventListener('click', async function() {
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Загрузка...';
            
            try {
                const response = await fetch('/local/ajax/', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        action: 'test',
                        sessid: BX.bitrix_sessid()
                    })
                });
                
                const result = await response.json();
                
                if (result.success && result.data) {
                    resultSpan.textContent = 'OK: ' + result.data.serverTime;
                    resultSpan.className = 'starter-base-info__ajax-result ml-3 text-sm text-green-600';
                } else {
                    resultSpan.textContent = 'Error: ' + (result.message || 'Unknown error');
                    resultSpan.className = 'starter-base-info__ajax-result ml-3 text-sm text-red-600';
                }
            } catch (error) {
                console.error('AJAX test error:', error);
                resultSpan.textContent = 'Error: ' + error.message;
                resultSpan.className = 'starter-base-info__ajax-result ml-3 text-sm text-red-600';
            } finally {
                btn.disabled = false;
                btn.textContent = originalText;
            }
        });
    }
})();
</script>
