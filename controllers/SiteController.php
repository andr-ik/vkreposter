<?php

namespace app\controllers;

use app\models\NewsfeedSearchForm;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\Response;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
            'eauth' => [
                'class' => \nodge\eauth\openid\ControllerBehavior::className(),
                'only' => ['login'],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $serviceName = 'vkontakte';
        $eauth = Yii::$app->get('eauth')->getIdentity($serviceName);

        if (!$eauth->getIsAuthenticated()) return $this->render('index');

        $model = new NewsfeedSearchForm();
        $model->load(Yii::$app->request->post());

        $model->q  = $model->q  ?: Yii::$app->request->get('q');
        $model->sq = $model->sq ?: Yii::$app->request->get('sq');

        $data = [
            'isAjax' => Yii::$app->request->isAjax,
            'model' => $model,
            'q' => $model->q,
            'sq' => $model->sq
        ];

        $data = ArrayHelper::merge($data, $eauth->getNewsfeedSearch($model));

        return Yii::$app->request->isAjax ?
            $this->renderPartial('newsfeed_search', $data) :
            $this->render('newsfeed_search', $data);
    }

    public function actionLogin() {
        $serviceName = Yii::$app->getRequest()->getQueryParam('service');
        if (isset($serviceName)) {
            $eauth = Yii::$app->get('eauth')->getIdentity($serviceName);
            $eauth->setRedirectUrl(Yii::$app->getUser()->getReturnUrl());
            $eauth->setCancelUrl(Yii::$app->getUrlManager()->createAbsoluteUrl('/'));

            try {
                if ($eauth->authenticate()) {
                    $identity = User::findByEAuth($eauth);
                    Yii::$app->getUser()->login($identity);
                    $eauth->redirect();
                }
                else {
                    $eauth->cancel();
                }
            }
            catch (\nodge\eauth\ErrorException $e) {
                Yii::$app->getSession()->setFlash('error', 'EAuthException: '.$e->getMessage());
                $eauth->redirect($eauth->getCancelUrl());
            }
        }

        return $this->goHome();
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    public function actionGroupsAdmin(){
        $serviceName = 'vkontakte';
        $eauth = Yii::$app->get('eauth')->getIdentity($serviceName);

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $eauth->getGroupsAdmin();
    }
}
