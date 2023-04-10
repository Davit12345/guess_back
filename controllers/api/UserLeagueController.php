<?php

namespace app\controllers\api;

use app\models\UserLeague;

class UserLeagueController extends ApiBaseController
{

    public function actionGetLeaguePlayers()
    {
        $league_id = \Yii::$app->request->post('league_id');
        $order = \Yii::$app->request->post('order');
        $players = [];

        if ($order == 'point') {
            $players = UserLeague::find()->select('user_league.*,users.username,users.fname,users.lname')
                ->leftJoin('users', 'user_league.user_id=users.id')->
                andWhere(['user_league.status' => UserLeague::STATUS_ACTIVE, 'user_league.league_id' => $league_id])
                ->orderBy(['user_league.points_coeff' => SORT_DESC])->asArray()->all();
        } else {
            $players = UserLeague::find()->select('user_league.*,users.username,users.fname,users.lname')
                ->leftJoin('users', 'user_league.user_id=users.id')->
                andWhere(['user_league.status' => UserLeague::STATUS_ACTIVE, 'user_league.league_id' => $league_id])
                ->orderBy(['user_league.points' => SORT_DESC])->asArray()->all();
        }


        return $this->createResponse(['status' => 'ok', 'players' => $players]);

    }
}
