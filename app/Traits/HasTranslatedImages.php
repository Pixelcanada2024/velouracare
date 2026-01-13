<?php

namespace App\Traits;

use App\Models\TranslatedImage;

trait HasTranslatedImages
{
    public function translatedImages()
    {
        return $this->morphMany(TranslatedImage::class, 'model');
    }

        /*
     * to eager load of banner
      use $blog->with('translatedImages.upload')
    *  & use resource like this
        $banner = $blog->getImage('banner');
        $bannerUrl = !!$banner
            ? ($banner->file_name
                ? asset('/public/' . $banner->file_name)
                : $banner->external_link)
            : null;
    */

    protected function getTranslatedImage(string $key, ?string $locale = null)
    {
        $locale = $locale ?? app()->getLocale();

        $image = $this->translatedImages
            ->where('key', $key)
            ->where('locale', $locale)
            ->first()
            ?? $this->translatedImages
                ->where('key', $key)
                ->where('locale', 'en')
                ->first();

        return $image;
    }

    public function getImageId(string $key, ?string $locale = null)
    {
        $image = $this->getTranslatedImage($key, $locale);

        return $image?->upload_id ?? null;
    }

    public function getImage(string $key, ?string $locale = null)
    {
        $image = $this->getTranslatedImage($key, $locale);

        return $image?->upload ?? null;
    }

    public function setImage(string $key, int $uploadId, ?string $locale = null)
    {
        $locale = $locale ?? "en";

        return $this->translatedImages()->updateOrCreate(
            ['key' => $key, 'locale' => $locale],
            ['upload_id' => $uploadId]
        );
    }

    public function getAllImages(string $key)
    {
        return $this->translatedImages->where('key', $key)?->pluck('upload', 'locale');
    }

    public function getAllImageIds(string $key)
    {
        return $this->translatedImages->where('key', $key)?->pluck('upload_id', 'locale');
    }

    public function deleteIfExist($key, $locale = 'en'){
      return $this->translatedImages()->where('key', $key)->where('locale', operator: $locale)?->delete();
    }
}
