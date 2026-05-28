# Bitrix Starter Kit - Универсальный шаблон проекта

Минимальная заготовка для проектов на 1С-Битрикс с использованием современных стандартов разработки.

## 📋 Возможности

- ✅ **D7 Kernel** - Только современные API Битрикса (ORM, D7 классы)
- ✅ **Мультиязычность** - Поддержка ru/en из коробки
- ✅ **Tailwind CSS** - Локальная сборка без внешних зависимостей
- ✅ **BEM методология** - Правильная структура CSS классов
- ✅ **Vanilla JS** - Без jQuery, модульная архитектура
- ✅ **AJAX контроллер** - Готовый обработчик POST запросов
- ✅ **Кеширование** - Тегированный кеш для производительности
- ✅ **Auto-Composite** - Совместимость с композитным режимом
- ✅ **Безопасность** - Валидация сессий, экранирование вывода

## 📁 Структура проекта

```
/local/
├── ajax/
│   └── index.php              # AJAX роутер
├── components/
│   └── starter/
│       └── base.info/         # Демонстрационный компонент
├── php_interface/
│   ├── init.php               # Точка входа
│   ├── lib/
│   │   └── Starter/
│   │       ├── Config.php     # Конфигурация
│   │       ├── Events.php     # Обработчики событий
│   │       └── Controllers/
│   │           └── Ajax.php   # AJAX контроллер
│   └── config.php             # Настройки проекта
└── templates/
    └── .default/
        ├── header.php         # Шапка сайта
        ├── footer.php         # Подвал сайта
        ├── styles.css         # Глобальные стили
        ├── script.js          # Базовый JavaScript
        └── tailwind.css       # Tailwind утилиты
```

## 🚀 Установка

### 1. Скопируйте файлы в `/local/` вашего сайта

```bash
cp -r local/* /path/to/bitrix/site/local/
```

### 2. Подключите `init.php`

В файле `/bitrix/php_interface/dbconn.php` или `/local/php_interface/dbconn.php`:

```php
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/init.php';
```

### 3. Активируйте шаблон сайта

В административной панели:
- Настройка → Настройки продукта → Сайты → Список сайтов
- Выберите сайт → вкладка "Шаблон сайта"
- Установите `.default`

### 4. Настройте конфиг

Отредактируйте `/local/config.php` под ваши нужды:

```php
return [
    'devMode' => true,  // Включить режим разработки
    'cacheTtl' => 7200, // Время кеширования
];
```

## 💡 Использование компонента

```php
<?$APPLICATION->IncludeComponent(
    "starter:base.info", 
    "", 
    [
        "TEXT" => "Привет, мир!",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600"
    ],
    false
);?>
```

## 🔌 AJAX запросы

### Отправка формы через AJAX

```javascript
const response = await fetch('/local/ajax/', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    },
    body: JSON.stringify({
        action: 'test',
        sessid: BX.bitrix_sessid()
    })
});

const result = await response.json();
console.log(result);
// { success: true, message: "...", data: {...} }
```

### Добавление нового действия

1. Создайте метод в `/local/php_interface/lib/Starter/Controllers/Ajax.php`:

```php
public function myCustomAction(): Json
{
    // Ваша логика
    return new Json(['success' => true, 'data' => [...]]);
}
```

2. Зарегистрируйте в `configureActions()`:

```php
public function configureActions(): array
{
    return [
        'myCustom' => [
            'prefilter' => [],
            'postfilter' => [],
        ],
    ];
}
```

## 🎨 Стили и скрипты

### Tailwind CSS

Для полной сборки Tailwind установите npm зависимости:

```bash
npm install -D tailwindcss
npx tailwindcss init
```

Настройте `tailwind.config.js`:

```js
module.exports = {
  content: ["/local/**/*.{php,js,html}"],
  theme: {
    extend: {},
  },
  plugins: [],
}
```

Сборка:

```bash
npx tailwindcss -i ./local/templates/.default/tailwind.css -o ./local/templates/.default/styles.compiled.css --watch
```

### Кастомные CSS переменные

Все переменные в `/local/templates/.default/styles.css`:

```css
:root {
  --color-primary: #2563eb;
  --spacing-md: 1rem;
  /* ... */
}
```

## 🌐 Мультиязычность

Языковые файлы расположены:
- `/local/templates/.default/lang/ru/template.php`
- `/local/templates/.default/lang/en/template.php`

Использование:

```php
<?= GetMessage('STARTER_NAVIGATION') ?>
```

## ⚠️ Важные замечания

1. **Никогда не редактируйте `/bitrix/`** - весь код только в `/local/`
2. **Безопасность** - всегда проверяйте `check_bitrix_sessid()` в формах
3. **Кеширование** - используйте тегированный кеш для iblock данных
4. **Экранирование** - все выводы через `htmlspecialcharsbx()`

## 📝 Лицензия

MIT License - свободное использование в коммерческих проектах.

## 🤝 Поддержка

Вопросы и предложения приветствуются!
