<?php

namespace App\View\Components\icons;

use App\Models\Cabang;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Users extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.icons.users');
    }

    // Model User
    public function cabangs()
    {
        return $this->belongsToMany(Cabang::class, 'user_cabangs');
    }

}
