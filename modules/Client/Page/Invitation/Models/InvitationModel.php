<?php

namespace Modules\Client\Page\Invitation\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class InvitationModel extends Model
{
    use HasUuids;

    protected $table = 'invitations';

    protected $fillable = [
        'slug',
        'edit_token',
        'template',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function wishes()
    {
        return $this->hasMany(InvitationWishModel::class, 'invitation_id')->latest();
    }

    public function rsvps()
    {
        return $this->hasMany(InvitationRsvpModel::class, 'invitation_id')->latest();
    }
}
