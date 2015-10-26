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
