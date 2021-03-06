<?php
require_once 'controllerBase.php';

class Upload extends controllerBase {
    protected $_fields = array('type');

    public function __construct() {
        $this->run();
    }

    public function FileLimitSize() {
        return 20 * 1024 * 1024;
    }


    public function run() {
        $params = $this->getParams();
        $upload_file = $_FILES['file'];
        $file = $upload_file['tmp_name'];
        $filename = md5($file);
        if (!isset($upload_file) || $upload_file['error'] != 0) {
            aj_output(ErrorMsg::ERRUPLOAD);
        }
        if ($upload_file['size'] > $this->FileLimitSize()) {
            aj_output(ErrorMsg::TOOBIG);
        }

        if (file_exists(URI_HOST . "/template/images/upload/" . $filename)) {
            $res = array(
                'url' => 'http://101.200.59.83/template/images/upload/' . $filename,
            );
            aj_output(ErrorMsg::SUCCESS, '', $res);
        } else {
            $res = move_uploaded_file($file, URI_HOST . "/template/images/upload/" . $filename);
            if (false === $res) {
                aj_output(ErrorMsg::ERRUPLOAD);
            }
            $res = array(
                'url' => 'http://101.200.59.83/template/images/upload/' . $filename,
            );
            aj_output(ErrorMsg::SUCCESS, '' , $res);
        }
    }
}

new Upload();
