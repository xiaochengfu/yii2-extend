<?php
namespace common\widgets;

use Yii;
use yii\captcha\CaptchaAction;

/**
 * Description of captcha_api
 *
 * @author houpeng <xcf-hp@foxmail.com>
 * @date 2017-06-21
 */
class Captcha extends CaptchaAction{

    private $verifycode;
    private $base64;

    public function __construct(){
        $this->init();
        $this->minLength = 4;
        $this->maxLength = 5;
        $this->foreColor = '1c77a2';
        $this->width = 80;
        $this->height = 45;
    }

    /**
     * @return string
     * 生成验证码
     */
    public function getPhrase(){
        if($this->verifycode){
            return $this->verifycode;
        }else{
            return $this->verifycode = $this->generateVerifyCode();
        }
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     * 获取用于api的验证码
     */
    public function getApiVerifyCode(){
        $verifyCode = $this->getPhrase();
        Yii::$app->cache->set("__captch__code:".Yii::$app->request->userIP,$verifyCode,60);
        return $this->base64 = "data:image/png;base64,".base64_encode($this->renderImage($verifyCode));
    }

    /**
     * @param $input
     * @param $caseSensitive
     * @return bool
     * 验证输入的验证码是否正确
     */
    public function validateVerifyCode($input,$caseSensitive = false){
        $code = Yii::$app->cache->get("__captch__code:".Yii::$app->request->userIP);
        $valid = $caseSensitive ? ($input === $code) : strcasecmp($input, $code) === 0;
        if($valid){
            Yii::$app->cache->delete("__captch__code:".Yii::$app->request->userIP);
            return true;
        }else{
            return false;
        }
    }




}
