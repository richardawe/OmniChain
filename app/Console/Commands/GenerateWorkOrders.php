<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ManufacturingWorkflowService;

class GenerateWorkOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manufacturing:generate-work-orders {--from-po : Generate from purchase orders} {--products= : Comma-separated product IDs} {--quantity=100 : Quantity to produce}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate work orders from purchase orders or for specific products';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $service = new ManufacturingWorkflowService();

        if ($this->option('from-po')) {
            $this->info('Generating work orders from purchase orders...');
            
            $workOrders = $service->generateWorkOrdersFromPurchaseOrders();
            
            if (empty($workOrders)) {
                $this->warn('No work orders generated. Check if you have confirmed purchase orders with products that have BOMs.');
                return;
            }
            
            $this->info('Generated ' . count($workOrders) . ' work orders:');
            
            foreach ($workOrders as $workOrder) {
                $this->line("- {$workOrder->work_order_number}: {$workOrder->product->name} (Qty: {$workOrder->quantity_planned})");
            }
            
        } elseif ($this->option('products')) {
            $productIds = explode(',', $this->option('products'));
            $quantity = (int) $this->option('quantity');
            
            $this->info("Generating work orders for products: " . implode(', ', $productIds));
            
            $workOrders = $service->createWorkOrdersForProducts($productIds, $quantity);
            
            if (empty($workOrders)) {
                $this->warn('No work orders generated. Check if products exist and have BOMs.');
                return;
            }
            
            $this->info('Generated ' . count($workOrders) . ' work orders:');
            
            foreach ($workOrders as $workOrder) {
                $this->line("- {$workOrder->work_order_number}: {$workOrder->product->name} (Qty: {$workOrder->quantity_planned})");
            }
            
        } else {
            $this->error('Please specify either --from-po or --products option');
            $this->line('Usage examples:');
            $this->line('  php artisan manufacturing:generate-work-orders --from-po');
            $this->line('  php artisan manufacturing:generate-work-orders --products=1,2,3 --quantity=50');
        }
    }
}