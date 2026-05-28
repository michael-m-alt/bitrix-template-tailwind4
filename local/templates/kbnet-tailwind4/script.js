/**
 * Базовый JavaScript шаблона kbnet-tailwind4
 * 
 * @package kbnet-tailwind4
 * @author Студия K.B.Net <www.kbnet.ru>
 */

(function() {
    'use strict';

    /**
     * Утилита для показа toast уведомлений
     * @param {string} message - Сообщение
     * @param {string} type - Тип (success, error, warning, info)
     * @param {number} duration - Длительность в мс
     */
    function showToast(message, type = 'info', duration = 5000) {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = `toast toast--${type}`;
        
        const icons = {
            success: '<svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            error: '<svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            warning: '<svg class="w-5 h-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
            info: '<svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
        };

        toast.innerHTML = `
            ${icons[type] || icons.info}
            <span class="toast__message">${escapeHtml(message)}</span>
            <button type="button" class="toast__close" aria-label="Закрыть">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;

        container.appendChild(toast);

        // Закрытие по клику
        const closeBtn = toast.querySelector('.toast__close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => removeToast(toast));
        }

        // Автозакрытие
        if (duration > 0) {
            setTimeout(() => removeToast(toast), duration);
        }
    }

    /**
     * Удаление toast уведомления
     * @param {HTMLElement} toast - Элемент toast
     */
    function removeToast(toast) {
        toast.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }

    /**
     * Экранирование HTML
     * @param {string} str - Строка
     * @returns {string} Экранированная строка
     */
    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    /**
     * Ленивая загрузка изображений через IntersectionObserver
     */
    function initLazyLoad() {
        if (!('IntersectionObserver' in window)) {
            // Fallback для старых браузеров
            const images = document.querySelectorAll('img[data-src]');
            images.forEach(img => {
                img.src = img.dataset.src;
            });
            return;
        }

        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        img.classList.add('loaded');
                    }
                    observer.unobserve(img);
                }
            });
        }, {
            rootMargin: '50px 0px',
            threshold: 0.01
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }

    /**
     * AJAX утилита для отправки форм
     * @param {HTMLFormElement} form - Форма
     * @param {Function} onSuccess - Callback при успехе
     * @param {Function} onError - Callback при ошибке
     */
    function submitFormAjax(form, onSuccess, onError) {
        if (!form) return;

        const formData = new FormData(form);
        formData.append('sessid', BX.bitrix_sessid());

        fetch(form.action || window.location.href, {
            method: form.method || 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (typeof onSuccess === 'function') {
                    onSuccess(data);
                }
                showToast(data.message || 'Операция выполнена успешно', 'success');
            } else {
                if (typeof onError === 'function') {
                    onError(data);
                }
                showToast(data.message || 'Произошла ошибка', 'error');
            }
        })
        .catch(error => {
            console.error('AJAX Error:', error);
            if (typeof onError === 'function') {
                onError({ message: 'Ошибка сети' });
            }
            showToast('Ошибка сети', 'error');
        });
    }

    /**
     * Инициализация после загрузки DOM
     */
    document.addEventListener('DOMContentLoaded', function() {
        // Ленивая загрузка изображений
        initLazyLoad();

        // Мобильное меню
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                const isExpanded = mobileMenuButton.getAttribute('aria-expanded') === 'true';
                mobileMenuButton.setAttribute('aria-expanded', !isExpanded);
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Кнопка "Наверх"
        const backToTop = document.getElementById('back-to-top');
        if (backToTop) {
            window.addEventListener('scroll', function() {
                if (window.scrollY > 300) {
                    backToTop.classList.remove('opacity-0', 'invisible');
                    backToTop.classList.add('opacity-100', 'visible');
                } else {
                    backToTop.classList.add('opacity-0', 'invisible');
                    backToTop.classList.remove('opacity-100', 'visible');
                }
            }, { passive: true });
            
            backToTop.addEventListener('click', function() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
            
            backToTop.classList.add('opacity-0', 'invisible');
        }

        // Автоматическое скрытие мобильного меню при клике вне его
        document.addEventListener('click', function(event) {
            if (mobileMenu && !mobileMenu.contains(event.target) && 
                !mobileMenuButton.contains(event.target)) {
                mobileMenu.classList.add('hidden');
                mobileMenuButton.setAttribute('aria-expanded', 'false');
            }
        });
    });

    // Глобальный объект для доступа извне
    window.KbnetStarter = {
        showToast: showToast,
        submitFormAjax: submitFormAjax,
        escapeHtml: escapeHtml
    };

})();
