<?php
/**
 * @license MIT 
 */

namespace eugenejk\customFields\actions;
use Yii;
use yii\base\Action;
use yii\web\Response;
use yii\imagine\Image;

/**
 * Description of ImageCropAction
 *
 * @author Eugene Lazarchuk <shadyjk@yandex.ru>
 */
class ImageCropAction extends Action
{
    /**
     * @var string Path where uploaded file will be stored
     */
    public $uploadRootPath;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!$this->uploadRootPath) {
            throw new InvalidConfigException('The "savePath" attribute must be set.');
        } else {
            $this->uploadRootPath = rtrim(Yii::getAlias($this->uploadRootPath), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

            if (!file_exists($this->uploadRootPath) && !FileHelper::createDirectory($this->uploadRootPath)) {
                throw new InvalidCallException('Directory specified in "savePath" attribute doesn\'t exist or cannot be created.');
            }
        }
    }
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if (Yii::$app->request->isPost) {
            $params = Yii::$app->request->post();
            $srcImage = $this->getImagePath(urldecode($params['image']['src']));
            $image = Image::getImagine()->open(Yii::getAlias($this->uploadRootPath . $srcImage));
            $size = $image->getSize()->heighten($params['image']['height']);
            $image->resize($size);
            $image->crop(
                new \Imagine\Image\Point(
                    $params['crop']['x'],
                    $params['crop']['y']
                ),
                new \Imagine\Image\Box(
                    $params['crop']['width'],
                    $params['crop']['height']
                ));
            $destImage = $this->getNewName($srcImage);
            $image->save(Yii::getAlias($this->uploadRootPath . $destImage));
            return [
                'file' => $destImage,
            ];
        }
        return [
            'error' => 'It should be POST request.',
        ];
    }
    
    private function getImagePath($url){
        $src = parse_url($url);
        return $src['path'];
    }
    
    private function getNewName($oldName){
        $parsed = explode('.', $oldName);
        $parsed[count($parsed) - 2] .= uniqid('-thumb-');
        return implode('.', $parsed);
    }
}
