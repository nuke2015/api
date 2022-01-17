<?php
namespace nuke2015\api\org;

// 年份处理
class ueditor
{
    // 计算年龄
    public static function config()
    {
        $ueditorDir = __DIR__ . "/ueditor/";
        $config     = json_decode(self::config(), 1);
        return $config;
    }

    // 配置
    public static function config()
    {
        $json = <<<doc
{
    "imageActionName": "uploadimage",
    "imageFieldName": "upfile",
    "imageMaxSize": 2048000,
    "imageAllowFiles": [".png", ".jpg", ".jpeg", ".gif", ".bmp"],
    "imageCompressEnable": false,
    "imageCompressBorder": 1600,
    "imageInsertAlign": "none",
    "imageUrlPrefix": "",
    "imagePathFormat": "\/upload\/ueditor\/image\/{yyyy}{mm}{dd}\/{time}{rand:6}",
    "scrawlActionName": "uploadscrawl",
    "scrawlFieldName": "upfile",
    "scrawlPathFormat": "\/upload\/ueditor\/image\/{yyyy}{mm}{dd}\/{time}{rand:6}",
    "scrawlMaxSize": 2048000,
    "scrawlUrlPrefix": "",
    "scrawlInsertAlign": "none",
    "snapscreenActionName": "uploadimage",
    "snapscreenPathFormat": "\/upload\/ueditor\/image\/{yyyy}{mm}{dd}\/{time}{rand:6}",
    "snapscreenUrlPrefix": "",
    "snapscreenInsertAlign": "none",
    "catcherLocalDomain": ["127.0.0.1", "localhost", "img.baidu.com"],
    "catcherActionName": "catchimage",
    "catcherFieldName": "source",
    "catcherPathFormat": "\/upload\/ueditor\/image\/{yyyy}{mm}{dd}\/{time}{rand:6}",
    "catcherUrlPrefix": "",
    "catcherMaxSize": 2048000,
    "catcherAllowFiles": [".png", ".jpg", ".jpeg", ".gif", ".bmp"],
    "videoActionName": "uploadvideo",
    "videoFieldName": "upfile",
    "videoPathFormat": "\/upload\/ueditor\/video\/{yyyy}{mm}{dd}\/{time}{rand:6}",
    "videoUrlPrefix": "",
    "videoMaxSize": 102400000,
    "videoAllowFiles": [".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg", ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid"],
    "fileActionName": "uploadfile",
    "fileFieldName": "upfile",
    "filePathFormat": "\/upload\/ueditor\/file\/{yyyy}{mm}{dd}\/{time}{rand:6}",
    "fileUrlPrefix": "",
    "fileMaxSize": 51200000,
    "fileAllowFiles": [".png", ".jpg", ".jpeg", ".gif", ".bmp", ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg", ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid", ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso", ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"],
    "imageManagerActionName": "listimage",
    "imageManagerListPath": "\/upload\/ueditor\/image\/",
    "imageManagerListSize": 20,
    "imageManagerUrlPrefix": "",
    "imageManagerInsertAlign": "none",
    "imageManagerAllowFiles": [".png", ".jpg", ".jpeg", ".gif", ".bmp"],
    "fileManagerActionName": "listfile",
    "fileManagerListPath": "\/upload\/ueditor\/file\/",
    "fileManagerUrlPrefix": "",
    "fileManagerListSize": 20,
    "fileManagerAllowFiles": [".png", ".jpg", ".jpeg", ".gif", ".bmp", ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg", ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid", ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso", ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"]
}
doc;
        return $json;
    }
}
