<?php

namespace app\controllers\api\admin;

use app\helpers\Functions;
use app\models\GameWeek;
use app\models\Leagues;
use Cassandra\Date;
use Yii;

class LeaguesController  extends ApiBaseController
{
    public function actionGetLeagues()
    {
        $teams = Leagues::find()->select('leagues.*,files.name as file_url')->
        leftJoin('files', 'files.id=leagues.flag')->asArray()->all();

        return $this->createResponse([
            'leagues' => $teams,
        ]);
    }

    public function actionSaveTeams()
    {
        if(Yii::$app->request->post('id')){
            $model=Leagues::findOne(['id'=>Yii::$app->request->post('id')]);
        }else{
            $model = new Leagues();

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

    public function actionSaveGw()
    {
        $post=Yii::$app->request->post();
        $gw= new GameWeek();
        $gw->league_id=$post['league_id'];
        $gw->name=$post['name'];
        $gw->start_date=Functions::sqlDateFormat($post['start_date']);
        $gw->end_date=Functions::sqlDateFormat($post['end_date']);
      if($gw->save()){
          return $this->createResponse([
              'status' => 'ok',
          ]);
      }else{
          return $this->createErrorResponse([
              'error' => $gw->errors,
          ]);
      }

    }

    public function actionAddLeague()
    {
        $post=Yii::$app->request->post();
        $league= new Leagues();
        $league->name=$post['name'];
        $league->start_date=Functions::sqlDateFormat($post['start_date']);
        $league->end_date=Functions::sqlDateFormat($post['end_date']);
        $league->type=Leagues::TYPE_CUP_LEAGUE;
        $league->show_type=Leagues::SHOW_TYPE_PUBLIC;
        $league->status=Leagues::STATUS_ACTIVE;

      if($league->save()){
          return $this->createResponse([
              'status' => 'ok',
          ]);
      }else{
          return $this->createErrorResponse([
              'error' => $league->errors,
          ]);
      }

    }


}