# translation_source_note
对源代码中的注释部分进行翻译,并且生成一份新的源代码以供参考.采用的是百度翻译.

# baidu_transapi.php
需要在百度翻译接口申请API KEY,填写进去
申请地址 http://api.fanyi.baidu.com

# transapi.php
这个文件中的目标文件夹需要做修改,修改为你需要翻译的源代码.

ps:文件中仅对.go文件做处理,并且只翻译 // 注释后面的, 可以自行按自己需求修改

# 运行起来
由于每次调用接口,会阻塞接口,导致运行时间比较长,有时间的朋友可以收集数据为数组后,再提交接口翻译

所以我是用命令行运行的,这样就不会超时了
php E:\www\transapi.php
