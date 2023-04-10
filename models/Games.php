<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "games".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $type
 * @property int|null $status
 * @property int|null $league_id
 * @property int|null $gw
 * @property string|null $start_date
 * @property string|null $changeable_date
 * @property string|null $description
 * @property string|null $start_show_date
 * @property string|null $winner
 * @property float|null $winner_coefficient
 */
class Games extends \yii\db\ActiveRecord
{
    public $participant;

     const RESULT_NEW=1;

     const RESULT_UPDATED=3;
     const FINISHED=4;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'games';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'league_id','gw'], 'integer'],
            [['start_date', 'changeable_date', 'start_show_date','winner'], 'safe'],
            [['description', 'participant'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['winner_coefficient'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'type' => Yii::t('app', 'Type'),
            'league_id' => Yii::t('app', 'league id'),
            'start_date' => Yii::t('app', 'Start Date'),
            'changeable_date' => Yii::t('app', 'Changeable Date'),
            'description' => Yii::t('app', 'Description'),
            'start_show_date' => Yii::t('app', 'Start Show Date'),
        ];
    }

    public static function getGames($date)
    {
        $games = Games::find()->select('games.*,leagues.name as league_name')
            ->leftJoin('leagues', 'games.league_id=leagues.id')
            ->andFilterWhere(['>', 'games.start_date', $date . ' 00:00:00'])
            ->andFilterWhere(['<', 'games.start_date', $date . ' 23:59:59'])
            ->orderBy([
                'league_id' => SORT_ASC,
                'start_date' => SORT_ASC,

            ])->asArray()->all();

        $rdata = [];
        $i = 0;
        foreach ($games as $game) {
            $rdata[$i]['game'] = $game;
            $rdata[$i]['participant'] = Participant::find()->select("participant.*,files.name as file_url ")->
                leftJoin('teams','teams.id=participant.team_id')->
                 leftJoin('files', 'files.id=teams.flag')
                ->andWhere(['game_id' => $game['id']])->asArray()->all();

            $rdata[$i]['guess'] = Guess::find()->andWhere(['game_id' => $game['id'], 'user_id' => Yii::$app->user->identity->id])->one();
            $i = $i + 1;
        }

        return $rdata;
    }

    public static function getGamesForAdmin($date)
    {
        $games = Games::find()->select('games.*,leagues.name as league_name')
            ->leftJoin('leagues', 'games.league_id=leagues.id')
            ->andFilterWhere(['>', 'games.start_date', $date . ' 00:00:00'])
            ->andFilterWhere(['<', 'games.start_date', $date . ' 23:59:59'])
            ->orderBy([
                'league_id' => SORT_ASC,
                'start_date' => SORT_ASC,

            ])->asArray()->all();

        $rdata = [];
        $i = 0;
        foreach ($games as $game) {
            $rdata[$i]['game'] = $game;
            $rdata[$i]['participant'] = Participant::find()->andWhere(['game_id' => $game['id']])->all();
            $i = $i + 1;
        }

        return $rdata;
    }


    public static function getAll()
    {
        $games = Games::find()->select('games.*,leagues.name as league_name')
            ->leftJoin('leagues', 'games.league_id=leagues.id')
            ->orderBy([
                'league_id' => SORT_ASC,
                'start_date' => SORT_ASC,

            ])->asArray()->all();

        $rdata = [];
        $i = 0;
        foreach ($games as $game) {
            $rdata[$i]['game'] = $game;
            $rdata[$i]['participant'] = Participant::find()->andWhere(['game_id' => $game['id']])->all();
            $rdata[$i]['guess'] = Guess::find()->andWhere(['game_id' => $game['id'], 'user_id' => Yii::$app->user->identity->id])->one();
            $i = $i + 1;
        }

        return $rdata;
    }

    public static function getGamesByGW($date)
    {
        $games = Games::find()->select('games.*,leagues.name as league_name')
            ->leftJoin('league_game', ' games.id=league_game.game_id ')
            ->leftJoin('leagues', 'league_game.league_id=leagues.id')

             ->andWhere(['league_game.gw_id'=>$date['id']/*,'league_game.league_id'=>$date['league_id']*/])
            ->orderBy([
                'games.start_date' => SORT_ASC,
            ])->asArray()->all();

        $rdata = [];
        $i = 0;
        foreach ($games as $game) {
            $rdata[$i]['game'] = $game;
            $rdata[$i]['participant'] =Participant::find()->select("participant.*,files.name as file_url ")->
            leftJoin('teams','teams.id=participant.team_id')->
            leftJoin('files', 'files.id=teams.flag')
                ->andWhere(['game_id' => $game['id']])->asArray()->all();
            $rdata[$i]['guess'] = Guess::find()->andWhere(['game_id' => $game['id'], 'user_id' => Yii::$app->user->identity->id])->one();
            $i = $i + 1;
        }

        return $rdata;
    }



    public static function leagueCount($date)
    {
        $league_count = Games::find()->select('games.league_id')
            ->andFilterWhere(['>', 'games.start_date', $date . ' 00:00:00'])
            ->andFilterWhere(['<', 'games.start_date', $date . ' 23:59:59'])
            ->groupBy('league_id')
            ->orderBy([
                'league_id' => SORT_ASC,
                'start_date' => SORT_ASC,

            ])->asArray()->all();
        return count($league_count);
    }



}
