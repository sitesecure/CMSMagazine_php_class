<?php

/**
*  
* Класс, описывающий запись сайта
* в системе Sitesecure, прикрепленного
* к аккаунту CMSMagazine
* 
* Пример использования:
* $site_record = new SitesecureSiteRecord("key", "site.ru");
* $site_record->registerSite(); //регистрируем сайт
* $site_record->changePlan("paid"); //меняем тариф сайта
* $site_record->getStatus(); //получаем статусы по сайту
*  
*/
class SitesecureSiteRecord
{

	/**
	* 
	* Функция конструктор класса принимает пароль доступа и название сайта, 
	* внутри функции задается адрес сервера sitesecure
	* 
	*/
	function __construct($key, $site) {
		$this->site = $site;
		$this->sign = md5($site.$key);
		$this->sserver = "https://sitesecure.ru";
	}

	/**
	* 
	* Защищенная функция sendRequest делает запрос к удаленному серверу ($url)
	* c параметрами $data, если массив параметров не задан делается GET запрос,
	* иначе, POST, при этом массив преобразовывается в Json. Результатом выполнения
	* является массив ответа сервера.
	* 
	* string $url, array $data
	* =>
	* result array
	* 
	*/
    protected function sendRequest($url, $data=false){
        $ch = curl_init($url);
         
        //Encode the array into JSON.
        if ($data){
            $jsonDataEncoded = json_encode($data);
            echo $jsonDataEncoded;
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	        //Tell cURL that we want to send a POST request.
	        curl_setopt($ch, CURLOPT_POST, 1);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);         

        return json_decode(curl_exec($ch), $assoc = TRUE);
    }

	/**
	* 
	* Функция пытается зарегистрировать сайт в системе Sitesecure
	* на аккаунт CMSMagazine. Результатом является массив, содержащий ответ
	* сервера sitesecure. 
	* 
	* Варианты ответа:
	*   Положительный:
	*       [status] => success
    *       [plan] => CMSMagazine::Trial
	*   Отрицательный:
	*       [status] => error
    *       [type] => ActiveRecord::RecordInvalid
    *       [message] => Error: Возникли ошибки: Name Этот домен уже добавлен
	* 
	*/
    public function registerSite(){
    	$url = $this->sserver.'/api/cms_magazine/register.json';
    	$data['website'] = $this->site;
    	$data['sign'] = $this->sign;
    	return $this->sendRequest($url, $data);
    }

	/**
	* 
	* Функция пытается сменить тариф($plan) сайта в системе Sitesecure
	* на аккаунте CMSMagazine. Результатом является массив, содержащий ответ
	* сервера sitesecure. 
	* 
	* Типы тарифов: trial - пробный, paid - платный, unpaid - без нотификаций
	* 
	* Варианты ответа:
	*   Положительный:
	*       [status] => success
	*       [website] => mail.ru
    *       [plan] => CMSMagazine::Unpaid
	*   Отрицательный:
	*       [status] => error
    *       [type] => NameError
    *       [message] => Error: uninitialized constant Website::Plan::CMSMagazine::Prepaid
	* 
	*/
    public function changePlan($plan){
    	$url = $this->sserver.'/api/cms_magazine/change.json';
    	$data['website'] = $this->site;
    	$data['sign'] = $this->sign;
    	$data['plan'] = $plan;
    	return $this->sendRequest($url, $data);
    }

	/**
	* 
	* Функция пытается получить статус безопасности сайта в системе Sitesecure
	* на аккаунте CMSMagazine. Результатом является массив, содержащий ответ
	* сервера sitesecure. 
	* 
	* Варианты ответа:
	*   Положительный:
	*       [status] => success
	*       [website] => mail.ru
	*       [checks] => Array
	*           (
	*               [0] => Array
	*                   (
	*                       [type] => availability
	*                       [name] => Доступность
	*                       [urgency] => none
	*                       [message] => Сайт доступен и открывается
	*                   )
	*
	*               [1] => Array
	*                   (
	*                       [type] => delegation
	*                       [name] => Срок истечения доменного имени
	*                       [urgency] => none
	*                       [message] => Не удалось получить информацию по доменному имени
	*                   )
	*
	*               [2] => Array
	*                   (
	*                       [type] => blacklist
	*                       [name] => Наличие в черных списках поисковых систем
	*                       [urgency] => none
	*                       [message] => Сайт не обнаружен в черных списках
	*                   )
	*
	*               [3] => Array
	*                   (
	*                       [type] => antivirusbase
	*                       [name] => Наличие в базах антивирусов
	*                       [urgency] => none
	*                       [message] => Сайт не обнаружен в базах данных антивирусов
	*                   )
	*
	*               [4] => Array
	*                   (
	*                       [type] => malware
	*                       [name] => Заражения сайта
	*                       [urgency] => none
	*                       [message] => На сайте не обнаружено заражений
	*                   )
	*
	*             [5] => Array
	*                   (
	*                       [type] => ssl
	*                       [name] => SSL-сертификат сайта
	*                       [urgency] => none
	*                       [message] => На сайте не обнаружены уязвимости SSL-сертификата
	*                   )
	*
	*           )
	*   Отрицательный:
	*       [status] => error
    *       [type] => ActiveRecord::RecordNotFound
    *       [message] => Error: ActiveRecord::RecordNotFound
	* 
	*/
    public function getStatus(){
    	$url = $this->sserver.'/api/cms_magazine/status/?website='.$this->site.'&sign='.$this->sign;
    	return $this->sendRequest($url);
    }
}
