<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HealthCheckController extends Controller
{
    /**
     * Check the health of the application
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $checks = [
            'status' => 'ok',
            'database' => $this->checkDatabase(),
            'environment' => app()->environment(),
            'timestamp' => now()->toIso8601String()
        ];

        $statusCode = collect($checks)->contains('error') ? 500 : 200;

        return response()->json($checks, $statusCode);
    }

    /**
     * Check database connection
     *
     * @return array
     */
    private function checkDatabase()
    {
        try {
            // Attempt a simple query
            DB::select('SELECT 1');
            return ['status' => 'ok'];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}
