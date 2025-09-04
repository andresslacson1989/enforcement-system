<?php

return [
    'requirement-transmittal-form' => [
        [
            'name' => 'application_form_and_recent_picture',
            'label' => 'Application Form with 2 pcs. 2x2 recent picture (white background)',
            'has_expiration' => false,
        ],
        [
            'name' => 'security_license',
            'label' => 'Security License',
            'has_expiration' => true,
            'sub_options' => ['sbr'],
        ],
        [
            'name' => 'nbi_clearance',
            'label' => 'NBI Clearance',
            'has_expiration' => true,
        ],
        [
            'name' => 'national_police_clearance',
            'label' => 'National Police Clearance',
            'has_expiration' => true,
        ],
        [
            'name' => 'police_clearance',
            'label' => 'Police Clearance',
            'has_expiration' => true,
        ],
        [
            'name' => 'court_clearance',
            'label' => 'Court Clearance',
            'has_expiration' => true,
        ],
        [
            'name' => 'barangay_clearance',
            'label' => 'Barangay Clearance',
            'has_expiration' => true,
        ],
        [
            'name' => 'neuro_psychiatric_test',
            'label' => 'Neuro Psychiatric Test',
            'has_expiration' => true,
        ],
        [
            'name' => 'drug_test',
            'label' => 'Drug Test',
            'has_expiration' => true,
        ],
        [
            'name' => 'diploma',
            'label' => 'Diploma',
            'has_expiration' => false,
            'sub_options' => ['diploma_hs' => 'High School', 'diploma_college' => 'College'],
        ],
        [
            'name' => 'transcript', 'label' => 'Transcript of Records',
            'has_expiration' => false,
        ],
        [
            'name' => 'psa_birth', 'label' => 'PSA Birth Certificate',
            'has_expiration' => false,
        ],
        [
            'name' => 'psa_marriage', 'label' => 'PSA Marriage Certificate',
            'has_expiration' => false,
        ],
        [
            'name' => 'psa_birth_dependents',
            'label' => 'PSA Birth Certificates of Dependents (Children)',
            'has_expiration' => false,
        ],
        [
            'name' => 'certificate_of_employment',
            'label' => 'Certificate of Employment (Previous Security Agency)',
            'has_expiration' => false,
        ],
        [
            'name' => 'community_tax_certificate',
            'label' => 'Community Tax Certificate',
            'has_expiration' => false],
        [
            'name' => 'tax_id_number',
            'label' => 'Tax Identification Number',
            'has_expiration' => false,
        ],
        [
            'name' => 'e1_form',
            'label' => 'E1 Form SSS',
            'has_expiration' => false,
        ],
        [
            'name' => 'sss_id',
            'label' => 'SSS',
            'has_expiration' => false,
            'sub_options' => [
                'sss_id' => 'SSS ID',
                'sss_slip' => 'SSS Slip',
            ],
        ],
        [
            'name' => 'philhealth_id',
            'label' => 'PhilHealth ID',
            'has_expiration' => true,
            'sub_options' => [
                'philhealth_mdr' => 'MDR',
            ],
        ],
        [
            'name' => 'pagibig_id',
            'label' => 'Pag-IBIG ID',
            'has_expiration' => true,
            'sub_options' => [
                'pagibig_mdf' => 'MDF',
            ],
        ],
        [
            'name' => 'statement_of_account',
            'label' => 'Statement of Account (Previous Loan)',
            'has_expiration' => false,
        ],
    ],
];
