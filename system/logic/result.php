<?php
/**
 * Logic result that accompanies Logic.
 */

namespace logic;

class Result extends \ArrayObject
{
	const SUCCESS = 1;
	const ERROR = 2;
	
	private $status;
	private $errorMessages = array();
	private $successMessages = array();
	
	/**
	 * Constructor. 
	 */
	public function __construct()
	{
		$this->initStatus();
	}
	
	/**
	 * Set initial local status. 
	 */
	private function initStatus()
	{
		$this->isSuccess();
	}
	
	/**
	 * Return local status.
	 * 
	 * @return type 
	 */
	public function getStatus()
	{
		return $this->status;
	}
	
	/**
	 * Return whether local status reflects "success". 
	 * 
	 * @return type 
	 */
	public function isSuccess()
	{
		return $this->status === self::SUCCESS;
	}
	
	/**
	 * Return whether local status reflects "error".
	 * 
	 * @return type 
	 */
	public function isError()
	{
		return $this->status === self::ERROR;
	}
	
	/**
	 * Set local status to "success".
	 *  
	 */
	public function setSuccessStatus()
	{
		$this->status = self::SUCCESS;
	}
	
	/**
	 * Set local status to "error". 
	 */
	public function setErrorStatus()
	{
		$this->status = self::ERROR;
	}
	
	/**
	 * Set "success" message.
	 * 
	 * @param type $message 
	 */
	public function setSuccessMessage($message)
	{
		$this->successMessages[] = $message;
	}
	
	/**
	 * Set multiple "success" messages.
	 * 
	 * @param array $messages 
	 */
	public function setSuccessMessages(array $messages)
	{
		$this->successMessages += $messages;
	}
	
	/**
	 * Fetch and return "success" messages. 
	 * 
	 * @return array 
	 */
	public function getSuccessMessages()
	{
		return $this->successMessages;
	}
	
	/**
	 * Set "error" message. 
	 * 
	 * @param type $message 
	 */
	public function setErrorMessage($message)
	{
		$this->errorMessages[] = $message;
	}
	
	/**
	 * Set multiple "error" messages.
	 * 
	 * @param array $messages 
	 */
	public function setErrorMessages(array $messages)
	{
		$this->errorMessages += $messages;
	}
	
	/**
	 * Fetch and return "error" messages. 
	 * 
	 * @return array 
	 */
	public function getErrorMessages()
	{
		return $this->errorMessages;
	}	
}