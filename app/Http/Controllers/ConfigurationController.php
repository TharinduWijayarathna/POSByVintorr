<?php

namespace App\Http\Controllers;

use App\Http\Requests\Configuration\CreateConfigurationRequest;
use App\Http\Requests\Configuration\UpdateConfigurationRequest;
use App\Http\Resources\GetBusinessDetailsResource;
use App\Models\BusinessDetail;
use App\Models\User;
use domain\Facades\ConfigurationFacade\ConfigurationFacade;
use domain\Facades\ImageFacade\ImageFacade;
use domain\Facades\MonthlyCustomerOutstandingFacade\MonthlyCustomerOutstandingFacade;
use domain\Facades\MonthlySummaryReportFacade\MonthlySummaryReportFacade;
use domain\Facades\UserFacade\UserFacade;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ConfigurationController extends ParentController
{
    public function index()
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN']) {
            return Inertia::render('Configuration/index');
        }
    }

    public function store(CreateConfigurationRequest $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN']) {
            try {
                if ($request->has('image')) {
                    $image = ImageFacade::convertImageToWebP($request->file('image'));

                    // get image aspect ratio
                    $image_size = getimagesize($request->file('image'));
                    $aspect_ratio = $image_size[0] / $image_size[1];

                    $request->merge(['business_logo_ratio' => $aspect_ratio]);
                    $request->merge(['image_id' => $image->id]);
                }
                if ($request->has('banner_image')) {
                    $banner_image = ImageFacade::convertImageToWebP($request->file('banner_image'));
                    $request->merge(['public_image_id' => $banner_image->id]);
                }
                return ConfigurationFacade::store($request->all());
            } catch (\Exception $e) {
                return response()->json(['error' => 'something went wrong'], 500);
            }
        }
    }

    public function get()
    {
        $business = BusinessDetail::first();
        return GetBusinessDetailsResource::make($business);
    }

    public function update(UpdateConfigurationRequest $request, int $id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN']) {
            try {
                if ($request->has('image')) {
                    $image = ImageFacade::store($request->file('image'));

                    // get image aspect ratio
                    $image_size = getimagesize($request->file('image'));
                    $aspect_ratio = $image_size[0] / $image_size[1];

                    $request->merge(['business_logo_ratio' => $aspect_ratio]);
                    $request->merge(['image_id' => $image->id]);
                }
                if ($request->has('banner_image')) {
                    $banner_image = ImageFacade::convertImageToWebP($request->file('banner_image'));
                    $request->merge(['public_image_id' => $banner_image->id]);
                }
                if ($request->has('bill_logo')) {
                    $bill_logo = ImageFacade::convertImageToWebP($request->file('bill_logo'));

                    // get image aspect ratio
                    $image_size = getimagesize($request->file('bill_logo'));
                    $aspect_ratio = $image_size[0] / $image_size[1];

                    $request->merge(['bill_logo_ratio' => $aspect_ratio]);
                    $request->merge(['bill_image_id' => $bill_logo->id]);
                }
                if ($request->has('invoice_logo')) {
                    $invoice_logo = ImageFacade::convertImageToWebP($request->file('invoice_logo'));

                    // get image aspect ratio
                    $image_size = getimagesize($request->file('invoice_logo'));
                    $aspect_ratio = $image_size[0] / $image_size[1];

                    $request->merge(['invoice_logo_ratio' => $aspect_ratio]);
                    $request->merge(['invoice_image_id' => $invoice_logo->id]);
                }
                return ConfigurationFacade::update($request->all(), $id);
            } catch (\Throwable $th) {
                return response()->json(['message' => $th->getMessage()]);
            }
        }
    }

    public function delete($id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN']) {
            return ConfigurationFacade::delete($id);
        }
    }

    public function removeLogo($id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN']) {
            return ConfigurationFacade::removeLogo($id);
        }
    }

    public function removeBillLogo($id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN']) {
            return ConfigurationFacade::removeBillLogo($id);
        }
    }

    public function removeInvoiceLogo($id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN']) {
            return ConfigurationFacade::removeInvoiceLogo($id);
        }
    }

    public function removeBanner($id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN']) {
            return ConfigurationFacade::removeBanner($id);
        }
    }

    public function change(int $status)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN']) {
            return ConfigurationFacade::change($status);
        }
    }

    public function changeImportSwitch($val)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN']) {
            return ConfigurationFacade::changeImportSwitch($val);
        }
    }

    public function storeDateValue($val)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN']) {
            return ConfigurationFacade::storeDateValue($val);
        }
    }

    public function storeBusinessSummaryEmailDateValue($val)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN']) {
            return ConfigurationFacade::storeBusinessSummaryEmailDateValue($val);
        }
    }

    public function getDateValue()
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN']) {
            return ConfigurationFacade::getDateValue();
        }
    }
    public function getMonthlyBusinessEmailDateValue()
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN']) {
            return ConfigurationFacade::getMonthlyBusinessEmailDateValue();
        }
    }


    public function sendMonthlyBusinessReport()
    {
        return MonthlySummaryReportFacade::sendMonthlySummaryReport();
    }

    public function sendMonthlyCustomerOutstandingReport()
    {
        return MonthlyCustomerOutstandingFacade::sendCustomerOutstandingMail();
    }

    public function sendTokenEmail()
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN']) {
            return ConfigurationFacade::sendTokenEmail();
        }
    }

    public function resetAccount($token)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN']) {
            return ConfigurationFacade::resetAccount($token);
        }
    }
}
