<?php

class jsonStatuses
{
    public function boolToStatus($bool)
    {
        if ($bool) {
            return json_encode('{"status": success }');
        } else {
            return json_encode('{"status": failed }');
        }
    }

    public function sessionNotExists()
    {
       return json_encode('{"error": session_not_exists}');
    }

    public function sessionAlreadyOpen()
    {
        return json_encode('{"error": session_already_open}');
    }

    public function inputParametersInvalid()
    {
        return json_encode('{"error": input_parameters_invalid}');
    }

    public function categoryNotExists()
    {
        return json_encode('{"error": category_not_exists}');
    }

    public function userNotMatch()
    {
        return json_encode('{"error": user_not_match_delete_user}');
    }
}