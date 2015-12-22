<?php

namespace cyneek\yii2\routes\components;

use yii\base\ActionFilter;

class RoutesFilter extends ActionFilter
{

    public $rule;

    public $type;

    public function beforeAction($action)
    {
        if (!($this->type == 'before'))
        {
            return TRUE;
        }

        if (is_callable($this->rule))
        {
            $return_data = call_user_func($this->rule);

            if (!is_bool($return_data))
            {
                return FALSE;
            }

            return TRUE;
        }

        return FALSE;
    }

    public function afterAction($action, $result)
    {
        if (!($this->type == 'after'))
        {
            return $result;
        }

        if (is_callable($this->rule))
        {
            $return_data = call_user_func($this->rule);

            if (!is_bool($return_data))
            {
                return FALSE;
            }

            return $result;
        }

        return FALSE;
    }


    /**
     * @inheritdoc
     * @param \yii\base\Action $action
     * @return bool
     */
    protected function isActive($action)
    {
        if ($this->owner instanceof Module)
        {
            // convert action uniqueId into an ID relative to the module
            $mid = $this->owner->getUniqueId();
            $id = $action->getUniqueId();
            if ($mid !== '' && strpos($id, $mid) === 0)
            {
                $id = substr($id, strlen($mid) + 1);
            }

            $id = $action->controller->getUniqueId() . '/' . $id;
        }
        else
        {
            $id = $action->controller->getUniqueId() . '/' . $action->id;
        }

        return !in_array($id, $this->except, TRUE) && (empty($this->only) || in_array($id, $this->only, TRUE));
    }
}