<?php
namespace app\index\controller;
use app\common\controller\Note;

class Index
{
    public function index()
    {
        echo cShuffleStr();
    }

    public function hello($name)
    {
        return 'hello'.$name;
    }
}
