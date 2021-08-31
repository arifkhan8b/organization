<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Http\Resources\CompanyResource;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Validator;


class CompanyController extends BaseController
{
    /**
     * @OA\Get(
     *      path="/api/v1/admin/companies/",
     *      operationId="getCompaniesList",
     *      tags={"Companies"},
     *      security={{ "apiAuth": {} }},
     *      summary="Get list of companies",
     *      description="Returns list of companies",
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
        $companies = Company::with('employees')->get();
    
        return $this->sendResponse(CompanyResource::collection($companies), 'Companies retrieved successfully.');
    }

    /**
     * @OA\Post(
     *      path="/api/v1/admin/companies",
     *      operationId="addCompany",
     *      tags={"Companies"},
     *      security={{ "apiAuth": {} }},
     *      summary="Add new company",
     *      description="Add new company in database",
     *  * @OA\RequestBody(
     *    required=true,
     *    description="Add company data",
     *    @OA\JsonContent(
     *       required={"name"},
     *       @OA\Property(property="name", type="string", example="Abc Corporation"),
     *       @OA\Property(property="email", type="string", format="email", example="abc@yahoo.com"),
     *       @OA\Property(property="website", type="string", example="www.abc.com"),
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
            'name' => 'required|unique:companies',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $company = Company::create($input);
   
        return $this->sendResponse(new CompanyResource($company), 'Company created successfully.');
    }

    /**
     * @OA\Get(
     *      path="/api/v1/admin/companies/{id}",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      operationId="getCompaniesDetail",
     *      tags={"Companies"},
     *      security={{ "apiAuth": {} }},
     *      summary="Get company detail",
     *      description="Returns detail of company",
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
        $company = Company::find($id);
  
        if (is_null($company)) {
            return $this->sendError('Company not found.');
        }
   
        return $this->sendResponse(new CompanyResource($company), 'Company retrieved successfully.');
    }
    
     /**
     * @OA\PUT(
     *      path="/api/v1/admin/companies/{id}",
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
     *    description="Update company data",
     *    @OA\JsonContent(
     *       required={"name"},
     *       @OA\Property(property="name", type="string", example="Abc Corporation"),
     *       @OA\Property(property="email", type="string", format="email", example="abc@yahoo.com"),
     *       @OA\Property(property="website", type="string", example="www.abc.com"),
     *    ),
     * ),
     *      operationId="updateCompaniesDetail",
     *      tags={"Companies"},
     *      security={{ "apiAuth": {} }},
     *      summary="Update company detail",
     *      description="Update detail of company",
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
    public function update(Request $request, Company $company)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'name' => 'required|unique:companies,name,'.$company->id,
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $company->name = $input['name'];
        $company->email = $input['email'];
        $company->website = $input['website'];
        $company->save();
   
        return $this->sendResponse(new CompanyResource($company), 'Company updated successfully.');
    }

       /**
     * @OA\Delete(
     *      path="/api/v1/admin/companies/{id}",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      operationId="deleteCompany",
     *      tags={"Companies"},
     *      security={{ "apiAuth": {} }},
     *      summary="Delete company",
     *      description="Delete company",
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
    public function destroy(Company $company)
    {
        $company->delete();
   
        return $this->sendResponse([], 'Company deleted successfully.');
    }
}
