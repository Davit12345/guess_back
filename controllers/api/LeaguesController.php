<?php


namespace app\controllers\api;


use app\helpers\Functions;
use app\models\Games;
use app\models\GameWeek;
use app\models\LeagueGame;
use app\models\Leagues;
use app\models\UserLeague;
use Yii;

class LeaguesController  extends ApiBaseController
{

    public function actionGetUserLeagues()
    {
        $basic_leagues =UserLeague::find()->select('leagues.*')->
        leftJoin('leagues','leagues.id=user_league.league_id')->
        andWhere(['user_league.user_id' =>\Yii::$app->user->id])->

        andWhere(['or',
            ['=', 'leagues.type', Leagues::TYPE_REGULAR_LEAGUE],
            ['=', 'leagues.type', Leagues::TYPE_CUP_LEAGUE]
        ]) ->andWhere(['leagues.status'=>Leagues::STATUS_ACTIVE])
            ->asArray()->all();

        $user_created_leagues =UserLeague::find()->select('leagues.*')->
        leftJoin('leagues','leagues.id=user_league.league_id')->
        andWhere(['user_league.user_id' =>\Yii::$app->user->id])->

        andWhere( ['=', 'leagues.type', Leagues::TYPE_USER_OWN_LEAGUE])
            ->andWhere(['leagues.status'=>Leagues::STATUS_ACTIVE])
            ->asArray()->all();


        return $this->createResponse(['status' => 'ok', 'basic_leagues' => $basic_leagues,'user_created_leagues'=>$user_created_leagues]);
    }


    public function actionGetGws()
    {
        $league_id = \Yii::$app->request->post('league_id');

        $gws=GameWeek::find()->andWhere(['league_id'=>$league_id])->all();

        return $this->createResponse(['status' => 'ok', 'gws' => $gws]);

    }
    public function actionGetGwGames()
    {
        $gw = \Yii::$app->request->post();

        $data = Games::getGamesByGW($gw);

        return $this->createResponse(['status' => 'ok', 'games' => $data]);

    }

    public function actionAddUserLeague()
    {
        $games=Yii::$app->request->post('games');
        $post_league=Yii::$app->request->post('league');



        $league= new Leagues();
        $league->name=$post_league['name'];
        $league->start_date=Functions::sqlDateFormat($post_league['start_date']);
        $league->end_date=Functions::sqlDateFormat($post_league['end_date']);
        $league->type=Leagues::TYPE_USER_OWN_LEAGUE;
        $league->show_type=Leagues::SHOW_TYPE_USER_PUBLIC;
        $league->status=Leagues::STATUS_ACTIVE;
        $league->creator_id=Yii::$app->user->id;

        if($league->save()){
            $gw=new GameWeek();
            $gw->league_id=$league->id;
            $gw->name='Only';
            $gw->start_date=$post_league['start_date'];
            $gw->end_date=$post_league['end_date'];
            $gw->save();

            foreach ($games as $game) {
                $league_game= new LeagueGame();
                $league_game->league_id=$league->id;
                $league_game->game_id=$game;
                $league_game->gw_id=$gw->id;
                $league_game->save();
            }

            $user_league= new UserLeague();
            $user_league->league_id=$league->id;
            $user_league->user_id=Yii::$app->user->id;
            $user_league->status=UserLeague::STATUS_ACTIVE;
            $user_league->save();

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