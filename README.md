# CI-roles-permissions

first, copy the isAuth_helper.php and RolesPermissions_helper.php to your app helpers folder (application/helpers)

and autoload the 'rolespermissions' and 'isauth' helper to autoload.php

```
$autoload['helper'] = array('url', 'form','isAuth','RolesPermissions');
```

enable hooks to auto run the helper on app boot

```
$config['enable_hooks'] = FALSE;
```

I recommend to enable hooks from config.php to auto run the helper on app boot

```
$config['enable_hooks'] = FALSE;
```
and then copy the hooks.php (config/hooks.php)

import the sql file (roles_permissions.sql) to your database and tailor it to your needs

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
