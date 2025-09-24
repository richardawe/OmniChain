<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InventoryBalance;
use App\Models\WarehouseBin;
use App\Models\CycleCount;
use App\Models\InboundReceiving;
use App\Models\PutawayRecord;
use App\Models\OutboundShipment;
use App\Models\CrossDockEvent;
use App\Models\Location;
use App\Models\Product;
use App\Models\User;
use App\Models\Company;
use App\Models\OrderFulfillment;

class InventoryWarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data to prevent unique constraint violations
        CrossDockEvent::truncate();
        OutboundShipment::truncate();
        PutawayRecord::truncate();
        InboundReceiving::truncate();
        CycleCount::truncate();
        InventoryBalance::truncate();
        WarehouseBin::truncate();
        
        // Create warehouse bins
        $this->createWarehouseBins();
        
        // Create inventory balances
        $this->createInventoryBalances();
        
        // Create cycle counts
        $this->createCycleCounts();
        
        // Create inbound receiving
        $this->createInboundReceiving();
        
        // Create putaway records
        $this->createPutawayRecords();
        
        // Create outbound shipments
        $this->createOutboundShipments();
        
        // Create cross dock events
        $this->createCrossDockEvents();
    }
    
    private function createWarehouseBins()
    {
        $locations = Location::limit(3)->get();
        $binTypes = ['pallet', 'shelf', 'floor', 'cold_storage', 'hazardous'];
        $zones = ['A', 'B', 'C', 'D'];
        $aisles = range(1, 10);
        $racks = range(1, 5);
        $levels = range(1, 4);
        
        $binCounter = 1;
        foreach ($locations as $location) {
            for ($i = 0; $i < 15; $i++) {
                WarehouseBin::create([
                    'bin_id' => 'BIN-' . str_pad($binCounter++, 3, '0', STR_PAD_LEFT),
                    'bin_name' => 'Bin ' . ($i + 1),
                    'location_id' => $location->id,
                    'bin_type' => fake()->randomElement(['storage', 'picking', 'receiving', 'shipping', 'cross_dock', 'quarantine']),
                    'zone' => fake()->randomElement($zones),
                    'aisle' => fake()->randomElement($aisles),
                    'rack' => fake()->randomElement($racks),
                    'level' => fake()->randomElement($levels),
                    'position' => fake()->randomElement(['left', 'right', 'center']),
                    'capacity_volume' => fake()->randomFloat(3, 1, 50),
                    'capacity_weight' => fake()->randomFloat(3, 100, 2000),
                    'current_volume' => fake()->randomFloat(3, 0, 45),
                    'current_weight' => fake()->randomFloat(3, 0, 1800),
                    'utilization_percentage' => fake()->randomFloat(2, 0, 100),
                    'status' => fake()->randomElement(['active', 'inactive', 'maintenance', 'reserved']),
                    'requires_temperature_control' => fake()->boolean(20),
                    'min_temperature' => fake()->numberBetween(-20, 5),
                    'max_temperature' => fake()->numberBetween(5, 25),
                    'hazardous_material_allowed' => fake()->boolean(10),
                    'bin_metadata' => [
                        'last_inspection' => fake()->dateTimeBetween('-30 days'),
                        'maintenance_notes' => fake()->optional()->sentence(),
                    ]
                ]);
            }
        }
    }
    
    private function createInventoryBalances()
    {
        $locations = Location::limit(3)->get();
        $products = Product::limit(10)->get();
        $bins = WarehouseBin::limit(20)->get();
        
        foreach ($locations as $location) {
            foreach ($products as $product) {
                $bin = fake()->randomElement($bins);
                
                $quantityOnHand = fake()->numberBetween(0, 1000);
                $averageCost = fake()->randomFloat(2, 1, 100);
                
                InventoryBalance::create([
                    'location_id' => $location->id,
                    'product_id' => $product->id,
                    'lot_bin' => 'LOT-' . fake()->unique()->numerify('######'),
                    'quantity_on_hand' => $quantityOnHand,
                    'quantity_available' => fake()->numberBetween(0, $quantityOnHand),
                    'quantity_allocated' => fake()->numberBetween(0, $quantityOnHand),
                    'quantity_on_order' => fake()->numberBetween(0, 500),
                    'reorder_point' => fake()->numberBetween(10, 100),
                    'safety_stock' => fake()->numberBetween(5, 50),
                    'max_stock_level' => fake()->numberBetween(500, 2000),
                    'last_count_date' => fake()->dateTimeBetween('-60 days'),
                    'last_count_quantity' => fake()->numberBetween(0, $quantityOnHand),
                    'average_cost' => $averageCost,
                    'total_value' => $quantityOnHand * $averageCost,
                    'status' => fake()->randomElement(['active', 'inactive', 'quarantined', 'reserved']),
                    'inventory_metadata' => [
                        'supplier' => fake()->company(),
                        'expiry_date' => fake()->dateTimeBetween('+30 days', '+2 years'),
                        'notes' => fake()->optional()->sentence(),
                    ]
                ]);
            }
        }
    }
    
    private function createCycleCounts()
    {
        $locations = Location::limit(3)->get();
        $products = Product::limit(10)->get();
        $bins = WarehouseBin::limit(20)->get();
        $users = User::limit(5)->get();
        
        // Skip if no data available
        if ($locations->isEmpty() || $products->isEmpty() || $bins->isEmpty() || $users->isEmpty()) {
            return;
        }
        
        for ($i = 0; $i < 25; $i++) {
            $location = fake()->randomElement($locations);
            $product = fake()->randomElement($products);
            $bin = fake()->randomElement($bins);
            
            $systemQty = fake()->numberBetween(0, 1000);
            $countedQty = $systemQty + fake()->numberBetween(-50, 50);
            $discrepancy = $countedQty - $systemQty;
            
            CycleCount::create([
                'count_id' => 'CC-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'location_id' => $location->id,
                'product_id' => $product->id,
                'lot_bin' => 'LOT-' . fake()->numerify('######'),
                'system_quantity' => $systemQty,
                'counted_quantity' => $countedQty,
                'discrepancy' => $discrepancy,
                'discrepancy_reason' => $discrepancy != 0 ? fake()->randomElement(['damage', 'theft', 'error', 'expired']) : null,
                'counter_id' => fake()->randomElement($users)->id,
                'supervisor_id' => fake()->randomElement($users)->id,
                'count_date' => fake()->dateTimeBetween('-30 days'),
                'count_status' => fake()->randomElement(['scheduled', 'in_progress', 'completed', 'disputed', 'approved']),
                'count_type' => fake()->randomElement(['cycle', 'full', 'spot', 'random']),
                'audit_comments' => fake()->optional()->sentence(),
            ]);
        }
    }
    
    private function createInboundReceiving()
    {
        $companies = Company::limit(5)->get();
        $users = User::limit(5)->get();
        
        for ($i = 0; $i < 20; $i++) {
            InboundReceiving::create([
                'receiving_id' => 'REC-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'po_number' => fake()->optional()->numerify('PO-######'),
                'carrier_id' => fake()->randomElement($companies)->id,
                'location_id' => fake()->numberBetween(1, 3),
                'asn_number' => fake()->optional()->numerify('ASN-######'),
                'bill_of_lading' => fake()->optional()->numerify('BOL-########'),
                'container_number' => fake()->optional()->numerify('CONT-########'),
                'expected_arrival_time' => fake()->dateTimeBetween('-7 days', '+7 days'),
                'actual_arrival_time' => fake()->optional()->dateTimeBetween('-5 days', 'now'),
                'unload_start_time' => fake()->optional()->dateTimeBetween('-3 days', 'now'),
                'unload_complete_time' => fake()->optional()->dateTimeBetween('-2 days', 'now'),
                'status' => fake()->randomElement(['expected', 'arrived', 'unloading', 'unloaded', 'qc_pending', 'qc_completed', 'putaway_pending', 'completed']),
                'total_weight' => fake()->randomFloat(3, 100, 5000),
                'total_volume' => fake()->randomFloat(3, 1, 100),
                'total_packages' => fake()->numberBetween(1, 100),
                'carrier_notes' => fake()->optional()->sentence(),
                'receiving_notes' => fake()->optional()->sentence(),
                'expected_items' => [
                    'total_items' => fake()->numberBetween(1, 50),
                    'item_types' => fake()->randomElements(['electronics', 'clothing', 'books', 'tools'], 2),
                ],
                'received_items' => [
                    'total_received' => fake()->numberBetween(1, 50),
                    'quality_status' => fake()->randomElement(['good', 'damaged', 'missing']),
                ],
                'qc_results' => [
                    'passed' => fake()->boolean(80),
                    'failed_items' => fake()->numberBetween(0, 5),
                    'notes' => fake()->optional()->sentence(),
                ],
                'receiving_metadata' => [
                    'temperature' => fake()->randomFloat(1, -10, 25),
                    'humidity' => fake()->randomFloat(1, 30, 80),
                    'special_handling' => fake()->optional()->sentence(),
                ]
            ]);
        }
    }
    
    private function createPutawayRecords()
    {
        $inboundReceivings = InboundReceiving::limit(15)->get();
        $products = Product::limit(10)->get();
        $bins = WarehouseBin::limit(20)->get();
        $users = User::limit(5)->get();
        $locations = Location::limit(3)->get();
        
        foreach ($inboundReceivings as $receiving) {
            $product = fake()->randomElement($products);
            $bin = fake()->randomElement($bins);
            $location = fake()->randomElement($locations);
            
            PutawayRecord::create([
                'putaway_id' => 'PA-' . fake()->unique()->numerify('######'),
                'receiving_id' => $receiving->id,
                'product_id' => $product->id,
                'lot_bin' => fake()->optional()->numerify('LOT-######'),
                'quantity' => fake()->numberBetween(10, 500),
                'unit_of_measure' => fake()->randomElement(['pcs', 'kg', 'm', 'L']),
                'from_location_id' => $location->id,
                'to_bin_id' => $bin->id,
                'putaway_timestamp' => fake()->dateTimeBetween('-5 days', 'now'),
                'putaway_operator_id' => fake()->randomElement($users)->id,
                'supervisor_id' => fake()->randomElement($users)->id,
                'status' => fake()->randomElement(['pending', 'in_progress', 'completed', 'cancelled']),
                'putaway_notes' => fake()->optional()->sentence(),
                'putaway_metadata' => [
                    'quality_check' => fake()->boolean(80),
                    'temperature_controlled' => fake()->boolean(20),
                ]
            ]);
        }
    }
    
    private function createOutboundShipments()
    {
        $orderFulfillments = OrderFulfillment::limit(15)->get();
        $companies = Company::limit(5)->get();
        $locations = Location::limit(3)->get();
        
        foreach ($orderFulfillments as $fulfillment) {
            $carrier = fake()->randomElement($companies);
            $shipFrom = fake()->randomElement($locations);
            $shipTo = fake()->randomElement($locations);
            
            OutboundShipment::create([
                'shipment_id' => 'SHIP-' . str_pad($fulfillment->id, 4, '0', STR_PAD_LEFT),
                'pick_list_id' => fake()->optional()->numerify('PICK-######'),
                'ship_from_location_id' => $shipFrom->id,
                'ship_to_location_id' => $shipTo->id,
                'carrier_id' => $carrier->id,
                'tracking_number' => fake()->optional()->numerify('TRK-##########'),
                'scheduled_ship_date' => fake()->dateTimeBetween('-5 days', '+5 days'),
                'actual_ship_date' => fake()->optional()->dateTimeBetween('-3 days', 'now'),
                'estimated_delivery_date' => fake()->dateTimeBetween('now', '+14 days'),
                'actual_delivery_date' => fake()->optional()->dateTimeBetween('now', '+10 days'),
                'total_weight' => fake()->randomFloat(2, 1, 1000),
                'total_volume' => fake()->randomFloat(3, 0.1, 10),
                'shipping_cost' => fake()->randomFloat(2, 50, 500),
                'status' => fake()->randomElement(['pending', 'picked', 'packed', 'shipped', 'delivered']),
                'packed_items' => [
                    'total_items' => fake()->numberBetween(1, 50),
                    'total_weight' => fake()->randomFloat(2, 1, 500),
                    'packaging_type' => fake()->randomElement(['box', 'pallet', 'envelope', 'tube']),
                ],
                'shipping_metadata' => [
                    'service_level' => fake()->randomElement(['standard', 'express', 'overnight']),
                    'insurance_value' => fake()->randomFloat(2, 100, 5000),
                    'special_instructions' => fake()->optional()->sentence(),
                ]
            ]);
        }
    }
    
    private function createCrossDockEvents()
    {
        $inboundReceivings = InboundReceiving::limit(10)->get();
        $outboundShipments = OutboundShipment::limit(10)->get();
        $locations = Location::limit(3)->get();
        $users = User::limit(5)->get();
        
        // Skip if no data available
        if ($inboundReceivings->isEmpty() || $outboundShipments->isEmpty() || $locations->isEmpty() || $users->isEmpty()) {
            return;
        }
        
        for ($i = 0; $i < 15; $i++) {
            $incoming = fake()->randomElement($inboundReceivings);
            $outgoing = fake()->randomElement($outboundShipments);
            $location = fake()->randomElement($locations);
            
            CrossDockEvent::create([
                'event_id' => 'CD-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'incoming_shipment_id' => $incoming->id,
                'outgoing_shipment_id' => $outgoing->id,
                'cross_dock_location_id' => $location->id,
                'transfer_start_time' => fake()->dateTimeBetween('-2 days', 'now'),
                'transfer_complete_time' => fake()->optional()->dateTimeBetween('-1 days', 'now'),
                'transfer_duration_minutes' => fake()->randomFloat(2, 30, 480),
                'status' => fake()->randomElement(['scheduled', 'in_progress', 'completed', 'delayed', 'cancelled']),
                'items_transferred' => fake()->numberBetween(1, 100),
                'weight_transferred' => fake()->randomFloat(2, 1, 1000),
                'volume_transferred' => fake()->randomFloat(3, 0.1, 10),
                'operator_id' => fake()->randomElement($users)->id,
                'transfer_notes' => fake()->optional()->sentence(),
                'transfer_metadata' => [
                    'quality_check' => fake()->boolean(90),
                    'temperature_maintained' => fake()->boolean(80),
                ]
            ]);
        }
    }
}
