<?php

namespace Yajra\Acl\Directives;

class RoleDirective extends DirectiveAbstract
{
    /**
     * Is Role blade directive compiler.
     *
     * @param  string|array  $role
     */
    public function handle($role): bool
    {
        if ($this->auth->check()) {
            // @phpstan-ignore-next-line
            return $this->auth->user()->hasRole($role);
        }

        return $role === 'guest';
    }
}
