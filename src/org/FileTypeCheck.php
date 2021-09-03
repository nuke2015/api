<?php
namespace nuke2015\api\org;

// 文件头识别
class FileTypeCheck
{
    public static function getFileType($filename)
    {
        $filetype = "other";
        if (!file_exists($filename)) {
            throw new Exception("no found file!");
        }

        $file = @fopen($filename, "rb");
        if (!$file) {
            throw new Exception("file refuse!");
        }

        $bin = fread($file, 15); //只读15字节 各个不同文件类型，头信息不一样。
        fclose($file);
        return self::bin2type($bin);
    }

    // 直接识别
    public static function bin2type($bin)
    {
        $typelist = self::getTypeList();
        foreach ($typelist as $v) {
            $blen   = strlen(pack("H*", $v[0])); //得到文件头标记字节数
            $tbin   = substr($bin, 0, intval($blen)); ///需要比较文件头长度
            $header = unpack("H*", $tbin);
            if (strtolower($v[0]) == strtolower(array_shift($header))) {
                return $v[1];
            }
        }
        return $filetype;
    }

    /**
     *得到文件头与文件类型映射表*
     *
     * @return array array(array('key',value)...)
     */
    public static function getTypeList()
    {
        return [
            array("FFD8FFE1", "jpg"),
            array("89504E47", "png"),
            array("47494638", "gif"),
            array("49492A00", "tif"),
            array("424D", "bmp"),
            array("41433130", "dwg"),
            array("38425053", "psd"),
            array("7B5C727466", "rtf"),
            array("3C3F786D6C", "xml"),
            array("0D0A312E", "txt"),
            array("504B0304", "xlsx/docx"),
            array("68746D6C3E", "html"),
            array("44656C69766572792D646174", "eml"),
            array("CFAD12FEC5FD746F", "dbx"),
            array("2142444E", "pst"),
            array("D0CF11E0", "xls/doc"),
            array("5374616E64617264204A", "mdb"),
            array("FF575043", "wpd"),
            array("252150532D41646F6265", "eps/ps"),
            array("255044462D312E", "pdf"),
            array("E3828596", "pwl"),
            array("504B0304", "zip"),
            array("52617221", "rar"),
            array("57415645", "wav"),
            array("41564920", "avi"),
            array("2E7261FD", "ram"),
            array("2E524D46", "rm"),
            array("000001BA", "mpg"),
            array("000001B3", "mpg"),
            array("6D6F6F76", "mov"),
            array("3026B2758E66CF11", "asf"),
            array("4D546864", "mid"),
        ];
    }
}
