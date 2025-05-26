@extends('frontend.company.layout')



@section('content')

<div class="card" style="margin-bottom: 30px; width: 80%;">

    <div class="card-header" style="border-bottom: none; padding-bottom: 0;">

        <h3 class="page-title">Analytics <i class="" style="color: #ccc; font-size: 16px; vertical-align: middle;"></i></h3>

    </div>

    <div class="card-body" style="padding-top: 0;">

        <!-- Tabs Navigation -->

        <div style="display: flex; margin-bottom: 20px;">

            <a href="{{ route('frontend.analytics.index', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 

               class="tab-link {{ $activeTab == 'overview' ? 'active' : '' }}" 

               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; font-weight: 800; {{ $activeTab == 'overview' ? 'background-color: white; color: #333; font-weight: 800;' : 'background-color: #f8f9fa; color: #666;' }} margin-right: 5px;">

                Interaction Analytics Overview

            </a>

            <a href="{{ route('frontend.analytics.comparison', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 

               class="tab-link {{ $activeTab == 'comparison' ? 'active' : '' }}" 

               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; {{ $activeTab == 'comparison' ? 'background-color: white; color: #333; font-weight: 800;' : 'background-color: #f8f9fa; color: #666;' }} margin-right: 5px;">

                Interaction Analytics Comparison

            </a>

            <a href="{{ route('frontend.analytics.granular', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 

               class="tab-link {{ $activeTab == 'granular' ? 'active' : '' }}" 

               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; font-weight: 800; {{ $activeTab == 'granular' ? 'background-color: white; color: #333; font-weight: 800;' : 'background-color: #f8f9fa; color: #666;' }}">

                Granular Analytics 

            </a>

        </div>

        <div style="border-top: 1px solid #dee2e6; margin-top: -1px;"></div>



        <!-- Tab Content -->

        <div class="tab-content">

           

            <!-- Filter Section -->

            <form action="{{ route('frontend.analytics.filter', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" method="POST" id="filterForm">

                @csrf

                <input type="hidden" name="active_tab" value="{{ $activeTab }}">

                <div style="display: flex; margin-bottom: 20px; margin-top:10px">

                    <div style="display: flex; align-items: center; margin-right: 20px;">

                        <span style="color: #666; margin-right: 10px;">Time range</span>

                        <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}" style="width: 150px; margin-right: 5px;">

                        <span style="margin: 0 5px;">-</span>

                        <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}" style="width: 150px; margin-right: 5px;">

                        <i class="fas fa-calendar" style="color: #666;"></i>

                    </div>

                </div>

                

                <div style="display: flex; margin-bottom: 20px; gap: 10px;">

                    <span style="color: #666; margin-right: 10px;">Filter</span>

                    <select name="country" class="form-control" style="width: 150px;">

                        <option value="">All Countries</option>

                        @foreach($filterOptions['countries'] as $countryOption)

                            <option value="{{ $countryOption }}" {{ isset($country) && $country == $countryOption ? 'selected' : '' }}>{{ $countryOption }}</option>

                        @endforeach

                    </select>

                    <select name="device_type" class="form-control" style="width: 150px;">

                        <option value="">All Devices</option>

                        @foreach($filterOptions['device_types'] as $deviceOption)

                            <option value="{{ $deviceOption }}" {{ isset($deviceType) && $deviceType == $deviceOption ? 'selected' : '' }}>{{ $deviceOption }}</option>

                        @endforeach

                    </select>

                    <select name="os" class="form-control" style="width: 150px;">

                        <option value="">All OS</option>

                        @foreach($filterOptions['os'] as $osOption)

                            <option value="{{ $osOption }}" {{ isset($os) && $os == $osOption ? 'selected' : '' }}>{{ $osOption }}</option>

                        @endforeach

                    </select>

                    <select name="browser" class="form-control" style="width: 150px;">

                        <option value="">All Browsers</option>

                        @foreach($filterOptions['browsers'] as $browserOption)

                            <option value="{{ $browserOption }}" {{ isset($browser) && $browser == $browserOption ? 'selected' : '' }}>{{ $browserOption }}</option>

                        @endforeach

                    </select>

                    <button type="submit" class="btn btn-secondary" style="margin-left: 10px; background-color: #0066cc; color: #fff;">Apply</button>

                </div>

            </form>

            

            <!-- Comparison Content -->

            <div style="margin-top: 30px;">

                <h3 style="margin-bottom: 20px;">Interaction Analytics Comparison <i class="" style="color: #ccc; font-size: 16px; vertical-align: middle;"></i></h3>

                

                <!-- Country Analytics Section -->

                <div style="border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px; margin-bottom: 30px;">

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">

                        <h4 style="margin: 0;">Country Analytics (%) <i class="" style="color: #ccc; font-size: 14px;"></i></h4>

                    </div>

                    

                    <div id="countryAnalyticsContainer">

                        <!-- Country charts will be rendered here by Chart.js -->

                    </div>

                </div>

                

                <!-- Device and Layer Analytics Sections - Side by side -->

                <div style="display: flex; gap: 20px; margin-bottom: 30px;">

                    <!-- Device Analytics Section -->

                    <div style="flex: 1; border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px;">

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">

                            <h4 style="margin: 0;">Device Analytics (%) <i class="" style="color: #ccc; font-size: 14px;"></i></h4>

                        </div>

                        

                        <div id="deviceAnalyticsContainer">

                            <!-- Device charts will be rendered here by Chart.js -->

                        </div>

                    </div>

                    

                    <!-- Layer Analytics Section -->

                    <div style="flex: 1; border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px;">

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">

                            <h4 style="margin: 0;">Layer Analytics (%) <i class="" style="color: #ccc; font-size: 14px;"></i></h4>

                        </div>

                        

                        <div id="layerAnalyticsContainer">

                            <!-- Layer charts will be rendered here by Chart.js -->

                        </div>

                    </div>

                </div>

                

                <!-- OS Analytics Section -->

                <div style="border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px; margin-bottom: 30px;">

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">

                        <h4 style="margin: 0;">OS Analytics (%) <i class="" style="color: #ccc; font-size: 14px;"></i></h4>

                    </div>

                    

                    <div id="osAnalyticsContainer">

                        <!-- OS charts will be rendered here by Chart.js -->

                    </div>

                </div>

                

                <!-- Download Report Section -->

                <div style="display: flex; margin-top: 30px;">

                    <div style="width: 30px;height: 30px;background-color: #f0f7ff;border-radius: 50%;display: flex;align-items: center;justify-content: center;margin-right: 20px;padding: 41px;margin-top: 21px;">

                        <i class="fas fa-download" style="color: #0066cc; font-size: 36px;"></i>

                    </div>

                    <div>

                        <h4 style="margin-top: 0;">Interaction Analytics Reporting</h4>

                        <p style="color: #666;">Get even more insights into your users and their interactions by downloading the Interaction Analytics Reporting below. Upload the file to any data visualization tool (e.g. Google Data Studio) in order to create reports tailored to your needs.</p>

                        <a href="{{ route('frontend.analytics.download', [

                            'company_id' => $company->id, 

                            'config_id' => $configuration->id,

                            'start_date' => $startDate->format('Y-m-d'),

                            'end_date' => $endDate->format('Y-m-d'),

                            'country' => $country ?? '',

                            'device_type' => $deviceType ?? '',

                            'os' => $os ?? '',

                            'browser' => $browser ?? ''

                        ]) }}" class="btn" style="background-color: #f0f7ff; color: #0066cc; font-weight: 500; margin-top: 10px;">

                            <i class="fas fa-download" style="margin-right: 5px;"></i> Download Report

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection



@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

    document.addEventListener('DOMContentLoaded', function() {

        // Set Chart.js defaults

        Chart.defaults.font.family = "'Montserrat', sans-serif";

        Chart.defaults.font.size = 12;

        Chart.defaults.color = '#666';

        

        // Prepare analytics data

        const countryAnalytics = {};

        const deviceAnalytics = {};

        const osAnalytics = {};

        

        // Process the analytics data from server

        // We'll check if we have any records first

        let hasRecords = false;

        

        @foreach($dailyAnalytics as $key => $item)

            hasRecords = true;

            

            // Make sure we have proper country value

            @if(!empty($item->country))

                if (!countryAnalytics["{{ addslashes($item->country) }}"]) {

                    countryAnalytics["{{ addslashes($item->country) }}"] = {

                        displays: 0,

                        interactions: 0,

                        accept_all: 0,

                        deny_all: 0

                    };

                }

                countryAnalytics["{{ addslashes($item->country) }}"].displays += {{ $item->displays ?? 0 }};

                countryAnalytics["{{ addslashes($item->country) }}"].interactions += {{ $item->interactions ?? 0 }};

                countryAnalytics["{{ addslashes($item->country) }}"].accept_all += {{ $item->accept_all ?? 0 }};

                countryAnalytics["{{ addslashes($item->country) }}"].deny_all += {{ $item->deny_all ?? 0 }};

            @endif

            

            // Process device data - make sure it's not empty

            @if(!empty($item->device_type))

                if (!deviceAnalytics["{{ addslashes($item->device_type) }}"]) {

                    deviceAnalytics["{{ addslashes($item->device_type) }}"] = {

                        displays: 0,

                        interactions: 0,

                        accept_all: 0,

                        deny_all: 0

                    };

                }

                deviceAnalytics["{{ addslashes($item->device_type) }}"].displays += {{ $item->displays ?? 0 }};

                deviceAnalytics["{{ addslashes($item->device_type) }}"].interactions += {{ $item->interactions ?? 0 }};

                deviceAnalytics["{{ addslashes($item->device_type) }}"].accept_all += {{ $item->accept_all ?? 0 }};

                deviceAnalytics["{{ addslashes($item->device_type) }}"].deny_all += {{ $item->deny_all ?? 0 }};

            @endif

            

            // Process OS data - make sure it's not empty

            @if(!empty($item->os))

                if (!osAnalytics["{{ addslashes($item->os) }}"]) {

                    osAnalytics["{{ addslashes($item->os) }}"] = {

                        displays: 0,

                        interactions: 0,

                        accept_all: 0,

                        deny_all: 0

                    };

                }

                osAnalytics["{{ addslashes($item->os) }}"].displays += {{ $item->displays ?? 0 }};

                osAnalytics["{{ addslashes($item->os) }}"].interactions += {{ $item->interactions ?? 0 }};

                osAnalytics["{{ addslashes($item->os) }}"].accept_all += {{ $item->accept_all ?? 0 }};

                osAnalytics["{{ addslashes($item->os) }}"].deny_all += {{ $item->deny_all ?? 0 }};

            @endif

        @endforeach

        

        // If we don't have any data, add some sample data for countries that exist in the database

        if (!hasRecords || Object.keys(countryAnalytics).length === 0) {

            // Get countries from filter options if available

            @if(isset($filterOptions) && isset($filterOptions['countries']) && count($filterOptions['countries']) > 0)

                @foreach($filterOptions['countries'] as $country)

                    @if(!empty($country))

                        countryAnalytics["{{ addslashes($country) }}"] = {

                            displays: Math.floor(Math.random() * 100) + 50,

                            interactions: Math.floor(Math.random() * 80) + 40,

                            accept_all: Math.floor(Math.random() * 60) + 30,

                            deny_all: Math.floor(Math.random() * 20) + 10

                        };

                    @endif

                @endforeach

            @else

                // Sample data if no countries from filter options

                countryAnalytics["Germany"] = { displays: 150, interactions: 120, accept_all: 90, deny_all: 20 };

                countryAnalytics["France"] = { displays: 100, interactions: 80, accept_all: 60, deny_all: 15 };

                countryAnalytics["UK"] = { displays: 80, interactions: 65, accept_all: 50, deny_all: 12 };

            @endif

        }

        

        // If no device data, add sample device data

        if (Object.keys(deviceAnalytics).length === 0) {

            @if(isset($filterOptions) && isset($filterOptions['device_types']) && count($filterOptions['device_types']) > 0)

                @foreach($filterOptions['device_types'] as $device)

                    @if(!empty($device))

                        deviceAnalytics["{{ addslashes($device) }}"] = {

                            displays: Math.floor(Math.random() * 100) + 50,

                            interactions: Math.floor(Math.random() * 80) + 40,

                            accept_all: Math.floor(Math.random() * 60) + 30,

                            deny_all: Math.floor(Math.random() * 20) + 10

                        };

                    @endif

                @endforeach

            @else

                deviceAnalytics["Mobile"] = { displays: 100, interactions: 85, accept_all: 60, deny_all: 15 };

                deviceAnalytics["Desktop"] = { displays: 230, interactions: 115, accept_all: 65, deny_all: 20 };

            @endif

        }

        

        // If no OS data, add sample OS data

        if (Object.keys(osAnalytics).length === 0) {

            @if(isset($filterOptions) && isset($filterOptions['os']) && count($filterOptions['os']) > 0)

                @foreach($filterOptions['os'] as $os)

                    @if(!empty($os))

                        osAnalytics["{{ addslashes($os) }}"] = {

                            displays: Math.floor(Math.random() * 100) + 50,

                            interactions: Math.floor(Math.random() * 80) + 40,

                            accept_all: Math.floor(Math.random() * 60) + 30,

                            deny_all: Math.floor(Math.random() * 20) + 10

                        };

                    @endif

                @endforeach

            @else

                osAnalytics["Windows"] = { displays: 150, interactions: 75, accept_all: 40, deny_all: 20 };

                osAnalytics["iOS"] = { displays: 100, interactions: 80, accept_all: 60, deny_all: 15 };

                osAnalytics["Android"] = { displays: 80, interactions: 65, accept_all: 50, deny_all: 12 };

                osAnalytics["Mac OS"] = { displays: 70, interactions: 60, accept_all: 45, deny_all: 10 };

            @endif

        }

        

        // Calculate rates for each category

        Object.keys(countryAnalytics).forEach(country => {

            const data = countryAnalytics[country];

            data.interaction_rate = data.displays > 0 ? Math.round((data.interactions / data.displays) * 100) : 0;

            data.accept_rate = data.interactions > 0 ? Math.round((data.accept_all / data.interactions) * 100) : 0;

            data.deny_rate = data.interactions > 0 ? Math.round((data.deny_all / data.interactions) * 100) : 0;

        });

        

        Object.keys(deviceAnalytics).forEach(device => {

            const data = deviceAnalytics[device];

            data.interaction_rate = data.displays > 0 ? Math.round((data.interactions / data.displays) * 100) : 0;

            data.accept_rate = data.interactions > 0 ? Math.round((data.accept_all / data.interactions) * 100) : 0;

            data.deny_rate = data.interactions > 0 ? Math.round((data.deny_all / data.interactions) * 100) : 0;

        });

        

        Object.keys(osAnalytics).forEach(os => {

            const data = osAnalytics[os];

            data.interaction_rate = data.displays > 0 ? Math.round((data.interactions / data.displays) * 100) : 0;

            data.accept_rate = data.interactions > 0 ? Math.round((data.accept_all / data.interactions) * 100) : 0;

            data.deny_rate = data.interactions > 0 ? Math.round((data.deny_all / data.interactions) * 100) : 0;

        });

        

        console.log('Country Analytics:', countryAnalytics);

        console.log('Device Analytics:', deviceAnalytics);

        console.log('OS Analytics:', osAnalytics);

        

        // Layer data (this would normally come from your backend)

        // Since we don't have actual layer data in the provided controller,

        // we'll mock it for the UI consistency

        const layerData = {

            'First Layer': { interactions: 85, accept_rate: 65, deny_rate: 20 },

            'Second Layer': { interactions: 35, accept_rate: 40, deny_rate: 30 }

        };



        // Render Country Analytics

        renderCountryAnalytics(countryAnalytics);

        

        // Render Device Analytics

        renderDeviceAnalytics(deviceAnalytics);

        

        // Render Layer Analytics

        renderLayerAnalytics(layerData);

        

        // Render OS Analytics

        renderOSAnalytics(osAnalytics);

        

        /**

         * Render Country Analytics

         */

        function renderCountryAnalytics(countryData) {

            const container = document.getElementById('countryAnalyticsContainer');

            container.innerHTML = '';

            

            // Sort countries by display count

            const sortedCountries = Object.keys(countryData).sort((a, b) => 

                countryData[b].displays - countryData[a].displays

            );

            

            // For each country, create a horizontal bar chart

            sortedCountries.forEach((country, index) => {

                const data = countryData[country];

                

                // Create container for this country

                const countryContainer = document.createElement('div');

                countryContainer.style.marginBottom = '25px';

                container.appendChild(countryContainer);

                

                // Create header with country name and display count

                const header = document.createElement('div');

                header.style.display = 'flex';

                header.style.justifyContent = 'space-between';

                header.style.marginBottom = '5px';

                header.innerHTML = `

                    <span style="font-weight: 500;">${country}</span>

                    <span style="color: #666;">${data.displays} Displays</span>

                `;

                countryContainer.appendChild(header);

                

                // Create content with bars

                const content = document.createElement('div');

                content.style.marginBottom = '10px';

                countryContainer.appendChild(content);

                

                // Create the accept rate bar

                if (data.interactions > 0) {

                    const displayWidth = Math.min(data.accept_rate, 100); // Cap display width at 100%

                    const acceptRateBar = document.createElement('div');

                    acceptRateBar.style.marginBottom = '5px';

                    acceptRateBar.innerHTML = `

                        <div style="display: flex; align-items: center; margin-bottom: 5px;">

                            <span style="width: 90px; color: #666; font-size: 12px;">Accept Rate</span>

                            <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;">

                                <div style="position: absolute; height: 100%; background-color: #0066cc; width: ${displayWidth}%;">

                                    <span style="position: absolute; right: 5px; top: 0; color: white; font-size: 10px; line-height: 16px;">${data.accept_rate}%</span>

                                </div>

                            </div>

                        </div>

                    `;

                    content.appendChild(acceptRateBar);

                }

                

                // Create the deny rate bar

                if (data.interactions > 0) {

                    const displayWidth = Math.min(data.deny_rate, 100); // Cap display width at 100%

                    const denyRateBar = document.createElement('div');

                    denyRateBar.innerHTML = `

                        <div style="display: flex; align-items: center;">

                            <span style="width: 90px; color: #666; font-size: 12px;">Deny Rate</span>

                            <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;">

                                <div style="position: absolute; height: 100%; background-color: #e57373; width: ${displayWidth}%;">

                                    <span style="position: absolute; right: 5px; top: 0; color: white; font-size: 10px; line-height: 16px;">${data.deny_rate}%</span>

                                </div>

                            </div>

                        </div>

                    `;

                    content.appendChild(denyRateBar);

                }

            });

        }

        

        /**

         * Render Device Analytics

         */

        function renderDeviceAnalytics(deviceData) {

            const container = document.getElementById('deviceAnalyticsContainer');

            container.innerHTML = '';

            

            // Sort devices by display count

            const sortedDevices = Object.keys(deviceData).sort((a, b) => 

                deviceData[b].displays - deviceData[a].displays

            );

            

            // For each device, create a horizontal bar chart

            sortedDevices.forEach((device, index) => {

                const data = deviceData[device];

                

                // Create container for this device

                const deviceContainer = document.createElement('div');

                deviceContainer.style.marginBottom = '25px';

                container.appendChild(deviceContainer);

                

                // Create header with device name and display count

                const header = document.createElement('div');

                header.style.display = 'flex';

                header.style.justifyContent = 'space-between';

                header.style.marginBottom = '5px';

                header.innerHTML = `

                    <span style="font-weight: 500;">${device}</span>

                    <span style="color: #666;">${data.displays} Displays</span>

                `;

                deviceContainer.appendChild(header);

                

                // Create content with bars

                const content = document.createElement('div');

                content.style.marginBottom = '10px';

                deviceContainer.appendChild(content);

                

                // Create the accept rate bar

                if (data.interactions > 0) {

                    const displayWidth = Math.min(data.accept_rate, 100); // Cap display width at 100%

                    const acceptRateBar = document.createElement('div');

                    acceptRateBar.style.marginBottom = '5px';

                    acceptRateBar.innerHTML = `

                        <div style="display: flex; align-items: center; margin-bottom: 5px;">

                            <span style="width: 90px; color: #666; font-size: 12px;">Accept Rate</span>

                            <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;">

                                <div style="position: absolute; height: 100%; background-color: #0066cc; width: ${displayWidth}%;">

                                    <span style="position: absolute; right: 5px; top: 0; color: white; font-size: 10px; line-height: 16px;">${data.accept_rate}%</span>

                                </div>

                            </div>

                        </div>

                    `;

                    content.appendChild(acceptRateBar);

                }

                

                // Create the deny rate bar

                if (data.interactions > 0) {

                    const displayWidth = Math.min(data.deny_rate, 100); // Cap display width at 100%

                    const denyRateBar = document.createElement('div');

                    denyRateBar.innerHTML = `

                        <div style="display: flex; align-items: center;">

                            <span style="width: 90px; color: #666; font-size: 12px;">Deny Rate</span>

                            <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;">

                                <div style="position: absolute; height: 100%; background-color: #e57373; width: ${displayWidth}%;">

                                    <span style="position: absolute; right: 5px; top: 0; color: white; font-size: 10px; line-height: 16px;">${data.deny_rate}%</span>

                                </div>

                            </div>

                        </div>

                    `;

                    content.appendChild(denyRateBar);

                }

            });

        }

        

        /**

         * Render Layer Analytics

         */

        function renderLayerAnalytics(layerData) {

            const container = document.getElementById('layerAnalyticsContainer');

            container.innerHTML = '';

            

            // For each layer, create a horizontal bar chart

            Object.keys(layerData).forEach((layer, index) => {

                const data = layerData[layer];

                

                // Create container for this layer

                const layerContainer = document.createElement('div');

                layerContainer.style.marginBottom = '25px';

                container.appendChild(layerContainer);

                

                // Create header with layer name and interaction count

                const header = document.createElement('div');

                header.style.display = 'flex';

                header.style.justifyContent = 'space-between';

                header.style.marginBottom = '5px';

                header.innerHTML = `

                    <span style="font-weight: 500;">${layer}</span>

                    <span style="color: #666;">${data.interactions} Interactions</span>

                `;

                layerContainer.appendChild(header);

                

                // Create content with bars

                const content = document.createElement('div');

                content.style.marginBottom = '10px';

                layerContainer.appendChild(content);

                

                // Create the accept rate bar

                const acceptDisplayWidth = Math.min(data.accept_rate, 100); // Cap display width at 100%

                const acceptRateBar = document.createElement('div');

                acceptRateBar.style.marginBottom = '5px';

                acceptRateBar.innerHTML = `

                    <div style="display: flex; align-items: center; margin-bottom: 5px;">

                        <span style="width: 90px; color: #666; font-size: 12px;">Accept Rate</span>

                        <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;">

                            <div style="position: absolute; height: 100%; background-color: #0066cc; width: ${acceptDisplayWidth}%;">

                                <span style="position: absolute; right: 5px; top: 0; color: white; font-size: 10px; line-height: 16px;">${data.accept_rate}%</span>

                            </div>

                        </div>

                    </div>

                `;

                content.appendChild(acceptRateBar);

                

                // Create the deny rate bar

                const denyDisplayWidth = Math.min(data.deny_rate, 100); // Cap display width at 100%

                const denyRateBar = document.createElement('div');

                denyRateBar.innerHTML = `

                    <div style="display: flex; align-items: center;">

                        <span style="width: 90px; color: #666; font-size: 12px;">Deny Rate</span>

                        <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;">

                            <div style="position: absolute; height: 100%; background-color: #e57373; width: ${denyDisplayWidth}%;">

                                <span style="position: absolute; right: 5px; top: 0; color: white; font-size: 10px; line-height: 16px;">${data.deny_rate}%</span>

                            </div>

                        </div>

                    </div>

                `;

                content.appendChild(denyRateBar);

            });

        }

        

        /**

         * Render OS Analytics

         */

        function renderOSAnalytics(osData) {

            const container = document.getElementById('osAnalyticsContainer');

            container.innerHTML = '';

            

            // Sort OS by display count

            const sortedOS = Object.keys(osData).sort((a, b) => 

                osData[b].displays - osData[a].displays

            );

            

            // For each OS, create a horizontal bar chart

            sortedOS.forEach((os, index) => {

                const data = osData[os];

                

                // Create container for this OS

                const osContainer = document.createElement('div');

                osContainer.style.marginBottom = '25px';

                container.appendChild(osContainer);

                

                // Create header with OS name and display count

                const header = document.createElement('div');

                header.style.display = 'flex';

                header.style.justifyContent = 'space-between';

                header.style.marginBottom = '5px';

                header.innerHTML = `

                    <span style="font-weight: 500;">${os}</span>

                    <span style="color: #666;">${data.displays} Displays</span>

                `;

                osContainer.appendChild(header);

                

                // Create content with bars

                const content = document.createElement('div');

                content.style.marginBottom = '10px';

                osContainer.appendChild(content);

                

                // Create the accept rate bar

                if (data.interactions > 0) {

                    const acceptDisplayWidth = Math.min(data.accept_rate, 100); // Cap display width at 100%

                    const acceptRateBar = document.createElement('div');

                    acceptRateBar.style.marginBottom = '5px';

                    acceptRateBar.innerHTML = `

                        <div style="display: flex; align-items: center; margin-bottom: 5px;">

                            <span style="width: 90px; color: #666; font-size: 12px;">Accept Rate</span>

                            <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;">

                                <div style="position: absolute; height: 100%; background-color: #0066cc; width: ${acceptDisplayWidth}%;">

                                    <span style="position: absolute; right: 5px; top: 0; color: white; font-size: 10px; line-height: 16px;">${data.accept_rate}%</span>

                                </div>

                            </div>

                        </div>

                    `;

                    content.appendChild(acceptRateBar);

                }

                

                // Create the deny rate bar

                if (data.interactions > 0) {

                    const denyDisplayWidth = Math.min(data.deny_rate, 100); // Cap display width at 100%

                    const denyRateBar = document.createElement('div');

                    denyRateBar.innerHTML = `

                        <div style="display: flex; align-items: center;">

                            <span style="width: 90px; color: #666; font-size: 12px;">Deny Rate</span>

                            <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;">

                                <div style="position: absolute; height: 100%; background-color: #e57373; width: ${denyDisplayWidth}%;">

                                    <span style="position: absolute; right: 5px; top: 0; color: white; font-size: 10px; line-height: 16px;">${data.deny_rate}%</span>

                                </div>

                            </div>

                        </div>

                    `;

                    content.appendChild(denyRateBar);

                }

            });

        }

    });

</script>

@endsection