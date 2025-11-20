<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    // hasMany(RelatedModel::class, $foreignKey, $localKey)

    public function comments()
    {
        // Relation Eloquent : un message possède plusieurs commentaires (clé étrangère : message_id)
        // 'message_id' → foreign key dans la table message_comments
        // 'id' → local key dans la table messages
        //   return $this->hasMany(MessageComment::class, 'message_id', 'id');

        return $this->hasMany(MessageComment::class);

    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
