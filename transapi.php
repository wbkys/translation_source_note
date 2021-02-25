<?php
/***************************************************************************

 * Copyright (c) 2015 Baidu.com, Inc. All Rights Reserved
 * 
**************************************************************************/

include_once "baidu_transapi.php";

header("Content-type: text/html; charset=GBK");

//读取文件
function readSourceFile($file_path){
    //$file_path = iconv("UTF-8", "GBK", $file_path);// windos上面会有读取不到中文目录的问题
    //逐行检测是否包含注释
    $file = fopen($file_path, "r") or exit("Unable to open file!");
    $temp = "";
    while(!feof($file))
    {
        //读取本行
        $line = fgets($file);
        //判断是否存在注释//符号
        preg_match("/^(.*?)\/\/(.*?)$/", $line, $matches);
        if(!empty($matches[2])){
            //发现需要翻译并且追加到后面
            $rt = translate($matches[2], "auto", "zh");
            if(!empty($rt['trans_result'][0]['dst'])){
                $temp .= str_replace("\r\n", "", $line) . ' => ' .$rt['trans_result'][0]['dst'] . "\r\n";
            }
        }else{
            $temp .= $line;
        }
    }
    fclose($file);
    //写入翻译后的
    $new_path = str_replace("go_server", "go_server_bak", $file_path);
    //创建文件夹
    
    $new_path2 = str_replace(strrchr($new_path, "\\"), "", $new_path);
    if(!file_exists($new_path2)){
        mkdir($new_path2, 777, true);
    }
    file_put_contents($new_path, $temp);
}


//readSourceFile("F:/go同步服务器代码/go-sync/command/command.go");

// 遍历某目录下的所有子目录和文件
function scanDirectory($path)
{
    global $arr;
    // windos上面会有读取不到中文目录的问题
    // $encode = mb_detect_encoding($path, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
 
    // if($encode != 'GBK'){
    //     $path = iconv($encode, "GBK", $path);
    // }
    
    //如果是目录，则进行下一步操作
    if (is_dir($path)) { 
        //打开目录
        $d = opendir($path);
        //目录打开正常
        if ($d) { 
            //循环读出目录下的文件，直到读不到为止
            while (($file = readdir($d)) !== false) { 
                //排除一个点和两个点
                if ($file != '.' && $file != '..') { 
                    $file2 = $path . DIRECTORY_SEPARATOR . $file;
                    //如果当前是目录
                    if (is_dir($file2)) { 
                        //进一步获取该目录里的文件
                        scanDirectory($file2); 
                    } else {
                        //记录文件名
                        $arr[] = $file2; 
                    }
                }
            }
        }
        closedir($d); //关闭句柄
    }
}
 
//开始翻译代码
function transSource($path){
    global $arr;
    //获取遍历的文件
    scanDirectory($path);
    //取得.go结尾的文件, 进行翻译 (其他格式自行修改比如.php)
    if(!empty($arr)){
        foreach($arr as $file){
            if(substr($file, -3) === '.go'){
                //开始翻译
                readSourceFile($file);
            }
        }
    }
}

//需要翻译的目录  由于时间原因第36行的go_server 也需要对应修改
transSource("F:/go_server/go-sync/");
echo '执行完成';
