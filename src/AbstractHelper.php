<?php

declare(strict_types=1);

namespace Light\View;

abstract class AbstractHelper
{
  /**
   * @var View
   */
  public View $view;

  /**
   * @param View $view
   */
  public function __construct(View $view)
  {
    $this->view = $view;
  }

  /**
   * @param mixed ...$args
   * @return string
   */
  abstract public function call(mixed...$args): string;
}