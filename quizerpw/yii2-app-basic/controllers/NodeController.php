<?php

namespace app\controllers;

use app\models\NodeConnection;
use app\models\NodeHint;
use app\models\Quest;
use Yii;
use app\models\Node;
use app\models\NodeSearch;
use yii\web\Controller;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use app\components\AccessRule;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;

/**
 * NodeController implements the CRUD actions for Node model.
 */
class NodeController extends Controller
{
    public function behaviors()
    {
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
                            'index',
                            'view',
                            'create',
                            'update',
                            'hints',
                            'createhint',
                            'updatehint',
                            'deletehint',
                            'delete'
                        ],
                        //'roles' => ['admin']
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return Yii::$app->user->identity->getIsAdmin();
                        }
                    ]
                ],
            ],/*
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],*/
        ];
    }

    /**
     * Lists all Node models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new NodeSearch();

        if(!isset(Yii::$app->request->queryParams['quest_id']))
            Yii::$app->getResponse()->redirect('/quest/');

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'quest' => Quest::findOne(Yii::$app->request->queryParams['quest_id']),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Node model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Node model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $node = new Node();
        $node->scenario = Node::QUESTION_TYPE_TEXT;

        if ($node->load(Yii::$app->request->post()) && $this->_uploadFiles($node)) {
            $node->name = Html::encode($node->name);
            $node->question = Html::encode($node->question);
            $node->description = Html::encode($node->description);
            $node->success_message = Html::encode($node->success_message);

            $node->save(false);

            if (!Yii::$app->getRequest()->getIsAjax())
                return $this->redirect(['view', 'id' => $node->id]);
        } else {
            if (Yii::$app->request->get('quest_id'))
                $node->quest_id = Yii::$app->request->get('quest_id');

            if(Yii::$app->getRequest()->getIsAjax())
                return $this->renderPartial('create', [
                    'node' => $node,
                ]);
            else
                return $this->render('create', [
                    'node' => $node,
                ]);
        }

        Yii::$app->end();
    }

    private function _uploadFiles(&$node) {
        $return = false;
        $cur_css = $node->css;
        $cur_js = $node->js;
        $cur_success_css = $node->success_css;
        $cur_success_js = $node->success_js;
        $cur_question = $node->question;
        $node->scenario = $node->question_type;

        if(in_array($node->scenario, [
            Node::QUESTION_TYPE_IMAGE,
            Node::QUESTION_TYPE_JS_FILE,
            Node::QUESTION_TYPE_PHP_FILE
        ]))
            $node->question = UploadedFile::getInstance($node, 'question');

        if($node->validate()) {
            if($node->question instanceof UploadedFile) {
                $return = $node->uploadQuestion();
                $node->question = $node->question->name;
            } else
                $return = true;

            if ($node->css = UploadedFile::getInstance($node, 'css')) {
                if ($return = $node->uploadCss()) {
                    $node->css = $node->css->name;
                }
            } else
                $node->css = $cur_css;

            if ($node->js = UploadedFile::getInstance($node, 'js')) {
                if ($return = $node->uploadJs()) {
                    $node->js = $node->js->name;
                }
            } else
                $node->js = $cur_js;

            if ($node->success_css = UploadedFile::getInstance($node, 'success_css')) {
                if ($return = $node->uploadSuccessCss()) {
                    $node->success_css = $node->success_css->name;
                }
            } else
                $node->success_css = $cur_success_css;

            if ($node->success_js = UploadedFile::getInstance($node, 'success_js')) {
                if ($return = $node->uploadSuccessJs()) {
                    $node->success_js = $node->success_js->name;
                }
            } else
                $node->success_js = $cur_success_js;
        }

        return $return;
    }

    /**
     * Updates an existing Node model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @throws BadRequestHttpException
     * @return mixed
     */
    public function actionUpdate() {
        if(!($id = Yii::$app->request->get('id')) || !is_numeric(Yii::$app->request->get('id')))
            throw new BadRequestHttpException('Неверный запрос');

        $node = $this->findModel((int)$id);
        $node->scenario = $node->question_type;
        $node->question = Html::decode($node->question);

        if ($node->load(Yii::$app->request->post()) && $this->_uploadFiles($node)) {
            $node->name = Html::encode($node->name);
            $node->question = Html::encode($node->question);
            $node->description = Html::encode($node->description);
            $node->success_message = Html::encode($node->success_message);
            $node->save(false);

            if (!Yii::$app->getRequest()->getIsAjax())
                return $this->redirect(['view', 'id' => $node->id]);
        } else {
            if(Yii::$app->getRequest()->getIsAjax())
                return $this->renderPartial('update', [
                    'node' => $node,
                ]);
            else
                return $this->render('update', [
                    'node' => $node,
                ]);
        }

        Yii::$app->end();
    }

    public function actionHints() {
        if(!($id = Yii::$app->request->get('id')) || !is_numeric(Yii::$app->request->get('id')))
            throw new BadRequestHttpException('Неверный запрос');

        $node = $this->findModel(intval($id));

        return $this->render('hints', [
            'quest' => Quest::find()->where(['id' => $node->quest_id])->one(),
            'node' => $node,
            'dataProvider' => new ActiveDataProvider([
                'query' => NodeHint::find()->where(['node_id' => $id])->orderBy('attemp'),
            ])
        ]);
    }

    public function actionCreatehint() {
        if(!($id = Yii::$app->request->get('id')) || !is_numeric(Yii::$app->request->get('id')))
            throw new BadRequestHttpException('Неверный запрос');

        if(!$node = $this->findModel(intval($id)))
            throw new NotFoundHttpException('Страница не найдена');

        $quest = Quest::findOne($node->quest_id);
        $hint = new NodeHint();

        if($hint->load(Yii::$app->request->post()) && $hint->validate()) {
            $hint->message = Html::encode($hint->message);
            $hint->node_id = $node->id;
            $hint->save(false);

            return $this->redirect(['hints', 'id' => $hint->node_id]);
        }

        return $this->render('create-hint', [
            'quest' => $quest,
            'node' => $node,
            'hint' => $hint
        ]);
    }

    public function actionUpdatehint() {
        if(!($id = Yii::$app->request->get('id')) || !is_numeric(Yii::$app->request->get('id')))
            throw new BadRequestHttpException('Неверный запрос');

        if(!$hint = NodeHint::findOne(intval($id)))
            throw new NotFoundHttpException('Страница не найдена');

        $node = Node::findOne($hint->node_id);
        $quest = Quest::findOne($node->quest_id);
        $hint->message = Html::decode($hint->message);

        if ($hint->load(Yii::$app->request->post()) && $hint->validate()) {
            $hint->message = Html::encode($hint->message);
            $hint->save(false);

            return $this->redirect(['hints', 'id' => $hint->node_id]);
        }

        return $this->render('update-hint', [
            'quest' => $quest,
            'node' => $node,
            'hint' => $hint
        ]);
    }

    public function actionDeletehint() {
        if(!($id = Yii::$app->request->get('id')) || !is_numeric(Yii::$app->request->get('id')))
            throw new BadRequestHttpException('Неверный запрос');

        if(!$hint = NodeHint::findOne(intval($id)))
            throw new NotFoundHttpException('Страница не найдена');

        $hint->delete();

        return $this->redirect(['hints', 'id' => $hint->node_id]);
    }

    /**
     * Deletes an existing Node model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @throws BadRequestHttpException
     * @return mixed
     */
    public function actionDelete() {
        if(!($id = Yii::$app->request->get('id')) || !is_numeric(Yii::$app->request->get('id')))
            throw new BadRequestHttpException('Неверный запрос');

        $connections = NodeConnection::find()
            ->where(['from_node_id' => (int)Yii::$app->request->get('id')])
            ->orWhere(['from_node_id' => (int)Yii::$app->request->get('id')])->all();

        foreach($connections as $connect)
            $connect->delete();

        $node = $this->findModel($id);
        $quest_id = $node->quest_id;
        $node->delete();

        if(!Yii::$app->getRequest()->getIsAjax())
            return $this->redirect(['index', 'quest_id' => $quest_id]);

        Yii::$app->end();
    }

    /**
     * Finds the Node model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Node the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Node::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Вопрос не найден');
        }
    }
}
