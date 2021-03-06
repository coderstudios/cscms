<?php
/**
 * Part of the CSCMS package by Coder Studios.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the terms of the MIT license https://opensource.org/licenses/MIT
 *
 * @package    CSCMS
 * @version    1.0.0
 * @author     Coder Studios Ltd
 * @license    MIT https://opensource.org/licenses/MIT
 * @copyright  (c) 2017, Coder Studios Ltd
 * @link       https://www.coderstudios.com
 */

namespace CoderStudios\CSCMS\Requests;

use CoderStudios\CSCMS\Requests;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends Request {

	/**
	 * Determine if the user is authorised to make this request.
	 *
	 * @return boolean
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		if (empty($this->request->get('password'))) {
			$this->request->remove('password');
			$this->request->remove('password_confirmation');
		}
		$rules = [
			'name' => 'required|max:255',
			'username' => [
				Rule::unique('users')->ignore($this->request->get('id')),
			],
			'email' => [
				'required',
				'max:255',
				Rule::unique('users')->ignore($this->request->get('id')),
			],
			'password' => 'nullable|min:6|confirmed',

		];

		return $rules;
	}

	/**
	 * Override the default error messages.
	 *
	 * @return array
	 */
	public function messages()
	{
		return [

		];
	}
}