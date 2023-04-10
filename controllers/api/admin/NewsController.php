<?php

namespace app\controllers\api\admin;

use app\models\News;
use Cassandra\Date;
use Yii;

class NewsController  extends ApiBaseController
{
    public function actionGetNews()
    {
        $teams = News::find()->select('news.*')->orderBy(['id'=>SORT_DESC])->
       asArray()->all();

        return $this->createResponse([
            'news' => $teams,
        ]);
    }

    public function actionAddNews()
    {
        if(Yii::$app->request->post('id')){
            $model=News::findOne(['id'=>Yii::$app->request->post('id')]);
        }else{
            $model = new News();

        }
        if ($model->load(\Yii::$app->request->post(), '')) {
            if(empty($model->start_show_date)){
                $model->start_show_date=  date('Y-m-d H:i:s');
            }
            if(empty($model->end_show_date)){
                $model->end_show_date=  date('Y-m-d H:i:s', strtotime('+10 days'));
            }
            if($model->save()){
                return $this->createResponse([
                    'status' => 'ok',
                ]);
            }else{
                return $this->createErrorResponse([
                    'msg' => $model->errors,
                ]);
            }

        } else {
            return $this->createErrorResponse([
                'msg' => $model->errors,
            ]);
        }

    }
}