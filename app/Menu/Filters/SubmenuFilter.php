<?php

namespace App\Menu\Filters;

use JeroenNoten\LaravelAdminLte\Menu\Builder;
use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;
use Illuminate\Contracts\Auth\Access\Gate;

class SubmenuFilter implements FilterInterface
{
    protected $gate;

    public function __construct(Gate $gate)
    {
        $this->gate = $gate;
    }

    public function transform($item, Builder $builder)
    {
        if (isset($item['cangroup'])) {
            foreach ($item['cangroup'] as $can) {
                if (! $this->gate->allows($can)) {
                    return false;
                }
            }
        }

        return $item;
    }

    protected function isVisible($item)
    {
        return ! isset($item['cangroup']);
    }
}
