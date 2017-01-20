<?php
/**
 * This file (EducationProgram.php) was created on 01/20/2017 at 10:44.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class EducationProgram extends Model{
    // Override the table used for the User Model
    protected $table = 'EducationProgram';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'ep_id';

    // Default
    protected $fillable = [
        'ep_id',
        'ep_name',
    ];

    public function educationprogramtype(){
        return $this->hasOne('App\EducationProgramType', 'eptype_id', 'eptype_id');
    }

    public function User(){
        return $this->belongsTo('App\User');
    }
}