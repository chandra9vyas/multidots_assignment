<?php

namespace backend\controllers;

use Yii;
use yii\rbac\DbManager;
use yii\db\Query;
use yii\helpers\Json;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\models\AuthItem;
use common\models\AuthItemChild;
use common\models\PositionRole;


/**
 * AuthController implements the CRUD actions for AuthItem model.
 */
class AuthController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    /* get roles */
    protected function authRoles()
    {
        $query = AuthItem::find()->where(['type' => 1])->all();
       // prd($query);
        return  $query;
    }

    /**
     * Lists all AuthItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        //prd(Yii::$app->authManager);
        $query = AuthItem::find()->where(['type' => 2]);
     
        $authItmes = new ActiveDataProvider([
            'query' => $query,            
        ]);
        //prd($dataProvider);

        return $this->render('index', [
            'authItmes' => $authItmes,
        ]);
    }

    /**
     * Displays a single AuthItem model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AuthItem();
        $modelAuthChild = new AuthItemChild();

        $auth = new DbManager();
        $auth->itemTable = $model->tableName();
        $auth->itemChildTable = $modelAuthChild->tableName();

        if (Yii::$app->request->isPost) {
            //prd(Yii::$app->request->post());
            $model->attributes = Yii::$app->request->post('AuthItem');
            //prd($model->attributes);
            /* add item in auth */
            $item = $auth->createPermission($model->name);    
                
            $item->description = $model->description;
                 
            $savedItem = $auth->add($item); 

            $savedItemChild = '';
            //prd($savedItem);
            foreach(Yii::$app->request->post('AuthItemChild')['parent'] as $key => $val){               
                $modelAuthChild->parent = $val;
                $itemChild = $auth->getRole($modelAuthChild->parent);
                $savedItemChild = $auth->addChild($itemChild, $item);
            }

            if($savedItem && $savedItemChild){

                Yii::$app->getSession()->setFlash('success', 'Authitem / Action added successfully.');
                return $this->redirect(['auth/index']);
                
            }else{
                Yii::$app->getSession()->setFlash('error', 'Failed to add Rule');
            }
           
            //prd($_POST);
        }
         
        $data['model'] = $model;
        $data['modelAuthChild'] = $modelAuthChild;
        $data['authRoles'] = self::authRoles();
        return $this->render('create', ['data' => $data]);
    }

    /**
     * Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {

        $model = new AuthItem();
        $modelAuthChild = new AuthItemChild();

        $auth = new DbManager();
        $auth->itemTable = $model->tableName();
        $auth->itemChildTable = $modelAuthChild->tableName();

        if (Yii::$app->request->isPost) {

            $model->attributes = Yii::$app->request->post('AuthItem');
            //prd(Yii::$app->request->post());
            /* add item in auth */
            $item = $auth->createPermission($model->name);
            $item->description = $model->description;
            $savedItem = $auth->add($item); 

            $savedItemChild = '';
            //prd($savedItem);
            foreach(Yii::$app->request->post('AuthItemChild')['parent'] as $key => $val){               
                $modelAuthChild->parent = $val;
                $itemChild = $auth->getRole($modelAuthChild->parent);
                $savedItemChild = $auth->addChild($itemChild, $item);
            }

            if($savedItem && $savedItemChild){

                Yii::$app->getSession()->setFlash('success', 'Authitem / Action added successfully.');
                return $this->redirect(['auth/index']);
                
            }else{
                Yii::$app->getSession()->setFlash('error', 'Failed to add Rule');
            }
           
            //prd($_POST);
        }
         
        $data['model'] = $model;
        $data['modelAuthChild'] = $modelAuthChild;
        $data['authRoles'] = self::authRoles();
        return $this->render('update', ['data' => $data]);  
    }

    /**
     * Deletes an existing AuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return AuthItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AuthItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
