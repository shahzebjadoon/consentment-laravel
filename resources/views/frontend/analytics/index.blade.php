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
            <div style="background-color: #fff8e1; border-radius: 5px; padding: 15px; margin: 20px 0; display: flex; align-items: center;">
                <div style="background-color: #ffd600; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                    <i class="fas fa-bolt" style="color: white;"></i>
                </div>
                <div>
                    <h4 style="margin: 0; margin-bottom: 5px; font-size: 16px;">This feature is available in higher plan</h4>
                    <p style="margin: 0; color: #666;">Upgrade your plan to unlock exclusive features and premium content.</p>
                </div>
                <button type="button" class="btn btn-primary" style="margin-left: auto;">Upgrade</button>
            </div>
            
            <!-- Filter Section -->
            <div style="display: flex; margin-bottom: 20px;">
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
                <input type="text" class="form-control" placeholder="Domain" style="width: 150px;">
                <input type="text" class="form-control" placeholder="Country" style="width: 150px;">
                <input type="text" class="form-control" placeholder="Device" style="width: 150px;">
                <input type="text" class="form-control" placeholder="OS" style="width: 150px;">
                <button class="btn btn-secondary" style="margin-left: 10px;">Apply</button>
            </div>
            
            <!-- User Interaction Analytics Section -->
            <div class="section-card" style="margin-bottom: 30px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h3 style="margin: 0;">User Interaction Analytics</h3>
                    <div style="display: flex; align-items: center;">
                        <div style="text-align: center; margin-right: 20px;">
                            <span style="color: #666; display: block; margin-bottom: 5px;">Displays <i class="fas fa-info-circle" style="color: #ccc; font-size: 12px;"></i></span>
                            <span style="font-weight: 600; font-size: 18px;">--</span>
                        </div>
                        <div style="text-align: center; margin-right: 20px;">
                            <span style="color: #666; display: block; margin-bottom: 5px;">Interactions <i class="fas fa-info-circle" style="color: #ccc; font-size: 12px;"></i></span>
                            <span style="font-weight: 600; font-size: 18px;">--</span>
                        </div>
                        <div style="text-align: center; margin-right: 20px;">
                            <span style="color: #666; display: block; margin-bottom: 5px;">Ignores <i class="fas fa-info-circle" style="color: #ccc; font-size: 12px;"></i></span>
                            <span style="font-weight: 600; font-size: 18px;">--</span>
                        </div>
                        <div style="text-align: center;">
                            <span style="color: #666; display: block; margin-bottom: 5px;">Interaction Rate <i class="fas fa-info-circle" style="color: #ccc; font-size: 12px;"></i></span>
                            <span style="font-weight: 600; font-size: 18px; color: #ff6a00;">--</span>
                        </div>
                    </div>
                </div>
                
                <p style="color: #666; margin-bottom: 20px;">User Interaction Data enables you to monitor and analyze how users are interacting with your CMP when it is being displayed. The interaction rate indicates the percentage of users who actively engage with the CMP compared to those who ignore it.</p>
                
                <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                    <div style="flex: 1; border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <h4 style="margin: 0;">Interaction vs Ignores (%)</h4>
                            <i class="fas fa-info-circle" style="color: #ccc; font-size: 16px;"></i>
                        </div>
                        
                        <div style="display: flex; justify-content: center; align-items: center; padding: 30px 0;">
                            <!-- Placeholder for pie chart -->
                            <div style="width: 200px; height: 200px; background-color: #f8f9fa; border-radius: 50%; position: relative; overflow: hidden;">
                                <div style="position: absolute; width: 170%; height: 170%; background-color: #ff6a00; top: -20%; left: -100%; transform: rotate(45deg);"></div>
                                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #fff; font-weight: 600;">17.81%</div>
                            </div>
                        </div>
                        
                        <div style="display: flex; justify-content: center; gap: 20px; margin-top: 15px;">
                            <div style="display: flex; align-items: center;">
                                <span style="width: 12px; height: 12px; background-color: #ff6a00; display: inline-block; margin-right: 5px;"></span>
                                <span>Interactions</span>
                            </div>
                            <div style="display: flex; align-items: center;">
                                <span style="width: 12px; height: 12px; background-color: #e9e9e9; display: inline-block; margin-right: 5px;"></span>
                                <span>Ignores</span>
                            </div>
                        </div>
                    </div>
                    
                    <div style="flex: 1; border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <h4 style="margin: 0;">Daily Interaction Rate (%)</h4>
                            <i class="fas fa-info-circle" style="color: #ccc; font-size: 16px;"></i>
                        </div>
                        
                        <div style="height: 250px; position: relative;">
                            <!-- Placeholder for line chart -->
                            <div style="width: 100%; height: 180px; background-image: linear-gradient(to bottom, rgba(255,106,0,0.1), transparent); position: relative; margin-top: 30px;">
                                <div style="position: absolute; left: 0; right: 0; bottom: 0; height: 2px; background-color: #e9e9e9;"></div>
                                <svg width="100%" height="100%" style="position: absolute; top: 0; left: 0;">
                                    <polyline points="0,120 100,80 200,50 300,30 400,70" style="fill:none;stroke:#ff6a00;stroke-width:2" />
                                </svg>
                            </div>
                            
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
                
                <div style="border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px; margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <h4 style="margin: 0;">Displays vs. Interactions (Total)</h4>
                        <i class="fas fa-info-circle" style="color: #ccc; font-size: 16px;"></i>
                    </div>
                    
                    <div style="height: 250px; position: relative;">
                        <!-- Placeholder for bar chart -->
                        <div style="width: 100%; height: 180px; position: relative; margin-top: 30px;">
                            <div style="position: absolute; left: 0; right: 0; bottom: 0; height: 2px; background-color: #e9e9e9;"></div>
                            
                            <div style="display: flex; justify-content: space-around; align-items: flex-end; height: 100%;">
                                <div style="display: flex; flex-direction: column; align-items: center; width: 40px;">
                                    <div style="height: 40px; width: 30px; background-color: #ff6a00;"></div>
                                    <div style="height: 20px; width: 30px; background-color: #e9e9e9;"></div>
                                </div>
                                <div style="display: flex; flex-direction: column; align-items: center; width: 40px;">
                                    <div style="height: 90px; width: 30px; background-color: #ff6a00;"></div>
                                    <div style="height: 30px; width: 30px; background-color: #e9e9e9;"></div>
                                </div>
                                <div style="display: flex; flex-direction: column; align-items: center; width: 40px;">
                                    <div style="height: 130px; width: 30px; background-color: #ff6a00;"></div>
                                    <div style="height: 40px; width: 30px; background-color: #e9e9e9;"></div>
                                </div>
                                <div style="display: flex; flex-direction: column; align-items: center; width: 40px;">
                                    <div style="height: 70px; width: 30px; background-color: #ff6a00;"></div>
                                    <div style="height: 25px; width: 30px; background-color: #e9e9e9;"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div style="display: flex; justify-content: space-around; margin-top: 10px; color: #666; font-size: 12px;">
                            <span>2023-04-01</span>
                            <span>2023-04-04</span>
                            <span>2023-04-09</span>
                            <span>2023-04-10</span>
                        </div>
                    </div>
                </div>
                
                <div style="display: flex; margin-top: 30px;">
                    <div style="width: 100px; height: 100px; background-color: #f0f7ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 20px;">
                        <i class="fas fa-chart-line" style="color: #0066cc; font-size: 36px;"></i>
                    </div>
                    <div>
                        <h4 style="margin-top: 0;">About Interaction Analytics</h4>
                        <p style="color: #666;">Interaction Analytics enable you to keep track of important CMP KPIs to maximize data capture and help you understand how your CMP configuration choices impact user behavior.</p>
                        <a href="#" style="color: #0066cc; text-decoration: none; font-weight: 500;">Documentation</a><br>
                        <a href="#" style="color: #0066cc; text-decoration: none; font-weight: 500;">Interaction vs. Consent Analytics</a>
                    </div>
                </div>
            </div>
            
            <!-- User Acceptance Section -->
            <div class="section-card" style="margin-bottom: 30px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h3 style="margin: 0;">User Acceptance</h3>
                    <div style="display: flex; align-items: center;">
                        <div style="text-align: center; margin-right: 20px;">
                            <span style="color: #666; display: block; margin-bottom: 5px;">Accept All <i class="fas fa-info-circle" style="color: #ccc; font-size: 12px;"></i></span>
                            <span style="font-weight: 600; font-size: 18px;">--</span>
                        </div>
                        <div style="text-align: center; margin-right: 20px;">
                            <span style="color: #666; display: block; margin-bottom: 5px;">Deny All <i class="fas fa-info-circle" style="color: #ccc; font-size: 12px;"></i></span>
                            <span style="font-weight: 600; font-size: 18px;">--</span>
                        </div>
                        <div style="text-align: center; margin-right: 20px;">
                            <span style="color: #666; display: block; margin-bottom: 5px;">Custom <i class="fas fa-info-circle" style="color: #ccc; font-size: 12px;"></i></span>
                            <span style="font-weight: 600; font-size: 18px;">--</span>
                        </div>
                        <div style="text-align: center;">
                            <span style="color: #666; display: block; margin-bottom: 5px;">Accept Rate <i class="fas fa-info-circle" style="color: #ccc; font-size: 12px;"></i></span>
                            <span style="font-weight: 600; font-size: 18px; color: #0066cc;">--</span>
                        </div>
                    </div>
                </div>
                
                <p style="color: #666; margin-bottom: 20px;">User Acceptance Data enables you detailed insights into the type of interactions your users have. The Accept Rate indicates how likely users are willing to accept all Data Processing Services on your page.</p>
                
                <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                    <div style="flex: 1; border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <h4 style="margin: 0;">Accept vs. Deny vs. Custom (%)</h4>
                            <i class="fas fa-info-circle" style="color: #ccc; font-size: 16px;"></i>
                        </div>
                        
                        <div style="display: flex; justify-content: center; align-items: center; padding: 30px 0;">
                            <!-- Placeholder for pie chart -->
                            <div style="width: 200px; height: 200px; background-color: #f8f9fa; border-radius: 50%; position: relative; overflow: hidden;">
                                <div style="position: absolute; width: 180%; height: 180%; background-color: #0066cc; top: -30%; left: -90%; transform: rotate(30deg);"></div>
                                <div style="position: absolute; width: 40%; height: 40%; background-color: #ffcc00; top: 5%; right: 5%; border-radius: 50%;"></div>
                                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #fff; font-weight: 600;">86.12%</div>
                            </div>
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
                                <span style="width: 12px; height: 12px; background-color: #e9e9e9; display: inline-block; margin-right: 5px;"></span>
                                <span>Deny All</span>
                            </div>
                        </div>
                    </div>
                    
                    <div style="flex: 1; border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <h4 style="margin: 0;">Daily Accept Rate (%)</h4>
                            <i class="fas fa-info-circle" style="color: #ccc; font-size: 16px;"></i>
                        </div>
                        
                        <div style="height: 250px; position: relative;">
                            <!-- Placeholder for line chart -->
                            <div style="width: 100%; height: 180px; background-image: linear-gradient(to bottom, rgba(0,102,204,0.1), transparent); position: relative; margin-top: 30px;">
                                <div style="position: absolute; left: 0; right: 0; bottom: 0; height: 2px; background-color: #e9e9e9;"></div>
                                <svg width="100%" height="100%" style="position: absolute; top: 0; left: 0;">
                                    <polyline points="0,120 100,70 200,20 300,30 400,90" style="fill:none;stroke:#0066cc;stroke-width:2" />
                                </svg>
                            </div>
                            
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
                
                <div style="border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px; margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <h4 style="margin: 0;">Accept All vs. Deny All vs. Custom (Total)</h4>
                        <i class="fas fa-info-circle" style="color: #ccc; font-size: 16px;"></i>
                    </div>
                    
                    <div style="height: 250px; position: relative;">
                        <!-- Placeholder for bar chart -->
                        <div style="width: 100%; height: 180px; position: relative; margin-top: 30px;">
                            <div style="position: absolute; left: 0; right: 0; bottom: 0; height: 2px; background-color: #e9e9e9;"></div>
                            
                            <div style="display: flex; justify-content: space-around; align-items: flex-end; height: 100%;">
                                <div style="display: flex; flex-direction: column; align-items: center; width: 40px;">
                                    <div style="height: 5px; width: 30px; background-color: #e9e9e9;"></div>
                                    <div style="height: 10px; width: 30px; background-color: #ffcc00;"></div>
                                    <div style="height: 20px; width: 30px; background-color: #0066cc;"></div>
                                </div>
                                <div style="display: flex; flex-direction: column; align-items: center; width: 40px;">
                                    <div style="height: 10px; width: 30px; background-color: #e9e9e9;"></div>
                                    <div style="height: 15px; width: 30px; background-color: #ffcc00;"></div>
                                    <div style="height: 80px; width: 30px; background-color: #0066cc;"></div>
                                </div>
                                <div style="display: flex; flex-direction: column; align-items: center; width: 40px;">
                                    <div style="height: 10px; width: 30px; background-color: #e9e9e9;"></div>
                                    <div style="height: 20px; width: 30px; background-color: #ffcc00;"></div>
                                    <div style="height: 120px; width: 30px; background-color: #0066cc;"></div>
                                </div>
                                <div style="display: flex; flex-direction: column; align-items: center; width: 40px;">
                                    <div style="height: 5px; width: 30px; background-color: #e9e9e9;"></div>
                                    <div style="height: 10px; width: 30px; background-color: #ffcc00;"></div>
                                    <div style="height: 40px; width: 30px; background-color: #0066cc;"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div style="display: flex; justify-content: space-around; margin-top: 10px; color: #666; font-size: 12px;">
                            <span>2023-04-01</span>
                            <span>2023-04-04</span>
                            <span>2023-04-09</span>
                            <span>2023-04-10</span>
                        </div>
                    </div>
                </div>
                
                <div style="display: flex; margin-top: 30px;">
                    <div style="width: 100px; height: 100px; background-color: #f0f7ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 20px;">
                        <i class="fas fa-download" style="color: #0066cc; font-size: 36px;"></i>
                    </div>
                    <div>
                        <h4 style="margin-top: 0;">Interaction Analytics Reporting</h4>
                        <p style="color: #666;">Get even more insights into your users and their interactions by downloading the Interaction Analytics Reporting below. Upload the file to any data visualization tool (e.g. Google Data Studio) in order to create reports tailored to your needs.</p>
                        <button class="btn" style="background-color: #f0f7ff; color: #0066cc; font-weight: 500; margin-top: 10px;">
                            <i class="fas fa-download" style="margin-right: 5px;"></i> Download Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection