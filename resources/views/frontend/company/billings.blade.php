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
                <a href="{{ route('frontend.companies.details', ['id' => $company->id]) }}" class="tab-link "
                    style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; background-color: #f8f9fa; color: #666; font-weight: 500;   margin-right: 5px;">
                    Details
                </a>
                <a href="{{ route('frontend.companies.billings', ['company_id' => $company->id]) }}" class="tab-link"
                    style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; {{ $activeTab == 'billing' ? 'background-color: white; color: #333; font-weight: 500;' : 'background-color: #f8f9fa; color: #666;' }} margin-right: 5px;">
                    Billings
                </a>
            </div>
            <div style="border-top: 1px solid #dee2e6; margin-top: -1px;"></div>


            <div class="tab-content">


                <br>
                <div class="collapsible-section">
                    <div class="collapsible-header">
                        <h5>Billing Information</h5>

                        <button class="btn-toggle"><i class="fas fa-chevron-up"></i></button>
                    </div>
                    <div class="collapsible-content">


                        <div class="card-body">

                            <div class="card-header" style="border-bottom: none; padding-bottom: 0;">

                                <h3></h3>
                                <button class="btn btn-primary" id="editCompanyBtn">
                                    Save
                                </button>
                            </div>

                            <div class="form-row">
                                <div class="form-column">
                                    <div class="form-group">
                                        <label class="form-label">Account Name</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="form-column">
                                    <div class="form-group">
                                        <label class="form-label">Billing Email</label>
                                        <input type="email" class="form-control" placeholder="example@email.com">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-column">
                                    <div class="form-group">
                                        <label class="form-label">Street</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="form-column">
                                    <div class="form-group">
                                        <label class="form-label">ZIP Code</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-column">
                                    <div class="form-group">
                                        <label class="form-label">City</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="form-column">
                                    <div class="form-group">
                                        <label class="form-label">Country</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-column">
                                    <div class="form-group">
                                        <label class="form-label">VAT ID</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>

                            </div>


                        </div>



                    </div>
                </div>

                <div class="collapsible-section">
                    <div class="collapsible-header">
                        <h5>Subscription Information</h5>

                        <button class="btn-toggle"><i class="fas fa-chevron-up"></i></button>
                    </div>
                    <div class="collapsible-content">



                        <div class="card" style="width: 40%">
                            <div class="card-header">
                                <h3 class="card-title">Free</h3>
                                <button class="btn btn-primary" id="editCompanyBtn">
                                    In Trial
                                </button>
                            </div>
                            <div class="card-body">


                                <table class="subscription-table">
                                    <tbody>
                                        <tr>
                                            <th>Status</th>
                                            <td>Trials ends on 27/04/2025</td>
                                        </tr>
                                        <tr>
                                            <th>Subscription-ID</th>
                                            <td>BTUSkWUiF5CQK30j1</td>
                                        </tr>
                                        <tr>
                                            <th>Price</th>
                                            <td>€0 / month</td>
                                        </tr>
                                        <tr>
                                            <th>Domains</th>
                                            <td>Incl. 1 domain</td>
                                        </tr>
                                        <tr>
                                            <th>Configurations</th>
                                            <td>1</td>
                                        </tr>
                                        <tr>
                                            <th>Sessions</th>
                                            <td>1,000 / month</td>
                                        </tr>
                                        <tr>
                                            <th>Payment Method</th>
                                            <td>not added</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <button class="manage-btn">Manage Subscription</button>








                            </div>
                        </div>
                    </div>



                </div>
                <br>

                <div class="collapsible-section">
                    <div class="collapsible-header">
                        <h5>Payment </h5>

                        <button class="btn-toggle"><i class="fas fa-chevron-up"></i></button>
                    </div>
                    <div class="collapsible-content">



                       
                            <div class="card-body" style="background: rgb(235, 235, 235); padding: 20px; ">


                                No payment method added yet. Please add a payment method to your account to enable automatic billing and avoid service interruptions.
                                <br><br>



                            </div>
                            <p style="font-size: 12px">If you have any question about payment or invoices please mail us at <span style="color:blue;">invoices@consentment.com </span> </p>
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
                                <input type="text" class="form-control" name="street"
                                    value="{{ $company->street }}">
                            </div>
                        </div>
                        <div class="form-column">
                            <div class="form-group">
                                <label class="form-label">ZIP Code</label>
                                <input type="text" class="form-control" name="zip_code"
                                    value="{{ $company->zip_code }}">
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
                                    <option value="US" {{ $company->country == 'US' ? 'selected' : '' }}>United States
                                    </option>
                                    <option value="UK" {{ $company->country == 'UK' ? 'selected' : '' }}>United Kingdom
                                    </option>
                                    <option value="CA" {{ $company->country == 'CA' ? 'selected' : '' }}>Canada
                                    </option>
                                    <option value="AU" {{ $company->country == 'AU' ? 'selected' : '' }}>Australia
                                    </option>
                                    <option value="DE" {{ $company->country == 'DE' ? 'selected' : '' }}>Germany
                                    </option>
                                    <option value="FR" {{ $company->country == 'FR' ? 'selected' : '' }}>France
                                    </option>
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

<style>
    /* Collapsible Sections */
    .collapsible-section {
        border: 1px solid #e6e8eb;
        border-radius: 8px;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .collapsible-header {
        padding: 15px 20px;
        background-color: #f8f9fa;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
    }

    .collapsible-header h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 500;
    }

    .btn-toggle {
        background: none;
        border: none;
        color: #666;
        cursor: pointer;
        font-size: 16px;
    }

    .collapsible-content {
        padding: 15px 20px;
        border-top: 1px solid #e6e8eb;
    }
</style>



<style>
    .subscription-table {
        width: 100%;
        max-width: 600px;
        font-size: calc(1rem - 3px);
    }

    .subscription-table th,
    .subscription-table td {
        text-align: left;
        padding: 8px 12px;
    }



    .manage-btn {
        margin-top: 12px;
        padding: 8px 16px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .manage-btn:hover {
        background-color: #0056b3;
    }
</style>

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

        // function closeEditModal() {
        //     editModal.classList.remove('show');
        //     setTimeout(() => {
        //         editModal.style.display = 'none';
        //     }, 300);
        // }

        closeEditModal.addEventListener('click', closeEditModal);
        cancelEditBtn.addEventListener('click', closeEditModal);

        window.addEventListener('click', function(event) {
            if (event.target == editModal) {
                closeEditModal();
            }
        });




        // Collapsible sections toggle
        document.querySelectorAll('.collapsible-header').forEach(function(header) {
            header.addEventListener('click', function() {
                const content = this.nextElementSibling;
                const toggleBtn = this.querySelector('.btn-toggle i');

                if (content.style.display === 'none') {
                    content.style.display = 'block';
                    toggleBtn.classList.remove('fa-chevron-down');
                    toggleBtn.classList.add('fa-chevron-up');
                } else {
                    content.style.display = 'none';
                    toggleBtn.classList.remove('fa-chevron-up');
                    toggleBtn.classList.add('fa-chevron-down');
                }
            });
        });
    </script>
@endsection
