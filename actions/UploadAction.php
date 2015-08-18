<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace eugenejk\customFields\actions;

use Yii;
use yii\base\Action;
/**
 * Description of UploadAction
 *
 * @author nataly
 */
class UploadAction extends Action
{
    public function init()
    {
        parent::init();
        Yii::$app->controller->enableCsrfValidation = false;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }
    /**
     * @inheritdoc
     */
    public function run()
    {
        $uploads_dir = '/var/tmp';
        if ($_FILES["file"]["error"] == UPLOAD_ERR_OK) {
            $tmp_name = $_FILES["file"]["tmp_name"];
            $name = $_FILES["file"]["name"];
            move_uploaded_file($tmp_name, "$uploads_dir/$name");
        }
        return ['status' => 'ok'];
    }
}