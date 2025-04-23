<?php
namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB as DBFacade; // Fix DB import with an alias to prevent naming conflict
use Artisan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationEmail;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str; // Add this import for Str class
use Illuminate\Support\Facades\Schema; // Add missing import for Schema
use Illuminate\Support\Facades\Log; // Add missing import for Log

use App\Http\Controllers\Controller;
use App\Models\User;

class UsersController extends Controller {

	use ValidatesRequests;

    public function list(Request $request) {
        if(!auth()->user()->hasPermissionTo('show_users'))abort(401);
        $query = User::select('*');
        $query->when($request->keywords,
        fn($q)=> $q->where("name", "like", "%$request->keywords%"));
        $users = $query->get();
        return view('users.list', compact('users'));
    }

	public function register(Request $request) {
        return view('users.register');
    }

    public function doRegister(Request $request) {
        // Validate the registration data
        $request->validate([
            'name' => ['required', 'string', 'min:5'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
        ]);

        // Create the user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->credits = 1000; // Give new customers some starting credits
        $user->save();

        // Assign the Customer role to new registrations
        $user->assignRole('Customer');

        // Log the user in
        Auth::login($user);

        return redirect('/')->with('success', 'Registration successful! Welcome to our store.');
    }

    public function createEmployee(Request $request) {
        // Check if the user has permission to manage employees
        if(!auth()->user()->hasPermissionTo('manage_employees')) abort(401);

        return view('users.create_employee');
    }

    public function saveEmployee(Request $request) {
        // Check if the user has permission to manage employees
        if(!auth()->user()->hasPermissionTo('manage_employees')) abort(401);

        try {
            $this->validate($request, [
                'name' => ['required', 'string', 'min:5'],
                'email' => ['required', 'email', 'unique:users'],
                'password' => ['required', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->withInput($request->input())->withErrors('Invalid employee information.');
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        // Assign the Employee role
        $user->assignRole('Employee');

        return redirect()->route('users')->with('success', 'Employee created successfully');
    }

    public function login(Request $request) {
        return view('users.login');
    }

    public function doLogin(Request $request) {

    	if(!Auth::attempt(['email' => $request->email, 'password' => $request->password]))
            return redirect()->back()->withInput($request->input())->withErrors('Invalid login information.');

        $user = User::where('email', $request->email)->first();
        Auth::setUser($user);

        return redirect('/');
    }

    public function doLogout(Request $request) {

    	Auth::logout();

        return redirect('/');
    }

    public function profile(Request $request, User $user = null) {

        $user = $user??auth()->user();
        if(auth()->id()!=$user->id) {
            if(!auth()->user()->hasPermissionTo('show_users')) abort(401);
        }

        $permissions = [];
        foreach($user->permissions as $permission) {
            $permissions[] = $permission;
        }
        foreach($user->roles as $role) {
            foreach($role->permissions as $permission) {
                $permissions[] = $permission;
            }
        }

        return view('users.profile', compact('user', 'permissions'));
    }

    public function edit(Request $request, User $user = null) {
        $user = $user ?? auth()->user();
        
        // Fixed permission check - using edit_users instead of show_users
        if(auth()->id() != $user?->id) {
            if(!auth()->user()->hasPermissionTo('edit_users')) abort(401);
        }

        $roles = [];
        foreach(Role::all() as $role) {
            $role->taken = ($user->hasRole($role->name));
            $roles[] = $role;
        }

        $permissions = [];
        $directPermissionsIds = $user->permissions()->pluck('id')->toArray();
        foreach(Permission::all() as $permission) {
            $permission->taken = in_array($permission->id, $directPermissionsIds);
            $permissions[] = $permission;
        }

        return view('users.edit', compact('user', 'roles', 'permissions'));
    }

    public function save(Request $request, User $user) {
        // Validate basic input
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
        ]);

        if(auth()->id() != $user->id) {
            if(!auth()->user()->hasPermissionTo('edit_users')) abort(401); // Fixed permission check
        }

        $user->name = $request->name;
        $user->save();

        if(auth()->user()->hasPermissionTo('admin_users')) {
            // Prevent removing Admin role from self or last admin
            if (auth()->id() == $user->id && $user->hasRole('Admin')) {
                $hasAdminRole = false;
                foreach ($request->roles ?? [] as $role) {
                    if ($role === 'Admin') {
                        $hasAdminRole = true;
                        break;
                    }
                }
                
                if (!$hasAdminRole) {
                    return redirect()->route('users_edit', $user->id)
                        ->with('error', 'You cannot remove your own Admin role.');
                }
            }
            
            // Check if this is the last admin
            if ($user->hasRole('Admin') && !in_array('Admin', $request->roles ?? [])) {
                $adminCount = User::role('Admin')->count();
                if ($adminCount <= 1) {
                    return redirect()->route('users_edit', $user->id)
                        ->with('error', 'Cannot remove Admin role from the only admin account.');
                }
            }

            $user->syncRoles($request->roles ?? []);
            $user->syncPermissions($request->permissions ?? []);

            Artisan::call('cache:clear');
        }

        return redirect(route('profile', ['user'=>$user->id]))->with('success', 'User updated successfully');
    }

    public function delete(Request $request, User $user) {
        if(!auth()->user()->hasPermissionTo('delete_users')) abort(401);
        
        // Check if trying to delete self
        if (auth()->id() == $user->id) {
            return redirect()->route('users')->with('error', 'You cannot delete your own account.');
        }
        
        // Check if this is the only admin
        if ($user->hasRole('Admin')) {
            $adminCount = User::role('Admin')->count();
            if ($adminCount <= 1) {
                return redirect()->route('users')->with('error', 'Cannot delete the only admin account.');
            }
        }

        // Safely delete the user
        $user->delete();
        
        return redirect()->route('users')->with('success', 'User deleted successfully');
    }        

    public function savePassword(Request $request, User $user) {
        // If user is changing their own password
        if(auth()->id() == $user?->id) {
            $this->validate($request, [
                'old_password' => ['required'],
                'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            ]);

            if(!Auth::attempt(['email' => $user->email, 'password' => $request->old_password])) {
                // Don't log them out, just show an error
                return redirect()->back()->withErrors('Current password is incorrect.');
            }
        }
        // If admin is changing someone else's password
        else if(auth()->user()->hasPermissionTo('admin_users')) {
            $this->validate($request, [
                'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            ]);
        }
        else if(!auth()->user()->hasPermissionTo('edit_users')) {
            abort(401);
        }

        $user->password = bcrypt($request->password); //Secure
        $user->save();

        return redirect(route('profile', ['user'=>$user->id]))->with('success', 'Password updated successfully');
    }

    public function editPassword(Request $request, User $user = null) {
        $user = $user??auth()->user();
        
        // Fixed permission check
        if(auth()->id() != $user?->id) {
            if(!auth()->user()->hasPermissionTo('edit_users') && 
               !auth()->user()->hasPermissionTo('admin_users')) {
                abort(401);
            }
        }
        
        return view('users.edit_password', compact('user'));
    }

    public function redirectToGoogle() {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback() {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user exists
            $existingUser = User::where('email', $googleUser->email)->first();
            
            if ($existingUser) {
                // User exists, login
                Auth::login($existingUser);
            } else {
                // User doesn't exist, create new user
                DBFacade::beginTransaction(); // Use the aliased DB facade here
                try {
                    $newUser = new User();
                    $newUser->name = $googleUser->name;
                    $newUser->email = $googleUser->email;
                    $newUser->password = Hash::make(Str::random(16));
                    
                    // Check if google_id column exists before using it
                    if (Schema::hasColumn('users', 'google_id')) {
                        $newUser->google_id = $googleUser->id;
                    }
                    
                    $newUser->email_verified_at = now(); // Consider them verified since Google verified
                    $newUser->credits = 1000; // Give new customers some starting credits
                    $newUser->save();
                    
                    // Assign Customer role
                    $customerRole = Role::where('name', 'Customer')->first();
                    if ($customerRole) {
                        $newUser->assignRole($customerRole);
                    }
                    
                    Auth::login($newUser);
                    DBFacade::commit(); // Use the aliased DB facade here
                } catch (\Exception $e) {
                    DBFacade::rollBack(); // Use the aliased DB facade here
                    Log::error('Failed to create user from Google: ' . $e->getMessage());
                    return redirect('login')->with('error', 'Unable to create your account. Please try again later.');
                }
            }
            
            return redirect('/');
        } catch (\Exception $e) {
            Log::error('Google authentication error: ' . $e->getMessage());
            return redirect('login')->with('error', 'Authentication failed. Please try again.');
        }
    }

    public function redirectToLinkedIn() {
        return Socialite::driver('linkedin')->redirect();
    }

    public function handleLinkedInCallback() {
        try {
            $linkedinUser = Socialite::driver('linkedin')->user();
            
            // Check if user exists
            $existingUser = User::where('email', $linkedinUser->email)->first();
            
            if ($existingUser) {
                // User exists, login
                Auth::login($existingUser);
            } else {
                // User doesn't exist, create new user
                DBFacade::beginTransaction();
                try {
                    $newUser = new User();
                    $newUser->name = $linkedinUser->name;
                    $newUser->email = $linkedinUser->email;
                    $newUser->password = Hash::make(Str::random(16));
                    
                    // Check if linkedin_id column exists before using it
                    if (Schema::hasColumn('users', 'linkedin_id')) {
                        $newUser->linkedin_id = $linkedinUser->id;
                    }
                    
                    $newUser->email_verified_at = now(); // Consider them verified since LinkedIn verified
                    $newUser->credits = 1000; // Give new customers some starting credits
                    $newUser->save();
                    
                    // Assign Customer role
                    $customerRole = Role::where('name', 'Customer')->first();
                    if ($customerRole) {
                        $newUser->assignRole($customerRole);
                    }
                    
                    Auth::login($newUser);
                    DBFacade::commit();
                } catch (\Exception $e) {
                    DBFacade::rollBack();
                    Log::error('Failed to create user from LinkedIn: ' . $e->getMessage());
                    return redirect('login')->with('error', 'Unable to create your account. Please try again later.');
                }
            }
            
            return redirect('/');
        } catch (\Exception $e) {
            Log::error('LinkedIn authentication error: ' . $e->getMessage());
            return redirect('login')->with('error', 'Authentication failed. Please try again.');
        }
    }

    public function redirectToFacebook() {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback() {
        try {
            $facebookUser = Socialite::driver('facebook')->user();
            
            // Check if user exists
            $existingUser = User::where('email', $facebookUser->email)->first();
            
            if ($existingUser) {
                // User exists, login
                Auth::login($existingUser);
            } else {
                // User doesn't exist, create new user
                DBFacade::beginTransaction();
                try {
                    $newUser = new User();
                    $newUser->name = $facebookUser->name;
                    $newUser->email = $facebookUser->email;
                    $newUser->password = Hash::make(Str::random(16));
                    
                    // Check if facebook_id column exists before using it
                    if (Schema::hasColumn('users', 'facebook_id')) {
                        $newUser->facebook_id = $facebookUser->id;
                    }
                    
                    $newUser->email_verified_at = now(); // Consider them verified since Facebook verified
                    $newUser->credits = 1000; // Give new customers some starting credits
                    $newUser->save();
                    
                    // Assign Customer role
                    $customerRole = Role::where('name', 'Customer')->first();
                    if ($customerRole) {
                        $newUser->assignRole($customerRole);
                    }
                    
                    Auth::login($newUser);
                    DBFacade::commit();
                } catch (\Exception $e) {
                    DBFacade::rollBack();
                    Log::error('Failed to create user from Facebook: ' . $e->getMessage());
                    return redirect('login')->with('error', 'Unable to create your account. Please try again later.');
                }
            }
            
            return redirect('/');
        } catch (\Exception $e) {
            Log::error('Facebook authentication error: ' . $e->getMessage());
            return redirect('login')->with('error', 'Authentication failed. Please try again.');
        }
    }
}
