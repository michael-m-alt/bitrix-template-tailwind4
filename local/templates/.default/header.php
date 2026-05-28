<!DOCTYPE html>
<html lang="<?= LANGUAGE_ID ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <?php
    // SEO мета-теги из настроек страницы
    if (isset($arResult['TITLE']) && !empty($arResult['TITLE'])) {
        $APPLICATION->SetTitle($arResult['TITLE']);
    }
    ?>
    
    <title><?$APPLICATION->ShowTitle()?></title>
    
    <?php
    // Автоматическое подключение CSS/JS через D7 Asset
    $this->addExternalCss('/local/templates/.default/tailwind.css');
    $this->addExternalCss('/local/templates/.default/styles.css');
    $this->addExternalJs('/local/templates/.default/script.js');
    
    // Вывод метатегов из админки
    $APPLICATION->ShowMeta('description');
    $APPLICATION->ShowMeta('keywords');
    
    // Favicon
    $APPLICATION->ShowHead();
    ?>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    <div id="panel"><?$APPLICATION->ShowPanel();?></div>
    
    <!-- Шапка сайта -->
    <header class="starter-header bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Логотип -->
                <a href="/" class="starter-header__logo flex-shrink-0">
                    <span class="text-xl font-bold text-blue-600"><?= htmlspecialcharsbx(COption::GetOptionString('main', 'site_name', 'My Site')) ?></span>
                </a>
                
                <!-- Навигация -->
                <nav class="starter-header__nav hidden md:block">
                    <?$APPLICATION->IncludeComponent("bitrix:menu", "horizontal_multilevel", [
                        "ROOT_MENU_TYPE" => "top",
                        "MENU_CACHE_TYPE" => "Y",
                        "MENU_CACHE_TIME" => "3600",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "MENU_CACHE_GET_VARS" => [],
                        "MAX_LEVEL" => "2",
                        "USE_EXT" => "N",
                        "CHILD_MENU_TYPE" => "left",
                        "DELAY" => "N",
                        "ALLOW_MULTI_SELECT" => "N"
                    ], false, ["HIDE_ICONS" => "Y"]);?>
                </nav>
                
                <!-- Переключатель языка -->
                <div class="starter-header__lang-switcher flex items-center space-x-2">
                    <?$APPLICATION->IncludeComponent("bitrix:system.lang.languages", "", [
                        "COMPONENT_TEMPLATE" => ".default",
                        "COMPONENT_WRAP" => "N"
                    ], false, ["HIDE_ICONS" => "Y"]);?>
                </div>
                
                <!-- Мобильное меню кнопка -->
                <button class="starter-header__mobile-toggle md:hidden p-2" aria-label="Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </header>
    
    <!-- Основной контент -->
    <main class="starter-main flex-grow">
