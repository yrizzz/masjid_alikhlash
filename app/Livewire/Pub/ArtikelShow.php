<?php

namespace App\Livewire\Pub;

use App\Models\Article;
use App\Models\Comment;
use Livewire\Component;

class ArtikelShow extends Component
{
    public Article $article;

    public string $commentName = '';
    public string $commentBody = '';

    public function mount(Article $article): void
    {
        abort_if($article->published_at === null || $article->published_at->isFuture(), 404);

        $this->article = $article->load(['category', 'author']);
        $article->increment('views');

        if ($user = auth()->user()) {
            $this->commentName = $user->name;
        }
    }

    public function comment(): void
    {
        $this->validate([
            'commentName' => 'required|min:3',
            'commentBody' => 'required|min:5',
        ], [], ['commentName' => 'nama', 'commentBody' => 'komentar']);

        Comment::create([
            'commentable_type' => 'artikel',
            'commentable_id'   => $this->article->id,
            'user_id'          => auth()->id(),
            'name'             => $this->commentName,
            'body'             => $this->commentBody,
        ]);

        $this->commentBody = '';
        $this->dispatch('toast', message: 'Komentar terkirim.', variant: 'success');
    }

    public function render()
    {
        return view('livewire.pub.artikel-show', [
            'related' => Article::published()->where('id', '!=', $this->article->id)
                ->where('category_id', $this->article->category_id)->latest('published_at')->take(3)->get(),
            'comments' => Comment::where('commentable_type', 'artikel')
                ->where('commentable_id', $this->article->id)
                ->where('is_approved', true)->latest()->get(),
        ])->layout('components.layouts.public', [
            'title'       => $this->article->title,
            'description' => $this->article->excerpt,
        ]);
    }
}
