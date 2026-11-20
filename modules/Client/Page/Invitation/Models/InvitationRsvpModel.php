<?php

namespace Modules\Client\Page\Invitation\Models;

use Illuminate\Database\Eloquent\Model;

class InvitationRsvpModel extends Model
{
    protected $table = 'invitation_rsvps';

    protected $fillable = [
        'invitation_id',
        'name',
        'phone',
        'attending',
        'guests',
        'side',
        'note',
    ];

    protected $casts = [
        'attending' => 'boolean',
    ];
}
