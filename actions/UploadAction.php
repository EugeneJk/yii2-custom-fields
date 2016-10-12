<?php
/**
 * @license MIT 
 */

namespace eugenejk\customFields\actions;

use Yii;
use yii\base\Action;
use yii\base\DynamicModel;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Description of UploadAction
 *
 * @author Eugene Lazarchuk <shadyjk@yandex.ru>
 */
class UploadAction extends Action
{
    /**
     * @var string Path where uploaded file will be stored
     */
    public $savePath;

    /**
     * @var string Url path to directory where file will be stored
     */
    public $accessUrl;

    /**
     * @var boolean Is allow upload image only
     */
    public $isImage = false;

    /**
     * @var array Validation options for file or image validator
     */
    public $validationOptions = [];

    /**
     * @var string Incoming form fild name 
     */
    public $incomingFieldName = 'file';

    /**
     * @var boolean 
     */
    public $isUseUniqueFileNames = true;
    
    /**
     * Parameter will work if <b>isUseUniqueFileNames</b> set as <b>true</b>
     * @var boolean 
     */
    public $isUseFileNamesAsPrefix = true;
    
    /**
     * Parameter will work if <b>isUseUniqueFileNames</b> set as <b>true</b>
     * @var boolean 
     */
    public $customPrefix = '';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!$this->savePath) {
            throw new InvalidConfigException('The "savePath" attribute must be set.');
        } else {
            $this->savePath = rtrim(Yii::getAlias($this->savePath), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

            if (!file_exists($this->savePath) && !FileHelper::createDirectory($this->savePath)) {
                throw new InvalidCallException('Directory specified in "savePath" attribute doesn\'t exist or cannot be created.');
            }
        }

        if (!$this->accessUrl) {
            throw new InvalidConfigException('The "accessUrl" attribute must be set.');
        } else {
            $this->accessUrl = $this->accessUrl = rtrim($this->accessUrl, '/') . '/';
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (Yii::$app->request->isPost) {
            $file = UploadedFile::getInstanceByName($this->incomingFieldName);
            $model = DynamicModel::validateData(compact('file'));
            $model->addRule('file', $this->isImage ? 'image' : 'file', $this->validationOptions);
            if ($model->hasErrors()) {
                return $this->getResult(false, implode('; ', $model->getErrors('file')));
            } else {
                if ($this->isUseUniqueFileNames) {
                    $filename = uniqid($this->isUseFileNamesAsPrefix ? $file->baseName : $this->customPrefix) . '.' . $file->extension;
                } else {
                    $filename = $file->baseName . '.' . $file->extension;
                }
                if (@$file->saveAs($this->savePath . $filename)) {
                    return $this->getResult(true, '', [
                        'access_link' => $this->accessUrl . $filename,
                    ]);
                }
                return $this->getResult(false, 'File can\'t be placed to destination folder');
            }
        } else {
            throw new BadRequestHttpException('Only Post request allowed for this action!');
        }

        $uploads_dir = '/var/tmp';
        if ($_FILES["file"]["error"] == UPLOAD_ERR_OK) {
            $tmp_name = $_FILES["file"]["tmp_name"];
            $name = $_FILES["file"]["name"];
            move_uploaded_file($tmp_name, "$uploads_dir/$name");
        }
        return [
        'success' => true,
        'value' => '/uploads/some.file.txt',
        ];
    }

    /**
     * 
     * @param boolean $isSuccess
     * @param string $message Error message
     * @return array
     */
    private function getResult($isSuccess, $message = '', $additionalParams = [])
    {
        $result = ['success' => $isSuccess];
        if (!$isSuccess) {
            $result['message'] = $message;
        }
        return array_merge($result, $additionalParams);
    }

}
