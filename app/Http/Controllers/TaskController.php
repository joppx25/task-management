<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class TaskController extends Controller
{
    /**
     * Render the all and search tasks
     * 
     * @param Request $request
     * @return Inertia
     */
    public function index(Request $request)
    {
        return Inertia::render('Task', [
            'tasks' => Task::query()
                ->when($request->search, function ($query, $search) {
                    $query->where('title', 'like', "%{$search}%");
                })
                ->where('user_id', Auth::user()->id)
                ->active()
                ->orderBy('id', 'desc')
                ->paginate(10)
                ->withQueryString(),
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Perform a store or update base on the request data received
     * 
     * @param Request $request
     * @return json
     */
    public function storeOrUpdate(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255'
        ]);

        if (empty($request->id)) {
            Task::create($validated + ['user_id' => Auth::user()->id]);
        } else {
            $task = Task::find($request->id);
            $task->title = $validated['title'];
            $task->save();
        }

        return response()->json([
            'message' => $request->has('id') ? 'Successfully Updated!' : 'Successfully Created!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request $request
     * @return Redirect
     */
    public function destroy(Request $request)
    {
        Task::findOrFail($request->id)
            ->delete();
        return Redirect::route('tasks.index');
    }
}
