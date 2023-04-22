<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        
        if($request->q ){
            
            $employees= Employee::where('firstname', 'LIKE', '%'.$request->q.'%')->get();
            
            return response()->json(['message' => 'Get Search Employee','data'=> $employees], 200);
        }
        else{
            $employees = Employee::all();
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

    //search the specific resource from storage.
    // public function search(Request $request){
    //     if($request->keyword){
    //         $employee= Employee::where('firstname', 'LIKE', '%'.$request->keyword.'%')->get();
    //         return response([ 'employee' => new 
    //         EmployeeResource($employee), 'message' => 'Get search Employee'], 200);
    //     }
        
        // if (count($employee)){
        // return response()-> json($employee);
        // }
        // else{
        //     return response()->json(['Result' => 'No Data not found'], 404);
        // }
    // }    
    

}
