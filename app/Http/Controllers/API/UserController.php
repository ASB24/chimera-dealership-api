<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::all();
        return response(
            [
                'message' => 'Successfully retrieved users',
                'data' => $user
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:5',
            'password' => 'required|string',
            'admin' => 'required|boolean',
        ]);

        $newUser = new User([
            'username' => $request->get('username'),
            'password' => Hash::make($request->get('password')),
            'admin' => $request->get('admin'),
        ]);
        $newUser->save();

        return response(
            [
                'message' => 'Successfully created user',
                'data' => $newUser
            ],
            201
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|string|min:5',
            'password' => 'required|string',
            'admin' => 'required|boolean',
        ]);

        $user = User::findOrFail($id);
        $user->username = $request->get('username');
        $user->password = $request->get('password');
        $user->admin = $request->get('admin');
        $user->save();

        return response(
            [
                'message' => 'User updated',
                'data' => $this->createJson($user)
            ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response(
            [
                'message' => 'User deleted',
                'data' => $this->createJson($user)
            ], 200);
    }
}
