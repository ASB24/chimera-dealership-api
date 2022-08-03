<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Car;

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
                'data' => $user,
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
        ]);

        $newUser = new User([
            'username' => $request->get('username'),
            'password' => Hash::make($request->get('password')),
            'admin' => $request->get('admin') ?? false,
        ]);
        $newUser->save();

        return response(
            [
                'message' => 'Successfully created user',
                'data' => $newUser
            ], 201
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
        if($user = User::find($id)){
            return response([
                'message' => 'Successfully retrieved user',
                'data' => $user->first(),
            ]);
        }

        return response(
            [
                'message' => 'User not found',
            ], 404
        );
    }

    /**
     * Login a user
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request){
        $request->validate([
            'username' => 'required|string|min:5',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->get('username'))->first();
        if($user && Hash::check($request->get('password'), $user->password)){
            return response(
                [
                    'message' => 'Successfully logged in',
                    'user' => [
                        'id' => $user->id,
                        'username' => $user->username,
                        'isAdmin' => $user->admin,
                    ]
                ]
            );
        }
        return response(
            [
                'message' => 'Invalid credentials'
            ], 400
        );
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
            'username' => 'string|min:5' ?? null,
            'password' => 'string' ?? null,
            'admin' => 'boolean' ?? null,
        ]);

        if($user = User::find($id)){
            $user = $user->first();
            $user->username = $request->get('username') ?? $user->username;
            if($request->get('password')){
                $user->password = Hash::make($request->get('password'));
            }
            $user->admin = $request->get('admin') ?? $user->admin ?? false;
            $user->save();

            return response(
                [
                    'message' => 'Successfully updated user',
                    'data' => $user,
                ]
            );
        }
        
        return response(
            [
                'message' => 'User not found'
            ], 404
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($user = User::find($id)){
            $user = $user->first();

            $car = Car::where('seller_id', $user->id)->get();
            foreach($car as $c){
                $c->delete();
            }

            $user->delete();
            return response(
                [
                    'message' => 'User deleted',
                    'statusCode' => '200'
                ]
            );
        }
        return response(
            [
                'message' => 'User not found',
            ], 404
        );
    }
}
