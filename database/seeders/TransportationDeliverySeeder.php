<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Location;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\RoutePlan;
use App\Models\RoutePlanStop;
use App\Models\VehicleTelematics;
use App\Models\ProofOfDelivery;
use App\Models\CustomsDocumentation;
use App\Models\ContainerTracking;
use App\Models\TerminalEvent;
use App\Models\OrderFulfillment;
use App\Models\OrderFulfillmentLineItem;
use App\Models\DeliveryTask;
use App\Models\GeofenceEvent;
use App\Models\CustomerNotification;
use App\Models\DeliveryException;
use App\Models\Product;
use App\Models\FreightOrder;

class TransportationDeliverySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating transportation and delivery sample data...');

        // Get existing companies and locations
        $carrierCompanies = Company::where('type', 'carrier')->get();
        $shipperCompanies = Company::where('type', 'shipper')->get();
        $warehouses = Location::where('type', 'warehouse')->get();
        $drivers = User::whereNotNull('driver_license')->get();
        $products = Product::take(10)->get();

        if ($carrierCompanies->isEmpty() || $warehouses->isEmpty() || $drivers->isEmpty()) {
            $this->command->error('Required data not found. Please run MasterDataSeeder and DriverSeeder first.');
            return;
        }

        // Create vehicles
        $this->createVehicles($carrierCompanies, $drivers);

        // Create route plans
        $this->createRoutePlans($carrierCompanies, $warehouses, $drivers);

        // Create vehicle telematics
        $this->createVehicleTelematics();

        // Create order fulfillments
        $this->createOrderFulfillments($shipperCompanies, $warehouses);

        // Create delivery tasks
        $this->createDeliveryTasks();

        // Create proof of deliveries
        $this->createProofOfDeliveries();

        // Create customs documentation
        $this->createCustomsDocumentations();

        // Create container tracking
        $this->createContainerTracking();

        // Create terminal events
        $this->createTerminalEvents();

        // Create geofence events
        $this->createGeofenceEvents();

        // Create customer notifications
        $this->createCustomerNotifications();

        // Create delivery exceptions
        $this->createDeliveryExceptions();

        $this->command->info('Transportation and delivery sample data created successfully!');
    }

    private function createVehicles($carrierCompanies, $drivers)
    {
        $this->command->info('Creating vehicles...');

        $vehicleTypes = ['truck', 'van', 'trailer', 'container'];
        $makes = ['Ford', 'Chevrolet', 'Mercedes-Benz', 'Volvo', 'Freightliner', 'Peterbilt'];
        $models = ['F-150', 'Silverado', 'Sprinter', 'FH16', 'Cascadia', '389'];

        foreach ($carrierCompanies as $company) {
            for ($i = 1; $i <= 3; $i++) {
                Vehicle::create([
                    'company_id' => $company->id,
                    'vehicle_number' => "VEH-{$company->code}-{$i}-" . rand(1000, 9999),
                    'license_plate' => strtoupper(substr($company->code, 0, 2)) . rand(1000, 9999),
                    'make' => $makes[array_rand($makes)],
                    'model' => $models[array_rand($models)],
                    'year' => rand(2018, 2024),
                    'vehicle_type' => $vehicleTypes[array_rand($vehicleTypes)],
                    'vehicle_class' => ['light_duty', 'medium_duty', 'heavy_duty'][array_rand([0, 1, 2])],
                    'max_weight_kg' => rand(5000, 25000),
                    'max_volume_cubic_meters' => rand(20, 80),
                    'max_pallets' => rand(12, 26),
                    'equipment_features' => [
                        'refrigeration' => rand(0, 1) ? true : false,
                        'liftgate' => rand(0, 1) ? true : false,
                        'temperature_control' => rand(0, 1) ? true : false,
                        'gps_tracking' => true,
                        'fuel_monitoring' => true
                    ],
                    'fuel_type' => ['diesel', 'electric', 'hybrid'][array_rand([0, 1, 2])],
                    'fuel_capacity_liters' => rand(80, 200),
                    'average_fuel_consumption_km_per_liter' => rand(8, 15) / 10,
                    'insurance_policy_number' => 'INS-' . rand(100000, 999999),
                    'insurance_expiry_date' => now()->addMonths(rand(1, 12)),
                    'registration_number' => 'REG-' . rand(100000, 999999),
                    'registration_expiry_date' => now()->addMonths(rand(1, 12)),
                    'vin_number' => 'VIN' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
                    'status' => ['active', 'active', 'active', 'maintenance'][array_rand([0, 1, 2, 3])],
                    'assigned_driver_id' => $drivers->random()->id,
                    'vehicle_metadata' => [
                        'last_service_date' => now()->subDays(rand(1, 90))->toDateString(),
                        'next_service_due' => now()->addDays(rand(30, 180))->toDateString(),
                        'mileage' => rand(50000, 200000)
                    ]
                ]);
            }
        }
    }

    private function createRoutePlans($carrierCompanies, $warehouses, $drivers)
    {
        $this->command->info('Creating route plans...');

        $vehicles = Vehicle::all();
        $freightOrders = FreightOrder::take(5)->get();

        foreach ($carrierCompanies->take(2) as $company) {
            for ($i = 1; $i <= 3; $i++) {
                $vehicle = $vehicles->where('company_id', $company->id)->random();
                $driver = $drivers->random();
                
                $routePlan = RoutePlan::create([
                    'route_number' => "RT-" . now()->format('Ymd') . "-{$i}-" . rand(1000, 9999),
                    'carrier_company_id' => $company->id,
                    'assigned_driver_id' => $driver->id,
                    'vehicle_id' => $vehicle->id,
                    'route_type' => ['delivery', 'pickup', 'mixed'][array_rand([0, 1, 2])],
                    'status' => ['planned', 'assigned', 'in_progress'][array_rand([0, 1, 2])],
                    'planned_date' => now()->addDays(rand(1, 7)),
                    'planned_start_time' => now()->addDays(rand(1, 7))->setHour(8)->setMinute(0),
                    'planned_end_time' => now()->addDays(rand(1, 7))->setHour(17)->setMinute(0),
                    'total_stops' => rand(3, 8),
                    'completed_stops' => rand(0, 3),
                    'total_distance_km' => rand(50, 300),
                    'estimated_duration_minutes' => rand(240, 480),
                    'fuel_cost' => rand(50, 150),
                    'toll_cost' => rand(10, 50),
                    'route_waypoints' => [
                        ['lat' => 40.7128, 'lon' => -74.0060, 'name' => 'Start'],
                        ['lat' => 40.7589, 'lon' => -73.9851, 'name' => 'Stop 1'],
                        ['lat' => 40.7505, 'lon' => -73.9934, 'name' => 'Stop 2'],
                        ['lat' => 40.7282, 'lon' => -73.9942, 'name' => 'End']
                    ],
                    'special_instructions' => 'Handle with care. Deliver during business hours only.',
                    'created_by_user_id' => 1
                ]);

                // Create route plan stops
                $this->createRoutePlanStops($routePlan, $warehouses, $freightOrders);
            }
        }
    }

    private function createRoutePlanStops($routePlan, $warehouses, $freightOrders)
    {
        $stopTypes = ['pickup', 'delivery', 'service', 'fuel'];
        
        for ($i = 1; $i <= $routePlan->total_stops; $i++) {
            RoutePlanStop::create([
                'route_plan_id' => $routePlan->id,
                'sequence_number' => $i,
                'location_id' => $warehouses->random()->id,
                'freight_order_id' => $freightOrders->random()->id ?? null,
                'stop_type' => $stopTypes[array_rand($stopTypes)],
                'status' => ['pending', 'completed'][array_rand([0, 1])],
                'planned_arrival_time' => $routePlan->planned_start_time->addHours($i),
                'planned_departure_time' => $routePlan->planned_start_time->addHours($i)->addMinutes(30),
                'planned_duration_minutes' => 30,
                'distance_from_previous_km' => rand(5, 25),
                'special_instructions' => 'Call ahead before arrival',
                'notes' => 'Customer prefers morning deliveries'
            ]);
        }
    }

    private function createVehicleTelematics()
    {
        $this->command->info('Creating vehicle telematics data...');

        $vehicles = Vehicle::with('routePlans')->get();
        
        foreach ($vehicles as $vehicle) {
            $routePlan = $vehicle->routePlans->first();
            
            for ($i = 0; $i < 20; $i++) {
                VehicleTelematics::create([
                    'vehicle_id' => $vehicle->id,
                    'route_plan_id' => $routePlan?->id,
                    'driver_id' => $vehicle->assigned_driver_id,
                    'timestamp' => now()->subHours(rand(1, 24)),
                    'latitude' => 40.7128 + (rand(-100, 100) / 1000),
                    'longitude' => -74.0060 + (rand(-100, 100) / 1000),
                    'speed_kmh' => rand(0, 80),
                    'heading_degrees' => rand(0, 360),
                    'altitude_meters' => rand(0, 100),
                    'odometer_km' => rand(50000, 200000),
                    'fuel_level_percentage' => rand(10, 100),
                    'fuel_consumed_liters' => rand(1, 10),
                    'engine_rpm' => rand(800, 3000),
                    'engine_load_percentage' => rand(10, 90),
                    'coolant_temperature_celsius' => rand(85, 105),
                    'battery_voltage' => rand(12, 14),
                    'engine_on' => rand(0, 1) ? true : false,
                    'driver_seatbelt' => rand(0, 1) ? true : false,
                    'door_open' => rand(0, 1) ? true : false,
                    'diagnostic_codes' => rand(0, 1) ? ['P0301', 'P0171'] : [],
                    'telematics_metadata' => [
                        'gps_accuracy' => rand(1, 5),
                        'signal_strength' => rand(1, 5),
                        'battery_level' => rand(20, 100)
                    ]
                ]);
            }
        }
    }

    private function createOrderFulfillments($shipperCompanies, $warehouses)
    {
        $this->command->info('Creating order fulfillments...');
        
        $statuses = ['received', 'processing', 'picked', 'packed', 'shipped', 'delivered'];
        $orderTypes = ['standard', 'express', 'same_day', 'scheduled'];
        $priorities = ['low', 'normal', 'high', 'urgent'];
        
        for ($i = 0; $i < 25; $i++) {
            \App\Models\OrderFulfillment::create([
                'customer_company_id' => $shipperCompanies->random()->id,
                'fulfillment_center_id' => $warehouses->random()->id,
                'order_number' => 'OF-' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'order_date' => now()->subDays(rand(1, 30)),
                'requested_delivery_date' => now()->addDays(rand(1, 14)),
                'promised_delivery_date' => now()->addDays(rand(1, 10)),
                'order_type' => $orderTypes[array_rand($orderTypes)],
                'priority' => $priorities[array_rand($priorities)],
                'status' => $statuses[array_rand($statuses)],
                'total_line_items' => rand(1, 20),
                'total_quantity' => rand(1, 100),
                'total_weight_kg' => rand(1, 1000),
                'total_volume_cubic_meters' => rand(1, 100),
                'order_value' => rand(100, 10000),
                'currency_code' => 'USD',
                'customer_notes' => rand(0, 1) ? fake()->sentence() : null,
                'internal_notes' => rand(0, 1) ? fake()->sentence() : null,
                'assigned_warehouse_user_id' => null,
                'fulfillment_metadata' => [
                    'source' => fake()->randomElement(['web', 'api', 'phone', 'email']),
                    'customer_segment' => fake()->randomElement(['premium', 'standard', 'basic']),
                    'delivery_window' => fake()->randomElement(['morning', 'afternoon', 'evening', 'anytime'])
                ]
            ]);
        }
    }

    private function createDeliveryTasks()
    {
        $this->command->info('Creating delivery tasks...');
        
        $orderFulfillments = \App\Models\OrderFulfillment::all();
        $drivers = \App\Models\User::where('driver_license', '!=', null)->get();
        
        if ($orderFulfillments->isEmpty() || $drivers->isEmpty()) {
            $this->command->warn('No order fulfillments or drivers found, skipping delivery tasks');
            return;
        }
        
        $taskTypes = ['pickup', 'delivery', 'return_pickup', 'service_call'];
        $priorities = ['low', 'normal', 'high', 'urgent'];
        $statuses = ['pending', 'assigned', 'in_progress', 'completed', 'failed', 'cancelled'];
        $locations = \App\Models\Location::all();
        
        for ($i = 0; $i < 30; $i++) {
            $orderFulfillment = $orderFulfillments->random();
            
            \App\Models\DeliveryTask::create([
                'order_fulfillment_id' => $orderFulfillment->id,
                'task_number' => 'DT-' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'task_type' => $taskTypes[array_rand($taskTypes)],
                'priority' => $priorities[array_rand($priorities)],
                'status' => $statuses[array_rand($statuses)],
                'assigned_driver_id' => rand(0, 1) ? $drivers->random()->id : null,
                'pickup_location_id' => $locations->random()->id,
                'delivery_location_id' => $locations->random()->id,
                'scheduled_start_time' => now()->addHours(rand(1, 24)),
                'scheduled_end_time' => now()->addHours(rand(25, 48)),
                'actual_start_time' => rand(0, 1) ? now()->subHours(rand(1, 12)) : null,
                'actual_end_time' => rand(0, 1) ? now()->subHours(rand(1, 6)) : null,
                'estimated_duration_minutes' => rand(30, 180),
                'distance_km' => rand(1, 50),
                'task_instructions' => rand(0, 1) ? fake()->sentence() : null,
                'delivery_instructions' => rand(0, 1) ? fake()->sentence() : null,
                'special_requirements' => rand(0, 1) ? fake()->sentence() : null,
                'delivery_contact_info' => [
                    'customer_name' => fake()->name(),
                    'phone' => fake()->phoneNumber(),
                    'email' => fake()->email()
                ],
                'task_metadata' => [
                    'customer_contact' => fake()->phoneNumber(),
                    'delivery_window' => fake()->randomElement(['morning', 'afternoon', 'evening']),
                    'signature_required' => rand(0, 1) ? true : false,
                    'id_required' => rand(0, 1) ? true : false
                ]
            ]);
        }
    }

    private function createProofOfDeliveries()
    {
        $this->command->info('Creating proof of deliveries...');
        // Simplified implementation for now
    }

    private function createCustomsDocumentations()
    {
        $this->command->info('Creating customs documentation...');
        // Simplified implementation for now
    }

    private function createContainerTracking()
    {
        $this->command->info('Creating container tracking...');
        // Simplified implementation for now
    }

    private function createTerminalEvents()
    {
        $this->command->info('Creating terminal events...');
        // Simplified implementation for now
    }

    private function createGeofenceEvents()
    {
        $this->command->info('Creating geofence events...');
        // Simplified implementation for now
    }

    private function createCustomerNotifications()
    {
        $this->command->info('Creating customer notifications...');
        
        $companies = \App\Models\Company::all();
        $orderFulfillments = \App\Models\OrderFulfillment::all();
        
        $notificationTypes = [
            'order_confirmation',
            'shipping_update',
            'delivery_confirmation',
            'delivery_exception',
            'delivery_delay'
        ];
        
        $channels = ['email', 'sms', 'push'];
        $statuses = ['sent', 'delivered', 'failed', 'opened'];
        
        for ($i = 0; $i < 20; $i++) {
            $company = $companies->random();
            $orderFulfillmentId = $orderFulfillments->isNotEmpty() ? $orderFulfillments->random()->id : null;
            
            \App\Models\CustomerNotification::create([
                'customer_company_id' => $company->id,
                'order_fulfillment_id' => $orderFulfillmentId,
                'notification_type' => $notificationTypes[array_rand($notificationTypes)],
                'channel' => $channels[array_rand($channels)],
                'recipient_name' => fake()->name(),
                'recipient_email' => fake()->email(),
                'recipient_phone' => fake()->phoneNumber(),
                'subject' => fake()->sentence(6),
                'message_content' => fake()->paragraph(3),
                'status' => $statuses[array_rand($statuses)],
                'sent_at' => fake()->dateTimeBetween('-30 days', 'now'),
                'delivered_at' => fake()->optional(0.8)->dateTimeBetween('-30 days', 'now'),
                'opened_at' => fake()->optional(0.6)->dateTimeBetween('-30 days', 'now'),
                'notification_metadata' => json_encode([
                    'template_id' => fake()->uuid(),
                    'provider' => fake()->randomElement(['sendgrid', 'twilio', 'firebase']),
                    'tracking_id' => fake()->uuid()
                ])
            ]);
        }
    }

    private function createDeliveryExceptions()
    {
        $this->command->info('Creating delivery exceptions...');
        
        $deliveryTasks = \App\Models\DeliveryTask::all();
        
        if ($deliveryTasks->isEmpty()) {
            $this->command->warn('No delivery tasks found, skipping delivery exceptions');
            return;
        }
        
        $exceptionTypes = ['delivery_failed', 'delivery_delayed', 'customer_issue', 'operational_issue'];
        $exceptionCodes = ['DAMAGED', 'REFUSED', 'NO_ACCESS', 'WEATHER', 'TRAFFIC', 'CUSTOMER_UNAVAILABLE'];
        $severities = ['low', 'medium', 'high', 'critical'];
        $statuses = ['open', 'in_progress', 'resolved', 'cancelled'];
        $drivers = \App\Models\User::where('driver_license', '!=', null)->get();
        
        for ($i = 0; $i < 15; $i++) {
            $deliveryTask = $deliveryTasks->random();
            
            \App\Models\DeliveryException::create([
                'delivery_task_id' => $deliveryTask->id,
                'driver_id' => rand(0, 1) ? $drivers->random()->id : null,
                'exception_code' => $exceptionCodes[array_rand($exceptionCodes)],
                'exception_type' => $exceptionTypes[array_rand($exceptionTypes)],
                'severity' => $severities[array_rand($severities)],
                'status' => $statuses[array_rand($statuses)],
                'exception_timestamp' => now()->subHours(rand(1, 72)),
                'exception_latitude' => 40.7128 + (rand(-100, 100) / 1000),
                'exception_longitude' => -74.0060 + (rand(-100, 100) / 1000),
                'exception_location' => fake()->address(),
                'description' => fake()->sentence(),
                'root_cause' => rand(0, 1) ? fake()->sentence() : null,
                'resolution_notes' => rand(0, 1) ? fake()->paragraph() : null,
                'customer_communication' => rand(0, 1) ? fake()->sentence() : null,
                'photos_attached' => rand(0, 1) ? [fake()->imageUrl()] : null,
                'required_actions' => [
                    'contact_customer' => rand(0, 1) ? true : false,
                    'reschedule_delivery' => rand(0, 1) ? true : false,
                    'investigate_damage' => rand(0, 1) ? true : false
                ],
                'resolved_at' => rand(0, 1) ? now()->subHours(rand(1, 24)) : null,
                'resolved_by_user_id' => rand(0, 1) ? $drivers->random()->id : null,
                'estimated_resolution_time_hours' => rand(1, 48),
                'actual_resolution_time_hours' => rand(0, 1) ? rand(1, 24) : null,
                'exception_metadata' => [
                    'weather_conditions' => fake()->randomElement(['clear', 'rain', 'snow', 'fog']),
                    'traffic_conditions' => fake()->randomElement(['light', 'moderate', 'heavy']),
                    'customer_response' => fake()->randomElement(['cooperative', 'unresponsive', 'angry']),
                    'damage_assessment' => rand(0, 1) ? fake()->randomElement(['minor', 'major', 'total']) : null
                ]
            ]);
        }
    }
}