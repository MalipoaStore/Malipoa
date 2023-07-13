<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class VendorController extends Controller
{
    public function VendorDashboard() {
        return view('vendor.index');
    }

    public function VendorLogin() {
        return view('vendor.vendor_login');
    }

    public function Destroy(Request $request) {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'Logout successful',
            'alert-type' => 'success'
        );

        return redirect('/vendor/login')->with($notification);
    }

    public function VendorProfile() {
        $vendor = User::find(Auth::user()->id);

        return view('vendor.vendor_profile_view', compact('vendor'));
    }

    public function VendorProfileUpdate(Request $request) {
        $vendor = User::find(Auth::user()->id);

        $vendor->name = $request->name;
        $vendor->email = $request->email;
        $vendor->phone = $request->phone;
        $vendor->address = $request->address;
        $vendor->vendor_join = $request->vendor_join;
        $vendor->vendor_short_info = $request->vendor_short_info;

        if ($request->file('profile_image')) {
            $image = $request->file('profile_image');

            @unlink(public_path('uploads/vendor_images/'.$vendor->profile_image));

            $filename = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();

            $image->move(public_path('uploads/vendor_images'), $filename);

            $vendor['profile_image'] = $filename;
        }

        $vendor->save();

        $notification = array(
            'message' => 'Vendor profile updated successfully',
            'alert-type' => 'success'
        );

        return back()->with($notification);
    }

    public function ChangePassword() {
        return view('vendor.vendor_change_password');
    }

    public function UpdatePassword(Request $request) {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed'
        ]);

        if (!Hash::check($request->old_password, Auth::user()->password)) {
            return back()->with('error', "The old password does not match");
        }

        User::whereId(Auth::user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('status', 'Vendor Password changed successfully');
    }
}
