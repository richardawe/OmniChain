<?php

namespace App\Services;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderLineItem;
use App\Models\WorkOrder;
use App\Models\Product;
use App\Models\BillOfMaterial;
use App\Models\BomComponent;
use App\Models\Company;
use App\Models\Location;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ManufacturingWorkflowService
{
    /**
     * Generate work orders from confirmed purchase orders
     */
    public function generateWorkOrdersFromPurchaseOrders()
    {
        $confirmedPOs = PurchaseOrder::where('status', 'confirmed')
            ->with(['lineItems.product'])
            ->get();

        $generatedWorkOrders = [];

        foreach ($confirmedPOs as $purchaseOrder) {
            $workOrders = $this->createWorkOrdersForPurchaseOrder($purchaseOrder);
            $generatedWorkOrders = array_merge($generatedWorkOrders, $workOrders);
        }

        return $generatedWorkOrders;
    }

    /**
     * Create work orders for a specific purchase order
     */
    public function createWorkOrdersForPurchaseOrder(PurchaseOrder $purchaseOrder)
    {
        $workOrders = [];
        
        // Group line items by product
        $productGroups = $purchaseOrder->lineItems->groupBy('sku');
        
        foreach ($productGroups as $sku => $lineItems) {
            $product = Product::where('sku', $sku)->first();
            
            if (!$product) {
                Log::warning("Product not found for SKU: {$sku}");
                continue;
            }

            // Check if this product has a BOM (Bill of Materials)
            $bom = BillOfMaterial::where('product_id', $product->id)
                ->where('status', 'active')
                ->first();

            if (!$bom) {
                Log::info("No BOM found for product: {$product->name} (SKU: {$sku})");
                continue;
            }

            // Calculate total quantity needed
            $totalQuantity = $lineItems->sum('quantity_ordered');
            
            // Find a suitable manufacturing location
            $manufacturingLocation = $this->findManufacturingLocation($product, $purchaseOrder);
            
            if (!$manufacturingLocation) {
                Log::warning("No manufacturing location found for product: {$product->name}");
                continue;
            }

            // Create work order
            $workOrder = WorkOrder::create([
                'work_order_id' => $this->generateWorkOrderNumber(),
                'product_id' => $product->id,
                'bom_id' => $bom->id,
                'production_line_id' => $manufacturingLocation->id,
                'quantity_planned' => $totalQuantity,
                'quantity_produced' => 0,
                'planned_start_time' => now()->addDays(1), // Start next day
                'planned_end_time' => now()->addDays(7), // Complete within a week
                'priority' => $this->determinePriority($purchaseOrder),
                'status' => 'planned',
                'created_by' => 1, // Default user ID
                'work_instructions' => "Generated from Purchase Order: {$purchaseOrder->po_number}",
            ]);

            $workOrders[] = $workOrder;
            
            Log::info("Created Work Order: {$workOrder->work_order_id} for Product: {$product->name}");
        }

        return $workOrders;
    }

    /**
     * Find suitable manufacturing location for a product
     */
    private function findManufacturingLocation(Product $product, ?PurchaseOrder $purchaseOrder = null)
    {
        // First, try to find a location owned by the product's company
        $productCompany = $product->company;
        
        if ($productCompany) {
            $manufacturingLocation = Location::where('company_id', $productCompany->id)
                ->where('type', 'Manufacturing')
                ->where('status', 'active')
                ->first();
                
            if ($manufacturingLocation) {
                return $manufacturingLocation;
            }
        }

        // Fallback: find any manufacturing location
        return Location::where('type', 'Manufacturing')
            ->where('status', 'active')
            ->first();
    }

    /**
     * Generate unique work order number
     */
    private function generateWorkOrderNumber()
    {
        $year = date('Y');
        $month = date('m');
        
        // Get the last work order number for this month
        $lastWorkOrder = WorkOrder::where('work_order_number', 'like', "WO-{$year}{$month}%")
            ->orderBy('work_order_number', 'desc')
            ->first();
        
        if ($lastWorkOrder) {
            $lastNumber = (int) substr($lastWorkOrder->work_order_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return "WO-{$year}{$month}" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Determine work order priority based on purchase order
     */
    private function determinePriority(PurchaseOrder $purchaseOrder)
    {
        // Simple priority logic - can be enhanced
        $daysUntilDelivery = now()->diffInDays($purchaseOrder->required_delivery_date);
        
        if ($daysUntilDelivery <= 3) {
            return 'high';
        } elseif ($daysUntilDelivery <= 7) {
            return 'medium';
        } else {
            return 'normal';
        }
    }

    /**
     * Create work orders for specific products that need manufacturing
     */
    public function createWorkOrdersForProducts(array $productIds, int $quantity = 100)
    {
        $workOrders = [];
        
        foreach ($productIds as $productId) {
            $product = Product::find($productId);
            
            if (!$product) {
                continue;
            }

            // Check if product has BOM
            $bom = BillOfMaterial::where('product_id', $product->id)
                ->where('status', 'active')
                ->first();

            if (!$bom) {
                continue;
            }

            // Find manufacturing location
            $manufacturingLocation = $this->findManufacturingLocation($product, null);
            
            if (!$manufacturingLocation) {
                continue;
            }

            // Create work order
            $workOrder = WorkOrder::create([
                'work_order_id' => $this->generateWorkOrderNumber(),
                'product_id' => $product->id,
                'bom_id' => $bom->id,
                'production_line_id' => $manufacturingLocation->id,
                'quantity_planned' => $quantity,
                'quantity_produced' => 0,
                'planned_start_time' => now()->addDays(1),
                'planned_end_time' => now()->addDays(7),
                'priority' => 1, // Normal priority
                'status' => 'planned',
                'created_by' => 1, // Default user ID
                'work_instructions' => 'Manually created work order',
            ]);

            $workOrders[] = $workOrder;
        }

        return $workOrders;
    }
}
