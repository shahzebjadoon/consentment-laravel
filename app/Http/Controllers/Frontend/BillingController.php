<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Http\Controllers\Controller;

class BillingController extends Controller
{


    public function getbill( $company_id)
    {

        $company = Company::findOrFail($company_id);

        return view('frontend.company.billings', [
            'company' => $company,
            "activeTab" => 'billing',
        ]);
    }

    //
}
