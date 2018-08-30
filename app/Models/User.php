<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User implements JWTSubject {

    use Notifiable;
    
    /**
     * $table indicates the table associated with the model.
     * $primaryKey indicates the table's primary key
     * 
     * @var string
     */

    protected $table = 'tusr', $primaryKey = 'usr_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'usr_id', 'usr_tcd', 'usr_scd', 'usr_nm', 'usr_fnm', 'usr_lnm', 'usr_gndr', 'usr_brday', 'usr_prfs_nm', 'usr_addr_ln1_txt', 'usr_addr_ln2_txt', 'usr_pwd_last_updt_dt', 'usr_pwd_dflt_ind', 'usr_email_txt', 'usr_email_cnfm_ind', 'usr_email_cnfm_dt', 'usr_email_dflt_ind', 'usr_email2_txt', 'usr_email2_cnfm_ind', 'usr_email2_cnfm_dt', 'usr_phn1_ctry_cd', 'usr_phn1_nbr', 'usr_phn1_fnbr', 'usr_phn1_cnfm_ind', 'usr_phn1_cnfm_dt', 'usr_phn2_ctry_cd', 'usr_phn2_nbr', 'usr_phn2_fnbr', 'usr_phn2_cnfm_ind', 'usr_phn2_cnfm_dt', 'usr_phn3_ctry_cd', 'usr_phn3_nbr', 'usr_phn3_fnbr', 'usr_phn3_cnfm_ind', 'usr_phn3_cnfm_dt', 'usr_phn4_ctry_cd', 'usr_phn4_nbr', 'usr_phn4_fnbr', 'usr_phn4_cnfm_ind', 'usr_phn4_cnfm_dt', 'usr_main_phn_nbr', 'usr_city_nm', 'usr_url_txt', 'usr_cdt', 'lang_cd', 'ctry_cd', 'city_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'usr_pwd_hash'
    ];

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }

}
