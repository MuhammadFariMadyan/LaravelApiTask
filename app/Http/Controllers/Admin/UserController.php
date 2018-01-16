<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Utils\AppConstant;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $userList = User::get();
	    return view('admin.pages.users')->with('userList', $userList);
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
        //
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
	public function userStatus($uuid, $status)
	{
		/*$stripe = Stripe::make(env('STRIPE_SECRET'));*/
		if ($status == 1) {
			$data = array(
				"userStatus" => 0
			);
		} else {
			$data = array(
				"userStatus" => 1
			);
		}
		$where = array('uuid' => $uuid);
		try {

			User::where($where)->update($data);
		} catch (QueryException $e) {
			/* $response['meta'] = array('code' => 500, 'message' => $e->getMessage());
			 print_r($response);
			 exit;*/
			Session::flash('serverError','Something went wrong!');
		}
		return response()->redirectTo(AppConstant::ADMIN_URL_PREFIX.'users');
	}
}
