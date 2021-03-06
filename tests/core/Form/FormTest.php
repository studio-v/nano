<?php

/**
 * @group nano
 */
class Form_FormTest extends TestUtils_TestCase {

	public function testPopulate() {
		$form = new Nano_Form(array('f1', 'f2', 'f3'));

		$form->populate(array('f1' => 'v1', 'f2' => 'v2', 'f3' => 'v3'));
		self::assertEquals(3, count($form->getValues()));
		self::assertArrayHasKey('f1', $form->getValues());
		self::assertArrayHasKey('f2', $form->getValues());
		self::assertArrayHasKey('f2', $form->getValues());
		$values = $form->getValues();
		self::assertEquals('v1', $values['f1']);
		self::assertEquals('v2', $values['f2']);
		self::assertEquals('v3', $values['f3']);

		$form->populate(array('f1' => array('', '   ', array(), array('		')), 'f2' => '', 'f3' => '     '));
		self::assertEquals(0, count($form->getValues()));
	}

	public function testFieldsAccess() {
		$form = new Nano_Form(array('f1', 'f2', 'f3'));
		$form->populate(array('f1' => 'v1'));

		self::assertTrue(isset($form->f1));
		self::assertEquals('v1', $form->f1);
		$form->f1 = 10;
		self::assertEquals('10', $form->f1);
	}

	public function testValidateNoValidators() {
		$form = new Nano_Form(array('f1', 'f2', 'f3'));
		$form->populate(array('f1' => 'v1'));
		self::assertTrue($form->isValid());
	}

	public function testValidate() {
		$form = new Nano_Form(array('f1', 'f2', 'f3'));
		$form->addValidator('f1', new Nano_Validator_True());
		$form->populate(array('f1' => 'v1'));
		self::assertTrue($form->isValid());

		$form->addValidator('f2', new Nano_Validator_True());
		self::assertTrue($form->isValid());

		$form->addValidator('f3', new Nano_Validator_False());
		self::assertFalse($form->isValid());
	}

	public function testValidateMessages() {
		$form = new Nano_Form(array('f1', 'f2', 'f3'));
		$form->addValidator('f1', new Nano_Validator_True());
		$form->populate(array('f1' => 'v1'));

		$form->validate();
		self::assertEquals(array(), $form->getErrors());
		self::assertNull($form->getFieldErros('f1'));
		self::assertNull($form->getFieldErros('f2'));
		self::assertNull($form->getFieldErros('f3'));

		$form->addValidator('f2', new Nano_Validator_True());
		$form->validate();
		self::assertEquals(array(), $form->getErrors());
		self::assertNull($form->getFieldErros('f1'));
		self::assertNull($form->getFieldErros('f2'));
		self::assertNull($form->getFieldErros('f3'));

		$form->addValidator('f3', new Nano_Validator_False(), 'message 1');
		$form->addValidator('f3', new Nano_Validator_True(),  'message 2');
		$form->addValidator('f3', new Nano_Validator_False(), 'message 3');
		$form->validate();
		self::assertNotEquals(array(), $form->getErrors());
		self::assertArrayNotHasKey('f1', $form->getErrors());
		self::assertArrayNotHasKey('f2', $form->getErrors());
		self::assertArrayHasKey('f3', $form->getErrors());
		self::assertNull($form->getFieldErros('f1'));
		self::assertNull($form->getFieldErros('f2'));
		self::assertNotNull($form->getFieldErros('f3'));
		self::assertEquals(array('message 1'), $form->getFieldErros('f3'));

		$form->setMode(Nano_Form::MODE_VALIDATE_ALL);
		$form->validate();
		self::assertNotEquals(array(), $form->getErrors());
		self::assertArrayNotHasKey('f1', $form->getErrors());
		self::assertArrayNotHasKey('f2', $form->getErrors());
		self::assertArrayHasKey('f3', $form->getErrors());
		self::assertNull($form->getFieldErros('f1'));
		self::assertNull($form->getFieldErros('f2'));
		self::assertNotNull($form->getFieldErros('f3'));
		self::assertEquals(array('message 1', 'message 3'), $form->getFieldErros('f3'));
	}

}