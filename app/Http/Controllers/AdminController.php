<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function AdminDashboard() {
        return view('admin.index');
    }

    public function Destroy(Request $request) {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');

    }

    public function AdminLogin() {
        return view('admin.admin_login');
    }

    public function AdminProfile() {
        $adminData = User::find(Auth::user()->id);
        return view('admin.admin_profile_view', compact('adminData'));
    }

    public function AdminProfileUpdate(Request $request) {
        $data = User::find(Auth::user()->id);

        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;

        if ($request->file('profile_image')) {
            $image = $request->file('profile_image');

            @unlink(public_path('uploads/admin_images/'.$data->profile_image));
            $filename = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $image->move(public_path('uploads/admin_images'), $filename);

            $data['profile_image'] = $filename;
        }

        $data->save();

        $notification = array(
            'message' => 'Admin Profile Updated successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function ChangePassword() {
        return view('admin.admin_change_password');
    }

    public function UpdatePassword(Request $request) {

        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed'
        ]);

        if (!Hash::check($request->old_password, Auth::user()->password)) {
            return redirect()->back()->with('error', "Old password doesn't match!!");
        }

        // Update the new password
        User::whereId(Auth::user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        $notification = array(
            'message' => 'Admin Password Changed successfully',
            'alert-type' => 'success'
        );

        return back()->with($notification);
    }
}
