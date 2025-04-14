@extends('frontend.company.layout')

@section('content')
<div class="card" style="margin-bottom: 30px; width: 100%;">
    <div class="card-header" style="border-bottom: none; padding-bottom: 0;">
        <h3 class="page-title">Implementation <i class="fas fa-info-circle" style="color: #ccc; font-size: 16px; vertical-align: middle;"></i></h3>
    </div>
    <div class="card-body" style="padding-top: 0;">
        <!-- Tabs Navigation -->
        <div style="display: flex; margin-bottom: 20px;">
            <a href="{{ route('frontend.implementation.script-tag', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 
               class="tab-link {{ $activeTab == 'script-tag' ? 'active' : '' }}" 
               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; {{ $activeTab == 'script-tag' ? 'background-color: white; color: #333; font-weight: 500;' : 'background-color: #f8f9fa; color: #666;' }} margin-right: 5px;">
                Script Tag
            </a>
            <a href="{{ route('frontend.implementation.embeddings', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 
               class="tab-link {{ $activeTab == 'embeddings' ? 'active' : '' }}" 
               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; {{ $activeTab == 'embeddings' ? 'background-color: white; color: #333; font-weight: 500;' : 'background-color: #f8f9fa; color: #666;' }} margin-right: 5px;">
                Embeddings
            </a>
            <a href="{{ route('frontend.implementation.data-layer', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 
               class="tab-link {{ $activeTab == 'data-layer' ? 'active' : '' }}" 
               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; {{ $activeTab == 'data-layer' ? 'background-color: white; color: #333; font-weight: 500;' : 'background-color: #f8f9fa; color: #666;' }} margin-right: 5px;">
                Data Layer & Events
            </a>
            <a href="{{ route('frontend.implementation.ab-testing', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 
               class="tab-link {{ $activeTab == 'ab-testing' ? 'active' : '' }}" 
               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; {{ $activeTab == 'ab-testing' ? 'background-color: white; color: #333; font-weight: 500;' : 'background-color: #f8f9fa; color: #666;' }}">
                A/B Testing
            </a>
        </div>
        <div style="border-top: 1px solid #dee2e6; margin-top: -1px;"></div>

        <!-- Data Layer & Events Content (Placeholder) -->
        <div class="tab-content">
            <div class="empty-state" style="padding: 50px 0; text-align: center;">
                <div style="margin-bottom: 20px;">
                    <i class="fas fa-layer-group" style="font-size: 48px; color: #ddd;"></i>
                </div>
                <h4>Data Layer & Events</h4>
                <p>This feature will be implemented later.</p>
            </div>
        </div>
    </div>
</div>
@endsection