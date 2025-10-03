<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;
use App\Models\SupplierOnboarding;
use App\Models\SupplierCatalog;
use App\Models\SupplierContract;
use App\Models\SupplierPerformance;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderLineItem;
use App\Models\Location;

class SupplierProcurementSeeder extends Seeder
{
    public function run(): void
    {
        // Create supplier companies
        $steelWorks = Company::firstOrCreate(
            ['code' => 'SWM002'],
            [
                'name' => 'SteelWorks Manufacturing',
                'legal_name' => 'SteelWorks Manufacturing LLC',
                'type' => 'shipper',
                'business_type' => 'Manufacturer',
                'email' => 'sales@steelworks.com',
                'phone' => '+1-555-1001',
                'address' => '1000 Steel Mill Road',
                'city' => 'Pittsburgh',
                'state' => 'PA',
                'country' => 'USA',
                'status' => 'active',
                'certifications' => ['ISO-9001', 'ASME', 'AWS'],
                'metadata' => ['founded_year' => 1975, 'employee_count' => 300]
            ]
        );

        $electronicsSupply = Company::firstOrCreate(
            ['code' => 'ESC002'],
            [
                'name' => 'Electronics Supply Co',
                'legal_name' => 'Electronics Supply Company Inc',
                'type' => 'shipper',
                'business_type' => 'Distributor',
                'email' => 'procurement@elecsupply.com',
                'phone' => '+1-555-2001',
                'address' => '2500 Tech Boulevard',
                'city' => 'Austin',
                'state' => 'TX',
                'country' => 'USA',
                'status' => 'active',
                'certifications' => ['ISO-9001', 'RoHS', 'REACH'],
                'metadata' => ['founded_year' => 1990, 'employee_count' => 150]
            ]
        );

        // Get buyer company and user
        $buyerCompany = Company::where('code', 'GMC002')->first();
        $user = User::first();
        $location = Location::first();

        if (!$buyerCompany || !$user || !$location) {
            $this->command->info('Required data not found. Please run MasterDataSeeder first.');
            return;
        }

        // Create supplier onboarding
        SupplierOnboarding::firstOrCreate(
            ['company_id' => $steelWorks->id, 'buyer_company_id' => $buyerCompany->id],
            [
            'onboarding_date' => now()->subMonths(6),
            'status' => 'approved',
            'kyc_documents' => ['business_license.pdf', 'tax_certificate.pdf'],
            'compliance_certs' => $steelWorks->certifications,
            'approved_items' => ['AUTO-PART-001', 'AUTO-PART-002'],
            'lead_times_days' => 14,
            'minimum_order_quantity' => 100.00,
            'supplier_performance_scores' => ['quality' => 8.5, 'delivery' => 9.0],
            'assigned_to_user_id' => $user->id,
            'approved_at' => now()->subMonths(5)
        ]);

        // Create supplier catalog
        SupplierCatalog::create([
            'company_id' => $steelWorks->id,
            'buyer_company_id' => $buyerCompany->id,
            'supplier_part_number' => 'SW-SP001',
            'buyer_sku' => 'AUTO-PART-002',
            'product_name' => 'Steel Brake Rotor',
            'description' => 'High-grade steel brake rotor',
            'category' => 'Automotive Parts',
            'unit_price' => 45.50,
            'currency' => 'USD',
            'lead_time_days' => 14,
            'minimum_order_quantity' => 50.00,
            'effective_date' => now()->subMonths(3),
            'status' => 'active'
        ]);

        // Create supplier contract
        SupplierContract::create([
            'contract_number' => 'CON-SWM-2024',
            'company_id' => $steelWorks->id,
            'buyer_company_id' => $buyerCompany->id,
            'start_date' => now()->subMonths(6),
            'end_date' => now()->addMonths(18),
            'contract_type' => 'standard',
            'sla_details' => ['delivery_time' => '14 days', 'quality_standard' => '99.5%'],
            'total_contract_value' => 5000000.00,
            'currency' => 'USD',
            'status' => 'active',
            'contract_manager_id' => $user->id
        ]);

        // Create supplier performance
        SupplierPerformance::create([
            'company_id' => $steelWorks->id,
            'buyer_company_id' => $buyerCompany->id,
            'performance_date' => now()->subMonth(),
            'period_type' => 'monthly',
            'on_time_delivery_pct' => 95.5,
            'quality_reject_rate' => 1.2,
            'fill_rate' => 98.0,
            'overall_score' => 8.5,
            'evaluated_by_user_id' => $user->id
        ]);

        // Create purchase order
        $po = PurchaseOrder::create([
            'po_number' => 'PO-GMC002-SWM-0001',
            'po_type' => 'standard',
            'created_by_user_id' => $user->id,
            'buyer_company_id' => $buyerCompany->id,
            'supplier_company_id' => $steelWorks->id,
            'order_date' => now()->subDays(10),
            'required_delivery_date' => now()->addDays(14),
            'currency' => 'USD',
            'incoterms' => 'FOB',
            'payment_terms' => 'NET30',
            'status' => 'confirmed',
            'approved_by_user_id' => $user->id,
            'approved_at' => now()->subDays(8),
            'confirmed_at' => now()->subDays(5)
        ]);

        PurchaseOrderLineItem::create([
            'purchase_order_id' => $po->id,
            'line_number' => 1,
            'supplier_part_number' => 'SW-SP001',
            'product_name' => 'Steel Brake Rotor',
            'quantity_ordered' => 200.00,
            'unit_price' => 42.00,
            'line_total' => 8400.00,
            'promised_date' => $po->required_delivery_date,
            'delivery_location_id' => $location->id,
            'status' => 'open'
        ]);

        $po->update(['total_amount' => 8400.00]);

        $this->command->info('Supplier & Procurement data seeded successfully!');
    }
}