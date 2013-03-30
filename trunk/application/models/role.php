<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 2/6/13
 * Time: 5:36 PM
 * To change this template use File | Settings | File Templates.
 */
class Role extends Eloquent
{
    const USER_ROLE_ADMIN = 'admin';
    const USER_ROLE_SUPER_ADMIN = 'superadmin';
    const USER_ROLE_EDITOR = 'editor';
    const USER_ROLE_AUTHENTICATED = 'authenticated';

    public function users()
    {
        $this->has_many_and_belongs_to('User');
    }

}
