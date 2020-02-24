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
		
		echo '<input' . $attrs . '/>';
	}
	
	public static function submit($inputData)
	{
		$attrs = self::stringifyAttrs($inputData);
		
		echo '<input type="submit"' . $attrs . '/>';
	}
	
	public static function inputBlock($inputData, $blockData)
	{
		$blockText = '';
		
		if (!empty($blockData['text']))
		{
			$blockText = $blockData['text'];
			
			unset($blockData['text']);
		}
		
		$inputAttrs = self::stringifyAttrs($inputData);
		$blockAttrs = self::stringifyAttrs($blockData);
		
		$html = '<div' . $blockAttrs . '>';
		
		if (!empty($inputData['id']))
		{
			$html .= '<label for="' . $inputData['id'] . '">' . $blockText . '</label>';
			$html .= '<input' . $inputAttrs . '/>';
		}
		else 
		{
			$html .= '<label>' . $blockText;
			$html .= '<input' . $inputAttrs . '/>';
			$html .= '</label>';
		}
		
		$html .= '</div>';
		
		echo $html;
	}

	public static function errors($errors)
    {
        if (count($errors))
        {
            echo '<div class="p-3 mb-3">';

            foreach ($errors as $key => $value)
            {
                echo '<div class="text-danger">' . $value . '</div>';
            }

            echo '</div>';
        }
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
}