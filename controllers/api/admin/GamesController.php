<?php

namespace app\controllers\api\admin;

use app\helpers\Functions;
use app\models\Games;
use app\models\GameWeek;
use app\models\Guess;
use app\models\LeagueGame;
use app\models\Leagues;
use app\models\Participant;
use app\models\Teams;
use app\models\UserLeague;

class GamesController extends ApiBaseController
{
    public function actionGetGws()
    {
        $league_id = \Yii::$app->request->post('league_id');

        $gws = GameWeek::find()->andWhere(['league_id' => $league_id])->all();
        $league = Leagues::find()->andWhere(['id' => $league_id])->one();

        return $this->createResponse(['status' => 'ok', 'gws' => $gws, 'league' => $league]);

    }

    public function actionGetGwGames()
    {
        $gw = \Yii::$app->request->post();

        $data = Games::find()->andWhere(['gw' => $gw['id'], 'league_id' => $gw['league_id']])->asArray()->all();

        return $this->createResponse(['status' => 'ok', 'games' => $data]);

    }

    public function actionAddGame()
    {
        $post = \Yii::$app->request->post();
        $home = Teams::findOne(['id' => $post['home_team']]);
        $away = Teams::findOne(['id' => $post['away_team']]);

        $game = new Games();
        $game->league_id = $post['league_id'];
        $game->gw = $post['gw'];
        $game->start_date = Functions::sqlDateFormat($post['start_date']);
        $game->name = $home['name'] . ' vs ' . $away['name'];
        if ($game->save()) {

            $home_participant = new Participant();
            $home_participant->name = $home['name'];
            $home_participant->game_id = $game->id;
            $home_participant->team_id = $post['home_team'];
            $home_participant->coefficient = $post['home_coefficient'];
            $home_participant->save();

            $away_participant = new Participant();
            $away_participant->name = $away['name'];
            $away_participant->game_id = $game->id;
            $away_participant->team_id = $post['away_team'];
            $away_participant->coefficient = $post['away_coefficient'];
            $away_participant->save() ;


            $draw_participant = new Participant();
            $draw_participant->name = 'DRAW';
            $draw_participant->game_id = $game->id;
            $draw_participant->coefficient = $post['draw_coefficient'];
            $draw_participant->save();


            $league_games = new LeagueGame();
            $league_games->game_id = $game->id;
            $league_games->league_id = $post['league_id'];
            $league_games->gw_id = $post['gw'];

            $league_games->save();


                return $this->createResponse(['status' => 'ok']);


        }
        return $this->createErrorResponse(['status' => 'chok', 'msg' => 'tada']);

    }


    public function actionGetAllGames()
    {
        $date = \Yii::$app->request->post('current_date');

        $data = Games::getGamesForAdmin($date);
        return $this->createResponse(['status' => 'ok', 'data' => $data]);

    }

    public function actionAddGameResult()
    {
        $post = \Yii::$app->request->post();

        $game = Games::findOne(['id' => $post['game_id']]);
        $game->status = Games::RESULT_UPDATED;

        $homeTeam = Participant::findOne(['id' => $post['home_team_id']]);
        $homeTeam->points = $post['home_team_result'];
        $awayTeam = Participant::findOne(['id' => $post['away_team_id']]);
        $awayTeam->points = $post['away_team_result'];


        if ($homeTeam->points > $awayTeam->points) {
            $game->winner = $post['home_team_id'];
            $game->winner_coefficient=$homeTeam->coefficient;
            $homeTeam->result = 'w';
            $awayTeam->result = 'l';

        } elseif ($homeTeam->points < $awayTeam->points) {
            $game->winner = $post['away_team_id'];
            $game->winner_coefficient=$awayTeam->coefficient;

            $homeTeam->result = 'l';
            $awayTeam->result = 'w';
        } else {
            $game->winner = $post['draw_id'];
            $draw=Participant::findOne(['id'=>$post['draw_id']]);
            $game->winner_coefficient=$draw->coefficient;

            $homeTeam->result = 'd';
            $awayTeam->result = 'd';
        }

        $homeTeam->save();
        $awayTeam->save();
        if ($game->save()) {
            return $this->createResponse(['status' => 'ok']);

        } else {
            return $this->createErrorResponse(['msg' => $game->errors]);

        }

    }

    public function actionAddFinished()
    {
        $game_id = \Yii::$app->request->post();
        $game=Games::findOne(['id'=>$game_id]);
        $leagues=LeagueGame::findAll(['game_id'=>$game_id]);
        foreach ($leagues as $league){
         $userLeague= UserLeague::findAll(['league_id'=>$league['league_id']]);
            foreach ($userLeague as &$item){
            $guess=Guess::findOne(['game_id'=>$game_id,'user_id'=>$item['user_id']]);
             if($guess['choose']==$game->winner){
                    $item->points=$item->points+1;
                    $item->points_coeff=$item->points_coeff+$game->winner_coefficient;
                    $item->save();
                }
            }
        }
        $game->status=Games::FINISHED;
        $game->save();
        return $this->createResponse(['status' => 'ok']);

    }
}