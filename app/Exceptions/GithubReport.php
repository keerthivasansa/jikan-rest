<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use PackageVersions\Versions;
use Predis\Connection\ConnectionException;

/**
 * Class GithubReport
 * @package App\Exceptions
 */
class GithubReport
{
    /**
     * @var string
     */
    private string $name;
    /**
     * @var string
     */
    private string $code;
    /**
     * @var string
     */
    private string $error;

    /**
     * @var string
     */
    private string $requestUri;
    /**
     * @var string
     */
    private string $requestMethod;

    /**
     * @var string
     */
    private string $repo;

    /**
     * @var string
     */
    private string $trace;

    /**
     * @var string
     */
    private string $jikanVersion;

    /**
     * @var string|bool
     */
    private string|bool $redisRunning = false;

    /**
     * @var string
     */
    private string $instanceType;

    /**
     * @var string
     */
    private string $phpVersion;

    /**
     * @param \Exception $exception
     * @param Request $request
     * @param string|null $repo
     * @return GithubReport
     */
    public static function make(\Throwable $exception, Request $request, ?string $repo = null) : self
    {
        $report = new self;
        $report->name = \get_class($exception);
        $report->code = $exception->getCode();
        $report->error = $exception->getMessage();
        $report->trace = "{$exception->getFile()} on line {$exception->getLine()}";
        $report->repo = $repo ?? env('GITHUB_REST', 'jikan-me/jikan-rest');
        $report->requestUri = $request->getRequestUri();
        $report->requestMethod = $request->getMethod();
        $report->jikanVersion = Versions::getVersion('jikan-me/jikan');
        $report->phpVersion = PHP_VERSION;

        $report->redisRunning = false;
        if (env('CACHING') && strtolower(env('CACHE_DRIVER')) === 'redis') {
            try {
                $report->redisRunning = trim(app('redis')->ping()) === 'PONG' ? "Connected" : "Disconnected";
            } catch (ConnectionException $e) {
                $report->redisRunning = false;
            }
        }

        $report->instanceType = 'UNKNOWN';
        if (env('APP_ENV') !== 'testing') {
            if (array_key_exists('SERVER_NAME', $_SERVER)) {
                $report->instanceType = $_SERVER['SERVER_NAME'] === 'api.jikan.moe' ? 'OFFICIAL' : 'HOSTED';
            }
            else {
                $report->instanceType = 'HOSTED-RR';
            }
        }

        return $report;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        $title = urlencode("[{$this->instanceType}] Generated Issue: {$this->getClassName()}");
        $body = urlencode(
            "Please fill out the details below.\n\n**Summary:**\n\n**Steps to reproduce:**\n\n\n\n ### Additional Details \n **Jikan Parser Version**: ```{$this->jikanVersion}```\n**PHP:** ```{$this->phpVersion}```\n**Redis**: ```{$this->redisRunning}```\n**Exception:** ```{$this->name}```\n**Code:** ```{$this->code}```\n**Message:** ```{$this->error}```\n**Trace:** ```{$this->trace}```\n**Request:** `{$this->requestMethod} {$this->requestUri}`\n"
        );

        // https://github.com/jikan-me/jikan-rest/issues/new?assignees=&labels=i%3A+bug%2C+i%3A+needs+triage&template=bug.md&title=
        return "https://github.com/{$this->repo}/issues/new?title={$title}&body={$body}";
    }

    /**
     * @return string
     */
    public function getClassName() : string
    {
        $path = explode('\\', $this->name);
        return array_pop($path);
    }

    /**
     * @param string $name
     * @return GithubReport
     */
    public function setName(string $name): GithubReport
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $code
     * @return GithubReport
     */
    public function setCode(string $code): GithubReport
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @param string $error
     * @return GithubReport
     */
    public function setError(string $error): GithubReport
    {
        $this->error = $error;
        return $this;
    }

    /**
     * @param string $repo
     * @return GithubReport
     */
    public function setRepo(string $repo): GithubReport
    {
        $this->repo = $repo;
        return $this;
    }

    /**
     * @param string $trace
     * @return GithubReport
     */
    public function setTrace(string $trace): GithubReport
    {
        $this->trace = $trace;
        return $this;
    }

    /**
     * @param string $jikanVersion
     * @return GithubReport
     */
    public function setJikanVersion(string $jikanVersion): GithubReport
    {
        $this->jikanVersion = $jikanVersion;
        return $this;
    }

    /**
     * @param string $redisRunning
     * @return GithubReport
     */
    public function setRedisRunning(string $redisRunning): GithubReport
    {
        $this->redisRunning = $redisRunning;
        return $this;
    }

    /**
     * @param string $instanceType
     * @return GithubReport
     */
    public function setInstanceType(string $instanceType): GithubReport
    {
        $this->instanceType = $instanceType;
        return $this;
    }
}
