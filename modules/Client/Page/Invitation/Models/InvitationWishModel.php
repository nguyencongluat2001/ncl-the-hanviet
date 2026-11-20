<?php

namespace Modules\Client\Page\Invitation\Models;

use Illuminate\Database\Eloquent\Model;

class InvitationWishModel extends Model
{
    protected $table = 'invitation_wishes';

    protected $fillable = [
        'invitation_id',
        'name',
        'message',
    ];
}
