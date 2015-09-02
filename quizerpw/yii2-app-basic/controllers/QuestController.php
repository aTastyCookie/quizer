<?php

namespace app\controllers;

use app\models\Achievement;
use app\models\NodeAnswer;
use app\models\NodeConnection;
use app\models\ARUser;
use Yii;
use app\models\Quest;
use app\models\QuestRun;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\web\Cookie;
use yii\helpers\Html;
use app\models\Node;
use app\models\QuestTag;
use app\components\AccessRule;
use app\models\NodeSearch;
use yii\base\ErrorException;
use app\models\UserAchievement;
use app\models\NodeHint;

/**
 * QuestController implements the CRUD actions for Quest model.
 */
class QuestController extends Controller
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
                            'index',
                            'view',
                            'run',
                            'choose',
                            'highscores',
                            'conformity',
                            'visual'
                        ],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'create',
                            'update',
                            'delete',
                            'save',
                            'check',
                            'preload',
                            'statistics',
                            'userstatistics',
                            'runanswers'
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

    public function actions() {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
            ],
        ];
    }

    /**
     * Lists all Quest models.
     * @return mixed
     */
    public function actionIndex() {
        return $this->render('index', [
            'dataProvider' => new ActiveDataProvider([
                'query' => Quest::find()->where(['is_closed' => false]),
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
                $node->save(false);
            }

        if($connects = Yii::$app->request->post('connects'))
            foreach($connects as $key => $connect) {
                $srcId = (int)str_replace('flowchartWindow', '', $connect['src']);
                $trgId = (int)str_replace('flowchartWindow', '', $connect['trg']);

                if(($nodeSrc = Node::findOne($srcId)) && ($nodeTrg = Node::findOne($trgId))) {
                    $connection = new NodeConnection();
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

    /**
     * Return conections of the node
     * @param int $quest_id
     * @return array
     */
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
     * Process uploading of css file
     * @param object $model
     * @return bool
     */
    protected function _cssUpload(&$model) {
        if ($model->cssFile = UploadedFile::getInstance($model, 'success_css')) {
            if($model->uploadCss()) {
                $model->success_css = $model->cssFile->name;
                $model->cssFile = null;

                return true;
            }
        }

        return false;
    }

    /**
     * Process uploading of js file
     * @param object $model
     * @return bool
     */
    protected function _jsUpload(&$model) {
        if ($model->jsFile = UploadedFile::getInstance($model, 'success_js')) {
            if($model->uploadJs()) {
                $model->success_js = $model->jsFile->name;
                $model->jsFile = null;

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
            $this->_cssUpload($model);
            $this->_jsUpload($model);

            if($model->validate()) {
                $model->date_start = date('Y-m-d', strtotime($model->date_start));
                $model->date_finish = $model->date_finish ? date('Y-m-d', strtotime($model->date_finish)) : null;
                $model->url = $model->url ? : null;
                $model->success_message = Html::encode($model->success_message);
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

    /**
     * Preloading image while create or update quest
     * If update is successful, the browser will be redirected to the 'view' page.
     * @throws BadRequestHttpException
     * @return mixed
     */
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
        $model->success_message = Html::decode($model->success_message);
        $tmp_logo = $model->logo;
        $tmp_css = $model->success_css;
        $tmp_js = $model->success_js;

        if($model->load(Yii::$app->request->post())) {
            $this->_logoUpload($model);
            $this->_cssUpload($model);
            $this->_jsUpload($model);
            $model->logo = $model->logo ? : $tmp_logo;
            $model->success_css = $model->success_css ? : $tmp_css;
            $model->success_js = $model->success_js ? : $tmp_js;

            if($model->validate()) {
                $model->date_start = date('Y-m-d', strtotime($model->date_start));
                $model->date_finish = $model->date_finish ? date('Y-m-d', strtotime($model->date_finish)) : null;
                $model->url = $model->url ? : null;
                $model->success_message = Html::encode($model->success_message);
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
     * Runing quest
     * @throws BadRequestHttpException
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @return mixed
     */
    public function actionRun() {
        if((!($id = Yii::$app->request->get('id')) || !is_numeric(Yii::$app->request->get('id'))) &&
           (!($url = Yii::$app->request->get('url'))))
            throw new BadRequestHttpException('Неверный запрос');

        $quest = $id ? $this->findModel(intval($id)) : Quest::find()->where(['url' => Html::encode($url)])->one();
        $this->_checkAccessByDate($quest);

        $answer = new NodeAnswer();
        $answer->user_id = Yii::$app->getUser()->getId();
        $current_run = QuestRun::find()->where(['quest_id' => $quest->id, 'user_id' => Yii::$app->getUser()->getId(), 'is_complete' => false])->orderBy('run_id DESC')->one();
        $current_node = null;
        $is_penalty = $this->_checkQuestRunPenalty($current_run, $answer);

        // If got answer from user, just save it. Even is a wrong answer
        if(!$is_penalty && $answer->load(Yii::$app->request->post()) && $answer->validate()) {
            if(!$current_run) {
                $current_run = new QuestRun();
                $current_run->quest_id = $answer->quest_id;
                $current_run->node_id = $answer->node_id;
                $current_run->user_id = Yii::$app->getUser()->getId();
                $current_run->time_begin = time();
            }

            $answer->checkAnswer($current_run);
            $current_run->save(false);
            $answer->run_id = $current_run->run_id;
            $answer->calculateAnswerTime($quest);
            $answer->save(false);

            // Listener event in answer (for achievements)
            Achievement::listenerEventOnAnswer($current_run, $quest, $answer);

            if(!$answer->is_wrong)
                return $id ? $this->redirect(['run', 'id' => $id]) : $this->redirect(['run', 'url' => $url]);
        }

        // Update running process or something like this
        if($current_run)
            $current_run->updateCurrentRun($quest, $current_node, $answer, $is_penalty);
        elseif($current_node = Node::find()->where(['quest_id' => $quest->id])->orderBy('number')->one()) {
            if(!Yii::$app->getRequest()->getCookies()->has('run-quest-'.$quest->id)) {
                Yii::$app->getResponse()->getCookies()->add(new Cookie([
                    'name' => 'run-quest-'.$quest->id,
                    'value' => time(),
                    'expire' => time() + 60 * 60,
                ]));
            }

            $answer->quest_id = $current_node->quest_id;
            $answer->node_id = $current_node->id;
        } else
            throw new NotFoundHttpException();

        return $this->render('run', [
            'answer' => $answer,
            'node' => $current_node,
            'quest' => $quest,
            'hint' => NodeHint::find()->where(['node_id' => $current_node->id, 'attemp' => $current_run->count_attempts])->one()
        ]);
    }

    private function _checkAccessByDate($quest) {
        $date_begin = new \DateTime($quest->date_start);
        $date_end = new \DateTime($quest->date_finish);
        $date_cur = new \DateTime(date('Y-m-d H:i:s'));

        if(!(($date_cur >= $date_begin) && ($date_cur <= $date_end)))
            throw new ForbiddenHttpException();
    }

    private function _checkQuestRunPenalty($current_run, &$answer) {
        // Wait timer while wrong answer
        if($current_run->sleep > time()) {
            $answer->load(Yii::$app->request->post());
            $answer->addError('text', 'Вы не можете ответить пока не закончится штраф. Осталось секунд: '.(($current_run->sleep - time())));
            return true;
        }

        return false;
    }

    /**
     * Choosing from variants while quest is running
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @return mixed
     */
    public function actionChoose() {
        if(!($node_id = Yii::$app->request->get('node_id')) || !is_numeric(Yii::$app->request->get('node_id')) ||
           !($quest_id = Yii::$app->request->get('quest_id')) || !is_numeric(Yii::$app->request->get('quest_id')))
            throw new BadRequestHttpException('Неверный запрос');

        if(!($current_run = QuestRun::find()
            ->where(['quest_id' => intval($quest_id), 'user_id' => Yii::$app->getUser()->getId()])
            ->orderBy('run_id DESC')
            ->one()))
            throw new NotFoundHttpException();

        if(!in_array($node_id, json_decode($current_run->next_nodes)))
            throw new BadRequestHttpException();

        $next_nodes = NodeConnection::find()->where(['from_node_id' => $node_id])->all();
        $next_ids = [];

        foreach ($next_nodes as $node)
            $next_ids[] = $node->toNodes->id;

        $current_run->node_id = $node_id;
        $current_run->next_nodes = !empty($next_ids) ? json_encode($next_ids) : null;
        $current_run->status = QuestRun::STATUS_ANSWERING;
        $current_run->save(false);

        $this->redirect(['run', 'id' => $quest_id]);
    }

    public function actionHighscores() {
        if(!($quest_id = Yii::$app->request->get('quest_id')) || !is_numeric(Yii::$app->request->get('quest_id')))
            throw new BadRequestHttpException('Неверный запрос');

        $users = ARUser::find()
            ->select('user.*, MIN(q.seconds) AS seconds')
            ->leftJoin(
                '(
                     SELECT qr.user_id, (qr.time_end - qr.time_begin) AS seconds
                     FROM quests_runs AS qr
                     WHERE qr.quest_id = '.intval($quest_id).'
                     ORDER BY seconds ASC
                 ) AS q', 'user.id = q.user_id'
            )
            ->where('NOT ISNULL(q.seconds)')
            ->groupBy('user.username')
            ->orderBy('q.seconds ASC');

        return $this->render('highscores', [
            'quest' => $this->findModel(intval($quest_id)),
            'dataProvider' => new ActiveDataProvider([
                'query' => $users,
            ])
        ]);
    }

    public function actionConformity() {
        if(!($quest_id = Yii::$app->request->get('quest_id')) || !is_numeric(Yii::$app->request->get('quest_id')))
            throw new BadRequestHttpException('Неверный запрос');

        $nodes = Node::find()
            ->select('node.*, COUNT(nap.node_id) as count_passed, COUNT(naip.node_id) as count_in_proccess, nap.avg_time')
            ->leftJoin(
                '(
                    SELECT na.node_id, na.user_id, SUM(na.status) AS status, AVG(na.time) AS avg_time
                    FROM nodes_answers AS na
                    GROUP BY na.node_id, na.user_id
                    HAVING status != 0
                ) AS nap', 'node.id = nap.node_id'
            )
            ->leftJoin(
                '(
                    SELECT na.node_id, na.user_id, SUM(na.status) AS status
                    FROM nodes_answers AS na
                    GROUP BY na.node_id, na.user_id
                    HAVING status = 0
                ) AS naip', 'node.id = naip.node_id'
            )
            ->where(['quest_id' => intval($quest_id)])
            ->groupBy('node.id')
            ->orderBy('number');


        return $this->render('conformity', [
            'quest' => $this->findModel(intval($quest_id)),
            'dataProvider' => new ActiveDataProvider([
                'query' => $nodes,
            ])
        ]);
    }

    public function actionStatistics() {
        if(!($id = Yii::$app->request->get('id')) || !is_numeric(Yii::$app->request->get('id')))
            throw new BadRequestHttpException('Неверный запрос');

        $quest = $this->findModel($id);
        $user_ids = [];

        foreach(QuestRun::find()->where(['quest_id' => $id])->all() as $run)
            $user_ids[] = $run->user_id;

        if(empty($user_ids))
            $user_ids[] = 0;

        return $this->render('statistics', [
            'quest' => $quest,
            'dataProvider' => new ActiveDataProvider([
                'query' => ARUser::find()->where('id IN ('.implode(', ', $user_ids).')')->orderBy('username'),
            ])
        ]);
    }

    public function actionUserstatistics() {
        if(!($quest_id = Yii::$app->request->get('quest_id')) || !is_numeric(Yii::$app->request->get('quest_id')) ||
           !($user_id = Yii::$app->request->get('user_id')) || !is_numeric(Yii::$app->request->get('user_id')))
            throw new BadRequestHttpException('Неверный запрос');

        $quest = $this->findModel(intval($quest_id));
        $user = ARUser::findOne(intval($user_id));

        return $this->render('user-statistics', [
            'quest' => $quest,
            'user' => $user,
            'dataProvider' => new ActiveDataProvider([
                'query' => QuestRun::find()->where(['quest_id' => $quest->id, 'user_id' => $user->id]),
            ])
        ]);
    }

    public function actionRunanswers() {
        if(!($run_id = Yii::$app->request->get('run_id')) || !is_numeric(Yii::$app->request->get('run_id')))
            throw new BadRequestHttpException('Неверный запрос');

        $run = QuestRun::findOne(intval($run_id));

        return $this->render('run-answers', [
            'run' => $run,
            'dataProvider' => new ActiveDataProvider([
                'query' => NodeAnswer::find()->where(['run_id' => $run->run_id]),
            ])
        ]);
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
