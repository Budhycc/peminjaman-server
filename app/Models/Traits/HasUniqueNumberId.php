<?php

namespace App\Models\Traits;

trait HasUniqueNumberId
{
    protected static function bootHasUniqueNumberId()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = $model->generateUniqueNumberId();
            }
        });
    }

    public function initializeHasUniqueNumberId()
    {
        $this->incrementing = false;
        $this->keyType = 'int';
    }

    protected function generateUniqueNumberId()
    {
        do {
            // Generate a 15-digit unique number based on time and random digits
            $id = (int) (date('ymdHis') . mt_rand(100, 999));
        } while (static::where($this->getKeyName(), $id)->exists());

        return $id;
    }
}

