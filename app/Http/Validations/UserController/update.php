<?php

return [
    'email'=>'email',
    'password'=>'min:6',
    'first_name'=>'min:2',
    'last_name'=>'min:2',
    'role'=>'in:'.implode(',', get_class_constants_value(\App\Constants\UserRoleConstant::class)),
    'status'=>'boolean',
];
