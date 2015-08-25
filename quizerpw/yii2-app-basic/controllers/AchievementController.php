<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\components\AccessRule;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\models\ARUser;
use app\models\Achievement;
use yii\helpers\Html;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;

class AchievementController extends Controller
{
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'index'
                        ],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'view',
                            'create',
                            'update',
                            'delete'
                        ],
                        'roles' => ['admin']
                    ]
                ],
            ],
            /*
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ]*/
        ];
    }

    public function actionIndex() {

        return $this->render('index', [
            'dataProvider' => new ActiveDataProvider([
                'query' => Achievement::find()
            ])
        ]);
    }

    public function actionView() {
        if(!($achiv_id = Yii::$app->request->get('id')) || !is_numeric(Yii::$app->request->get('id')))
            throw new BadRequestHttpException('Неверный запрос');

        if(!$achiv = Achievement::findOne(intval($achiv_id)))
            throw new BadRequestHttpException('Достижение не найдено');

        return $this->render('view', [
            'achiv' => $achiv,
        ]);
    }

    public function actionDelete() {
        if(!($achiv_id = Yii::$app->request->get('id')) || !is_numeric(Yii::$app->request->get('id')))
            throw new BadRequestHttpException('Неверный запрос');

        if(!$achiv = Achievement::findOne(intval($achiv_id)))
            throw new BadRequestHttpException('Достижение не найдено');

        $achiv->delete();

        return $this->redirect(['index']);
    }

    public function actionCreate() {
        $achiv = new Achievement();

        if($achiv->load(Yii::$app->request->post())) {
            if($achiv->validate() && $this->_imageUpload($achiv)) {
                $achiv->name = Html::encode($achiv->name);
                $achiv->description = Html::encode($achiv->description);
                $achiv->conditions = Html::encode($achiv->conditions);
                $achiv->save(false);

                return $this->redirect(['view', 'achiv_id' => $achiv->achievement_id]);
            }
        }

        return $this->render('create', [
            'achiv' => $achiv,
        ]);
    }

    public function actionUpdate() {
        if(!($achiv_id = Yii::$app->request->get('id')) || !is_numeric(Yii::$app->request->get('id')))
            throw new BadRequestHttpException('Неверный запрос');

        if(!$achiv = Achievement::findOne(intval($achiv_id)))
            throw new NotFoundHttpException('Достижение не найдено');

        if($achiv->load(Yii::$app->request->post())) {
            if($achiv->validate() && $this->_imageUpload($achiv)) {
                $achiv->name = Html::encode($achiv->name);
                $achiv->description = Html::encode($achiv->description);
                $achiv->conditions = Html::encode($achiv->conditions);
                $achiv->save(false);

                return $this->redirect(['view', 'id' => $achiv->achievement_id]);
            }
        }

        return $this->render('update', [
            'achiv' => $achiv,
        ]);
    }

    private function _imageUpload(&$achiv) {
        $cur_image = $achiv->image;

        if($achiv->image = UploadedFile::getInstance($achiv, 'image')) {
            if($achiv->upload()) {
                $achiv->image = $achiv->image->name;

                return true;
            }
        } else
            $achiv->image = $cur_image;

        return false;
    }
}