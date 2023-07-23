<?php
namespace App\Traits;
use App\Models\{Topic, Category};

trait CachesCategoriesAndTopics
{
    public function categoriesCache($rename = false)
    {
        $categories = cache()->remember('categories', now()->addHour(24), static function () {
            return Category::query()->select('title', 'id')->get();
        });

        if ($rename) {
            return $categories->map(static fn($category) => [
                "label" => $category->title,
                "value" => $category->id
            ])->all();
        }
        return $categories;

    }

    public function topicsCache($rename = false)
    {
        $topics = cache()->remember('topics', now()->addHour(24), static function () {
            return Topic::query()->select('title', 'id')->get();
        });

        if ($rename) {
          return  $topics->map(static fn($topic) => [
                "label" => $topic->title,
                "value" => $topic->id
            ])->all();
        }
        return $topics;
    }
}
