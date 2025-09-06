<?php

use App\Http\Handlers\IdApplicationFormHandler;
use App\Http\Handlers\PerformanceEvaluationForms\FirstMonthPerformanceEvaluationFormHandler;
use App\Http\Handlers\PerformanceEvaluationForms\SixthMonthPerformanceEvaluationFormHandler;
use App\Http\Handlers\PerformanceEvaluationForms\ThirdMonthPerformanceEvaluationFormHandler;
use App\Http\Handlers\PersonnelRequisitionFormHandler;
use App\Http\Handlers\RequirementTransmittalFormHandler;
use App\Http\Requests\StoreFirstMonthPerformanceEvaluationForm;
use App\Http\Requests\StoreIdApplicationFormRequest;
use App\Http\Requests\StorePersonnelRequisitionFormRequest;
use App\Http\Requests\StoreRequirementTransmittalFormRequest;
use App\Http\Requests\StoreSixthMonthPerformanceEvaluationForm;
use App\Http\Requests\StoreThirdMonthPerformanceEvaluationForm;
use App\Models\FirstMonthPerformanceEvaluationForm;
use App\Models\IdApplicationForm;
use App\Models\PersonnelRequisitionForm;
use App\Models\RequirementTransmittalForm;
use App\Models\SixthMonthPerformanceEvaluationForm;
use App\Models\ThirdMonthPerformanceEvaluationForm;

return [
    'types' => [
        // The "slug" is the key
        'requirement-transmittal-form' => [
            'model' => RequirementTransmittalForm::class,
            'request' => StoreRequirementTransmittalFormRequest::class,
            'handler' => RequirementTransmittalFormHandler::class,
            'name' => 'Requirement Transmittal Form',
            'one_to_one' => true, // A user can only be onboarded once.
            'notifications' => [
                'roles' => ['hr manager', 'hr specialist', 'operation manager'],
                'notify_employee' => true,
            ],
        ],
        'first-month-performance-evaluation-form' => [
            'model' => FirstMonthPerformanceEvaluationForm::class,
            'request' => StoreFirstMonthPerformanceEvaluationForm::class,
            'handler' => FirstMonthPerformanceEvaluationFormHandler::class,
            'name' => 'First Month Performance Evaluation Form',
            'one_to_one' => true,
            'notifications' => [
                'special_logic' => 'performance_evaluation',
                'notify_employee' => true,
            ],
        ],
        'third-month-performance-evaluation-form' => [
            'model' => ThirdMonthPerformanceEvaluationForm::class,
            'request' => StoreThirdMonthPerformanceEvaluationForm::class,
            'handler' => ThirdMonthPerformanceEvaluationFormHandler::class,
            'name' => 'Third Month Performance Evaluation Form',
            'one_to_one' => true,
            'notifications' => [
                'special_logic' => 'performance_evaluation',
                'notify_employee' => true,
            ],
        ],
        'sixth-month-performance-evaluation-form' => [
            'model' => SixthMonthPerformanceEvaluationForm::class,
            'request' => StoreSixthMonthPerformanceEvaluationForm::class,
            'handler' => SixthMonthPerformanceEvaluationFormHandler::class,
            'name' => 'Sixth Month Performance Evaluation Form',
            'one_to_one' => true,
            'notifications' => [
                'special_logic' => 'performance_evaluation',
                'notify_employee' => true,
            ],
        ],
        'id-application-form' => [
            'model' => IdApplicationForm::class,
            'request' => StoreIdApplicationFormRequest::class,
            'handler' => IdApplicationFormHandler::class,
            'name' => 'ID Application Form',
            'one_to_one' => false, // A user can have multiple ID applications.
            'notifications' => [
                'roles' => ['hr manager', 'hr specialist'],
                'notify_employee' => false,
            ],
        ],
        'personnel-requisition-form' => [
            'model' => PersonnelRequisitionForm::class,
            'request' => StorePersonnelRequisitionFormRequest::class,
            'handler' => PersonnelRequisitionFormHandler::class,
            'name' => 'Personnel Requisition Form',
            'one_to_one' => false, // A user can have multiple ID applications.
            'notifications' => [
                'roles' => ['hr manager', 'hr specialist'],
                'notify_employee' => false,
            ],
        ],
        'personnel-leave-application-form' => [
            'name' => 'Personnel Leave Application Form',
            'model' => \App\Models\PersonnelLeaveApplicationForm::class,
            'request' => \App\Http\Requests\StorePersonnelLeaveApplicationFormRequest::class,
            // We will create this handler next
            'handler' => \App\Http\Handlers\PersonnelLeaveApplicationFormHandler::class,
            'one_to_one' => false, // A user can have multiple leave applications
            'notifications' => [
                'roles' => ['hr-manager', 'operation-manager'], // Notify HR and Operations by default
                'notify_employee' => false,
            ],
        ],
        // Add any new form types here
    ],
];
