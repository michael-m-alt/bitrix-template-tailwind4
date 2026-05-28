/**
 * Скрипт компонента starter:base.info
 * Демонстрация AJAX взаимодействия
 */

(function() {
    'use strict';

    /**
     * Класс для управления компонентом BaseInfo
     */
    class BaseInfoComponent {
        constructor(container) {
            this.container = container;
            this.button = container.querySelector('.kbnet-base-info__ajax-button');
            this.resultContainer = container.querySelector('.kbnet-base-info__ajax-result');
            
            if (this.button) {
                this.init();
            }
        }

        /**
         * Инициализация обработчиков событий
         */
        init() {
            this.button.addEventListener('click', (e) => this.handleAjaxClick(e));
        }

        /**
         * Обработчик клика по кнопке AJAX теста
         * @param {Event} e 
         */
        handleAjaxClick(e) {
            e.preventDefault();
            
            const button = e.target;
            const originalText = button.textContent;
            button.textContent = 'Загрузка...';
            button.disabled = true;

            // AJAX запрос к контроллеру модуля
            fetch('/local/ajax/index.php?action=test_ajax', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Bitrix-Sessid': document.querySelector('[name="bitrix_sessid"]')?.value || ''
                },
                body: 'bitrix_sessid=' + encodeURIComponent(
                    document.querySelector('[name="bitrix_sessid"]')?.value || ''
                )
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.showResult('✓ ' + data.message, 'success');
                } else {
                    this.showResult('✗ Ошибка: ' + (data.message || 'Неизвестная ошибка'), 'error');
                }
            })
            .catch(error => {
                this.showResult('✗ Ошибка соединения: ' + error.message, 'error');
            })
            .finally(() => {
                button.textContent = originalText;
                button.disabled = false;
            });
        }

        /**
         * Отображение результата AJAX запроса
         * @param {string} message 
         * @param {string} type 
         */
        showResult(message, type) {
            if (!this.resultContainer) return;
            
            this.resultContainer.textContent = message;
            this.resultContainer.className = 'kbnet-base-info__result kbnet-base-info__result--' + type;
        }
    }

    /**
     * Инициализация компонентов после загрузки DOM
     */
    document.addEventListener('DOMContentLoaded', function() {
        const containers = document.querySelectorAll('.kbnet-base-info[data-ajax="Y"]');
        containers.forEach(container => {
            new BaseInfoComponent(container);
        });
    });
})();
