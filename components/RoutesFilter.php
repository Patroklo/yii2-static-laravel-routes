<?php

namespace cyneek\yii2\routes\components;

use yii\base\ActionFilter;

class RoutesFilter extends ActionFilter
{

    public $rule;

    public $type;

    public function beforeAction($action)
    {
        if (!($this->type == 'before')) {
            return TRUE;
        }

        if (is_callable($this->rule)) {
            $return_data = call_user_func($this->rule);

            if (!is_bool($return_data)) {
                return FALSE;
            }
            return TRUE;
        }
        return FALSE;
    }

    public function afterAction($action, $result)
    {
        if (!($this->type == 'after')) {
            return $result;
        }

        if (is_callable($this->rule)) {
            $return_data = call_user_func($this->rule);

            if (!is_bool($return_data)) {
                return FALSE;
            }
            return $result;
        }
        return FALSE;
    }

}

