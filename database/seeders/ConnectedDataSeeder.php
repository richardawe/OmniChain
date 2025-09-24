<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Location;
use App\Models\Product;
use App\Models\Employee;
use App\Models\Carrier;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderLineItem;
use App\Models\WorkOrder;
use App\Models\InventoryBalance;
use App\Models\FreightOrder;
use App\Models\OrderFulfillment;
use App\Models\OrderFulfillmentLineItem;
use App\Models\DeliveryTask;
use App\Models\ReturnRequest;
use App\Models\ReturnLineItem;
use App\Models\BillOfMaterial;
use App\Models\BomComponent;
use App\Models\MaterialMovement;
use App\Models\BatchTracking;
use App\Models\RoutePlan;
use App\Models\RoutePlanStop;
use App\Models\ShipmentEvent;
use App\Models\ProofOfDelivery;
use App\Models\SupplierContract;
use App\Models\SupplierCatalog;
use App\Models\Machine;
use App\Models\MachineTelemetry;
use App\Models\QualityInspection;

class ConnectedDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating connected data across all modules...');

        // 1. Create Companies (Master Data Foundation)
        $manufacturer = Company::firstOrCreate(
            ['code' => 'ACM-MFG'],
            [
            'name' => 'Acme Manufacturing Corp',
            'code' => 'ACM-MFG',
            'legal_name' => 'Acme Manufacturing Corporation',
            'business_type' => 'Manufacturer',
            'address' => '123 Industrial Blvd',
            'city' => 'Detroit',
            'state' => 'MI',
            'country' => 'USA',
            'postal_code' => '48201',
            'type' => 'shipper',
            'currency' => 'USD',
            'timezone' => 'America/Detroit',
            'status' => 'active',
            ]
        );

        $supplier = Company::firstOrCreate(
            ['code' => 'GCL-SUP'],
            [
                'name' => 'Global Components Ltd',
                'code' => 'GCL-SUP',
                'legal_name' => 'Global Components Limited',
                'business_type' => 'Supplier',
                'address' => '456 Supply Chain Ave',
                'city' => 'Chicago',
                'state' => 'IL',
                'country' => 'USA',
                'postal_code' => '60601',
                'type' => 'shipper',
                'currency' => 'USD',
                'timezone' => 'America/Chicago',
                'status' => 'active',
            ]
        );

        $carrier = Company::firstOrCreate(
            ['code' => 'ELI-CAR'],
            [
                'name' => 'Express Logistics Inc',
                'code' => 'ELI-CAR',
                'legal_name' => 'Express Logistics Incorporated',
                'business_type' => 'Carrier',
                'address' => '789 Transport Way',
                'city' => 'Memphis',
                'state' => 'TN',
                'country' => 'USA',
                'postal_code' => '38103',
                'type' => 'carrier',
                'currency' => 'USD',
                'timezone' => 'America/Chicago',
                'status' => 'active',
            ]
        );

        $customer = Company::firstOrCreate(
            ['code' => 'RDC-CUS'],
            [
                'name' => 'Retail Distribution Co',
                'code' => 'RDC-CUS',
                'legal_name' => 'Retail Distribution Company',
                'business_type' => 'Customer',
                'address' => '321 Commerce St',
                'city' => 'Atlanta',
                'state' => 'GA',
                'country' => 'USA',
                'postal_code' => '30309',
                'type' => 'customer',
                'currency' => 'USD',
                'timezone' => 'America/New_York',
                'status' => 'active',
            ]
        );

        // 2. Create Locations (Connected to Companies)
        $manufacturingPlant = Location::create([
            'company_id' => $manufacturer->id,
            'name' => 'Detroit Manufacturing Plant',
            'code' => 'DET-PLANT',
            'type' => 'Manufacturing',
            'address' => '123 Industrial Blvd',
            'city' => 'Detroit',
            'state' => 'MI',
            'country' => 'USA',
            'postal_code' => '48201',
            'latitude' => 42.3314,
            'longitude' => -83.0458,
            'timezone' => 'America/Detroit',
            'capacity_volume' => 100000,
            'capacity_weight' => 50000,
            'status' => 'active',
        ]);

        $supplierWarehouse = Location::create([
            'company_id' => $supplier->id,
            'name' => 'Chicago Distribution Center',
            'code' => 'CHI-DC',
            'type' => 'Warehouse',
            'address' => '456 Supply Chain Ave',
            'city' => 'Chicago',
            'state' => 'IL',
            'country' => 'USA',
            'postal_code' => '60601',
            'latitude' => 41.8781,
            'longitude' => -87.6298,
            'timezone' => 'America/Chicago',
            'capacity_volume' => 75000,
            'capacity_weight' => 40000,
            'status' => 'active',
        ]);

        $customerWarehouse = Location::create([
            'company_id' => $customer->id,
            'name' => 'Atlanta Distribution Hub',
            'code' => 'ATL-HUB',
            'type' => 'Distribution Center',
            'address' => '321 Commerce St',
            'city' => 'Atlanta',
            'state' => 'GA',
            'country' => 'USA',
            'postal_code' => '30309',
            'latitude' => 33.7490,
            'longitude' => -84.3880,
            'timezone' => 'America/New_York',
            'capacity_volume' => 80000,
            'capacity_weight' => 45000,
            'status' => 'active',
        ]);

        // 3. Create Products (Connected to Manufacturer)
        $finalProduct = Product::create([
            'company_id' => $manufacturer->id,
            'sku' => 'FIN-PROD-001',
            'gtin' => '1234567890123',
            'name' => 'Premium Widget Pro',
            'description' => 'High-quality premium widget',
            'category' => 'Electronics',
            'subcategory' => 'Widgets',
            'product_family' => 'Widget Pro Series',
            'brand' => 'Acme',
            'manufacturer' => 'Acme Manufacturing Corp',
            'unit_of_measure' => 'EA',
            'weight' => 2.5,
            'net_weight' => 2.2,
            'length' => 15.0,
            'width' => 10.0,
            'height' => 8.0,
            'volume' => 1200.0,
            'hazardous' => 'no',
            'temperature_controlled' => 'no',
            'batch_trackable' => true,
            'serialization_capable' => true,
            'country_of_origin' => 'USA',
            'lifecycle_status' => 'active',
            'status' => 'active',
        ]);

        $component1 = Product::create([
            'company_id' => $supplier->id,
            'sku' => 'COMP-001',
            'gtin' => '9876543210987',
            'name' => 'Base Component A',
            'description' => 'Primary base component',
            'category' => 'Components',
            'subcategory' => 'Base Components',
            'product_family' => 'Component Series A',
            'brand' => 'Global Components',
            'manufacturer' => 'Global Components Ltd',
            'unit_of_measure' => 'EA',
            'weight' => 1.0,
            'net_weight' => 0.9,
            'length' => 8.0,
            'width' => 6.0,
            'height' => 4.0,
            'volume' => 192.0,
            'hazardous' => 'no',
            'temperature_controlled' => 'no',
            'batch_trackable' => true,
            'serialization_capable' => false,
            'country_of_origin' => 'USA',
            'lifecycle_status' => 'active',
            'status' => 'active',
        ]);

        $component2 = Product::create([
            'company_id' => $supplier->id,
            'sku' => 'COMP-002',
            'gtin' => '9876543210988',
            'name' => 'Secondary Component B',
            'description' => 'Secondary component for assembly',
            'category' => 'Components',
            'subcategory' => 'Secondary Components',
            'product_family' => 'Component Series B',
            'brand' => 'Global Components',
            'manufacturer' => 'Global Components Ltd',
            'unit_of_measure' => 'EA',
            'weight' => 0.5,
            'net_weight' => 0.4,
            'length' => 5.0,
            'width' => 3.0,
            'height' => 2.0,
            'volume' => 30.0,
            'hazardous' => 'no',
            'temperature_controlled' => 'no',
            'batch_trackable' => true,
            'serialization_capable' => false,
            'country_of_origin' => 'USA',
            'lifecycle_status' => 'active',
            'status' => 'active',
        ]);

        // 4. Create Bill of Materials (Manufacturing)
        $bom = BillOfMaterial::create([
            'bom_id' => 'BOM-' . $finalProduct->sku . '-V1.0',
            'product_id' => $finalProduct->id,
            'version' => '1.0',
            'effective_date' => now()->subDays(30),
            'status' => 'active',
        ]);

        BomComponent::create([
            'bom_id' => $bom->id,
            'component_product_id' => $component1->id,
            'line_number' => 1,
            'quantity_required' => 2,
            'unit_of_measure' => 'EA',
        ]);

        BomComponent::create([
            'bom_id' => $bom->id,
            'component_product_id' => $component2->id,
            'line_number' => 2,
            'quantity_required' => 4,
            'unit_of_measure' => 'EA',
        ]);

        // 5. Create Purchase Orders (Supplier & Procurement)
        $purchaseOrder = PurchaseOrder::create([
            'po_number' => 'PO-2024-001',
            'supplier_id' => $supplier->id,
            'order_date' => now()->subDays(45),
            'required_delivery_date' => now()->subDays(15),
            'currency' => 'USD',
            'status' => 'confirmed',
            'total_amount' => 25000.00,
        ]);

        PurchaseOrderLineItem::create([
            'purchase_order_id' => $purchaseOrder->id,
            'line_number' => 1,
            'sku' => $component1->sku,
            'quantity_ordered' => 1000,
            'unit_price' => 15.00,
            'unit_of_measure' => 'EA',
            'promised_date' => now()->subDays(15),
            'delivery_location_id' => $manufacturingPlant->id,
        ]);

        PurchaseOrderLineItem::create([
            'purchase_order_id' => $purchaseOrder->id,
            'line_number' => 2,
            'sku' => $component2->sku,
            'quantity_ordered' => 2000,
            'unit_price' => 5.00,
            'unit_of_measure' => 'EA',
            'promised_date' => now()->subDays(15),
            'delivery_location_id' => $manufacturingPlant->id,
        ]);

        // 6. Create Work Orders (Manufacturing)
        $workOrder = WorkOrder::create([
            'work_order_number' => 'WO-2024-001',
            'product_id' => $finalProduct->id,
            'production_location_id' => $manufacturingPlant->id,
            'quantity_planned' => 500,
            'quantity_produced' => 475,
            'start_datetime' => now()->subDays(10),
            'end_datetime' => now()->subDays(5),
            'status' => 'completed',
        ]);

        // 7. Create Inventory Balances (Inventory & Warehousing)
        InventoryBalance::create([
            'location_id' => $manufacturingPlant->id,
            'sku' => $component1->sku,
            'quantity_on_hand' => 500,
            'quantity_available' => 480,
            'quantity_allocated' => 20,
            'reorder_point' => 100,
            'safety_stock' => 50,
        ]);

        InventoryBalance::create([
            'location_id' => $manufacturingPlant->id,
            'sku' => $component2->sku,
            'quantity_on_hand' => 1200,
            'quantity_available' => 1180,
            'quantity_allocated' => 20,
            'reorder_point' => 200,
            'safety_stock' => 100,
        ]);

        InventoryBalance::create([
            'location_id' => $manufacturingPlant->id,
            'sku' => $finalProduct->sku,
            'quantity_on_hand' => 475,
            'quantity_available' => 400,
            'quantity_allocated' => 75,
            'reorder_point' => 50,
            'safety_stock' => 25,
        ]);

        // 8. Create Freight Orders (Transportation)
        $freightOrder = FreightOrder::create([
            'order_number' => 'FO-2024-001',
            'shipper_company_id' => $manufacturer->id,
            'carrier_company_id' => $carrier->id,
            'origin_location_id' => $manufacturingPlant->id,
            'destination_location_id' => $customerWarehouse->id,
            'service_type' => 'Standard Ground',
            'priority' => 'Normal',
            'pickup_date' => now()->subDays(3),
            'delivery_date' => now()->addDays(2),
            'requested_pickup_date' => now()->subDays(3),
            'requested_delivery_date' => now()->addDays(2),
            'total_weight' => 1187.5,
            'total_volume' => 570000,
            'total_pieces' => 75,
            'declared_value' => 75000.00,
            'currency' => 'USD',
            'status' => 'in_transit',
            'cargo_description' => 'Premium Widget Pro units',
        ]);

        // 9. Create Route Plans (Transportation)
        $routePlan = RoutePlan::create([
            'freight_order_id' => $freightOrder->id,
            'route_name' => 'Detroit to Atlanta Route',
            'estimated_distance' => 750.5,
            'estimated_duration' => 12.5,
            'vehicle_type' => 'Truck',
            'status' => 'active',
        ]);

        RoutePlanStop::create([
            'route_plan_id' => $routePlan->id,
            'location_id' => $manufacturingPlant->id,
            'stop_number' => 1,
            'stop_type' => 'Pickup',
            'estimated_arrival' => now()->subDays(3),
            'estimated_departure' => now()->subDays(3)->addHours(2),
            'service_time_minutes' => 120,
        ]);

        RoutePlanStop::create([
            'route_plan_id' => $routePlan->id,
            'location_id' => $customerWarehouse->id,
            'stop_number' => 2,
            'stop_type' => 'Delivery',
            'estimated_arrival' => now()->addDays(2),
            'estimated_departure' => now()->addDays(2)->addHours(1),
            'service_time_minutes' => 60,
        ]);

        // 10. Create Shipment Events (Transportation)
        ShipmentEvent::create([
            'freight_order_id' => $freightOrder->id,
            'event_type' => 'pickup_completed',
            'event_timestamp' => now()->subDays(3)->addHours(2),
            'location_id' => $manufacturingPlant->id,
            'description' => 'Freight picked up from manufacturing plant',
            'status' => 'completed',
        ]);

        ShipmentEvent::create([
            'freight_order_id' => $freightOrder->id,
            'event_type' => 'in_transit',
            'event_timestamp' => now()->subDays(2),
            'location_id' => null,
            'description' => 'Freight in transit to destination',
            'status' => 'active',
        ]);

        // 11. Create Order Fulfillment (Delivery Management)
        $orderFulfillment = OrderFulfillment::create([
            'order_number' => 'OF-2024-001',
            'customer_id' => $customer->id,
            'fulfillment_location_id' => $manufacturingPlant->id,
            'delivery_location_id' => $customerWarehouse->id,
            'promised_delivery_date' => now()->addDays(2),
            'fulfillment_type' => 'Direct-to-customer',
            'sla_type' => 'Standard',
            'priority' => 'Normal',
            'status' => 'in_progress',
            'total_amount' => 75000.00,
            'currency' => 'USD',
        ]);

        OrderFulfillmentLineItem::create([
            'order_fulfillment_id' => $orderFulfillment->id,
            'line_number' => 1,
            'sku' => $finalProduct->sku,
            'quantity_ordered' => 75,
            'quantity_fulfilled' => 75,
            'unit_price' => 1000.00,
            'unit_of_measure' => 'EA',
            'line_total' => 75000.00,
        ]);

        // 12. Create Delivery Tasks (Delivery Management)
        $deliveryTask = DeliveryTask::create([
            'order_fulfillment_id' => $orderFulfillment->id,
            'freight_order_id' => $freightOrder->id,
            'task_number' => 'DT-2024-001',
            'delivery_location_id' => $customerWarehouse->id,
            'scheduled_delivery_date' => now()->addDays(2),
            'priority' => 'Normal',
            'status' => 'scheduled',
            'special_instructions' => 'Deliver during business hours',
        ]);

        // 13. Create Return Request (Returns & Reverse Logistics)
        $returnRequest = ReturnRequest::create([
            'return_number' => 'RT-2024-001',
            'customer_id' => $customer->id,
            'return_location_id' => $customerWarehouse->id,
            'original_order_id' => $orderFulfillment->id,
            'return_reason' => 'Defective Product',
            'return_date' => now()->addDays(5),
            'status' => 'pending',
            'total_value' => 1000.00,
            'currency' => 'USD',
        ]);

        ReturnLineItem::create([
            'return_request_id' => $returnRequest->id,
            'line_number' => 1,
            'sku' => $finalProduct->sku,
            'quantity_returned' => 1,
            'unit_price' => 1000.00,
            'reason_code' => 'DEFECT',
            'condition' => 'Damaged',
        ]);

        // 14. Create Supplier Contract (Supplier & Procurement)
        SupplierContract::create([
            'supplier_id' => $supplier->id,
            'contract_number' => 'SC-2024-001',
            'start_date' => now()->subDays(60),
            'end_date' => now()->addDays(305),
            'contract_type' => 'Supply Agreement',
            'status' => 'active',
            'payment_terms' => 'Net 30',
            'currency' => 'USD',
        ]);

        // 15. Create Supplier Catalog (Supplier & Procurement)
        SupplierCatalog::create([
            'supplier_id' => $supplier->id,
            'sku' => $component1->sku,
            'supplier_part_number' => 'GCL-COMP-001',
            'lead_time_days' => 7,
            'minimum_order_quantity' => 100,
            'unit_price' => 15.00,
            'currency' => 'USD',
            'status' => 'active',
        ]);

        SupplierCatalog::create([
            'supplier_id' => $supplier->id,
            'sku' => $component2->sku,
            'supplier_part_number' => 'GCL-COMP-002',
            'lead_time_days' => 5,
            'minimum_order_quantity' => 200,
            'unit_price' => 5.00,
            'currency' => 'USD',
            'status' => 'active',
        ]);

        // 16. Create Batch Tracking (Manufacturing)
        BatchTracking::create([
            'batch_number' => 'BATCH-2024-001',
            'product_id' => $finalProduct->id,
            'work_order_id' => $workOrder->id,
            'quantity' => 475,
            'production_date' => now()->subDays(5),
            'expiry_date' => now()->addDays(365),
            'quality_status' => 'Passed',
            'status' => 'Active',
        ]);

        // 17. Create Material Movement (Manufacturing)
        MaterialMovement::create([
            'movement_number' => 'MM-2024-001',
            'from_location_id' => $manufacturingPlant->id,
            'to_location_id' => $customerWarehouse->id,
            'sku' => $finalProduct->sku,
            'quantity' => 75,
            'movement_type' => 'Outbound Shipment',
            'movement_reason' => 'Customer Order',
            'timestamp' => now()->subDays(3),
            'status' => 'Completed',
        ]);

        $this->command->info('Connected data created successfully across all modules!');
        $this->command->info('Created:');
        $this->command->info('- 4 Companies (Manufacturer, Supplier, Carrier, Customer)');
        $this->command->info('- 3 Locations (Manufacturing Plant, Supplier Warehouse, Customer Warehouse)');
        $this->command->info('- 3 Products (Final Product + 2 Components)');
        $this->command->info('- 1 Bill of Materials with 2 Components');
        $this->command->info('- 1 Purchase Order with 2 Line Items');
        $this->command->info('- 1 Work Order');
        $this->command->info('- 3 Inventory Balances');
        $this->command->info('- 1 Freight Order with Route Plan and Stops');
        $this->command->info('- 2 Shipment Events');
        $this->command->info('- 1 Order Fulfillment with Line Items');
        $this->command->info('- 1 Delivery Task');
        $this->command->info('- 1 Return Request with Line Items');
        $this->command->info('- 1 Supplier Contract');
        $this->command->info('- 2 Supplier Catalog Entries');
        $this->command->info('- 1 Batch Tracking Record');
        $this->command->info('- 1 Material Movement Record');
    }
}
