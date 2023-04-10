<?php


namespace app\controllers\api;


use app\models\Games;
use app\models\Guess;
use Yii;

class GamesController extends ApiBaseController
{
    public function actionGetGames()
    {
        $date = \Yii::$app->request->post('current_date');
        $data = Games::getGames($date);
        $leagueCount = Games::leagueCount($date);
        return $this->createResponse(['status' => 'ok', 'data' => $data,'leagueCount'=>$leagueCount]);

    }

    public function actionUpdateChoosen()
    {
        $games = \Yii::$app->request->post();


        foreach ($games as $game) {
            $guess = Guess::find()->andWhere(['game_id' => $game['game_id'], 'user_id' => Yii::$app->user->identity->id])->one();
            if ($guess) {
                $guess->choose = $game['choose'];
                if($guess->save()){

                }else{
                    return $this->createResponse(['status' => $guess->errors]);

                }
            } else {
                $new_guess = new Guess(['game_id' => $game['game_id'], 'user_id' => Yii::$app->user->identity->id, 'choose' => $game['choose']]);
                if($new_guess->save()){

                }else{
                    return $this->createResponse(['status' => $guess->errors]);

                }
            }
        }

        return $this->createResponse(['status' => 'ok']);

    }
}