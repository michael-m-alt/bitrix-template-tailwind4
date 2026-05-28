<?php
/**
 * Шаблон сайта kbnet-tailwind4
 * Footer template
 * 
 * @package kbnet-tailwind4
 * @author Студия K.B.Net <www.kbnet.ru>
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Page\Asset;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$currentYear = date('Y');
?>

</main>

<!-- Footer -->
<footer class="site-footer bg-gray-900 text-gray-300">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Company Info -->
            <div class="col-span-1 md:col-span-2">
                <h3 class="text-white text-lg font-semibold mb-4"><?= GetMessage('KBNET_SITE_NAME') ?></h3>
                <p class="text-gray-400 mb-4 max-w-md">
                    <?= GetMessage('KBNET_COPYRIGHT', ['#YEAR#' => $currentYear]) ?>
                </p>
                <div class="flex space-x-4">
                    <!-- Social links placeholder -->
                    <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="Telegram">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.16.16-.295.295-.605.295l.213-3.054 5.56-5.022c.242-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.64-.203-.658-.64.135-.954l11.566-4.458c.538-.196 1.006.128.832.941z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="VKontakte">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M15.073 2H8.937C5.001 2 2 4.926 2 8.852v6.296C2 19.074 4.926 22 8.852 22h6.296C19.074 22 22 19.074 22 15.148V8.852C22 4.926 19.074 2 15.148 2h-.075zM17.945 15.292c.356.356.712.712 1.146.712h1.146v2.356c-.356.071-.712.142-1.146.142-1.787 0-3.076-.926-4.146-2.071-.5-.57-1.071-1.071-1.787-1.071-.213 0-.427.071-.64.142-.213.142-.284.427-.284.712v1.926c-.071.071-.142.071-.213.071-.57 0-1.21.071-1.787-.142-1.716-.57-2.926-1.852-3.926-3.284-.071-.142-.213-.284-.213-.427 0-.213.213-.284.427-.284h2.146c.356 0 .57.142.783.427.57.926 1.284 1.787 2.21 2.356.142.071.284.142.427.142.142 0 .284-.142.284-.356v-2.21c-.071-.783-.5-1.426-1.146-1.787-.213-.142-.427-.213-.64-.284-.213 0-.356-.142-.356-.356 0-.284.213-.427.498-.498.427-.071.854-.142 1.356-.142.57 0 1.146.071 1.716.213.926.213 1.64.783 2.21 1.57.213.284.427.57.57.926.071.213.142.356.356.427.213.071.427 0 .57-.142.213-.213.427-.427.57-.712.213-.427.427-.854.57-1.356.071-.213.142-.427.356-.498.213-.071.427 0 .57.142.213.213.427.427.57.712.427.854.712 1.78.712 2.712v.071c-.071.427-.213.854-.427 1.21z"/>
                        </svg>
                    </a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h4 class="text-white font-semibold mb-4"><?= GetMessage('KBNET_CONTACTS') ?></h4>
                <ul class="space-y-2">
                    <li>
                        <a href="/contacts/" class="hover:text-white transition-colors">
                            <?= GetMessage('KBNET_CONTACTS') ?>
                        </a>
                    </li>
                    <li>
                        <a href="tel:+70000000000" class="hover:text-white transition-colors">
                            +7 (000) 000-00-00
                        </a>
                    </li>
                    <li>
                        <a href="mailto:info@kbnet.ru" class="hover:text-white transition-colors">
                            info@kbnet.ru
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Legal -->
            <div>
                <h4 class="text-white font-semibold mb-4">Legal</h4>
                <ul class="space-y-2">
                    <li>
                        <a href="/privacy/" class="hover:text-white transition-colors">
                            <?= GetMessage('KBNET_PRIVACY_POLICY') ?>
                        </a>
                    </li>
                    <li>
                        <a href="/terms/" class="hover:text-white transition-colors">
                            <?= GetMessage('KBNET_TERMS_OF_USE') ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Bottom bar -->
        <div class="border-t border-gray-800 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
            <p class="text-sm text-gray-500">
                <?= str_replace('%d', $currentYear, GetMessage('KBNET_COPYRIGHT')) ?>
            </p>
            
            <!-- Back to top button -->
            <button type="button" 
                    id="back-to-top"
                    class="mt-4 md:mt-0 p-2 rounded-full bg-gray-800 hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500"
                    aria-label="<?= GetMessage('KBNET_BACK_TO_TOP') ?>">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                </svg>
            </button>
        </div>
    </div>
</footer>

<!-- Toast notification container -->
<div id="toast-container" class="fixed bottom-4 right-4 z-50 space-y-2"></div>

<script>
// Инициализация после загрузки DOM
document.addEventListener('DOMContentLoaded', function() {
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
        });
        
        backToTop.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        
        // Скрыть кнопку изначально
        backToTop.classList.add('opacity-0', 'invisible');
    }
});
</script>

<?php
// Вывод ассетов перед закрывающим тегом body для оптимизации
Asset::getInstance()->addString('');
?>
</body>
</html>
