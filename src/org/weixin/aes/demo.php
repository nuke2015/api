<?php
namespace nuke2015\api\org\weixin\aes;

$appid      = '';
$sessionKey = '';

$encryptedData = "";

$iv = '';

$pc      = new WXBizDataCrypt($appid, $sessionKey);
$errCode = $pc->decryptData($encryptedData, $iv, $data);

if ($errCode == 0) {
    print($data . "\n");
} else {
    print($errCode . "\n");
}
