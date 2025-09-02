<?php

use App\Http\Handlers\IdApplicationFormHandler;
use App\Http\Handlers\PerformanceEvaluationForms\FirstMonthPerformanceEvaluationFormHandler;
use App\Http\Handlers\PerformanceEvaluationForms\SixthMonthPerformanceEvaluationFormHandler;
use App\Http\Handlers\PerformanceEvaluationForms\ThirdMonthPerformanceEvaluationFormHandler;
use App\Http\Handlers\RequirementTransmittalFormHandler;
use App\Http\Requests\StoreFirstMonthPerformanceEvaluationForm;
use App\Http\Requests\StoreIdApplicationFormRequest;
use App\Http\Requests\StoreRequirementTransmittalFormRequest;
use App\Http\Requests\StoreSixthMonthPerformanceEvaluationForm;
use App\Http\Requests\StoreThirdMonthPerformanceEvaluationForm;
use App\Models\FirstMonthPerformanceEvaluationForm;
use App\Models\IdApplicationForm;
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
            'one_to_one' => 'requirementTransmittalForm', // A user can only be onboarded once.
            'notifications' => [
                'roles' => ['hr manager', 'hr specialist', 'operation manager'],
                'notify_employee' => true, // Also notify the new employee
            ],
        ],
        'first-month-performance-evaluation-form' => [
            'model' => FirstMonthPerformanceEvaluationForm::class,
            'request' => StoreFirstMonthPerformanceEvaluationForm::class,
            'handler' => FirstMonthPerformanceEvaluationFormHandler::class,
            'name' => 'First Month Performance Evaluation Form',
            'one_to_one' => 'firstMonthPerformanceEvaluation',
            'notifications' => [
                'special_logic' => 'performance_evaluation', // Use a dedicated handler for complex logic
            ],
        ],
        'third-month-performance-evaluation-form' => [
            'model' => ThirdMonthPerformanceEvaluationForm::class,
            'request' => StoreThirdMonthPerformanceEvaluationForm::class,
            'handler' => ThirdMonthPerformanceEvaluationFormHandler::class,
            'name' => 'Third Month Performance Evaluation Form',
            'one_to_one' => 'thirdMonthPerformanceEvaluation',
            'notifications' => [
                'special_logic' => 'performance_evaluation',
            ],
        ],
        'sixth-month-performance-evaluation-form' => [
            'model' => SixthMonthPerformanceEvaluationForm::class,
            'request' => StoreSixthMonthPerformanceEvaluationForm::class,
            'handler' => SixthMonthPerformanceEvaluationFormHandler::class,
            'name' => 'Sixth Month Performance Evaluation Form',
            'one_to_one' => 'sixthMonthPerformanceEvaluation',
            'notifications' => [
                'special_logic' => 'performance_evaluation',
            ],
        ],
        'id-application-form' => [
            'model' => IdApplicationForm::class,
            'request' => StoreIdApplicationFormRequest::class,
            'handler' => IdApplicationFormHandler::class,
            'name' => 'ID Application Form',
            // This form can be submitted multiple times, so no 'one_to_one' key.
            'notifications' => [
                'roles' => ['hr manager', 'hr specialist'],
            ],
        ],
        // Add any new form types here
    ],
];
