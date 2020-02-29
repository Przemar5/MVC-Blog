<?php


class TagsModel extends Model
{
    public $id, $name;

    public function __construct()
    {
        parent::__construct('tags');
    }
	
	public function prepareForDisplay()
	{
		return '<span class="badge badge-secondary">' . $this->name . '</span>';
	}
}
