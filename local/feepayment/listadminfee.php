<?php
//include simplehtml_form.php
require_once '../../config.php';
global $USER, $DB, $CFG;
require_once('form/feepaymentform.php');
require_once(__DIR__ . '/../../config.php');

$id = optional_param('id', 0, PARAM_INT);
$action = optional_param('action', '', PARAM_ALPHA);

$PAGE->set_url('/local/feepayment/listadminfee.php');
$PAGE->set_context(context_system::instance());

require_login();

$PAGE->set_title('Admin Fee List');
$PAGE->set_heading('Admin Fee List');

if($action == "delete"){
    $DB->delete_records('local_feepayment', array('id' => $id));
    redirect($CFG->wwwroot.'/local/feepayment/listadminfee.php');
}else{
    $sql = "SELECT id, payment, FORMAT(feeamount, 0,'id_ID') as feeamount, feepercentage FROM {local_feepayment} ORDER BY id DESC";

    $feepaymentdb = $DB->get_records_sql($sql);
    $feepayment = [];
    foreach ($feepaymentdb as $item) {
        $feepayment[] = $item;
    }
    
    $data = [
        'feepayment' => $feepayment,
        'hasfee' => !empty($feepayment),
    ];
    
    echo $OUTPUT->header();
    echo $OUTPUT->render_from_template('local_feepayment/feepaymentlist', $data);
    echo $OUTPUT->footer();
}

