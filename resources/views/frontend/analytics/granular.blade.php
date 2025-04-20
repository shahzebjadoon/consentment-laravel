@extends('frontend.company.layout')

@section('content')
<div class="card" style="margin-bottom: 30px; width: 60%;">
    <div class="card-header" style="border-bottom: none; padding-bottom: 0;">
        <h3 class="page-title">Analytics <i class="fas fa-info-circle" style="color: #ccc; font-size: 16px; vertical-align: middle;"></i></h3>
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
            <!-- Premium Feature Banner -->
          
            
            <!-- Filter Section -->
            <div style="display: flex; margin-bottom: 20px;margin-top:10px;">
                <div style="display: flex; align-items: center; margin-right: 20px;">
                    <span style="color: #666; margin-right: 10px;">Time range</span>
                    <input type="text" class="form-control" placeholder="03/04/25" style="width: 100px; margin-right: 5px;">
                    <span style="margin: 0 5px;">-</span>
                    <input type="text" class="form-control" placeholder="10/04/25" style="width: 100px; margin-right: 5px;">
                    <i class="fas fa-calendar" style="color: #666;"></i>
                </div>
            </div>
            
            <div style="display: flex; margin-bottom: 20px; gap: 10px;">
                <span style="color: #666; margin-right: 10px;">Filter</span>
                <input type="text" class="form-control" placeholder="Country" style="width: 150px;">
                <input type="text" class="form-control" placeholder="Browser" style="width: 150px;">
                <label class="checkbox-container" style="display: flex; align-items: center; margin-left: 10px;">
                    <input type="checkbox" style="margin-right: 5px;">
                    <span>Exclude Essential Services</span>
                </label>
                <button class="btn btn-secondary" style="margin-left: auto;">Apply</button>
            </div>
            
            <!-- Granular Analytics Section -->
            <div style="margin-top: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h3 style="margin: 0;">Granular Analytics</h3>
                    <div style="display: flex; align-items: center; gap: 30px;">
                        <div style="text-align: center;">
                            <span style="color: #666; display: block; font-size: 12px;">Consent Decisions</span>
                            <span style="font-weight: 600; font-size: 24px;">942</span>
                        </div>
                        <div style="text-align: center;">
                            <span style="color: #666; display: block; font-size: 12px;">Opt-in Rate</span>
                            <span style="font-weight: 600; font-size: 24px; color: #4CAF50;">94%</span>
                        </div>
                    </div>
                </div>
                
                <p style="color: #666; margin-bottom: 20px;">Granular Analytics enables you to take informed decisions by providing insights into your users' active consent decisions.</p>
                
                <!-- Consent Decisions and Daily Opt-in Rate -->
                <div style="display: flex; gap: 20px; margin-bottom: 30px;">
                    <!-- Consent Decisions -->
                    <div style="flex: 1; border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <h4 style="margin: 0;">Consent Decisions <i class="fas fa-info-circle" style="color: #ccc; font-size: 14px;"></i></h4>
                        </div>
                        
                        <div style="display: flex; justify-content: center; padding: 20px;">
                            <!-- Pie chart for consent decisions -->
                            <div style="width: 200px; height: 200px; position: relative;">
                                <div style="width: 200px; height: 200px; border-radius: 50%; background-color: #4CAF50; position: relative; overflow: hidden;">
                                    <div style="position: absolute; width: 200px; height: 200px; clip-path: polygon(50% 50%, 50% 0, 12% 0, 0 0, 0 12%, 0 50%); background-color: #f2f2f2;"></div>
                                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; font-weight: bold; font-size: 20px;">93.95%</div>
                                </div>
                            </div>
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
                            <h4 style="margin: 0;">Daily Opt-in Rate (%) <i class="fas fa-info-circle" style="color: #ccc; font-size: 14px;"></i></h4>
                        </div>
                        
                        <div style="height: 250px; position: relative;">
                            <!-- Line chart for daily opt-in rate -->
                            <div style="width: 100%; height: 200px; background-color: #f8f9fa; position: relative; margin-top: 10px;">
                                <!-- Y-axis markings -->
                                <div style="position: absolute; left: 0; right: 0; top: 0; height: 1px; background-color: #e9e9e9;"></div>
                                <div style="position: absolute; left: 0; right: 0; top: 50px; height: 1px; background-color: #e9e9e9;"></div>
                                <div style="position: absolute; left: 0; right: 0; top: 100px; height: 1px; background-color: #e9e9e9;"></div>
                                <div style="position: absolute; left: 0; right: 0; top: 150px; height: 1px; background-color: #e9e9e9;"></div>
                                <div style="position: absolute; left: 0; right: 0; bottom: 0; height: 1px; background-color: #e9e9e9;"></div>
                                
                                <!-- Line chart -->
                                <svg width="100%" height="100%" style="position: absolute; top: 0; left: 0;">
                                    <polyline points="0,70 80,80 160,40 240,35 320,60" style="fill:none;stroke:#4CAF50;stroke-width:2" />
                                    <circle cx="0" cy="70" r="4" style="fill:#4CAF50;" />
                                    <circle cx="80" cy="80" r="4" style="fill:#4CAF50;" />
                                    <circle cx="160" cy="40" r="4" style="fill:#4CAF50;" />
                                    <circle cx="240" cy="35" r="4" style="fill:#4CAF50;" />
                                    <circle cx="320" cy="60" r="4" style="fill:#4CAF50;" />
                                </svg>
                            </div>
                            
                            <!-- X-axis labels -->
                            <div style="display: flex; justify-content: space-between; margin-top: 10px; color: #666; font-size: 12px;">
                                <span>2023-04-01</span>
                                <span>2023-04-04</span>
                                <span>2023-04-08</span>
                                <span>2023-04-09</span>
                                <span>2023-04-10</span>
                            </div>
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
                                <span style="font-weight: 600; display: block;">--</span>
                            </div>
                            <div style="text-align: right;">
                                <span style="color: #666; font-size: 12px;">Opt-in Rate</span>
                                <span style="font-weight: 600; display: block;">--</span>
                            </div>
                        </div>
                    </div>
                    <p style="color: #666; font-size: 14px; margin-bottom: 10px;">2 Data Processing Services</p>
                    
                    <!-- Consent Timeline -->
                    <div style="margin-top: 15px; height: 70px; position: relative;">
                        <!-- Time markers -->
                        <div style="display: flex; justify-content: space-between; position: absolute; width: 100%; top: 0; color: #666; font-size: 10px;">
                            <span>12%</span>
                            <span>30%</span>
                            <span>50%</span>
                            <span>70%</span>
                            <span>90%</span>
                        </div>
                        
                        <!-- Progress bar -->
                        <div style="margin-top: 20px; width: 100%; height: 30px; background-color: #f2f2f2; border-radius: 4px; position: relative; overflow: hidden;">
                            <div style="position: absolute; top: 0; left: 0; height: 100%; width: 85%; background-color: #4CAF50;"></div>
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
                    <div style="padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e6e8eb; cursor: pointer;">
                        <span style="font-weight: 500;">Data Processing Services</span>
                        <i class="fas fa-chevron-down" style="color: #666;"></i>
                    </div>
                </div>
                
                <!-- Functional Section -->
                <div style="border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px; margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                        <h4 style="margin: 0;">Functional</h4>
                        <div style="display: flex; gap: 20px;">
                            <div style="text-align: right;">
                                <span style="color: #666; font-size: 12px;">Consent Decisions</span>
                                <span style="font-weight: 600; display: block;">--</span>
                            </div>
                            <div style="text-align: right;">
                                <span style="color: #666; font-size: 12px;">Opt-in Rate</span>
                                <span style="font-weight: 600; display: block;">--</span>
                            </div>
                        </div>
                    </div>
                    <p style="color: #666; font-size: 14px; margin-bottom: 10px;">4 Data Processing Services</p>
                    
                    <!-- Consent Timeline -->
                    <div style="margin-top: 15px; height: 70px; position: relative;">
                        <!-- Progress bar -->
                        <div style="margin-top: 20px; width: 100%; height: 30px; background-color: #f2f2f2; border-radius: 4px; position: relative; overflow: hidden;">
                            <div style="position: absolute; top: 0; left: 0; height: 100%; width: 92%; background-color: #4CAF50;"></div>
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
                    <div style="padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e6e8eb; cursor: pointer;">
                        <span style="font-weight: 500;">Trend</span>
                        <i class="fas fa-chevron-down" style="color: #666;"></i>
                    </div>
                </div>
                
                <!-- Essential Section -->
                <div style="border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px; margin-bottom: 30px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                        <h4 style="margin: 0;">Essential <span style="font-weight: normal; color: #666; font-size: 14px;">[Essential]</span></h4>
                        <div style="display: flex; gap: 20px;">
                            <div style="text-align: right;">
                                <span style="color: #666; font-size: 12px;">Consent Decisions</span>
                                <span style="font-weight: 600; display: block;">--</span>
                            </div>
                            <div style="text-align: right;">
                                <span style="color: #666; font-size: 12px;">Opt-in Rate</span>
                                <span style="font-weight: 600; display: block;">--</span>
                            </div>
                        </div>
                    </div>
                    <p style="color: #666; font-size: 14px; margin-bottom: 10px;">1 Data Processing Services</p>
                    
                    <!-- Consent Timeline -->
                    <div style="margin-top: 15px; height: 70px; position: relative;">
                        <!-- Progress bar -->
                        <div style="margin-top: 20px; width: 100%; height: 30px; background-color: #f2f2f2; border-radius: 4px; position: relative; overflow: hidden;">
                            <div style="position: absolute; top: 0; left: 0; height: 100%; width: 98%; background-color: #4CAF50;"></div>
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
                    <div style="padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; cursor: pointer;">
                        <span style="font-weight: 500;">Data Processing Services</span>
                        <i class="fas fa-chevron-down" style="color: #666;"></i>
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
                            <button class="btn" style="background-color: #f0f7ff; color: #0066cc; font-weight: 500;">
                                <i class="fas fa-download" style="margin-right: 5px;"></i> Download Report
                            </button>
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