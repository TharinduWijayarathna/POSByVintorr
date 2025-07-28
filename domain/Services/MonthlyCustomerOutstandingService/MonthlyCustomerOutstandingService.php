<?php

namespace domain\Services\MonthlyCustomerOutstandingService;

use App\Jobs\SendCustomerOutstandingMailJob\SendCustomerOutstandingMailJob;
use App\Models\BusinessDetail;
use App\Models\Customer;
use App\Models\CustomerOutstandingEmail;
use App\Models\PosOrder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use PDO;

/**
 * MonthlyCustomerOutstandingService
 * php version 8
 *
 * @category Service
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 * @link     https://emergentspark.com
 * */
class MonthlyCustomerOutstandingService
{
    protected $business_detail;
    protected $pos_order;
    protected $customers;
    protected $customer_outstanding_emails;



    public function __construct()
    {
        $this->business_detail = new BusinessDetail();
        $this->pos_order = new PosOrder();
        $this->customers = new Customer();
        $this->customer_outstanding_emails = new CustomerOutstandingEmail();
    }

    public function sendCustomerOutstandingMail()
    {
        try {
            Log::info('Outstanding email start');
            $today = Carbon::now()->format('Y-m-d');
            $email_date = $this->customer_outstanding_emails->first();
            $today_date = Carbon::now()->format('d');
            $custom_date = $email_date->value;

            if (isset($custom_date)) {
                if ($custom_date != 0 && $today_date == $custom_date) {

                    $customers = $this->customers->get();
                    $customerIds = [];
                    foreach ($customers as $customer) {
                        $credit_invoices = $this->pos_order
                            ->where('credit_status', 0)
                            ->where('total', '>', 0)
                            ->where('status', '<', 4)
                            ->where('customer_id', $customer->id)
                            ->whereNotIn('status', [3])
                            ->get();
                        if ($credit_invoices->isNotEmpty()) {
                            $customerIds[] = $customer->id;
                        }
                    }

                    foreach ($customerIds as $id) {

                        $credit_bills = $this->pos_order
                        ->where('status', 1)
                        ->where('credit_status', 0)
                        ->where('is_return', 0)
                        ->where('type', 0)
                        ->where('customer_id', $id)
                        ->get();

                        $credit_invoices = $this->pos_order
                            ->where('credit_status', 0)
                            ->where('total', '>', 0)
                            ->where('status', '<', 4)
                            ->where('type', 1)
                            ->where('customer_id', $id)
                            ->whereNotIn('status', [3])
                            ->where(function ($query) use ($today) {
                                $query->where('due_date', '<', $today)
                                    ->orWhere('date', '<', $today);
                            })
                            ->get();


                        $customer = $this->customers->find($id);

                        if (isset($customer->email3)) {
                            $default_mail = $customer->email3;
                        } elseif (isset($customer->email2)) {
                            $default_mail = $customer->email2;
                        } elseif (isset($customer->email)) {
                            $default_mail = $customer->email;
                        }else{
                            $default_mail = null;
                        }

                        Log::info('Outstanding email body');
                        $business_details = $this->business_detail->first();

                        $image = $business_details->image_url;

                        $sendData['credit_bills'] = $credit_bills;
                        $sendData['sender_email'] = $business_details->email;
                        $sendData['business_name'] = $business_details->name;
                        $sendData['invoices'] = $credit_invoices;
                        $sendData['email'] = $default_mail;
                        $sendData['cc'] = $business_details->email;
                        $sendData['subject'] = "Outstanding Report Up To " . Carbon::now()->format('Y-m-d');
                        $contact_person = " " . $customer['name'];
                        $sendData['message'] = "<p>Hi" . $contact_person .
                            ",</p><p>I hope you’re doing well! Please see the outstanding payment report below.</p><p>
                                Don’t hesitate to reach out if you have any questions.</p><p>Kind regards!</p>";


                        SendCustomerOutstandingMailJob::dispatch($sendData, $sendData['email'], $image);
                        Log::info('Outstanding email send successfully');
                    }
                }
            }
        } catch (\Throwable $th) {
            Log::info('email not send' . $th);
        }
    }
}
