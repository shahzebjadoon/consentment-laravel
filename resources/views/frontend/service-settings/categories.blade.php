@extends('frontend.company.layout')



@section('content')

<div class="card" style="margin-bottom: 30px; width: 100%;">

    <div class="card-header" style="border-bottom: none; padding-bottom: 0;">

        <h3 class="page-title">Service Settings <i class="" style="color: #ccc; font-size: 16px; vertical-align: middle;"></i></h3>

    </div>

    <div class="card-body" style="padding-top: 0;">

        <!-- Tabs Navigation -->

        <div style="display: flex; margin-bottom: 20px;">

            <a href="{{ route('frontend.service-settings.scanner', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 

               class="tab-link {{ $activeTab == 'scanner' ? 'active' : '' }}" 

               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; font-weight: 800;{{ $activeTab == 'scanner' ? 'background-color: white; color: #333; font-weight: 800;' : 'background-color: #f8f9fa; color: #666;' }} margin-right: 5px;">

               Data Processing Service Scanner

            </a>

            <a href="{{ route('frontend.service-settings.services', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 

               class="tab-link {{ $activeTab == 'services' ? 'active' : '' }}" 

               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; font-weight: 800;{{ $activeTab == 'services' ? 'background-color: white; color: #333; font-weight: 800;' : 'background-color: #f8f9fa; color: #666;' }} margin-right: 5px;">

                Data Processing Services Detected

            </a>

            <a href="{{ route('frontend.service-settings.categories', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 

               class="tab-link {{ $activeTab == 'categories' ? 'active' : '' }}" 

               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none;font-weight: 800; {{ $activeTab == 'categories' ? 'background-color: white; color: #333; font-weight: 800;' : 'background-color: #f8f9fa; color: #666;' }}">

                Categories

            </a>

        </div>

        <div style="border-top: 1px solid #dee2e6; margin-top: -1px;"></div>



        <!-- Categories Content -->

        <div class="tab-content">

            <div style="margin-top: 20px;">

                <h3 style="margin-bottom: 20px;">Categories <i class="" style="color: #ccc; font-size: 16px; vertical-align: middle;"></i></h3>

                

                <!-- Categories List -->

                <div class="categories-list">

                    @forelse($categories as $category)

                    <div class="category-item">

                        <div class="category-header">

                            <div class="category-name">

                                {{ $category->name }}

                                @if($category->is_essential)

                                <span class="essential-badge">Essential Category</span>

                                @endif

                            </div>

                            <div class="category-controls">

                                <button class="btn-toggle">

                                    <i class="fas fa-chevron-down"></i>

                                </button>

                            </div>

                        </div>

                        

                        <div class="category-details">

                            <div class="detail-row">

                                <div class="detail-group">

                                    <label>Name <i class=""></i></label>

                                    <div class="input-with-action">

                                        <input type="text" class="form-control" value="{{ $category->name }}">

                                        <button class="btn-edit-translate" data-category-id="{{ $category->id }}" data-field="name">

                                            <i class="fas fa-edit"></i> Edit & Translate

                                        </button>

                                    </div>

                                    <div class="default-identifier">Default Category Identifier: {{ $category->identifier }}</div>

                                </div>

                            </div>

                            

                            <div class="detail-row">

                                <div class="detail-group">

                                    <label>Description</label>

                                    <div class="input-with-action">

                                        <textarea class="form-control">{{ $category->description }}</textarea>

                                        <button class="btn-edit-translate" data-category-id="{{ $category->id }}" data-field="description">

                                            <i class="fas fa-edit"></i> Edit & Translate

                                        </button>

                                    </div>

                                </div>

                            </div>

                            

                            <div class="detail-row">

                                <div class="detail-group">

                                    <label>Settings</label>

                                    <div class="settings-options">

                                        <div class="checkbox-group">

                                            <input type="checkbox" id="mark-essential-{{ $category->id }}" 

                                                {{ $category->is_essential ? 'checked' : '' }}>

                                            <label for="mark-essential-{{ $category->id }}">Mark category as essential <i class=""></i></label>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            

                            <div class="detail-row">

                                <div class="detail-group">

                                    <div class="data-processing-header">

                                        <div class="data-processing-title">

                                            Data Processing Services

                                            <span class="service-count">{{ count($category->servicesList) }}</span>

                                        </div>

                                        <button class="btn-show-dps" id="toggleDps{{ $category->id }}">

                                            Show DPS

                                        </button>

                                    </div>

                                    

                                    <div class="data-processing-list hidden" id="dpsList{{ $category->id }}">

                                        @if(count($category->servicesList) > 0)

                                            <ul>

                                                @foreach($category->servicesList as $service)

                                                    <li class="service-item">

                                                        <span class="service-name">{{ $service->name }}</span>

                                                    </li>

                                                @endforeach

                                            </ul>

                                        @else

                                            <p class="no-services">No services in this category</p>

                                        @endif

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    @empty

                    <div style="text-align: center; padding: 20px; color: #666; border: 1px dashed #ddd; border-radius: 6px; margin-bottom: 15px;">

                        <p>No categories defined yet.</p>

                    </div>

                    @endforelse

                </div>

                

                <!-- Add Category Button -->

                <button class="btn-add-category">

                    <i class="fas fa-plus"></i> Add Category

                </button>

            </div>

        </div>

    </div>

</div>

@endsection



@section('scripts')

<style>

    /* Category Item Styling */

    .categories-list {

        margin-bottom: 20px;

    }

    

    .category-item {

        border: 1px solid #e6e8eb;

        border-radius: 6px;

        margin-bottom: 15px;

        overflow: hidden;

    }

    

    .category-header {

        display: flex;

        justify-content: space-between;

        align-items: center;

        padding: 15px 20px;

        background-color: #fff;

        cursor: pointer;

    }

    

    .category-name {

        font-size: 16px;

        font-weight: 500;

        display: flex;

        align-items: center;

        gap: 10px;

    }

    

    .essential-badge {

        font-size: 12px;

        font-weight: normal;

        background-color: #e8f5e9;

        color: #2e7d32;

        padding: 3px 8px;

        border-radius: 4px;

    }

    

    .category-controls {

        display: flex;

        align-items: center;

    }

    

    .btn-toggle {

        background: none;

        border: none;

        cursor: pointer;

        width: 30px;

        height: 30px;

        display: flex;

        align-items: center;

        justify-content: center;

        color: #666;

        border-radius: 4px;

    }

    

    .btn-toggle:hover {

        background-color: #f5f5f5;

    }

    

    /* Category Details */

    .category-details {

        padding: 0 20px 20px;

        border-top: 1px solid #e6e8eb;

        background-color: #f8f9fa;

        display: none;

    }

    

    .category-item.expanded .category-details {

        display: block;

    }

    

    .detail-row {

        padding: 15px 0;

        border-bottom: 1px solid #e6e8eb;

    }

    

    .detail-row:last-child {

        border-bottom: none;

    }

    

    .detail-group {

        display: flex;

        flex-direction: column;

        gap: 8px;

    }

    

    .detail-group label {

        font-size: 14px;

        color: #333;

        font-weight: 500;

        display: flex;

        align-items: center;

        gap: 5px;

    }

    

    .detail-group label i {

        color: #ccc;

        font-size: 14px;

    }

    

    .input-with-action {

        display: flex;

        align-items: flex-start;

        gap: 10px;

    }

    

    .form-control {

        flex: 1;

        padding: 10px 15px;

        border: 1px solid #ddd;

        border-radius: 4px;

        font-size: 14px;

    }

    

    textarea.form-control {

        min-height: 100px;

        resize: vertical;

    }

    

    .btn-edit-translate {

        background: none;

        border: none;

        color: #0066cc;

        cursor: pointer;

        display: flex;

        align-items: center;

        gap: 5px;

        font-size: 14px;

    }

    

    .default-identifier {

        font-size: 12px;

        color: #666;

        margin-top: 5px;

    }

    

    /* Settings Options */

    .settings-options {

        display: flex;

        flex-direction: column;

        gap: 12px;

    }

    

    .checkbox-group {

        display: flex;

        align-items: center;

        gap: 8px;

    }

    

    .checkbox-group label {

        font-weight: 400;

        font-size: 14px;

    }

    

    /* Data Processing Services */

    .data-processing-header {

        display: flex;

        justify-content: space-between;

        align-items: center;

        margin-bottom: 10px;

    }

    

    .data-processing-title {

        font-weight: 500;

        font-size: 14px;

        display: flex;

        align-items: center;

        gap: 10px;

    }

    

    .service-count {

        background-color: #e9ecef;

        color: #495057;

        font-size: 12px;

        padding: 2px 6px;

        border-radius: 10px;

    }

    

    .btn-hide-dps, .btn-show-dps {

        background: none;

        border: none;

        color: #0066cc;

        cursor: pointer;

        font-size: 14px;

    }

    

    .data-processing-list {

        background-color: white;

        border: 1px solid #ddd;

        border-radius: 4px;

        padding: 10px;

    }

    

    .data-processing-list.hidden {

        display: none;

    }

    

    .data-processing-list ul {

        list-style-type: disc;

        margin: 0 0 0 20px;

        padding: 0;

    }

    

    .data-processing-list li {

        font-size: 14px;

        margin-bottom: 5px;

    }

    

    /* Add Category Button */

    .btn-add-category {

        margin-top: 20px;

        padding: 10px 15px;

        border: 1px solid #ddd;

        border-radius: 4px;

        background-color: white;

        font-size: 14px;

        cursor: pointer;

        display: flex;

        align-items: center;

        gap: 8px;

        color: #333;

    }

    

    /* Service styling */

    .service-item {

        display: flex;

        align-items: center;

    }

    

    .no-services {

        color: #6c757d;

        font-style: italic;

        margin: 0;

        padding: 5px 0;

    }

</style>



<script>

   // Toggle category details

document.querySelectorAll('.category-header').forEach(function(header) {

    header.addEventListener('click', function() {

        const item = this.closest('.category-item');

        const isExpanded = item.classList.contains('expanded');

        

        // Toggle current item

        if (isExpanded) {

            item.classList.remove('expanded');

            const toggleBtn = item.querySelector('.btn-toggle i');

            toggleBtn.classList.remove('fa-chevron-up');

            toggleBtn.classList.add('fa-chevron-down');

        } else {

            item.classList.add('expanded');

            const toggleBtn = item.querySelector('.btn-toggle i');

            toggleBtn.classList.remove('fa-chevron-down');

            toggleBtn.classList.add('fa-chevron-up');

        }

    });

});



// Toggle DPS lists - dynamic approach

document.querySelectorAll('[id^="toggleDps"]').forEach(function(btn) {

    btn.addEventListener('click', function(e) {

        e.stopPropagation();

        const categoryId = this.id.replace('toggleDps', '');

        const dpsList = document.getElementById('dpsList' + categoryId);

        const isHidden = dpsList.classList.contains('hidden');

        

        if (isHidden) {

            dpsList.classList.remove('hidden');

            this.textContent = 'Hide DPS';

            this.classList.remove('btn-show-dps');

            this.classList.add('btn-hide-dps');

        } else {

            dpsList.classList.add('hidden');

            this.textContent = 'Show DPS';

            this.classList.remove('btn-hide-dps');

            this.classList.add('btn-show-dps');

        }

    });

});



// Handle checkbox changes for essential status

document.querySelectorAll('[id^="mark-essential-"]').forEach(function(checkbox) {

    checkbox.addEventListener('change', function() {

        const categoryId = this.id.replace('mark-essential-', '');

        const isChecked = this.checked;

        

        // Here you would normally make an AJAX call to update the essential status

        console.log('Category ' + categoryId + ' essential status changed to: ' + isChecked);

        

        // Visual feedback

        const categoryHeader = this.closest('.category-item').querySelector('.category-name');

        if (isChecked) {

            if (!categoryHeader.querySelector('.essential-badge')) {

                const badge = document.createElement('span');

                badge.className = 'essential-badge';

                badge.textContent = 'Essential Category';

                categoryHeader.appendChild(badge);

            }

        } else {

            const badge = categoryHeader.querySelector('.essential-badge');

            if (badge) {

                badge.remove();

            }

        }

    });

});



// Edit & Translate buttons

document.querySelectorAll('.btn-edit-translate').forEach(function(btn) {

    btn.addEventListener('click', function(e) {

        e.preventDefault();

        const categoryId = this.getAttribute('data-category-id');

        const field = this.getAttribute('data-field');

        

        // Here you would normally open a modal or navigate to translation page

        console.log('Edit & Translate clicked for category ' + categoryId + ', field: ' + field);

    });

});



// Add category button

document.querySelector('.btn-add-category').addEventListener('click', function() {

    // Here you would normally open a modal or navigate to add category page

    console.log('Add category clicked');

});

</script>

@endsection