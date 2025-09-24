<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FreightOrder;
use App\Models\Company;
use App\Models\Location;
use App\Models\ShipmentEvent;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Basic statistics
        $stats = [
            'total_orders' => FreightOrder::count(),
            'active_orders' => FreightOrder::whereIn('status', ['booked', 'picked_up', 'in_transit'])->count(),
            'total_companies' => Company::count(),
            'total_locations' => Location::count(),
            'total_carriers' => Company::where('type', 'carrier')->count(),
            'total_shippers' => Company::where('type', 'shipper')->count(),
        ];

        // Recent orders with full details
        $recent_orders = FreightOrder::with([
            'shipperCompany',
            'carrierCompany',
            'originLocation',
            'destinationLocation'
        ])
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();

        // Orders by status for charts
        $orders_by_status = FreightOrder::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // Orders by service type
        $orders_by_service = FreightOrder::selectRaw('service_type, COUNT(*) as count')
            ->groupBy('service_type')
            ->pluck('count', 'service_type');

        // Recent shipment events for timeline
        $recent_events = ShipmentEvent::with('freightOrder')
            ->orderBy('event_time', 'desc')
            ->limit(20)
            ->get();

        // Active shipments for tracking
        $active_shipments = FreightOrder::with([
            'shipperCompany', 'carrierCompany', 'originLocation', 'destinationLocation', 'shipmentEvents'
        ])
        ->whereIn('status', ['picked_up', 'in_transit'])
        ->orderBy('updated_at', 'desc')
        ->get();

        // All locations for map
        $locations = Location::with('company')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        // All companies for CRUD operations
        $all_companies = Company::orderBy('name')->get();

        // All locations for CRUD operations
        $all_locations = Location::with('company')->orderBy('name')->get();

        // Performance metrics
        $performance_metrics = [
            'on_time_delivery' => $this->calculateOnTimeDelivery(),
            'average_transit_time' => $this->calculateAverageTransitTime(),
            'cost_per_shipment' => $this->calculateAverageCost(),
        ];

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recentOrders' => $recent_orders,
            'ordersByStatus' => $orders_by_status,
            'ordersByService' => $orders_by_service,
            'recentEvents' => $recent_events,
            'activeShipments' => $active_shipments,
            'locations' => $locations,
            'allCompanies' => $all_companies,
            'allLocations' => $all_locations,
            'performanceMetrics' => $performance_metrics,
        ]);
    }

    private function calculateOnTimeDelivery(): float
    {
        $delivered_orders = FreightOrder::where('status', 'delivered')
            ->whereNotNull('delivery_date')
            ->whereNotNull('requested_delivery_date')
            ->get();

        if ($delivered_orders->count() === 0) {
            return 0.0;
        }

        $on_time = $delivered_orders->filter(function ($order) {
            return $order->delivery_date <= $order->requested_delivery_date;
        })->count();

        return round(($on_time / $delivered_orders->count()) * 100, 2);
    }

    private function calculateAverageTransitTime(): float
    {
        $delivered_orders = FreightOrder::where('status', 'delivered')
            ->whereNotNull('pickup_date')
            ->whereNotNull('delivery_date')
            ->get();

        if ($delivered_orders->count() === 0) {
            return 0.0;
        }

        $total_days = $delivered_orders->sum(function ($order) {
            return $order->pickup_date->diffInDays($order->delivery_date);
        });

        return round($total_days / $delivered_orders->count(), 2);
    }

    private function calculateAverageCost(): float
    {
        // This would typically come from a rates or billing table
        // For now, we'll return a placeholder
        return 0.0;
    }
}
