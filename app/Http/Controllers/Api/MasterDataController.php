<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Location;
use App\Models\Product;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MasterDataController extends Controller
{
    /**
     * Get enhanced companies with master data
     */
    public function getCompanies(Request $request): JsonResponse
    {
        $query = Company::with(['locations', 'employees']);

        if ($request->filled('type')) {
            $query->where('type', $request->get('type'));
        }

        if ($request->filled('business_type')) {
            $query->where('business_type', $request->get('business_type'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $companies = $query->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $companies
        ]);
    }

    /**
     * Get enhanced locations with master data
     */
    public function getLocations(Request $request): JsonResponse
    {
        $query = Location::with(['company']);

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->get('company_id'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->get('type'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $locations = $query->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $locations
        ]);
    }

    /**
     * Get enhanced products with master data
     */
    public function getProducts(Request $request): JsonResponse
    {
        $query = Product::with(['company']);

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->get('company_id'));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->get('category'));
        }

        if ($request->filled('hazardous')) {
            $query->where('hazardous', $request->get('hazardous'));
        }

        if ($request->filled('batch_trackable')) {
            $query->where('batch_trackable', $request->get('batch_trackable'));
        }

        $products = $query->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Get employees with master data
     */
    public function getEmployees(Request $request): JsonResponse
    {
        $query = Employee::with(['company', 'homeLocation']);

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->get('company_id'));
        }

        if ($request->filled('role')) {
            $query->where('role', $request->get('role'));
        }

        if ($request->filled('department')) {
            $query->where('department', $request->get('department'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $employees = $query->orderBy('first_name')->get();

        return response()->json([
            'success' => true,
            'data' => $employees
        ]);
    }

    /**
     * Get master data summary statistics
     */
    public function getSummary(): JsonResponse
    {
        $summary = [
            'companies' => [
                'total' => Company::count(),
                'active' => Company::where('status', 'active')->count(),
                'by_type' => Company::selectRaw('type, count(*) as count')->groupBy('type')->get(),
                'by_business_type' => Company::selectRaw('business_type, count(*) as count')->groupBy('business_type')->get()
            ],
            'locations' => [
                'total' => Location::count(),
                'active' => Location::where('status', 'active')->count(),
                'by_type' => Location::selectRaw('type, count(*) as count')->groupBy('type')->get(),
                'total_capacity_volume' => Location::sum('capacity_volume'),
                'total_capacity_weight' => Location::sum('capacity_weight')
            ],
            'products' => [
                'total' => Product::count(),
                'active' => Product::where('status', 'active')->count(),
                'hazardous' => Product::where('hazardous', 'yes')->count(),
                'batch_trackable' => Product::where('batch_trackable', true)->count(),
                'serialization_capable' => Product::where('serialization_capable', true)->count(),
                'by_category' => Product::selectRaw('category, count(*) as count')->groupBy('category')->get()
            ],
            'employees' => [
                'total' => Employee::count(),
                'active' => Employee::where('status', 'active')->count(),
                'by_role' => Employee::selectRaw('role, count(*) as count')->groupBy('role')->get(),
                'by_department' => Employee::selectRaw('department, count(*) as count')->groupBy('department')->get()
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $summary
        ]);
    }

    /**
     * Create a new company
     */
    public function createCompany(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:companies',
            'type' => 'required|string|in:shipper,carrier,3pl,broker,customer',
            'business_type' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'status' => 'nullable|string|in:active,inactive'
        ]);

        $company = Company::create($validated);

        return response()->json([
            'success' => true,
            'data' => $company,
            'message' => 'Company created successfully'
        ], 201);
    }


    /**
     * Create a new product
     */
    public function createProduct(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'sku' => 'required|string|max:100',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'weight' => 'nullable|numeric|min:0',
            'volume' => 'nullable|numeric|min:0',
            'status' => 'nullable|string|in:active,inactive'
        ]);

        $product = Product::create($validated);

        return response()->json([
            'success' => true,
            'data' => $product,
            'message' => 'Product created successfully'
        ], 201);
    }
}