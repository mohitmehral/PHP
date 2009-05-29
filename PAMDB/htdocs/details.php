<?php
require_once 'support.php';

require_once 'DB.php';
require_once 'Helper.php';
require_once 'Model.php';
require_once 'View.php';
require_once 'Controller.php';

try {
    DB::vInit();
    $ixPam = Controller::ixPamFromRequest();
    $mpPam = Model::mpGetPamDetailsById($ixPam);

    if (!empty($mpPam['name_pam'])) {
        standard_html_header($mpPam['name_pam']);
    } else {
        standard_html_header("Detailed Results");
    }

    View::vRenderDetailView($mpPam);
} catch (Exception $e) {
    Helper::vSendCrashReport($e);
    View::vRenderErrorMsg($e);
}

standard_html_footer();
?>
