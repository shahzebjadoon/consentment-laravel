<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use App\Models\Invitation;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CompanyInvitationController extends Controller
{
    public function invite(Request $request, Company $company)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Check if user already exists and is already associated
        $existingUser = User::where('email', $request->email)->first();
        
        if ($existingUser && $company->users()->where('user_id', $existingUser->id)->exists()) {
            return back()->with('error', 'This user is already associated with your company.');
        }

        // Create invitation
        $invitation = Invitation::create([
            'company_id' => $company->id,
            'inviter_id' => auth()->id(),
            'email' => $request->email,
            'token' => Str::random(64),
            'role' => "member", //hardcoded member later you can change if application grow ;-)
            'expires_at' => Carbon::now()->addDays(7)
        ]);

        // Send invitation email
        $emailController = new \App\Http\Controllers\EmailController();
        $emailController->sendInvitationEmail($invitation);

        // $invitation->notify(new CompanyInvitation($invitation));

        return back()->with('success', 'Invitation sent successfully!');
    }

    public function accept($token)
    {
        $invitation = Invitation::where('token', $token)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        // If user is logged in, containing same email, associate him directly
        if (auth()->check() && auth()->user()->email === $invitation->email) {
            
            $user = User::where('id', auth()->user()->id)->first();
            return $this->associateUser($user, $invitation);
        }

        // Otherwise, redirect to registration with invitation token
        // return ["I am sending to register form"];

        //check if user is already registered
        $existingUser = User::where('email', $invitation->email)->first();
        if ($existingUser) {
            // If user exists, associate them with the company
            return $this->associateUser($existingUser, $invitation);
        }
        // If user does not exist, redirect to registration form with token
        return redirect()->route('frontend.new.register', ['invitation' => $token]);
    }

    protected function associateUser(User $user, Invitation $invitation)
    {
        // Check if already associated
        if (!$invitation->company->users()->where('user_id', $user->id)->exists()) {
            $invitation->company->users()->attach($user->id, [
                'role' => $invitation->role
            ]);
        }

        $invitation->delete();

        return redirect("/dashboard")
            ->with('success', 'You have been successfully added to the company!');
    }

    public function removeUser( Request $request, Company $company, User $user)
    {
        // Check if the user is authorized to remove users from the company
        $admin = User::where('id', auth()->id())->first();

// dd([
//     'user_class' => get_class($user),
//     'user_methods' => get_class_methods($user),
//     'has_method' => method_exists($user, 'isCompanyAdmin')
// ]);
      
        
        if (!$admin->isCompanyAdmin($company->id)) {
           
            return back()->with('error', 'You are not authorized to remove users from this company.');
        }

        $companyUser = CompanyUser::where('company_id', $company->id)
            ->where('user_id', $user->id)
            ->first();
        
            $companyUser->delete();

       

         return redirect()->back()->with('success', 'User removed successfully');
       
    }

}