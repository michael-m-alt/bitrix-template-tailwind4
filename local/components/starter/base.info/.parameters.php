<?php
/**
 * Параметры компонента
 */

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

return [
    'TEXT' => [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('STARTER_BASE_INFO_TEXT'),
        'TYPE' => 'STRING',
        'DEFAULT' => '',
        'MULTILINE' => 'Y',
        'COLS' => 50,
    ],
    'CACHE_TYPE' => [
        'PARENT' => 'CACHING',
        'NAME' => Loc::getMessage('STARTER_BASE_INFO_CACHE_TYPE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'A' => Loc::getMessage('STARTER_BASE_INFO_CACHE_TYPE_AUTO'),
            'Y' => Loc::getMessage('STARTER_BASE_INFO_CACHE_TYPE_YES'),
            'N' => Loc::getMessage('STARTER_BASE_INFO_CACHE_TYPE_NO'),
        ],
        'DEFAULT' => 'A',
    ],
    'CACHE_TIME' => [
        'PARENT' => 'CACHING',
        'NAME' => Loc::getMessage('STARTER_BASE_INFO_CACHE_TIME'),
        'TYPE' => 'STRING',
        'DEFAULT' => '3600',
    ],
];
