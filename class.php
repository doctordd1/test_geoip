<?
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Error;
use Bitrix\Main\Errorable;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Loader;
use Bitrix\Main\Web\Uri;
use \Bitrix\Main\Web\HttpClient;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class CGeoip extends CBitrixComponent implements Controllerable, Errorable
{

	public function onPrepareComponentParams($arParams)
    {
        $this->errorCollection = new ErrorCollection();

        return $arParams;
    }
    public function configureActions()
	{
		return [];
	}
    public function executeComponent() {

        $this->includeComponentTemplate();
    }
    public function getErrors(): array
    {
        return $this->errorCollection->toArray();
    }

    public function getInfoIPAction($ip)
    {
        Loader::includeModule("highloadblock"); 
        if(filter_var($ip,FILTER_VALIDATE_IP,['flags' => FILTER_FLAG_IPV4]) === false) {
            $this->errorCollection[] = new Error("Не валидный IP",0);
            return false;
        }

        $hlblockId = \Bitrix\Highloadblock\HighloadBlockTable::getList(
            array("filter" => array(
                'NAME' => 'GeoIP'
            ))
        )->fetch()['ID'];
        $arHLBlock = Bitrix\Highloadblock\HighloadBlockTable::getById($hlblockId)->fetch();
        $obEntity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arHLBlock);
        $strEntityDataClass = $obEntity->getDataClass();
        $rsData = $strEntityDataClass::getList(array(
            // необходимые для выборки поля
            'select' => array('ID', 'UF_IP', 'UF_CITY'),
            'filter' => array('=UF_IP' => $ip)
        ));
        if($arItem = $rsData->Fetch()){
            return $arItem['UF_CITY'];
        }
        else{
            $city = self::getApi($ip);
            $strEntityDataClass::add([
                'UF_IP' => $ip,
                'UF_CITY' => $city
            ]);
            return $city;
        }
        
    }
    static public function getApi($ip) {
        //  Выполнение запросов к сервисам. Получает только город. Если нужно что то еще - не сложно доработать и добавить
        $httpClient = new HttpClient([]);
        $res = $httpClient->get("https://api.sypexgeo.net/json/" . $ip);
        $res = json_decode($res,1);
        return $res['city']['name_ru'];
    }
    public function getErrorByCode($code): Error
    {
        return $this->errorCollection->getErrorByCode($code);
    }


}?>