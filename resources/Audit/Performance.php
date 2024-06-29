<?php

namespace Resources\Audit;

class Performance
{
    private $starttime;
    private $breakpoints = [];
    public function __construct()
    {
        $this->starttime = microtime(true);
    }
    public function addBreakpoint($label)
    {
        $timestamp = microtime(true);
        $elapsedtime = $timestamp - $this->starttime;
        $timeinterval = $this->breakpoints ? $timestamp - end($this->breakpoints)["timestamp"] : $elapsedtime;
        ["timestamp"];
        reset($this->breakpoints);
        array_push(
            $this->breakpoints,
            [
                "label" => $label,
                "timestamp" => $timestamp,
                "elapsed time" => $elapsedtime,
                "time interval" => $timeinterval
            ]
        );
    }
    public function getTimeInterval()
    {
        $timeinterval["Start"] = 0.00;
        foreach ($this->breakpoints as $breakpoint) {
            $timeinterval[$breakpoint["label"]] = $breakpoint["time interval"];
        }
        return $timeinterval;
    }
    public function getElapsedTime()
    {
        $elapsedtime["Start"] = 0.00;
        foreach ($this->breakpoints as $breakpoint) {
            $elapsedtime[$breakpoint["label"]] = $breakpoint["elapsed time"];
        }
        return $elapsedtime;
    }
    public function getTimestamp()
    {
        $timestamp["Start"] = 0.00;
        foreach ($this->breakpoints as $breakpoint) {
            $timestamp[$breakpoint["label"]] = $breakpoint["timestamp"];
        }
        return $timestamp;
    }
}