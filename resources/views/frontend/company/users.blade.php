@extends('frontend.company.layout')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <div>
        <h1 class="page-title">User Management</h1>
        <p class="page-description">Users added to the company can access all configurations.</p>
    </div>
    <button class="btn btn-primary" id="manageAccessBtn">
        <i class="fas fa-user-plus mr-2"></i> &nbsp; Manage Access
    </button>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Company Access</h3>
    </div>
    <div class="card-body">
        {{-- <div class="user-item" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid #eee;">
            <div style="display: flex; align-items: center;">
                <div style="width: 36px; height: 36px; border-radius: 50%; background-color: #1da1f2; color: white; display: flex; align-items: center; justify-content: center; margin-right: 15px; font-weight: 600;">
                    {{ substr(auth()->user()->email, 0, 1) }}
                </div>
                <span>{{ auth()->user()->email }}</span>
            </div>

         
            <div>
               
                <span class="company-badge admin">Admin </span>
                {{-- <button class="btn btn-secondary" style="width: 36px; height: 36px; padding: 0; display: flex; align-items: center; justify-content: center; margin-left: 10px;">
                    <i class="fas fa-ellipsis-v"></i>
                </button> 
            </div>
        </div> --}}
        
        @if(isset($users) && count($users) > 0)
            @foreach($users as $user)
                {{-- @if($user->id == auth()->id()) --}}
               
                <div class="user-item" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid #eee;">
                    <div style="display: flex; align-items: center;">
                        <div style="width: 36px; height: 36px; border-radius: 50%; background-color: #1da1f2; color: white; display: flex; align-items: center; justify-content: center; margin-right: 15px; font-weight: 600;">
                            {{ substr($user->email, 0, 1) }}
                        </div>
                        <span>{{ $user->email }}</span>
                    </div>
                    <div style="display: flex; align-items: center;">
                        @if($user->id != auth()->id() &&  $user->pivot->role != 'admin')
                            <form action="{{ route('companies.removeUser', ['company' => $company, 'user' => $user]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit"   style="background: #ffd2d6; border: none; color: #e23b3b; padding: 6px 10px; border-radius: 4px; display: flex; align-items: center; cursor: pointer;" title="Remove User">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif

                        <span class="company-badge admin">
                            {{ $user->pivot ? ucfirst($user->pivot->role) : 'User' }}
                        </span>
                    </div>
                </div>
                {{-- @endif --}}
            @endforeach
        @endif
    </div>
</div>




<!-- Manage Access Modal -->
<div id="manageAccessModal" class="modal">
    <div class="modal-content" style="width:50% !important;">
        <div class="modal-header">
            <h3>Manage Company Access</h3>
            <span class="close" id="closeAccessModal">&times;</span>
        </div>
        <div class="modal-body" style="font-size: 20px !important;">
            <p>Add and manage users to grant access to all configurations within your company.</p>
            
            <form action="{{ route('companies.invite', $company) }}" id="deleteForm-{{ $user->id }}" method="POST">
            @csrf
            <div class="form-group" style="font-size: 20px !important;">
                <div class="form-row">
                    <div class="form-column">
                        <br>
                        <label class="form-label" style="font-size: 20px; font-weight:700">Email</label>
                        <input type="email" style="font-size: 20px;" class="form-control" placeholder="Email" required name="email" >
                    </div>
                    {{-- <div class="form-column">
                        <label class="form-label">Permission</label>
                        <select class="form-control">
                            <option value="write">Write</option>
                            <option value="read">Read</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div> --}}
                    {{-- <div style="display: flex; align-items: flex-end; padding-bottom: 12px; margin-left: 10px;">
                        <button class="btn btn-primary" style="font-size: 20px;">Add</button>
                    </div> --}}
                </div>
                
                <div style="display: flex; align-items: center; margin-top: 10px; font-size: 20px;">
                    <i class="fas fa-check-square"  style="margin-right: 10px; width:20px; height:20px; color:#1da1f2;"></i>
                    <label for="notifyUser">User will be Notify via email</label>
                </div>
{{--                 
                <div style="margin-top: 10px;">
                    <a href="#" style="color: #1da1f2; text-decoration: none;">See permission details <i class="fas fa-chevron-down"></i></a>
                </div> --}}
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" id="cancelAccessBtn" style="font-size: 20px;">Cancel</button>
            <button class="btn btn-primary" style="font-size: 20px;">Save Changes</button>
        </div>
            </form>
    </div>
</div>
@endsection

@section('scripts')



<script>
    // Modal functionality
    const accessModal = document.getElementById('manageAccessModal');
    const manageAccessBtn = document.getElementById('manageAccessBtn');
    const closeAccessModal = document.getElementById('closeAccessModal');
    const cancelAccessBtn = document.getElementById('cancelAccessBtn');
    
    manageAccessBtn.addEventListener('click', function() {
        accessModal.style.display = 'block';
        setTimeout(() => {
            accessModal.classList.add('show');
        }, 10);
    });
    
    function closeModal() {
        accessModal.classList.remove('show');
        setTimeout(() => {
            accessModal.style.display = 'none';
        }, 300);
    }
    
    closeAccessModal.addEventListener('click', closeModal);
    cancelAccessBtn.addEventListener('click', closeModal);
    
    window.addEventListener('click', function(event) {
        if (event.target == accessModal) {
            closeModal();
        }
    });
</script>
@endsection