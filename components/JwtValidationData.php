<?php


namespace app\components;


class JwtValidationData extends \sizeg\jwt\JwtValidationData
{
    public function init() {
        $jwtParams = \Yii::$app->params['jwt'];
        $this->validationData->setIssuer($jwtParams['issuer']);
        $this->validationData->setAudience($jwtParams['audience']);
        $this->validationData->setId($jwtParams['id']);

        parent::init();
    }
}