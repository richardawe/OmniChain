<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Location;
use App\Models\Product;
use App\Models\Employee;
use Illuminate\Support\Str;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createCompanies();
        $this->createLocations();
        $this->createProducts();
        $this->createEmployees();
    }

    private function createCompanies()
    {
        $companies = [
            [
                'name' => 'Global Manufacturing Corp',
                'code' => 'GMC002',
                'legal_name' => 'Global Manufacturing Corporation',
                'trade_name' => 'Global Mfg',
                'tax_id' => 'TAX-GMC-001',
                'duns_number' => '123456789',
                'lei_number' => 'LEI-GMC-001',
                'vat_number' => 'VAT-GMC-001',
                'email' => 'info@globalmfg.com',
                'phone' => '+1-555-0101',
                'website' => 'https://globalmfg.com',
                'address' => '1234 Industrial Blvd',
                'city' => 'Detroit',
                'state' => 'MI',
                'country' => 'USA',
                'postal_code' => '48201',
                'type' => 'shipper',
                'business_type' => 'Manufacturer',
                'currency' => 'USD',
                'timezone' => 'America/Detroit',
                'industry_classification' => 'NAICS-336111',
                'certifications' => ['ISO-9001', 'ISO-14001', 'IATF-16949'],
                'payment_terms_code' => 'NET30',
                'credit_limit' => 1000000.00,
                'status' => 'active',
                'addresses' => [
                    [
                        'type' => 'headquarters',
                        'address' => '1234 Industrial Blvd',
                        'city' => 'Detroit',
                        'state' => 'MI',
                        'country' => 'USA',
                        'postal_code' => '48201'
                    ]
                ],
                'metadata' => [
                    'founded_year' => 1985,
                    'employee_count' => 500,
                    'revenue' => 50000000
                ]
            ],
            [
                'name' => 'Logistics Solutions Inc',
                'code' => 'LSI002',
                'legal_name' => 'Logistics Solutions Incorporated',
                'trade_name' => 'LogSol',
                'tax_id' => 'TAX-LSI-001',
                'duns_number' => '987654321',
                'lei_number' => 'LEI-LSI-001',
                'vat_number' => 'VAT-LSI-001',
                'email' => 'contact@logsolutions.com',
                'phone' => '+1-555-0202',
                'website' => 'https://logsolutions.com',
                'address' => '4567 Logistics Way',
                'city' => 'Chicago',
                'state' => 'IL',
                'country' => 'USA',
                'postal_code' => '60601',
                'type' => 'carrier',
                'business_type' => '3PL',
                'currency' => 'USD',
                'timezone' => 'America/Chicago',
                'industry_classification' => 'NAICS-484122',
                'certifications' => ['ISO-9001', 'C-TPAT', 'AEO'],
                'payment_terms_code' => 'NET15',
                'credit_limit' => 500000.00,
                'status' => 'active',
                'addresses' => [
                    [
                        'type' => 'headquarters',
                        'address' => '4567 Logistics Way',
                        'city' => 'Chicago',
                        'state' => 'IL',
                        'country' => 'USA',
                        'postal_code' => '60601'
                    ]
                ],
                'metadata' => [
                    'founded_year' => 1995,
                    'employee_count' => 200,
                    'revenue' => 25000000
                ]
            ]
        ];

        foreach ($companies as $companyData) {
            Company::firstOrCreate(
                ['code' => $companyData['code']],
                $companyData
            );
        }
    }

    private function createLocations()
    {
        $locations = [
            [
                'company_id' => 4,
                'name' => 'Detroit Manufacturing Plant',
                'code' => 'DET-PLANT-001',
                'type' => 'manufacturing_plant',
                'address' => '1234 Industrial Blvd',
                'city' => 'Detroit',
                'state' => 'MI',
                'country' => 'USA',
                'postal_code' => '48201',
                'latitude' => 42.3314,
                'longitude' => -83.0458,
                'timezone' => 'America/Detroit',
                'operating_hours' => [
                    'monday' => ['start' => '06:00', 'end' => '18:00'],
                    'tuesday' => ['start' => '06:00', 'end' => '18:00'],
                    'wednesday' => ['start' => '06:00', 'end' => '18:00'],
                    'thursday' => ['start' => '06:00', 'end' => '18:00'],
                    'friday' => ['start' => '06:00', 'end' => '18:00'],
                    'saturday' => ['start' => '08:00', 'end' => '16:00'],
                    'sunday' => ['start' => 'closed', 'end' => 'closed']
                ],
                'capabilities' => [
                    'loading_docks' => 12,
                    'crane_capacity' => '50_ton',
                    'forklift_count' => 8,
                    'conveyor_systems' => true
                ],
                'capacity_volume' => 50000.000,
                'capacity_weight' => 1000000.000,
                'hazardous_material_capabilities' => [
                    'hazmat_approved' => true,
                    'classes' => ['Class 3', 'Class 8', 'Class 9'],
                    'storage_areas' => 3
                ],
                'temperature_control' => [
                    'controlled_areas' => true,
                    'min_temp' => -20,
                    'max_temp' => 25,
                    'monitoring' => 'continuous'
                ],
                'loading_bays_count' => 12,
                'dock_types' => ['dock_high', 'dock_low', 'drive_through'],
                'accessibility_notes' => 'Full accessibility compliance, 24/7 security',
                'status' => 'active',
                'metadata' => [
                    'sq_ft' => 250000,
                    'ceiling_height' => '30ft',
                    'security_level' => 'high'
                ]
            ]
        ];

        foreach ($locations as $locationData) {
            Location::firstOrCreate(
                ['code' => $locationData['code']],
                $locationData
            );
        }
    }

    private function createProducts()
    {
        $products = [
            [
                'company_id' => 4,
                'sku' => 'AUTO-PART-001',
                'gtin' => '1234567890123',
                'upc' => '123456789012',
                'ean' => '1234567890123',
                'name' => 'Automotive Brake Pad Set',
                'description' => 'High-performance ceramic brake pad set for passenger vehicles',
                'category' => 'Automotive Parts',
                'subcategory' => 'Brake Components',
                'product_family' => 'Brake Systems',
                'brand' => 'GlobalAuto',
                'manufacturer' => 'Global Manufacturing Corp',
                'unit_of_measure' => 'SET',
                'weight' => 2.500,
                'net_weight' => 2.200,
                'length' => 25.0,
                'width' => 15.0,
                'height' => 5.0,
                'volume' => 0.001875,
                'hazardous' => 'no',
                'temperature_controlled' => 'no',
                'hazardous_details' => null,
                'temperature_requirements' => null,
                'handling_requirements' => ['handle_with_care', 'avoid_moisture'],
                'hazardous_material_code' => null,
                'storage_requirements' => [
                    'temperature_range' => 'ambient',
                    'humidity_max' => '60%',
                    'light_exposure' => 'avoid_direct_sunlight'
                ],
                'shelf_life_days' => 1825,
                'batch_trackable' => true,
                'serialization_capable' => false,
                'bom_references' => [
                    'ceramic_material' => '2.0kg',
                    'adhesive' => '0.1kg',
                    'packaging' => '1_set'
                ],
                'country_of_origin' => 'USA',
                'tariff_codes' => ['8708.70.60.00'],
                'lifecycle_status' => 'active',
                'manufacturer_part_number' => 'GMC-BP-001',
                'status' => 'active',
                'metadata' => [
                    'warranty_period' => '2_years',
                    'compliance' => ['DOT', 'FMVSS'],
                    'target_market' => 'aftermarket'
                ]
            ]
        ];

        foreach ($products as $productData) {
            Product::firstOrCreate(
                ['sku' => $productData['sku']],
                $productData
            );
        }
    }

    private function createEmployees()
    {
        // Get the first company and location for employee references
        $company = Company::first();
        $location = Location::first();
        
        $employees = [
            [
                'id' => Str::uuid(),
                'employee_id' => 'EMP001',
                'company_id' => $company ? $company->id : 1,
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'john.smith@globalmfg.com',
                'phone' => '+1-555-1001',
                'role' => 'Plant Manager',
                'department' => 'Operations',
                'certifications' => ['PMP', 'Six Sigma Black Belt', 'OSHA 30'],
                'home_location_id' => $location ? $location->id : 1,
                'work_schedule' => [
                    'type' => 'full_time',
                    'hours_per_week' => 40,
                    'shift' => 'day',
                    'days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday']
                ],
                'hire_date' => '2020-01-15',
                'termination_date' => null,
                'status' => 'active',
                'emergency_contact' => [
                    'name' => 'Jane Smith',
                    'relationship' => 'Spouse',
                    'phone' => '+1-555-1002',
                    'email' => 'jane.smith@email.com'
                ],
                'metadata' => [
                    'employee_type' => 'salaried',
                    'salary' => 85000,
                    'benefits' => ['health', 'dental', '401k']
                ]
            ]
        ];

        foreach ($employees as $employeeData) {
            Employee::firstOrCreate(
                ['employee_id' => $employeeData['employee_id']],
                $employeeData
            );
        }
    }
}