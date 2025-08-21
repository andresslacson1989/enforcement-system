<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->string('employee_number')->unique()->default('123');
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('telegram_chat_id')->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('status')->default('hired');
            /* Employment Status:
                Hired: Currently employed and deployed.
                Re-hired: Previously employed, now re-employed.
                Floating: Employed but unassigned, seeking placement.
                On Leave: Currently on an approved leave of absence.
                Resigned: Filed and processed resignation.
                Preventive Suspension: Temporarily suspended from work pending investigation of a case.

            Security Personnel Hierarchy
            This outlines the chain of command for security personnel based on the number of guards assigned.
            SO = Security Officer License
            SG = Security Guard License
            Structure 1: Large Detachment (61+ Guards)
            1. Detachment Commander (SO): Overall in charge.
            2. Assistant Detachment Commander (ADC) / Security-In-Charge (SIC) (SO): Second in command, assists the Detachment Commander.
            3. Cluster Head Guard (SG): Supervises the security guards within a specific cluster or unit.
            4. Security Guards/Lady Guards (SG): The main security force.

            Structure 2: Medium Detachment (11-60 Guards)
            1. Officer-In-Charge (OIC) (SO): Overall in charge in the absence of a Detachment Commander.
            2. Security-In-Charge (SIC) (SO): Second in command, assists the OIC/Security Officer.
            3. Cluster Head Guard (HG) (SG): Supervises the security guards within a specific unit/cluster.
            4. Security Guards/Lady Guards (SG): The main security force.

            Structure 3: Small Team (1-6 Guards)
            1. Head Guard (SG): In charge of the small team.
            2. Assistant Head Guard (SG): Assists the Head Guard.
            3. Security Guards/Lady Guards (SG): Security personnel.

            Structure 4: Single Post (1-2 Guards)
            1. Operations Manager: Overall management.
            2. Security Guard: Single security personnel on duty.
             */

            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
