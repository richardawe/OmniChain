<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\Location;
use App\Models\FreightOrder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create sample companies
        $shipper = Company::create([
            'name' => 'Acme Manufacturing',
            'code' => 'ACME001',
            'legal_name' => 'Acme Manufacturing Corp.',
            'email' => 'shipping@acme.com',
            'phone' => '+1-555-0123',
            'address' => '123 Industrial Blvd',
            'city' => 'Chicago',
            'state' => 'IL',
            'country' => 'USA',
            'postal_code' => '60601',
            'type' => 'shipper',
            'status' => 'active',
        ]);

        $carrier = Company::create([
            'name' => 'Swift Logistics',
            'code' => 'SWIFT001',
            'legal_name' => 'Swift Logistics LLC',
            'email' => 'dispatch@swiftlogistics.com',
            'phone' => '+1-555-0456',
            'address' => '456 Transport Way',
            'city' => 'Dallas',
            'state' => 'TX',
            'country' => 'USA',
            'postal_code' => '75201',
            'type' => 'carrier',
            'status' => 'active',
        ]);

        // Create sample locations
        $origin = Location::create([
            'company_id' => $shipper->id,
            'name' => 'Acme Chicago Warehouse',
            'code' => 'ACME-CHI-01',
            'type' => 'warehouse',
            'address' => '123 Industrial Blvd',
            'city' => 'Chicago',
            'state' => 'IL',
            'country' => 'USA',
            'postal_code' => '60601',
            'latitude' => 41.8781,
            'longitude' => -87.6298,
            'status' => 'active',
        ]);

        $destination = Location::create([
            'company_id' => $shipper->id,
            'name' => 'Acme Los Angeles DC',
            'code' => 'ACME-LAX-01',
            'type' => 'distribution_center',
            'address' => '789 Distribution Ave',
            'city' => 'Los Angeles',
            'state' => 'CA',
            'country' => 'USA',
            'postal_code' => '90001',
            'latitude' => 34.0522,
            'longitude' => -118.2437,
            'status' => 'active',
        ]);

        // Create sample freight orders
        $order1 = FreightOrder::create([
            'order_number' => 'FO-20250920-0001',
            'shipper_company_id' => $shipper->id,
            'carrier_company_id' => $carrier->id,
            'assigned_driver_id' => null, // Will be assigned after driver is created
            'origin_location_id' => $origin->id,
            'destination_location_id' => $destination->id,
            'service_type' => 'ltl',
            'priority' => 'normal',
            'requested_pickup_date' => now()->addDays(1),
            'requested_delivery_date' => now()->addDays(5),
            'total_weight' => 2500.50,
            'total_volume' => 12.5,
            'total_pieces' => 15,
            'declared_value' => 50000.00,
            'status' => 'booked',
            'special_instructions' => 'Handle with care - fragile items included',
        ]);

        $order2 = FreightOrder::create([
            'order_number' => 'FO-20250920-0002',
            'shipper_company_id' => $shipper->id,
            'carrier_company_id' => $carrier->id,
            'assigned_driver_id' => null, // Will be assigned after driver is created
            'origin_location_id' => $origin->id,
            'destination_location_id' => $destination->id,
            'service_type' => 'ftl',
            'priority' => 'high',
            'requested_pickup_date' => now()->addDays(2),
            'requested_delivery_date' => now()->addDays(4),
            'total_weight' => 15000.00,
            'total_volume' => 68.0,
            'total_pieces' => 1,
            'declared_value' => 125000.00,
            'status' => 'booked',
            'special_instructions' => 'Temperature controlled shipment - maintain 2-8°C',
        ]);

        $order3 = FreightOrder::create([
            'order_number' => 'FO-20250920-0003',
            'shipper_company_id' => $shipper->id,
            'carrier_company_id' => null,
            'assigned_driver_id' => null, // Will be assigned after driver is created
            'origin_location_id' => $origin->id,
            'destination_location_id' => $destination->id,
            'service_type' => 'air',
            'priority' => 'urgent',
            'requested_pickup_date' => now()->addHours(6),
            'requested_delivery_date' => now()->addDays(1),
            'total_weight' => 500.25,
            'total_volume' => 2.5,
            'total_pieces' => 3,
            'declared_value' => 25000.00,
            'status' => 'booked',
            'special_instructions' => 'Same day pickup required',
        ]);

        // Assign orders to the driver after driver is created
        // This will be handled by the DriverSeeder

        // Create sample shipment events for tracking
        \App\Models\ShipmentEvent::create([
            'freight_order_id' => $order1->id,
            'event_type' => 'pickup',
            'event_code' => 'PU',
            'event_time' => now()->subDays(1),
            'location_name' => 'Acme Chicago Warehouse',
            'city' => 'Chicago',
            'state' => 'IL',
            'country' => 'USA',
            'latitude' => 41.8781,
            'longitude' => -87.6298,
            'description' => 'Shipment picked up from origin',
            'source' => 'carrier_api',
            'reference_number' => 'PU-' . now()->format('YmdHis'),
        ]);

        \App\Models\ShipmentEvent::create([
            'freight_order_id' => $order1->id,
            'event_type' => 'in_transit',
            'event_code' => 'IT',
            'event_time' => now()->subHours(12),
            'location_name' => 'Kansas City Hub',
            'city' => 'Kansas City',
            'state' => 'MO',
            'country' => 'USA',
            'latitude' => 39.0997,
            'longitude' => -94.5786,
            'description' => 'Shipment arrived at sorting facility',
            'source' => 'carrier_api',
            'reference_number' => 'IT-' . now()->format('YmdHis'),
        ]);

        \App\Models\ShipmentEvent::create([
            'freight_order_id' => $order1->id,
            'event_type' => 'in_transit',
            'event_code' => 'IT',
            'event_time' => now()->subHours(2),
            'location_name' => 'Denver Distribution Center',
            'city' => 'Denver',
            'state' => 'CO',
            'country' => 'USA',
            'latitude' => 39.7392,
            'longitude' => -104.9903,
            'description' => 'Shipment departed for final destination',
            'source' => 'carrier_api',
            'reference_number' => 'IT-' . now()->format('YmdHis'),
        ]);

        \App\Models\ShipmentEvent::create([
            'freight_order_id' => $order2->id,
            'event_type' => 'pickup',
            'event_code' => 'PU',
            'event_time' => now()->subHours(6),
            'location_name' => 'Acme Chicago Warehouse',
            'city' => 'Chicago',
            'state' => 'IL',
            'country' => 'USA',
            'latitude' => 41.8781,
            'longitude' => -87.6298,
            'description' => 'Full truckload picked up',
            'source' => 'carrier_api',
            'reference_number' => 'PU-' . now()->format('YmdHis'),
        ]);

        \App\Models\ShipmentEvent::create([
            'freight_order_id' => $order2->id,
            'event_type' => 'in_transit',
            'event_code' => 'IT',
            'event_time' => now()->subHours(1),
            'location_name' => 'St. Louis Terminal',
            'city' => 'St. Louis',
            'state' => 'MO',
            'country' => 'USA',
            'latitude' => 38.6270,
            'longitude' => -90.1994,
            'description' => 'Shipment in transit - making good time',
            'source' => 'carrier_api',
            'reference_number' => 'IT-' . now()->format('YmdHis'),
        ]);

        // Run additional seeders for comprehensive data
        $this->call([
            MasterDataSeeder::class,
            InventoryWarehouseSeeder::class,
            ReturnsSeeder::class,
            SupplierProcurementSeeder::class,
            TransportationDeliverySeeder::class,
            ManufacturingSeeder::class,
        ]);
    }
}
