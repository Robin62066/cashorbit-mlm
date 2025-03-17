<?php
class Permission
{
    private $data = [];
    private $actions = [];
    const USERS = 'users';
    const PROPERTIES = 'sites';
    const BHUMI_LOCKER = 'bhumi-locker';
    const PAYMENTS = 'payments';
    const MUTATION_SERVICES = 'mutations';
    const MEDIA = 'media';
    const REPORTS = 'reports';
    const SETTINGS = 'settings';
    const JOBS = 'jobs';

    function __construct($str, $module = null)
    {
        $this->data = json_decode($str);
        if ($module != null) {
            $this->setModule($module);
        }
    }

    function setModule($module)
    {
        $actions = [];
        foreach ($this->data as $item) {
            if ($item->module == $module) {
                $actions = $item->actions;
            }
        }
        $this->setActions($actions);
        return $this;
    }

    private function setActions($actions)
    {
        $arr = [];
        foreach ($actions as $item) {
            $arr[$item->action] = $item->is_selected;
        }
        $this->actions = $arr;
        return $this;
    }

    function canEdit()
    {
        if (admin_id() == 1) return true;
        return $this->actions['edit'] ?? false;
    }

    function canDelete()
    {
        if (admin_id() == 1) return true;
        return $this->actions['delete'] ?? false;
    }

    function canCreateNew()
    {
        if (admin_id() == 1) return true;
        return $this->actions['add'] ?? false;
    }

    function canView()
    {
        if (admin_id() == 1) return true;
        return $this->actions['view'] ?? false;
    }
}
