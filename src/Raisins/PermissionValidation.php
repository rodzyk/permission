<?php

namespace Raisins;

class PermissionValidation
{

    /**
     * Available Permission List
     *
     * @var array<Permission>
     */
    public $available;

    /**
     * Required Permission List
     *
     * @var array<Permission>
     */
    public $required;

    /**
     * Undocumented function
     *
     * @param array<Permission> $mergePermission
     * @return void
     */
    public function merge(array $mergePermission = [])
    {
        foreach ($this->available as $key => $item) {
            $name = $item->getName();
            $mergeIndex = $this->getIndexByName($name, $mergePermission);

            if ($mergeIndex >= 0) {
                // merge
                $this->available[$key] = $this->available[$key]->merge($mergePermission[$mergeIndex]);

                // delete merget item
                \array_splice($mergePermission, $mergeIndex, 1);
            }
        }

        // merge other permission
        $this->available = array_merge($this->available, $mergePermission);
    }

    /**
     * Undocumented function
     *
     * @param string $name
     * @param array<Permission> $list
     * @return void
     */
    public function getIndexByName(string $name, array $list)
    {
        for ($i = 0; $i < count($list); $i++) {
            if ($list[$i]->getName() == $name) return $i;
        }
        return -1;
    }

    /**
     * Validate permissions
     *
     * @return boolean
     */
    public function validate(): bool
    {
        $this->available = array_filter(
            $this->available,
            function (Permission $v) {
                return $v->getState() != -1;
            }
        );

        $res = array_udiff(
            $this->required,
            $this->available,
            function (Permission $a, Permission $r) {
                if ($a->getName() < $r->getName()) {
                    return -1;
                } elseif ($a->getName() > $r->getName()) {
                    return 1;
                } else {
                    return 0;
                }
            }
        );

        return \count($res) == 0;
    }
}
