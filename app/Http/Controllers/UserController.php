<?php

namespace App\Http\Controllers;

use App\Models\User;
use Mailgun\Mailgun;
use Mailgun\Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::user()) {
            abort(403, 'Unauthorized access');
        }
        $userID = Auth::user()->id;
        $getUsers = User::where('id', '!=', $userID)->get();
        return view('users.index',compact('getUsers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => 'required',
        ]);
        dd($request->all());
        $saveUser = new User();
        $saveUser->name = $request->name;
        $saveUser->email = $request->email;
        $saveUser->password = bcrypt($request->password);
        $saveUser->role = $request->role;
        $userEmail = $request->email;
        $userPassword = $request->password;
        $userName = $request->name;
        $emailTemplate = "email_template.welcome-email.create-user";
        if($saveUser){
            $params = [
                'from'	    => "support@cuttingedgegizmos.com",
                'to'	    => $request->email,
                'subject'   => 'Customer Welcome',
                'html'	    =>  View($emailTemplate, compact('userName','userEmail','userPassword'))->render()
            ];
            try {
                $mgClient = Mailgun::create(env("MAILGUN_API"));
                $result = $mgClient->messages()->send(env("MAILGUN_DOMAIN"), $params);
                // $saveUser->save();
                // return redirect()->route('users.index')->with('success', 'User added successfully!');
            }
            catch (Exception $e) {
                return redirect()->route('users.index')->with('error', $e->getMessage());
            };
            
        }else{
            return redirect()->route('users.index')->with('error', 'Something went wrong!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $editUser = User::findOrFail($user->id);
        return view('users.edit',compact('editUser'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            //'password' => ['required', 'string', 'min:8'],
            'role' => 'required',
        ]);
        $updateUser = User::findOrFail($user->id);
        $updateUser->name = $request->name;
        $updateUser->email = $request->email;
        //$updateUser->password = bcrypt($request->password);
        $updateUser->role = $request->role;
        if($updateUser){
            $updateUser->update();
            return redirect()->route('users.index')->with('success', 'User updated successfully!');
        }else{
            return redirect()->route('users.index')->with('error', 'Something went wrong!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
