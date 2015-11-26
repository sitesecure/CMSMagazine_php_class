# CMSMagazine PHP Class

Класс SitesecureSiteRecord описывает единичную запись сайта на аккаунте CMSMagazine@SiteSecure.ru в системе SiteSecure. C записью могут производиться следующие действия:

- Инициализация: 
```php
$site_record = new SitesecureSiteRecord("key", "site.ru");
```
- Регистрация в системе:
```php
$site_record->registerSite();
```
- Смена тарифа:
```php
$site_record->changePlan("paid");
```
- Получение статуса безопасности:
```php
$site_record->getStatus();
```

Варианты тарифов: trial - пробный, paid - платный, unpaid - без нотификаций

Для работы класса необходима PHP версии 5.2+ с установленным разрешением curl. Если есть необходимость, по запросу мы можем добавить поддержку более старших верcий PHP.


Типы ошибок:

- Неверный ключ доступа
```php
Array
(
    [status] => error
    [type] => ApiWrongSignatureException
    [message] => Wrong signature provided.
)
```
- Не получается подсоединиться к серверу SiteSecure (проблемы на нашей стороне)
```php
Array
(
    [status] => error
    [type] => ConnectionError
    [message] => Can't connect to SiteSecure server
)
```
- Сайт уже зарегистрирован в системе (при попытке зарегистрировать домен)
```php
Array
(
    [status] => error
    [type] => ActiveRecord::RecordInvalid
    [message] => Error: Возникли ошибки: Name Этот домен уже добавлен
)
```
- Сайт не зарегистрирован (при попытке получения статуса безопасности или смены тарифа)
```php
Array
(
    [status] => error
    [type] => ActiveRecord::RecordNotFound
    [message] => Error: ActiveRecord::RecordNotFound
)
```
- Неверный тип тарифа
```php
Array
(
    [status] => error
    [type] => NameError
    [message] => Error: wrong constant name 2paid
)
```
