@extends('frontend.company.layout')

@section('content')

<div class="card" style="margin-bottom: 30px; width: 100%;">
    <div class="card-header" style="border-bottom: none; padding-bottom: 0;">
        <h3 class="page-title">Company Details <i class=""
                style="color: #ccc; font-size: 16px; vertical-align: middle;"></i></h3>
    </div>
    <br>
    <div class="card-body" style="padding-top: 0;">
        <!-- Tabs Navigation -->
        <div style="display: flex; margin-bottom: 20px;">
            <a href="#" class="tab-link active"
                style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; background-color: white; color: #333; font-weight: 800;   margin-right: 5px;">
                Details
            </a>
            <a
             href="{{route('frontend.companies.billings', ['company_id' => $company->id]) }}"
           class="tab-link  active" 
           style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; {{ $activeTab == 'embeddings' ? 'background-color: white; color: #333; font-weight: 800;' : 'background-color: #f8f9fa; color: #666;' }} margin-right: 5px;">
            Billings
        </a>
        </div>
        <div style="border-top: 1px solid #dee2e6; margin-top: -1px;"></div>
     
        
        <div class="tab-content">



          
            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">General Information</h3>
                    <button class="btn btn-primary" id="editCompanyBtn">
                        Save
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-column">
                            <div class="form-group">
                                <label class="form-label">Company Name</label>
                                <input type="text" class="form-control" value="{{ $company->name }}" >
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-column">
                            <div class="form-group">
                                <label class="form-label">Street</label>
                                <input type="text" class="form-control" value="{{ $company->street }}" >
                            </div>
                        </div>
                        <div class="form-column">
                            <div class="form-group">
                                <label class="form-label">ZIP Code</label>
                                <input type="text" class="form-control" value="{{ $company->zip_code }}" >
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-column">
                            <div class="form-group">
                                <label class="form-label">City</label>
                                <input type="text" class="form-control" value="{{ $company->city }}" >
                            </div>
                        </div>
                        <div class="form-column">
                            <div class="form-group">
                                <label class="form-label">Country</label>
                                <input type="text" class="form-control" value="{{ $company->country }}" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
         
        </div>
    </div>
</div>




















<!-- Edit Company Modal -->
<div id="editCompanyModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Company</h3>
            <span class="close" id="closeEditModal">&times;</span>
        </div>
        <form action="#" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-column">
                        <div class="form-group">
                            <label class="form-label">Company Name</label>
                            <input type="text" class="form-control" name="name" value="{{ $company->name }}">
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-column">
                        <div class="form-group">
                            <label class="form-label">Street</label>
                            <input type="text" class="form-control" name="street" value="{{ $company->street }}">
                        </div>
                    </div>
                    <div class="form-column">
                        <div class="form-group">
                            <label class="form-label">ZIP Code</label>
                            <input type="text" class="form-control" name="zip_code" value="{{ $company->zip_code }}">
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-column">
                        <div class="form-group">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" name="city" value="{{ $company->city }}">
                        </div>
                    </div>
                    <div class="form-column">
                        <div class="form-group">
                            <label class="form-label">Country</label>
                            <select class="form-control" name="country">
                                <option value="US" {{ $company->country == 'US' ? 'selected' : '' }}>United States</option>
                                <option value="UK" {{ $company->country == 'UK' ? 'selected' : '' }}>United Kingdom</option>
                                <option value="CA" {{ $company->country == 'CA' ? 'selected' : '' }}>Canada</option>
                                <option value="AU" {{ $company->country == 'AU' ? 'selected' : '' }}>Australia</option>
                                <option value="DE" {{ $company->country == 'DE' ? 'selected' : '' }}>Germany</option>
                                <option value="FR" {{ $company->country == 'FR' ? 'selected' : '' }}>France</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelEditBtn">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Edit Company Modal functionality
    const editModal = document.getElementById('editCompanyModal');
    const editCompanyBtn = document.getElementById('editCompanyBtn');
    const closeEditModal = document.getElementById('closeEditModal');
    const cancelEditBtn = document.getElementById('cancelEditBtn');
    
    editCompanyBtn.addEventListener('click', function() {
        editModal.style.display = 'block';
        setTimeout(() => {
            editModal.classList.add('show');
        }, 10);
    });
    
    function closeEditModal() {
        editModal.classList.remove('show');
        setTimeout(() => {
            editModal.style.display = 'none';
        }, 300);
    }
    
    closeEditModal.addEventListener('click', closeEditModal);
    cancelEditBtn.addEventListener('click', closeEditModal);
    
    window.addEventListener('click', function(event) {
        if (event.target == editModal) {
            closeEditModal();
        }
    });
</script>
@endsection