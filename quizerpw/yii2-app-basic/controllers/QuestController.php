<?php

namespace app\controllers;

use app\models\NodesConnections;
use Yii;
use app\models\Quest;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\Html;
use app\models\Node;
use app\models\QuestTag;
use app\models\NodeSearch;

/**
 * QuestController implements the CRUD actions for Quest model.
 */
class QuestController extends Controller
{
    public function behaviors() {
        //var_dump(Yii::$app->getAuthManager()); exit;
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'actions' => ['index', 'run'], 'roles' => ['@']],
                    //['allow' => false, 'actions' => ['visual'], 'roles' => ['user']],
                    ['allow' => true, 'actions' => [
                        'visual', 'create', 'save', 'update', 'view', 'delete', 'run', 'preload'
                    ], 'roles' => ['admin']],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ]
        ];
    }

    /**
     * Lists all Quest models.
     * @return mixed
     */
    public function actionIndex() {
        return $this->render('index', [
            'dataProvider' => new ActiveDataProvider([
                'query' => Quest::find(),
            ])
        ]);
    }

    /**
     * Lists all Quest models.
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @return mixed
     */
    public function actionVisual() {
        if(!Yii::$app->request->get('quest_id'))
            throw new BadRequestHttpException('Неверный запрос');

        if(!($quest = Quest::findOne((int)Yii::$app->request->get('quest_id'))))
            throw new NotFoundHttpException('Страница не найдена');

        $dataProvider = new ActiveDataProvider([
            'query' => Node::find()->where(array('quest_id'=>Yii::$app->request->get('quest_id')))
        ]);

        return $this->render('visual', [
            'nodes' => $dataProvider->getModels(),
            'chain' => Quest::getChain(Yii::$app->request->get('quest_id')),
            'quest' => $quest
        ]);
    }

    /**
     * Save connection from visual admin
     * @return mixed
     */
    public function actionSave() {
        Node::cleanConnections(Yii::$app->request->post('quest_id'));

        if($pos = Yii::$app->request->post('pos'))
            foreach($pos as $id => $data) {
                $id = intval(str_replace('flowchartWindow', '', $id));
                $node = Node::findOne($id);
                $node->left = (int)str_replace('px', '', $data['left']);
                $node->top = (int)str_replace('px', '', $data['top']);
                $node->save();
            }

        if($connects = Yii::$app->request->post('connects'))
            foreach($connects as $key => $connect) {
                $srcId = (int)str_replace('flowchartWindow', '', $connect['src']);
                $trgId = (int)str_replace('flowchartWindow', '', $connect['trg']);

                if(($nodeSrc = Node::findOne($srcId)) && ($nodeTrg = Node::findOne($trgId))) {
                    $connection = new NodesConnections();
                    $connection->quest_id = (int)Yii::$app->request->post('quest_id');
                    $connection->from_node_id = $nodeSrc->id;
                    $connection->to_node_id = $nodeTrg->id;

                    if(strpos($connect['uuid'], 'RightMiddle') !== false)
                        $connection->type = 'next';
                    else
                        $connection->type = 'prev';

                    $connection->save();
                }
            }

        return true;
    }

    public function getConnections($quest_id) {
        $dataProvider = new ActiveDataProvider([
            'query' => Node::find()->where(array('quest_id' => $quest_id)),
        ]);

        $connections = [];

        if ($nodes = $dataProvider->getModels()) {
            foreach ($nodes as $node) {
                if ($node->next)
                    $connections[] = ['src' => $node->id, 'trg' => $node->next, 'type' => 'next'];
                elseif ($node->prev)
                    $connections[] = ['src' => $node->id, 'trg' => $node->prev, 'type' => 'prev'];
            }
        }

        return $connections;
    }

    /**
     * Displays a single Quest model
     * @throws BadRequestHttpException
     * @return mixed
     */
    public function actionView() {
        if(!($id = Yii::$app->request->get('id')))
            throw new BadRequestHttpException('Неверный запрос');

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Process uploading of logo
     * @param object $model
     * @return bool
     */
    protected function _logoUpload(&$model, $upload_dir = null, $file_name = null, $no_validate = false) {
        if ($model->logoFile = UploadedFile::getInstance($model, 'logo')) {
            if($model->upload($upload_dir, $file_name, $no_validate)) {
                $model->logo = $model->logoFile->name;
                $model->logoFile = null;

                return true;
            }
        }

        return false;
    }

    /**
     * Creates a new Quest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Quest();
        $model->date_start = date('d.m.Y');

        if($model->load(Yii::$app->request->post())) {
            $this->_logoUpload($model);

            if($model->validate()) {
                $model->date_start = date('Y-m-d', strtotime($model->date_start));
                $model->date_finish = date('Y-m-d', strtotime($model->date_finish));
                $model->url = $model->url ? : null;
                $model->save(false);

                if($tags = Yii::$app->getRequest()->post('HashTags')) {
                    $values = [];

                    foreach($tags as $tag) {
                        if(!empty($tag))
                            $values[] = '(NULL, ' . $model->id . ', \'' . Html::encode($tag) . '\', \'' . QuestTag::translit(Html::encode($tag)) . '\')';
                    }

                    if(count($values) > 0) {
                        Yii::$app->db->createCommand('DELETE FROM quests_tags WHERE quest_id = :quest_id', [
                            ':quest_id' => $model->id
                        ])->execute();
                        Yii::$app->db->createCommand('REPLACE INTO quests_tags VALUES ' . implode(', ', $values))->execute();
                    }
                }

                return $this->redirect(['visual', 'quest_id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionPreload() {
        if(!Yii::$app->getRequest()->getIsAjax() || !isset($_FILES['Quest']))
            throw new BadRequestHttpException('Неверный запрос');

        if(!file_exists($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'assets'))
            mkdir($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'assets');

        if(!file_exists($upload_dir = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'tmp'))
            mkdir($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'tmp');

        $quest = new Quest();

        if($this->_logoUpload($quest, $upload_dir, 'cq-'.Yii::$app->getUser()->getId(), true)) {
            return json_encode([
                'type' => 'success',
                'resource' => '/assets/tmp/'.$quest->logo.'?'.rand(0, 1000)
            ]);
        } else
            return json_encode([
                'type' => 'error',
                'resource' => 'Не удалось загрузить изобоажение'
            ]);
    }

    /**
     * Updates an existing Quest model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @throws BadRequestHttpException
     * @return mixed
     */
    public function actionUpdate() {
        if(!($id = Yii::$app->request->get('id')))
            throw new BadRequestHttpException('Неверный запрос');

        $model = $this->findModel((int)$id);
        $model->date_start = date('d.m.Y', strtotime($model->date_start));
        $model->date_finish = $model->date_finish ? date('d.m.Y', strtotime($model->date_finish)) : '';
        $tmp_logo = $model->logo;

        if($model->load(Yii::$app->request->post())) {
            $this->_logoUpload( $model );
            $model->logo = $model->logo ? : $tmp_logo;

            if($model->validate()) {
                $model->date_start = date('Y-m-d', strtotime($model->date_start));
                $model->date_finish = $model->date_finish ? date('Y-m-d', strtotime($model->date_finish)) : '';
                $model->save(false);

                if($tags = Yii::$app->getRequest()->post('HashTags')) {
                    $values = [];

                    foreach($tags as $tag) {
                        if(!empty($tag))
                            $values[] = '(NULL, ' . $model->id . ', \'' . Html::encode($tag) . '\', \'' . QuestTag::translit(Html::encode($tag)) . '\')';
                    }

                    if(count($values) > 0) {
                        Yii::$app->db->createCommand('DELETE FROM quests_tags WHERE quest_id = :quest_id', [
                            ':quest_id' => $model->id
                        ])->execute();
                        Yii::$app->db->createCommand('REPLACE INTO quests_tags VALUES ' . implode(', ', $values))->execute();
                    }
                }

                return $this->redirect(['visual', 'quest_id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'tags' => QuestTag::find()->where('quest_id = :quest_id', [':quest_id' => $model->id])->all()
        ]);
    }

    /**
     * Deletes an existing Quest model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @return mixed
     */
    public function actionDelete() {
        if(!($id = Yii::$app->request->get('id')))
            throw new BadRequestHttpException('Неверный запрос');

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Run quest
     * @throws BadRequestHttpException
     * @return mixed
     */
    public function actionRun() {
        if(!($id = Yii::$app->request->get('id')))
            throw new BadRequestHttpException('Неверный запрос');

        $runInfo = Yii::$app->session->get('run_'.(int)$id);

        if ($runInfo['current'])
            $currentNode = Node::findModel((int)$runInfo['current']);
        else
            $currentNode = Quest::getFirstNode((int)$id);

        return $this->render('run', [
            'node' => $currentNode,
            'quest' => $this->findModel($currentNode->quest_id)
        ]);
    }

    public function actionCheck($nodeId, $answer = null) {
        $node = Node::findModel($nodeId);
    }

    /**
     * Finds the Quest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Quest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Quest::findOne($id)) !== null)
            return $model;
        else
            throw new NotFoundHttpException('Запрошенная страница не найдена');
    }
}
