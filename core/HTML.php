<?php


class HTML
{
	public function tag($data)
	{
		self::getAttrsString($data);
	}
	
	public static function input($inputData)
	{
		$attrs = self::stringifyAttrs($inputData);
		
		return '<input' . $attrs . '/>';
	}

	public static function textarea($inputData)
    {
        $text = self::pop($inputData['text']);
        $attrs = self::stringifyAttrs($inputData);

        return '<textarea' . $attrs . '>' . $text . '</textarea>';
    }
	
	public static function submit($inputData)
	{
		$attrs = self::stringifyAttrs($inputData);
		
		return '<input type="submit"' . $attrs . '/>';
	}

	public static function select($inputData)
    {
        $options = self::pop($inputData['options']);
        unset($inputData['options']);

        $data = self::pop($inputData['data']);
        unset($inputData['data']);

        $attrs = self::stringifyAttrs($inputData);

        $html = '<select' . $attrs . '>';

        foreach ($data as $params)
        {
            $html .= '<option value="' . $params->{$options['value']} . '">' . $params->{$options['text']} . '</option>';
        }

        $html .= '</select>';

        return $html;
    }

    public static function block($type, $inputData, $blockData)
    {
        $blockText = self::pop($blockData['text']);
        $blockAttrs = self::stringifyAttrs($blockData);

        $html = '<div' . $blockAttrs . '>';

        if (!empty($inputData['id']))
        {
            $html .= '<label for="' . $inputData['id'] . '">' . $blockText . '</label>';
            $html .= self::{$type}($inputData);
        }
        else
        {
            $html .= '<label>' . $blockText;
            $html .= self::{$type}($inputData);
            $html .= '</label>';
        }

        $html .= '</div>';

        return $html;
    }
	
	public static function inputBlock($inputData, $blockData)
	{
        return self::block('input', $inputData, $blockData);
	}

	public static function textareaBlock($inputData, $blockData)
	{
        return self::block('textarea', $inputData, $blockData);
	}

	public static function submitBlock($inputData, $blockData)
    {
        return self::block('submit', $inputData, $blockData);
    }

	public static function selectBlock($inputData, $blockData)
    {
        return self::block('select', $inputData, $blockData);
    }

	public static function errors($errors)
    {
        $result = '';

        if ($errors && count($errors))
        {
            $result .= '<div class="p-3 mb-3">';

            foreach ($errors as $key => $value)
            {
                $result .= '<div class="text-danger">' . $value . '</div>';
            }

            $result .= '</div>';
        }

        return $result;
    }
	
	private static function stringifyAttrs($data)
	{
		$result = '';
		
		foreach ($data as $key => $value)
		{
			$result .= ' ' . $key . '="' . $value . '"';
		}
		
		return $result;
	}

	private static function pop(&$data)
    {
        $result = null;

		if (!empty($data))
        {
            $result = $data;

            unset($data);
        }

		return $result;
    }
}