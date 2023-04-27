<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;
use Illuminate\Support\Facades\Validator;
// use Cache;
use Illuminate\Support\Facades\Cache as FacadesCache;
use Illuminate\Support\Facades\Redis;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $url = request()->url();
        $queryParams = request()->query();
        ksort($queryParams);
        $queryString = http_build_query($queryParams);
        $fullUrl = "{$url}?{$queryString}";

        
        $employeedetails = FacadesCache::get($fullUrl);
        if(isset($employeedetails)) {
            $employees = json_decode($employeedetails);

            return response()->json([
                'status_code' => 201,
                'message' => 'Fetched from redis',
                'data' => $employees,
            ]);
        }
    
        
        if($request->q &&  ($request->sort_dir && in_array($request->sort_dir, ['asc', 'desc']) ) && ($request->sort_by && in_array($request->sort_by, ['firstname', 'lastname']))){

           $sort_dir= $request->sort_dir;
           $sort_by= $request->sort_by;
           
            $search_employees= Employee::where('firstname', 'LIKE', '%'.$request->q.'%')
            -> orwhere('lastname', 'LIKE', '%'.$request->q.'%')->orderBy($sort_by, $sort_dir)->get();

            FacadesCache::put($fullUrl, $search_employees, 3600);
            
            return response()->json(['message' => 'Get Search Employee','data'=> $search_employees], 200);
        }
        else{
            $employees = Employee::all();
            FacadesCache::put($fullUrl, $employees, 3600);
            return response([ 'employees' => 
            EmployeeResource::collection($employees), 
            'message' => 'Get List Employee'], 200);
        }
         
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
        $data = $request->all();
        $validator = Validator::make($data, [
            'firstname' => 'required',
            'lastname' => 'required',
            'address' => 'required',
            'email' => 'required'
        ]);
        if($validator->fails()){
            return response(['error' => $validator->errors(), 
            'Validation Error']);
        }
        $employee = Employee::create($data);

        return response([ 'employee' => new 
        EmployeeResource($employee), 
        'message' => 'Employee Created successful!'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        //
        return response([ 'employee' => new 
        EmployeeResource($employee), 'message' => 'Get Employee'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        //
        $employee->update($request->all());

        return response([ 'employee' => new 
        EmployeeResource($employee), 'message' => 'Update Success'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        //
        $employee->delete();

        return response(['message' => 'Employee deleted']);
    }

    

}
