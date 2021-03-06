<?php

class UiHelper extends Nano_Helper {

	public function invoke() {
		return $this;
	}

	public function blockStart($title, $image = null, $titleClass = null, $contentClass = null) {
		return
			'<div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">'
				. '<div class="portlet-header ui-widget-header ui-corner-top' . ($titleClass ? ' ' . $titleClass : '') . '">'
					. '<span class="ui-icon ui-icon-triangle-1-n"></span>'
					. ($image ? '<img src="/resources/images/icons/' . $image . '" width="16" height="16" alt="' . $title .'" title="' . $title .'" />' : '')
					. $title
				. '</div>'
				. '<div class="portlet-content' . ($contentClass ? ' ' . $contentClass : '') . '">'
		;
	}

	public function blockEnd() {
		return '</div></div>';
	}

	public function message($type, $text) {
		return '<p class="info ' . $type . '"><span class="info_inner">' . $text . '</span></p>';
	}

	public function inputField($type, $name, $title, $value = null, $css = null, $description = null) {
		return
			'<p>'
				. '<label for="' . $name . '">' . $title . '</label>'
				. '<input ' . ($css ? 'class="' . $css . '"' : '') . ' type="' . $type . '" name="' . $name . '" id="' . $name . '" value="' . htmlSpecialChars($value) . '" />'
				. ($description ? '<small>' . $description . '</small>' : '')
			. '</p>'
		;
	}

	public function textField($name, $title, $value = null, $css = null, $description = null) {
		return $this->inputField('text', $name, $title, $value, $css, $description);
	}

	public function fileField($name, $title, $css = null, $description = null) {
		return $this->inputField('file', $name, $title, null, $css, $description);
	}

	public function textareaField($name, $title, $value = null, $css = null, $description = null) {
		return
			'<p>'
				. '<label for="' . $name . '">' . $title . '</label>'
				. '<textarea ' . ($css ? 'class="' . $css . '"' : '') . ' name="' . $name . '" id="' . $name . '" style="height: 75px">'
					. htmlSpecialChars($value)
				. '</textarea>'
				. ($description ? '<small>' . $description . '</small>' : '')
			. '</p>'
		;
	}

	public function selectField($name, $title, array $options, $selected = null, $css = null, $description = null) {
		$result = '<p>'
			. '<label for="' . $name . '">' . $title . '</label>'
			. '<select ' . ($css ? 'class="' . $css . '"' : '') . ' name="' . $name . '" id="' . $name . '">'
		;
		foreach ($options as $value => $title) {
			$result .= '<option value="' . $value . ($value == $selected ? ' selected="selected"' : '') . '">' . $title . '</option>';
		}
		$result .= '</select>' . ($description ? '<small>' . $description . '</small>' : '') . '</p>';
		return $result;
	}

	public function radioField($name, $title, array $options, $selected = null, $css = null, $description = null) {
		$result = '<p>';
		foreach ($options as $value => $title) {
			$id = $name . '-' . $value;
			$result .= '<label for="' . $id . '">'
					. '<input class="check" type="radio" name="' . $name . '" id="' . $id . '" value="' . $value . '"' . ($selected ? ' checked="checked"' : '') . ' />'
				 	. '&nbsp;' . $title
				 . '</label>'
			;
		}
		$result .= '</p>';
		return $result;
	}

	public function boolField($name, $title, $checked = null) {
		return
			'<p>'
				. '<label for="' . $name . '">'
					. '<input class="check" type="checkbox" name="' . $name . '" id="' . $name . '" value="1"' . ($checked ? ' checked="checked"' : '') . ' />'
				 	. '&nbsp;' . $title
				 . '</label>'
			. '</p>'
		;
	}

	public function submit($title) {
		return '<p class="ui-helper-clearfix"><a class="button floatRight submit" href="#"><span>' . $title . '</span></a></p>';
	}

}