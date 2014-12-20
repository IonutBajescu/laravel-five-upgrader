<?php

class TestController
{
	/**
	 * This method try a new test in database
	 * Be prudent with this method.
	 *
	 * @Post("tests/make", as="tests.make")
	 */
	public function make()
	{
	}
	/**
	 * @param 1
	 * @param 2
	 *
	 * @Get("tests/find/{id}")
	 */
	public function find($id)
	{
		// what the fuck
	}
	/**
	 * Enjoy this!
	 *
	 * @Post("tests/delete", as="tests.delete")
	 */
	public function delete()
	{
	}
}