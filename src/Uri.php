<?php declare(strict_types=1);

namespace Tale;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;

/**
 * {@inheritdoc}
 */
final class Uri implements UriInterface
{
    /**
     * The scheme this URI contains
     *
     * e.g. {{https}}://example.com
     *
     * @var string
     */
    private $scheme;

    /**
     * The user this URI is associated with
     *
     * e.g. {{user}}@example.com
     *
     * @var string
     */
    private $user;

    /**
     * The password this URI is associated with
     *
     * e.g. user:{{password}}@example.com
     *
     * @var string
     */
    private $password;

    /**
     * The host this URI points to
     *
     * e.g. http://user@{{example.com}}/test
     *
     * @var string
     */
    private $host;

    /**
     * The port this URI points to
     *
     * e.g. http://example.com:{{8080}}/test
     *
     * @var int|null
     */
    private $port;

    /**
     * The path this URI points to
     *
     * e.g. http://example.com{{/some/sub/path}}
     *
     * @var string
     */
    private $path;

    /**
     * The query string this URI contains
     *
     * e.g. http://example.com/test?{{var1=val1&var2=val2}}
     *
     * @var string
     */
    private $query;

    /**
     * The fragment the URI contains
     *
     * e.g. http://example.com/test#{{someFragment}}
     *
     * @var string
     */
    private $fragment;

    /**
     * A cache for the fully generated URI string
     *
     * @var string|null
     */
    private $uriString;


    /**
     * @param string $scheme
     * @param string $host
     * @param int|null $port
     * @param string $path
     * @param string $query
     * @param string $fragment
     * @param string $user
     * @param string $password
     */
    public function __construct(
        string $scheme = '',
        string $user = '',
        string $password = '',
        string $host = '',
        ?int $port = null,
        string $path = '',
        string $query = '',
        string $fragment = ''
    ) {
    
        $this->scheme = $this->filterScheme($scheme);
        $this->user = $this->filterUser($user);
        $this->password = $this->user === '' ? '' : $this->filterPassword($password);
        $this->host = $this->filterHost($host);
        $this->port = $this->filterPort($port);
        $this->path = $this->filterPath($path);
        $this->query = $this->filterQuery($query);
        $this->fragment = $this->filterFragment($fragment);
    }

    /**
     * {@inheritdoc}
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * {@inheritdoc}
     *
     * @return $this
     */
    public function withScheme($scheme): self
    {
        $uri = clone $this;
        $uri->scheme = $this->filterScheme($scheme);
        return $uri;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthority(): string
    {
        if ($this->host === '') {
            return '';
        }

        $authority = '';
        $userInfo = $this->getUserInfo();
        if ($userInfo !== '') {
            $authority .= "{$userInfo}@";
        }

        $authority .= $this->host;
        if ($this->port !== null) {
            $authority .= ":{$this->port}";
        }
        return $authority;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserInfo(): string
    {
        $userInfo = $this->user;
        if ($this->password !== '') {
            $userInfo .= ":{$this->password}";
        }
        return $userInfo;
    }

    /**
     * {@inheritdoc}
     *
     * @return $this
     */
    public function withUserInfo($user, $password = null): self
    {
        $user = $this->filterUser($user);
        $password = $password !== null ? $this->filterPassword($password) : '';

        $uri = clone $this;
        if ($user === '') {
            $uri->user = '';
            $uri->password = '';
            return $uri;
        }
        $uri->user = $user;
        $uri->password = $password;
        return $uri;
    }

    /**
     * {@inheritdoc}
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * {@inheritdoc}
     */
    public function withHost($host): self
    {
        $uri = clone $this;
        $uri->host = $this->filterHost($host);
        return $uri;
    }

    /**
     * {@inheritdoc}
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * {@inheritdoc}
     *
     * @return $this
     */
    public function withPort($port): self
    {
        $uri = clone $this;
        $uri->port = $this->filterPort($port);
        return $uri;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     *
     * @return $this
     */
    public function withPath($path): self
    {
        $uri = clone $this;
        $uri->path = $this->filterPath($path);
        return $uri;
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * {@inheritdoc}
     *
     * @return $this
     */
    public function withQuery($query): self
    {
        $uri = clone $this;
        $uri->query = $this->filterQuery($query);
        return $uri;
    }

    /**
     * {@inheritdoc}
     */
    public function getFragment(): string
    {
        return $this->fragment;
    }

    /**
     * {@inheritdoc}
     *
     * @return $this
     */
    public function withFragment($fragment): self
    {
        $uri = clone $this;
        $uri->fragment = $this->filterFragment($fragment);
        return $uri;
    }

    private function filterScheme($scheme): string
    {
        if (!\is_string($scheme)) {
            throw new \InvalidArgumentException('Uri scheme must be a string');
        }
        return strtolower(rtrim($scheme, ':'));
    }

    public function filterUser($user): string
    {
        if (!\is_string($user)) {
            throw new \InvalidArgumentException('Uri user must be a string');
        }
        return $this->encode($user);
    }

    public function filterPassword($password): string
    {
        if (!\is_string($password)) {
            throw new \InvalidArgumentException('Uri password must be a string');
        }
        return $this->encode($password);
    }

    private function filterHost($host): ?string
    {
        if (!\is_string($host)) {
            throw new \InvalidArgumentException('Uri host must be a string');
        }
        return strtolower($host);
    }

    private function filterPort($port): ?int
    {
        if ($port !== null && !\is_int($port)) {
            throw new InvalidArgumentException('Port needs to be valid integer or null');
        }
        if ($port === null || $port === 0) {
            return null;
        }
        if ($port < 1 || $port > 65535) {
            throw new InvalidArgumentException('Port needs to be a valid TCP/UDP port between 1 and 65535');
        }
        return $port;
    }

    private function filterPath($path): string
    {
        if (!\is_string($path)) {
            throw new \InvalidArgumentException('Path must be a string');
        }

        if (strpos($path, '#') !== false || strpos($path, '?') !== false) {
            throw new InvalidArgumentException('The passed path shouldn\'t contain a query or fragment');
        }

        if ($path === '') {
            return $path;
        }

        return implode('/', array_map([$this, 'encode'], explode('/', $path)));
    }

    private function filterQuery($query): string
    {
        if (!\is_string($query)) {
            throw new \InvalidArgumentException('Query must be a string');
        }

        if ($query === '') {
            return $query;
        }

        if (strpos($query, '?') === 0) {
            $query = substr($query, 1);
        }

        $pairs = explode('&', $query);
        //We don't resolve [ and ] in parameters, this is something the application should do
        foreach ($pairs as $i => $pair) {
            [$key, $value] = array_pad(explode('=', $pair), 2, null);
            if ($value === null) {
                $pairs[$i] = $this->encode($key, true);
                continue;
            }
            $pairs[$i] = $this->encode($key, true).'='.$this->encode($value, true);
        }
        return implode('&', $pairs);
    }

    private function filterFragment($fragment): string
    {
        if (!\is_string($fragment)) {
            throw new \InvalidArgumentException('Fragment must be a string');
        }
        if ($fragment === '') {
            return $fragment;
        }

        if (strpos($fragment, '#') === 0) {
            $fragment = substr($fragment, 1);
        }
        return $this->encode($fragment);
    }

    /**
     * Encodes a value and makes sure it's not double-encoded
     *
     * The following characters DON'T get encoded:
     * a-z, A-Z, 0-9, _, -, ., ~, +, ;, ,, =, $, &, %, :, @, /, ?
     *
     * If the second parameter is passed, the characters
     * !, ', (, ) and * won't be encoded as well
     *
     * @param string $value the value to encode
     * @param bool|false $withDelimeters Allow extended delimeters
     *
     * @return string the encoded value
     */
    private function encode(string $value, bool $withDelimeters = false): string
    {
        $delims = $withDelimeters ? '!\'\(\)\*' : '';
        return preg_replace_callback(
            '/(?:[^a-zA-Z0-9_\-\.~\+;,=\$&%:@\/\?'.$delims.']+|%(?![A-Fa-f0-9]{2}))/',
            function ($matches) {
                return rawurlencode($matches[0]);
            },
            $value
        );
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        if ($this->uriString !== null) {
            return $this->uriString;
        }

        $scheme = $this->getScheme();

        $authority = $this->getAuthority();
        if ($authority !== '') {
            $authority = "//{$authority}";
        }

        $path = $this->getPath();
        if ($path !== '' && $authority === '' && $scheme === 'file') {
            $path = "//{$path}";
        }

        if ($scheme !== '') {
            $scheme .= ':';
        }

        $query = $this->getQuery();
        if ($query !== '') {
            $query = "?{$query}";
        }

        $fragment = $this->getFragment();
        if ($fragment !== '') {
            $fragment = "#{$fragment}";
        }

        $this->uriString = implode('', [$scheme, $authority, $path, $query, $fragment]);
        return $this->uriString;
    }

    /**
     * Makes sure that the cached string-representation
     * of the current URI instance is reset upon cloning.
     */
    public function __clone()
    {
        $this->uriString = null;
    }
}
