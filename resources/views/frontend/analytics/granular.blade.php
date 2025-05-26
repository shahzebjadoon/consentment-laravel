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

               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; font-weight: 800;{{ $activeTab == 'overview' ? 'background-color: white; color: #333; font-weight: 500;' : 'background-color: #f8f9fa; color: #666;' }} margin-right: 5px;">

                Interaction Analytics Overview

            </a>

            <a href="{{ route('frontend.analytics.comparison', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 

               class="tab-link {{ $activeTab == 'comparison' ? 'active' : '' }}" 

               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; font-weight: 800; {{ $activeTab == 'comparison' ? 'background-color: white; color: #333; font-weight: 500;' : 'background-color: #f8f9fa; color: #666;' }} margin-right: 5px;">

                Interaction Analytics Comparison

            </a>

            <a href="{{ route('frontend.analytics.granular', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 

               class="tab-link {{ $activeTab == 'granular' ? 'active' : '' }}" 

               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; {{ $activeTab == 'granular' ? 'background-color: white; color: #333; font-weight: 800;' : 'background-color: #f8f9fa; color: #666;' }}">

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

                <div style="display: flex; margin-bottom: 20px; margin-top:10px;">

                    <div style="display: flex; align-items: center; margin-right: 20px;">

                        <span style="color: #666; margin-right: 10px;">Time range</span>

                        <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}" style="width: 100px; margin-right: 5px;">

                        <span style="margin: 0 5px;">-</span>

                        <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}" style="width: 100px; margin-right: 5px;">

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

                    <select name="browser" class="form-control" style="width: 150px;">

                        <option value="">All Browsers</option>

                        @foreach($filterOptions['browsers'] as $browserOption)

                            <option value="{{ $browserOption }}" {{ isset($browser) && $browser == $browserOption ? 'selected' : '' }}>{{ $browserOption }}</option>

                        @endforeach

                    </select>

                    <label class="checkbox-container" style="display: flex; align-items: center; margin-left: 10px;">

                        <input type="checkbox" name="exclude_essential" style="margin-right: 5px;" {{ isset($excludeEssential) && $excludeEssential ? 'checked' : '' }}>

                        <span>Exclude Essential Services</span>

                    </label>

                    <button type="submit" class="btn btn-secondary" style="margin-left: auto; background-color: #0066cc; color: #fff;">Apply</button>

                </div>

            </form>

            

            <!-- Granular Analytics Section -->

            <div style="margin-top: 20px;">

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">

                    <h3 style="margin: 0;">Granular Analytics</h3>

                    <div style="display: flex; align-items: center; gap: 30px;">

                        <div style="text-align: center;">

                            <span style="color: #666; display: block; font-size: 12px;">Consent Decisions</span>

                            <span style="font-weight: 600; font-size: 24px;" id="totalConsentDecisions">{{ $analyticsData['interactions'] ?? 0 }}</span>

                        </div>

                        <div style="text-align: center;">

                            <span style="color: #666; display: block; font-size: 12px;">Opt-in Rate</span>

                            <span style="font-weight: 600; font-size: 24px; color: #4CAF50;" id="overallOptInRate">{{ $analyticsData['accept_rate'] ?? 0 }}%</span>

                        </div>

                    </div>

                </div>

                

                <p style="color: #666; margin-bottom: 20px;">Granular Analytics enables you to take informed decisions by providing insights into your users' active consent decisions.</p>

                

                <!-- Consent Decisions and Daily Opt-in Rate -->

                <div style="display: flex; gap: 20px; margin-bottom: 30px;">

                    <!-- Consent Decisions -->

                    <div style="flex: 1; border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px;">

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">

                            <h4 style="margin: 0;">Consent Decisions <i class="" style="color: #ccc; font-size: 14px;"></i></h4>

                        </div>

                        

                        <div style="display: flex; justify-content: center; padding: 20px;">

                            <!-- Doughnut chart for consent decisions -->

                            <canvas id="consentDecisionsChart" width="200" height="200"></canvas>

                        </div>

                        

                        <div style="display: flex; justify-content: center; gap: 30px; margin-top: 20px;">

                            <div style="display: flex; align-items: center;">

                                <span style="width: 14px; height: 14px; background-color: #4CAF50; border-radius: 2px; display: inline-block; margin-right: 5px;"></span>

                                <span>Opt-in</span>

                            </div>

                            <div style="display: flex; align-items: center;">

                                <span style="width: 14px; height: 14px; background-color: #f2f2f2; border-radius: 2px; display: inline-block; margin-right: 5px;"></span>

                                <span>Opt-out</span>

                            </div>

                        </div>

                    </div>

                    

                    <!-- Daily Opt-in Rate -->

                    <div style="flex: 1; border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px;">

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">

                            <h4 style="margin: 0;">Daily Opt-in Rate (%) <i class="" style="color: #ccc; font-size: 14px;"></i></h4>

                        </div>

                        

                        <div style="height: 250px; position: relative;">

                            <!-- Line chart for daily opt-in rate -->

                            <canvas id="dailyOptInRateChart"></canvas>

                        </div>

                    </div>

                </div>

                

                <!-- Marketing Section -->

                <div style="border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px; margin-bottom: 20px;">

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">

                        <h4 style="margin: 0;">Marketing</h4>

                        <div style="display: flex; gap: 20px;">

                            <div style="text-align: right;">

                                <span style="color: #666; font-size: 12px;">Consent Decisions</span>

                                <span style="font-weight: 600; display: block;" id="marketingDecisions">{{ $analyticsData['marketing_decisions'] ?? '--' }}</span>

                            </div>

                            <div style="text-align: right;">

                                <span style="color: #666; font-size: 12px;">Opt-in Rate</span>

                                <span style="font-weight: 600; display: block;" id="marketingOptInRate">{{ $analyticsData['marketing_rate'] ?? '--' }}</span>

                            </div>

                        </div>

                    </div>

                    <p style="color: #666; font-size: 14px; margin-bottom: 10px;">2 Data Processing Services</p>

                    

                    <!-- Consent Timeline -->

                    <div style="margin-top: 15px; height: 70px; position: relative;">

                        <!-- Progress bar -->

                        <div style="margin-top: 20px; width: 100%; height: 30px; background-color: #f2f2f2; border-radius: 4px; position: relative; overflow: hidden;">

                            <div id="marketingProgressBar" style="position: absolute; top: 0; left: 0; height: 100%; width: {{ $analyticsData['marketing_rate'] ?? 85 }}%; background-color: #4CAF50;"></div>

                        </div>

                        

                        <!-- Legend -->

                        <div style="display: flex; justify-content: space-between; margin-top: 10px; color: #666; font-size: 10px;">

                            <span>0%</span>

                            <span>20%</span>

                            <span>40%</span>

                            <span>60%</span>

                            <span>80%</span>

                            <span>100%</span>

                        </div>

                    </div>

                    

                    <div style="display: flex; justify-content: center; gap: 30px; margin-top: 10px;">

                        <div style="display: flex; align-items: center;">

                            <span style="width: 14px; height: 14px; background-color: #4CAF50; border-radius: 2px; display: inline-block; margin-right: 5px;"></span>

                            <span>Opt-in</span>

                        </div>

                        <div style="display: flex; align-items: center;">

                            <span style="width: 14px; height: 14px; background-color: #f2f2f2; border-radius: 2px; display: inline-block; margin-right: 5px;"></span>

                            <span>Opt-out</span>

                        </div>

                    </div>

                </div>

                

                <!-- Data Processing Services Accordion -->

                <div style="border: 1px solid #e6e8eb; border-radius: 8px; margin-bottom: 20px;">

                    <div style="padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e6e8eb; cursor: pointer;" onclick="toggleAccordion('dpsAccordion')">

                        <span style="font-weight: 500;">Data Processing Services</span>

                        <i class="fas fa-chevron-down" style="color: #666;" id="dpsAccordionIcon"></i>

                    </div>

                    <div id="dpsAccordion" style="display: none; padding: 15px 20px;">

                        <!-- DPS content would go here -->

                        <p>Data Processing Services details will appear here.</p>

                    </div>

                </div>

                

                <!-- Functional Section -->

                <div style="border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px; margin-bottom: 20px;">

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">

                        <h4 style="margin: 0;">Functional</h4>

                        <div style="display: flex; gap: 20px;">

                            <div style="text-align: right;">

                                <span style="color: #666; font-size: 12px;">Consent Decisions</span>

                                <span style="font-weight: 600; display: block;" id="functionalDecisions">{{ $analyticsData['functional_decisions'] ?? '--' }}</span>

                            </div>

                            <div style="text-align: right;">

                                <span style="color: #666; font-size: 12px;">Opt-in Rate</span>

                                <span style="font-weight: 600; display: block;" id="functionalOptInRate">{{ $analyticsData['functional_rate'] ?? '--' }}</span>

                            </div>

                        </div>

                    </div>

                    <p style="color: #666; font-size: 14px; margin-bottom: 10px;">4 Data Processing Services</p>

                    

                    <!-- Consent Timeline -->

                    <div style="margin-top: 15px; height: 70px; position: relative;">

                        <!-- Progress bar -->

                        <div style="margin-top: 20px; width: 100%; height: 30px; background-color: #f2f2f2; border-radius: 4px; position: relative; overflow: hidden;">

                            <div id="functionalProgressBar" style="position: absolute; top: 0; left: 0; height: 100%; width: {{ $analyticsData['functional_rate'] ?? 92 }}%; background-color: #4CAF50;"></div>

                        </div>

                        

                        <!-- Legend -->

                        <div style="display: flex; justify-content: space-between; margin-top: 10px; color: #666; font-size: 10px;">

                            <span>0%</span>

                            <span>20%</span>

                            <span>40%</span>

                            <span>60%</span>

                            <span>80%</span>

                            <span>100%</span>

                        </div>

                    </div>

                    

                    <div style="display: flex; justify-content: center; gap: 30px; margin-top: 10px;">

                        <div style="display: flex; align-items: center;">

                            <span style="width: 14px; height: 14px; background-color: #4CAF50; border-radius: 2px; display: inline-block; margin-right: 5px;"></span>

                            <span>Opt-in</span>

                        </div>

                        <div style="display: flex; align-items: center;">

                            <span style="width: 14px; height: 14px; background-color: #f2f2f2; border-radius: 2px; display: inline-block; margin-right: 5px;"></span>

                            <span>Opt-out</span>

                        </div>

                    </div>

                </div>

                

                <!-- Trend Accordion -->

                <div style="border: 1px solid #e6e8eb; border-radius: 8px; margin-bottom: 20px;">

                    <div style="padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e6e8eb; cursor: pointer;" onclick="toggleAccordion('trendAccordion')">

                        <span style="font-weight: 500;">Trend</span>

                        <i class="fas fa-chevron-down" style="color: #666;" id="trendAccordionIcon"></i>

                    </div>

                    <div id="trendAccordion" style="display: none; padding: 15px 20px;">

                        <!-- Trend content would go here -->

                        <p>Trend analysis will appear here.</p>

                    </div>

                </div>

                

                <!-- Essential Section -->

                <div style="border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px; margin-bottom: 30px;">

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">

                        <h4 style="margin: 0;">Essential <span style="font-weight: normal; color: #666; font-size: 14px;">[Essential]</span></h4>

                        <div style="display: flex; gap: 20px;">

                            <div style="text-align: right;">

                                <span style="color: #666; font-size: 12px;">Consent Decisions</span>

                                <span style="font-weight: 600; display: block;" id="essentialDecisions">{{ $analyticsData['essential_decisions'] ?? '--' }}</span>

                            </div>

                            <div style="text-align: right;">

                                <span style="color: #666; font-size: 12px;">Opt-in Rate</span>

                                <span style="font-weight: 600; display: block;" id="essentialOptInRate">{{ $analyticsData['essential_rate'] ?? '--' }}</span>

                            </div>

                        </div>

                    </div>

                    <p style="color: #666; font-size: 14px; margin-bottom: 10px;">1 Data Processing Services</p>

                    

                    <!-- Consent Timeline -->

                    <div style="margin-top: 15px; height: 70px; position: relative;">

                        <!-- Progress bar -->

                        <div style="margin-top: 20px; width: 100%; height: 30px; background-color: #f2f2f2; border-radius: 4px; position: relative; overflow: hidden;">

                            <div id="essentialProgressBar" style="position: absolute; top: 0; left: 0; height: 100%; width: {{ $analyticsData['essential_rate'] ?? 98 }}%; background-color: #4CAF50;"></div>

                        </div>

                        

                        <!-- Legend -->

                        <div style="display: flex; justify-content: space-between; margin-top: 10px; color: #666; font-size: 10px;">

                            <span>0%</span>

                            <span>20%</span>

                            <span>40%</span>

                            <span>60%</span>

                            <span>80%</span>

                            <span>100%</span>

                        </div>

                    </div>

                    

                    <div style="display: flex; justify-content: center; gap: 30px; margin-top: 10px;">

                        <div style="display: flex; align-items: center;">

                            <span style="width: 14px; height: 14px; background-color: #4CAF50; border-radius: 2px; display: inline-block; margin-right: 5px;"></span>

                            <span>Opt-in</span>

                        </div>

                        <div style="display: flex; align-items: center;">

                            <span style="width: 14px; height: 14px; background-color: #f2f2f2; border-radius: 2px; display: inline-block; margin-right: 5px;"></span>

                            <span>Opt-out</span>

                        </div>

                    </div>

                </div>

                

                <!-- Data Processing Services Accordion -->

                <div style="border: 1px solid #e6e8eb; border-radius: 8px; margin-bottom: 30px;">

                    <div style="padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; cursor: pointer;" onclick="toggleAccordion('dpsAccordion2')">

                        <span style="font-weight: 500;">Data Processing Services</span>

                        <i class="fas fa-chevron-down" style="color: #666;" id="dpsAccordion2Icon"></i>

                    </div>

                    <div id="dpsAccordion2" style="display: none; padding: 15px 20px;">

                        <!-- DPS content would go here -->

                        <p>Data Processing Services details will appear here.</p>

                    </div>

                </div>

                

                <!-- Info and Download Sections -->

                <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-top: 30px;">

                    <!-- Info Section -->

                    <div style="flex: 1; min-width: 300px; border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px; display: flex; align-items: center;">

                        <div style="width: 80px; height: 80px; background-color: #f0f7ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 20px;">

                            <i class="fas fa-chart-line" style="color: #0066cc; font-size: 30px;"></i>

                        </div>

                        <div>

                            <p style="font-size: 14px; margin-bottom: 5px;">maximize your data capture</p>

                            <a href="#" style="color: #0066cc; text-decoration: none; font-weight: 500;">Documentation</a>

                        </div>

                    </div>

                    

                    <!-- Download Report Section -->

                    <div style="flex: 1; min-width: 300px; border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px; display: flex; align-items: center;">

                        <div style="width: 80px; height: 80px; background-color: #f0f7ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 20px;">

                            <i class="fas fa-download" style="color: #0066cc; font-size: 30px;"></i>

                        </div>

                        <div>

                            <h4 style="margin-top: 0; margin-bottom: 5px;">Download Report</h4>

                            <p style="color: #666; font-size: 14px; margin-bottom: 10px;">Visualization tool (e.g. Google Data Studio) in order to create reports tailored to your needs.</p>

                            <a href="{{ route('frontend.analytics.download', [

                                'company_id' => $company->id, 

                                'config_id' => $configuration->id,

                                'start_date' => $startDate->format('Y-m-d'),

                                'end_date' => $endDate->format('Y-m-d'),

                                'country' => $country ?? '',

                                'device_type' => $deviceType ?? '',

                                'os' => $os ?? '',

                                'browser' => $browser ?? ''

                            ]) }}" class="btn" style="background-color: #f0f7ff; color: #0066cc; font-weight: 500;">

                                <i class="fas fa-download" style="margin-right: 5px;"></i> Download Report

                            </a>

                        </div>

                    </div>

                    

                    <!-- Download Category Overview Section -->

                    <div style="width: 100%; border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px;">

                        <h4 style="margin-top: 0; margin-bottom: 10px;">Download Category Overview</h4>

                        <p style="color: #666; font-size: 14px; margin-bottom: 15px;">Click to download a list of all active categories and their currently assigned Data Processing Services. You can use the list to enrich your Granular Analytics raw data.</p>

                        <button class="btn" style="background-color: #f0f7ff; color: #0066cc; font-weight: 500;">

                            <i class="fas fa-download" style="margin-right: 5px;"></i> Download

                        </button>

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

        const acceptRate = {{ $analyticsData['accept_rate'] ?? 0 }};

        const denyRate = 100 - acceptRate;

        

        // Process analytics data for daily chart

        const dailyData = @json($dailyAnalytics ?? []);

        

        // Extract dates and opt-in rates for the line chart

        const dates = [];

        const optInRates = [];

        

        if (dailyData && dailyData.length > 0) {

            dailyData.forEach(item => {

                if (item.date) {

                    const date = new Date(item.date);

                    dates.push(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));

                    optInRates.push(item.accept_rate || 0);

                }

            });

        } else {

            // No need for sample data in production - only show what's available

            console.log('No daily data available');

        }

        

        // Create the Consent Decisions chart (doughnut)

        const consentDecisionsChart = new Chart(

            document.getElementById('consentDecisionsChart'),

            {

                type: 'doughnut',

                data: {

                    labels: ['Opt-in', 'Opt-out'],

                    datasets: [{

                        data: [acceptRate, denyRate],

                        backgroundColor: ['#4CAF50', '#f2f2f2'],

                        borderWidth: 0

                    }]

                },

                options: {

                    responsive: true,

                    maintainAspectRatio: true,

                    cutout: '70%',

                    plugins: {

                        legend: {

                            display: false

                        },

                        tooltip: {

                            callbacks: {

                                label: function(context) {

                                    return context.label + ': ' + context.raw + '%';

                                }

                            }

                        }

                    }

                }

            }

        );

        

        // Create the Daily Opt-in Rate chart (line)

        const dailyOptInRateChart = new Chart(

            document.getElementById('dailyOptInRateChart'),

            {

                type: 'line',

                data: {

                    labels: dates,

                    datasets: [{

                        label: 'Opt-in Rate',

                        data: optInRates,

                        borderColor: '#4CAF50',

                        backgroundColor: 'rgba(76, 175, 80, 0.1)',

                        fill: true,

                        tension: 0.3,

                        pointBackgroundColor: '#4CAF50',

                        pointRadius: 4

                    }]

                },

                options: {

                    responsive: true,

                    maintainAspectRatio: false,

                    scales: {

                        y: {

                            beginAtZero: true,

                            max: 100,

                            ticks: {

                                callback: function(value) {

                                    return value + '%';

                                }

                            }

                        }

                    },

                    plugins: {

                        legend: {

                            display: false

                        },

                        tooltip: {

                            callbacks: {

                                label: function(context) {

                                    return context.dataset.label + ': ' + context.raw + '%';

                                }

                            }

                        }

                    }

                }

            }

        );

        

        // Set up the accordion functionality

        window.toggleAccordion = function(accordionId) {

            const accordion = document.getElementById(accordionId);

            const accordionIcon = document.getElementById(accordionId + 'Icon');

            

            if (accordion.style.display === 'none') {

                accordion.style.display = 'block';

                accordionIcon.classList.remove('fa-chevron-down');

                accordionIcon.classList.add('fa-chevron-up');

            } else {

                accordion.style.display = 'none';

                accordionIcon.classList.remove('fa-chevron-up');

                accordionIcon.classList.add('fa-chevron-down');

            }

        };

        

        // Update category progress bars - constrain width to max 100%

        function updateProgressBar(elementId, percentage) {

            const element = document.getElementById(elementId);

            if (element) {

                const displayWidth = Math.min(percentage, 100);

                element.style.width = displayWidth + '%';

            }

        }

        

        // Initialize category data with proper fallbacks and calculations

        function initCategoryData() {

            // Marketing data - use actual data from database, not hardcoded values

            let marketingRate = {{ isset($analyticsData['marketing_rate']) ? $analyticsData['marketing_rate'] : 0 }};

            let marketingDecisions = {{ isset($analyticsData['marketing_decisions']) ? $analyticsData['marketing_decisions'] : 'null' }};

            

            if (marketingDecisions === null || isNaN(marketingDecisions)) {

                marketingDecisions = '--';

                document.getElementById('marketingDecisions').textContent = '--';

            } else {

                document.getElementById('marketingDecisions').textContent = marketingDecisions;

            }

            

            if (marketingRate === null || isNaN(marketingRate)) {

                marketingRate = 0; // Default to 0 instead of hard-coded value

                document.getElementById('marketingOptInRate').textContent = '--';

            } else {

                document.getElementById('marketingOptInRate').textContent = marketingRate + '%';

            }

            

            updateProgressBar('marketingProgressBar', marketingRate);

            

            // Functional data - use actual data from database

            let functionalRate = {{ isset($analyticsData['functional_rate']) ? $analyticsData['functional_rate'] : 0 }};

            let functionalDecisions = {{ isset($analyticsData['functional_decisions']) ? $analyticsData['functional_decisions'] : 'null' }};

            

            if (functionalDecisions === null || isNaN(functionalDecisions)) {

                functionalDecisions = '--';

                document.getElementById('functionalDecisions').textContent = '--';

            } else {

                document.getElementById('functionalDecisions').textContent = functionalDecisions;

            }

            

            if (functionalRate === null || isNaN(functionalRate)) {

                functionalRate = 0; // Default to 0 instead of hard-coded value

                document.getElementById('functionalOptInRate').textContent = '--';

            } else {

                document.getElementById('functionalOptInRate').textContent = functionalRate + '%';

            }

            

            updateProgressBar('functionalProgressBar', functionalRate);

            

            // Essential data - use actual data from database

            let essentialRate = {{ isset($analyticsData['essential_rate']) ? $analyticsData['essential_rate'] : 0 }};

            let essentialDecisions = {{ isset($analyticsData['essential_decisions']) ? $analyticsData['essential_decisions'] : 'null' }};

            

            if (essentialDecisions === null || isNaN(essentialDecisions)) {

                essentialDecisions = '--';

                document.getElementById('essentialDecisions').textContent = '--';

            } else {

                document.getElementById('essentialDecisions').textContent = essentialDecisions;

            }

            

            if (essentialRate === null || isNaN(essentialRate)) {

                essentialRate = 0; // Default to 0 instead of hard-coded value

                document.getElementById('essentialOptInRate').textContent = '--';

            } else {

                document.getElementById('essentialOptInRate').textContent = essentialRate + '%';

            }

            

            updateProgressBar('essentialProgressBar', essentialRate);

        }

        

        // Initialize all data

        initCategoryData();

    });

</script>

@endsection