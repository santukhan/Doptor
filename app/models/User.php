<?php
/*
=================================================
CMS Name  :  DOPTOR
CMS Version :  v1.2
Available at :  www.doptor.org
Copyright : Copyright (coffee) 2011 - 2014 Doptor. All rights reserved.
License : GNU/GPL, visit LICENSE.txt
Description :  Doptor is Opensource CMS.
===================================================
*/
class User extends Eloquent {

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     * @var array
     */
    protected $hidden = array('password');

    // Path in the public folder to upload image and its corresponding thumbnail
    public static $images_path = 'uploads/users/';

    public static function validator($input, $rules)
    {
        // Validate the inputs
        $validator = Validator::make($input, $rules);

        return $validator;
    }

    public static function validate_registration($input)
    {
        $rules = array(
            'username'              => 'alpha_spaces|required|min:4|unique:users,username',
            'email'                 => 'required|min:4|email|unique:users,email',
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
            'first_name'            => 'required|min:3',
            'last_name'             => 'required|min:3'
        );

        return User::validator($input, $rules);
    }

    public static function validate_change($input, $id)
    {
        $rules = array(
            'username'              => 'alpha_spaces|required|min:4|unique:users,username,'.$id,
            'email'                 => 'required|min:4|email|unique:users,email,'.$id,
            'password'              => 'min:6|confirmed|confirmed',
            'password_confirmation' => 'min:6',
            'first_name'            => 'required|min:3',
            'last_name'             => 'required|min:3'
        );

        return User::validator($input, $rules);
    }

    public static function validate_reset($input)
    {
        $rules = array(
                    'email' => 'required|email|min:4'
            );

        return User::validator($input, $rules);
    }

    /**
     * Get the user group that the user lies in
     * @return integer
     */
    public static function user_group($user)
    {
        $groups = $user->getGroups();

        return $groups[0]->id;
    }

    public static function status()
    {
        return array(
                1 => 'Active',
                0 => 'Inactive'
            );
    }

    public static function isBanned($id)
    {
        $throttle = Sentry::findThrottlerByUserId($id);

        if($throttle->isBanned()) {
            // User is Banned
            return true;
        } else {
            // User is not Banned
            return false;
        }
    }

}
