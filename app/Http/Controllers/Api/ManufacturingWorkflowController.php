<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ManufacturingWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ManufacturingWorkflowController extends Controller
{
    protected $workflowService;

    public function __construct(ManufacturingWorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    /**
     * Generate work orders from confirmed purchase orders
     */
    public function generateFromPurchaseOrders(): JsonResponse
    {
        try {
            $workOrders = $this->workflowService->generateWorkOrdersFromPurchaseOrders();
            
            return response()->json([
                'success' => true,
                'message' => 'Work orders generated successfully',
                'data' => [
                    'work_orders_count' => count($workOrders),
                    'work_orders' => $workOrders
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate work orders: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate work orders for specific products
     */
    public function generateForProducts(Request $request): JsonResponse
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer|exists:products,id',
            'quantity' => 'integer|min:1|max:10000'
        ]);

        try {
            $productIds = $request->input('product_ids');
            $quantity = $request->input('quantity', 100);
            
            $workOrders = $this->workflowService->createWorkOrdersForProducts($productIds, $quantity);
            
            return response()->json([
                'success' => true,
                'message' => 'Work orders generated successfully',
                'data' => [
                    'work_orders_count' => count($workOrders),
                    'work_orders' => $workOrders
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate work orders: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get manufacturing workflow status
     */
    public function getWorkflowStatus(): JsonResponse
    {
        try {
            $stats = [
                'confirmed_purchase_orders' => \App\Models\PurchaseOrder::where('status', 'confirmed')->count(),
                'products_with_bom' => \App\Models\BillOfMaterial::where('status', 'active')->count(),
                'manufacturing_locations' => \App\Models\Location::where('type', 'Manufacturing')->count(),
                'planned_work_orders' => \App\Models\WorkOrder::where('status', 'planned')->count(),
                'in_progress_work_orders' => \App\Models\WorkOrder::where('status', 'in_progress')->count(),
                'completed_work_orders' => \App\Models\WorkOrder::where('status', 'completed')->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get workflow status: ' . $e->getMessage()
            ], 500);
        }
    }
}
