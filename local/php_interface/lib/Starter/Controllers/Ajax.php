<?php
/**
 * Базовый AJAX контроллер для обработки запросов
 * Использует D7 Controller API
 */

namespace Local\Starter\Controllers;

use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Engine\Response\Json;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag\Debug;

class Ajax extends Controller
{
    /**
     * Конфигурация действий контроллера
     * @return array
     */
    public function configureActions(): array
    {
        return [
            'test' => [
                'prefilter' => [],
                'postfilter' => [],
            ],
            'submitForm' => [
                'prefilter' => [],
                'postfilter' => [],
            ],
        ];
    }
    
    /**
     * Тестовый метод для проверки AJAX
     * @return Json
     */
    public function testAction(): Json
    {
        return new Json([
            'success' => true,
            'message' => Loc::getMessage('STARTER_AJAX_TEST_SUCCESS'),
            'timestamp' => time(),
            'data' => [
                'serverTime' => date('Y-m-d H:i:s'),
                'siteId' => SITE_ID,
                'language' => LANGUAGE_ID,
            ],
        ]);
    }
    
    /**
     * Обработка отправки формы
     * @param string $name
     * @param string $email
     * @param string $message
     * @return Json
     */
    public function submitFormAction(string $name, string $email, string $message = ''): Json
    {
        // Валидация сессии (обязательно для форм!)
        if (!check_bitrix_sessid()) {
            return new Json([
                'success' => false,
                'message' => Loc::getMessage('STARTER_AJAX_INVALID_SESSION'),
            ], 403);
        }
        
        // Санитизация входных данных
        $name = htmlspecialcharsbx(trim($name));
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        $message = htmlspecialcharsbx(trim($message));
        
        // Валидация
        if (empty($name)) {
            return new Json([
                'success' => false,
                'message' => Loc::getMessage('STARTER_AJAX_NAME_REQUIRED'),
            ], 400);
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new Json([
                'success' => false,
                'message' => Loc::getMessage('STARTER_AJAX_INVALID_EMAIL'),
            ], 400);
        }
        
        try {
            // Здесь логика обработки формы (отправка почты, сохранение в БД и т.д.)
            Debug::writeToFile("Form submitted: name={$name}, email={$email}", '', 'starter_forms.log');
            
            return new Json([
                'success' => true,
                'message' => Loc::getMessage('STARTER_AJAX_FORM_SUBMITTED'),
                'data' => [
                    'name' => $name,
                    'email' => $email,
                ],
            ]);
        } catch (\Exception $e) {
            Debug::writeToFile("Form error: " . $e->getMessage(), '', 'starter_errors.log');
            
            return new Json([
                'success' => false,
                'message' => Loc::getMessage('STARTER_AJAX_ERROR'),
            ], 500);
        }
    }
}
