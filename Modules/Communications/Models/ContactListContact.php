<?php

namespace Modules\Communications\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ContactListContact extends Pivot
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The table associated with the model.
     */
    protected $table = 'contact_list_contacts';
}
