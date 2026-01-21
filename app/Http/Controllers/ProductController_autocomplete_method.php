    /**
     * Search autocomplete suggestions API
     */
    public function searchSuggestions(Request $request)
    {
        $search = $request->input('q', '');
        
        if (strlen($search) < 2) {
            return response()->json([]);
        }
        
        $products = Product::where('is_active', true)
            ->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhereHas('brand', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  });
            })
            ->with('brand')
            ->limit(5)
            ->get(['id', 'name', 'slug', 'price', 'brand_id']);
        
        return response()->json($products);
    }
