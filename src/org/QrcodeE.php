<?php

namespace nuke2015\api\org;

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;

// logo二维码
class QrcodeE
{
    // 在线显示
    public static function show($turl, $header = 1, $logo = ROOT_PATH . '/ijiazhen/com/config/image/logo.png')
    {
        $qrCode = new QrCode($turl);
        $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::MEDIUM);

        if (file_exists($logo)) {
            $qrCode->setLogoPath($logo)->setLogoWidth(50);
        }

        $qrCode->setSize(200);
        if ($header) {
            header('Content-Type: ' . $qrCode->getContentType());
        }
        return $qrCode->writeString();
    }

}
