<?php

namespace app\components;

class AccessRule extends \yii\filters\AccessRule {

    protected function matchRole($user) {

        if(empty($this->roles))
            return true;

        foreach($this->roles as $role) {
            if ($role == '?') {
                if ($user->getIsGuest())
                    return true;
            } elseif ($role == '@') {
                if (!$user->getIsGuest())
                    return true;
            } elseif (!$user->getIsGuest()) {
                $assign = \Yii::$app->getAuthManager()->getRolesByUser(\Yii::$app->getUser()->getId());

                if(isset($assign[$role]) && !empty($assign[$role]))
                    return true;
            }
        }

        return false;
    }
}