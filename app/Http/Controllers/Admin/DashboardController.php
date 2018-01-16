<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\User;
use App\Utils\AppConstant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$data['users'] = User::where('status',AppConstant::STATUS_ACTIVE)->get()->count();
		return view('admin.pages.dashboard')->withdata($data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}

	public function adminEditProfile()
	{
		$data['admin'] = Admin::find(Auth::guard(AppConstant::GUARD_ADMIN)->user()->id);
		return view('admin.pages.editProfile')->withdata($data);
	}

	public function updateProfile(Request $request)
	{
		if (!$request->adminName) {
			return redirect()->back()
			                 ->withErrors([
				                 'adminName' => "Admin Name can't be empty"
			                 ]);
		}

		$data = array(
			"adminName" => $request->adminName
		);
		$where = array(
			'id' => Auth::guard(AppConstant::GUARD_ADMIN)->user()->id
		);
		Admin::where($where)->update($data);
		Session::flash('update_success', 'Profile updated successfully.');
		return response()->redirectTo(AppConstant::ADMIN_URL_PREFIX . 'editProfile');
	}

	public function changePassword()
	{
		return view('admin.pages.changePassword');
	}

	public function editpassword(Request $request)
	{
		$adminAuth = Auth::guard(AppConstant::GUARD_ADMIN)->user();
		if (!$request->currentPassword) {
			return redirect()->back()
			                 ->withInput($request->only('currentPassword'))
			                 ->withErrors([
				                 'currentPassword' => "Current Password can't be empty"
			                 ]);
		}
		if (!Hash::check($request->currentPassword, $adminAuth->adminPassword)) {
			return redirect()->back()
			                 ->withInput($request->only('currentPassword'))
			                 ->withErrors([
				                 'currentPassword' => "Current Password does not match"
			                 ]);
		}
		if ($request->newPassword != $request->reenterNewPassword) {
			return redirect()->back()
			                 ->withErrors([
				                 'newPassword' => "New Password and Confirm Password does not match"
			                 ]);
		}

		$data = array(
			"adminPassword" => Hash::make($request->newPassword)
		);
		$where = array(
			'id' => Auth::guard(AppConstant::GUARD_ADMIN)->user()->id
		);
		Admin::where($where)->update($data);
		Session::flash('password_success', 'Password updated successfully.');
		return response()->redirectTo(AppConstant::ADMIN_URL_PREFIX . 'changePassword');
	}
}
