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
	
	public static function inputBlock($inputData, $blockData)
	{
		$blockText = self::pop($blockData['text']);
		$blockAttrs = self::stringifyAttrs($blockData);
		
		$html = '<div' . $blockAttrs . '>';
		
		if (!empty($inputData['id']))
		{
			$html .= '<label for="' . $inputData['id'] . '">' . $blockText . '</label>';
			$html .= self::input($inputData);
		}
		else 
		{
			$html .= '<label>' . $blockText;
            $html .= self::input($inputData);
			$html .= '</label>';
		}
		
		$html .= '</div>';
		
		return $html;
	}

	public static function textareaBlock($inputData, $blockData)
	{
		$blockText = self::pop($blockData['text']);
		$blockAttrs = self::stringifyAttrs($blockData);

		$html = '<div' . $blockAttrs . '>';

		if (!empty($inputData['id']))
		{
			$html .= '<label for="' . $inputData['id'] . '">' . $blockText . '</label>';
            $html .= self::textarea($inputData);
		}
		else
		{
			$html .= '<label>' . $blockText;
            $html .= self::textarea($inputData);
			$html .= '</label>';
		}

		$html .= '</div>';

		return $html;
	}

	public static function errors($errors)
    {
        $result = '';

        if (count($errors))
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
        $result = '';

		if (!empty($data))
        {
            $result = $data;

            unset($data);
        }

		return $result;
    }
}