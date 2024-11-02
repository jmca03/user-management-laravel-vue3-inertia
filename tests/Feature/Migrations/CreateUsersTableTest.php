<?php

namespace Tests\Feature\Migrations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CreateUsersTableTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function it_has_users_table()
    {
        // Run the migration
        $this->artisan('migrate');

        // Assert that the users table exists
        $this->assertTrue(Schema::hasTable('users'));
    }

    /**
     * @test
     *
     * @return void
     */
    public function it_has_users_table_expected_columns()
    {
        // Run the migration
        $this->artisan('migrate');

        // Assert that the users table has the expected columns
        $this->assertTrue(Schema::hasColumn('users', 'id'));
        $this->assertTrue(Schema::hasColumn('users', 'prefixname'));
        $this->assertTrue(Schema::hasColumn('users', 'firstname'));
        $this->assertTrue(Schema::hasColumn('users', 'middlename'));
        $this->assertTrue(Schema::hasColumn('users', 'lastname'));
        $this->assertTrue(Schema::hasColumn('users', 'suffixname'));
        $this->assertTrue(Schema::hasColumn('users', 'username'));
        $this->assertTrue(Schema::hasColumn('users', 'email'));
        $this->assertTrue(Schema::hasColumn('users', 'password'));
        $this->assertTrue(Schema::hasColumn('users', 'photo'));
        $this->assertTrue(Schema::hasColumn('users', 'type'));
        $this->assertTrue(Schema::hasColumn('users', 'email_verified_at'));
        $this->assertTrue(Schema::hasColumn('users', 'created_at'));
        $this->assertTrue(Schema::hasColumn('users', 'updated_at'));
        $this->assertTrue(Schema::hasColumn('users', 'deleted_at'));
    }

    /**
     * @test
     *
     * @return void
     */
    public function it_has_users_table_constraints()
    {
        // Run the migration
        $this->artisan('migrate');

        // Check for unique constraints
        $this->assertTrue(Schema::hasTable('users'));

        // Check that the username column has a unique index
        $indexes = collect(Schema::getConnection()->getSchemaBuilder()->getIndexes('users'))->where('unique', true)->pluck('name')->toArray();

        $this->assertContains('users_username_unique', $indexes); // Adjust the index name as needed
        $this->assertContains('users_email_unique', $indexes); // Adjust the index name as needed
    }

    /**
     * @test
     *
     * @return void
     */
    public function it_has_users_expected_indexing()
    {
        // Run the migration
        $this->artisan('migrate');

        $indexes = array_column(Schema::getConnection()->getSchemaBuilder()->getIndexes('users'), 'name');

        // Assert that the index on the 'type' column exists
        $this->assertContains('users_type_index', $indexes);
    }
}
