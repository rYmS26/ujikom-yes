<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemakaian;

class SearchController extends Controller
{
    /**
     * Search in the 'pemakaians' table by 'NoKontrol'.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        // Validate the request input
        $validated = $request->validate([
            'query' => 'required|string|min:1'
        ]);

        // Retrieve the validated query parameter
        $query = $validated['query'];

        // Perform the search using the 'LIKE' operator, with an optional limit
        $results = Pemakaian::query()
                            ->where('NoKontrol', 'LIKE', '%' . $query . '%')
                            ->limit(10)
                            ->get(['NoKontrol', 'column2', 'column3']); // specify the columns you need here

        // Return results as a JSON response
        return response()->json([
            'data' => $results,
            'message' => 'Search results fetched successfully.',
            'status' => 200
        ]);
    }
}
