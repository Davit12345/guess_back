<?php

namespace app\controllers\api\admin;

use app\models\Teams;
use Yii;

class TeamsController extends ApiBaseController
{
    public function actionGetTeams()
    {
        $country_id=Yii::$app->request->post('country_id');
        $teams = Teams::find()->select('teams.*,files.name as file_url')->
        leftJoin('files', 'files.id=teams.flag')->andWhere(['country_id'=>$country_id])->asArray()->all();

        return $this->createResponse([
            'teams' => $teams,
        ]);
    }

    public function actionSaveTeams()
    {
        if(Yii::$app->request->post('id')){
            $model=Teams::findOne(['id'=>Yii::$app->request->post('id')]);
        }else{
            $model = new Teams();

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
}