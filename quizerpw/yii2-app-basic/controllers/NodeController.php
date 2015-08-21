<?php

namespace app\controllers;

use app\models\NodeConnection;
use Yii;
use app\models\Node;
use app\models\NodeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use app\components\AccessRule;
use yii\filters\VerbFilter;

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
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'roles' => ['admin']
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Node models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NodeSearch();
        if (!isset(Yii::$app->request->queryParams['quest_id'])) {
            Yii::$app->getResponse()->redirect('/quest/');
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'quest_id' => Yii::$app->request->queryParams['quest_id'],
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

        if ($node->load(Yii::$app->request->post()) && $node->save()) {
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

        if ($node->load(Yii::$app->request->post()) && $node->save()) {
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

        $this->findModel($id)->delete();

        if(!Yii::$app->getRequest()->getIsAjax())
            return $this->redirect(['index']);

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
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
