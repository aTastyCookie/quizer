<?php

namespace app\controllers;

use Yii;
use app\models\Quest;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\models\Node;
use app\models\NodeSearch;

/**
 * QuestController implements the CRUD actions for Quest model.
 */
class QuestController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Quest models.
     * @return mixed
     */
    public function actionIndex()
    {
		$query = Quest::find();
		
	    $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

	/**
     * Lists all Quest models.
     * @return mixed
     */
    public function actionVisual()
    {
		if (!Yii::$app->request->get('quest_id')) {
			return false;
		}
		$query = Node::find()->where(array('quest_id'=>Yii::$app->request->get('quest_id')));		

	    $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		
		$nodes = $dataProvider->getModels();
		$chain = Quest::getChain(Yii::$app->request->get('quest_id'));

        return $this->render('visual', [
            'nodes' => $nodes,
			'chain' => $chain,
			'quest_id' => Yii::$app->request->get('quest_id')
        ]);
    }
	
	/*
	save connection from visual admin
	*/
	public function actionSave() {
		Node::cleanConnections(Yii::$app->request->post('quest_id')); 
		
		
		$pos = Yii::$app->request->post('pos');
		if ($pos) {
			foreach ($pos as $id => $data) {
				$id = (int)str_replace('flowchartWindow', '', $id);
				$node = Node::findOne($id);
				$node->left = (int)str_replace('px', '', $data['left']);
				$node->top = (int)str_replace('px', '', $data['top']);
				$node->save();
			}
		}

		$connects = Yii::$app->request->post('connects');
		if ($connects) {
			//var_dump($connects);
			foreach ($connects as $key => $connect) {
				$srcId = (int)str_replace('flowchartWindow', '', $connect['src']);
				$trgId = (int)str_replace('flowchartWindow', '', $connect['trg']);
				
				$nodeSrc = Node::findOne($srcId);
				$nodeTrg = Node::findOne($trgId);

				if ($nodeSrc) {
					if (strpos($connect['uuid'], 'RightMiddle') !== false) {
						$nodeSrc->next = $trgId;
						//connect to next
					}else {
						//Window2TopCenter
						//connect to back
						$nodeSrc->prev2 = $trgId;
					}
					$nodeSrc->save();
				}

				if ($nodeTrg) {
					if (strpos($connect['uuid'], 'RightMiddle') !== false) {
						$nodeTrg->prev = $srcId;
						//connect to next
					}
					$nodeTrg->save();
				}

			}
		}
		return true;
	}

	public function getConnections( $quest_id )
	{
		$query = Node::find()->where(array('quest_id' => $quest_id ));		

	    $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		
		$nodes = $dataProvider->getModels();	
		$connections = array();
		if ($nodes) {
			foreach ($nodes as $node) {
				if ($node->next) {
					$connections[] = array('src' => $node->id, 'trg' => $node->next, 'type' => 'next');
				}elseif ($node->prev) {
					$connections[] = array('src' => $node->id, 'trg' => $node->prev, 'type' => 'prev');
				}
				
			}
		}
		return $connections;
	}

    /**
     * Displays a single Quest model.
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
	* process uploading of logo
	*/

	protected function _logoUpload( &$model )
	{
		$model->logoFile = UploadedFile::getInstance($model, 'logo');
		if ($model->logoFile) {
			if ($model->upload()) {
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
    public function actionCreate()
    {
        $model = new Quest();

        if ($model->load(Yii::$app->request->post())) {
			$this->_logoUpload( $model );
			$model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Quest model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) ) {
			$this->_logoUpload( $model );
            $model->save();
			return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Quest model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Quest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Quest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Quest::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
