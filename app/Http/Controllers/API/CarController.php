<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Validator;
use GuzzleHttp\Client;

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
        
        return response($car);
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
            'image' => 'required|string',
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
            'motor.motor_liters' => 'required|numeric',
            'seller_id' => 'required|integer'
        ]);

        //Make a Guzzle request to https://api.imgur.com/3/image to upload the image encoded in base64 and return the image link
        $upload = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('IMGUR_ACCESS_TOKEN'),
        ])->post('https://api.imgur.com/3/image', [
            'image' => $request->get('image'),
            'type' => 'base64',
            'album' => 'mGU6oL1' 
        ])->onError(function ($response) {
            return response([
                'message' => 'Error uploading image'
            ], 500);
        });

        $newCar = new Car([
            'image' => $upload->json()['data']['link'],
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

        if($newCar->save()){
            return response([
                'message' => 'Successfully created car',
                'data' => $this->createJson($newCar)
            ]);
        }else{
            return response([
                'message' => 'Error creating car'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if($car = Car::find($id)){
            return response([
                'message' => 'Successfully retrieved car',
                'data' => $this->createJson($car)
            ]);
        }else{
            return response([
                'message' => 'Car not found'
            ], 404);
        }
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
            'image' => 'required|string',
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

        $upload = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('IMGUR_ACCESS_TOKEN'),
        ])->post('https://api.imgur.com/3/image', [
            'image' => $request->image,
            'type' => 'base64',
            'album' => 'mGU6oL1' 
        ])->onError(function ($response) {
            return response([
                'message' => 'Error uploading image'
            ], 500);
        });

        if($car = Car::find($id)){
            $car->image = $upload->json()['data']['link'];
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
        }else{
            return response([
                'message' => 'Car not found',
                'data' => $car
            ], 404);
        }

        if($car->save()){
            return response([
                'message' => 'Successfully updated car',
                'data' => $this->createJson($car)
            ]);
        }else{
            return response([
                'message' => 'Error updating car',
                'data' => $car
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($car = Car::find($id)){
            if($car->delete()){
                return response([
                    'message' => 'Successfully deleted car',
                    'data' => $this->createJson($car)
                ]);
            }
            
            return response([
                'message' => 'Error deleting car',
                'data' => $car
            ], 500);
            
        }

        return response([
            'message' => 'Car not found',
        ], 404);
        
    }
}
