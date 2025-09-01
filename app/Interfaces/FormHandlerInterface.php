<?php

namespace App\Interfaces;

use Illuminate\Contracts\View\View;

interface FormHandlerInterface
{
    /**
     * Get the data needed for the create form view.
     */
    public function create(): View;

    /**
     * Get the data needed to view a specific form submission.
     *
     * @param int $id The ID of the form model.
     * @return View
     */
    public function view(int $id): View;

    /**
     * Get the data needed for the print view of a form.
     *
     * @param int $id The ID of the form model.
     * @return View
     */
    public function print(int $id): View;
}
