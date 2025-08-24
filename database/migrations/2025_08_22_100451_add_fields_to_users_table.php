```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('fathers_name')->nullable()->after('email');
            $table->string('fathers_email')->nullable()->after('fathers_name');
            $table->string('fathers_contact_number')->nullable()->after('fathers_email');
            $table->string('mothers_name')->nullable()->after('fathers_contact_number');
            $table->string('mothers_email')->nullable()->after('mothers_name');
            $table->string('mothers_contact_number')->nullable()->after('mothers_email');
            $table->date('birthdate')->nullable()->after('address');
            $table->string('nationality')->nullable()->after('birthdate');
            $table->string('nid_number')->nullable()->after('nationality');
            $table->string('religion')->nullable()->after('nid_number');
            $table->enum('sex', ['male', 'female', 'other'])->nullable()->after('religion');
            $table->string('user_image')->nullable()->after('sex');
            $table->string('nid_image')->nullable()->after('user_image');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'fathers_name',
                'fathers_email',
                'fathers_contact_number',
                'mothers_name',
                'mothers_email',
                'mothers_contact_number',
                'birthdate',
                'nationality',
                'nid_number',
                'religion',
                'sex',
                'user_image',
                'nid_image'
            ]);
        });
    }
}
