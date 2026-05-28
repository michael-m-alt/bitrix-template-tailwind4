<?php
/**
 * Параметры компонента starter:base.info
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$arComponentParameters = [
    'GROUPS' => [
        'CACHE_SETTINGS' => [
            'NAME' => GetMessage('KBNET_STARTER_CACHE_SETTINGS')
        ],
        'AJAX_SETTINGS' => [
            'NAME' => GetMessage('KBNET_STARTER_AJAX_SETTINGS')
        ]
    ],
    'PARAMETERS' => [
        'CACHE_TIME' => [
            'PARENT' => 'CACHE_SETTINGS',
            'NAME' => GetMessage('KBNET_STARTER_CACHE_TIME'),
            'TYPE' => 'STRING',
            'DEFAULT' => '3600'
        ],
        'AJAX_TEST' => [
            'PARENT' => 'AJAX_SETTINGS',
            'NAME' => GetMessage('KBNET_STARTER_AJAX_TEST'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y'
        ]
    ]
];
