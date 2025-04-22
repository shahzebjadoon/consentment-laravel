@extends('frontend.company.layout')



@section('content')

<div class="card" style="margin-bottom: 30px; width: 73%;">

    <div class="card-header" style="border-bottom: none; padding-bottom: 0;">

        <h3 class="page-title">Analytics <i class="" style="color: #ccc; font-size: 16px; vertical-align: middle;"></i></h3>

    </div>

    <div class="card-body" style="padding-top: 0;">

        <!-- Tabs Navigation -->

        <div style="display: flex; margin-bottom: 20px;">

            <a href="{{ route('frontend.analytics.index', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 

               class="tab-link {{ $activeTab == 'overview' ? 'active' : '' }}" 

               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; {{ $activeTab == 'overview' ? 'background-color: white; color: #333; font-weight: 500;' : 'background-color: #f8f9fa; color: #666;' }} margin-right: 5px;">

                Interaction Analytics Overview

            </a>

            <a href="{{ route('frontend.analytics.comparison', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 

               class="tab-link {{ $activeTab == 'comparison' ? 'active' : '' }}" 

               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; {{ $activeTab == 'comparison' ? 'background-color: white; color: #333; font-weight: 500;' : 'background-color: #f8f9fa; color: #666;' }} margin-right: 5px;">

                Interaction Analytics Comparison

            </a>

            <a href="{{ route('frontend.analytics.granular', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 

               class="tab-link {{ $activeTab == 'granular' ? 'active' : '' }}" 

               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; {{ $activeTab == 'granular' ? 'background-color: white; color: #333; font-weight: 500;' : 'background-color: #f8f9fa; color: #666;' }}">

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

            

            <!-- User Interaction Analytics Section -->

            <div class="section-card" style="margin-bottom: 30px; margin-top: 50px;">

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">

                    <h3 style="margin: 0;">User Interaction Analytics</h3>

                    <div style="display: flex; align-items: center;">

                        <div style="text-align: center; margin-right: 20px;">

                            <span style="color: #666; display: block; margin-bottom: 5px;">Displays <i class="" style="color: #ccc; font-size: 12px;"></i></span>

                            <span style="font-weight: 600; font-size: 18px;">{{ number_format($analyticsData['displays']) }}</span>

                        </div>

                        <div style="text-align: center; margin-right: 20px;">

                            <span style="color: #666; display: block; margin-bottom: 5px;">Interactions <i class="" style="color: #ccc; font-size: 12px;"></i></span>

                            <span style="font-weight: 600; font-size: 18px;">{{ number_format($analyticsData['interactions']) }}</span>

                        </div>

                        <div style="text-align: center; margin-right: 20px;">

                            <span style="color: #666; display: block; margin-bottom: 5px;">Ignores <i class="" style="color: #ccc; font-size: 12px;"></i></span>

                            <span style="font-weight: 600; font-size: 18px;">{{ number_format($analyticsData['ignores']) }}</span>

                        </div>

                        <div style="text-align: center;">

                            <span style="color: #666; display: block; margin-bottom: 5px;">Interaction Rate <i class="" style="color: #ccc; font-size: 12px;"></i></span>

                            <span style="font-weight: 600; font-size: 18px; color: #ff6a00;">{{ $analyticsData['interaction_rate'] }}%</span>

                        </div>

                    </div>

                </div>

                

                <p style="color: #666; margin-bottom: 20px;">User Interaction Data enables you to monitor and analyze how users are interacting with your CMP when it is being displayed. The interaction rate indicates the percentage of users who actively engage with the CMP compared to those who ignore it.</p>

                

                <div style="display: flex; gap: 20px; margin-bottom: 20px;">

                    <div style="flex: 1; border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px;">

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">

                            <h4 style="margin: 0;">Accept vs. Deny (%)</h4>

                            <i class="" style="color: #ccc; font-size: 16px;"></i>

                        </div>

                        

                        <div style="display: flex; justify-content: center; align-items: center; padding: 30px 0;">

                            <canvas id="acceptVsDenyChart" width="200" height="200"></canvas>

                        </div>

                        

                        <div style="display: flex; justify-content: center; gap: 20px; margin-top: 15px;">

                            <div style="display: flex; align-items: center;">

                                <span style="width: 12px; height: 12px; background-color: #0066cc; display: inline-block; margin-right: 5px;"></span>

                                <span>Accept All</span>

                            </div>

                            <div style="display: flex; align-items: center;">

                                <span style="width: 12px; height: 12px; background-color: #e57373; display: inline-block; margin-right: 5px;"></span>

                                <span>Deny All</span>

                            </div>

                        </div>

                    </div>

                    

                    <div style="flex: 1; border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px;">

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">

                            <h4 style="margin: 0;">Daily Accept Rate (%)</h4>

                            <i class="" style="color: #ccc; font-size: 16px;"></i>

                        </div>

                        

                        <div style="height: 250px; position: relative;">

                            <canvas id="dailyAcceptRateOverviewChart"></canvas>

                        </div>

                    </div>

                </div>

                

                <div style="border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px; margin-bottom: 20px;">

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">

                        <h4 style="margin: 0;">Accept All vs. Deny All (Total)</h4>

                        <i class="" style="color: #ccc; font-size: 16px;"></i>

                    </div>

                    

                    <div style="height: 250px; position: relative;">

                        <canvas id="acceptVsDenyTotalChart"></canvas>

                    </div>

                </div>

                

                

            </div>

            

            <!-- User Acceptance Section -->

            <div class="section-card" style="margin-bottom: 30px;">

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">

                    <h3 style="margin: 0;">User Acceptance</h3>

                    <div style="display: flex; align-items: center;">

                        <div style="text-align: center; margin-right: 20px;">

                            <span style="color: #666; display: block; margin-bottom: 5px;">Accept All <i class="" style="color: #ccc; font-size: 12px;"></i></span>

                            <span style="font-weight: 600; font-size: 18px;">{{ number_format($analyticsData['accept_all']) }}</span>

                        </div>

                        <div style="text-align: center; margin-right: 20px;">

                            <span style="color: #666; display: block; margin-bottom: 5px;">Deny All <i class="" style="color: #ccc; font-size: 12px;"></i></span>

                            <span style="font-weight: 600; font-size: 18px;">{{ number_format($analyticsData['deny_all']) }}</span>

                        </div>

                        <div style="text-align: center; margin-right: 20px;">

                            <span style="color: #666; display: block; margin-bottom: 5px;">Custom <i class="" style="color: #ccc; font-size: 12px;"></i></span>

                            <span style="font-weight: 600; font-size: 18px;">{{ number_format($analyticsData['custom_choice']) }}</span>

                        </div>

                        <div style="text-align: center;">

                            <span style="color: #666; display: block; margin-bottom: 5px;">Accept Rate <i class="" style="color: #ccc; font-size: 12px;"></i></span>

                            <span style="font-weight: 600; font-size: 18px; color: #0066cc;">{{ $analyticsData['accept_rate'] }}%</span>

                        </div>

                    </div>

                </div>

                

                <p style="color: #666; margin-bottom: 20px;">User Acceptance Data enables you detailed insights into the type of interactions your users have. The Accept Rate indicates how likely users are willing to accept all Data Processing Services on your page.</p>

                

                <div style="display: flex; gap: 20px; margin-bottom: 20px;">

                    <div style="flex: 1; border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px;">

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">

                            <h4 style="margin: 0;">Accept vs. Deny vs. Custom (%)</h4>

                            <i class="" style="color: #ccc; font-size: 16px;"></i>

                        </div>

                        

                        <div style="display: flex; justify-content: center; align-items: center; padding: 30px 0;">

                            <canvas id="userChoicesChart" width="200" height="200"></canvas>

                        </div>

                        

                        <div style="display: flex; justify-content: center; gap: 20px; margin-top: 15px;">

                            <div style="display: flex; align-items: center;">

                                <span style="width: 12px; height: 12px; background-color: #0066cc; display: inline-block; margin-right: 5px;"></span>

                                <span>Accept All</span>

                            </div>

                            <div style="display: flex; align-items: center;">

                                <span style="width: 12px; height: 12px; background-color: #ffcc00; display: inline-block; margin-right: 5px;"></span>

                                <span>Custom</span>

                            </div>

                            <div style="display: flex; align-items: center;">

                                <span style="width: 12px; height: 12px; background-color: #e57373; display: inline-block; margin-right: 5px;"></span>

                                <span>Deny All</span>

                            </div>

                        </div>

                    </div>

                    

                    <div style="flex: 1; border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px;">

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">

                            <h4 style="margin: 0;">Daily Deny Rate (%)</h4>

                            <i class="" style="color: #ccc; font-size: 16px;"></i>

                        </div>

                        

                        <div style="height: 250px; position: relative;">

                            <canvas id="dailyDenyRateChart"></canvas>

                        </div>

                    </div>

                </div>

                

                <div style="border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px; margin-bottom: 20px;">

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">

                        <h4 style="margin: 0;">Accept All vs. Deny All vs. Custom (Total)</h4>

                        <i class="" style="color: #ccc; font-size: 16px;"></i>

                    </div>

                    

                    <div style="height: 250px; position: relative;">

                        <canvas id="userChoicesTotalChart"></canvas>

                    </div>

                </div>

                

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

        

        // Prepare daily analytics data

        const dailyData = @json($dailyAnalytics);

        const dates = dailyData.map(item => {

            const date = new Date(item.date);

            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });

        });

        

        // Accept vs Deny Chart

        const acceptVsDenyChart = new Chart(

            document.getElementById('acceptVsDenyChart'),

            {

                type: 'doughnut',

                data: {

                    labels: ['Accept All', 'Deny All'],

                    datasets: [{

                        data: [

                            {{ $analyticsData['accept_all'] }}, 

                            {{ $analyticsData['deny_all'] }}

                        ],

                        backgroundColor: ['#0066cc', '#e57373'],

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

                                    const label = context.label || '';

                                    const value = context.raw || 0;

                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);

                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;

                                    return `${label}: ${percentage}%`;

                                }

                            }

                        }

                    }

                }

            }

        );

        

        // Daily Accept Rate Overview Chart

        const dailyAcceptRateOverviewChart = new Chart(

            document.getElementById('dailyAcceptRateOverviewChart'),

            {

                type: 'line',

                data: {

                    labels: dates,

                    datasets: [{

                        label: 'Accept Rate',

                        data: dailyData.map(item => item.accept_rate),

                        borderColor: '#0066cc',

                        backgroundColor: 'rgba(0, 102, 204, 0.1)',

                        fill: true,

                        tension: 0.3,

                        pointBackgroundColor: '#0066cc',

                        pointRadius: 3

                    }]

                },

                options: {

                    responsive: true,

                    maintainAspectRatio: false,

                    scales: {

                        y: {

                            beginAtZero: true,

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

        

        // Accept vs Deny Total Chart

        const acceptVsDenyTotalChart = new Chart(

            document.getElementById('acceptVsDenyTotalChart'),

            {

                type: 'bar',

                data: {

                    labels: dates,

                    datasets: [

                        {

                            label: 'Accept All',

                            data: dailyData.map(item => item.accept_all),

                            backgroundColor: '#0066cc',

                            order: 1

                        },

                        {

                            label: 'Deny All',

                            data: dailyData.map(item => item.deny_all),

                            backgroundColor: '#e57373',

                            order: 2

                        }

                    ]

                },

                options: {

                    responsive: true,

                    maintainAspectRatio: false,

                    scales: {

                        x: {

                            stacked: false

                        },

                        y: {

                            beginAtZero: true

                        }

                    }

                }

            }

        );

        

        // User Choices Chart

        const userChoicesChart = new Chart(

            document.getElementById('userChoicesChart'),

            {

                type: 'doughnut',

                data: {

                    labels: ['Accept All', 'Custom Choice', 'Deny All'],

                    datasets: [{

                        data: [

                            {{ $analyticsData['accept_all'] }},

                            {{ $analyticsData['custom_choice'] }},

                            {{ $analyticsData['deny_all'] }}

                        ],

                        backgroundColor: ['#0066cc', '#ffcc00', '#e57373'],

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

                                    const label = context.label || '';

                                    const value = context.raw || 0;

                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);

                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;

                                    return `${label}: ${percentage}%`;

                                }

                            }

                        }

                    }

                }

            }

        );

        

        // Daily Deny Rate Chart

        const dailyDenyRateChart = new Chart(

            document.getElementById('dailyDenyRateChart'),

            {

                type: 'line',

                data: {

                    labels: dates,

                    datasets: [{

                        label: 'Deny Rate',

                        data: dailyData.map(item => {

                            // Calculate deny rate as percentage of deny_all to total interactions

                            const totalInteractions = item.accept_all + item.deny_all + item.custom_choice;

                            return totalInteractions > 0 ? Math.round((item.deny_all / totalInteractions) * 100) : 0;

                        }),

                        borderColor: '#e57373',

                        backgroundColor: 'rgba(229, 115, 115, 0.1)',

                        fill: true,

                        tension: 0.3,

                        pointBackgroundColor: '#e57373',

                        pointRadius: 3

                    }]

                },

                options: {

                    responsive: true,

                    maintainAspectRatio: false,

                    scales: {

                        y: {

                            beginAtZero: true,

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

        

        // User Choices Total Chart

        const userChoicesTotalChart = new Chart(

            document.getElementById('userChoicesTotalChart'),

            {

                type: 'bar',

                data: {

                    labels: dates,

                    datasets: [

                        {

                            label: 'Accept All',

                            data: dailyData.map(item => item.accept_all),

                            backgroundColor: '#0066cc',

                            order: 1

                        },

                        {

                            label: 'Custom Choice',

                            data: dailyData.map(item => item.custom_choice),

                            backgroundColor: '#ffcc00',

                            order: 2

                        },

                        {

                            label: 'Deny All',

                            data: dailyData.map(item => item.deny_all),

                            backgroundColor: '#e57373',

                            order: 3

                        }

                    ]

                },

                options: {

                    responsive: true,

                    maintainAspectRatio: false,

                    scales: {

                        x: {

                            stacked: false

                        },

                        y: {

                            beginAtZero: true,

                            stacked: false

                        }

                    }

                }

            }

        );

    });

</script>

@endsection