<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use TechlifyInc\LaravelRbac\Models\Permission;

/**
 * Setup a table that specifies which computers are allowed to access the application
 * 
 * This works in sync with evercookie in the frontend to restrict access
 */
class CreateAllowedComputersTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laravel_rbac_allowed_computers', function(Blueprint $table) {
            $table->increments('id');
            $table->string('token')->unique();
            $table->integer('creator_id')->unsigned();
            $table->timestamps();
        });

        /* Lets add the permissions to handle this */
        $this->removePermissions();

        $models = [
            ['slug' => 'laravel_rbac_allowed_computer_bypass', 'label' => "Computer Restricted Access: Bypass this Access Control"],
            ['slug' => 'laravel_rbac_allowed_computer_add', 'label' => "Computer Restricted Access: Allow Computers"],
            ['slug' => 'laravel_rbac_allowed_computer_remove', 'label' => "Computer Restricted Access: Block Computers"]
        ];

        $model = new Permission;
        $table = $model->getTable();
        DB::table($table)->insert($models);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laravel_rbac_allowed_computers');

        $this->removePermissions();
    }

    private function removePermissions()
    {
        $slugs = [
            "laravel_rbac_allowed_computer_bypass",
            "laravel_rbac_allowed_computer_add",
            "laravel_rbac_allowed_computer_remove",
        ];

        $model = new Permission;
        $table = $model->getTable();
        DB::table($table)
            ->whereIn("slug", $slugs)
            ->delete();
    }
}
