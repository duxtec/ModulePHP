<?php

namespace Resources\Utils;

class Response implements \ArrayAccess
{
    protected mixed $data;
    protected bool $success;
    protected string $error;
    protected int $code;

    /**
     * Constructor for Response class.
     *
     * @param string|array|self $messageOrArray The message or array of data.
     * @param int|null $code The error code (optional).
     */
    public function __construct(mixed $errorOrData = null, bool $success = true)
    {
        $this->success = $success;
        
        // Initialize data array
        if ($this->success) {
            $this->data = $errorOrData;
        } else {
            $this->error = $errorOrData;
        }
    }

    /**
     * Generate a successful response object.
     *
     * @param string|array|self $messageOrArray The message or array of data.
     * @param int|null $code The error code (optional).
     * @return self The Response object.
     */
    public static function Success(string|array|self $messageOrArray = [], ?int $code = null): self
    {
        $self = new self($messageOrArray);
        if ($code !== null) {
            $self->setCode($code);
        }
        return $self;
    }

    /**
     * Generate an error response object.
     *
     * @param string|array|self $messageOrArray The message or array of data.
     * @param int|null $code The error code (optional).
     * @return self The Response object.
     */
    public static function Error(string|array|self $messageOrArray = [], ?int $code = null): self
    {
        $self = new self($messageOrArray, false);
        if ($code !== null) {
            $self->setCode($code);
        }
        return $self;
    }

    /**
     * Generate an error response object.
     *
     * @param \Throwable $throwable The throwable.
     * @param int|null $code The error code (optional).
     * @return self The Response object.
     */
    public static function ErrorThrowable(\Throwable $throwable, ?int $code = null): self
    {
        $message = "{$throwable->getMessage()}\n{$throwable->getFile()}({$throwable->getLine()})\n{$throwable->getTraceAsString()}";
        $self = new self($message, false);
        if ($code !== null) {
            $self->setCode($code);
        }
        return $self;
    }

    /**
     * Set the success flag and update the data array.
     *
     * @param bool $success The success flag.
     * @return self The Response object.
     */
    private function setSuccess(bool $success): self
    {
        $this->success = $success;
        return $this;
    }

    /**
     * Set the message and update the data array.
     *
     * @param string $message The message.
     * @return self The Response object.
     */
    private function setError(string $error): self
    {
        $this->error = $error;
        $this->data["error"] = $error;
        return $this;
    }

    /**
     * Set the error code and update the data array.
     *
     * @param int $code The error code.
     * @return self The Response object.
     */
    private function setCode(int $code): self
    {
        $this->code = $code;
        $this->data["code"] = $code;
        return $this;
    }

    /**
     * Check if an offset exists in the data array.
     *
     * @param mixed $offset The offset to check.
     * @return bool Whether the offset exists.
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->data[$offset]);
    }

    /**
     * Get the value at a given offset in the data array.
     *
     * @param mixed $offset The offset to retrieve.
     * @return mixed The value at the given offset.
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->data[$offset];
    }

    /**
     * Set the value at a given offset in the data array.
     *
     * @param mixed $offset The offset to set.
     * @param mixed $value The value to set.
     * @return void
     * @throws \RuntimeException If attempting to modify properties.
     */
    public function offsetSet($offset, $value): void
    {
        throw new \RuntimeException("Modifying properties of Response class is not allowed.");
    }

    /**
     * Unset the value at a given offset in the data array.
     *
     * @param mixed $offset The offset to unset.
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }

    public function getSuccess(): bool
    {
        return $this->success;
    }

    public function getData(): mixed
    {
        return $this->data;
    }
    public function getError(): string
    {
        return $this->error;
    }
}