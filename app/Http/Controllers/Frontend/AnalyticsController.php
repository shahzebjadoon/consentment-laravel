<?php

namespace App\Http\Controllers\Frontend;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Company;

use App\Models\Configuration;

use App\Models\Analytics;

use Carbon\Carbon;

use Illuminate\Support\Facades\DB;



class AnalyticsController extends Controller

{

    public function index($companyId, $configId)

    {

        $company = Company::findOrFail($companyId);

        $configuration = Configuration::findOrFail($configId);

        $activeTab = 'overview';



        // Default to last 7 days if no date range specified

        $startDate = Carbon::now()->subDays(7)->startOfDay();

        $endDate = Carbon::now()->endOfDay();



        // Get analytics data for the date range

        $analyticsData = $this->getAnalyticsData($companyId, $configId, $startDate, $endDate);
        // $analyticsData = $analyticsData + ["company id " => $companyId, "config is" => $configId, $startDate, $endDate];

        

        // Get daily analytics for charts

        $dailyAnalytics = $this->getDailyAnalytics($companyId, $configId, $startDate, $endDate);

        

        // Get available filter options

        $filterOptions = $this->getFilterOptions($companyId, $configId);



        return view('frontend.analytics.index', compact(

            'company', 

            'configuration', 

            'activeTab', 

            'analyticsData', 

            'dailyAnalytics',

            'filterOptions',

            'startDate',

            'endDate'

        ));

    }



    public function comparison($companyId, $configId)

    {

        $company = Company::findOrFail($companyId);

        $configuration = Configuration::findOrFail($configId);

        $activeTab = 'comparison';

        

        // Default to last 7 days if no date range specified

        $startDate = Carbon::now()->subDays(7)->startOfDay();

        $endDate = Carbon::now()->endOfDay();



        // Get analytics data for the date range

        $analyticsData = $this->getAnalyticsData($companyId, $configId, $startDate, $endDate);

        

        // Get daily analytics for charts

        $dailyAnalytics = $this->getDailyAnalytics($companyId, $configId, $startDate, $endDate);

        

        // Get available filter options

        $filterOptions = $this->getFilterOptions($companyId, $configId);



        return view('frontend.analytics.comparison', compact(

            'company', 

            'configuration', 

            'activeTab', 

            'analyticsData', 

            'dailyAnalytics',

            'filterOptions',

            'startDate',

            'endDate'

        ));

    }



    public function granular($companyId, $configId)

    {

        $company = Company::findOrFail($companyId);

        $configuration = Configuration::findOrFail($configId);

        $activeTab = 'granular';

        

        // Default to last 7 days if no date range specified

        $startDate = Carbon::now()->subDays(7)->startOfDay();

        $endDate = Carbon::now()->endOfDay();



        // Get analytics data for the date range

        $analyticsData = $this->getAnalyticsData($companyId, $configId, $startDate, $endDate);

        

        // Get daily analytics for charts

        $dailyAnalytics = $this->getDailyAnalytics($companyId, $configId, $startDate, $endDate);

        

        // Get available filter options

        $filterOptions = $this->getFilterOptions($companyId, $configId);



        return view('frontend.analytics.granular', compact(

            'company', 

            'configuration', 

            'activeTab', 

            'analyticsData', 

            'dailyAnalytics',

            'filterOptions',

            'startDate',

            'endDate'

        ));

    }



    public function filter(Request $request, $companyId, $configId)

    {

        $company = Company::findOrFail($companyId);

        $configuration = Configuration::findOrFail($configId);

        

        // Get filter parameters

        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->subDays(7)->startOfDay();

        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfDay();

        $country = $request->input('country');

        $deviceType = $request->input('device_type');

        $os = $request->input('os');

        $browser = $request->input('browser');

        

        // Active tab from request

        $activeTab = $request->input('active_tab', 'overview');

        

        // Get filtered analytics data

        $query = Analytics::where('company_id', $companyId)

            ->where('configuration_id', $configId)

            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()]);

            

        if ($country) {

            $query->where('country', $country);

        }

        

        if ($deviceType) {

            $query->where('device_type', $deviceType);

        }

        

        if ($os) {

            $query->where('os', $os);

        }

        

        if ($browser) {

            $query->where('browser', $browser);

        }

        

        $analyticsData = [

            'displays' => $query->sum('displays'),

            'interactions' => $query->sum('interactions'),

            'ignores' => $query->sum('ignores'),

            'accept_all' => $query->sum('accept_all'),

            'deny_all' => $query->sum('deny_all'),

            'custom_choice' => $query->sum('custom_choice'),

        ];

        

        // Calculate rates

        $analyticsData['interaction_rate'] = $analyticsData['displays'] > 0 

            ? round(($analyticsData['interactions'] / $analyticsData['displays']) * 100, 2) 

            : 0;

            

        $analyticsData['accept_rate'] = $analyticsData['interactions'] > 0 

            ? round(($analyticsData['accept_all'] / $analyticsData['interactions']) * 100, 2) 

            : 0;

        

        // Get daily analytics for charts

        $dailyAnalytics = $this->getDailyAnalytics($companyId, $configId, $startDate, $endDate, $country, $deviceType, $os, $browser);

        

        // Get available filter options

        $filterOptions = $this->getFilterOptions($companyId, $configId);

        

        // Determine which view to return based on active tab

        $view = 'frontend.analytics.index';

        

        switch ($activeTab) {

            case 'comparison':

                $view = 'frontend.analytics.comparison';

                break;

            case 'granular':

                $view = 'frontend.analytics.granular';

                break;

            default:

                $view = 'frontend.analytics.index';

        }

        

        return view($view, compact(

            'company', 

            'configuration', 

            'activeTab', 

            'analyticsData', 

            'dailyAnalytics',

            'filterOptions',

            'startDate',

            'endDate',

            'country',

            'deviceType',

            'os',

            'browser'

        ));

    }



    /**

     * Get aggregated analytics data for the specified date range and filters

     */

    private function getAnalyticsData($companyId, $configId, $startDate, $endDate, $country = null, $deviceType = null, $os = null, $browser = null)

    {

        $query = Analytics::where('company_id', $companyId)

            ->where('configuration_id', $configId)

            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()]);

            

        if ($country) {

            $query->where('country', $country);

        }

        

        if ($deviceType) {

            $query->where('device_type', $deviceType);

        }

        

        if ($os) {

            $query->where('os', $os);

        }

        

        if ($browser) {

            $query->where('browser', $browser);

        }

        

        $analyticsData = [

            'displays' => $query->sum('displays'),

            'interactions' => $query->sum('interactions'),

            'ignores' => $query->sum('ignores'),

            'accept_all' => $query->sum('accept_all'),

            'deny_all' => $query->sum('deny_all'),

            'custom_choice' => $query->sum('custom_choice'),

        ];

        

        // Calculate rates

        $analyticsData['interaction_rate'] = $analyticsData['displays'] > 0 

            ? round(($analyticsData['interactions'] / $analyticsData['displays']) * 100, 2) 

            : 0;

            

        $analyticsData['accept_rate'] = $analyticsData['interactions'] > 0 

            ? round(($analyticsData['accept_all'] / $analyticsData['interactions']) * 100, 2) 

            : 0;

            

        return $analyticsData;

    }

    

    /**

     * Get daily analytics data for charts

     */

    private function getDailyAnalytics($companyId, $configId, $startDate, $endDate, $country = null, $deviceType = null, $os = null, $browser = null)

    {

        $query = Analytics::select(

                'date',

                DB::raw('SUM(displays) as displays'),

                DB::raw('SUM(interactions) as interactions'),

                DB::raw('SUM(ignores) as ignores'),

                DB::raw('SUM(accept_all) as accept_all'),

                DB::raw('SUM(deny_all) as deny_all'),

                DB::raw('SUM(custom_choice) as custom_choice')

            )

            ->where('company_id', $companyId)

            ->where('configuration_id', $configId)

            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()]);

            

        if ($country) {

            $query->where('country', $country);

        }

        

        if ($deviceType) {

            $query->where('device_type', $deviceType);

        }

        

        if ($os) {

            $query->where('os', $os);

        }

        

        if ($browser) {

            $query->where('browser', $browser);

        }

        

        $dailyData = $query->groupBy('date')

            ->orderBy('date')

            ->get()

            ->map(function ($item) {

                // Calculate rates

                $item->interaction_rate = $item->displays > 0 

                    ? round(($item->interactions / $item->displays) * 100, 2) 

                    : 0;

                    

                $item->accept_rate = $item->interactions > 0 

                    ? round(($item->accept_all / $item->interactions) * 100, 2) 

                    : 0;

                    

                return $item;

            });

            

        return $dailyData;

    }

    

    /**

     * Get available filter options

     */

    private function getFilterOptions($companyId, $configId)

    {

        $filterOptions = [

            'countries' => Analytics::where('company_id', $companyId)

                ->where('configuration_id', $configId)

                ->whereNotNull('country')

                ->distinct()

                ->pluck('country'),

                

            'device_types' => Analytics::where('company_id', $companyId)

                ->where('configuration_id', $configId)

                ->whereNotNull('device_type')

                ->distinct()

                ->pluck('device_type'),

                

            'os' => Analytics::where('company_id', $companyId)

                ->where('configuration_id', $configId)

                ->whereNotNull('os')

                ->distinct()

                ->pluck('os'),

                

            'browsers' => Analytics::where('company_id', $companyId)

                ->where('configuration_id', $configId)

                ->whereNotNull('browser')

                ->distinct()

                ->pluck('browser')

        ];

        

        return $filterOptions;

    }

    

    /**

     * Generate and download analytics report

     */

    public function downloadReport(Request $request, $companyId, $configId)

    {

        $company = Company::findOrFail($companyId);

        $configuration = Configuration::findOrFail($configId);

        

        // Get filter parameters

        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->subDays(30)->startOfDay();

        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfDay();

        $country = $request->input('country');

        $deviceType = $request->input('device_type');

        $os = $request->input('os');

        $browser = $request->input('browser');

        

        // Get analytics data

        $query = Analytics::where('company_id', $companyId)

            ->where('configuration_id', $configId)

            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()]);

            

        if ($country) {

            $query->where('country', $country);

        }

        

        if ($deviceType) {

            $query->where('device_type', $deviceType);

        }

        

        if ($os) {

            $query->where('os', $os);

        }

        

        if ($browser) {

            $query->where('browser', $browser);

        }

        

        $data = $query->get();

        

        // Create CSV content

        $headers = [

            'Date', 'Displays', 'Interactions', 'Ignores', 'Accept All', 'Deny All', 'Custom Choice',

            'Country', 'Device Type', 'OS', 'Browser'

        ];

        

        $callback = function() use ($data, $headers) {

            $file = fopen('php://output', 'w');

            fputcsv($file, $headers);

            

            foreach($data as $row) {

                fputcsv($file, [

                    $row->date,

                    $row->displays,

                    $row->interactions,

                    $row->ignores,

                    $row->accept_all,

                    $row->deny_all,

                    $row->custom_choice,

                    $row->country,

                    $row->device_type,

                    $row->os,

                    $row->browser

                ]);

            }

            

            fclose($file);

        };

        

        $filename = "cmp_analytics_{$company->name}_{$startDate->format('Y-m-d')}_{$endDate->format('Y-m-d')}.csv";

        

        return response()->stream($callback, 200, [

            'Content-Type' => 'text/csv',

            'Content-Disposition' => "attachment; filename=\"$filename\""

        ]);

    }

}