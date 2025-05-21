<?php

namespace App\Exports;

use App\Models\Post;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PostsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Post::select('title', 'slug', 'macros')->get()->map(function ($post) {
            return [
                $post->title,
                $post->slug,
                $post->macros['calories'] ?? '',
                $post->macros['protein'] ?? '',
            ];
        });
    }

    public function headings(): array
    {
        return ['Título', 'Slug', 'Calorías', 'Proteínas'];
    }
}
