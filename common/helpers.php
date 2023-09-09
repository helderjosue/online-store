<?php
/**
 * @author Helder Josue <helderjosuemata@gmail.com>
 * Date 9/9/23
 */

/**
 * Global method to see if the user is guest or authorized
 * @return bool
 */
function isGuest(): bool
{
    return Yii::$app->user->isGuest;
}

function currentUserId(){
    return Yii::$app->user->id;
}