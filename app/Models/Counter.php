<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Counter extends Model
{
    protected $collection = 'counters';

    public function scopeNextId($builder, $className)
    {
        $collectionName = $className;

        if (class_exists($className) && is_subclass_of($className, Model::class)) {
            $collectionName = (new $className)->getTable();
        }

        $seq = static::raw(function ($collection) use($collectionName){
            return $collection->findOneAndUpdate(
                array('_id' => $collectionName),
                array('$inc' => array('seq' => 1)),
                array('new' => true, 'upsert' => true, 'returnDocument' => \MongoDB\Operation\FindOneAndUpdate::RETURN_DOCUMENT_AFTER)
            );
        });

        return $seq->seq;
    }
}
