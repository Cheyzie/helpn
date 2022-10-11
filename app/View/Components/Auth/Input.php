<?php

namespace App\View\Components\Auth;

use Illuminate\Support\ViewErrorBag;
use Illuminate\View\Component;

class Input extends Component
{
    public string $name;

    public string $type;

    public ?string $errorMessage;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(ViewErrorBag $errors, string $name, string $type)
    {
        $this->name = $name;
        $this->type = $type;
        $this->errorMessage = $errors->getBag('default')->first($name);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.auth.input');
    }
}
