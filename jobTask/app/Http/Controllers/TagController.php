<?php
namespace App\Http\Controllers;
use App\Models\Tag;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum'); // Apply authentication middleware
    }

    // a. View all tags
    public function index()
    {
        return response()->json(Tag::all());
    }

    // b. Store new tag
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:tags|max:255',
        ]);

        $tag = Tag::create($validatedData);

        return response()->json($tag, 201);
    }

    // c. Update a single tag
    public function update(Request $request, Tag $tag)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:tags,name,' . $tag->id . '|max:255',
        ]);

        $tag->update($validatedData);

        return response()->json($tag);
    }

    // d. Delete a single tag
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return response()->json(['message' => 'Tag deleted successfully.']);
    }
}
