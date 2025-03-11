<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Event::with(['categories', 'creator', 'tickets']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        } else {
            $query->published();
        }

        // Filter by category
        if ($request->has('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        // Filter by date range
        if ($request->has('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }
        
        if ($request->has('end_date')) {
            $query->where('end_date', '<=', $request->end_date);
        }

        // Filter by location
        if ($request->has('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // Search by title or description
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Featured events
        if ($request->has('featured') && $request->featured) {
            $query->featured();
        }

        // Upcoming events
        if ($request->has('upcoming') && $request->upcoming) {
            $query->upcoming();
        }

        // Sort by
        $sortField = $request->sort_by ?? 'start_date';
        $sortDirection = $request->sort_direction ?? 'asc';
        $query->orderBy($sortField, $sortDirection);

        $events = $query->paginate($request->per_page ?? 15);
        
        return response()->json($events);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Event::class);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:draft,published',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'featured_image' => 'nullable|image|max:5120', // 5MB max
            'is_featured' => 'boolean',
        ]);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('event-images', 'public');
            $validated['featured_image'] = $path;
        }

        // Set creator_id to current user
        $validated['creator_id'] = Auth::id();
        
        $event = Event::create($validated);

        // Attach categories if provided
        if (isset($validated['categories'])) {
            $event->categories()->attach($validated['categories']);
        }
        
        return response()->json([
            'message' => 'Event created successfully',
            'event' => $event->load('categories')
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        $event->load(['categories', 'creator', 'tickets']);
        
        return response()->json($event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);
        
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after_or_equal:start_date',
            'location' => 'sometimes|required|string|max:255',
            'address' => 'nullable|string',
            'capacity' => 'sometimes|required|integer|min:1',
            'status' => 'sometimes|required|in:draft,published,cancelled,completed',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'featured_image' => 'nullable|image|max:5120', 
            'is_featured' => 'boolean',
        ]);

      
        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($event->featured_image) {
                Storage::disk('public')->delete($event->featured_image);
            }
            
            $path = $request->file('featured_image')->store('event-images', 'public');
            $validated['featured_image'] = $path;
        }
        
        $event->update($validated);

        if (isset($validated['categories'])) {
            $event->categories()->sync($validated['categories']);
        }
        
        return response()->json([
            'message' => 'Event updated successfully',
            'event' => $event->load('categories')
        ]);
    }

   
}

