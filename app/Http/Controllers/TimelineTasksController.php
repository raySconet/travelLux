<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimelineTask;
use App\Models\Product;
use App\Models\Destination;

class TimelineTasksController extends Controller
{
    public function index(Request $request)
    {
       $search = $request->input('search'); 
       $timelineTasksQuery = TimelineTask::with('product', 'destination')
                                         ->select('id','product_id','destination_id','task_name','priority','due_days','before_after','date_type','created_by','is_deleted')
                                         ->where('is_deleted',0);
       $products = Product::orderBy('product_name')->get();
       $destinations = Destination::orderBy('destination_name')->get();
       $productId = $request->input('product_id');
        $destinationId = $request->input('destination_id'); 

        if ($productId && $productId != '') {
            $timelineTasksQuery->where('product_id', $productId);
        }

        if ($destinationId && $destinationId != '') {
            $timelineTasksQuery->where('destination_id', $destinationId);
        }
        
        if ($search) {
            $timelineTasksQuery->where(function ($query) use ($search) {

                $query->whereHas('product', function ($q) use ($search) {
                    $q->where('product_name', 'like', "%{$search}%");
                })

                ->orWhereHas('destination', function ($q) use ($search) {
                    $q->where('destination_name', 'like', "%{$search}%");
                })

                ->orWhere('task_name', 'like', "%{$search}%")
                ->orWhere('priority', 'like', "%{$search}%")
                ->orWhere('due_days', 'like', "%{$search}%")
                ->orWhere('before_after', 'like', "%{$search}%")
                ->orWhere('date_type', 'like', "%{$search}%");

            });
        }

       $timelineTasks = $timelineTasksQuery->get();
       return view('timelineTasks', compact('timelineTasks','products', 'destinations'));
    }

    public function edit(TimelineTask $timelineTask)
    {
        $isNewTimelineTask = false;
        $products = Product::orderBy('product_name')->get();
        $destinations = Destination::orderBy('destination_name')->get();
        return view('timeline-tasks.edit', compact('timelineTask', 'isNewTimelineTask','products','destinations'));
    }

    public function create(TimelineTask $timelineTask)
    {
        $timelineTask = new TimelineTask();
        $isNewTimelineTask = true;
        $products = Product::orderBy('product_name')->get();
        $destinations = Destination::orderBy('destination_name')->get();
        return view('timeline-tasks.edit', compact('timelineTask', 'isNewTimelineTask','products','destinations'));
    }

    public function store(Request $request)
    {
        $messages = [
            'product_id.required' => 'The Product field is required.',
            'destination_id.required' => 'The Destination field is required.',
            'task_name.required' => 'The Task Name field is required.',
            'priority.required' => 'The Priority field is required.',
            'before_after.required' => 'The Before/After field is required.',
            'date_type.required' => 'The Date Type field is required.'
        ];

        $data = $request->validate([
            'product_id' => 'required|integer',
            'destination_id' => 'required|integer',
            'task_name' => 'required|string:max:255',
            'priority' => 'required|string|max:255',
            'before_after' => 'required|string|max:255',
            'date_type' => 'required|string|max:255',
            'due_days' => 'nullable|integer'
        ],$messages);

        $data['created_by'] = auth()->id();
        $data['created_on'] = now();

        TimelineTask::create($data);

        return redirect()
                ->route('timelinetasks')
                ->with('success', 'Timeline Task created successfully');
    }

    public function update(Request $request, TimelineTask $timelineTask)
    {
        $messages = [
            'product_id.required' => 'The Product field is required.',
            'destination_id.required' => 'The Destination field is required.',
            'task_name.required' => 'The Task Name field is required.',
            'priority.required' => 'The Priority field is required.',
            'before_after.required' => 'The Before/After field is required.',
            'date_type.required' => 'The Date Type field is required.' 
        ];

        $data = $request->validate([
            'product_id' => 'required|integer',
            'destination_id' => 'required|integer',
            'task_name' => 'required|string|max:255',
            'priority' => 'required|string|max:255',
            'before_after' => 'required|string|max:255',
            'date_type' => 'required|string|max:255',
            'due_days' => 'nullable|integer'
        ], $messages);

        $data['last_modified_by'] = auth()->id();
        $data['last_modfified_on'] = now();

        $timelineTask->update($data);

        return redirect()
                ->route('timelinetasks', $timelineTask->id)
                ->with('success', 'Timeline Task updated successfully');
    }

    public function destroy(TimelineTask $timelineTask)
    {
        $timelineTask->update([
            'is_deleted' => 1,
            'last_modified_by' => auth()->id(),
            'last_modified_on' => now(),
        ]);

        return redirect()
                ->route('timelinetasks')
                ->with('success', 'Timeline Task deleted successfully');
    }
}
