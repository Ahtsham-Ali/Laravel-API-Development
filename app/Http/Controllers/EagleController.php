<?php

namespace App\Http\Controllers;
use App\Helper\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use PHPUnit\Framework\Constraint\Count;

class EagleController extends Controller
{


    public function SearchVechicles($year, $company, $model)
    {

// setting up route of NHTSA API with the selected parameters
        $url =Helper::ApiUrl($year, $company, $model);

// calling the NHTSA API using PHP CURL
        $response = Helper::curlRequest($url);

// initialized an empty array for the data to be returned by developed API
        $data = [
            'count' => 0,
            'data' => []
        ];
// if response is received from the NHTSA API
        if ($response) {
// fetch the result from the NHTSA API for processing
            $results = $response->Results;
            $associatedArray = [];
            $carIds = [];
// storing vehicle IDs and the result set in an array to associate the crashrating from the NHTSA API
            foreach ($results as $result) {
                $carIds[] = $result->VehicleId;
                $associatedArray[$result->VehicleId] = $result;
            }
// calling the next NHTSA API for fetching and associating the crashrating of each vehicle to its data set in loop.
            foreach ($carIds as $id) {
//                $innerURL = 'https://one.nhtsa.gov/webapi/api/SafetyRatings/VehicleId/' . $id . '?format=json';
                $innerURL =Helper::ApiUrlById($id);
// calling the API to get crashrating of car
                $innerResponse = Helper::curlRequest($innerURL);

// if data of specified car is found
                if (property_exists($innerResponse, 'Results')) {
                    $requiredCar = $innerResponse->Results;
// fetch the rating and store in the array of vehicle previous data.
                    if ($requiredCar) {
                        $rating = $requiredCar[0]->OverallRating;
// associating the fetched rating to the vehicle data of first API call.
                        if (array_key_exists($id, $associatedArray)) {
                            $associatedArray[$id]->CrashRating = $rating;
                        }
                    }
                } else {
// if data is missing in API, associate the default rating value. Can be skipped just for handling a possible crashing scenario in case the data is missing from the API
                    if (array_key_exists($id, $associatedArray)) {
                        $associatedArray[$id]->CrashRating = 'Not Rated';
                    }
                }
            }

// convereting the final array of data to an object to return in valid JSON format             $standardObject = (object) $associatedArray;
            $standardObject = (object)$associatedArray;
// STORING data in final array and converting to JSON
//            $data = [
//                'count' => $response->Count,
//                'data' => json_encode($standardObject)
//            ];
// returning the result in final JSON format with specified details of final step (3)
            return $data = [
                'count' => $response->Count,
                'data' => $standardObject
            ];
//            Or you can return your view here with data as in else case
        } else {
            print_r(compact(json_encode($data)));
        }
    }


    public function SearchVehiclesByPost(Request $request)
    {
//        dd((Input::all()));
        $year = $request->get('year');
        $company = $request->get('company');
        $model = $request->get('model');

        $url = Helper::ApiUrl($year, $company, $model);

        $response = Helper::curlRequest($url);

        $data = [
            'count' => 0,
            'data' => [
            ]
        ];

        if ($response) {
            $results = $response->Results;
            $associatedArray = [];
            $carIds = [];

            foreach ($results as $result) {
                $carIds[] = $result->VehicleId;
                $associatedArray[$result->VehicleId] = $result;
            }
            foreach ($carIds as $id) {
                $innerURL =Helper::ApiUrlById($id);
                $innerResponse = Helper::curlRequest($innerURL);

                if (property_exists($innerResponse, 'Results')) {
                    $requiredCar = $innerResponse->Results;
                    if ($requiredCar) {
                        $rating = $requiredCar[0]->OverallRating;
                        if (array_key_exists($id, $associatedArray)) {
                            $associatedArray[$id]->CrashRating = $rating;
                        }
                    }
                } else {
                    if (array_key_exists($id, $associatedArray)) {
                        $associatedArray[$id]->CrashRating = 'Not Rated';
                    }
                }
            }

            $standardObject = (object)$associatedArray;
            return $data = [
                'count' => $response->Count,
                'data' => $standardObject
            ];
        } else {
            return json_encode($data);
        }
    }

    public function getForm()
    {
        return view('foam');
    }


}
