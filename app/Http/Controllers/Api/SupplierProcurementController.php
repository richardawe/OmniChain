<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SupplierOnboarding;
use App\Models\SupplierCatalog;
use App\Models\SupplierContract;
use App\Models\SupplierPerformance;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderLineItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SupplierProcurementController extends Controller
{
    public function getSupplierOnboarding(Request $request): JsonResponse
    {
        $query = SupplierOnboarding::with(['company', 'buyerCompany', 'assignedToUser']);

        if ($request->filled('buyer_company_id')) {
            $query->where('buyer_company_id', $request->get('buyer_company_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $onboarding = $query->orderBy('onboarding_date', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $onboarding
        ]);
    }

    public function getSupplierCatalogs(Request $request): JsonResponse
    {
        $query = SupplierCatalog::with(['company', 'buyerCompany']);

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->get('company_id'));
        }

        if ($request->filled('buyer_company_id')) {
            $query->where('buyer_company_id', $request->get('buyer_company_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $catalogs = $query->orderBy('product_name')->get();

        return response()->json([
            'success' => true,
            'data' => $catalogs
        ]);
    }

    public function getSupplierContracts(Request $request): JsonResponse
    {
        $query = SupplierContract::with(['company', 'buyerCompany', 'contractManager']);

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->get('company_id'));
        }

        if ($request->filled('buyer_company_id')) {
            $query->where('buyer_company_id', $request->get('buyer_company_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $contracts = $query->orderBy('start_date', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $contracts
        ]);
    }

    public function getSupplierPerformance(Request $request): JsonResponse
    {
        $query = SupplierPerformance::with(['company', 'buyerCompany', 'evaluatedByUser']);

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->get('company_id'));
        }

        if ($request->filled('buyer_company_id')) {
            $query->where('buyer_company_id', $request->get('buyer_company_id'));
        }

        $performance = $query->orderBy('performance_date', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $performance
        ]);
    }

    public function getPurchaseOrders(Request $request): JsonResponse
    {
        $query = PurchaseOrder::with([
            'buyerCompany',
            'supplierCompany',
            'createdByUser',
            'lineItems'
        ]);

        if ($request->filled('buyer_company_id')) {
            $query->where('buyer_company_id', $request->get('buyer_company_id'));
        }

        if ($request->filled('supplier_company_id')) {
            $query->where('supplier_company_id', $request->get('supplier_company_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $purchaseOrders = $query->orderBy('order_date', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $purchaseOrders
        ]);
    }

    public function getSummary(Request $request): JsonResponse
    {
        $buyerCompanyId = $request->get('buyer_company_id');

        $summary = [
            'suppliers' => [
                'total_onboarded' => SupplierOnboarding::when($buyerCompanyId, function($q) use ($buyerCompanyId) {
                    return $q->where('buyer_company_id', $buyerCompanyId);
                })->where('status', 'approved')->count(),
                'pending_onboarding' => SupplierOnboarding::when($buyerCompanyId, function($q) use ($buyerCompanyId) {
                    return $q->where('buyer_company_id', $buyerCompanyId);
                })->where('status', 'pending')->count(),
                'active_contracts' => SupplierContract::when($buyerCompanyId, function($q) use ($buyerCompanyId) {
                    return $q->where('buyer_company_id', $buyerCompanyId);
                })->active()->count(),
            ],
            'purchase_orders' => [
                'total_orders' => PurchaseOrder::when($buyerCompanyId, function($q) use ($buyerCompanyId) {
                    return $q->where('buyer_company_id', $buyerCompanyId);
                })->count(),
                'open_orders' => PurchaseOrder::when($buyerCompanyId, function($q) use ($buyerCompanyId) {
                    return $q->where('buyer_company_id', $buyerCompanyId);
                })->open()->count(),
                'confirmed_orders' => PurchaseOrder::when($buyerCompanyId, function($q) use ($buyerCompanyId) {
                    return $q->where('buyer_company_id', $buyerCompanyId);
                })->confirmed()->count(),
                'total_value' => PurchaseOrder::when($buyerCompanyId, function($q) use ($buyerCompanyId) {
                    return $q->where('buyer_company_id', $buyerCompanyId);
                })->sum('total_amount'),
            ],
            'performance' => [
                'average_otd' => SupplierPerformance::when($buyerCompanyId, function($q) use ($buyerCompanyId) {
                    return $q->where('buyer_company_id', $buyerCompanyId);
                })->avg('on_time_delivery_pct'),
                'average_quality' => SupplierPerformance::when($buyerCompanyId, function($q) use ($buyerCompanyId) {
                    return $q->where('buyer_company_id', $buyerCompanyId);
                })->avg('quality_reject_rate'),
                'average_score' => SupplierPerformance::when($buyerCompanyId, function($q) use ($buyerCompanyId) {
                    return $q->where('buyer_company_id', $buyerCompanyId);
                })->avg('overall_score'),
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $summary
        ]);
    }

    /**
     * Create a new purchase order
     */
    public function createPurchaseOrder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'po_number' => 'required|string|max:100|unique:purchase_orders',
            'po_type' => 'required|string|in:standard,blanket,consignment',
            'buyer_company_id' => 'required|exists:companies,id',
            'supplier_company_id' => 'required|exists:companies,id',
            'order_date' => 'required|date',
            'required_delivery_date' => 'nullable|date',
            'currency' => 'nullable|string|max:3',
            'payment_terms' => 'nullable|string|max:50',
            'status' => 'nullable|string|in:draft,open,confirmed,partially_received,closed,cancelled'
        ]);

        // Add default values
        $validated['created_by_user_id'] = 1; // TODO: Get from auth
        $validated['approved_by_user_id'] = 1; // TODO: Get from auth
        $validated['approved_at'] = now();

        $purchaseOrder = PurchaseOrder::create($validated);

        return response()->json([
            'success' => true,
            'data' => $purchaseOrder,
            'message' => 'Purchase order created successfully'
        ], 201);
    }

    /**
     * Create a new supplier contract
     */
    public function createContract(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'contract_number' => 'required|string|max:100|unique:supplier_contracts',
            'company_id' => 'required|exists:companies,id',
            'buyer_company_id' => 'required|exists:companies,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'contract_type' => 'required|string|in:standard,blanket,master_agreement,framework',
            'total_contract_value' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'status' => 'nullable|string|in:draft,active,expired,terminated,suspended'
        ]);

        // Add default values
        $validated['contract_manager_id'] = 1; // TODO: Get from auth

        $contract = SupplierContract::create($validated);

        return response()->json([
            'success' => true,
            'data' => $contract,
            'message' => 'Contract created successfully'
        ], 201);
    }

    // CRUD Operations for Purchase Orders
    public function showPurchaseOrder(PurchaseOrder $purchaseOrder): JsonResponse
    {
        $purchaseOrder->load(['buyerCompany', 'supplierCompany', 'createdByUser', 'lineItems']);
        return response()->json(['success' => true, 'data' => $purchaseOrder]);
    }

    public function updatePurchaseOrder(Request $request, PurchaseOrder $purchaseOrder): JsonResponse
    {
        $validated = $request->validate([
            'po_number' => 'sometimes|string|max:100|unique:purchase_orders,po_number,' . $purchaseOrder->id,
            'po_type' => 'sometimes|string|in:standard,blanket,consignment',
            'buyer_company_id' => 'sometimes|exists:companies,id',
            'supplier_company_id' => 'sometimes|exists:companies,id',
            'order_date' => 'sometimes|date',
            'required_delivery_date' => 'nullable|date',
            'currency' => 'nullable|string|max:3',
            'payment_terms' => 'nullable|string|max:50',
            'status' => 'sometimes|string|in:draft,open,confirmed,partially_received,closed,cancelled',
            'total_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'po_metadata' => 'nullable|array',
        ]);

        $purchaseOrder->update($validated);
        $purchaseOrder->load(['buyerCompany', 'supplierCompany', 'createdByUser', 'lineItems']);

        return response()->json(['success' => true, 'data' => $purchaseOrder]);
    }

    public function destroyPurchaseOrder(PurchaseOrder $purchaseOrder): JsonResponse
    {
        $purchaseOrder->delete();
        return response()->json(['success' => true, 'message' => 'Purchase order deleted successfully']);
    }

    // CRUD Operations for Purchase Order Line Items
    public function storePurchaseOrderLineItem(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'purchase_order_id' => 'required|integer|exists:purchase_orders,id',
            'product_id' => 'required|integer|exists:products,id',
            'line_number' => 'required|integer|min:1',
            'quantity_ordered' => 'required|integer|min:1',
            'quantity_received' => 'nullable|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'total_line_amount' => 'nullable|numeric|min:0',
            'required_delivery_date' => 'nullable|date',
            'line_notes' => 'nullable|string|max:500',
            'line_metadata' => 'nullable|array',
        ]);

        $lineItem = PurchaseOrderLineItem::create($validated);
        $lineItem->load(['purchaseOrder', 'product']);

        return response()->json(['success' => true, 'data' => $lineItem], 201);
    }

    public function showPurchaseOrderLineItem(PurchaseOrderLineItem $purchaseOrderLineItem): JsonResponse
    {
        $purchaseOrderLineItem->load(['purchaseOrder', 'product']);
        return response()->json(['success' => true, 'data' => $purchaseOrderLineItem]);
    }

    public function updatePurchaseOrderLineItem(Request $request, PurchaseOrderLineItem $purchaseOrderLineItem): JsonResponse
    {
        $validated = $request->validate([
            'purchase_order_id' => 'sometimes|integer|exists:purchase_orders,id',
            'product_id' => 'sometimes|integer|exists:products,id',
            'line_number' => 'sometimes|integer|min:1',
            'quantity_ordered' => 'sometimes|integer|min:1',
            'quantity_received' => 'nullable|integer|min:0',
            'unit_price' => 'sometimes|numeric|min:0',
            'total_line_amount' => 'nullable|numeric|min:0',
            'required_delivery_date' => 'nullable|date',
            'line_notes' => 'nullable|string|max:500',
            'line_metadata' => 'nullable|array',
        ]);

        $purchaseOrderLineItem->update($validated);
        $purchaseOrderLineItem->load(['purchaseOrder', 'product']);

        return response()->json(['success' => true, 'data' => $purchaseOrderLineItem]);
    }

    public function destroyPurchaseOrderLineItem(PurchaseOrderLineItem $purchaseOrderLineItem): JsonResponse
    {
        $purchaseOrderLineItem->delete();
        return response()->json(['success' => true, 'message' => 'Purchase order line item deleted successfully']);
    }

    // CRUD Operations for Supplier Onboarding
    public function storeSupplierOnboarding(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|integer|exists:companies,id',
            'buyer_company_id' => 'required|integer|exists:companies,id',
            'onboarding_date' => 'required|date',
            'status' => 'required|string|in:pending,in_progress,approved,rejected,on_hold',
            'assigned_to_user_id' => 'nullable|integer|exists:users,id',
            'onboarding_notes' => 'nullable|string|max:1000',
            'onboarding_metadata' => 'nullable|array',
        ]);

        $onboarding = SupplierOnboarding::create($validated);
        $onboarding->load(['company', 'buyerCompany', 'assignedToUser']);

        return response()->json(['success' => true, 'data' => $onboarding], 201);
    }

    public function showSupplierOnboarding(SupplierOnboarding $supplierOnboarding): JsonResponse
    {
        $supplierOnboarding->load(['company', 'buyerCompany', 'assignedToUser']);
        return response()->json(['success' => true, 'data' => $supplierOnboarding]);
    }

    public function updateSupplierOnboarding(Request $request, SupplierOnboarding $supplierOnboarding): JsonResponse
    {
        $validated = $request->validate([
            'company_id' => 'sometimes|integer|exists:companies,id',
            'buyer_company_id' => 'sometimes|integer|exists:companies,id',
            'onboarding_date' => 'sometimes|date',
            'status' => 'sometimes|string|in:pending,in_progress,approved,rejected,on_hold',
            'assigned_to_user_id' => 'nullable|integer|exists:users,id',
            'onboarding_notes' => 'nullable|string|max:1000',
            'onboarding_metadata' => 'nullable|array',
        ]);

        $supplierOnboarding->update($validated);
        $supplierOnboarding->load(['company', 'buyerCompany', 'assignedToUser']);

        return response()->json(['success' => true, 'data' => $supplierOnboarding]);
    }

    public function destroySupplierOnboarding(SupplierOnboarding $supplierOnboarding): JsonResponse
    {
        $supplierOnboarding->delete();
        return response()->json(['success' => true, 'message' => 'Supplier onboarding record deleted successfully']);
    }

    // CRUD Operations for Supplier Catalogs
    public function storeSupplierCatalog(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|integer|exists:companies,id',
            'buyer_company_id' => 'required|integer|exists:companies,id',
            'product_name' => 'required|string|max:200',
            'product_description' => 'nullable|string|max:1000',
            'unit_of_measure' => 'required|string|max:20',
            'unit_price' => 'required|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'minimum_order_quantity' => 'nullable|integer|min:1',
            'lead_time_days' => 'nullable|integer|min:0',
            'status' => 'required|string|in:active,inactive,discontinued',
            'catalog_metadata' => 'nullable|array',
        ]);

        $catalog = SupplierCatalog::create($validated);
        $catalog->load(['company', 'buyerCompany']);

        return response()->json(['success' => true, 'data' => $catalog], 201);
    }

    public function showSupplierCatalog(SupplierCatalog $supplierCatalog): JsonResponse
    {
        $supplierCatalog->load(['company', 'buyerCompany']);
        return response()->json(['success' => true, 'data' => $supplierCatalog]);
    }

    public function updateSupplierCatalog(Request $request, SupplierCatalog $supplierCatalog): JsonResponse
    {
        $validated = $request->validate([
            'company_id' => 'sometimes|integer|exists:companies,id',
            'buyer_company_id' => 'sometimes|integer|exists:companies,id',
            'product_name' => 'sometimes|string|max:200',
            'product_description' => 'nullable|string|max:1000',
            'unit_of_measure' => 'sometimes|string|max:20',
            'unit_price' => 'sometimes|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'minimum_order_quantity' => 'nullable|integer|min:1',
            'lead_time_days' => 'nullable|integer|min:0',
            'status' => 'sometimes|string|in:active,inactive,discontinued',
            'catalog_metadata' => 'nullable|array',
        ]);

        $supplierCatalog->update($validated);
        $supplierCatalog->load(['company', 'buyerCompany']);

        return response()->json(['success' => true, 'data' => $supplierCatalog]);
    }

    public function destroySupplierCatalog(SupplierCatalog $supplierCatalog): JsonResponse
    {
        $supplierCatalog->delete();
        return response()->json(['success' => true, 'message' => 'Supplier catalog record deleted successfully']);
    }

    // CRUD Operations for Supplier Contracts
    public function showSupplierContract(SupplierContract $supplierContract): JsonResponse
    {
        $supplierContract->load(['company', 'buyerCompany', 'contractManager']);
        return response()->json(['success' => true, 'data' => $supplierContract]);
    }

    public function updateSupplierContract(Request $request, SupplierContract $supplierContract): JsonResponse
    {
        $validated = $request->validate([
            'contract_number' => 'sometimes|string|max:100|unique:supplier_contracts,contract_number,' . $supplierContract->id,
            'company_id' => 'sometimes|integer|exists:companies,id',
            'buyer_company_id' => 'sometimes|integer|exists:companies,id',
            'start_date' => 'sometimes|date',
            'end_date' => 'nullable|date',
            'contract_type' => 'sometimes|string|in:standard,blanket,master_agreement,framework',
            'total_contract_value' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'status' => 'sometimes|string|in:draft,active,expired,terminated,suspended',
            'contract_manager_id' => 'nullable|integer|exists:users,id',
            'contract_notes' => 'nullable|string|max:1000',
            'contract_metadata' => 'nullable|array',
        ]);

        $supplierContract->update($validated);
        $supplierContract->load(['company', 'buyerCompany', 'contractManager']);

        return response()->json(['success' => true, 'data' => $supplierContract]);
    }

    public function destroySupplierContract(SupplierContract $supplierContract): JsonResponse
    {
        $supplierContract->delete();
        return response()->json(['success' => true, 'message' => 'Supplier contract deleted successfully']);
    }

    // CRUD Operations for Supplier Performance
    public function storeSupplierPerformance(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|integer|exists:companies,id',
            'buyer_company_id' => 'required|integer|exists:companies,id',
            'performance_period_start' => 'required|date',
            'performance_period_end' => 'required|date|after:performance_period_start',
            'on_time_delivery_pct' => 'nullable|numeric|min:0|max:100',
            'quality_reject_rate' => 'nullable|numeric|min:0|max:100',
            'cost_variance_pct' => 'nullable|numeric',
            'communication_score' => 'nullable|numeric|min:0|max:10',
            'overall_score' => 'nullable|numeric|min:0|max:10',
            'evaluated_by_user_id' => 'required|integer|exists:users,id',
            'performance_notes' => 'nullable|string|max:1000',
            'performance_metadata' => 'nullable|array',
        ]);

        $performance = SupplierPerformance::create($validated);
        $performance->load(['company', 'buyerCompany', 'evaluatedByUser']);

        return response()->json(['success' => true, 'data' => $performance], 201);
    }

    public function showSupplierPerformance(SupplierPerformance $supplierPerformance): JsonResponse
    {
        $supplierPerformance->load(['company', 'buyerCompany', 'evaluatedByUser']);
        return response()->json(['success' => true, 'data' => $supplierPerformance]);
    }

    public function updateSupplierPerformance(Request $request, SupplierPerformance $supplierPerformance): JsonResponse
    {
        $validated = $request->validate([
            'company_id' => 'sometimes|integer|exists:companies,id',
            'buyer_company_id' => 'sometimes|integer|exists:companies,id',
            'performance_period_start' => 'sometimes|date',
            'performance_period_end' => 'sometimes|date|after:performance_period_start',
            'on_time_delivery_pct' => 'nullable|numeric|min:0|max:100',
            'quality_reject_rate' => 'nullable|numeric|min:0|max:100',
            'cost_variance_pct' => 'nullable|numeric',
            'communication_score' => 'nullable|numeric|min:0|max:10',
            'overall_score' => 'nullable|numeric|min:0|max:10',
            'evaluated_by_user_id' => 'sometimes|integer|exists:users,id',
            'performance_notes' => 'nullable|string|max:1000',
            'performance_metadata' => 'nullable|array',
        ]);

        $supplierPerformance->update($validated);
        $supplierPerformance->load(['company', 'buyerCompany', 'evaluatedByUser']);

        return response()->json(['success' => true, 'data' => $supplierPerformance]);
    }

    public function destroySupplierPerformance(SupplierPerformance $supplierPerformance): JsonResponse
    {
        $supplierPerformance->delete();
        return response()->json(['success' => true, 'message' => 'Supplier performance record deleted successfully']);
    }
}