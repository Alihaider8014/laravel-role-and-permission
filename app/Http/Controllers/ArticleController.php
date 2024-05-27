<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function add(Request $request)
    {
        $newArticle = Article::create([
            'title' => $request->title,
            // Add other fields if needed
        ]);
        return response()->json(['message' => 'Article added successfully', 'data' => $newArticle]);
    }

    public function edit($id, Request $request)
    {
        $article = Article::find($id);
        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }
        $article->update([
            'title' => $request->title,
            // Update other fields if needed
        ]);
        return response()->json(['message' => 'Article updated successfully', 'data' => $article]);
    }

    public function view($id)
    {
        $article = Article::find($id);
        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        return response()->json(['data' => $article]);
    }

    public function delete($id)
    {
        $article = Article::find($id);
        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }
        $article->delete();
        return response()->json(['message' => 'Article deleted successfully']);
    }
}
