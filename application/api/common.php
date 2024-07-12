<?php


/**
 * 错误返回结果
 * @param $msg
 * @param int $code
 * @return string
 */
function returnErrors( $msg = 'fail', $data = '',$code = 1)
{
    header('Content-Type:application/json;charset=utf-8');
    $array = array(
        'code' => $code,
        'msg' => $msg,
        'data' => $data,
    );
    exit(json_encode($array, JSON_UNESCAPED_UNICODE));
}

/**
 * 成功返回结果
 * @param string $msg
 * @param array $data
 * @param int $code
 * @return string
 */
function returnSuccess( $msg = 'success', $data = '',$code = 0)
{
    header('Content-Type:application/json;charset=utf-8');
    $array = array(
        'code' => $code,
        'msg' => $msg,
        'data' => $data
    );

    exit(json_encode($array, JSON_UNESCAPED_UNICODE));
}

//获取随机字符串
function rand_str( $length = 5 ) {
    $chars = '0123456789abcdefghijklmnopqrstuvwxyz';
    $str ='';
    for ( $i = 0; $i < $length; $i++ )
    {
        $str .= $chars[ mt_rand(0, strlen($chars) - 1) ];
    }
    return $str;
}

// 获取当前请求完整的url
function http_url()
{
    if (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
        return 'https'. '://' . $_SERVER['HTTP_HOST'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
        return 'https'. '://' . $_SERVER['HTTP_HOST'];
    } elseif (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
        return 'https' . '://' . $_SERVER['HTTP_HOST'];
    }
    return 'http' . '://' . $_SERVER['HTTP_HOST'];
}

function object_array($array)
{
    if (is_object($array)) {
        $array = (array)$array;
    }
    if (is_array($array)) {
        foreach ($array as $key => $value) {
            $array[$key] = object_array($value);
        }
    }
    return $array;
}


function delDir($directory){//自定义函数递归的函数整个目录
    if(file_exists($directory)){//判断目录是否存在，如果不存在rmdir()函数会出错
        if($dir_handle=@opendir($directory)){//打开目录返回目录资源，并判断是否成功
            while($filename=readdir($dir_handle)){//遍历目录，读出目录中的文件或文件夹
                if($filename!='.' && $filename!='..'){//一定要排除两个特殊的目录
                    $subFile=$directory."/".$filename;//将目录下的文件与当前目录相连
                    if(is_dir($subFile)){//如果是目录条件则成了
                        delDir($subFile);//递归调用自己删除子目录
                    }
                    if(is_file($subFile)){//如果是文件条件则成立
                        unlink($subFile);//直接删除这个文件
                    }
                }
            }
            closedir($dir_handle);//关闭目录资源
            rmdir($directory);//删除空目录
        }
    }
}
