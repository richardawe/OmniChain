<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ReturnRequest;
use App\Models\ReturnLineItem;
use App\Models\ReturnAuthorization;
use App\Models\ReturnReason;
use App\Models\ReturnDisposition;
use App\Models\ReturnProcessing;
use App\Models\Company;
use App\Models\Location;
use App\Models\Product;
use App\Models\User;

class ReturnsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        ReturnProcessing::truncate();
        ReturnLineItem::truncate();
        ReturnRequest::truncate();
        ReturnAuthorization::truncate();
        ReturnDisposition::truncate();
        ReturnReason::truncate();

        // Create return reasons
        $this->createReturnReasons();

        // Create return dispositions
        $this->createReturnDispositions();

        // Create return authorizations
        $this->createReturnAuthorizations();

        // Create return requests
        $this->createReturnRequests();

        // Create return line items
        $this->createReturnLineItems();

        // Create return processing records
        $this->createReturnProcessing();
    }

    private function createReturnReasons()
    {
        $reasons = [
            [
                'reason_code' => 'DEFECTIVE',
                'reason_name' => 'Defective Product',
                'description' => 'Product arrived damaged or not working properly',
                'category' => 'defective',
                'requires_approval' => false,
                'auto_approve' => true,
                'max_refund_percentage' => 100.00,
                'charge_restocking_fee' => false,
                'requires_return_shipping' => true,
                'requires_inspection' => true,
                'valid_days' => 30
            ],
            [
                'reason_code' => 'WRONG_ITEM',
                'reason_name' => 'Wrong Item Received',
                'description' => 'Customer received different item than ordered',
                'category' => 'wrong_item',
                'requires_approval' => false,
                'auto_approve' => true,
                'max_refund_percentage' => 100.00,
                'charge_restocking_fee' => false,
                'requires_return_shipping' => true,
                'requires_inspection' => true,
                'valid_days' => 14
            ],
            [
                'reason_code' => 'NOT_AS_DESCRIBED',
                'reason_name' => 'Not as Described',
                'description' => 'Product does not match description or images',
                'category' => 'not_as_described',
                'requires_approval' => true,
                'auto_approve' => false,
                'max_refund_percentage' => 100.00,
                'charge_restocking_fee' => false,
                'requires_return_shipping' => true,
                'requires_inspection' => true,
                'valid_days' => 30
            ]
        ];

        foreach ($reasons as $reason) {
            ReturnReason::create($reason);
        }
    }

    private function createReturnDispositions()
    {
        $dispositions = [
            [
                'disposition_code' => 'REFUND',
                'disposition_name' => 'Full Refund',
                'description' => 'Issue full refund to customer',
                'disposition_type' => 'refund',
                'requires_inspection' => true,
                'requires_approval' => false,
                'cost_per_item' => 0.00,
                'processing_days' => 1,
                'generates_credit' => true,
                'generates_replacement' => false,
                'affects_inventory' => true,
                'inventory_action' => 'add'
            ],
            [
                'disposition_code' => 'EXCHANGE',
                'disposition_name' => 'Exchange',
                'description' => 'Exchange for different item',
                'disposition_type' => 'exchange',
                'requires_inspection' => true,
                'requires_approval' => true,
                'cost_per_item' => 0.00,
                'processing_days' => 2,
                'generates_credit' => false,
                'generates_replacement' => true,
                'affects_inventory' => true,
                'inventory_action' => 'add'
            ]
        ];

        foreach ($dispositions as $disposition) {
            ReturnDisposition::create($disposition);
        }
    }

    private function createReturnAuthorizations()
    {
        $companies = Company::limit(5)->get();
        $locations = Location::limit(3)->get();
        $users = User::limit(5)->get();

        for ($i = 0; $i < 10; $i++) {
            ReturnAuthorization::create([
                'authorization_number' => 'RA-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'customer_id' => fake()->randomElement($companies)->id,
                'location_id' => fake()->randomElement($locations)->id,
                'original_order_number' => fake()->optional()->numerify('ORD-######'),
                'authorization_type' => fake()->randomElement(['return', 'exchange', 'warranty', 'defective']),
                'status' => fake()->randomElement(['pending', 'approved', 'rejected']),
                'request_date' => fake()->dateTimeBetween('-30 days', 'now'),
                'authorized_date' => fake()->optional()->dateTimeBetween('-25 days', 'now'),
                'expiry_date' => fake()->dateTimeBetween('now', '+30 days'),
                'authorized_by' => fake()->randomElement($users)->id,
                'authorized_amount' => fake()->randomFloat(2, 50, 1000),
                'refund_method' => fake()->randomElement(['credit_card', 'bank_transfer', 'store_credit']),
                'valid_days' => fake()->numberBetween(7, 30),
                'authorization_notes' => fake()->optional()->sentence(),
                'requires_approval' => fake()->boolean(30),
                'auto_approve' => fake()->boolean(70),
            ]);
        }
    }

    private function createReturnRequests()
    {
        $companies = Company::limit(5)->get();
        $locations = Location::limit(3)->get();
        $authorizations = ReturnAuthorization::limit(10)->get();

        for ($i = 0; $i < 15; $i++) {
            ReturnRequest::create([
                'return_number' => 'RET-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'original_order_number' => fake()->optional()->numerify('ORD-######'),
                'customer_id' => fake()->randomElement($companies)->id,
                'location_id' => fake()->randomElement($locations)->id,
                'return_authorization_id' => fake()->randomElement($authorizations)->id,
                'return_type' => fake()->randomElement(['customer_return', 'vendor_return', 'damage_return']),
                'status' => fake()->randomElement(['requested', 'authorized', 'received', 'processing', 'completed']),
                'priority' => fake()->randomElement(['low', 'medium', 'high']),
                'request_date' => fake()->dateTimeBetween('-20 days', 'now'),
                'authorized_date' => fake()->optional()->dateTimeBetween('-15 days', 'now'),
                'received_date' => fake()->optional()->dateTimeBetween('-10 days', 'now'),
                'completed_date' => fake()->optional()->dateTimeBetween('-5 days', 'now'),
                'total_value' => fake()->randomFloat(2, 25, 500),
                'refund_amount' => fake()->randomFloat(2, 20, 450),
                'refund_method' => fake()->randomElement(['credit_card', 'bank_transfer', 'store_credit']),
                'tracking_number' => fake()->optional()->numerify('TRK-##########'),
                'carrier' => fake()->optional()->randomElement(['UPS', 'FedEx', 'DHL']),
                'return_reason' => fake()->sentence(),
                'customer_notes' => fake()->optional()->sentence(),
                'requires_inspection' => fake()->boolean(80),
                'customer_notified' => fake()->boolean(90),
            ]);
        }
    }

    private function createReturnLineItems()
    {
        $returnRequests = ReturnRequest::limit(10)->get();
        $products = Product::limit(10)->get();
        $users = User::limit(5)->get();

        foreach ($returnRequests as $returnRequest) {
            $itemCount = fake()->numberBetween(1, 2);
            for ($j = 0; $j < $itemCount; $j++) {
                $quantityReturned = fake()->numberBetween(1, 3);
                $unitPrice = fake()->randomFloat(2, 10, 100);
                $totalValue = $quantityReturned * $unitPrice;

                ReturnLineItem::create([
                    'return_id' => $returnRequest->id,
                    'product_id' => fake()->randomElement($products)->id,
                    'quantity_returned' => $quantityReturned,
                    'quantity_received' => fake()->numberBetween(0, $quantityReturned),
                    'quantity_damaged' => fake()->numberBetween(0, $quantityReturned),
                    'unit_price' => $unitPrice,
                    'total_value' => $totalValue,
                    'refund_amount' => fake()->randomFloat(2, $totalValue * 0.8, $totalValue),
                    'condition' => fake()->randomElement(['new', 'like_new', 'good', 'fair', 'damaged']),
                    'disposition' => fake()->randomElement(['refund', 'exchange', 'repair', 'dispose']),
                    'condition_notes' => fake()->optional()->sentence(),
                    'requires_approval' => fake()->boolean(30),
                    'approved' => fake()->boolean(70),
                    'approved_by' => fake()->randomElement($users)->id,
                    'approved_at' => fake()->optional()->dateTimeBetween('-5 days', 'now'),
                ]);
            }
        }
    }

    private function createReturnProcessing()
    {
        $returnRequests = ReturnRequest::limit(8)->get();
        $returnLineItems = ReturnLineItem::limit(15)->get();
        $users = User::limit(5)->get();

        foreach ($returnRequests as $returnRequest) {
            $lineItems = $returnLineItems->where('return_id', $returnRequest->id)->take(1);
            
            foreach ($lineItems as $lineItem) {
                ReturnProcessing::create([
                    'return_id' => $returnRequest->id,
                    'return_line_item_id' => $lineItem->id,
                    'processor_id' => fake()->randomElement($users)->id,
                    'processing_step' => fake()->randomElement(['received', 'inspected', 'approved', 'refunded']),
                    'status' => fake()->randomElement(['in_progress', 'completed']),
                    'started_at' => fake()->dateTimeBetween('-7 days', 'now'),
                    'completed_at' => fake()->optional()->dateTimeBetween('-6 days', 'now'),
                    'processing_time_minutes' => fake()->numberBetween(15, 120),
                    'inspection_notes' => fake()->optional()->sentence(),
                    'processing_notes' => fake()->optional()->sentence(),
                    'refund_amount' => fake()->optional()->randomFloat(2, 10, 200),
                    'refund_method' => fake()->optional()->randomElement(['credit_card', 'bank_transfer']),
                ]);
            }
        }
    }
}