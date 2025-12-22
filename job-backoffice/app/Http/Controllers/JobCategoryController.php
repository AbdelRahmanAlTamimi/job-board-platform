<?php

namespace App\Http\Controllers;

use App\Models\JobCategory;
use Illuminate\Http\Request;
use App\Http\Requests\JobCategoreCreateRequest;
use App\Http\Requests\JobCategoreUpdateRequest;

class JobCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $query = JobCategory::query();

        if ($status === 'archived') {
            $query->onlyTrashed();
        } else {
            $query->latest();
        }

        $jobCategories = $query->paginate(4)->withQueryString();

        return view('job-category.index', compact('jobCategories', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('job-category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JobCategoreCreateRequest $request)
    {
        $validated = $request->validated();
        JobCategory::create($validated);
        return redirect()->route('job-categories.index')->with('success', 'Job category created successfully');
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
    public function edit(JobCategory $jobCategory)
    {
        return view('job-category.edit', compact('jobCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JobCategoreUpdateRequest $request, JobCategory $jobCategory)
    {
        $validated = $request->validated();
        $jobCategory->update($validated);

        return redirect()->route('job-categories.index')
                         ->with('success', 'Job category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobCategory $jobCategory)
    {
        $jobCategory->delete();

        return redirect()
            ->route('job-categories.index')
            ->with('success', 'Job category archived successfully');
    }

    /**
     * Restore a soft-deleted category.
     */
    public function restore(string $id)
    {
        $jobCategory = JobCategory::withTrashed()->findOrFail($id);
        $jobCategory->restore();

        return redirect()
            ->route('job-categories.index', ['status' => 'archived'])
            ->with('success', 'Job category restored successfully');
    }
}
