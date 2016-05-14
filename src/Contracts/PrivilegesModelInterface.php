<?php

namespace MatthC\Privileges\Contracts;

/**
 * Interface PrivilegesModelInterface
 *
 * @package MatthC\Privileges\Contracts
 * @author Matthieu Calie <matthieu.calie@gmail.com>
 */
interface PrivilegesModelInterface
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany;
     */
    public function users();
}
