<?php

/**
 * Laravel multiple filter search
 */

# Model

// Filter 1 - One to Many
public function scopeOfFilter1($query, $filter1 = null)
{
    if (!empty($filter1)) {
        return $query->where('filter1_id', $filter1);
    }

    return $query;
}

// Filter 2 - Many to Many
public function scopeOfFilter2($query, $filter2 = null)
{
    // use ($filter2) - Inheriting variables from the parent scope
    if (!empty($filter2)) {
        return $query->whereHas('filter2s', function ($query) use ($filter2) {
            $query->where('filter2_id', $filter2);
        });
    }

    return $query;
}

# Controller

public function search(Request $request)
{
    return Model::when($request->filter1, function ($query) use ($request) {
                $query->ofFilter1($request->filter1);
            })
            ->when($request->filter2, function ($query) use ($request) {
                $query->ofFilter2($request->filter2);
            })
            ->get();
}
