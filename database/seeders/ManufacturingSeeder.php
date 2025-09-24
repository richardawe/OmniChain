<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WorkOrder;
use App\Models\Machine;
use App\Models\QualityInspection;
use App\Models\BatchTracking;
use App\Models\Product;
use App\Models\Location;
use App\Models\User;

class ManufacturingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        BatchTracking::truncate();
        QualityInspection::truncate();
        WorkOrder::truncate();
        Machine::truncate();
        
        // Create machines
        $this->createMachines();
        
        // Create work orders
        $this->createWorkOrders();
        
        // Create quality inspections
        $this->createQualityInspections();
        
        // Create batch tracking
        $this->createBatchTracking();
    }
    
    private function createMachines()
    {
        $machines = [
            [
                'machine_id' => 'MACH-001',
                'machine_name' => 'CNC Lathe #1',
                'machine_type' => 'cnc_lathe',
                'model' => 'HAAS ST-20',
                'manufacturer' => 'HAAS Automation',
                'serial_number' => 'HT-2023-001',
                'location_id' => Location::first()->id,
                'status' => 'active',
                'installation_date' => now()->subDays(365),
                'last_maintenance_date' => now()->subDays(15),
                'next_maintenance_date' => now()->addDays(15),
                'capacity_per_hour' => 1000,
                'efficiency_rating' => 95.5,
                'operational_parameters' => [
                    'max_workpiece_diameter' => '200mm',
                    'max_workpiece_length' => '500mm',
                    'spindle_speed_range' => '50-4000 RPM'
                ],
                'machine_metadata' => [
                    'capabilities' => ['turning', 'boring', 'threading'],
                    'maintenance_schedule' => 'monthly'
                ]
            ],
            [
                'machine_id' => 'MACH-002',
                'machine_name' => 'CNC Mill #1',
                'machine_type' => 'cnc_mill',
                'model' => 'HAAS VF-2',
                'manufacturer' => 'HAAS Automation',
                'serial_number' => 'HV-2023-002',
                'location_id' => Location::first()->id,
                'status' => 'active',
                'installation_date' => now()->subDays(300),
                'last_maintenance_date' => now()->subDays(10),
                'next_maintenance_date' => now()->addDays(20),
                'capacity_per_hour' => 800,
                'efficiency_rating' => 92.3,
                'operational_parameters' => [
                    'table_size' => '762mm x 305mm',
                    'max_travel_x' => '762mm',
                    'max_travel_y' => '406mm',
                    'max_travel_z' => '508mm'
                ],
                'machine_metadata' => [
                    'capabilities' => ['milling', 'drilling', 'tapping'],
                    'maintenance_schedule' => 'monthly'
                ]
            ]
        ];
        
        foreach ($machines as $machineData) {
            Machine::create($machineData);
        }
    }
    
    private function createWorkOrders()
    {
        $products = Product::limit(3)->get();
        $machines = Machine::limit(2)->get();
        $users = User::limit(3)->get();
        
        if ($products->isEmpty() || $machines->isEmpty() || $users->isEmpty()) {
            return;
        }
        
        for ($i = 0; $i < 10; $i++) {
            $product = fake()->randomElement($products);
            $machine = fake()->randomElement($machines);
            $operator = fake()->randomElement($users);
            
            WorkOrder::create([
                'work_order_id' => 'WO-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'production_line_id' => Location::first()->id,
                'product_id' => $product->id,
                'bom_id' => null,
                'route_id' => null,
                'quantity_planned' => fake()->numberBetween(10, 100),
                'quantity_produced' => fake()->numberBetween(0, 50),
                'quantity_scrapped' => fake()->numberBetween(0, 5),
                'planned_start_time' => fake()->dateTimeBetween('-30 days', 'now'),
                'planned_end_time' => fake()->dateTimeBetween('now', '+30 days'),
                'actual_start_time' => fake()->optional()->dateTimeBetween('-20 days', 'now'),
                'actual_end_time' => fake()->optional()->dateTimeBetween('-10 days', 'now'),
                'shift_id' => null,
                'status' => fake()->randomElement(['planned', 'released', 'in_progress', 'completed', 'cancelled', 'on_hold']),
                'priority' => fake()->numberBetween(1, 5),
                'created_by' => User::first()->id,
                'assigned_supervisor' => fake()->optional(0.7)->randomElement($users)?->id,
                'operator_ids' => [$operator->id],
                'associated_batch_numbers' => ['BATCH-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT)],
                'work_instructions' => fake()->optional()->sentence(),
                'special_requirements' => fake()->optional()->sentence(),
                'work_order_metadata' => [
                    'customer' => fake()->company(),
                    'project' => fake()->words(2, true),
                    'notes' => fake()->optional()->sentence(),
                    'quality_requirements' => [
                        'tolerance' => '±0.001"',
                        'surface_finish' => 'Ra 32',
                        'inspection_required' => true
                    ]
                ]
            ]);
        }
    }
    
    private function createQualityInspections()
    {
        $workOrders = WorkOrder::limit(5)->get();
        $users = User::limit(3)->get();
        
        if ($workOrders->isEmpty() || $users->isEmpty()) {
            return;
        }
        
        for ($i = 0; $i < 15; $i++) {
            $workOrder = fake()->randomElement($workOrders);
            $inspector = fake()->randomElement($users);
            
            QualityInspection::create([
                'inspection_id' => 'QI-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'work_order_id' => $workOrder->id,
                'batch_id' => fake()->optional()->numerify('BATCH-######'),
                'inspector_id' => $inspector->id,
                'inspection_timestamp' => fake()->dateTimeBetween('-30 days', 'now'),
                'inspection_type' => fake()->randomElement(['incoming', 'in_process', 'final', 'random', 'customer_return']),
                'inspection_result' => fake()->randomElement(['pass', 'fail', 'conditional_pass']),
                'sample_size' => fake()->numberBetween(1, 50),
                'defects_found' => fake()->numberBetween(0, 10),
                'defect_rate_percentage' => fake()->randomFloat(2, 0, 100),
                'inspection_notes' => fake()->optional()->sentence(),
                'measured_attributes' => [
                    'dimension_1' => fake()->randomFloat(3, 10, 100),
                    'dimension_2' => fake()->randomFloat(3, 5, 50),
                    'tolerance' => '±0.001"'
                ],
                'quality_metadata' => [
                    'equipment_used' => ['caliper', 'micrometer', 'surface_roughness_tester'],
                    'environmental_conditions' => [
                        'temperature' => '20°C ± 2°C',
                        'humidity' => '45% ± 5%'
                    ],
                    'standards' => ['ISO-9001', 'AS9100'],
                    'defects_found' => fake()->optional()->randomElements(['scratch', 'burr', 'dimension_out', 'surface_finish'], 2),
                    'corrective_actions' => fake()->optional()->sentence()
                ]
            ]);
        }
    }
    
    private function createBatchTracking()
    {
        $workOrders = WorkOrder::limit(5)->get();
        $products = Product::limit(3)->get();
        
        if ($workOrders->isEmpty() || $products->isEmpty()) {
            return;
        }
        
        for ($i = 0; $i < 20; $i++) {
            $workOrder = fake()->randomElement($workOrders);
            $product = fake()->randomElement($products);
            
            BatchTracking::create([
                'batch_id' => 'BATCH-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'product_id' => $product->id,
                'work_order_id' => $workOrder->id,
                'batch_quantity' => fake()->numberBetween(10, 100),
                'production_date' => fake()->dateTimeBetween('-30 days', 'now'),
                'expiry_date' => fake()->optional()->dateTimeBetween('+30 days', '+2 years'),
                'quality_status' => fake()->randomElement(['pending', 'passed', 'failed', 'quarantined']),
                'created_by' => User::first()->id,
                'component_batches' => [
                    'LOT-' . fake()->numerify('######'),
                    fake()->optional()->numerify('SUP-######'),
                    fake()->optional()->numerify('RM-######')
                ],
                'batch_metadata' => [
                    'supplier' => fake()->company(),
                    'raw_materials' => fake()->words(3, true),
                    'processing_notes' => fake()->optional()->sentence(),
                    'batch_status' => fake()->randomElement(['pending', 'in_progress', 'completed', 'quarantined']),
                    'completion_date' => fake()->optional()->dateTimeBetween('-20 days', 'now')
                ]
            ]);
        }
    }
}
