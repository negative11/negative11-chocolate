<?php
/**
 * General-purpose controller provides basic functionality to be extended by
 * controller.
 */
namespace controller;

abstract class Core {

	public function __isset($arg) {

	}

	/**
	 * Whether this Controller should be loaded.
	 * This can be overridden in extending classes to enable/disable
	 * specific Controllers.
	 */
	const ENABLED = TRUE;

	/**
	 * Whether output should be drawn automatically on destroy.
	 */
	private $autoDraw = TRUE;

	/**
	 * The template to be drawn
	 */
	protected $template;

	/**
	 * Initialize local template into View.
	 */
	protected function initTemplate() {
		if (!empty($this->template)) {
			$this->template = new \component\View($this->template);
		}
	}

	/**
	 * Enable auto draw, which will automatically display the appropriate template
	 * on destruct.
	 *
	 */
	protected function enableAutoDraw() {
		$this->autoDraw = TRUE;
	}

	/**
	 * Disable auto draw.
	 *
	 */
	protected function disableAutoDraw() {
		$this->autoDraw = FALSE;
	}

	/**
	 * Set local template.
	 *
	 * @param type $template
	 */
	protected function setTemplate($template) {
		$this->template = $template;
		$this->initTemplate();
	}

	/**
	 * Draws output.
	 * Any page content ready to be output is done via this method.
	 */
	protected function draw() {
		if (\Core::$draw) {
			// Has View been created yet?
			if (is_scalar($this->template)) {
				$this->initTemplate();
			}

			if ($this->template instanceof \component\View) {
				$this->template->display();
			}
		}
	}

	/**
	 * Destructor
	 * Finalize any actions before destroying object
	 */
	public function __destruct() {
		if ($this->autoDraw === TRUE) {
			try
			{
				$this->draw();
			} catch (\Exception $e) {
				/**
				 * We must invoke the error handler manually, because by this point the Core has
				 * already destructed, so the proper error handler is not set.
				 */
				\FrameworkError::handler($e);
			}
		}
	}
}
