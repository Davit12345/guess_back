<?php

namespace app\controllers\api;

use app\models\News;

class NewsController extends ApiBaseController
{
    public function actionGetNews()
    {
        $teams = News::find()->select('news.*,files.name as file_url')->
        leftJoin('files', 'files.id=news.file')
            ->orderBy(['id'=>SORT_DESC])->asArray()->all();

        return $this->createResponse([
            'news' => $teams,
        ]);
    }

}