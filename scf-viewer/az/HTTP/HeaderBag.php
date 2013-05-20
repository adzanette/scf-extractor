<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AZ\Framework;

/**
 * HeaderBag is a container for HTTP headers.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @api
 */
class HeaderBag implements \IteratorAggregate, \Countable{

  const COOKIES_FLAT           = 'flat';
  const COOKIES_ARRAY          = 'array';
  const DISPOSITION_ATTACHMENT = 'attachment';
  const DISPOSITION_INLINE     = 'inline';

  protected $computedCacheControl = array();
  protected $cookies              = array();
  protected $headerNames          = array();
  protected $headers;
  protected $cacheControl;

  public function __construct(array $headers = array()){
    $this->cacheControl = array();
    $this->headers = array();
    foreach ($headers as $key => $values) {
      $this->set($key, $values);
    }

    if (!isset($this->headers['cache-control'])) {
      $this->set('Cache-Control', '');
    }
  }

  public function __toString(){
    $cookies = '';
    foreach ($this->getCookies() as $cookie) {
      $cookies .= 'Set-Cookie: '.$cookie."\r\n";
    }

    ksort($this->headerNames);

    if (!$this->headers) {
      return '';
    }

    $max = max(array_map('strlen', array_keys($this->headers))) + 1;
    $content = '';
    ksort($this->headers);
    foreach ($this->headers as $name => $values) {
      $name = implode('-', array_map('ucfirst', explode('-', $name)));
      foreach ($values as $value) {
        $content .= sprintf("%-{$max}s %s\r\n", $name.':', $value);
      }
    }

    return $content.$cookies;
  }

  public function all(){
    return $this->headers;
  }

  public function allPreserveCase(){
    return array_combine($this->headerNames, $this->headers);
  }

  public function keys(){
    return array_keys($this->headers);
  }

  public function replace(array $headers = array()){
    $this->headerNames = array();

    $this->headers = array();
    $this->add($headers);

    if (!isset($this->headers['cache-control'])) {
      $this->set('Cache-Control', '');
    }
  }

  public function add(array $headers){
    foreach ($headers as $key => $values) {
      $this->set($key, $values);
    }
  }

  public function get($key, $default = null, $first = true){
    $key = strtr(strtolower($key), '_', '-');

    if (!array_key_exists($key, $this->headers)) {
      if (null === $default) {
        return $first ? null : array();
      }

      return $first ? $default : array($default);
    }

    if ($first) {
      return count($this->headers[$key]) ? $this->headers[$key][0] : $default;
    }

    return $this->headers[$key];
  }

  public function set($key, $values, $replace = true){
    $key = strtr(strtolower($key), '_', '-');

    $values = array_values((array) $values);

    if (true === $replace || !isset($this->headers[$key])) {
      $this->headers[$key] = $values;
    } else {
      $this->headers[$key] = array_merge($this->headers[$key], $values);
    }

    if ('cache-control' === $key) {
      $this->cacheControl = $this->parseCacheControl($values[0]);
    }

    $uniqueKey = strtr(strtolower($key), '_', '-');
    $this->headerNames[$uniqueKey] = $key;

    if (in_array($uniqueKey, array('cache-control', 'etag', 'last-modified', 'expires'))) {
      $computed = $this->computeCacheControlValue();
      $this->headers['cache-control'] = array($computed);
      $this->headerNames['cache-control'] = 'Cache-Control';
      $this->computedCacheControl = $this->parseCacheControl($computed);
    }
  }

  public function has($key){
    return array_key_exists(strtr(strtolower($key), '_', '-'), $this->headers);
  }

  public function contains($key, $value){
    return in_array($value, $this->get($key, null, false));
  }

  public function remove($key){
    $key = strtr(strtolower($key), '_', '-');

    unset($this->headers[$key]);

    if ('cache-control' === $key) {
      $this->cacheControl = array();
    }

    $uniqueKey = strtr(strtolower($key), '_', '-');
    unset($this->headerNames[$uniqueKey]);

    if ('cache-control' === $uniqueKey) {
      $this->computedCacheControl = array();
    }
  }

  public function getDate($key, \DateTime $default = null){
    if (null === $value = $this->get($key)) {
      return $default;
    }

    if (false === $date = \DateTime::createFromFormat(DATE_RFC2822, $value)) {
      throw new \RuntimeException(sprintf('The %s HTTP header is not parseable (%s).', $key, $value));
    }

    return $date;
  }

  public function addCacheControlDirective($key, $value = true){
    $this->cacheControl[$key] = $value;

    $this->set('Cache-Control', $this->getCacheControlHeader());
  }

  public function hasCacheControlDirective($key){
    return array_key_exists($key, $this->computedCacheControl);
  }

  public function getCacheControlDirective($key){
    return array_key_exists($key, $this->computedCacheControl) ? $this->computedCacheControl[$key] : null;
  }

  public function removeCacheControlDirective($key){
    unset($this->cacheControl[$key]);
    $this->set('Cache-Control', $this->getCacheControlHeader());
  }

  public function setCookie(Cookie $cookie){
    $this->cookies[$cookie->getDomain()][$cookie->getPath()][$cookie->getName()] = $cookie;
  }

  protected function computeCacheControlValue(){
    if (!$this->cacheControl && !$this->has('ETag') && !$this->has('Last-Modified') && !$this->has('Expires')) {
      return 'no-cache';
    }

    if (!$this->cacheControl) {
      // conservative by default
      return 'private, must-revalidate';
    }

    $header = $this->getCacheControlHeader();
    if (isset($this->cacheControl['public']) || isset($this->cacheControl['private'])) {
      return $header;
    }

    if (!isset($this->cacheControl['s-maxage'])) {
      return $header.', private';
    }

    return $header;
  }


  public function removeCookie($name, $path = '/', $domain = null){
    if (null === $path) {
      $path = '/';
    }

    unset($this->cookies[$domain][$path][$name]);

    if (empty($this->cookies[$domain][$path])) {
      unset($this->cookies[$domain][$path]);

      if (empty($this->cookies[$domain])) {
        unset($this->cookies[$domain]);
      }
    }
  }

  public function getCookies($format = self::COOKIES_FLAT){
    if (!in_array($format, array(self::COOKIES_FLAT, self::COOKIES_ARRAY))) {
      throw new \InvalidArgumentException(sprintf('Format "%s" invalid (%s).', $format, implode(', ', array(self::COOKIES_FLAT, self::COOKIES_ARRAY))));
    }

    if (self::COOKIES_ARRAY === $format) {
      return $this->cookies;
    }

    $flattenedCookies = array();
    foreach ($this->cookies as $path) {
      foreach ($path as $cookies) {
        foreach ($cookies as $cookie) {
          $flattenedCookies[] = $cookie;
        }
      }
    }

    return $flattenedCookies;
  }

  public function clearCookie($name, $path = '/', $domain = null){
    $this->setCookie(new Cookie($name, null, 1, $path, $domain));
  }


  public function getIterator(){
    return new \ArrayIterator($this->headers);
  }

  public function count(){
    return count($this->headers);
  }

  protected function getCacheControlHeader(){
    $parts = array();
    ksort($this->cacheControl);
    foreach ($this->cacheControl as $key => $value) {
      if (true === $value) {
        $parts[] = $key;
      } else {
        if (preg_match('#[^a-zA-Z0-9._-]#', $value)) {
          $value = '"'.$value.'"';
        }

        $parts[] = "$key=$value";
      }
    }

    return implode(', ', $parts);
  }

  protected function parseCacheControl($header){
    $cacheControl = array();
    preg_match_all('#([a-zA-Z][a-zA-Z_-]*)\s*(?:=(?:"([^"]*)"|([^ \t",;]*)))?#', $header, $matches, PREG_SET_ORDER);
    foreach ($matches as $match) {
      $cacheControl[strtolower($match[1])] = isset($match[3]) ? $match[3] : (isset($match[2]) ? $match[2] : true);
    }

    return $cacheControl;
  }
}
