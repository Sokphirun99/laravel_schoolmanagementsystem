<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:portal');
    }

    /**
     * Display a listing of fees.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::guard('portal')->user();
        
        if ($user->user_type === 'student') {
            $studentId = $user->student->id;
            $fees = Fee::where('student_id', $studentId)->paginate(10);
        } else {
            // For parent users
            $studentIds = $user->parent->students->pluck('id')->toArray();
            $fees = Fee::whereIn('student_id', $studentIds)->paginate(10);
        }
        
        return view('portal.fees.index', compact('fees'));
    }

    /**
     * Display the payment form for a fee.
     *
     * @param  \App\Models\Fee  $fee
     * @return \Illuminate\Http\Response
     */
    public function showPaymentForm(Fee $fee)
    {
        $user = Auth::guard('portal')->user();
        
        // Security check: ensure the fee belongs to the student or parent's student
        if ($user->user_type === 'student') {
            if ($fee->student_id !== $user->student->id) {
                return abort(403, 'You are not authorized to pay this fee.');
            }
        } else {
            $studentIds = $user->parent->students->pluck('id')->toArray();
            if (!in_array($fee->student_id, $studentIds)) {
                return abort(403, 'You are not authorized to pay this fee.');
            }
        }
        
        return view('portal.fees.pay', compact('fee'));
    }

    /**
     * Process a fee payment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fee  $fee
     * @return \Illuminate\Http\Response
     */
    public function processPayment(Request $request, Fee $fee)
    {
        $user = Auth::guard('portal')->user();
        
        // Security check: ensure the fee belongs to the student or parent's student
        if ($user->user_type === 'student') {
            if ($fee->student_id !== $user->student->id) {
                return abort(403, 'You are not authorized to pay this fee.');
            }
        } else {
            $studentIds = $user->parent->students->pluck('id')->toArray();
            if (!in_array($fee->student_id, $studentIds)) {
                return abort(403, 'You are not authorized to pay this fee.');
            }
        }
        
        // Validate request
        $validated = $request->validate([
            'payment_method' => 'required|string',
            'transaction_id' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);
        
        // Update fee status
        $fee->payment_date = now();
        $fee->payment_method = $validated['payment_method'];
        $fee->transaction_id = $validated['transaction_id'];
        $fee->status = 'paid';
        $fee->remarks = $validated['remarks'] ?? 'Payment completed';
        $fee->save();
        
        return redirect()->route('portal.fees.index')
            ->with('success', 'Payment completed successfully!');
    }
}
