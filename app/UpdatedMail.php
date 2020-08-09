<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UpdatedMail extends Model
{
    use Notifiable;

    protected $table = 'updated_mails';

    protected $fillable = [ 'email', 'activation_code', 'user_id'];
}
