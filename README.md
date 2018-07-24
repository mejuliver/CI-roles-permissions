# CI-roles-permissions

first, copy the isauth_helper.php and rolespermissions_helper.php to your app helpers folder (application/helpers)

and autoload the 'rolespermissions' and 'isauth' helper to autoload.php

```
$autoload['helper'] = array('url', 'form','isauth','rolespermissions');
```
I recommend to enable hooks from config.php to auto run the helper on app boot

```
$config['enable_hooks'] = FALSE;
```
and then copy the hooks.php (config/hooks.php)

import the sql file (roles_permissions.sql) to your database and tailor it to your needs

on user authentication, attach below as a session data

```
$this->session->userdata('auth',true);
$user_info = [ 'users_id' => $users_id ];
$this->session->userdata('user_info',$user_info);
```

to use it, to check if current user has a role, you can do (example, check if has role of admin)

```
if( inRole('admin') )
```

you can also specify a group of roles by an array. Return false if none from the given group of roles exist.

```
if( inRole([ 'admin', 'user' ]) )
```

to check for current user's permission(s), you can do

```
if ( canPerm('can edit') )
```

you can also specify an array just like the role

```
if( canPerm([ 'can edit', 'can post' ]) )
