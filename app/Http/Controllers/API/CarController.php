<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class CarController extends Controller
{

    public function createJson($car){
        return [
            'model' => $car->get('model'),
            'brand' => $car->get('brand'),
            'year' => $car->get('year'),
            'price' => $car->get('price'),
            'color' => $car->get('color'),
            'traction' => $car->get('traction'),
            'motor' => [
                'type' => $car->get('type'),
                'hp' => $car->get('hp'),
                'turbo' => $car->get('turbo'),
                'cylinders' => $car->get('cylinders'),
                'motor_liters' => $car->get('motor_liters')
            ],
            'user_id' => $car->get('user_id')
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $car = Car::all();
        
        return response()->json($car);
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
            'model' => 'required|string',
            'brand' => 'required|string',
            'year' => 'required|integer',
            'price' => 'required|numeric',
            'color' => 'required|string',
            'traction' => 'required|string',
            'motor' => [
                'type' => 'required|string',
                'hp' => 'required|integer',
                'turbo' => 'required|boolean',
                'cylinders' => 'required|integer',
                'motor_liters' => 'required|numeric'
            ],
            'user_id' => 'required|integer'
        ]);

        $newCar = new Car([
            'model' => $request->get('model'),
            'brand' => $request->get('brand'),
            'year' => $request->get('year'),
            'price' => $request->get('price'),
            'color' => $request->get('color'),
            'traction' => $request->get('traction'),
            'type' => $request->get('motor')['type'],
            'hp' => $request->get('motor')['hp'],
            'turbo' => $request->get('motor')['turbo'],
            'cylinders' => $request->get('motor')['cylinders'],
            'motor_liters' => $request->get('motor')['motor_liters'],
            'user_id' => $request->get('user_id')
        ]);

        $newCar->save();

        return response()->json($newCar);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $car = Car::findOrFail($id);

        return response()->json(CarController::createJson($car));
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
            'model' => 'required|string',
            'brand' => 'required|string',
            'year' => 'required|integer',
            'price' => 'required|numeric',
            'color' => 'required|string',
            'traction' => 'required|string',
            'motor' => [
                'type' => 'required|string',
                'hp' => 'required|integer',
                'turbo' => 'required|boolean',
                'cylinders' => 'required|integer',
                'motor_liters' => 'required|numeric'
            ],
            'user_id' => 'required|integer'
        ]);

        $car = Car::findOrFail($id);
        $car->model = $request->get('model');
        $car->brand = $request->get('brand');
        $car->year = $request->get('year');
        $car->price = $request->get('price');
        $car->color = $request->get('color');
        $car->traction = $request->get('traction');
        $car->type = $request->get('motor')['type'];
        $car->hp = $request->get('motor')['hp'];
        $car->turbo = $request->get('motor')['turbo'];
        $car->cylinders = $request->get('motor')['cylinders'];
        $car->motor_liters = $request->get('motor')['motor_liters'];
        $car->user_id = $request->get('user_id');
        $car->save();

        return response()->json($car);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $car = Car::findOrFail($id);
        $car->delete();

        return response()->json($car);
    }
}
