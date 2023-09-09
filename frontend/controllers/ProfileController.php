<?php

namespace frontend\controllers;

use common\models\User;
use frontend\base\Controller;
use Yii;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

class ProfileController extends Controller
{

    public function behaviors()
    {
       return [
           'access' => [
               'class' => AccessControl::class,
               'rules' => [
                   [
                       'actions' => ['index', 'update-address', 'update-account'],
                       'allow' => true,
                       'roles' => ['@'],
                   ]
               ],
           ]
       ];
    }


    /**
     * @return string
     */
    public function actionIndex(){
        /**
         * @var User $user
         */
        $user = Yii::$app->user->identity;
        $userAddresses = $user->addresses;
        $userAddress = $user->getAddress() ;

        return $this->render('index', [
            'user' => $user,
            'userAddress' => $userAddress
        ]);
    }

    /**
     * @throws ForbiddenHttpException
     */
    public function actionUpdateAddress()
    {
        if(!Yii::$app->request->isAjax){
            throw new ForbiddenHttpException("Voce Nao tem privilegios para fazer esse tipo de requisicoes!");
        }
        /**
         * @var User $user
         */
        $user = Yii::$app->user->identity;
        $userAddress = $user->getAddress();
        $success = false;
        if ($userAddress->load(Yii::$app->request->post()) && $userAddress->save()){
            $success = true;
        }
        return $this->renderAjax('user_address', [
            'user_address' => $userAddress,
            'success' => $success
        ]);
    }

    /**
     * @throws ForbiddenHttpException
     */
    public function actionUpdateAccount()
    {
        if(!Yii::$app->request->isAjax){
            throw new ForbiddenHttpException("Voce Nao tem privilegios para fazer esse tipo de requisicoes!");
        }
        /**
         * @var User $user
         */
        $user = Yii::$app->user->identity;
        $success = false;
        if ($user->load(Yii::$app->request->post()) && $user->save()){
            $success = true;
        }
        return $this->renderAjax('user_account', [
            'user' => $user,
            'success' => $success
        ]);
    }
}