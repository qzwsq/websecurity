<?php

class Token
{
    /**
     * @desc 获取随机数
     *
     * @return string 返回随机数字符串
     */
    public function getTokenValue()
    {
//        return md5(uniqid(rand()+$_POST["username"], true) . time());
        return md5(uniqid(rand(), true) . time());
    }

    /**
     * @desc 获取秘钥
     *
     * @param $tokenName string | 与秘钥值配对成键值对存入session中（标识符，保证唯一性）
     *
     * @return array 返回存储在session中秘钥值
     */
    public function getToken($tokenName)
    {
        $token['name'] = $tokenName;      #先将$tokenName放入数组中
        session_start();
        if (@$_SESSION[$tokenName])      #判断该用户是否存储了该session
        {                               #是，则直接返回已经存储的秘钥
            $token['value'] = $_SESSION[$tokenName];
            return $token;
        } else                            #否，则生成秘钥并保存
        {
            $token['value'] = $this->getTokenValue();
            $_SESSION[$tokenName] = $token['value'];
            return $token;
        }
    }

}