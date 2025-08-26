<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FeeController extends Controller
{
    /**
     * Display a listing of fees.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Fee::with(['student.user']);
        
        // Filter by student
        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        
        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by fee type
        if ($request->has('fee_type')) {
            $query->where('fee_type', $request->fee_type);
        }
        
        $fees = $query->paginate(15);
        
        return response()->json([
            'status' => 'success',
            'data' => $fees
        ]);
    }

    /**
     * Store a newly created fee.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'fee_type' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'description' => 'nullable|string',
            'academic_year' => 'required|string|max:20',
            'semester' => 'nullable|string|max:50',
        ]);

        $fee = Fee::create(array_merge($request->all(), [
            'status' => 'pending'
        ]));
        
        $fee->load(['student.user']);

        return response()->json([
            'status' => 'success',
            'message' => 'Fee created successfully',
            'data' => $fee
        ], 201);
    }

    /**
     * Display the specified fee.
     */
    public function show(Fee $fee): JsonResponse
    {
        $fee->load(['student.user']);
        
        return response()->json([
            'status' => 'success',
            'data' => $fee
        ]);
    }

    /**
     * Update the specified fee.
     */
    public function update(Request $request, Fee $fee): JsonResponse
    {
        $request->validate([
            'fee_type' => 'sometimes|string|max:100',
            'amount' => 'sometimes|numeric|min:0',
            'due_date' => 'sometimes|date',
            'description' => 'nullable|string',
            'status' => 'sometimes|in:pending,paid,overdue,partial',
            'academic_year' => 'sometimes|string|max:20',
            'semester' => 'nullable|string|max:50',
        ]);

        $fee->update($request->all());
        $fee->load(['student.user']);

        return response()->json([
            'status' => 'success',
            'message' => 'Fee updated successfully',
            'data' => $fee
        ]);
    }

    /**
     * Remove the specified fee.
     */
    public function destroy(Fee $fee): JsonResponse
    {
        $fee->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Fee deleted successfully'
        ]);
    }

    /**
     * Mark fee as paid.
     */
    public function markPaid(Request $request, Fee $fee): JsonResponse
    {
        $request->validate([
            'payment_method' => 'nullable|string|max:50',
            'transaction_id' => 'nullable|string|max:100',
            'payment_notes' => 'nullable|string',
        ]);

        $fee->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'payment_notes' => $request->payment_notes,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Fee marked as paid successfully',
            'data' => $fee->load(['student.user'])
        ]);
    }

    /**
     * Get fee summary for a student.
     */
    public function studentSummary($studentId): JsonResponse
    {
        $fees = Fee::where('student_id', $studentId)->get();
        
        $summary = [
            'total_fees' => $fees->sum('amount'),
            'paid_fees' => $fees->where('status', 'paid')->sum('amount'),
            'pending_fees' => $fees->where('status', 'pending')->sum('amount'),
            'overdue_fees' => $fees->where('status', 'overdue')->sum('amount'),
            'fees_breakdown' => $fees->groupBy('fee_type')->map(function ($typeFees) {
                return [
                    'total' => $typeFees->sum('amount'),
                    'paid' => $typeFees->where('status', 'paid')->sum('amount'),
                    'pending' => $typeFees->where('status', 'pending')->sum('amount'),
                ];
            }),
            'recent_fees' => $fees->sortByDesc('created_at')->take(5)->values()
        ];
        
        return response()->json([
            'status' => 'success',
            'data' => $summary
        ]);
    }
}
