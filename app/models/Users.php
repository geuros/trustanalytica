<?php
use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Callback as CallbackValidator;
use Phalcon\Validation\Validator\StringLength as StringLengthValidation;
use Phalcon\Validation\Validator\Between as BetweenValidation;

class Users extends Model {

	/**
	 *
	 * @var integer
	 */
	public $id;

	/**
	 *
	 * @var string
	 */
	public $name;

	/**
	 *
	 * @var string
	 */
	public $surname;

	/**
	 *
	 * @var string
	 */
	public $age;

	/**
	 *
	 * @var string
	 */
	public $phone;

	/**
	 *
	 * @var string
	 */
	public $driver_licence;

	/**
	 *
	 * @var string
	 */
	public $address;

	/**
	 *
	 * @var string
	 */
	public $password;

	/**
	 * Validations and business logic
	 *
	 * @return boolean
	 */
	public function validation() {
		$validator = new Validation();

		// Validate required fields
		$validator->add(
			[
				'name',
				'surname',
				'age',
				'phone',
				'driver_licence',
				'password',
			],
			new PresenceOf( [
					'message' => 'Field is required.'
				]
			)
		);

		// Validate fields length
		$validator->add(
			[
				'name',
				'surname',
			],
			new StringLengthValidation(
				[
					'max' => 50,
					'min' => 2,
					'messageMaximum' => 'We don\'t like really long names',
					'messageMinimum' => 'We want more than just their initials',
					'includedMaximum' => true,
					'includedMinimum' => false,
				]
			)
		);

		// Validate address field length
		$validator->add(
			'address',
			new StringLengthValidation(
				[
					'max' => 1000,
					'messageMaximum' => 'You can write max 1000 chars in address field.',
					'includedMaximum' => true
				]
			)
		);

		// Validate age field
		$validator->add(
			'age',
			new BetweenValidation(
				[
					'minimum' => 1,
					'maximum' => 99,
					'message' => 'The age must be between 1 and 99',
				]
			)
		);

		// Validate Canadian phone number
		$validator->add(
			'phone',
			new CallbackValidator(
				[
					'message' => 'Please enter correct phone number.',
					'callback' => function ( $data ) {
						$value = $data->phone;

						// Ten digits, maybe  spaces and/or dashes and/or parentheses
						// Maybe a 1 or a 0..
						$reg = '/^[0-1]?[- ]?(\()?[2-9](0[0-9]|10|1[2-9]|[2-8]\d)(?(1)\))[- ]?[2-9](0[0-9]|10|1[2-9]|[2-9]\d)[- ]?\d{4}$/';

						// These special area codes allow "exchange" codes to end in 11
						$special = [
							800,
							822,
							833,
							844,
							855,
							866,
							877,
							880,
							881,
							882,
							883,
							884,
							885,
							886,
							887,
							888,
							889,
							900
						];

						$reg2 = '/^[0-1]?[- ]?(\()?(' . implode( '|', $special )
							. ')(?(1)\))[- ]?[2-9]\d{2}[- ]?\d{4}$/';

						return ( ( boolean ) preg_match( $reg, $value ) OR ( boolean ) preg_match( $reg2, $value ) );
					}
				]
			)
		);

		// Validate Canadian driver licence number
		$validator->add(
			'driver_licence',
			new CallbackValidator(
				[
					'message' => 'Please enter correct driver licence number.',
					'callback' => function ( $data ) {
						$value = $data->driver_licence;

						$regs = [
							// Alberta
							'/^[A-Za-z]{2}\d{4}$/',
							'/^[A-Za-z]{1}\d{5}$/',
							'/^\d{6}$/',
							'/^\d{5}-\d{3}$/',
							// British Columbia
							'/^\d{7}$/',
							// Manitoba
							'/^[A-Za-z]{7}\d{3}[A-Za-z0-9]{2}$/',
							// Newfoundland & Labrador
							'/^[A-Za-z]\d{2}(0?[1-9]|1[012])([1-9]|[12]\d|3[01])\d{3}$/',
							'/^[A-Za-z]{2}\d{8}$/',
							// New Brunswick (like British Columbia )
							// '/^\d{7}$/',
							// Northwest Territories (like Alberta )
							// '/^\d{6}$/',
							// Nova Scotia
							'/^[A-Za-z]{5}([1-9]|[12]\d|3[01])(0?[1-9]|1[012])\d{5}$/',
							// Nunavut (like Alberta )
							// '/^\d{6}$/',
							// Ontario
							'/^[A-Za-z]\d{4}-\d{5}-\d(0?[1-9]|1[012])([1-9]|[12]\d|3[01])$/',
							// Prince Edward Island
							'/^\d{4}([1-9]|[12]\d|3[01])(0?[1-9]|1[012])\d{4}$/',
							// Quebec
							'/^[A-Za-z]\d{4}([1-9]|[12]\d|3[01])(0?[1-9]|1[012])\d{4}$/',
							// Saskatchewan
							'/^\d{8}$/',
							// Yukon (like Alberta )
							// '/^\d{6}$/'
						];

						foreach ( $regs as $reg ) {
							$match = (boolean) preg_match( $reg, $value );
							if ( $match ) {
								return true;
							}
						}

						return false;
					}
				]
			)
		);

		return $this->validate( $validator );
	}
}
