<?php
/**
 * Базовый AJAX контроллер для модуля kbnet.starter
 * 
 * @package kbnet.starter
 * @author Студия K.B.Net <www.kbnet.ru>
 */

namespace Kbnet\Starter\Controllers;

use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Response;
use Bitrix\Main\Request;
use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use Bitrix\Main\Web\Json;
use Bitrix\Main\Diag\Debug;

/**
 * Class Ajax
 * 
 * Базовый контроллер для обработки AJAX запросов
 * 
 * @package Kbnet\Starter\Controllers
 */
class Ajax extends Controller
{
    /**
     * Конфигурация действий контроллера
     *
     * @return array
     */
    public function configureActions(): array
    {
        return [
            'test' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod([
                        ActionFilter\HttpMethod::POST
                    ]),
                    new ActionFilter\Csrf()
                ],
                'postfilters' => []
            ],
            'saveSetting' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod([
                        ActionFilter\HttpMethod::POST
                    ]),
                    new ActionFilter\Csrf()
                ],
                'postfilters' => []
            ]
        ];
    }

    /**
     * Тестовый метод AJAX
     * Возвращает успешный ответ с данными
     *
     * @return array
     */
    public function testAction(): array
    {
        return [
            'success' => true,
            'message' => 'AJAX контроллер работает корректно',
            'data'    => [
                'timestamp' => time(),
                'site_id'   => SITE_ID ?? 'unknown'
            ]
        ];
    }

    /**
     * Сохранение настройки через AJAX
     *
     * @param string $code Код настройки
     * @param mixed $value Значение настройки
     * @param string|null $description Описание (опционально)
     * @return array
     */
    public function saveSettingAction(string $code, mixed $value, ?string $description = null): array
    {
        try {
            // Валидация кода настройки
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $code)) {
                throw new \Exception('Некорректный код настройки. Допустимы только латинские буквы, цифры и подчеркивание.');
            }

            // Проверка прав доступа (можно расширить)
            global $USER;
            if (!$USER->IsAdmin()) {
                throw new \Exception('Доступ запрещен. Требуются права администратора.');
            }

            // Сохранение настройки
            $config = \Kbnet\Starter\Config::getInstance();
            $result = $config->set($code, $value, $description);

            if (!$result) {
                throw new \Exception('Ошибка при сохранении настройки');
            }

            return [
                'success' => true,
                'message' => 'Настройка успешно сохранена',
                'data'    => [
                    'code'  => $code,
                    'value' => $value
                ]
            ];
        } catch (\Exception $e) {
            Debug::writeToFile(
                "Ошибка AJAX saveSetting: " . $e->getMessage(),
                '',
                'kbnet_starter_ajax.log'
            );

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Получение настройки через AJAX
     *
     * @param string $code Код настройки
     * @return array
     */
    public function getSettingAction(string $code): array
    {
        try {
            $config = \Kbnet\Starter\Config::getInstance();
            $value = $config->get($code);

            return [
                'success' => true,
                'data'    => [
                    'code'  => $code,
                    'value' => $value
                ]
            ];
        } catch (\Exception $e) {
            Debug::writeToFile(
                "Ошибка AJAX getSetting: " . $e->getMessage(),
                '',
                'kbnet_starter_ajax.log'
            );

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Обработка ошибок
     *
     * @param \Exception $exception
     * @return Response
     */
    protected function errorAction(\Exception $exception): Response
    {
        Debug::writeToFile(
            "Общая ошибка AJAX: " . $exception->getMessage(),
            '',
            'kbnet_starter_ajax.log'
        );

        $this->errorCollection[] = $exception->getMessage();
        
        return parent::errorAction($exception);
    }
}
