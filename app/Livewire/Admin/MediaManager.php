<?php

namespace App\Livewire\Admin;

use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class MediaManager extends Component
{
    use WithFileUploads, WithPagination;

    /** @var array<int, \Livewire\Features\SupportFileUploads\TemporaryUploadedFile> */
    public $files = [];

    public string $folder = '/';
    public string $search = '';

    public function upload(): void
    {
        $this->validate(['files.*' => 'file|max:10240']);

        foreach ($this->files as $file) {
            $path = $file->store('media/'.trim($this->folder, '/'), 'public');

            Media::create([
                'folder'  => $this->folder,
                'name'    => $file->getClientOriginalName(),
                'path'    => $path,
                'mime'    => $file->getMimeType(),
                'size'    => $file->getSize(),
                'user_id' => auth()->id(),
            ]);
        }

        $this->files = [];
        $this->dispatch('toast', message: 'Berkas diunggah.', variant: 'success');
    }

    public function delete(int $id): void
    {
        $media = Media::findOrFail($id);
        Storage::disk($media->disk)->delete($media->path);
        $media->delete();

        $this->dispatch('toast', message: 'Berkas dihapus.', variant: 'destructive');
    }

    public function render()
    {
        $query = Media::latest();
        if ($this->search !== '') {
            $query->where('name', 'like', '%'.$this->search.'%');
        }

        return view('livewire.admin.media', [
            'items'   => $query->paginate(24),
            'folders' => Media::select('folder')->distinct()->pluck('folder'),
            'used'    => Media::sum('size'),
        ])->layout('components.layouts.app', [
            'title'       => 'Media Manager',
            'breadcrumbs' => [['label' => 'Sistem'], ['label' => 'Media']],
        ]);
    }
}
