<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DecisionSupport extends Model
{
    use HasFactory;

    protected $table = 'decision_support';

    protected $fillable = [
        'title',
        'content',
        'summary',
        'category',
        'sub_category',
        'difficulty_level',
        'is_featured',
        'is_published',
        'views_count',
        'image',
        'keywords',
        'created_by',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'keywords' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = ['topic', 'image_url'];

    public function getTopicAttribute()
    {
        return $this->sub_category;
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) return null;
        return filter_var($this->image, FILTER_VALIDATE_URL)
            ? $this->image
            : url('storage/' . ltrim($this->image, '/'));
    }

    // Scopes for filtering
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeTopic($query, $topic)
    {
        return $query->where('sub_category', $topic);
    }

    // Get animal icon and color
    public function getAnimalInfoAttribute()
    {
        $animals = [
            'cattle' => ['name' => 'Cattle', 'icon' => 'fa-cow', 'color' => '#795548'],
            'goat' => ['name' => 'Goats', 'icon' => 'fa-paw', 'color' => '#2e7d32'],
            'sheep' => ['name' => 'Sheep', 'icon' => 'fa-paw', 'color' => '#607d8b'],
            'poultry' => ['name' => 'Poultry', 'icon' => 'fa-kiwi-bird', 'color' => '#f57c00'],
            'pig' => ['name' => 'Pigs', 'icon' => 'fa-paw', 'color' => '#c2185b'],
            'rabbit' => ['name' => 'Rabbits', 'icon' => 'fa-rabbit', 'color' => '#6a1b9a'],
        ];

        return $animals[$this->category] ?? ['name' => 'General', 'icon' => 'fa-book', 'color' => '#666'];
    }

    // Get topic info
    public function getTopicInfoAttribute()
    {
        $topics = [
            'feeding' => ['title' => 'Feeding & Nutrition', 'icon' => 'fa-wheat-awn'],
            'health' => ['title' => 'Health & Disease', 'icon' => 'fa-heart-pulse'],
            'breeding' => ['title' => 'Breeding & Reproduction', 'icon' => 'fa-dna'],
            'housing' => ['title' => 'Housing & Facilities', 'icon' => 'fa-house'],
            'marketing' => ['title' => 'Marketing & Sales', 'icon' => 'fa-chart-line'],
        ];

        return $topics[$this->topic] ?? ['title' => 'General', 'icon' => 'fa-info-circle'];
    }
}
