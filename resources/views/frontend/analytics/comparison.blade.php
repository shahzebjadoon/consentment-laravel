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
            
            <!-- Comparison Content -->
            <div style="margin-top: 30px;">
                <h3 style="margin-bottom: 20px;">Interaction Analytics Comparison <i class="fas fa-info-circle" style="color: #ccc; font-size: 16px; vertical-align: middle;"></i></h3>
                
                <!-- Country Analytics Section -->
                <div style="border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px; margin-bottom: 30px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <h4 style="margin: 0;">Country Analytics (%) <i class="fas fa-info-circle" style="color: #ccc; font-size: 14px;"></i></h4>
                    </div>
                    
                    <!-- Germany -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span style="font-weight: 500;">Germany</span>
                            <span style="color: #666;">Displays</span>
                        </div>
                        <div style="margin-bottom: 10px;">
                            <div style="display: flex; align-items: center; margin-bottom: 5px;">
                                <span style="width: 90px; color: #666; font-size: 12px;">Interaction Rate</span>
                                <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;">
                                    <div style="position: absolute; height: 100%; background-color: #ff6a00; width: 85%;"></div>
                                </div>
                            </div>
                            <div style="display: flex; align-items: center;">
                                <span style="width: 90px; color: #666; font-size: 12px;">Accept Rate</span>
                                <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;">
                                    <div style="position: absolute; height: 100%; background-color: #0066cc; width: 70%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- United Kingdom -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span style="font-weight: 500;">United Kingdom</span>
                            <span style="color: #666;">Displays</span>
                        </div>
                        <div style="margin-bottom: 10px;">
                            <div style="display: flex; align-items: center; margin-bottom: 5px;">
                                <span style="width: 90px; color: #666; font-size: 12px;">Interaction Rate</span>
                                <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;">
                                    <div style="position: absolute; height: 100%; background-color: #ff6a00; width: 80%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- United States -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span style="font-weight: 500;">United States</span>
                            <span style="color: #666;">Displays</span>
                        </div>
                    </div>
                    
                    <!-- Ireland -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span style="font-weight: 500;">Ireland</span>
                            <span style="color: #666;">Displays</span>
                        </div>
                        <div style="margin-bottom: 10px;">
                            <div style="display: flex; align-items: center; margin-bottom: 5px;">
                                <span style="width: 90px; color: #666; font-size: 12px;">Interaction Rate</span>
                                <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;">
                                    <div style="position: absolute; height: 100%; background-color: #ff6a00; width: 65%;"></div>
                                </div>
                            </div>
                            <div style="display: flex; align-items: center;">
                                <span style="width: 90px; color: #666; font-size: 12px;">Accept Rate</span>
                                <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;">
                                    <div style="position: absolute; height: 100%; background-color: #0066cc; width: 60%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- France -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span style="font-weight: 500;">France</span>
                            <span style="color: #666;">Displays</span>
                        </div>
                        <div style="margin-bottom: 10px;">
                            <div style="display: flex; align-items: center; margin-bottom: 5px;">
                                <span style="width: 90px; color: #666; font-size: 12px;">Interaction Rate</span>
                                <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;">
                                    <div style="position: absolute; height: 100%; background-color: #ff6a00; width: 50%;"></div>
                                </div>
                            </div>
                            <div style="display: flex; align-items: center;">
                                <span style="width: 90px; color: #666; font-size: 12px;">Accept Rate</span>
                                <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;">
                                    <div style="position: absolute; height: 100%; background-color: #0066cc; width: 55%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Japan -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span style="font-weight: 500;">Japan</span>
                            <span style="color: #666;">Displays</span>
                        </div>
                    </div>
                    
                    <!-- Poland -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span style="font-weight: 500;">Poland</span>
                            <span style="color: #666;">Displays</span>
                        </div>
                        <div style="margin-bottom: 10px;">
                            <div style="display: flex; align-items: center; margin-bottom: 5px;">
                                <span style="width: 90px; color: #666; font-size: 12px;">Interaction Rate</span>
                                <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;"></div>
                            </div>
                            <div style="display: flex; align-items: center;">
                                <span style="width: 90px; color: #666; font-size: 12px;">Accept Rate</span>
                                <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Device and Layer Analytics Sections - Side by side -->
                <div style="display: flex; gap: 20px; margin-bottom: 30px;">
                    <!-- Device Analytics Section -->
                    <div style="flex: 1; border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <h4 style="margin: 0;">Device Analytics (%) <i class="fas fa-info-circle" style="color: #ccc; font-size: 14px;"></i></h4>
                        </div>
                        
                        <!-- Mobile -->
                        <div style="margin-bottom: 25px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <span style="font-weight: 500;">Mobile</span>
                                <span style="color: #666;">Displays</span>
                            </div>
                            <div style="margin-bottom: 10px;">
                                <div style="display: flex; align-items: center; margin-bottom: 5px;">
                                    <span style="width: 90px; color: #666; font-size: 12px;">Interaction Rate</span>
                                    <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;">
                                        <div style="position: absolute; height: 100%; background-color: #ff6a00; width: 85%;"></div>
                                    </div>
                                </div>
                                <div style="display: flex; align-items: center;">
                                    <span style="width: 90px; color: #666; font-size: 12px;">Accept Rate</span>
                                    <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;">
                                        <div style="position: absolute; height: 100%; background-color: #0066cc; width: 75%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Desktop -->
                        <div style="margin-bottom: 25px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <span style="font-weight: 500;">Desktop</span>
                                <span style="color: #666;">Displays</span>
                            </div>
                            <div style="margin-bottom: 10px;">
                                <div style="display: flex; align-items: center; margin-bottom: 5px;">
                                    <span style="width: 90px; color: #666; font-size: 12px;">Interaction Rate</span>
                                    <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;">
                                        <div style="position: absolute; height: 100%; background-color: #ff6a00; width: 80%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Layer Analytics Section -->
                    <div style="flex: 1; border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <h4 style="margin: 0;">Layer Analytics (%) <i class="fas fa-info-circle" style="color: #ccc; font-size: 14px;"></i></h4>
                        </div>
                        
                        <!-- First Layer -->
                        <div style="margin-bottom: 25px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <span style="font-weight: 500;">First Layer</span>
                                <span style="color: #666;">Interactions</span>
                            </div>
                            <div style="margin-bottom: 10px;">
                                <div style="display: flex; align-items: center;">
                                    <span style="width: 90px; color: #666; font-size: 12px;">Accept Rate</span>
                                    <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;">
                                        <div style="position: absolute; height: 100%; background-color: #0066cc; width: 65%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Second Layer -->
                        <div style="margin-bottom: 25px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <span style="font-weight: 500;">Second Layer</span>
                                <span style="color: #666;">Interactions</span>
                            </div>
                            <div style="margin-bottom: 10px;">
                                <div style="display: flex; align-items: center;">
                                    <span style="width: 90px; color: #666; font-size: 12px;">Accept Rate</span>
                                    <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- OS Analytics Section -->
                <div style="border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px; margin-bottom: 30px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <h4 style="margin: 0;">OS Analytics (%) <i class="fas fa-info-circle" style="color: #ccc; font-size: 14px;"></i></h4>
                    </div>
                    
                    <!-- Windows Mobile -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span style="font-weight: 500;">Windows Mobile</span>
                            <span style="color: #666;">1 Display</span>
                        </div>
                        <div style="margin-bottom: 10px;">
                            <div style="display: flex; align-items: center; margin-bottom: 5px;">
                                <span style="width: 90px; color: #666; font-size: 12px;">Interaction Rate</span>
                                <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;"></div>
                            </div>
                            <div style="display: flex; align-items: center;">
                                <span style="width: 90px; color: #666; font-size: 12px;">Accept Rate</span>
                                <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Android -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span style="font-weight: 500;">Android</span>
                            <span style="color: #666;">16 Displays</span>
                        </div>
                        <div style="margin-bottom: 10px;">
                            <div style="display: flex; align-items: center; margin-bottom: 5px;">
                                <span style="width: 90px; color: #666; font-size: 12px;">Interaction Rate</span>
                                <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;">
                                    <div style="position: absolute; height: 100%; background-color: #ff6a00; width: 70%;"></div>
                                </div>
                            </div>
                            <div style="display: flex; align-items: center;">
                                <span style="width: 90px; color: #666; font-size: 12px;">Accept Rate</span>
                                <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;">
                                    <div style="position: absolute; height: 100%; background-color: #0066cc; width: 60%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Linux -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span style="font-weight: 500;">Linux</span>
                            <span style="color: #666;">104 Displays</span>
                        </div>
                        <div style="margin-bottom: 10px;">
                            <div style="display: flex; align-items: center; margin-bottom: 5px;">
                                <span style="width: 90px; color: #666; font-size: 12px;">Interaction Rate</span>
                                <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;">
                                    <div style="position: absolute; height: 100%; background-color: #ff6a00; width: 85%;"></div>
                                </div>
                            </div>
                            <div style="display: flex; align-items: center;">
                                <span style="width: 90px; color: #666; font-size: 12px;">Accept Rate</span>
                                <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;">
                                    <div style="position: absolute; height: 100%; background-color: #0066cc; width: 80%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mac OS -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span style="font-weight: 500;">Mac OS</span>
                            <span style="color: #666;">21 Displays</span>
                        </div>
                        <div style="margin-bottom: 10px;">
                            <div style="display: flex; align-items: center; margin-bottom: 5px;">
                                <span style="width: 90px; color: #666; font-size: 12px;">Interaction Rate</span>
                                <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;">
                                    <div style="position: absolute; height: 100%; background-color: #ff6a00; width: 60%;"></div>
                                </div>
                            </div>
                            <div style="display: flex; align-items: center;">
                                <span style="width: 90px; color: #666; font-size: 12px;">Accept Rate</span>
                                <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;">
                                    <div style="position: absolute; height: 100%; background-color: #0066cc; width: 55%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Windows -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span style="font-weight: 500;">Windows</span>
                            <span style="color: #666;">80 Displays</span>
                        </div>
                        <div style="margin-bottom: 10px;">
                            <div style="display: flex; align-items: center; margin-bottom: 5px;">
                                <span style="width: 90px; color: #666; font-size: 12px;">Interaction Rate</span>
                                <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;">
                                    <div style="position: absolute; height: 100%; background-color: #ff6a00; width: 55%;"></div>
                                </div>
                            </div>
                            <div style="display: flex; align-items: center;">
                                <span style="width: 90px; color: #666; font-size: 12px;">Accept Rate</span>
                                <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;">
                                    <div style="position: absolute; height: 100%; background-color: #0066cc; width: 45%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- iOS -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span style="font-weight: 500;">iOS</span>
                            <span style="color: #666;">26 Displays</span>
                        </div>
                        <div style="margin-bottom: 10px;">
                            <div style="display: flex; align-items: center; margin-bottom: 5px;">
                                <span style="width: 90px; color: #666; font-size: 12px;">Interaction Rate</span>
                                <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;">
                                    <div style="position: absolute; height: 100%; background-color: #ff6a00; width: 75%;"></div>
                                </div>
                            </div>
                            <div style="display: flex; align-items: center;">
                                <span style="width: 90px; color: #666; font-size: 12px;">Accept Rate</span>
                                <div style="flex-grow: 1; height: 16px; background-color: #f2f2f2; border-radius: 4px; overflow: hidden; position: relative;">
                                    <div style="position: absolute; height: 100%; background-color: #0066cc; width: 65%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Download Report Section -->
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