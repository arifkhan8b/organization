<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Http\Resources\EmployeeResource;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Validator;


class EmployeeController extends BaseController
{
    /**
     * @OA\Get(
     *      path="/api/v1/admin/employees/",
     *      operationId="getEmployeesList",
     *      tags={"Employees"},
     *      security={{ "apiAuth": {} }},
     *      summary="Get list of employees",
     *      description="Returns list of employees",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function index()
    {
        $employees = Employee::with('company')->get();
    
        return $this->sendResponse(EmployeeResource::collection($employees), 'Employees retrieved successfully.');
    }

    
    /**
     * @OA\Post(
     *      path="/api/v1/admin/employees",
     *      operationId="addEmployee",
     *      tags={"Employees"},
     *      security={{ "apiAuth": {} }},
     *      summary="Add new emplyee",
     *      description="Add new employee in database",
     *  * @OA\RequestBody(
     *    required=true,
     *    description="Add employee data",
     *    @OA\JsonContent(
     *       required={"first_name", "last_name", "email", "company_id"},
     *       @OA\Property(property="first_name", type="string", example="John"),
     *       @OA\Property(property="last_name", type="string", example="San"),
     *       @OA\Property(property="email", type="string", format="email", example="abc@yahoo.com"),
     *       @OA\Property(property="phone", type="string", example="03456789652"),
     *       @OA\Property(property="company_id", type="integer", example="1"),
     *    ),
     * ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function store(Request $request)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'email' => 'required|unique:employees',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $employee = Employee::create($input);
   
        return $this->sendResponse(new EmployeeResource($employee), 'Company created successfully.');
    }

    /**
     * @OA\Get(
     *      path="/api/v1/admin/employees/{id}",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      operationId="getEmployeesDetail",
     *      tags={"Employees"},
     *      security={{ "apiAuth": {} }},
     *      summary="Get employee detail",
     *      description="Returns detail of employee",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::find($id);
  
        if (is_null($employee)) {
            return $this->sendError('Employee not found.');
        }
   
        return $this->sendResponse(new EmployeeResource($employee), 'Employee retrieved successfully.');
    }
    
     /**
     * @OA\PUT(
     *      path="/api/v1/admin/employees/{id}",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *   @OA\RequestBody(
     *    required=true,
     *    description="Update employee data",
     *    @OA\JsonContent(
     *       required={"first_name", "last_name", "email", "company_id"},
     *       @OA\Property(property="first_name", type="string", example="John"),
     *       @OA\Property(property="last_name", type="string", example="San"),
     *       @OA\Property(property="email", type="string", format="email", example="abc@yahoo.com"),
     *       @OA\Property(property="phone", type="string", example="03456789652"),
     *       @OA\Property(property="company_id", type="integer", example="1"),
     *    ),
     * ),
     *      operationId="updateEmployeeDetail",
     *      tags={"Employees"},
     *      security={{ "apiAuth": {} }},
     *      summary="Update employee detail",
     *      description="Update detail of employee",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'email' => 'required|unique:employees,email,'.$employee->id,
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $employee->first_name = $input['first_name'];
        $employee->last_name = $input['last_name'];
        $employee->email = $input['email'];
        $employee->company_id = $input['company_id'];
        $employee->phone = $input['phone'];
        $employee->save();
   
        return $this->sendResponse(new EmployeeResource($employee), 'Employee updated successfully.');
    }

     
      /**
     * @OA\Delete(
     *      path="/api/v1/admin/employees/{id}",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      operationId="deleteEmployee",
     *      tags={"Employees"},
     *      security={{ "apiAuth": {} }},
     *      summary="Delete employee",
     *      description="Delete employee",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
   
        return $this->sendResponse([], 'Employee deleted successfully.');
    }
}
