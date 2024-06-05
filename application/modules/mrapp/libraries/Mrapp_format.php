<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mrapp_format {

    public function threshold_format_ukuran($type=null,$nilai=null,$satuan=null,$min=null,$max=null)
    {
        $result = "";
        if(isset($type))
        {
            switch ($type)
            {
                case "less" :
                    $result = "< ".$nilai." ".$satuan;
                    break;
                case "lessequal" :
                    $result = "<= ".$nilai." ".$satuan;
                    break;
                case "equal" :
                    $result = "= ".$nilai." ".$satuan;
                    break;
                case "greaterequal" :
                    $result = ">= ".$nilai." ".$satuan;
                    break;
                case "greater" :
                    $result = "> ".$nilai." ".$satuan;
                    break;
            }
        }
        return $result;
    }
}