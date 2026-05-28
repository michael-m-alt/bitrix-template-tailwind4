<?php
/**
 * ORM таблица для хранения настроек модуля kbnet.starter
 * 
 * @package kbnet.starter
 * @author Студия K.B.Net <www.kbnet.ru>
 */

namespace Kbnet\Starter\ORM;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\Type\DateTime;

/**
 * Class SettingsTable
 * 
 * @package Kbnet\Starter\ORM
 */
class SettingsTable extends DataManager
{
    /**
     * Возвращает имя таблицы БД или сущности
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return 'b_kbnet_starter_settings';
    }

    /**
     * Возвращает карту полей сущности
     *
     * @return array
     */
    public static function getMap(): array
    {
        return [
            (new IntegerField('ID'))
                ->setPrimary(true)
                ->setAutocomplete(true)
                ->configureTitle('ID'),

            (new StringField('CODE', 255))
                ->setRequired(true)
                ->configureTitle('Код настройки')
                ->addValidator(function ($value) {
                    if (!preg_match('/^[a-zA-Z0-9_]+$/', $value)) {
                        return ['only_latin_chars' => 'Код должен содержать только латинские буквы, цифры и подчеркивание'];
                    }
                    return true;
                }),

            (new TextField('VALUE'))
                ->configureTitle('Значение')
                ->setDefault(null),

            (new StringField('SITE_ID', 2))
                ->setRequired(false)
                ->configureTitle('ID сайта')
                ->setDefault(null),

            (new TextField('DESCRIPTION'))
                ->configureTitle('Описание')
                ->setDefault(null),

            (new DatetimeField('TIMESTAMP_X'))
                ->configureTitle('Дата изменения')
                ->setDefault(new \Bitrix\Main\DB\SqlExpression('NOW()')),
        ];
    }

    /**
     * Получить настройку по коду
     *
     * @param string $code Код настройки
     * @param string|null $siteId ID сайта (опционально)
     * @return mixed|null Значение настройки или null
     */
    public static function getByCode(string $code, ?string $siteId = null): mixed
    {
        $filter = ['=CODE' => $code];
        
        if ($siteId !== null) {
            $filter['=SITE_ID'] = $siteId;
        } else {
            // Если сайт не указан, ищем сначала для текущего сайта, потом без привязки
            if (defined('SITE_ID')) {
                $filter['LOGIC'] = 'OR';
                $filter[] = ['=SITE_ID' => SITE_ID];
                $filter[] = ['=SITE_ID' => null];
            }
        }

        $result = static::getList([
            'filter' => $filter,
            'select' => ['VALUE'],
            'limit'  => 1,
            'order'  => ['SITE_ID' => 'DESC'] // Сначала ищем для конкретного сайта
        ])->fetch();

        return $result['VALUE'] ?? null;
    }

    /**
     * Установить значение настройки
     *
     * @param string $code Код настройки
     * @param mixed $value Значение
     * @param string|null $description Описание
     * @param string|null $siteId ID сайта (опционально)
     * @return bool
     */
    public static function setByCode(
        string $code,
        mixed $value,
        ?string $description = null,
        ?string $siteId = null
    ): bool {
        $existing = static::getList([
            'filter' => [
                '=CODE' => $code,
                '=SITE_ID' => $siteId
            ]
        ])->fetch();

        if ($existing) {
            $result = static::update($existing['ID'], [
                'VALUE'       => $value,
                'DESCRIPTION' => $description,
                'TIMESTAMP_X' => new DateTime()
            ]);
            return $result->isSuccess();
        }

        $result = static::add([
            'CODE'        => $code,
            'VALUE'       => $value,
            'DESCRIPTION' => $description,
            'SITE_ID'     => $siteId,
            'TIMESTAMP_X' => new DateTime()
        ]);

        return $result->isSuccess();
    }

    /**
     * Удалить настройку по коду
     *
     * @param string $code Код настройки
     * @param string|null $siteId ID сайта (опционально)
     * @return bool
     */
    public static function deleteByCode(string $code, ?string $siteId = null): bool
    {
        $filter = ['=CODE' => $code];
        if ($siteId !== null) {
            $filter['=SITE_ID'] = $siteId;
        }

        $result = static::delete($filter);
        return $result->isSuccess();
    }
}
