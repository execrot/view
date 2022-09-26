<?php

declare(strict_types=1);

namespace Light\View\Exception;

use Exception;

class ViewTemplateWasNotFound extends Exception
{
  /**
   * ViewTemplateWasNotFound constructor.
   * @param string $template
   */
  public function __construct(string $template)
  {
    parent::__construct('ViewTemplateWasNotFound: ' . $template);
  }
}
