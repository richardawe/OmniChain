<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\ReturnRequest;
use App\Models\ReturnLineItem;
use App\Models\ReturnAuthorization;
use App\Models\ReturnReason;
use App\Models\ReturnDisposition;
use App\Models\ReturnProcessing;

class ReturnsController extends Controller
{
    public function getReturnsSummary(): JsonResponse
    {
        $totalReturns = ReturnRequest::count();
        $pendingReturns = ReturnRequest::where('status', 'requested')->count();
        $authorizedReturns = ReturnRequest::where('status', 'authorized')->count();
        $receivedReturns = ReturnRequest::where('status', 'received')->count();
        $processingReturns = ReturnRequest::where('status', 'processing')->count();
        $completedReturns = ReturnRequest::where('status', 'completed')->count();
        $totalValue = ReturnRequest::sum('total_value');
        $totalRefunds = ReturnRequest::sum('refund_amount');

        return response()->json([
            'success' => true,
            'data' => [
                'total_returns' => $totalReturns,
                'pending_returns' => $pendingReturns,
                'authorized_returns' => $authorizedReturns,
                'received_returns' => $receivedReturns,
                'processing_returns' => $processingReturns,
                'completed_returns' => $completedReturns,
                'total_value' => $totalValue,
                'total_refunds' => $totalRefunds,
            ]
        ]);
    }

    public function getReturnRequests(Request $request): JsonResponse
    {
        $query = ReturnRequest::with(['customer', 'location', 'returnAuthorization', 'lineItems.product']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('return_type')) {
            $query->where('return_type', $request->input('return_type'));
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->input('priority'));
        }
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->input('customer_id'));
        }

        $returns = $query->orderBy('request_date', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $returns
        ]);
    }

    public function getReturnAuthorizations(Request $request): JsonResponse
    {
        $query = ReturnAuthorization::with(['customer', 'location', 'authorizer']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('authorization_type')) {
            $query->where('authorization_type', $request->input('authorization_type'));
        }
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->input('customer_id'));
        }

        $authorizations = $query->orderBy('request_date', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $authorizations
        ]);
    }

    public function getReturnReasons(Request $request): JsonResponse
    {
        $query = ReturnReason::query();

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->input('is_active'));
        }

        $reasons = $query->orderBy('reason_name')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $reasons
        ]);
    }

    public function getReturnDispositions(Request $request): JsonResponse
    {
        $query = ReturnDisposition::query();

        if ($request->filled('disposition_type')) {
            $query->where('disposition_type', $request->input('disposition_type'));
        }
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->input('is_active'));
        }

        $dispositions = $query->orderBy('disposition_name')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $dispositions
        ]);
    }

    public function getReturnProcessing(Request $request): JsonResponse
    {
        $query = ReturnProcessing::with(['returnRequest', 'returnLineItem', 'processor']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('processing_step')) {
            $query->where('processing_step', $request->input('processing_step'));
        }
        if ($request->filled('processor_id')) {
            $query->where('processor_id', $request->input('processor_id'));
        }

        $processing = $query->orderBy('started_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $processing
        ]);
    }

    public function getReturnLineItems(Request $request): JsonResponse
    {
        $query = ReturnLineItem::with(['returnRequest', 'product', 'approver']);

        if ($request->filled('return_id')) {
            $query->where('return_id', $request->input('return_id'));
        }
        if ($request->filled('condition')) {
            $query->where('condition', $request->input('condition'));
        }
        if ($request->filled('disposition')) {
            $query->where('disposition', $request->input('disposition'));
        }
        if ($request->filled('approved')) {
            $query->where('approved', $request->input('approved'));
        }

        $lineItems = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $lineItems
        ]);
    }

    // CRUD Operations for Return Requests
    public function storeReturnRequest(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_id' => 'required|integer|exists:companies,id',
            'location_id' => 'required|integer|exists:locations,id',
            'return_type' => 'required|string|in:defective,unsatisfied,size_exchange,color_exchange,damaged_shipping,warranty,other',
            'priority' => 'required|string|in:low,medium,high,urgent',
            'status' => 'required|string|in:requested,authorized,received,processing,completed,cancelled',
            'request_date' => 'required|date',
            'expected_return_date' => 'nullable|date|after:request_date',
            'actual_return_date' => 'nullable|date',
            'return_reason_id' => 'nullable|integer|exists:return_reasons,id',
            'return_notes' => 'nullable|string|max:1000',
            'total_value' => 'nullable|numeric|min:0',
            'refund_amount' => 'nullable|numeric|min:0',
            'return_metadata' => 'nullable|array',
        ]);

        $returnRequest = ReturnRequest::create($validated);
        $returnRequest->load(['customer', 'location', 'returnAuthorization', 'lineItems.product']);

        return response()->json(['success' => true, 'data' => $returnRequest], 201);
    }

    public function showReturnRequest(ReturnRequest $returnRequest): JsonResponse
    {
        $returnRequest->load(['customer', 'location', 'returnAuthorization', 'lineItems.product']);
        return response()->json(['success' => true, 'data' => $returnRequest]);
    }

    public function updateReturnRequest(Request $request, ReturnRequest $returnRequest): JsonResponse
    {
        $validated = $request->validate([
            'customer_id' => 'sometimes|integer|exists:companies,id',
            'location_id' => 'sometimes|integer|exists:locations,id',
            'return_type' => 'sometimes|string|in:defective,unsatisfied,size_exchange,color_exchange,damaged_shipping,warranty,other',
            'priority' => 'sometimes|string|in:low,medium,high,urgent',
            'status' => 'sometimes|string|in:requested,authorized,received,processing,completed,cancelled',
            'request_date' => 'sometimes|date',
            'expected_return_date' => 'nullable|date|after:request_date',
            'actual_return_date' => 'nullable|date',
            'return_reason_id' => 'nullable|integer|exists:return_reasons,id',
            'return_notes' => 'nullable|string|max:1000',
            'total_value' => 'nullable|numeric|min:0',
            'refund_amount' => 'nullable|numeric|min:0',
            'return_metadata' => 'nullable|array',
        ]);

        $returnRequest->update($validated);
        $returnRequest->load(['customer', 'location', 'returnAuthorization', 'lineItems.product']);

        return response()->json(['success' => true, 'data' => $returnRequest]);
    }

    public function destroyReturnRequest(ReturnRequest $returnRequest): JsonResponse
    {
        $returnRequest->delete();
        return response()->json(['success' => true, 'message' => 'Return request deleted successfully']);
    }

    // CRUD Operations for Return Authorizations
    public function storeReturnAuthorization(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_id' => 'required|integer|exists:companies,id',
            'location_id' => 'required|integer|exists:locations,id',
            'authorization_type' => 'required|string|in:automatic,manual,manager_approval',
            'status' => 'required|string|in:pending,approved,rejected,expired',
            'request_date' => 'required|date',
            'authorization_date' => 'nullable|date|after_or_equal:request_date',
            'expiry_date' => 'nullable|date|after:authorization_date',
            'authorizer_id' => 'required|integer|exists:users,id',
            'authorization_notes' => 'nullable|string|max:1000',
            'authorization_metadata' => 'nullable|array',
        ]);

        $authorization = ReturnAuthorization::create($validated);
        $authorization->load(['customer', 'location', 'authorizer']);

        return response()->json(['success' => true, 'data' => $authorization], 201);
    }

    public function showReturnAuthorization(ReturnAuthorization $returnAuthorization): JsonResponse
    {
        $returnAuthorization->load(['customer', 'location', 'authorizer']);
        return response()->json(['success' => true, 'data' => $returnAuthorization]);
    }

    public function updateReturnAuthorization(Request $request, ReturnAuthorization $returnAuthorization): JsonResponse
    {
        $validated = $request->validate([
            'customer_id' => 'sometimes|integer|exists:companies,id',
            'location_id' => 'sometimes|integer|exists:locations,id',
            'authorization_type' => 'sometimes|string|in:automatic,manual,manager_approval',
            'status' => 'sometimes|string|in:pending,approved,rejected,expired',
            'request_date' => 'sometimes|date',
            'authorization_date' => 'nullable|date|after_or_equal:request_date',
            'expiry_date' => 'nullable|date|after:authorization_date',
            'authorizer_id' => 'sometimes|integer|exists:users,id',
            'authorization_notes' => 'nullable|string|max:1000',
            'authorization_metadata' => 'nullable|array',
        ]);

        $returnAuthorization->update($validated);
        $returnAuthorization->load(['customer', 'location', 'authorizer']);

        return response()->json(['success' => true, 'data' => $returnAuthorization]);
    }

    public function destroyReturnAuthorization(ReturnAuthorization $returnAuthorization): JsonResponse
    {
        $returnAuthorization->delete();
        return response()->json(['success' => true, 'message' => 'Return authorization deleted successfully']);
    }

    // CRUD Operations for Return Line Items
    public function storeReturnLineItem(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'return_id' => 'required|integer|exists:return_requests,id',
            'product_id' => 'required|integer|exists:products,id',
            'quantity_returned' => 'required|integer|min:1',
            'condition' => 'required|string|in:new,like_new,good,fair,poor,defective',
            'disposition' => 'required|string|in:refund,exchange,repair,dispose,quarantine',
            'unit_price' => 'nullable|numeric|min:0',
            'total_amount' => 'nullable|numeric|min:0',
            'approved' => 'boolean',
            'approver_id' => 'nullable|integer|exists:users,id',
            'approval_date' => 'nullable|date',
            'line_item_notes' => 'nullable|string|max:500',
            'line_item_metadata' => 'nullable|array',
        ]);

        $lineItem = ReturnLineItem::create($validated);
        $lineItem->load(['returnRequest', 'product', 'approver']);

        return response()->json(['success' => true, 'data' => $lineItem], 201);
    }

    public function showReturnLineItem(ReturnLineItem $returnLineItem): JsonResponse
    {
        $returnLineItem->load(['returnRequest', 'product', 'approver']);
        return response()->json(['success' => true, 'data' => $returnLineItem]);
    }

    public function updateReturnLineItem(Request $request, ReturnLineItem $returnLineItem): JsonResponse
    {
        $validated = $request->validate([
            'return_id' => 'sometimes|integer|exists:return_requests,id',
            'product_id' => 'sometimes|integer|exists:products,id',
            'quantity_returned' => 'sometimes|integer|min:1',
            'condition' => 'sometimes|string|in:new,like_new,good,fair,poor,defective',
            'disposition' => 'sometimes|string|in:refund,exchange,repair,dispose,quarantine',
            'unit_price' => 'nullable|numeric|min:0',
            'total_amount' => 'nullable|numeric|min:0',
            'approved' => 'boolean',
            'approver_id' => 'nullable|integer|exists:users,id',
            'approval_date' => 'nullable|date',
            'line_item_notes' => 'nullable|string|max:500',
            'line_item_metadata' => 'nullable|array',
        ]);

        $returnLineItem->update($validated);
        $returnLineItem->load(['returnRequest', 'product', 'approver']);

        return response()->json(['success' => true, 'data' => $returnLineItem]);
    }

    public function destroyReturnLineItem(ReturnLineItem $returnLineItem): JsonResponse
    {
        $returnLineItem->delete();
        return response()->json(['success' => true, 'message' => 'Return line item deleted successfully']);
    }

    // CRUD Operations for Return Processing
    public function storeReturnProcessing(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'return_request_id' => 'required|integer|exists:return_requests,id',
            'return_line_item_id' => 'nullable|integer|exists:return_line_items,id',
            'status' => 'required|string|in:pending,in_progress,completed,cancelled',
            'processing_step' => 'required|string|in:receipt,inspection,disposition,refund,exchange,repair,disposal',
            'processor_id' => 'required|integer|exists:users,id',
            'started_at' => 'required|date',
            'completed_at' => 'nullable|date|after:started_at',
            'processing_notes' => 'nullable|string|max:1000',
            'processing_metadata' => 'nullable|array',
        ]);

        $processing = ReturnProcessing::create($validated);
        $processing->load(['returnRequest', 'returnLineItem', 'processor']);

        return response()->json(['success' => true, 'data' => $processing], 201);
    }

    public function showReturnProcessing(ReturnProcessing $returnProcessing): JsonResponse
    {
        $returnProcessing->load(['returnRequest', 'returnLineItem', 'processor']);
        return response()->json(['success' => true, 'data' => $returnProcessing]);
    }

    public function updateReturnProcessing(Request $request, ReturnProcessing $returnProcessing): JsonResponse
    {
        $validated = $request->validate([
            'return_request_id' => 'sometimes|integer|exists:return_requests,id',
            'return_line_item_id' => 'nullable|integer|exists:return_line_items,id',
            'status' => 'sometimes|string|in:pending,in_progress,completed,cancelled',
            'processing_step' => 'sometimes|string|in:receipt,inspection,disposition,refund,exchange,repair,disposal',
            'processor_id' => 'sometimes|integer|exists:users,id',
            'started_at' => 'sometimes|date',
            'completed_at' => 'nullable|date|after:started_at',
            'processing_notes' => 'nullable|string|max:1000',
            'processing_metadata' => 'nullable|array',
        ]);

        $returnProcessing->update($validated);
        $returnProcessing->load(['returnRequest', 'returnLineItem', 'processor']);

        return response()->json(['success' => true, 'data' => $returnProcessing]);
    }

    public function destroyReturnProcessing(ReturnProcessing $returnProcessing): JsonResponse
    {
        $returnProcessing->delete();
        return response()->json(['success' => true, 'message' => 'Return processing record deleted successfully']);
    }

    // CRUD Operations for Return Reasons
    public function storeReturnReason(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'reason_name' => 'required|string|max:100',
            'category' => 'required|string|max:50',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'reason_metadata' => 'nullable|array',
        ]);

        $reason = ReturnReason::create($validated);
        return response()->json(['success' => true, 'data' => $reason], 201);
    }

    public function showReturnReason(ReturnReason $returnReason): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $returnReason]);
    }

    public function updateReturnReason(Request $request, ReturnReason $returnReason): JsonResponse
    {
        $validated = $request->validate([
            'reason_name' => 'sometimes|string|max:100',
            'category' => 'sometimes|string|max:50',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'reason_metadata' => 'nullable|array',
        ]);

        $returnReason->update($validated);
        return response()->json(['success' => true, 'data' => $returnReason]);
    }

    public function destroyReturnReason(ReturnReason $returnReason): JsonResponse
    {
        $returnReason->delete();
        return response()->json(['success' => true, 'message' => 'Return reason deleted successfully']);
    }

    // CRUD Operations for Return Dispositions
    public function storeReturnDisposition(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'disposition_name' => 'required|string|max:100',
            'disposition_type' => 'required|string|in:refund,exchange,repair,dispose,quarantine,resell',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'disposition_metadata' => 'nullable|array',
        ]);

        $disposition = ReturnDisposition::create($validated);
        return response()->json(['success' => true, 'data' => $disposition], 201);
    }

    public function showReturnDisposition(ReturnDisposition $returnDisposition): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $returnDisposition]);
    }

    public function updateReturnDisposition(Request $request, ReturnDisposition $returnDisposition): JsonResponse
    {
        $validated = $request->validate([
            'disposition_name' => 'sometimes|string|max:100',
            'disposition_type' => 'sometimes|string|in:refund,exchange,repair,dispose,quarantine,resell',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'disposition_metadata' => 'nullable|array',
        ]);

        $returnDisposition->update($validated);
        return response()->json(['success' => true, 'data' => $returnDisposition]);
    }

    public function destroyReturnDisposition(ReturnDisposition $returnDisposition): JsonResponse
    {
        $returnDisposition->delete();
        return response()->json(['success' => true, 'message' => 'Return disposition deleted successfully']);
    }
}