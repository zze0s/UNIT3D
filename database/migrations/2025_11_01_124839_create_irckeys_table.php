<?php

declare(strict_types=1);

/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('irckey')->after('rsskey');
        });

        Schema::create('irckeys', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('content');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate();
        });

        DB::table('users')
            ->lazyById()
            ->each(function ($user): void {
                $irckey = md5(random_bytes(60).$user->password);

                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['irckey' => $irckey]);

                DB::table('irckeys')->insert([
                    'user_id'    => $user->id,
                    'content'    => $irckey,
                    'created_at' => now(),
                ]);
            });
    }
};
