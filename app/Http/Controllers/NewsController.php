<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    // Tampilkan daftar berita
    public function index()
    {
        $news = News::with('author')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
        
        return view('admin.news.index', compact('news'));
    }

    // Form tambah berita
    public function create()
    {
        return view('admin.news.create');
    }

    // Simpan berita baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'link_text' => 'nullable|string|max:255',
            'link_url' => 'nullable|url|max:500',
            'status' => 'required|in:draft,published',
        ]);

        // Upload gambar jika ada
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')
                                                   ->store('news', 'public');
        }

        // Set published_at jika status published
        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        // Set author
        $validated['author_id'] = Auth::id();

        News::create($validated);

        return redirect()->route('admin.news.index')
                         ->with('success', 'Berita berhasil ditambahkan!');
    }

    // Form edit berita
    public function edit($id)
    {
        $news = News::findOrFail($id);
        return view('admin.news.edit', compact('news'));
    }

    // Update berita
    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'link_text' => 'nullable|string|max:255',
            'link_url' => 'nullable|url|max:500',
            'status' => 'required|in:draft,published',
        ]);

        // Upload gambar baru jika ada
        if ($request->hasFile('featured_image')) {
            // Hapus gambar lama
            if ($news->featured_image) {
                Storage::disk('public')->delete($news->featured_image);
            }
            
            $validated['featured_image'] = $request->file('featured_image')
                                                   ->store('news', 'public');
        }

        // Update published_at
        if ($validated['status'] === 'published' && $news->status === 'draft') {
            $validated['published_at'] = now();
        }

        $news->update($validated);

        return redirect()->route('admin.news.index')
                         ->with('success', 'Berita berhasil diperbarui!');
    }

    // Hapus berita
    public function destroy($id)
    {
        $news = News::findOrFail($id);

        // Hapus gambar jika ada
        if ($news->featured_image) {
            Storage::disk('public')->delete($news->featured_image);
        }

        $news->delete();

        return redirect()->route('admin.news.index')
                         ->with('success', 'Berita berhasil dihapus!');
    }
}
