<?php

declare(strict_types=1);

namespace Light\View;

use Throwable;

use Light\View\Exception\ViewTemplateWasNotFound;

class View
{
  /**
   * @var string
   */
  protected string $workspace = '';

  /**
   * @var array
   */
  protected array $vars = [];

  /**
   * @var bool
   */
  protected bool $minify = false;

  /**
   * @param string $key
   * @param mixed $value
   */
  public function addVar(string $key, mixed $value): void
  {
    $this->vars[$key] = $value;
  }

  /**
   * @return array
   */
  public function getVars(): array
  {
    return $this->vars;
  }

  /**
   * @param array $vars
   */
  public function setVars(array $vars): void
  {
    $this->vars = $vars;
  }

  /**
   * @param string $name
   * @return mixed|null
   */
  public function __get(string $name)
  {
    return $this->vars[$name] ?? null;
  }

  /**
   * @param string $name
   * @param $value
   */
  public function __set(string $name, $value)
  {
    $this->vars[$name] = $value;
  }

  /**
   * @param string $workspace
   */
  public function __construct(string $workspace = null)
  {
    if ($workspace) {
      $this->workspace = $workspace;
    }
  }

  /**
   * @param bool $minify
   */
  public function setMinify(bool $minify): void
  {
    $this->minify = $minify;
  }

  /**
   * @param string $template
   * @param array $vars
   *
   * @return string
   * @throws Throwable
   * @throws ViewTemplateWasNotFound
   */
  public function render(string $template, array $vars = []): string
  {
    foreach ($vars as $key => $val) {
      $this->addVar($key, $val);
    }

    ob_start();
    try {
      if (substr($template, -6) != '.phtml') {
        $template .= '.phtml';
      }
      if (!file_exists($template)) {
        $template = implode('/', array_filter([
          $this->workspace,
          $template
        ]));
      }
      if (!file_exists($template)) {
        throw new ViewTemplateWasNotFound($template);
      }
      include $template;
    } catch (Throwable $e) {
      $exception = $e;
    }

    $content = ob_get_contents();
    ob_end_clean();

    if (isset($exception)) {
      throw $exception;
    }

    if ($this->minify) {
      $content = preg_replace(
        ['/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', '/<!--(.|\s)*?-->/'],
        ['>', '<', '\\1', ''],
        $content
      );
    }

    return $content;
  }
}
