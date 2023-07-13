<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Image;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function UserDashboard() {
        $user = User::find(Auth::user()->id);
        return view('index', compact('user'));
    }

    public function UserUpdateProfile(Request $request) {

        // $request->validate([
        //     'profile_image' => ['image|mimes:jpeg,png,jpg|max:2048']
        // ]);
        $user = User::find(Auth::user()->id);

        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->phone = $request->phone;

        if ($request->file('profile_image')) {
            $image = $request->file('profile_image');

            @unlink(public_path('uploads/user_images/'.$user->profile_image));
            $filename = hexdec(uniqid()).$image->getClientOriginalExtension();
            $image->move(public_path('uploads/user_images'), $filename);

            $user['profile_image'] = $filename;
        }

        $user->save();

        $notification = array(
            'message' => 'User Profile Updated Successfully',
            'alert-type' => 'success'
        );

        return back()->with($notification);
    }


    public function destroy(Request $request) {

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function ChangePassword(Request $request) {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed'
        ]);

        if (!Hash::check($request->old_password, Auth::user()->password)) {
            return back()->with('error', "Old Password doesn't match!");
        }

        //Update Password
        User::whereId(Auth::user()->id)->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('status', "Password Changed Successfully");
    }
}
