<?php

namespace migration;

use Ilum\Console\Schema\Schema;
use Ilum\Console\Schema\Type;

class Migration_23_35_39_Token 
{
    public function up()
    {
        Schema::up('token', function(Type $type){
            $type->engine = 'MyISAM';
            $type->id()->autoincrement()
                ->integers('user_id')
                ->string('token')
                ->save();
        });
    }
    
    public function down()
    {
        Schema::down('token');
    }

    public function truncate()
    {
        Schema::truncate('token');
    }
}