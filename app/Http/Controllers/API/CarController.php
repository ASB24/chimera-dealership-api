<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Validator;

class CarController extends Controller
{

    static public function createJson($car){
        return [
            'id' => $car->id,
            'image' => $car->image,
            'model' => $car->model,
            'brand' => $car->brand,
            'year' => $car->year,
            'price' => $car->price,
            'color' => $car->color,
            'traction' => $car->traction,
            'motor' => [
                'type' => $car->type,
                'hp' => $car->hp,
                'turbo' => $car->turbo,
                'cylinders' => $car->cylinders,
                'motor_liters' => $car->motor_liters,
            ],
            'seller_id' => $car->seller_id,
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cars = Car::all();
        $cars_json = [];
        
        foreach($cars as $car){
            array_push($cars_json, $this->createJson($car));
        }
        
        return response([
            'message' => 'Successfully retrieved cars',
            'data' => $cars_json
        ]);
    }

    /**
     * Display a listing of all cars registered by user.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexByUser($user_id)
    {
        $car = Car::where('seller_id', $user_id)->get();
        
        return response([
            'message' => 'Successfully retrieved cars',
            'data' => $car
        ]);
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
            'image' => 'required|image|mimes:jpeg,png,jpg|max:10124',
            'model' => 'required|string',
            'brand' => 'required|string',
            'year' => 'required|integer',
            'price' => 'required|numeric',
            'color' => 'required|string',
            'traction' => 'required|string',
            'motor.type' => 'required|string',
            'motor.hp' => 'required|integer',
            'motor.turbo' => 'required|boolean',
            'motor.cylinders' => 'required|integer',
            'seller_id' => 'required|integer'
        ]);

        $image = base64_encode(file_get_contents($request->file('image')->getRealPath()));

        $upload = Http::withHeaders([
            'Authorization' => 'Client-ID ' . env('IMGUR_CLIENT_ID'),
        ])->post('https://api.imgur.com/3/upload', [
            'image' => $image,
            'album' => 'mGU6oL1',
            'name' => str_replace( ' ', '', $request->get('seller_id') . '_' . $request->get('brand') . '_' . $request->get('model') ),
            'type' => 'base64'
        ])->json();

        $newCar = new Car([
            'image' => 'https://i.imgur.com/' . $upload['data']['id'] . '.' . $request->file('image')->getClientOriginalExtension(),
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
            'seller_id' => $request->get('seller_id')
        ]);

        $newCar->save();

        return response([
            'message' => 'Car registered successfully',
            'data' => $this->createJson($newCar)
        ], 201);
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

        return response([
            'message' => 'Car found',
            'data' => $this->createJson($car)
        ]);
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
            'motor.type' => 'required|string',
            'motor.hp' => 'required|integer',
            'motor.turbo' => 'required|boolean',
            'motor.cylinders' => 'required|integer',
            'seller_id' => 'required|integer'
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
        $car->seller_id = $request->get('seller_id');
        $car->save();

        return response([
            'message' => 'Car updated successfully',
            'data' => $this->createJson($car)
        ]);
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

        return response([
            'message' => 'Car deleted successfully'
        ]);
    }
}
