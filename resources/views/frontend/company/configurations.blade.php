@extends('frontend.company.layout')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <div>
        <h1 class="page-title">Domain Settings</h1>
        <p class="page-description">Below you will find an overview of all domains which are assigned to this company.</p>
    </div>
    <button class="btn btn-primary" id="addConfigBtn">
        <i class="fas fa-plus mr-2"></i> &nbsp; Add Domain 
    </button>
</div>

<div class="search-bar">
    <i class="fas fa-search search-icon"></i>
    <input type="text" class="search-input" placeholder="Search for configuration by All parameter">
</div>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Compliance Name [ID]</th>
                <th>Domain / App-ID</th>
                <th>Data Controller</th>
                <th>Framework</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @if(count($configurations) > 0)
                @foreach($configurations as $config)
                <tr   style="cursor: pointer;" onclick="window.location='{{ route('frontend.analytics.index', ['company_id' => $company->id, 'config_id' => $config->id]) }}'">
                    <td>
                        <div style="display: flex; align-items: center;">
                            <i class="fas fa-cog mr-2" style="margin-right: 10px;"></i>
                            {{ $config->name ?? 'GDPR configuration #1' }}
                        </div>
                    </td>
                    <td>{{ $config->domain ?? 'm-club.co.uk' }}</td>
                    <td>{{ $config->data_controller ?? '' }}</td>
                    <td>
                        <span class="company-badge admin">{{ $config->framework_type ?? 'GDPR' }}</span>
                    </td>
                    <td>
                        <div class="table-actions">
                                {{-- <a href="{{ route('frontend.configurations.edit', ['company_id' => $company->id, 'config_id' => $config->id]) }}" class="action-icon">
                                    <i class="fas fa-pencil-alt"></i>
                                </a> --}}
                                {{-- <span class="action-icon">
                                    <i class="fas fa-ellipsis-v"></i>
                                </span> --}}
                            </div>
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div class="empty-state-text">
                                <p>No domain found. Click "Add Domain" to create your first configuration.</p>
                            </div>
                            <button class="btn btn-primary" id="emptyStateAddBtn">Add Configuration</button>
                        </div>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

<!-- Add Configuration Modal with multi-step form -->
<div id="configurationModal" class="modal">
    <div class="modal-content" style="max-width: 700px; margin-top:-50px !important;">
        <div class="modal-header">
            <h3>Add New Domain</h3>
            <span class="close" id="closeModal">&times;</span>
        </div>
        <form id="configForm" action="{{ route('frontend.configurations.store', $company->id) }}" method="POST">
            @csrf
            <div class="modal-body">
                <!-- Step 1: Framework Type -->
<div class="config-step" id="step1">
    <!-- Warning Banner (only in step 1) -->
    <div style="background-color: #fff8e1; border-radius: 5px; padding: 15px; margin-bottom: 20px; display: flex; align-items: center;">
        <div style="background-color: #ffd600; width: 60px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
            <i class="fas fa-bolt" style="color: white;"></i>
        </div>
        <div>
            <h4 style="margin: 0; margin-bottom: 5px; font-size: 16px;">Maximum number of configurations reached</h4>
            <p style="margin: 0; color: #666;">You reached maximum number of 1 configuration, unlock full access by upgrading your account.</p>
        </div>
        <button type="button" class="btn btn-primary" style="margin-left: auto;">Upgrade</button>
    </div>
    
    <h4 style="margin-top: 0; margin-bottom: 5px; font-size: 20px;">Compliance Type</h4>
    <p style="color: #666; margin-bottom: 20px; font-size: 20px;">Select the legal compliance your configuration should support. Depending on your choice, there will be a default setup prepared including all required features to be compliant with the selected framework.</p>
    
    <div class="framework-options">
        @foreach($frameworks as $framework)
        <div class="framework-option" data-framework="{{ $framework->code }}" data-region="{{ $framework->region }}">
            <div style="display: flex; align-items: center;">
                <img src="{{ $framework->logo }}" alt="{{ $framework->name }} Logo" style="width: 40px; height: 30px; margin-right: 15px;">
                <div>
                    <h5 style="margin: 0; font-size: 16px;">{{ strtoupper($framework->code) }}</h5>
                    <p style="margin: 0; color: #666; font-size: 13px;">{{ $framework->name }}</p>
                </div>
            </div>
            <div style="display: flex; align-items: center;">
                <span style="margin-right: 10px; color: #1da1f2;">{{ $framework->region }}</span>
                <div class="radio-button"></div>
            </div>
        </div>
        @endforeach
    </div>
    
    <input type="hidden" name="framework_type" id="framework_type" value="">
    <input type="hidden" name="framework_region" id="framework_region" value="">
</div>

<style>
.framework-options {
    display: flex;
    flex-direction: column;
    gap: 10px;
    max-height: 250px; /* Shows approximately 3 frameworks */
    overflow-y: auto;
    padding-right: 5px; /* Ensures content doesn't touch scrollbar */
}

.framework-option {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    transition: all 0.2s;
    min-height: 80px; /* Ensure consistent height */
}

.framework-option:hover {
    border-color: #1da1f2;
    background-color: rgba(29, 161, 242, 0.05);
}

.framework-option.selected {
    border-color: #1da1f2;
    background-color: rgba(29, 161, 242, 0.05);
}

.radio-button {
    width: 20px;
    height: 20px;
    border: 2px solid #ddd;
    border-radius: 50%;
    position: relative;
}

.framework-option.selected .radio-button {
    border-color: #1da1f2;
}

.framework-option.selected .radio-button:after {
    content: "";
    position: absolute;
    width: 12px;
    height: 12px;
    background-color: #1da1f2;
    border-radius: 50%;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

/* Custom scrollbar styling for better appearance */
.framework-options::-webkit-scrollbar {
    width: 8px;
}

.framework-options::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.framework-options::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.framework-options::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
                
                <!-- Step 2: Add Domain -->
                <div class="config-step" id="step2" style="display: none;">
                    <div class="form-group">
                        <label class="form-label" style="font-size: 20px;">Compliance Name</label>
                        <input type="text" class="form-control" name="name" id="configuration_name" style="font-size: 20px;" placeholder="Enter configuration name" required>
                    </div>

                    <h4 style="margin-top: 20px; margin-bottom: 5px; font-size: 20px;">Add Domain</h4>
                    <p style="color: #666; margin-bottom: 20px; font-size:20px;">Select or add domains for this configuration. Domains added will be scanned for Data Processing Services. Once you integrated the consent banner script tag your consent banner will be displayed.</p>
                    
                    <div style="background-color: #f8f9fa; border-radius: 5px; padding: 20px; margin-bottom: 20px;">
                        <div style="margin-bottom: 10px; font-weight: 500; font-size:20px;">Domain</div>
                        <div style="display: flex;">
                            <div style="background-color: #e9ecef; padding: 12px 15px; border-radius: 8px 0 0 8px; border: 1px solid #ddd; font-size:20px; border-right: none;">https://</div>
                            <input type="text" class="form-control" id="domain_input" placeholder="www.example.com" style="border-radius: 0 8px 8px 0; flex-grow: 1; font-size:20px;">
                            <button type="button" class="btn btn-primary" id="add_domain_btn" style="margin-left: 10px; font-size:20px;">Add</button>
                        </div>
                    </div>
                    
                    <div style="border: 1px solid #ddd; border-radius: 8px; padding: 15px; margin-bottom: 20px;">
                        <h5 style="margin-top: 0; margin-bottom: 10px;">Domain Overview <span style="background-color: #e9ecef; color: #666; padding: 2px 8px; border-radius: 10px; font-size: 12px;">0/1</span></h5>
                        <div id="domains_list" style="min-height: 50px;">
                            <!-- Domains will be added here -->
                        </div>
                    </div>
                    
                    <input type="hidden" name="domain" id="domain" value="">
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="backBtn" style="display: none; font-size:20px;">Back</button>
                <button type="button" class="btn btn-secondary" id="cancelBtn" style="font-size: 20px;">Cancel</button>
                <button type="button" class="btn btn-primary" id="nextBtn" style="font-size: 20px;">Next</button>
                <button type="submit" class="btn btn-primary" id="saveBtn" style="display: none; font-size:20px">Save</button>
            </div>
        </form>
    </div>
</div>

<style>
.framework-options {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.framework-option {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    transition: all 0.2s;
}

.framework-option:hover {
    border-color: #1da1f2;
    background-color: rgba(29, 161, 242, 0.05);
}

.framework-option.selected {
    border-color: #1da1f2;
    background-color: rgba(29, 161, 242, 0.05);
}

.radio-button {
    width: 20px;
    height: 20px;
    border: 2px solid #ddd;
    border-radius: 50%;
    position: relative;
}

.framework-option.selected .radio-button {
    border-color: #1da1f2;
}

.framework-option.selected .radio-button:after {
    content: "";
    position: absolute;
    width: 12px;
    height: 12px;
    background-color: #1da1f2;
    border-radius: 50%;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
</style>
@endsection

@section('scripts')
<script>
    // Modal functionality
    const configModal = document.getElementById('configurationModal');
    const addConfigBtn = document.getElementById('addConfigBtn');
    const closeModal = document.getElementById('closeModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const nextBtn = document.getElementById('nextBtn');
    const backBtn = document.getElementById('backBtn');
    const saveBtn = document.getElementById('saveBtn');
    
    // Make sure these buttons exist before adding event listeners
    if (addConfigBtn) {
        addConfigBtn.addEventListener('click', function() {
            configModal.style.display = 'block';
            setTimeout(() => {
                configModal.classList.add('show');
                showStep(1);
            }, 10);
        });
    }
    
    const emptyStateAddBtn = document.getElementById('emptyStateAddBtn');
    if (emptyStateAddBtn) {
        emptyStateAddBtn.addEventListener('click', function() {
            configModal.style.display = 'block';
            setTimeout(() => {
                configModal.classList.add('show');
                showStep(1);
            }, 10);
        });
    }
    
    // Step navigation
    let currentStep = 1;
    const totalSteps = 2;
    
    function showStep(step) {
        // Hide all steps
        document.querySelectorAll('.config-step').forEach(el => {
            el.style.display = 'none';
        });
        
        // Show current step
        document.getElementById('step' + step).style.display = 'block';
        
        // Update buttons
        if (step === 1) {
            backBtn.style.display = 'none';
            nextBtn.style.display = 'inline-block';
            saveBtn.style.display = 'none';
        } else if (step === totalSteps) {
            backBtn.style.display = 'inline-block';
            nextBtn.style.display = 'none';
            saveBtn.style.display = 'inline-block';
        }
        
        currentStep = step;
    }
    
    // Close modal
    function closeModalFunc() {
        configModal.classList.remove('show');
        setTimeout(() => {
            configModal.style.display = 'none';
            // Reset form
            document.getElementById('configForm').reset();
            document.querySelectorAll('.framework-option').forEach(opt => opt.classList.remove('selected'));
            domains = [];
            updateDomainsList();
        }, 300);
    }
    
    if (closeModal) {
        closeModal.addEventListener('click', closeModalFunc);
    }
    
    if (cancelBtn) {
        cancelBtn.addEventListener('click', closeModalFunc);
    }
    
    // Framework selection
    const frameworkOptions = document.querySelectorAll('.framework-option');
    frameworkOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove selection from all options
            frameworkOptions.forEach(opt => opt.classList.remove('selected'));
            
            // Add selection to clicked option
            this.classList.add('selected');
            
            // Update hidden fields
            document.getElementById('framework_type').value = this.getAttribute('data-framework');
            document.getElementById('framework_region').value = this.getAttribute('data-region');
        });
    });
    
    // Domain handling
    const addDomainBtn = document.getElementById('add_domain_btn');
    const domainInput = document.getElementById('domain_input');
    const domainsList = document.getElementById('domains_list');
    let domains = [];
    
    if (addDomainBtn && domainInput && domainsList) {
        addDomainBtn.addEventListener('click', function() {
            const domain = domainInput.value.trim();
            if (domain) {
                if (!domains.includes(domain)) {
                    domains.push(domain);
                    updateDomainsList();
                    domainInput.value = '';
                    document.getElementById('domain').value = domains.join(',');
                }
            }
        });
    }
    
    function updateDomainsList() {
        if (!domainsList) return;
        
        domainsList.innerHTML = '';
        
        if (domains.length === 0) {
            return;
        }
        
        domains.forEach(domain => {
            const domainItem = document.createElement('div');
            domainItem.style.display = 'flex';
            domainItem.style.justifyContent = 'space-between';
            domainItem.style.alignItems = 'center';
            domainItem.style.padding = '10px 0';
            domainItem.style.borderBottom = '1px solid #eee';
            
            domainItem.innerHTML = `
                <div>https://${domain}</div>
                <button type="button" class="btn btn-secondary remove-domain" data-domain="${domain}" style="padding: 5px 10px;">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            domainsList.appendChild(domainItem);
        });
        
        // Add remove event listeners
        document.querySelectorAll('.remove-domain').forEach(btn => {
            btn.addEventListener('click', function() {
                const domainToRemove = this.getAttribute('data-domain');
                domains = domains.filter(d => d !== domainToRemove);
                updateDomainsList();
                document.getElementById('domain').value = domains.join(',');
            });
        });
    }
    
    // Next button
    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            if (validateStep(currentStep)) {
                showStep(currentStep + 1);
            }
        });
    }
    
    // Back button
    if (backBtn) {
        backBtn.addEventListener('click', function() {
            showStep(currentStep - 1);
        });
    }
    
    // Validate steps
    function validateStep(step) {
        if (step === 1) {
            // Validate framework selection
            if (!document.getElementById('framework_type').value) {
                alert('Please select a framework');
                return false;
            }
            return true;
        }
        return true;
    }
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target == configModal) {
            closeModalFunc();
        }
    });
    
    // Initialize the first step
    showStep(1);
</script>
@endsection