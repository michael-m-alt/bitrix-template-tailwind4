/**
 * Базовый JavaScript шаблон сайта
 * Vanilla JS, модульная структура, Mobile-First
 */

(function() {
    'use strict';

    /**
     * Основной объект приложения Starter
     */
    const Starter = {
        /**
         * Инициализация при загрузке DOM
         */
        init: function() {
            this.initMobileMenu();
            this.initLazyLoading();
            this.initAjaxForms();
            this.initSmoothScroll();
            
            console.log('Starter initialized');
        },

        /**
         * Мобильное меню
         */
        initMobileMenu: function() {
            const toggle = document.querySelector('.starter-header__mobile-toggle');
            const nav = document.querySelector('.starter-header__nav');
            
            if (!toggle || !nav) return;
            
            toggle.addEventListener('click', function() {
                const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
                toggle.setAttribute('aria-expanded', !isExpanded);
                nav.classList.toggle('hidden');
                nav.classList.toggle('md:block');
            });
            
            // Закрытие меню при клике вне его
            document.addEventListener('click', function(event) {
                if (!nav.contains(event.target) && !toggle.contains(event.target)) {
                    nav.classList.add('hidden');
                    nav.classList.remove('md:block');
                    toggle.setAttribute('aria-expanded', 'false');
                }
            });
        },

        /**
         * Ленивая загрузка изображений (IntersectionObserver)
         */
        initLazyLoading: function() {
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            if (img.dataset.src) {
                                img.src = img.dataset.src;
                                img.removeAttribute('data-src');
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
            } else {
                // Fallback для старых браузеров
                document.querySelectorAll('img[data-src]').forEach(img => {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                });
            }
        },

        /**
         * Обработка AJAX форм
         */
        initAjaxForms: function() {
            const forms = document.querySelectorAll('[data-ajax-form]');
            
            forms.forEach(form => {
                form.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    
                    const submitBtn = form.querySelector('[type="submit"]');
                    const originalText = submitBtn ? submitBtn.textContent : '';
                    
                    // Блокировка кнопки
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.textContent = form.dataset.loadingText || 'Загрузка...';
                    }
                    
                    try {
                        const formData = new FormData(form);
                        formData.append('sessid', BX.bitrix_sessid());
                        
                        const response = await fetch(form.action || window.location.pathname, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        });
                        
                        const result = await response.json();
                        
                        if (result.success) {
                            this.showMessage('success', result.message);
                            form.reset();
                        } else {
                            this.showMessage('error', result.message);
                        }
                    } catch (error) {
                        console.error('AJAX error:', error);
                        this.showMessage('error', 'Произошла ошибка при отправке формы');
                    } finally {
                        // Разблокировка кнопки
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.textContent = originalText;
                        }
                    }
                });
            });
        },

        /**
         * Показ сообщений (toast уведомления)
         * @param {string} type - 'success' | 'error' | 'warning'
         * @param {string} message - Текст сообщения
         */
        showMessage: function(type, message) {
            const toast = document.createElement('div');
            toast.className = `starter-toast starter-toast--${type}`;
            toast.textContent = message;
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 1rem 1.5rem;
                background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#f59e0b'};
                color: white;
                border-radius: 0.375rem;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                z-index: 9999;
                animation: slideIn 0.3s ease;
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        },

        /**
         * Плавный скролл к якорям
         */
        initSmoothScroll: function() {
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;
                    
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        e.preventDefault();
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        },

        /**
         * Утилита: debounce
         * @param {Function} func 
         * @param {number} wait 
         */
        debounce: function(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },

        /**
         * Утилита: throttle
         * @param {Function} func 
         * @param {number} limit 
         */
        throttle: function(func, limit) {
            let inThrottle;
            return function(...args) {
                if (!inThrottle) {
                    func.apply(this, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit);
                }
            };
        }
    };

    /**
     * Инициализация после загрузки DOM
     */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => Starter.init());
    } else {
        Starter.init();
    }

    /**
     * Экспорт в глобальную область видимости
     */
    window.Starter = Starter;

})();

/**
 * CSS анимации для toast уведомлений
 */
if (typeof document !== 'undefined') {
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
}
