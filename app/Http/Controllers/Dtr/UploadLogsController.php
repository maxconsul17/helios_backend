<?php

namespace App\Http\Controllers\Dtr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Dtr\FacialLog;
use Carbon\Carbon;

class UploadLogsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate uploaded file
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        // 2. Get and read the file
        $file = $request->file('file');
        $rows = array_map('str_getcsv', file($file->getRealPath()));

        $maxRows = 5000; // Set your desired maximum (excluding header)

        if (count($rows) - 1 > $maxRows) {
            return response()->json([
                'status' => 'error',
                'message' => "CSV file exceeds maximum allowed rows of $maxRows.",
            ], 413); // 413 Payload Too Large
        }

        if (count($rows) < 2) {
            return response()->json([
                'status' => 'error',
                'message' => 'CSV file is empty or has missing data.',
            ], 400);
        }

        // 3. Define expected headers
        $headers = ['person_id', 'time', 'device_id', 'site'];
        unset($rows[0]); // Remove the CSV header row

        $skipped = [];
        $inserted = 0;

        // 4. Start DB transaction
        DB::beginTransaction();

        try {
            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2; // Add 2 to show real CSV row (including header)

                // Check if row has all required columns
                if (count($row) !== count($headers)) {
                    $skipped[] = [
                        'row' => $rowNumber,
                        'reason' => 'Incorrect number of columns'
                    ];
                    continue;
                }

                $data = array_combine($headers, $row);

                // Parse datetime
                try {
                    $data['time'] = date('Y-m-d H:i:s', strtotime($data['time']));
                } catch (\Exception $e) {
                    $skipped[] = [
                        'row' => $rowNumber,
                        'reason' => 'Invalid datetime format'
                    ];
                    continue;
                }

                // Attempt to insert
                try {
                    FacialLog::create($data);
                    $inserted++;
                } catch (\Exception $e) {
                    $skipped[] = [
                        'row' => $rowNumber,
                        'reason' => 'DB insert failed: ' . $e->getMessage()
                    ];
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'CSV imported with some rows skipped.',
                'inserted_rows' => $inserted,
                'skipped_rows' => $skipped,
                'total_rows' => count($rows),
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process CSV: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
