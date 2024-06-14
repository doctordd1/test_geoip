<?php

namespace Sprint\Migration;


class CreateGeoIP20240614021847 extends Version
{
    protected $description = "CreateGeoIP";

    protected $moduleVersion = "4.6.1";
    protected $arFields = []; # Поля можно вынести сюда
    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $hlblockId = $helper->Hlblock()->getHlblockIdIfExists('GeoIP');
        $obUserField  = new \CUserTypeEntity;
        $obUserField->Add([
            'ENTITY_ID' => 'HLBLOCK_' . $hlblockId,
            'FIELD_NAME' => 'UF_IP',
            'USER_TYPE_ID' => 'string',
            'MANDATORY' => 'Y',
            "EDIT_FORM_LABEL" => Array('ru'=>'IP'), 
            "LIST_COLUMN_LABEL" => Array('ru'=>'IP'),
            "LIST_FILTER_LABEL" => Array('ru'=>'IP'), 
        ]);
        $obUserField->Add([
            'ENTITY_ID' => 'HLBLOCK_' . $hlblockId,
            'FIELD_NAME' => 'UF_CITY',
            'USER_TYPE_ID' => 'string',
            'MANDATORY' => 'Y',
            "EDIT_FORM_LABEL" => Array('ru'=>'Город'), 
            "LIST_COLUMN_LABEL" => Array('ru'=>'Город'),
            "LIST_FILTER_LABEL" => Array('ru'=>'Город'), 
        ]);

    }

    public function down()
    {
        // Тут надо сделать удаление этих полей. 
        //your code ...
    }
}
