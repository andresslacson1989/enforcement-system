<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property int $id
 * @property string $name
 * @property int $submitted_by
 * @property int|null $detachment_id
 * @property bool $complete_requirements
 * @property bool $qualified_for_loan
 * @property string $status
 * @property int $printed
 * @property int $times_printed
 * @property string|null $date_last_printed
 * @property int|null $last_printed_by
 * @property int|null $employee_id
 * @property string|null $employee_number
 * @property string|null $deployment
 * @property bool $original_application_form_and_recent_picture
 * @property bool $photocopy_application_form_and_recent_picture
 * @property bool $sbr
 * @property bool $original_security_license
 * @property bool $photocopy_security_license
 * @property bool $original_nbi_clearance
 * @property bool $photocopy_nbi_clearance
 * @property bool $original_national_police_clearance
 * @property bool $photocopy_national_police_clearance
 * @property bool $original_police_clearance
 * @property bool $photocopy_police_clearance
 * @property bool $original_court_clearance
 * @property bool $photocopy_court_clearance
 * @property bool $original_barangay_clearance
 * @property bool $photocopy_barangay_clearance
 * @property bool $original_neuro_psychiatric_test
 * @property bool $photocopy_neuro_psychiatric_test
 * @property bool $original_drug_test
 * @property bool $photocopy_drug_test
 * @property bool $diploma_hs
 * @property bool $diploma_college
 * @property bool $original_diploma
 * @property bool $photocopy_diploma
 * @property bool $original_transcript
 * @property bool $photocopy_transcript
 * @property bool $original_training_certificate
 * @property bool $photocopy_training_certificate
 * @property bool $original_psa_birth
 * @property bool $photocopy_psa_birth
 * @property bool $original_psa_marriage
 * @property bool $photocopy_psa_marriage
 * @property bool $original_psa_birth_dependents
 * @property bool $photocopy_psa_birth_dependents
 * @property bool $original_certificate_of_employment
 * @property bool $photocopy_certificate_of_employment
 * @property bool $original_community_tax_certificate
 * @property bool $photocopy_community_tax_certificate
 * @property bool $original_tax_id_number
 * @property bool $photocopy_tax_id_number
 * @property bool $original_e1_form
 * @property bool $photocopy_e1_form
 * @property bool $sss_id
 * @property bool $sss_slip
 * @property bool $original_sss_id
 * @property bool $photocopy_sss_id
 * @property bool $philhealth_mdr
 * @property bool $original_philhealth_id
 * @property bool $photocopy_philhealth_id
 * @property bool $pagibig_mdf
 * @property bool $original_pagibig_id
 * @property bool $photocopy_pagibig_id
 * @property bool $original_statement_of_account
 * @property bool $photocopy_statement_of_account
 * @property \Illuminate\Support\Carbon|null $exp_security_license
 * @property \Illuminate\Support\Carbon|null $exp_nbi_clearance
 * @property \Illuminate\Support\Carbon|null $exp_national_police_clearance
 * @property \Illuminate\Support\Carbon|null $exp_police_clearance
 * @property \Illuminate\Support\Carbon|null $exp_court_clearance
 * @property \Illuminate\Support\Carbon|null $exp_barangay_clearance
 * @property \Illuminate\Support\Carbon|null $exp_neuro_psychiatric_test
 * @property \Illuminate\Support\Carbon|null $exp_drug_test
 * @property \Illuminate\Support\Carbon|null $exp_training_certificate
 * @property \Illuminate\Support\Carbon|null $exp_philhealth_id
 * @property \Illuminate\Support\Carbon|null $exp_pagibig_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Detachment|null $detachment
 * @property-read \App\Models\Submission|null $submission
 * @property-read \App\Models\User $submittedBy
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereCompleteRequirements($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereDateLastPrinted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereDeployment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereDetachmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereDiplomaCollege($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereDiplomaHs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereEmployeeNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereExpBarangayClearance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereExpCourtClearance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereExpDrugTest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereExpNationalPoliceClearance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereExpNbiClearance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereExpNeuroPsychiatricTest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereExpPagibigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereExpPhilhealthId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereExpPoliceClearance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereExpSecurityLicense($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereExpTrainingCertificate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereLastPrintedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereOriginalApplicationFormAndRecentPicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereOriginalBarangayClearance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereOriginalCertificateOfEmployment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereOriginalCommunityTaxCertificate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereOriginalCourtClearance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereOriginalDiploma($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereOriginalDrugTest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereOriginalE1Form($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereOriginalNationalPoliceClearance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereOriginalNbiClearance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereOriginalNeuroPsychiatricTest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereOriginalPagibigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereOriginalPhilhealthId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereOriginalPoliceClearance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereOriginalPsaBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereOriginalPsaBirthDependents($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereOriginalPsaMarriage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereOriginalSecurityLicense($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereOriginalSssId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereOriginalStatementOfAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereOriginalTaxIdNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereOriginalTrainingCertificate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereOriginalTranscript($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm wherePagibigMdf($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm wherePhilhealthMdr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm wherePhotocopyApplicationFormAndRecentPicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm wherePhotocopyBarangayClearance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm wherePhotocopyCertificateOfEmployment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm wherePhotocopyCommunityTaxCertificate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm wherePhotocopyCourtClearance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm wherePhotocopyDiploma($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm wherePhotocopyDrugTest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm wherePhotocopyE1Form($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm wherePhotocopyNationalPoliceClearance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm wherePhotocopyNbiClearance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm wherePhotocopyNeuroPsychiatricTest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm wherePhotocopyPagibigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm wherePhotocopyPhilhealthId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm wherePhotocopyPoliceClearance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm wherePhotocopyPsaBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm wherePhotocopyPsaBirthDependents($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm wherePhotocopyPsaMarriage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm wherePhotocopySecurityLicense($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm wherePhotocopySssId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm wherePhotocopyStatementOfAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm wherePhotocopyTaxIdNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm wherePhotocopyTrainingCertificate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm wherePhotocopyTranscript($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm wherePrinted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereQualifiedForLoan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereSbr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereSssId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereSssSlip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereSubmittedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereTimesPrinted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequirementTransmittalForm whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RequirementTransmittalForm extends Model
{
    protected $fillable = [
        'submitted_by',
        'employee_id',
        'employee_number',
        'deployment',
        'original_application_form_and_recent_picture',
        'photocopy_application_form_and_recent_picture',
        'sbr',
        'original_security_license',
        'photocopy_security_license',
        'original_nbi_clearance',
        'photocopy_nbi_clearance',
        'original_national_police_clearance',
        'photocopy_national_police_clearance',
        'original_police_clearance',
        'photocopy_police_clearance',
        'original_court_clearance',
        'photocopy_court_clearance',
        'original_barangay_clearance',
        'photocopy_barangay_clearance',
        'original_neuro_psychiatric_test',
        'photocopy_neuro_psychiatric_test',
        'original_drug_test',
        'photocopy_drug_test',
        'diploma_hs',
        'diploma_college',
        'original_diploma',
        'photocopy_diploma',
        'original_transcript',
        'photocopy_transcript',
        'original_training_certificate',
        'photocopy_training_certificate',
        'original_psa_birth',
        'photocopy_psa_birth',
        'original_psa_marriage',
        'photocopy_psa_marriage',
        'original_psa_birth_dependents',
        'photocopy_psa_birth_dependents',
        'original_certificate_of_employment',
        'photocopy_certificate_of_employment',
        'original_community_tax_certificate',
        'photocopy_community_tax_certificate',
        'original_tax_id_number',
        'photocopy_tax_id_number',
        'original_e1_form',
        'photocopy_e1_form',
        'sss_id',
        'sss_slip',
        'original_sss_id',
        'photocopy_sss_id',
        'philhealth_mdr',
        'original_philhealth_id',
        'photocopy_philhealth_id',
        'pagibig_mdf',
        'original_pagibig_id',
        'photocopy_pagibig_id',
        'original_statement_of_account',
        'photocopy_statement_of_account',
        'exp_security_license',
        'exp_nbi_clearance',
        'exp_national_police_clearance',
        'exp_police_clearance',
        'exp_court_clearance',
        'exp_barangay_clearance',
        'exp_neuro_psychiatric_test',
        'exp_drug_test',
        'exp_training_certificate',
        'exp_philhealth_id',
        'exp_pagibig_id',
        'complete_requirements',
        'qualified_for_loan',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // Cast all checkbox fields to boolean for data integrity
        'original_application_form_and_recent_picture' => 'boolean',
        'photocopy_application_form_and_recent_picture' => 'boolean',
        'sbr' => 'boolean',
        'original_security_license' => 'boolean',
        'photocopy_security_license' => 'boolean',
        'original_nbi_clearance' => 'boolean',
        'photocopy_nbi_clearance' => 'boolean',
        'original_national_police_clearance' => 'boolean',
        'photocopy_national_police_clearance' => 'boolean',
        'original_police_clearance' => 'boolean',
        'photocopy_police_clearance' => 'boolean',
        'original_court_clearance' => 'boolean',
        'photocopy_court_clearance' => 'boolean',
        'original_barangay_clearance' => 'boolean',
        'photocopy_barangay_clearance' => 'boolean',
        'original_neuro_psychiatric_test' => 'boolean',
        'photocopy_neuro_psychiatric_test' => 'boolean',
        'original_drug_test' => 'boolean',
        'photocopy_drug_test' => 'boolean',
        'diploma_hs' => 'boolean',
        'diploma_college' => 'boolean',
        'original_diploma' => 'boolean',
        'photocopy_diploma' => 'boolean',
        'original_transcript' => 'boolean',
        'photocopy_transcript' => 'boolean',
        'original_training_certificate' => 'boolean',
        'photocopy_training_certificate' => 'boolean',
        'original_psa_birth' => 'boolean',
        'photocopy_psa_birth' => 'boolean',
        'original_psa_marriage' => 'boolean',
        'photocopy_psa_marriage' => 'boolean',
        'original_psa_birth_dependents' => 'boolean',
        'photocopy_psa_birth_dependents' => 'boolean',
        'original_certificate_of_employment' => 'boolean',
        'photocopy_certificate_of_employment' => 'boolean',
        'original_community_tax_certificate' => 'boolean',
        'photocopy_community_tax_certificate' => 'boolean',
        'original_tax_id_number' => 'boolean',
        'photocopy_tax_id_number' => 'boolean',
        'original_e1_form' => 'boolean',
        'photocopy_e1_form' => 'boolean',
        'sss_id' => 'boolean',
        'sss_slip' => 'boolean',
        'original_sss_id' => 'boolean',
        'photocopy_sss_id' => 'boolean',
        'philhealth_mdr' => 'boolean',
        'original_philhealth_id' => 'boolean',
        'photocopy_philhealth_id' => 'boolean',
        'pagibig_mdf' => 'boolean',
        'original_pagibig_id' => 'boolean',
        'photocopy_pagibig_id' => 'boolean',
        'original_statement_of_account' => 'boolean',
        'photocopy_statement_of_account' => 'boolean',
        'complete_requirements' => 'boolean',
        'qualified_for_loan' => 'boolean',

        // Cast all expiration date fields to dates for easier handling
        'exp_security_license' => 'date',
        'exp_nbi_clearance' => 'date',
        'exp_national_police_clearance' => 'date',
        'exp_police_clearance' => 'date',
        'exp_court_clearance' => 'date',
        'exp_barangay_clearance' => 'date',
        'exp_neuro_psychiatric_test' => 'date',
        'exp_drug_test' => 'date',
        'exp_training_certificate' => 'date',
        'exp_philhealth_id' => 'date',
        'exp_pagibig_id' => 'date',
    ];

    public function submission(): MorphOne
    {
        // This tells Laravel that this form is "submittable"
        return $this->morphOne(Submission::class, 'submittable');
    }

    /**
     * Get the user that owns the form.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    /**
     * Get the user that owns the form.
     */
    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    /**
     * Get the detachment that this form belongs to.
     */
    public function detachment(): BelongsTo
    {
        // This defines that a form belongs to one Detachment.
        // We specify 'deployment' as the foreign key on this model's table.
        return $this->belongsTo(Detachment::class, 'detachment_id', 'id');
    }
}
