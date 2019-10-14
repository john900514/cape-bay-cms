<?php

namespace App\Traits;

use App\Traits\Concerns\HasEnhancedRoles;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Silber\Bouncer\Database\Concerns\HasAbilities;

trait HasEnhancedRolesAndAbilities
{
    use HasEnhancedRoles, HasAbilities {
        HasEnhancedRoles::getClipboardInstance insteadof HasAbilities;
    }
}
