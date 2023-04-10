<?php

namespace app\controllers\api\admin;

use app\models\Country;
use app\models\Files;
use Yii;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\helpers\Url;

class CountryController extends ApiBaseController
{
    const UPLOAD_FOLDER = 'images/uploads/all';
    const UPLOAD_FOLDER_NEWS = 'images/uploads/all/news';
    public $enableCsrfValidation = false;

    public function actionGetCountries()
    {
//        $file = Files::findOne(['id' => $model->logo]);

        $countries = Country::find()->select('country.*,files.name as file_url')->
        leftJoin('files', 'files.id=country.flag')->andWhere(['country.type' => 1])->asArray()->all();

        return $this->createResponse([
            'countries' => $countries,
        ]);
    }

    public function actionSaveCountry()
    {
        if(Yii::$app->request->post('id')){
            $model=Country::findOne(['id'=>Yii::$app->request->post('id')]);
        }else{
            $model = new Country();

        }
        if ($model->load(\Yii::$app->request->post(), '') && $model->save()) {
            return $this->createResponse([
                'status' => 'ok',
            ]);
        } else {
            return $this->createErrorResponse([
                'msg' => $model->errors,
            ]);
        }

    }

    public function actionUpload()
    {
        $type=Yii::$app->request->post('type');

        \Yii::$app->response->format = Response::FORMAT_JSON;

        $file = UploadedFile::getInstanceByName('file');
        if($type && $type=='news'){
            $name = uniqid('news') . '.' . $file->extension;
            $url = '/' . self::UPLOAD_FOLDER_NEWS . '/' . $name;

        }else{
            $name = uniqid('logo') . '.' . $file->extension;
            $url = '/' . self::UPLOAD_FOLDER . '/' . $name;

        }
        $path = \Yii::getAlias('@webroot') . $url;
        $file_model = new Files();
        $file_model->user_id = \Yii::$app->user->id;
        $file_model->name = $name;

        if ($file->saveAs($path)) {
            $file_model->save();
            $full_url = Url::to(Files::getFullUrl($file_model->name), true);
            return $this->createResponse(['status' => 'ok', 'path' => $full_url, 'model' => $file_model]);
        }
    }


}