    </main>
    
    <!-- Подвал сайта -->
    <footer class="starter-footer bg-gray-800 text-white mt-auto">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Информация о компании -->
                <div class="starter-footer__section">
                    <h3 class="text-lg font-semibold mb-4"><?= htmlspecialcharsbx(COption::GetOptionString('main', 'site_name', 'My Site')) ?></h3>
                    <p class="text-gray-400 text-sm">
                        © <?= date('Y') ?> <?= htmlspecialcharsbx(GetMessage('STARTER_ALL_RIGHTS_RESERVED')) ?>
                    </p>
                </div>
                
                <!-- Навигация -->
                <div class="starter-footer__nav">
                    <h4 class="text-sm font-semibold uppercase tracking-wider mb-4"><?= GetMessage('STARTER_NAVIGATION') ?></h4>
                    <?$APPLICATION->IncludeComponent("bitrix:menu", "footer", [
                        "ROOT_MENU_TYPE" => "bottom",
                        "MENU_CACHE_TYPE" => "Y",
                        "MENU_CACHE_TIME" => "3600",
                        "MENU_CACHE_USE_GROUPS" => "N",
                        "MENU_CACHE_GET_VARS" => [],
                        "MAX_LEVEL" => "1",
                        "USE_EXT" => "N",
                        "CHILD_MENU_TYPE" => "",
                        "DELAY" => "N",
                        "ALLOW_MULTI_SELECT" => "N"
                    ], false, ["HIDE_ICONS" => "Y"]);?>
                </div>
                
                <!-- Контакты -->
                <div class="starter-footer__contacts">
                    <h4 class="text-sm font-semibold uppercase tracking-wider mb-4"><?= GetMessage('STARTER_CONTACTS') ?></h4>
                    <div class="text-gray-400 text-sm space-y-2">
                        <p><?= htmlspecialcharsbx(COption::GetOptionString('main', 'site_email', 'info@example.com')) ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Техническая информация -->
            <div class="starter-footer__bottom border-t border-gray-700 mt-8 pt-8 text-center text-gray-400 text-xs">
                <p><?= GetMessage('STARTER_POWERED_BY_BITRIX') ?></p>
            </div>
        </div>
    </footer>
    
    <!-- Динамическая зона для Auto-Composite -->
    <?if (defined("STOP_TIME")):?>
        <div data-dynamic-zone="true" class="hidden"></div>
    <?endif;?>
    
    <?php
    // Вывод скриптов перед закрытием body
    $this->addExternalJs('/local/templates/.default/script.js');
    ?>
    
    <?$APPLICATION->ShowBodyScript()?>
</body>
</html>
