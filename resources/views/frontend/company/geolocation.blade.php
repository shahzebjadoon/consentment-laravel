@extends('frontend.company.layout')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <div>
        <h1 class="page-title">Geolocation Rulesets</h1>
        <p class="page-description">Create and manage Geolocation Rulesets for an international CMP setup.</p>
        {{-- <div class="premium-badge">
            <i class="fas fa-star"></i> Premium Feature
        </div> --}}
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="empty-state">
            <div class="empty-state-text">
                <h3 style="margin-bottom: 10px;">No ruleset created</h3>
                <p>Creating a Geolocation Ruleset enables you to define for which region a certain configuration should be displayed. Each Geolocation Ruleset consists of a <strong>Global Rule</strong> and one or more <strong>Regional Rules</strong> (if any).</p>
            </div>
            
            {{-- <div class="map-container" style="margin: 30px 0;">
                <img src="https://via.placeholder.com/800x300?text=World+Map+Visualization" alt="World Map" style="width: 100%; height: 100%; object-fit: cover;">
            </div> --}}
            
            <button class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i> Create Ruleset
            </button>
        </div>
    </div>
</div>
@endsection