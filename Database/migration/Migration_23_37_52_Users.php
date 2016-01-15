<?php

namespace migration;

use Ilum\Console\Schema\Schema;
use Ilum\Console\Schema\Type;

class Migration_23_37_52_Users 
{
    public function up()
    {
        Schema::up('users', function(Type $type){
            $type->engine = 'MyISAM';
            /*
                default value
            $type->charset = 'utf8';
            $type->collate = 'utf8_unicode_ci';
            */
            $type->id()->autoincrement()
                ->string('name', 60)
                ->unique('email', 60)
                ->string('password')
                ->timeStamp('created_at')
                ->timestamp('update_at', 'NULL')
                ->save();
        });
    }
    
    public function down()
    {
        Schema::down('users');
    }

    public function truncate()
    {
        Schema::truncate('users');
    }
}