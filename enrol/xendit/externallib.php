<?php

// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * External Web Service Template
 *
 * @package    localwstemplate
 * @copyright  2011 Moodle Pty Ltd (http://moodle.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
use Xendit\Xendit;
require_once($CFG->dirroot . '/local/xendit_sdk/vendor/autoload.php');
require_once($CFG->libdir . "/externallib.php");

class enrol_xendit_external extends external_api {

    public static function create_recurring_parameters() {
        return new external_function_parameters(
                array(
                    'instance' => new external_value(PARAM_INT, 'Enrol ID to be purchased', VALUE_REQUIRED),
                    'method' => new external_value(PARAM_TEXT, 'Payment method used', VALUE_REQUIRED),
                    'couponcode' => new external_value(PARAM_TEXT, 'Enrol ID to be purchased', VALUE_REQUIRED),
                )
        );
    }

    public static function create_recurring($instance, $method, $couponcode) {
        global $USER, $DB, $CFG;

        //Parameter validation
        //REQUIRED
        $params = self::validate_parameters(self::create_recurring_parameters(),
            array(
                'instance' => $instance,
                'method' => $method,
                'couponcode' => $couponcode,
            )
        );
        
        $payment_methods = [$method];

        //Context validation
        //OPTIONAL but in most web service it should present
        $context = get_context_instance(CONTEXT_USER, $USER->id);
        self::validate_context($context);

        $instance = $DB->get_record('enrol', ['id' => $instance]);

        $course = $DB->get_record('course', array('id' => $instance->courseid));
        $context = context_course::instance($course->id);

	// Key / Token Xendit Lama
        //Xendit::setApiKey('xnd_development_5Dfeoqqhndoh1SezzQJp3QxberGVWjPMuHr7fl7VCyJvcb9Asqbg9fyPYTXZu');

	// Key / Token Xendit Baru = Update 08 February 2023 by PCMan Team
	Xendit::setApiKey('xnd_production_HZntArBsiGhAYYNnHXsZHZLnzSop7iKacYDkDruRvrhpUknXeorVAub1IaUK13');

        if ( (float) $instance->cost <= 0 ) {
            $cost = (float) get_config('enrol_xendit', 'cost');
        } else {
            $cost = (float) $instance->cost;
        }

        if ( (float) $instance->customint1 <= 0 ) {
            $beforeDiscount = (float) get_config('enrol_xendit', 'customint1');
        } else {
            $beforeDiscount = (float) $instance->customint1;
        }

        $sqlCoupon = "SELECT id, amount, percentage, couponlimit FROM {local_coupon} WHERE couponcode = '$couponcode' AND category = '$course->category' ORDER BY id DESC LIMIT 1";
        $coupon = $DB->get_records_sql($sqlCoupon);

        if(count($coupon) > 0){
            $amount = 0;
            $percentage = 0;
            foreach($coupon as $item){
                $amount = (float) $item->amount;
                $percentage = (float) $item->percentage;

                $item->couponlimit = (int) $item->couponlimit - 1;
                $DB->update_record('local_coupon', $item);
            }

            $percentage = $cost * ($percentage / 100);
            $cost = $cost - ($percentage + $amount);
        }

        $sqlFeePayment = "SELECT id, feeamount, feepercentage FROM {local_feepayment} WHERE payment = '$method' ORDER BY id DESC LIMIT 1";
        $feePayment = $DB->get_records_sql($sqlFeePayment);
        if(count($feePayment) > 0){
            $feePercent = 0;
            $feeAmount = 0;
            foreach($feePayment as $item){
                $feePercent = (float) $item->feepercentage;
                $feeAmount = (float) $item->feeamount;
            }

            $feePercent = (float) $cost * ($feePercent / 100);
            $cost = (float) $cost + ($feePercent + $feeAmount);
        }
        
        $fullprice = "Rp ".number_format($beforeDiscount, 0, ',', '.');
        $discountedprice = "Rp ".number_format($cost, 0, ',', '.');
        $discount = "Rp ".number_format($beforeDiscount-$cost, 0, ',', '.');

        $DB->delete_records('payments', array('userid'=>$USER->id, 'itemid'=>$course->id));

        // $inv = $DB->get_record_sql('SELECT * FROM {payments} 
        //     WHERE userid = ? AND itemid = ? 
        //     AND paymentarea NOT LIKE "I-%" 
        //     ORDER BY accountid DESC LIMIT 1', [$USER->id, $course->id]);


        // if($inv !== false){
        //     $invoice = \Xendit\Recurring::retrieve($inv->paymentarea);

        //     if($invoice['status'] == 'PENDING'){
        //         // invoice still valid, returning active invoice
        //         $invoice_url = $invoice['last_created_invoice_url'];
        //         return $invoice_url;
        //     }
        // }

        // either there's no invoice or it's expired, create new invoice
        $accountid = $DB->get_record_sql('SELECT MAX(id) as accountid FROM {payments} WHERE component = "enrol_xendit"');

        if(is_null($accountid->accountid) || $accountid->accountid == 0){
            $accountid = 1;
        }else{
            $accountid = $accountid->accountid + 1;
        }

        $invoice_id = "CBA/".date("m/Y")."/".$course->id."/".$USER->id."/".sprintf("%010d", $accountid);

        $params = [
            'external_id' => $invoice_id,
            'payer_email' => $USER->email,
            'description' => get_string('purchasedescription', 'enrol_xendit',
            format_string($course->fullname, true, ['context' => $context])),
            'amount' => $cost,
            'interval' => 'MONTH',
            'interval_count' => 1
        ];
        
        $createRecurring = \Xendit\Recurring::create($params);
        $invoice_url = $createRecurring['last_created_invoice_url'];
        \core_payment\helper::save_payment($accountid, 'enrol_xendit', $createRecurring['id'], $course->id, $USER->id, $cost, $createRecurring['currency'], 'xendit');
        return $invoice_url;
    }

    public static function create_recurring_returns() {
        return new external_value(PARAM_RAW, 'The recurring url');
    }
    
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function create_invoice_parameters() {
        return new external_function_parameters(
                array(
                    'instance' => new external_value(PARAM_INT, 'Enrol ID to be purchased', VALUE_REQUIRED),
                    'method' => new external_value(PARAM_TEXT, 'Payment method used', VALUE_REQUIRED),
                    'couponcode' => new external_value(PARAM_TEXT, 'Enrol ID to be purchased', VALUE_REQUIRED),
                )
        );
    }

    /**
     * Returns welcome message
     * @return string welcome message
     */
    public static function create_invoice($instance, $method, $couponcode) {
        global $USER, $DB, $CFG;

        //Parameter validation
        //REQUIRED
        $params = self::validate_parameters(self::create_invoice_parameters(),
            array(
                'instance' => $instance,
                'method' => $method,
                'couponcode' => $couponcode,
            )
        );
        
        $payment_methods = [$method];
        //Context validation
        //OPTIONAL but in most web service it should present
        $context = get_context_instance(CONTEXT_USER, $USER->id);
        self::validate_context($context);

        $instance = $DB->get_record('enrol', ['id' => $instance]);

        $course = $DB->get_record('course', array('id' => $instance->courseid));
        $context = context_course::instance($course->id);

       // Key / Token Xendit Lama
       //Xendit::setApiKey('xnd_development_5Dfeoqqhndoh1SezzQJp3QxberGVWjPMuHr7fl7VCyJvcb9Asqbg9fyPYTXZu');

       // Key / Token Xendit Baru = Input 08 Februari 2023 by PCMan Team
	Xendit::setApiKey('xnd_production_HZntArBsiGhAYYNnHXsZHZLnzSop7iKacYDkDruRvrhpUknXeorVAub1IaUK13');

        if ( (float) $instance->cost <= 0 ) {
            $cost = (float) get_config('enrol_xendit', 'cost');
        } else {
            $cost = (float) $instance->cost;
        }

        if ( (float) $instance->customint1 <= 0 ) {
            $beforeDiscount = (float) get_config('enrol_xendit', 'customint1');
        } else {
            $beforeDiscount = (float) $instance->customint1;
        }

        $sqlCoupon = "SELECT id, amount, percentage, couponlimit FROM {local_coupon} WHERE couponcode = '$couponcode' AND category = '$course->category' ORDER BY id DESC LIMIT 1";
        $coupon = $DB->get_records_sql($sqlCoupon);

        if(count($coupon) > 0){
            $amount = 0;
            $percentage = 0;
            foreach($coupon as $item){
                $amount = (float) $item->amount;
                $percentage = (float) $item->percentage;

                $item->couponlimit = (int) $item->couponlimit - 1;
                $DB->update_record('local_coupon', $item);
            }

            $percentage = $cost * ($percentage / 100);
            $cost = $cost - ($percentage + $amount);
        }

        $sqlFeePayment = "SELECT id, feeamount, feepercentage FROM {local_feepayment} WHERE payment = '$method' ORDER BY id DESC LIMIT 1";
        $feePayment = $DB->get_records_sql($sqlFeePayment);
        if(count($feePayment) > 0){
            $feePercent = 0;
            $feeAmount = 0;
            foreach($feePayment as $item){
                $feePercent = (float) $item->feepercentage;
                $feeAmount = (float) $item->feeamount;
            }

            $feePercent = (float) $cost * ($feePercent / 100);
            $cost = (float) $cost + ($feePercent + $feeAmount);
        }
        
        $fullprice = "Rp ".number_format($beforeDiscount, 0, ',', '.');
        $discountedprice = "Rp ".number_format($cost, 0, ',', '.');
        $discount = "Rp ".number_format($beforeDiscount-$cost, 0, ',', '.');

        $DB->delete_records('payments', array('userid'=>$USER->id, 'itemid'=>$course->id));

        // $inv = $DB->get_record_sql('SELECT * FROM {payments} 
        //     WHERE userid = ? AND itemid = ? 
        //     AND paymentarea NOT LIKE "I-%" 
        //     ORDER BY accountid DESC LIMIT 1', [$USER->id, $course->id]);

        // if($inv !== false){
        //     $invoice = \Xendit\Invoice::retrieve($inv->paymentarea);
        //     if($invoice['status'] == 'PENDING'){
        //         // invoice still valid, returning active invoice
        //         $invoice_url = $invoice['invoice_url'];
        //         return $invoice_url;
        //     }
        // }

        // either there's no invoice or it's expired, create new invoice
        $accountid = $DB->get_record_sql('SELECT MAX(id) as accountid FROM {payments} WHERE component = "enrol_xendit"');

        if(is_null($accountid->accountid) || $accountid->accountid == 0){
            $accountid = 1;
        }else{
            $accountid = $accountid->accountid + 1;
        }

        $invoice_id = "CBA/".date("m/Y")."/".$course->id."/".$USER->id."/".sprintf("%010d", $accountid);
        $params = [
            'external_id' => $invoice_id,
            'payer_email' => $USER->email,
            'description' => get_string('purchasedescription', 'enrol_xendit',
            format_string($course->fullname, true, ['context' => $context])),
            'amount' => $cost,
            'should_send_email' => true,
            'invoice_duration' => 7200,
            'success_redirect_url' => $CFG->wwwroot.'/course/view.php?id='.$course->id,
            'failure_redirect_url' => $CFG->wwwroot.'/my',
            'payment_methods' => $payment_methods,
        ];

        $createInvoice = \Xendit\Invoice::create($params);
        $invoice_url = $createInvoice['invoice_url'];
        \core_payment\helper::save_payment($accountid, 'enrol_xendit', $createInvoice['id'], $course->id, $USER->id, $cost, $createInvoice['currency'], 'xendit');
        return $invoice_url;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function create_invoice_returns() {
        return new external_value(PARAM_RAW, 'The invoice url');
    }

}
