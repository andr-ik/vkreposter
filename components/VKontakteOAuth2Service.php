<?php

namespace app\components;

use app\models\NewsfeedSearchForm;
use nodge\eauth\services\VKontakteOAuth2Service as BaseVKontakteOAuth2Service;
use Yii;
use yii\data\ArrayDataProvider;

class VKontakteOAuth2Service extends BaseVKontakteOAuth2Service
{
    const SCOPE_FRIENDS = "friends";
    const SCOPE_WALL = "wall";
    const SCOPE_VIDEO = "video";
    const SCOPE_AUDIO = "audio";
    const SCOPE_STATUS = "status";

    protected $scopes = [
        self::SCOPE_FRIENDS,
        self::SCOPE_STATUS,
        self::SCOPE_WALL,
    ];

    public function getNewsfeedSearch(NewsfeedSearchForm $model, $start_from = null)
    {
        $count = 10;

        $itemsDataProvider = new ArrayDataProvider();

        $q = $model->getQuery();

        $emptyResult = [
            'start_from' => null,
            'itemsDataProvider' => $itemsDataProvider
        ];

        if (!$q) return $emptyResult;

        $start_from = Yii::$app->request->get('start_from');

        $response = $this->makeSignedRequest('newsfeed.search', [
            'query' => [
                'q' => $q,
                'count' => $count,
                'v' => 5.52,
                'start_from' => $start_from
            ],
        ]);

        if (!isset($response['response'])) return $emptyResult;

        if (!empty($stopWords = $model->getStopWords())) {
            $stopWordsRegExp = "/(" . (implode('|', (array_map(function($item){ return preg_quote($item); }, $stopWords)))) . ")/i";
        }

        foreach ($response['response']['items'] as $i => $item) {
            if (isset($stopWordsRegExp) && preg_match($stopWordsRegExp, $item['text'])) {
                unset($response['response']['items'][$i]);
            }

            // если это группа и нету подписи -> то удаляем
            if ($item['owner_id'] < 0 && !isset($item['signer_id'])) {
                unset($response['response']['items'][$i]);
            }else{
                if($item['owner_id'] < 0){
                    $response['response']['items'][$i]['signer'] = $item['signer_id'];
                }else{
                    $response['response']['items'][$i]['signer'] = $item['from_id'];
                }
            }
        }

        return [
            'start_from' => isset($response['response']['next_from']) ? $response['response']['next_from'] : null,
            'itemsDataProvider' => new ArrayDataProvider([
                'allModels' => $response['response']['items'],
            ])
        ];
    }

    public function getGroupsAdmin(){
        $response = $this->makeSignedRequest('groups.get', [
            'query' => [
                'message' => 'test',
                'extended' => 1,
                'filter' => 'admin, editor',
                'count' => 1000,
                'v' => 5.52,
            ],
        ]);

        return $response;
    }
}