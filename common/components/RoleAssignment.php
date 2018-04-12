<?php
namespace common\components;
 
use Yii;
 
class RoleAssignment extends \yii\rbac\DbManager
{
	public function init()
	{
		parent::init();
	}
	 
	public function getAssignments($userId)
	{
		if(!\Yii::$app->user->isGuest){
			$assignment = new \yii\rbac\Assignment;
			$assignment->userId = $userId;
			$assignment->roleName = Yii::$app->user->identity->u_role;
			return [$assignment->roleName => $assignment];
		}
	}
}